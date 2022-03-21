<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class tutor
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
        $token = $request->bearerToken();
        $user = User::where('token', $token)->with('role')->first();
        // dd($user->role->name);

        if(!$user || !isset($token))
        {
            abort(403, 'Access denied');
        }
        else{

            if($user->role->name != 'tutor')
            {
                abort(403, 'Access denied');
            }
            return $next($request);
        }

    }
}
