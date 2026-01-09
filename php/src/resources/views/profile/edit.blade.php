<x-admin-app>
    <div class="max-w-7xl mx-auto pb-12 relative z-0">
        <div class="mt-4"><x-message type="status" /></div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('patch')

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-8 mt-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6">基本情報</h2>
                
                <div class="flex items-center gap-8 mb-8">
                    <div class="shrink-0 text-center">
                        <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-avatar.png') }}" 
                             class="h-24 w-24 object-cover rounded-full border-2 border-gray-100 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">プロフィール画像</label>
                        {{-- text-transparent で「ファイルが選択されていません」を隠しています --}}
                        <input type="file" name="avatar" class="text-transparent w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        @error('avatar')
                            <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">※最大サイズ: 2MB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">名前</label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">メールアドレス</label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6">パスワード変更 (変更する場合のみ入力)</h2>
                
                <div class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">現在のパスワード</label>
                        <input name="current_password" type="password" autocomplete="off" class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">新しいパスワード</label>
                        <input name="new_password" type="password" autocomplete="new-password" class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">新しいパスワード (確認用)</label>
                        <input name="new_password_confirmation" type="password" class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-sm font-bold shadow-md hover:bg-blue-700 transition">
                    設定を保存する
                </button>
            </div>
        </form>
    </div>
</x-admin-app>