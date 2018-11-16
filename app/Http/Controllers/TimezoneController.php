<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $timezone = $request->input('timezone');

        $valid = false;

        if (in_array($timezone, timezone_identifiers_list())) {
            session(['timezone' => $timezone]);

            $valid = true;
        }

        if (! $request->expectsJson())
            return redirect()->back();

        if ($valid) return response('Timezone updated');

        return response('Invalid timezone', 403);
    }
}
