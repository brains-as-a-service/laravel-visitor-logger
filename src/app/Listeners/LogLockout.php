<?php

namespace Baas\LaravelVisitorLogger\App\Listeners;

use Illuminate\Auth\Events\Lockout;
use Baas\LaravelVisitorLogger\App\Http\Traits\VisitorActivityLogger;

class LogLockout
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
     * @param Lockout $event
     *
     * @return void
     */
    public function handle(Lockout $event)
    {
        if (config('LaravelVisitorLogger.logLockOut')) {
            VisitorActivityLogger::activity('Locked Out');
        }
    }
}
