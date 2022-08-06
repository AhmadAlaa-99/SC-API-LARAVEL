<?php

namespace App\Http\Controllers\Helper;

use Illuminate\Http\Request;
use App\Models\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Collection;
use App\Http\Controllers\Controller; 
class HelperController extends Controller
{
    public function login(Request $request)
    {
        $validator=Validator::make(
            $request->all(),
            [
                'email'=>'required|exists:helpers,email',
                'password'=>'required|min:8|max:60',
             ]);

            if ($validator->fails())
            {
                return $this->sendError($validator->errors()->first());
                //return $this->sendError('Validator Error', $validator->errors());
             }
            $helper=Helper::where('email',$request->email)->get();
            return      $success['token'] = $helper->createToken('helpersocial')->accessToken;
         
            $success['id'] = $heper->id;
            $success['email'] = $helper->email;
            return $this->sendResponse($success, 'login Successfully!');
        }
    }
