<?php

namespace App\Http\Middleware;

use App\Services\CreditService;
use Closure;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;

class CheckCredits
{
    public function __construct(private CreditService $credits)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && !$user->isAdmin()) {
            try {
                $this->credits->consume($user);
            } catch (HttpClientException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too Many Requests',
                ], 429);
            }
        }
        return $next($request);
    }
}