<x-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-center mb-6">
                <div class="w-20 aspect-square rounded-full bg-[#004A3E] flex items-center justify-center shadow-sm p-3">
                    <x-signin-logo class="block h-10 w-auto fill-current text-white" />
                </div>
            </div>

            {{-- メッセージ表示 --}}
            @if(session('status'))
                <div class="text-green-600 text-center mb-4">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="text-red-600 text-center mb-4">{{ session('error') }}</div>
            @endif

            {{-- ログインフォーム --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- メールアドレス --}}
                <div class="mb-4 flex items-center border border-black rounded-md overflow-hidden">
                    <div class="bg-[#004A3E] p-3 flex items-center justify-center">
                        <img src="https://cdn4.iconfinder.com/data/icons/font-awesome-2/2048/f007-32.png"
                             alt="email" class="w-4 h-4 invert">
                    </div>
                    <input id="email" type="email" name="email"
                           placeholder="メールアドレス"
                           class="flex-1 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
                @enderror

                {{-- パスワード --}}
                <div class="mb-6 flex items-center border border-black rounded-md overflow-hidden">
                    <div class="bg-[#004A3E] p-3 flex items-center justify-center">
                        <img src="https://cdn4.iconfinder.com/data/icons/font-awesome-2/2048/f09c-32.png"
                             alt="password" class="w-4 h-4 invert">
                    </div>
                    <input id="password" type="password" name="password"
                           placeholder="パスワード"
                           class="flex-1 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#53B9A8]" required>
                </div>
                @error('password')
                    <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
                @enderror

                {{-- ログインボタン --}}
                <button type="submit"
                        class="w-full bg-[#004A3E] text-white py-2 rounded-md hover:bg-[#53B9A8] transition-colors">
                    ログイン
                </button>
            </form>

            {{-- 補助リンク --}}
            <p class="text-sm text-center text-gray-600 mt-6">
                アカウントをお持ちでないですか？
                <a href="{{ route('register') }}" class="text-[#004A3E] hover:underline">新規登録</a>
            </p>
        </div>
    </div>
</x-layout>
