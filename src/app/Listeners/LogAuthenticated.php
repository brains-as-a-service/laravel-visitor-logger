<?php

namespace Baas\LaravelVisitorLogger\App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Baas\LaravelVisitorLogger\App\Http\Traits\VisitorActivityLogger;

class LogAuthenticated
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
     * Handle ANY authenticated event.
     *
     * @param Authenticated $event
     *
     * @return void
     */
    public function handle(Authenticated $event)
    {
        if (config('LaravelVisitorLogger.logAllAuthEvents')) {
            VisitorActivityLogger::activity('Authenticated VisitorActivity');
        }
    }
}
