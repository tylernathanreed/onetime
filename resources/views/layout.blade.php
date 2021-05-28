<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>@yield('title', config('app.name'))</title>

        <meta name="author" content="Tyler Reed"/>
        <meta name="description" content="Create and share one-time secrets"/>
        <meta name="keywords" content="Laravel, Onetime, One, Time, Secret"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="theme-color" content="#1f2937">
        <meta charset="UTF-8"/>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    </head>
    <body class="bg-gray-800 text-white">
        <main role="main" class="container mx-auto px-4">
            <div class="mt-12 xl:px-48 lg:px-32 md:px-16 sm:px-8 px-4">
                <h1 class="text-2xl sm:text-3xl md:text-5xl mb-2">
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
        <div class="fixed bottom-0 text-center p-3 w-full">
            <?php $start = request()->server('REQUEST_START_TIME') ?: LARAVEL_START; ?>
            <small class="text-gray-500">This page served in {{ number_format((microtime(true) - $start) * 1000, 2) }}ms.</small>
        </div>
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
