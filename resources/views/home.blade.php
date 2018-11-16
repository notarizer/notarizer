@extends('layouts.app')

@section('content')
    <p>Welcome to Notarizer. Please upload a file to begin.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/doc" method="post" id="upload">
        <input type="file" id="doc">

        <input type="hidden" name="name">
        <input type="hidden" name="sha256">
        <input type="hidden" name="size">

        <input type="submit" value="Submit">
    </form>
@endsection

@push('scripts')
    <script>
        window.registerUploadForm(
            document.getElementById('upload'),
            document.getElementById('doc')
        );
    </script>
@endpush
