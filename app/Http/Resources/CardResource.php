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
                    'card_no' =>Crypt::decryptString($card->card_no),
                    'type' =>Crypt::decryptString($card->type),
                    'cvv'=>Crypt::decryptString($card->cvv),
                    'expiration_date'=> Carbon::parse(Crypt::decryptString($card->expiration_date))->format('d-m-y'),
                ]);
        }
        return $cards;
    }
}
