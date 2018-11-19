@extends('layouts.app')

@section('content')
    <h2>Did we save your bacon?</h2>

    <p>Consider submitting a payment so that we can keep our servers running.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">

        <div class="error_message"></div>

        <label for="amount">Please enter a payment amount: </label>
        <input type="number" name="amount" placeholder="1.00 minimum" min="1" step="0.01" required>

        @if(request()->input('for'))
            <input type="hidden" name="for" value="{{ request()->input('for') }}">
        @endif

        <input type="hidden" name="stripeToken" id="stripeToken">
        <input type="hidden" name="email" id="email">

        <input type="submit" value="Pay">
    </form>

    @if(request()->input('for'))
        <p><a href="{{ route('doc.show', request()->input('for')) }}">Skip payment, continue to document &rarr;</a></p>
    @endif
@endsection

@push('scripts')
    <script src="https://checkout.stripe.com/checkout.js"></script>

    <script>
        var handler = StripeCheckout.configure({
            key: '{{ config('services.stripe.key') }}',
            locale: 'auto',
            zipCode: true,
            name: 'Notarizer',
            description: 'One-time payment',
            token: function(token) {
                document.getElementById('stripeToken').value = token.id;
                document.getElementById('email').value = token.email;
                document.getElementById('paymentForm').submit();
            }
        });

        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            event.preventDefault();

            document.querySelector('.error_message').innerHTML = '';

            let amount = document.querySelector('input[name=amount]').value;
            amount = amount.replace(/\$/g, '').replace(/\,/g, '');

            amount = parseFloat(amount);

            if (isNaN(amount)) {
                document.querySelector('.error_message').innerHTML = 'The amount you entered is not a number!';
            } else if (amount < 1.00) {
                document.querySelector('.error_message').innerHTML = 'The minimum donation amount is $1.00';
            } else {
                amount = amount * 100; // Convert from dollars to cents
                handler.open({
                    amount: Math.round(amount)
                });
            }



        });
    </script>
@endpush
