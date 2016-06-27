<?php

namespace App\Http\Middleware;

use App\Models\IPRole;
use App\Models\Role;

use Closure;
use Auth;

class CheckIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!env('CHECK_IP','false'))
            return $next($request);


        if ( IPRole::checkIP($request->ip()) ){
            return $next($request);       
        } else {
            Auth::logOut();
            return response([
                'error' => [
                    'code' => 'INSUFFICIENT_ROLE',
                    'description' => 'You are not authorized to access this resource.'
                ]
            ], 401);
        }
     
        return $next($request);
    }

}
