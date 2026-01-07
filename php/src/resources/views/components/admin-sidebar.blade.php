<aside class="h-full flex flex-col p-4 bg-white">
    <div class="mb-8 px-2 py-4 border-b border-gray-100">
        {{-- サイドバーの最上部にロゴを配置 --}}
        <div class="flex items-center gap-2">
            <x-application-logo class="h-8 w-auto fill-current text-[#004A3E]" />
            <h2 class="text-xl font-bold text-gray-800">管理者用メニュー</h2>
        </div>
        <p class="text-[10px] text-gray-400 mt-2 tracking-widest uppercase font-bold">Control Panel</p>
    </div>

    <div class="flex-1 px-2">
        <nav class="flex flex-col space-y-2">
            <x-admin-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                ダッシュボード
            </x-admin-nav-link>
            <x-admin-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.index')">
                ユーザー管理
            </x-admin-nav-link>
        </nav>
    </div>

    {{-- 下部のログアウト --}}
    <div class="mt-auto pt-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded transition">
                ログアウト
            </button>
        </form>
    </div>
</aside>