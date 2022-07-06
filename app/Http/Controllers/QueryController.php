<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;

class QueryController extends BaseController
{
    /*
     return response()->json([
            'message'=>'register send email',],200);
            */
    public function query()
    {
      //  $users=DB::table('users')->first();
     //   return $users;  
  //   return $users->username;    //error   solve : first
     /*
     foreach ($users as $user)
      {
        echo $user->username;    //  pagac.charlottezetta51wiegand.tristonbrennan65senger.edwquitzonbarney2

    }
    */
    /*
    $user = DB::table('users')->where('name', 'John')->first();
     return $user->email;
*/
$email = DB::table('users')->where('username', 'pagac.charlotte')->value('email');
//return $email;  
$user = DB::table('users')->find(3);
//return $user;   //return infuser not need get
$emails = DB::table('users')->pluck('email','username');
foreach ($emails as $email) {
echo $this->sendResponse($email, 'true');
}

DB::table('users')->orderBy('id')->chunk(100, function ($users) {
    foreach ($users as $user) {
    //
    }
    });

    DB::table('users')->orderBy('id')->chunk(100, function ($users) {
        // Process the records...
        return false;
        });

        DB::table('users')->where('active', false)
->chunkById(100, function ($users) {
foreach ($users as $user) {
DB::table('users')
->where('id', $user->id)
->update(['active' => true]);
}
});
/*
NOTE When updating or deleting records inside the chunk callback, any changes to the primary key or
foreign keys could affect the chunk query. This could potentially result in records not being included in the
chunked results.
*/
/*
//Lazy :
//eager :
//n+1 :
*/
//max, min, avg,sum
$users = DB::table('users')->count();
$price = DB::table('orders')->max('price');
$price = DB::table('orders')
->where('finalized', 1)
->avg('price');
   //Instead of using the count method to determine if any records exist that match your query's constraints, you
//may use the exists and doesntExist methods:

if (DB::table('orders')->where('finalized', 1)->exists()) {
    // ...
    }
    if (DB::table('orders')->where('finalized', 1)->doesntExist()) {
    // ...
    }
    

//SELECT 
$users = DB::table('users')
->select('name', 'email as user_email')
->get();


$users = DB::table('users')->distinct()->get(); //

$query = DB::table('users')->select('name');
$users = $query->addSelect('age')->get();

//RAW (need learn statement sql)
  //EXPRESIION 
  //METHODS

//joins 
//inner join
$users=DB::table('users')->join('comments','users.id','=','comments.user_id')
                         ->join('likes','users.id','=','likes.user_id')
                        ->select('users.*','comments.text','likes.nameUser')
                        ->get();

//left right 
$users = DB::table('users')
->leftJoin('posts', 'users.id', '=', 'posts.user_id')
->get();
$users = DB::table('users')
->rightJoin('posts', 'users.id', '=', 'posts.user_id')
->get();

//crossJOIN : Illuminate\Database\Query\JoinClause
/*
$sizes = DB::table('sizes')
->crossJoin('colors')
->get();

DB::table('users')
->join('contacts', function ($join) {
$join->on('users.id', '=', 'contacts.user_id')
->orOn(...);
})
->get();
//
DB::table('users')
->join('contacts', function ($join) {
$join->on('users.id', '=', 'contacts.user_id')
->where('contacts.user_id', '>', 5);
})
->get();

*/

//Subquery Joins  : 


    



//UNIONS




   }
}
