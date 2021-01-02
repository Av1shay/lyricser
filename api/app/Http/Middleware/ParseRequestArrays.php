<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ParseRequestArrays
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
        if ($request->get('composers')) {
            $request->merge([
                'composers' => json_decode($request->get('composers')),
            ]);
        }

        if ($request->get('performers')) {
            $request->merge([
                'performers' => json_decode($request->get('performers')),
            ]);
        }

        return $next($request);
    }
}
