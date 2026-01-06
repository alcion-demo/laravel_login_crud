@php
use App\Enums\TodoStatus;
use App\Enums\TodoPriority;
@endphp

<x-app>
    <x-slot name="title">
        編集画面
    </x-slot>
    <div class="max-w-3xl mx-auto py-6">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ url('todos/' . $todo->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="previous_url" value="{{ request('previous_url') }}">
                    <!-- タイトル -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">タイトル</label>
                        <input type="text" name="title" 
                               value="{{ old('title', $todo->title) }}" 
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 内容 -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">内容</label>
                        <textarea name="detail" rows="5" 
                                  class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">{{ old('detail', $todo->detail) }}</textarea>
                        @error('detail')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 状態 -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">状態</label>
                        <select name="status" class="w-full border border-gray-300 rounded px-3 py-2">
                            @foreach(TodoStatus::toSelectArray() as $key => $label)
                                <option value="{{ $key }}" {{ $key == old('status', $todo->status) ? 'selected' : '' }}>
                                    {{ TodoStatus::labelForValue($key) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 期限日 -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">期限日</label>
                        <input type="date" name="deadline" 
                               value="{{ old('deadline', $todo->deadline) }}" 
                               class="w-full border border-gray-300 rounded px-3 py-2">
                        @error('deadline')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <div>
                            <x-input-label>開始時刻</x-input-label>
                            <input type="time" name="start_time" class="border rounded p-2 w-full"value="{{ old('start_time', $todo->start_time ? substr($todo->start_time, 0, 5) : '') }}"/>
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-input-label>終了時刻</x-input-label>
                            <input type="time" name="end_time" class="border rounded p-2 w-full" value="{{ old('end_time', $todo->end_time ? substr($todo->end_time, 0, 5) : '') }}"/>
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 優先度 -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">優先度</label>
                        <select name="priority" class="w-full border border-gray-300 rounded px-3 py-2">
                            @foreach(TodoPriority::toSelectArray() as $key => $label)
                                <option value="{{ $key }}" {{ $key == old('priority', $todo->priority) ? 'selected' : '' }}>
                                    {{ TodoPriority::labelForValue($key) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- タグ -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">タグ</label>
                        @php
                            $tags = old('tags', $todo->tags->pluck('tag_name')->toArray());
                            if(empty($tags)) { $tags = ['']; } // 空でも1つ入力欄表示
                        @endphp
                        @foreach($tags as $index => $tag)
                            <input type="text" name="tags[]" 
                                   value="{{ $tag }}" 
                                   class="w-full border border-gray-300 rounded px-3 py-2 mb-2">
                        @endforeach
                    </div>

                    <!-- 更新ボタン -->
                    <div class="flex gap-4 mt-4">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            更新
                        </button>
                        <!-- 戻るボタン -->
                        <a href="{{ request('previous_url', url('todos')) }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            戻る
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app>
