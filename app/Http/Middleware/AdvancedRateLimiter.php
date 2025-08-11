<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdvancedRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $limitType = 'default'): Response
    {
        $config = config('rate_limiter.limits.' . $limitType, config('rate_limiter.defaults'));
        
        $maxAttempts = $config['attempts'];
        $decayMinutes = $config['decay_minutes'];
        
        $key = $this->resolveRequestSignature($request, $limitType);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts, $limitType);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request, string $limitType): string
    {
        $user = Auth::user();
        $userId = $user ? $user->id : 'guest';
        $ip = $request->ip();
        $route = $request->route()?->getName() ?? $request->path();
        
        return sha1($userId . '|' . $ip . '|' . $route . '|' . $limitType);
    }

    /**
     * Create a 'too many attempts' response.
     */
    protected function buildResponse(string $key, int $maxAttempts, string $limitType): Response
    {
        $retryAfter = RateLimiter::availableIn($key);
        $minutes = ceil($retryAfter / 60);
        
        $message = config("rate_limiter.messages.{$limitType}", config('rate_limiter.messages.default'));
        $message = str_replace(':minutes', $minutes, $message);

        return response()->json([
            'error' => 'Too many requests',
            'message' => $message,
            'retry_after' => $retryAfter,
            'limit_type' => $limitType
        ], 429)->withHeaders([
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset' => time() + $retryAfter,
        ]);
    }

    /**
     * Add the limit header information to the given response.
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts): Response
    {
        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);
    }

    /**
     * Calculate the number of remaining attempts.
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return $maxAttempts - RateLimiter::attempts($key) + 1;
    }
}
