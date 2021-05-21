@extends('layout')

@section('content')
    <a class="btn btn-warning btn-lg mt-3" href="{{ route('reveal', $secret) }}">
        @include('icons.reveal')
        Show this Secret
    </a>
@endsection
