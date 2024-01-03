<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   
public function handle($request, Closure $next, $usertype)
{
    $user = Auth::guard('api')->user();

    if ($user && $user->usertype === $usertype) {
        return $next($request);
    }

    return response()->json(['error' => 'Não autorizado'], 403);
}

}
