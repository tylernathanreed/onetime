@extends('layout')

@section('content')
    <div class="form-group">
        <label for="secret">Here's your URL:</label>
        <input type="text" name="url" id="url" value="{{ route('show', $slug) }}" class="form-control font-monospace" readonly>
    </div>
    <p>
        <button type="button" class="btn btn-success" onclick="copyText('url')">
            @icon('copy')
            Copy URL to Clipboard
        </button>
        <a class="btn btn-danger" href="{{ route('destroy', $slug) }}">
            @icon('destroy')
            Destroy this Secret
        </a>
        <a class="btn btn-primary" href="{{ route('index') }}">
            @icon('share')
            Share another Secret
        </a>
    </p>
@endsection
