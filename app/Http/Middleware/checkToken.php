<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->role_as==0)
        {
            return response()->json([
                'status'=>'205',
                'message'=>'User'
            ]);
        }
        if(auth()->user()->role_as==1)
        {
            return response()->json([
                'status'=>'205',
                'message'=>'Admin'
            ]);
        }
        if(auth()->user()->role_as==2)
        {
            return response()->json([
                'status'=>'205',
                'message'=>'Helper User'
            ]);
        }
        if(auth()->user()->role_as==3)
        {
            return response()->json([
                'status'=>'205',
                'message'=>'Helper Post'
            ]);
        }
        if(auth()->user()->role_as==4)
        {
            return response()->json([
                'status'=>'205',
                'message'=>'Helper Comment'
            ]);
        }
    }
}
