@extends('layouts.app')

@section('content')
    <p>Welcome to Notarizer. Please upload a file to begin.</p>

    @include('components/doc-upload', ['form' => 'upload', 'doc' => 'doc', 'submitText' => 'Upload a File'])
@endsection

@push('scripts')
    <script>
        window.registerUploadForm(
            document.getElementById('upload'),
            document.getElementById('doc')
        );
    </script>
@endpush
