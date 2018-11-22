@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <h1>Contact Us</h1>

    <p class="mb-2">Phone: <strong><a href="tel:18776176458">+1 (877) 617-6458</a></strong></p>

    <p class="mb-2">Email: <strong><a href="mailto:hello@notarizer.app">hello@notarizer.app</a></strong></p>

    <div class="border my-4"></div>

    <form action="https://jumprock.co/mail/notarizer" method="post">
        <input type="text" name="trapit" value="" style="display:none">

        <input type="hidden" name="replyto" value="%email">

        <div class="my-2">
            <label for="email">Your Email:</label>
            <input class="border border-grey-dark rounded w-full p-2 mt-2" type="email" name="email" placeholder="Email Address" />
        </div>

        <div class="my-2">
            <label for="subject">Subject:</label>
            <input class="border border-grey-dark rounded w-full p-2 mt-2" type="text" name="subject" placeholder="Subject" />
        </div>

        <div class="my-2">
            <label for="message">Message:</label>
            <textarea class="border border-grey-dark rounded w-full p-2 mt-2 h-24" type="text" name="message" placeholder="Message"></textarea>
        </div>

        <input class="rounded bg-primary py-2 px-4 text-white tracking-wide cursor-pointer" type="submit" value="Contact Us" />

        <input type="hidden" name="after" value="{{ url()->route('contact.thanks') }}">
    </form>
@endsection
