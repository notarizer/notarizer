<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Error\Api;
use Stripe\Error\Base;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    /**
     * Show the form to create a payment.
     * 
     * @return Illuminate\Http\Response
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Charge the payment in the database and charge the card
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'stripeToken' => 'required',
            'email' => 'required|email',
            'for' => ''
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $formattedAmount = money_format('%.2n', $data['amount']);

        try {
            $charge = Charge::create([
                'amount' => round($data['amount'] * 100),
                'source' => $data['stripeToken'],
                'currency' => 'usd',
                'description' => 'Notarizer One-time payment',
                'metadata' => [
                    'for' => $data['for'] ?? null
                ],
                'receipt_email' => $data['email'],
            ]);

            session()->flash('payment_confirmation', "Thank you for your payment of {$formattedAmount}! We've emailed you a reciept to {$data['email']}.");

            if(empty($data['for']))
                return redirect()->route('home');

            return redirect()->route('doc.show', $data['for']);
        } catch (Base $e) {
            Log::error('Charge creation failed: ' . $e->getMessage());

            session()->flash('payment_error', "Your payment of {$formattedAmount} failed! Error: {$e->getStripeCode()}");

            return redirect()->back();
        }

    }
}
