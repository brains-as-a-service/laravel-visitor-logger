<?php

namespace Baas\LaravelVisitorLogger\App\Listeners;

use Illuminate\Auth\Events\Failed;
use Baas\LaravelVisitorLogger\App\Http\Traits\VisitorActivityLogger;

class LogFailedLogin
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
     * @param Failed $event
     *
     * @return void
     */
    public function handle(Failed $event)
    {
        if (config('LaravelVisitorLogger.logFailedAuthAttempts')) {
            VisitorActivityLogger::activity('Failed Login Attempt');
        }
    }
}
