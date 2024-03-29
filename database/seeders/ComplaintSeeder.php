<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CommentHelper;
use App\Models\UserHelper;
use App\Models\PostHelper;
use DB;
use Faker\Factory;


class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $faker=Factory::create();
        //USER
        for($i=0; $i<10 ;$i++)
        {
        DB::table('user_helper')->insert([
            'user_id'=>rand(5,9),
            'content'=>$faker->paragraph(2, true),
        ]);
    }
        //POST
        for($i=0; $i<10 ;$i++)
        {
        DB::table('post_helper')->insert([
            'post_id'=>rand(1,5),
            'content'=>$faker->paragraph(2, true),
        ]);
         }
        //COMMENT
        for($i=0; $i<15 ;$i++)
        {
        DB::table('comment_helper')->insert([
            'content'=>$faker->paragraph(2, true),
            'comment_id'=>rand(1,12),
          //  'user_id'=>Auth::id()
        ]);
    }
        //User
    }
}
