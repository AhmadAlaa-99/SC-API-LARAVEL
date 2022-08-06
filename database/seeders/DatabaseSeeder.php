<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\HelperSeeder;
use Database\Seeders\AdminSeeder;
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
           // UserSeeder::class,
            HelperSeeder::Class,
            AdminSeeder::Class,
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
