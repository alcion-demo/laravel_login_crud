@vite('resources/js/app.js') <!-- Alpine.js import済み -->

<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- 左側ロゴ -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                <x-application-logo class="block h-10 w-auto fill-current text-white" /></a>
            </div>


            <!-- デスクトップメニュー -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">ダッシュボード</a>

                <!-- ユーザードロップダウン -->
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        {{ Auth::user()->name }}
                        <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <!-- ログアウトフォーム -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    ログアウト
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
