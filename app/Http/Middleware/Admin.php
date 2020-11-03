<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        elseif(Auth::user()->is_admin){
            return $next($request);
        }
        elseif(!Auth::user()->is_admin){
            abort(403, 'Not Authorized for this action');
        }  
    }
}
