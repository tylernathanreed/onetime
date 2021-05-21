<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>@yield('title', config('app.name'))</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="bg-gray-800 text-white">
        <main role="main" class="container mx-auto px-4">
            <div class="mt-12 xl:px-48 lg:px-32 md:px-16 sm:px-8 px-4">
                <h1 class="text-5xl mb-2">
                    @include('icons.user-secret')
                    <span>{{ config('app.name') }}</span>
                </h1>

                @if($errors->isNotEmpty())
                    <div class="bg-red-300 p-2 rounded text-red-900 border-red-900 mb-2">
                        @foreach($errors->all() as $message)
                            {!! $message !!}
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        <script type="text/javascript">
            function copyText(elementId) {
                var element = document.getElementById(elementId);
                element.select();
                element.setSelectionRange(0, 9999);
                document.execCommand('copy');
            }
        </script>
    </body>
</html>
