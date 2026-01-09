@php
use App\Enums\TodoStatus;
use App\Enums\TodoPriority;
@endphp

<x-admin-app>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-message type="status" />

        <div class="py-6">
            {{-- ヘッダー：検索とボタンの配置 --}}
            <div class="flex items-center justify-between mb-6">
                <x-button class="bg-green-500 text-white hover:bg-green-600 rounded-lg">
                    <a href="{{ url('todos/create') }}">登録</a>
                </x-button>

                <form method="GET" action="{{ route('todos.index') }}" class="flex items-center space-x-2">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="キーワード検索..."
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
                    <x-button type="submit" class="bg-gray-500 text-white hover:bg-gray-600 rounded-lg">
                        検索
                    </x-button>
                    @if(!empty(request('keyword')))
                        <a href="{{ route('todos.index') }}" class="px-3 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 text-sm">
                            クリア
                        </a>
                    @endif
                </form>
            </div>

            {{-- ★修正ポイント：テーブルの角を丸くし、フォントを整える --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600">タイトル</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600">状態</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600">期限</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600">優先度</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-600" colspan="3">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($todos as $todo)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-4">
                                    <div class="font-bold text-gray-900">{{ $todo->title }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($todo->detail, 30, '...') }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    {{-- エラー回避のため int 変換して渡す --}}
                                    <span class="px-2 py-1 rounded-md text-xs font-bold {{ TodoStatus::getStatusColor((int)$todo->status) }}">
                                        {{ TodoStatus::labelForValue((int)$todo->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    {{ $todo->formatted_deadline }}
                                </td>
                                <td class="px-4 py-4 text-gray-600">
                                    {{ TodoPriority::labelForValue((int)$todo->priority) }}
                                </td>
                                
                                {{-- 操作ボタン（元のボタンを維持） --}}
                                <td class="px-2 py-4 text-right">
                                    <x-button class="bg-blue-500 text-white hover:bg-blue-600 text-xs rounded-md">
                                        <a href="{{ url('todos/' . $todo->id) }}">詳細</a>
                                    </x-button>
                                </td>
                                <td class="px-2 py-4 text-right">
                                    <x-button class="bg-indigo-500 text-white hover:bg-indigo-600 text-xs rounded-md">
                                        <a href="{{ url('todos/' . $todo->id . '/edit') }}">編集</a>
                                    </x-button>
                                </td>
                                <td class="px-2 py-4 text-right pr-4">
                                    <form method="POST" action="{{ url('todos/' . $todo->id) }}" onsubmit="return confirm('削除しますか？')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" class="bg-red-500 text-white hover:bg-red-600 text-xs rounded-md">
                                            削除
                                        </x-button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $todos->links('vendor.pagination.tailwind2') }}
            </div>
        </div>
    </div>
</x-admin-app>