<?php

namespace App\Payments;


interface Payments
{
    /**
     * Charge the user.
     *
     * @param $amount
     * @param $token
     * @param $email
     * @param null $for
     * @return mixed
     */
    public function charge($amount, $token, $email, $for = null);
}