@extends('layouts.app')

@section('content')
    <h2>Document Detail</h2>

    <table>
        <tr>
            <th>Name</th>
            <td>{{ $doc->name }}</td>
        </tr>
        <tr>
            <th>Size</th>
            <td>{{ $doc->humanSize }}</td>
        </tr>
        <tr>
            <th>SHA-256</th>
            <td>{{ $doc->sha256 }}</td>
        </tr>
        <tr>
            <th>Uploaded at</th>
            <td>{{ $doc->created_at->setTimezone(session('timezone', 'America/New_York'))->format('F j, Y, g:i a T') }}</td>
        </tr>
        <tr>
            <th>Shareable link</th>
            <td><input type="text" value="{{ url(route('doc.show', $doc)) }}" onclick="this.setSelectionRange(0, this.value.length)" onkeyup="return false"></td>
        </tr>
    </table>

    <br>

    Select a timezone:

    <form action="{{ route('timezone') }}" method="post" onchange="event.currentTarget.submit()">
        <select name="timezone" id="timezone">
            @foreach(timezone_identifiers_list() as $timezoneId => $timezone)
                <option value="{{ $timezone }}" {{ session('timezone', 'America/New_York') == $timezone ? 'selected' : '' }}>{{ str_replace('_', ' ', $timezone) }}</option>
            @endforeach
        </select>
        <noscript>
            <input type="submit" value="Update">
        </noscript>
    </form>
@endsection
