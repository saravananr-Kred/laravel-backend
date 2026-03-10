<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Global Loader -->
    <div id="global-loader"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-300"
        style="display: none;">
        <div class="relative">
            <div class="h-16 w-16 rounded-full border-4 border-indigo-100"></div>
            <div
                class="absolute top-0 h-16 w-16 animate-spin rounded-full border-4 border-indigo-600 border-t-transparent">
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loader = document.getElementById('global-loader');

            // Show loader on page unload (navigation)
            window.addEventListener('beforeunload', () => {
                loader.style.display = 'flex';
            });

            // Show loader on form submissions
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    loader.style.display = 'flex';
                });
            });

            // Ensure loader is hidden when page is loaded (handling back/forward cache)
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    loader.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>