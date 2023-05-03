<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FamilyMember>
 */
class FamilyMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'name'=>$this->faker->name(),
            'sponsor_id'=>User::factory(),
            'user_name'=>$this->faker->unique()->name(),
            'phone_number'=>'01102154877',
            'password'=>'TabCash@2023',
            'total_amount'=>$this->faker->numberBetween(100,900),
            'percentage'=>$this->faker->numberBetween(1,10),
        ];
    }
}
