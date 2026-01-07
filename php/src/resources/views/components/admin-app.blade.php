<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>管理システム</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 text-gray-900">
        <div class="flex h-screen overflow-hidden">
            @if(Auth::user()->is_admin)
                <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 h-full overflow-y-auto">
                    <x-admin-sidebar />
                </aside>
            @endif

            <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
                <header class="bg-white border-b border-gray-200 shrink-0">
                    {{-- コンポーネント側でロゴの有無を制御 --}}
                    <x-navigation />
                </header>

                <main class="flex-1 overflow-y-auto bg-gray-50">
                    {{-- ここで max-w-7xl を指定し、左寄せを防ぐ --}}
                    <div class="max-w-7xl mx-auto p-6 lg:p-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>