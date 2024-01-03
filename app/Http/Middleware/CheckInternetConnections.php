<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInternetConnections
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





//  $website = 'https://www.google.com';

// $headers = @get_headers($website);
// if ($headers && strpos($headers[0], '200 OK') !== false) {
  
   
// } else {
//     // Internet connection is not available
//     // return response()->json(['success' => 'Internet connection is not available'], 200);
// }










        return $next($request);
    }
}
