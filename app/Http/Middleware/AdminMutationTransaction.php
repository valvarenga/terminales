<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMutationTransaction
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethodSafe()) {
            return $next($request);
        }

        // Model changes and their audit records commit or roll back together.
        return DB::transaction(function () use ($request, $next) {
            $response = $next($request);
            if ($response->exception ?? null) {
                throw $response->exception;
            }

            return $response;
        });
    }
}
