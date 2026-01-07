<x-admin-app>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">ユーザー編集</h1>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            @csrf
            @method('PUT')

            {{-- 名前 --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">名前</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            {{-- メール --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">メール</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            {{-- 権限 --}}
            <div class="mb-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }} class="rounded border-gray-300">
                    管理者権限
                </label>
            </div>

            {{-- 更新ボタン --}}
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                更新
            </button>
            <!-- 戻るボタン -->
            <a href="{{ route('admin.users.index') }}" class="inline-block rounded-lg bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                戻る
            </a>
        </form>
    </div>
</x-admin-app>
