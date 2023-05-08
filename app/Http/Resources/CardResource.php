<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Crypt;

class CardResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cards = [];
        foreach($this->collection as $card)
        {
                array_push($cards,[
                    'card_no' =>$card->card_no,
                    'type' =>$card->type,
                    'cvv'=>$card->cvv,
                    'expiration_date'=>Carbon::parse($card->expiration_date)->format('d-m-y'),
                ]);
        }
        return $cards;
    }
}
