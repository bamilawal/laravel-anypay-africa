<?php

namespace Bamilawal\LaravelAnypayAfrica\Providers;

use Bamilawal\LaravelAnypayAfrica\Services\PaystackService;
use Bamilawal\LaravelAnypayAfrica\Services\FlutterwaveService;
use Bamilawal\LaravelAnypayAfrica\Services\MoniepointService;
use Bamilawal\LaravelAnypayAfrica\Services\PagaService;

use Illuminate\Support\ServiceProvider;

class LaravelAnypayAfricaServiceProvider extends ServiceProvider
{
    public function register() {
        // The Facades
        $this->app->bind('paystack', function ($app) {
            return new PaystackService;
        });
        $this->app->bind('flutterwave', function ($app) {
            return new FlutterwaveService;
        });
        $this->app->bind('moniepoint', function ($app) {
            return new MoniepointService;
        });
        $this->app->bind('paga', function ($app) {
            return new PagaService;
        });

        $this->mergeConfigFrom(__DIR__.'/../../config/laravel-anypay-africa.php', 'laravel-anypay-africa');

    }

    public function boot() {

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../../config/laravel-anypay-africa.php' => config_path('laravel-anypay-africa.php'),
            ], 'laravel-anypay-africa');
        }
    }
}