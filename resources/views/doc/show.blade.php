@extends('layouts.app')

@section('title', $doc->name ?? 'Untitled')

@section('content')
    <h1>
        @if(isset($doc->name))
            {{ $doc->name }}
        @else
            Document details
        @endif

        <small class="text-lg">
            ({{ $doc->humanFileSize() }})
        </small>
    </h1>

    <div class="flex flex-col md:flex-row">
        <div class="md:w-1/2">
            <p class="my-2">
                {{ $doc->name ?? '(Untitled document)' }} was first uploaded

                <span id="time" class="font-bold" title="{{ $doc->created_at->format('F j, Y \a\t g:i:s a T') }}"></span>
                <noscript>
                    <span class="font-bold">
                        {{ $doc->created_at->setTimezone(session('timezone', 'America/Los_Angeles'))->format('F j, Y, g:i a T') }}
                    </span>

                    <form action="{{ route('timezone') }}" method="post" onchange="event.currentTarget.submit()">
                        Update your timezone:
                        <select class="border border-grey-dark" name="timezone" id="timezone">
                            @foreach(timezone_identifiers_list() as $timezoneId => $timezone)
                                <option value="{{ $timezone }}" {{ session('timezone', 'America/Los_Angeles') == $timezone ? 'selected' : '' }}>{{ str_replace('_', ' ', $timezone) }}</option>
                            @endforeach
                        </select>

                        <input type="submit" value="Update" class="p-1 px-2 cursor-pointer shadow-inner rounded bg-grey-lighter border border-grey-dark">
                    </form>
                </noscript>
            </p>

            @if(session('confirmation') !== null)
                @if(session('confirmation'))
                    <p class="my-2 rounded border border-green-dark bg-green-light text-green-darkest p-4"><strong>Success!</strong> The two files are equal</p>
                @else
                    <p class="my-2 rounded border border-red-dark bg-red-light text-red-darkest p-4"><strong>Uh oh.</strong> The files are not equal.</p>
                @endif
            @elseif(! $doc->isOwner())
                <p class="my-2 rounded border border-blue-dark bg-blue-lightest p-4"><strong>Important:</strong> The equality of this file compared to your file still needs to be verified. Please upload your file to compare the two.</p>
            @elseif($doc->isOwner())
                <p class="my-2 rounded border border-blue-dark bg-blue-lightest p-4 flex flex-col sm:flex-row items-center">
                    <strong>Share:</strong>
                    <input class="px-2 py-1 border border-grey-dark flex-grow mx-2 min-w-0 w-full" type="text" id="link" value="{{ url()->route('doc.show', $doc) }}" onclick="this.select()" readonly>
                    <button class="rounded bg-white px-2 py-1 border border-grey-darkest mt-2 sm:mt-0" onclick="window.copyEl(document.getElementById('link'), this, 'Copy');">Copy</button>
                </p>
            @endif
        </div>

        <div class="md:w-1/2 w-full sm:w-2/3 mx-auto md:ml-8">
            @include('components/doc-upload', ['form' => 'upload', 'doc' => 'doc', 'submitText' => 'Verify similarity', 'compareTo' => $doc->sha256])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.registerUploadForm(
            document.getElementById('upload'),
            document.getElementById('doc')
        );

        let dateOptions = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
        document.getElementById('time').innerHTML = (new Date({{ $doc->created_at->format('U') * 1000 }})).toLocaleDateString('en-US', dateOptions);
    </script>
@endpush
