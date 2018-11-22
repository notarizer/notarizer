@extends('layouts.app')

@section('title', 'Payment for document')

@section('content')
    <h1 class="pb-4">One more step...</h1>

    <p>Our service is based on the pay-what-you-want pricing model. In order to keep our service running, we depend on payments from our community. If Notarizer has been useful to you, please consider submitting a payment. Payment is handled through a third-party payment processor; your credit card information never touches our servers. <a href="{{ route('contact.create') }}">Contact us</a> for more ways to pay.</p>

    @if ($errors->any())
        <div class="error-box my-6">
            <p class="mb-2">Uh oh! There was an error processing your payment. <strong>Your card has not been charged.</strong> <a href="{{ route('contact.create') }}" class="text-red-darkest">Click here if you need assistance</a>.</p>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
        @if(request()->input('for'))
            <input type="hidden" name="for" value="{{ request()->input('for') }}">
        @endif

        <input type="hidden" name="stripeToken" id="stripeToken">
        <input type="hidden" name="email" id="email">

        <div class="js-error-message error-box my-4 hidden"></div>

        <div class="my-4 leading-loose">
            <div class="mb-2">
                <label for="amount">Please enter a payment amount: </label>
            </div>

            <span class="mb-2">
                <button class="rounded bg-grey-light py-2 px-4 border border-grey-dark" onclick="this.form.querySelector('input[name=amount]').value = '1.00'">$1</button>
                <button class="rounded bg-grey-light py-2 px-4 border border-grey-dark" onclick="this.form.querySelector('input[name=amount]').value = '2.00'">$2</button>
                <button class="rounded bg-grey-light py-2 px-4 border border-grey-dark" onclick="this.form.querySelector('input[name=amount]').value = '5.00'">$5</button>
                <button class="rounded bg-grey-light py-2 px-4 border border-grey-dark" onclick="this.form.querySelector('input[name=amount]').value = '10.00'">$10</button>
            </span>

            <span>|</span>

            <span style="white-space: nowrap">
                <input class="border border-grey-dark p-2 rounded my-2" type="number" name="amount" placeholder="Custom amount..." min="1" step="0.01" required>

                <input type="submit" value="Pay" class="rounded bg-primary py-2 px-6 text-white cursor-pointer">
            </span>
        </div>
    </form>

    @if(request()->input('for'))
        <p class="text-right mt-6"><a class="text-grey-darker" href="{{ route('doc.show', request()->input('for')) }}">Skip payment, continue to document &rarr;</a></p>
    @endif
@endsection

@push('scripts')
    <script src="https://checkout.stripe.com/checkout.js"></script>

    <script>
        window.initPaymentForm(
            document.getElementById('paymentForm'),
            document.getElementById('stripeToken'),
            document.getElementById('email'),
            '{{ config('services.stripe.key') }}'
        )
    </script>
@endpush
