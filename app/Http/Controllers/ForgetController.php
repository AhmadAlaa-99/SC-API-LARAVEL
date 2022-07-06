<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\User;
use DB;
class ForgetController extends Controller
{
    public function forget(ForgetRequest $request, $exception)
    {
        /*
    }
        $email = $request->input(key: 'email');
        if (User::where('email', $email)->doesntExist()) {
            return response()->json([
                'message' => 'EMAIL NOT REGISTER',], 200);
        }


        $token = Str::random(length: 10);
        try {
            DB::table(table:'password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);

            //send email
            Mail::send(view:'Mail.forget',['token'=>$token],function(Message $message) use ($email)
            {
                $message->to($email);
                $message->subject(subject:'Reset your password');

            });
            return response()->json([
                'message' => 'CHECK YOUR INBOX EMAIL',], 200);

        catch (\Exception $exception )
        {

            return response()->json([
                'message' => $exception->getMessage(),], 200);
        }


    }
}
public function reset(ResetRequest $request )
{

    $token=$request->input(key:'token');

    if(!passwordResets=DB::table('password_resets')->where('token',$token)->first())
    {
        return response(
            [
                'message'=>'invalid token'

            ],status:400
        );
    }
     if (!$user=user::where('email',$passwordResets=>email)->first())
         {
             return response([
                 'message'=>'user not found
             ]);
         }

     $user->password=Hash::make($request->input(key:'password'));
     $user->save();

*/
}
}
