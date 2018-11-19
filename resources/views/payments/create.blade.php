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
        window.initPaymentForm(
            document.getElementById('paymentForm'),
            document.getElementById('stripeToken'),
            document.getElementById('email'),
            '{{ config('services.stripe.key') }}'
        )
    </script>
@endpush
