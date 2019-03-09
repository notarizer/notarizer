<?php

namespace App\Providers;

use App\Payments\StripePayments;
use App\Payments\Payments;
use Illuminate\Support\ServiceProvider;
use Stripe\Charge;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Payments::class, function() {
            $stripe = new Stripe();

            $stripe->setApiKey(config('services.stripe.secret'));

            return new StripePayments($stripe, new Charge());
        });
    }
}
