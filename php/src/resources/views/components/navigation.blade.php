@vite(['resources/css/app.css', 'resources/js/app.js'])
<nav class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center flex-1">
                @if(!Auth::user()->is_admin)
                    <div class="shrink-0 flex items-center mr-10">
                        <x-application-logo class="block h-10 w-auto fill-current text-[#004A3E]" />
                    </div>
                @endif

                <div class="hidden sm:flex sm:space-x-8 sm:-my-px items-center h-16">
                    @if(!Auth::user()->is_admin)
                        {{-- 一般ユーザー用 dashboard リンク。x-nav-link と高さを合わせるため直接 A タグではなく構成を統一 --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('dashboard') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('todos.index')" :active="request()->routeIs('todos.index')">
                        {{ __('Todo一覧') }}
                    </x-nav-link>

                    <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')">
                        {{ __('予定表') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        @if(!Auth::user()->is_admin)
                            <img src="{{ Auth::user()->avatar_path ? asset('storage/' . Auth::user()->avatar_path) : asset('images/default-avatar.png') }}" 
                                    class="h-8 w-8 rounded-full mr-2 object-cover border border-gray-200">
                        @endif
                        {{ Auth::user()->name }}
                        <svg class="ml-2 h-5 w-5 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                        x-transition:enter="transition ease-out duration-200" 
                        x-transition:enter-start="opacity-0 scale-95" 
                        class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            @if(!Auth::user()->is_admin)
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    プロフィール設定
                                </a>
                            @endif
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