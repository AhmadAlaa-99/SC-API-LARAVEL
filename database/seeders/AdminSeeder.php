<?php

namespace Database\Seeders;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\User; 


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::Create([
                 'fullname'=>'abdullah',
                 'email'=>'abdullah@gmail.com',
                 'email_verified_at'=>Carbon::now(),
                 'role_as'=>'1',
                 'password'=>bcrypt('12345678'),
                 'c_password'=>bcrypt('12345678'),
        ]);
    }
    
}
