<div class="rounded border border-grey-dark p-8 bg-grey-light shadow-inner">
    @if ($errors->any())
        <div class="error-box text-sm">
            <p class="mb-2">Uh oh! There were errors uploading your file:</p>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TODO: Add drag and drop --}}

    <noscript>
        <div class="error-box text-sm">
            Javascript is required for file uploading.
        </div>
    </noscript>


    <form action="{{ route('doc.store') }}" method="post" id="{{ $form }}">
        <input style="display: none" type="file" id="{{ $doc }}" onchange="window.submitUpload(this.form, this)">

        <div class="js-errors hidden bg-red-light border border-red-dark text-red-darkest mb-3 p-2 text-sm shadow-inner"></div>

        <label for="{{ $doc }}" class="js-upload-status shadow cursor-pointer rounded bg-primary w-full block text-white text-center p-4 text-xl font-thin tracking-wide border-primary border-4 active:shadow-lg active:bg-primary-dark">{{ $submitText }}*</label>

        <div class="text-xs tracking-wide text-center">*file never leaves your device</div>

        <input type="hidden" name="name">
        <input type="hidden" name="sha256">
        <input type="hidden" name="size">

        @if(isset($compareTo))
            <input type="hidden" name="compare_to" value="{{ $compareTo }}">
        @endif
    </form>
</div>
