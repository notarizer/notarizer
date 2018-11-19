@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- TODO: Show upload progress for larger files --}}

<noscript>
    <div class="alert alert-danger">
        Javascript is required for form uploading.
    </div>
</noscript>


<form action="{{ route('doc.store') }}" method="post" id="{{ $form }}">
    <input type="file" id="{{ $doc }}" onchange="window.submitUpload(this.form, this)">
    
    <div class="upload-status"></div>

    <input type="hidden" name="name">
    <input type="hidden" name="sha256">
    <input type="hidden" name="size">

    @if(isset($compareTo))
        <input type="hidden" name="compare_to" value="{{ $compareTo }}">
    @endif

    <noscript>
        <input type="submit" value="{{ $submitText ?? 'Upload a File' }}">
    </noscript>
</form>
