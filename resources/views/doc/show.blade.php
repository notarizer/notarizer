@extends('layouts.app')

@section('content')
    <h2>Document detail</h2>

    @if(in_array($doc->sha256, session('owners') ?? []))
        <p>
            <strong>You are the owner of this file. Here's the link to copy: </strong>
            <input type="text" id="link" value="{{ url(route('doc.show', $doc)) }}" onclick="this.select()" readonly>
            <button onclick="window.copyEl(document.getElementById('link'), this, 'Copy');">Copy</button>
        </p>
    @endif

    @if(session('confirmation') !== null)
        <p>
            @if(session('confirmation'))
                Woo! The files are the same!
            @else
                Uh oh. The files are not the same.
            @endif
        </p>
    @endif

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
    </table>

    <br>

    <form action="{{ route('timezone') }}" method="post" onchange="event.currentTarget.submit()">
        Update your timezone: 
        <select name="timezone" id="timezone">
            @foreach(timezone_identifiers_list() as $timezoneId => $timezone)
                <option value="{{ $timezone }}" {{ session('timezone', 'America/New_York') == $timezone ? 'selected' : '' }}>{{ str_replace('_', ' ', $timezone) }}</option>
            @endforeach
        </select>
        <noscript>
            <input type="submit" value="Update">
        </noscript>
    </form>

    <br>

    <p>But wait! There's one more step. Please verify the file you have is the same as this file:</p>
    @include('components/doc-upload', ['form' => 'upload', 'doc' => 'doc', 'submitText' => 'Verify similarity', 'compareTo' => $doc->sha256])
@endsection

@push('scripts')
    <script>
        window.registerUploadForm(
            document.getElementById('upload'),
            document.getElementById('doc')
        );
    </script>
@endpush
