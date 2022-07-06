<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\User; 
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Factory::create();
        for($i=0; $i<10 ;$i++)
        {
            $user=User::create([
                'firstname'=>$faker->firstname,
                'lastname'=>$faker->lastname,
                'username'=>$faker->username,
                'phone'=>'9639'.random_int(10000000,99999999),
                'address'=>$faker->address,
                'city'=>$faker->country,
                'country'=>$faker->country,
                'email'=>$faker->email,
                'email_verified_at'=>Carbon::now(),
                'password'=>bcrypt('12345678'),
                'c_password'=>bcrypt('12345678'),
              //  'profile_image' => $faker->image(public_path('images'),400,300, null, false)
               // 'profile_image' => $faker->image('public/storage/images',640,480, null, false),
            ]);
        }
    }
}
