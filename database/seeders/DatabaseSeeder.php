<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\HelperSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ComplaintSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
           // 
            AdminSeeder::Class,
            HelperSeeder::Class,
            UserSeeder::class,
            ComplaintSeeder::class
            
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
