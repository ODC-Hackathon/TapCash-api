<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CardController extends BaseController
{
    //

    public function create(CardRequest $request)
    {
        $this->authorize('create',Card::class);

        $faker = Container::getInstance()->make(Generator::class);

        $card = Card::where('user_id',$request->user()->id)
        ->get();
        
        if(count($card) != 0)
            return $this->success(new CardResource($card));

        $card = Card::create([
            'card_no' =>Crypt::encryptString($faker->creditCardNumber($request->type)),
            'type' =>Crypt::encryptString($request->type),
            'cvv'=>Crypt::encryptString(rand(100, 999)),
            'user_id'=>$request->user()->id,
            'expiration_date'=> Crypt::encryptString(Carbon::now()->addDay()),
        ]);

        $card = $card->select('card_no','type','cvv','expiration_date')->get();

        return $this->success(new CardResource($card));

    }
    public function get_card(Request $request)
    {
        $card = Card::where('user_id',$request->user()->id)
        ->get();

        $this->authorize('view',Card::class);

        if(count($card) !=0)
            return $this->success(new CardResource($card));

        return $this->error('No Data found',404);
    }
}
