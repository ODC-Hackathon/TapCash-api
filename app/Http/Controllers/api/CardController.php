<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Support\Carbon;

class CardController extends Controller
{
    //


    public function generate(Request $request)
    {
        $faker = Container::getInstance()->make(Generator::class);


        $card = Card::create([
            'card_no' =>$faker->creditCardNumber($request->type),
            'type' =>$request->type,
            'cvv'=>rand(100, 999),
            'user_id'=>$request->user()->id,
            'expiration_date'=> Carbon::now()->addDay(),
        ]);
        $data=array(
            'data'=>$card
        );
        return response($data,200);

    }
    public function get_card(Request $request)
    {
        $card = $request->user()->card;

        $data=array(
            'data'=>$card
        );
        return response($data,200);
    }
}
