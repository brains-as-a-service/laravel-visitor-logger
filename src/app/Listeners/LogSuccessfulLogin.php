<?php

namespace Baas\LaravelVisitorLogger\App\Listeners;

use Illuminate\Auth\Events\Login;
use Baas\LaravelVisitorLogger\App\Http\Traits\VisitorActivityLogger;

class LogSuccessfulLogin
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
     * @param Login $event
     *
     * @return void
     */
    public function handle(Login $event)
    {
        if (config('LaravelVisitorLogger.logSuccessfulLogin')) {
            VisitorActivityLogger::activity('Logged In');
        }
    }
}
