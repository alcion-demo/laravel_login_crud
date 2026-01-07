<x-admin-app>
    <x-slot name="title">
    登録画面
    </x-slot>

    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-lg">
        <h2 class="text-xl font-bold mb-4">登録画面</h2>
        <form method="POST" action="/todos" class="space-y-4">
            @csrf
            <input type="hidden" name="previous_url" value="{{ request('previous_url') }}">
            <div>
                <label class="block mb-1 font-medium">タイトル</label>
                <input name="title" type="text" value="{{ old('title') }}"
                    class="w-full border rounded px-3 py-2">
                @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-1 font-medium">内容</label>
                <textarea name="detail" rows="5"
                        class="w-full border rounded px-3 py-2">{{ old('detail') }}</textarea>
                @error('detail') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-1 font-medium">期限日</label>
                <input type="date" name="deadline" 
                        {{-- oldがあれば優先、なければURLから飛んできた日付をセット --}}
                        value="{{ old('deadline', $selectedDate) }}" 
                        class="w-full border border-gray-300 rounded px-3 py-2">
                    @error('deadline')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            <div class="flex gap-2">
                <div>
                    <x-input-label>開始時刻</x-input-label>
                    <input type="time" name="start_time" class="border rounded p-2 w-full"value="{{ old('start_time') }}"/>
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-input-label>終了時刻</x-input-label>
                    <input type="time" name="end_time" class="border rounded p-2 w-full" value="{{ old('end_time') }}"/>
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label class="block mb-1 font-medium">タグ</label>
                <input name="tags" type="text" value="{{ old('tags') }}"
                    class="w-full border rounded px-3 py-2">
                @error('tags') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-4 mt-4">
                <!-- 登録ボタン -->
                <x-button class="bg-blue-500 text-white hover:bg-blue-600" type="submit">
                    登録
                </x-button>

                <!-- 戻るボタン -->
                <a href="{{ request('previous_url', url('todos')) }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    戻る
                </a>
            </div>
        </form>
    </div>

</x-admin-app>