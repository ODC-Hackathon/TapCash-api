<?php
namespace App\Traits;

use App\Models\FamilyMember;
use App\Models\SubCategory;
use App\Models\TransactionDetail;
use App\Models\UserNotification;
use Carbon\Carbon;

trait FamilyMemberServices
{
        public function diff_months(FamilyMember $member)
        {
            $current_date = Carbon::now();
            $member_created_at = Carbon::parse($member?->amount_added);
            $diff_months =  $member_created_at->diffInMonths($current_date);

            return $diff_months;
        }

        public function send_notification(FamilyMember $member , $transaction ,SubCategory $service)
        {
            // $this->create_transaction($member,$transaction,$service);

            $notification = UserNotification::create([
                'message' => $member->user_name  . ' has bought new '.$service->name . ' with $' . abs($transaction->amount)  .' At '. now()->format('d-m-y'),
                'type' =>'purchased',
                'user_id' => $member->sponser->id,
                'transaction_id'=>$transaction->id,
            ]);


            $notification = UserNotification::create([
                'message' => 'You have purchased '.$service->name . ' with $' . abs($transaction->amount)  .' At '. now()->format('d-m-y'),
                'type' =>'purchased',
                'family_id' => $member->id,
                'transaction_id'=>$transaction->id,
            ]);
        }


        public function create_transaction(FamilyMember $member , $transaction ,SubCategory $service)
        {

            $transaction_detail = TransactionDetail::create([
                'type'=>$service->name,
                'transaction_id'=>$transaction->id,
                'subcategory_id'=>$service->id,
                'familymember_id' => $member->id,
            ]);

        }



        public function money_update(FamilyMember $member , $amount)
        {
            $member->spent_money =  $member->spent_money + $amount;
            $member->save();
        }

        public function get_remainingMoney(FamilyMember $member)
        {
            return $member->allowed_money - $member->spent_money;
        }
}
