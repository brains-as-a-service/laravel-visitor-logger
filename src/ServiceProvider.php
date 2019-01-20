<?php

namespace Baas\LaravelVisitorLogger;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Baas\LaravelVisitorLogger\App\Http\Middleware\LogActivity;


class ServiceProvider extends ServiceProvider
{
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The event listener mappings for the applications auth scafolding.
     *
     * @var array
     */
    protected $listeners = [

        'Illuminate\Auth\Events\Attempting' => [
            'Baas\LaravelVisitorLogger\App\Listeners\LogAuthenticationAttempt',
        ],

        'Illuminate\Auth\Events\Authenticated' => [
            'Baas\LaravelVisitorLogger\App\Listeners\LogAuthenticated',
        ],

        'Illuminate\Auth\Events\Login' => [
            'Baas\LaravelVisitorLogger\App\Listeners\LogSuccessfulLogin',
        ],

        'Illuminate\Auth\Events\Failed' => [
            'Baas\LaravelVisitorLogger\App\Listeners\LogFailedLogin',
        ],

        'Illuminate\Auth\Events\Logout' => [
            'Baas\LaravelVisitorLogger\App\Listeners\LogSuccessfulLogout',
        ],

        'Illuminate\Auth\Events\Lockout' => [
            'Baas\LaravelVisitorLogger\App\Listeners\LogLockout',
        ],

        'Illuminate\Auth\Events\PasswordReset' => [
            'Baas\LaravelVisitorLogger\App\Listeners\LogPasswordReset',
        ],

    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('activity', [LogActivity::class]);
        $this->loadTranslationsFrom(__DIR__.'/resources/lang/', 'LaravelVisitorLogger');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views/', 'LaravelVisitorLogger');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        if (file_exists(config_path('laravel-visitor-logger.php'))) {
            $this->mergeConfigFrom(config_path('laravel-visitor-logger.php'), 'LaravelVisitorLogger');
        } else {
            $this->mergeConfigFrom(__DIR__.'/config/laravel-visitor-logger.php', 'LaravelVisitorLogger');
        }
        $this->registerEventListeners();
        $this->publishFiles();
    }

    /**
     * Get the list of listeners and events.
     *
     * @return array
     */
    private function getListeners()
    {
        return $this->listeners;
    }

    /**
     * Register the list of listeners and events.
     *
     * @return void
     */
    private function registerEventListeners()
    {
        $listeners = $this->getListeners();
        foreach ($listeners as $listenerKey => $listenerValues) {
            foreach ($listenerValues as $listenerValue) {
                \Event::listen($listenerKey,
                    $listenerValue
                );
            }
        }
    }

    /**
     * Publish files for Laravel Logger.
     *
     * @return void
     */
    private function publishFiles()
    {
        $publishTag = 'laravelvisitorlogger';

        $this->publishes([
            __DIR__.'/config/laravel-visitor-logger.php' => base_path('config/laravel-visitor-logger.php'),
        ], $publishTag);

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/'.$publishTag),
        ], $publishTag);

        $this->publishes([
            __DIR__.'/resources/lang' => base_path('resources/lang/vendor/'.$publishTag),
        ], $publishTag);
    }
}
