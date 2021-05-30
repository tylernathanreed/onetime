@extends('layout')

@section('content')
    <a class="btn btn-warning btn-lg mt-3" href="{{ route('reveal', $slug) }}">
        @include('icons.reveal')
        Show this Secret
    </a>
@endsection
