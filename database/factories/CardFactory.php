<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $types = ['MasterCard','Visa','American Express'];
        $key = array_rand($types);
        return [
            //
            'type'=> $types[$key],
            'card_no'=> $this->faker->creditCardNumber($types[$key]),
            'user_id'=>User::factory(),
            'cvv'=>rand(100,999),
            'expiration_date'=>Carbon::now()->addDay(),
        ];
    }
}
