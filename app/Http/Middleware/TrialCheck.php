<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Auth;

class TrialCheck
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
        if(Auth::check() &&
            Auth::user()->company->subscription_plan != 0 &&
            Carbon::now() > (Auth::user()->company->created_at->addDays(7))
        ) {
            return redirect(route('home'))
                ->with("message","Trial ended. Contact admin.")
                ->with("alert-class", "error");
        }
        return $next($request);
    }
}
