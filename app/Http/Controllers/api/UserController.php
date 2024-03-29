<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\UpdateUserAccountData;
use App\Http\Requests\api\UserServiceRequest;
use App\Http\Requests\api\UserUpdateMemberRequest;
use App\Http\Requests\CreateFamilyMember;
use App\Models\FamilyMember;
use App\Models\MemberPermission;
use App\Models\PinCodeReset;
use App\Models\SubCategory;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
class UserController extends BaseController
{
    public function get_users(Request $request)
    {
        $users = User::select('user_name')->get();
        return $this->success($users);
    }

    public function storeFamilyMember(CreateFamilyMember $request)
    {
        try
        {
            DB::beginTransaction();
            $user  = User::where('id',$request->user()->id)->first();

            $member = FamilyMember::create([
                'sponsor_id'=>$user->id,
                'user_name' => $request->user_name,
                'phone_number' => $request->phone_number,
                // 'percentage' => $request->percentage,
                'pincode' => $request->pincode,
                'name'=> $request->name,
                // 'total_amount' => $user->balance * ($request->percentage / 100),
                'allowed_money' => $request->amount,
                'amount_added' => now(),
            ]);

            MemberPermission::create([
                'member_id' =>$member->id,
                'permissions' => json_decode($request->permissions)
            ]);

            DB::commit();
            return $this->success([
                'message'=>'Family has been created'
            ]);
        }catch(Exception $e)
        {
            DB::rollBack();
            return $this->error($e->getMessage());
        }



    }

    public function CreateService(UserServiceRequest $request)
    {
        try
        {
            $user  = User::where('id',$request->user()->id)->first();
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

            return $this->success(['message'=>'the service has been bought successfully']);
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


    public function get_member_transaction(Request $request,FamilyMember $member)
    {
        try
        {
            $transactions = FamilyMember::where('id',$member->id)->with(['transaction_details'])
            ->select('id','user_name')
            ->first();
            return $this->success($transactions);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }

        return $transactions;
    }

    public function getBalance(Request $request)
    {
        return User::find($request->user()->id)->balance;
        return $this->success($request->user->balance);
    }


    public function get_UserData(Request $request)
    {
        try
        {
            $user = User::where('id',$request->user()->id)
            ->select('name','email','phone_number')
            ->first();
            return $this->success($user);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }
    }

    public function update_user_accountData(UpdateUserAccountData $request,User $user)
    {
        if(!Hash::check($request->password,$user->account->password))
            return $this->error('Incorrect password');
        try
        {
            if($request->email !== $user->email)
            {
                $user->account->forceFill([
                    'email'=> $request->email,
                    'email_verified_at' =>null
                ])->save();
                $user->account->sendEmailVerificationNotification();
            }
            $user->name = $request->name;
            $user->phone_number = $request->phone_number;
            $user->email = $request->email;
            $user->save();

            return $this->success([
                'message' => 'Informations has been updated successfully'
            ]);

        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }
    }
    public function get_MemberData(Request $request , FamilyMember $family)
    {
            return $this->success(
                $family->where('id',$family->id)->select('name','allowed_money','spent_money')->get()
            );
    }
    public function update_memberData(UserUpdateMemberRequest $request , FamilyMember $family)
    {

        $family->allowed_money = $request->amount;
        if($request->pincode !="")
            $family->pincode = $request->pincode;

        if($family->isDirty('allowed_money'))
            $family->amount_added = now();

        $family->save();
        return $this->success([
            'message' => 'Data has been updated successfully'
        ]);
    }

}
