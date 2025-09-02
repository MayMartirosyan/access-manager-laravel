<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Доступ запрещён');
        }
        return $next($request);
    }
}