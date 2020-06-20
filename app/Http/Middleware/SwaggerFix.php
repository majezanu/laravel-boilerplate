<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class SwaggerFix
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
        if (strpos($request->headers->get("Authorization"),"Bearer ") === false && $request->headers->has('bearer_auth')) {
            $request->headers->set("Authorization", $request->headers->get("bearer_auth"));
        }

        $response = $next($request);

        return $response;
    }
}
