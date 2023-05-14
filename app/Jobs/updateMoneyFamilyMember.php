<?php

namespace App\Jobs;

use App\Models\FamilyMember;
use App\Traits\FamilyMemberServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class updateMoneyFamilyMember implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,FamilyMemberServices;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $members = FamilyMember::all();
        
        foreach($members as $member)
        {
                if($this->diff_months($member) > 0)
                {
                    $member->amount_added = now();
                    $member->spent_money = 0;
                    $member->save();
                }
        }
    }
}
