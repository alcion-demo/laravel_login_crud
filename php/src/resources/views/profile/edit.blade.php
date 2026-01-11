<x-admin-app>
    <div class="max-w-7xl mx-auto pb-12 relative z-0">
        <div class="mt-4"><x-message type="status" /></div>

        {{-- メインの更新フォーム --}}
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('patch')

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-8 mt-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6">基本情報</h2>
                
                <div class="flex items-center gap-8 mb-8" x-data="{ photoPreview: null }">
                    <div class="shrink-0 text-center">
                        <template x-if="!photoPreview">
                            <div>
                                @if ($user->avatar_path)
                                    <img src="{{ asset('storage/' . $user->avatar_path) }}" 
                                         class="h-24 w-24 object-cover rounded-full border-2 border-gray-100 shadow-sm mx-auto">
                                    
                                    {{-- ★削除ボタン：onclickで下の隠しフォームを送信する --}}
                                    <button type="button" 
                                            onclick="if(confirm('プロフィール画像を削除しますか？')) document.getElementById('avatar-delete-form').submit();"
                                            class="mt-2 text-xs text-red-600 hover:text-red-800 font-bold underline">
                                        画像を削除
                                    </button>
                                @else
                                    <div class="h-24 w-24 rounded-full border-2 border-gray-100 shadow-sm bg-gray-200 flex items-center justify-center mx-auto">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </template>
                        
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="h-24 w-24 object-cover rounded-full border-2 border-gray-100 shadow-sm mx-auto">
                        </template>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">プロフィール画像</label>
                        <input type="file" name="avatar" 
                               class="text-transparent w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
                               x-ref="avatar"
                               @change="
                                    const file = $refs.avatar.files[0];
                                    if (!file) return;
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL(file);
                               ">
                        @error('avatar')
                            <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">※最大サイズ: 2MB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">名前</label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">メールアドレス</label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6">パスワード変更 (変更する場合のみ入力)</h2>
                <div class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">現在のパスワード</label>
                        <input name="current_password" type="password" autocomplete="off" 
                               class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">新しいパスワード</label>
                        <input name="new_password" type="password" autocomplete="new-password" 
                               class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">新しいパスワード (確認用)</label>
                        <input name="new_password_confirmation" type="password" 
                               class="w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-sm font-bold shadow-md hover:bg-blue-700 transition">
                    設定を保存する
                </button>
            </div>
        </form>

        {{-- ★ここが重要：削除用の隠しフォーム（メインフォームの外に置く） --}}
        <form id="avatar-delete-form" method="POST" action="{{ route('profile.avatar.destroy') }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-admin-app>