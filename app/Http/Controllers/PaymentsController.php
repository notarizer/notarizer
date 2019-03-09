<?php

namespace App\Http\Controllers;

use App\Payments\Payments;
use Illuminate\Http\Request;
use Stripe\Error\Base;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    /**
     * Show the form to create a payment.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Charge the payment in the database and charge the card
     *
     * @param \Illuminate\Http\Request
     * @param \App\Payments\Payments
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Payments $payments)
    {
        $data = $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'stripeToken' => 'required',
            'email' => 'required|email',
            'for' => ''
        ]);

        $formattedAmount = money_format('%.2n', $data['amount']);

        try {

            $payments->charge(
                round($data['amount'] * 100),
                $data['stripeToken'],
                $data['email'],
                $data['for'] ?? null
            );

            session()->flash('payment_confirmation', "Thank you for your payment of {$formattedAmount}! We've emailed you a reciept to {$data['email']}.");

            if(empty($data['for']))
                return redirect()->route('home');

            return redirect()->route('doc.show', $data['for']);
        } catch (Base $e) {
            Log::error('Charge creation failed: ' . $e->getMessage());

            session()->flash('payment_error', "Your payment of \${$formattedAmount} failed! Error: {$e->getStripeCode()}.");

            return redirect()->back();
        }

    }
}
