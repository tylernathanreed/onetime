@extends('layout')

@section('content')
    <div class="form-group">
        <label for="secret">Here's your Secret:</label>
        <textarea name="secret" id="secret" rows="5" class="form-control" readonly>{{ $secret }}</textarea>
    </div>

    <p>This secret has now been destroyed forever.</p>

    <p>
        <button type="button" class="btn btn-success" onclick="copyText('secret')">
            @icon('copy')
            Copy Secret to Clipboard
        </button>
        <a class="btn btn-primary" href="{{ route('index') }}">
            @icon('share')
            Share another Secret
        </a>
    </p>

@endsection
