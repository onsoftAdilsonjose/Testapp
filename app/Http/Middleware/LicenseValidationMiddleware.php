<?php

namespace App\Http\Middleware;

use App\Models\Center\Key\Provider\Service\Keygerate;
use Closure;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth; // Import JWTAuth facade

class LicenseValidationMiddleware
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
        // Check if there is a valid license in the system.
        if (!$this->hasValidLicense()) {

          
            return response()->json(['error' => 'license Expirada Por Favor Requisitar Nova License.'], 403);
            
        }

        return $next($request);
    }

    private function hasValidLicense()
    {
        // Implement your logic to check if there is a valid license in the system.
        // This could involve querying the database or checking an external API.
        // Return true if a valid license is found, false otherwise.
        $validLicense = Keygerate::where('activated', true)
            ->whereDate('endday', '>=', now())
            ->exists();

        return $validLicense;
    }
}
