<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory;
use App\Models\User; 
class HelperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $helper=User::create([
                 'fullname'=>'Sumbul',
                 'email'=>'sumbul@gmail.com',
                 'email_verified_at'=>Carbon::now(),
                 'role_as'=>'2',
                 'password'=>bcrypt('12345678'),
                 'c_password'=>bcrypt('12345678'),

        ]);
        $helper=User::create([
            'fullname'=>'kroatia',
            'email'=>'kroatia@gmail.com',
            'email_verified_at'=>Carbon::now(),
            'role_as'=>'3',
            'password'=>bcrypt('12345678'),
            'c_password'=>bcrypt('12345678'),
        ]);
            $helper=User::create([
                'fullname'=>'kashmere',
                 'email'=>'kashmere@gmail.com',
                 'email_verified_at'=>Carbon::now(),
                 'role_as'=>'4',
                 'password'=>bcrypt('12345678'),
                 'c_password'=>bcrypt('12345678'),
             
            ]);
    }
}
