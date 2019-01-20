<?php

namespace Baas\LaravelVisitorLogger\App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Baas\LaravelVisitorLogger\App\Http\Traits\VisitorActivityLogger;

class LogPasswordReset
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
     * @param PasswordReset $event
     *
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        if (config('LaravelVisitorLogger.logPasswordReset')) {
            VisitorActivityLogger::activity('Reset Password');
        }
    }
}
