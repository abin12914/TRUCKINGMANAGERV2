<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Landlord;

class LandlordTenancy
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
        if (Auth::check()) {
            $tenantId = Auth::user()->company_id;

            Landlord::addTenant('company_id', $tenantId);
        }

        return $next($request);
    }
}
