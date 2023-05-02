@extends('layout')

@section('content')

    <div class="flex justify-center bg-red-300 p-2 rounded text-red-900 mb-2">
        @hasSection('code')
            <div class="px-4 text-lg border-r border-red-900 tracking-wider">
                @yield('code', $code ?? null)
            </div>
        @endif

        <div class="ml-4 text-lg tracking-wider">
            @yield('message', $message ?? null)
        </div>
    </div>

    <a class="btn btn-primary" href="{{ route('index') }}">
        @icon('share')
        Share a new Secret
    </a>

@endsection
