<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\FamilyServiceRequest;
use App\Http\Requests\StoreFamilyRequest;
use App\Models\FamilyMember;
use App\Models\SubCategory;
use App\Models\TransactionDetail;
use App\Models\UserNotification;
use Exception;
use Illuminate\Http\Request;

class FamilyController extends BaseController
{

    public function CreateService(FamilyServiceRequest $request)
    {


        try
        {
            $member = FamilyMember::where('user_name',$request->user_name)->first();
            $service = SubCategory::find($request->service);
            $transaction  = $member->sponser->withdraw(50);

            $transaction_detail = TransactionDetail::create([
                'type'=>$service->name,
                'transaction_id'=>$transaction->id,
                'subcategory_id'=>$request->service,
                'familymember_id' => $member->id,
            ]);

            $notification = UserNotification::create([
                'message' => $member->user_name  . ' has bought new '.$service->name .' At '. now()->format('d-m-y'),
                'type' =>'purchased',
                'user_id' => $member->sponser->id,
                'transaction_id'=>$transaction->id,
            ]);

            $notification = UserNotification::create([
                'message' => 'You have purchased '.$service->name .' At '. now()->format('d-m-y'),
                'type' =>'purchased',
                'family_id' => $member->id,
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
            $transactions = FamilyMember::where('id',$user->id)
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
