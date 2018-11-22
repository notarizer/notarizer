@extends('layouts/app')

@section('title', 'All Documents')

@section('content')
    <h2>Index of all documents</h2>

    <p>
        Don't trust us? That's okay. We designed our service to be publicly auditable. <br>
        All documents created through our service are listed on this page.
    </p>

    <p>Compare our page with others:</p>

    <ul>
        {{-- TODO --}}
        <li><a href="#todo">Archive.org</a> (<a href="#todo">Save this webpage</a>)</li>
        <li><a href="https://www.google.com/search?q=site%3Anotarizer.app%2Fdoc">Google Cache</a> (Click the dropdown next to the website URL, then click "Cached")</li>
        <li><a href="https://bing.com/search?q=site%3Anotarizer.app%2Fdoc">Bing Cache</a> (Click the dropdown next to the website URL, then click "Cached")</li>
        <li><a href="#todo">archive.is</a></li>
        <li>Have an archiving site? <a href="#todo-contact">Add it here!</a></li>
    </ul>

    <p>Once on the cached site, use your browser's "Find" function to search for a document.</p>

    <table class="block whitespace-no-wrap">
        <tr>
            <th>Sha-256</th>
            <th>Creation date (UTC)</th>
        </tr>
        @foreach($documents as $document)
            <tr>
                <td>{{ $document->sha256 }}</td>
                <td>{{ $document->created_at }}</td>
            </tr>
        @endforeach
    </table>
@endsection
