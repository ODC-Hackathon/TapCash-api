<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\PaymentMethodType;
use App\Models\SubCategory;
use App\Models\UserNotification;
use Exception;
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
        // $account = \App\Models\Account::factory(1)->create();
        // \App\Models\FamilyMember::factory(1)->create(
        //     [
        //         'sponsor_id' =>Account::find(1)->user_id
        //     ]
        // );
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
            // \App\Models\User::factory()->create([
            //     'name' => fake()->name(),
            //     'email' => fake()->unique()->safeEmail(),
            //     'email_verified_at' => now(),
            //     'password' => 'TabCash@2023', // password
            //     'remember_token' => Str::random(10),
            //     'phone_number'=>'01550781783',
            //     'user_name'=>fake()->unique()->name(),
            // ]);

            // \App\Models\User::factory(1)->create();

            $this->Fill_category();
            $this->Fill_Payments_methods_type();

    }
    protected function Fill_category()
    {
        $ararys = ['Housing','Utilities','Food','Transportation','Insurance','Healthcare'];
        $ararys_sub = ['Furnishings','Electricity','Groceries','Car payment','Health insurance','Urgent care'];
        $index=0;
        foreach($ararys as $field)
        {
            $category = Category::create([
                'name'=>$field,
                'description' => str::random(10),
            ]);

            SubCategory::create([
                'name'=>$ararys_sub[$index],
                'description'=>str::random(10),
                'category_id' =>$category->id
            ]);

            $index++;
        }
    }

    public function Fill_Payments_methods_type()
    {
        $methods = array('Vodafone Cash','Etisalat Cash','Orange Cash','Visa','Fawry','Request');
        foreach($methods as $method)
        {
            try{
                \App\Models\PaymentMethodType::create([
                    'name'=>$method
                ]);
            }catch(Exception $e)
            {

            }
        }
    }
}
