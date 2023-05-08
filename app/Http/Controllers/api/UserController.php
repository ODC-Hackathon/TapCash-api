<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\UserServiceRequest;
use App\Http\Requests\CreateFamilyMember;
use App\Models\FamilyMember;
use App\Models\SubCategory;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserNotification;
use Exception;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    //


    public function get_users(Request $request)
    {
        $users = User::select('user_name')->get();
        return $this->success($users);
    }

    public function storeFamilyMember(CreateFamilyMember $request)
    {

        try
        {
            FamilyMember::create([
                'sponsor_id'=>User::where('user_name',$request->sponser)->first()->id,
                'user_name' => $request->user_name,
                'phone_number' => $request->phone_number,
                'percentage' => $request->percentage,
                'pincode' => $request->pincode,
                'name'=> $request->name,
            ]);
            return $this->success(null);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }



    }

    public function CreateService(UserServiceRequest $request)
    {
        try
        {
            $user  = User::where('user_name',$request->user_name)->first();
            $service = SubCategory::find($request->service);
            $transaction = $user->withdraw($request->amount);

            $transaction_detail = TransactionDetail::create([
                'type'=>$service->name,
                'transaction_id'=>$transaction->id,
                'subcategory_id'=>$request->service,
                'user_id' => $user->id,
            ]);

            $notification = UserNotification::create([
                'message' => 'You have purchased '.$service->name .' At '. now()->format('d-m-y'),
                'type' =>'purchased',
                'user_id' => $user->id,
                'transaction_id'=>$transaction->id,
            ]);

            return $this->success(null);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }
    }

    public function get_Transactions(Request $request)
    {
        try
        {
            $user = $request->user();
            $transactions = User::where('id',$user->id)
            ->with(['transaction_details'])
            ->select('id','user_name')
            ->first();
            return $this->success($transactions);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }

    }
}
