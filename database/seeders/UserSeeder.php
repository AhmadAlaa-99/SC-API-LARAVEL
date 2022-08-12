<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\User; 
use App\Models\Post; 
use App\Models\Comment; 
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

          //User 
        for($i=0; $i<5 ;$i++)
        {
            $user=User::create([
                'fullname'=>$faker->username,
                'email'=>$faker->email,
                'email_verified_at'=>Carbon::now(),
                'password'=>bcrypt('12345678'),
                'c_password'=>bcrypt('12345678'),
            ]);
        }
        //Post
             for($i=0; $i<5 ;$i++)
        {
               $post=Post::create([
                'user_id'=>$i,
                'Content'=>$faker->paragraph(),
                //public_path().'/upload/Post_images/'.$image_name;   $image_name = time() . '.' . $request->photo->extension();
              //  'photo'=>public_path().'/upload/Post_images'.$faker->image('public/images'),300,300),
            ]);
        }
        //Comment
        for($i=0; $i<20 ;$i++)
        {
               $comment=Comment::create([
                'user_id'=>rand(1,5),
                'post_id'=>rand(1,5),
                'comment'=>$faker->paragraph(2, true),
            ]);
        }
       
    }
}
