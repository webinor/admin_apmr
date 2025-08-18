<?php

namespace App\Http\Middleware;

use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotVerify
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
        $userAgent = $request->userAgent();
        $user = User::whereId(Auth::user()->id)
        ->with('login_details:id,user_id,user_agent')
        ->with('employee')
        ->first();

        $login_details = $user->login_details->count();
                

      //  dd($user);//->login_details->pluck('user_agent'));
      //  dd(User::find(2));//->login_details->pluck('user_agent'));
       // dd(in_array($userAgent , $user->login_details->pluck('user_agent')->toArray() ));

                if ($login_details == 0  || ($login_details > 0 &&  ! in_array($userAgent , $user->login_details->pluck('user_agent')->toArray() )) ) {
                    
                    return redirect('verify-account/'.$user->employee->code);
                    
                }
        
        return $next($request);
    }
}
