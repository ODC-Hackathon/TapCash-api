<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\FamilyServiceRequest;
use App\Http\Requests\api\UpdateFamilyMember;
use App\Http\Requests\StoreFamilyRequest;
use App\Models\FamilyMember;
use App\Models\SubCategory;
use App\Models\TransactionDetail;
use App\Models\UserNotification;
use App\Traits\FamilyMemberServices;
use App\Traits\FamilyMemberTrait;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;

class FamilyController extends BaseController
{
    use FamilyMemberServices;
    public function CreateService(FamilyServiceRequest $request)
    {

        try
        {

            $member = FamilyMember::where('id',$request->user()->id)->first();
            if($request->amount > $this->get_remainingMoney($member))
                return $this->error('Sorry You have spent The amount allowed u in this month');

            $service = SubCategory::find($request->service);
            $transaction  = $member->sponser->withdraw($request->amount);

            $this->send_notification($member,$transaction,$service);
            $this->create_transaction($member,$transaction,$service);
            $this->money_update($member,$request->amount);

            return $this->success([
                'message' => 'You have paied for this service '.$service->name
            ]);

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

    public function update(UpdateFamilyMember $request)
    {

            $family = FamilyMember::find($request->user()->id);
            $family->name = $request->name;
            $family->phone_number = $request->phone_number;
            $family->pincode = $request->pincode;
            $family->save();

            return $this->success(['message'=>'Updated Successffully']);
    }

    public function get_details(Request $request)
    {
        $family = FamilyMember::where('id',$request->user()->id)
                    ->select('name','user_name','phone_number','spent_money')
                    ->get();
        return $this->success([
            'member' => $family
        ]);
    }

}
