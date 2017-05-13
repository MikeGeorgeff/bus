<?php

namespace Georgeff\Bus\Bridge\Laravel;

use Georgeff\Bus\CommandBus;
use Georgeff\Bus\Instance\NativeBus;
use Illuminate\Support\ServiceProvider;
use Georgeff\Bus\Resolver\NativeResolver;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Determine if loading the provider is deferred
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register container bindings
     *
     * @return void
     */
    public function register()
    {
        $this->registerContainer();

        $this->registerResolver();

        $this->registerBus();
    }

    /**
     * Register the bus container
     *
     * @return void
     */
    protected function registerContainer()
    {
        $this->app->bind('bus.container', function ($app) {
            return new IlluminateContainer($app);
        });
    }

    /**
     * Register the bus resolver
     *
     * @return void
     */
    public function registerResolver()
    {
        $this->app->bind('bus.resolver', function ($app) {
            return new NativeResolver($app['bus.container']);
        });
    }

    /**
     * Register the command bus
     *
     * @return void
     */
    protected function registerBus()
    {
        $this->app->singleton(CommandBus::class, function ($app) {
            return new NativeBus($app['bus.resolver']);
        });
    }

    /**
     * Get the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [
            'bus.container', 'bus.resolver', CommandBus::class
        ];
    }
}