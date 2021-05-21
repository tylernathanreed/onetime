@extends('layout')

@section('content')
    <div class="form-group">
        <label for="secret">Here's your Secret:</label>
        <textarea name="secret" id="secret" rows="5" class="form-control" readonly>{{ decrypt($secret->secret) }}</textarea>
    </div>

    <p>This secret has now been destroyed forever.</p>

    <p>
        <button type="button" class="btn btn-success" onclick="copyText('secret')">
            @include('icons.copy')
            Copy Secret to Clipboard
        </button>
        <a class="btn btn-primary" href="{{ route('index') }}">
            @include('icons.share')
            Share another Secret
        </a>
    </p>

@endsection
