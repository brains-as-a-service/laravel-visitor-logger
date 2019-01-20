<?php

namespace Baas\LaravelVisitorLogger\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Baas\LaravelVisitorLogger\App\Http\Traits\ActivityLogger;

class LogActivity
{
    use VisitorActivityLogger;

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $description = null)
    {
        if (config('LaravelVisitorLogger.loggerMiddlewareEnabled') && $this->shouldLog($request)) {
            VisitorActivityLogger::activity($description);
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should log.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldLog($request)
    {
        foreach (config('LaravelVisitorLogger.loggerMiddlewareExcept') as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return false;
            }
        }

        return true;
    }
}
