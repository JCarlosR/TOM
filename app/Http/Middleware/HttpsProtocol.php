<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
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
        // the custom middleware isn't necessary because CloudFlare performs the redirect

        if (!$request->secure() && env('REDIRECT_HTTPS')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
