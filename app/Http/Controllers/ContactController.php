<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show the contact form
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Show the thank you page for contacting us
     *
     * @return \Illuminate\Http\Response
     */
    public function thanks()
    {
        return view('contact.thanks');
    }
}
