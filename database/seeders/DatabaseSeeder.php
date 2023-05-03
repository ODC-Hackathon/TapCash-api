<?php

namespace Database\Seeders;

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

        // \App\Models\Card::factory(1)->create();
        // \App\Models\SubCategory::factory(10)->create();
        // \App\Models\Payment_Method_Type::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
            \App\Models\Payment_Method_Type::factory()->create([
                'name' => 'VodafoneCash',
            ]);
            \App\Models\Payment_Method_Type::factory()->create([
                'name' => 'EtisalateCash',
            ]);
            \App\Models\Payment_Method_Type::factory()->create([
                'name' => 'VisaCard',
            ]);
    }
}
