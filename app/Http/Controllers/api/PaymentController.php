<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Payment_Method_Type;
use App\Models\PaymentMethodType;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserNotification;
use Bavix\Wallet\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaymentController extends BaseController
{
    //


    public function get_payment_methods()
    {
        $methods = Cache::rememberForever('payment-method-types', fn()=> PaymentMethodType::all());

        return $this->success($methods );
    }

    public function AddMoney(PaymentRequest $request)
    {
        $user = $request->user();
        try
        {
            $confirmed=true;
            $notifcation = null;
            if($request->type ==='Request')
                $confirmed=false;

            $transaction = $user->deposit($request->amount,null,$confirmed);
            $transaction_details = TransactionDetail::create([
                'type' => $request->type,
                'transaction_id'=>$transaction->id
            ]);

            if($request->type ==='Request')
            {
                $notifcation = UserNotification::create([
                    'user_id' => User::where('user_name',$request->user_name)->first()->id,
                    'type' => $request->type,
                    'transaction_id'=>$transaction->id,
                    'message'=> $user->user_name .' Requested '.$request->amount .'$  From You at ' .now()->format('d-m-y'),
                ]);
            }

            return $this->success($notifcation);
        }catch (Exception $e)
        {
            return $this->error('Something went Wrong',422);
        }

    }

    public function ConfirmPayment(UpdateNotificationRequest $request)
    {
        try
        {
            $notification = UserNotification::find($request->notification);
            $transaction = $notification->transaction;
            $user = $request->user();
            $user->deposit(5000);
            $user->transfer($transaction->wallet,$transaction->amount);
            $transaction = Transaction::find($notification->transaction_id);
            $notification->transaction_id = null;
            $notification->is_read = true;
            $notification->save();

            $transaction->delete();
            return $this->success(null);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }
    }


    public function SendMoney(Request $request)
    {
        $request->validate([
            'user_name' => ['required','exists:users,user_name'],
            'amount'=>['required','numeric','min:1'],
        ]);

        try
        {
            $reciever = User::where('user_name',$request->user_name)->first();
            $user = $request->user();

            $transaction = $user->transfer($reciever,$request->amount);
            UserNotification::create([
                'type'=>'payment',
                'message'=>$user->user_name.' '.' has Sent You $ ', $request->amount ,' at '.now()->format('d-m-y'),
                'user_id'=>$reciever->id
            ]);

            return $this->success(null);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }


    }



}
