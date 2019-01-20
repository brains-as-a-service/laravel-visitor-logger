<?php

namespace Baas\LaravelVisitorLogger\App\Listeners;

use Illuminate\Auth\Events\Attempting;
use Baas\LaravelVisitorLogger\App\Http\Traits\VisitorActivityLogger;

class LogAuthenticationAttempt
{
    use VisitorActivityLogger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Attempting $event
     *
     * @return void
     */
    public function handle(Attempting $event)
    {
        if (config('LaravelVisitorLogger.logAuthAttempts')) {
            VisitorActivityLogger::activity('Authenticated Attempt');
        }
    }
}
