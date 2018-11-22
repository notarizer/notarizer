@extends('layouts.app')

@section('title', 'Thanks for Contacting Us')

@section('content')
    <h1>Thanks for contacting us!</h1>

    <p>We'll get back to you shortly.</p>

    <p class="mt-4">
        <a href="{{ route('home') }}">Return to homepage</a>
    </p>
@endsection
