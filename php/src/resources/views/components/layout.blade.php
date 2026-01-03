<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <div class="min-h-screen flex flex-col">

        <!-- メインコンテンツ -->
        <main class="flex-1 py-8">
            <div class="mx-auto w-full">
                {{ $slot }}
            </div>
        </main>

        <!-- フッター -->
        <footer class="bg-white text-center py-4 text-sm text-gray-500">
            © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </footer>
    </div>
</body>
</html>
