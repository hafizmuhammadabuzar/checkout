<?php

namespace Hafiz\Checkout;

use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Hafiz\Checkout\Payment', function ($app) {
            return new Payment($app);
        });
        $this->app->bind('Hafiz\Checkout\Checkout', function ($app) {
            return new Checkout($app);
        });
    }
}
