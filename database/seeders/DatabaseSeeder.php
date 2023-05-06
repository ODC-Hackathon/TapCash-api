<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\FamilyMember::factory(1)->create();
        // \App\Models\SubCategory::factory(10)->create();
        // \App\Models\Payment_Method_Type::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

            // \App\Models\Payment_Method_Type::factory()->create([
            //     'name' => 'VodafoneCash',
            // ]);
            // \App\Models\Payment_Method_Type::factory()->create([
            //     'name' => 'EtisalateCash',
            // ]);
            // \App\Models\Payment_Method_Type::factory()->create([
            //     'name' => 'VisaCard',
            // ]);
            \App\Models\User::factory()->create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => 'TabCash@2023', // password
                'remember_token' => Str::random(10),
                'phone_number'=>'01550781783',
                'user_name'=>fake()->unique()->name(),
            ]);

            $this->Fill_category();

    }
    protected function Fill_category()
    {
        // App\Models\Category::create([
        //     'name'=>fake()->name(),
        //     'description' =>
        // ]);
    }
}
