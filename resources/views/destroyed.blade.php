@extends('layout')

@section('content')
<p>This secret has now been destroyed forever.</p>
<p>
    <a class="btn btn-primary" href="{{ route('index') }}">
        @icon('share')
        Share another Secret
    </a>
</p>
@endsection
