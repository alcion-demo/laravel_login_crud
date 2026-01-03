<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 flex">
        <!-- Sidebar -->
        <aside class="w-64 border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 flex flex-col">
            <div class="flex items-center p-4 gap-2">
                <x-application-logo class="block h-10 w-auto fill-current text-[#004A3E]" />
                @php
                    $userRole = auth()->user()->role ?? '';
                @endphp

                @if($userRole === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <span class="font-bold text-lg">ららべるぅ？</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="font-bold text-lg">ららべるぅ？</span>
                    </a>
                @endif
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-2 py-4 space-y-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase px-2 mb-2">Menu</h3>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.index') }}"
                        class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.index') ? 'bg-zinc-200 dark:bg-zinc-700' : '' }}">
                        ユーザー管理
                        </a>
                        <a href="{{ route('admin.create') }}"
                        class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.create') ? 'bg-zinc-200 dark:bg-zinc-700' : '' }}">
                        管理者追加
                        </a>
                    @endif
                    {{-- <nav class="flex-1 px-2 py-4 space-y-2"> --}}
                    <a href="{{ route('post.index') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('post.index') ? 'bg-zinc-200 dark:bg-zinc-700' : '' }}">
                    投稿一覧
                    </a>
                    <a href="{{ route('post.create') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('post.create') ? 'bg-zinc-200 dark:bg-zinc-700' : '' }}">
                    投稿作成
                    </a>
                    <a href="{{ route('calendar.index') }}"
                    class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('calendar.index') ? 'bg-zinc-200 dark:bg-zinc-700' : '' }}">
                    予定表
                    </a>
            </nav>
            <div class="mt-auto p-4">
                <div class="mt-2">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700">
                        個人情報変更
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700">
                            ログアウト
                        </button>
                    </form>
                </div>
            </div>
        </aside>
    </body>
</html>
