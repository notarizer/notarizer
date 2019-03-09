<?php

namespace App\Payments;


use Stripe\Charge;
use Stripe\Stripe;

class StripePayments implements Payments
{
    /** @var Stripe */
    var $stripe;

    /** @var Charge */
    var $charge;

    public function __construct(Stripe $stripe, Charge $charge)
    {
        $this->stripe = $stripe;

        $this->charge = $charge;

        setlocale(LC_MONETARY, 'en_US.UTF-8');
    }


    public function charge($amount, $token, $email, $for = null)
    {
        return $this->charge->create([
            'amount' => $amount,
            'source' => $token,
            'currency' => 'usd',
            'description' => 'Notarizer One-time payment',
            'metadata' => [
                'for' => $for
            ],
            'receipt_email' => $email
        ]);
    }
}