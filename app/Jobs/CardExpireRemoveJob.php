<?php

namespace App\Jobs;

use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CardExpireRemoveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        //

        $cards = Card::all();

        foreach($cards as $card)
        {
            $current_date = Carbon::now();
            $card_expiration_date = $card->expiration_date;
            if($card_expiration_date < $current_date)
            {
                $card->delete();
            }
        }
    }
}
