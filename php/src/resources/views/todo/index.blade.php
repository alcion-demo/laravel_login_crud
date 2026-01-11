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

            {{-- ★ここから修正：isEmpty()で大きく分岐させる --}}
            @if($todos->isEmpty())
                {{-- データがない時：テーブルを表示せず、メッセージカードを出す --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-16 text-center shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 font-bold text-lg">現在Todoはありません</p>
                    <p class="text-gray-400 text-sm mt-2">新しいTodoを登録して、作業を開始しましょう！</p>
                </div>
            @else
                {{-- データがある時：テーブルを表示する --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-gray-600">タイトル</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-600">詳細</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-600">状態</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-600">期限</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-600">優先度</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-600">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($todos as $todo)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-gray-900">{{ $todo->title }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($todo->detail, 30, '...') }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 rounded-md text-xs font-bold {{ TodoStatus::getStatusColor((int)$todo->status) }}">
                                            {{ TodoStatus::labelForValue((int)$todo->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-gray-600">{{ $todo->formatted_deadline }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ TodoPriority::labelForValue((int)$todo->priority) }}</td>
                                    <td class="px-2 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ url('todos/' . $todo->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-md hover:bg-blue-600 transition">
                                                詳細
                                            </a>
                                            <a href="{{ url('todos/' . $todo->id . '/edit') }}" class="inline-flex items-center px-3 py-1 bg-indigo-500 text-white text-xs font-bold rounded-md hover:bg-indigo-600 transition">
                                                編集
                                            </a>
                                            <form method="POST" action="{{ url('todos/' . $todo->id) }}" onsubmit="return confirm('削除しますか？')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-md hover:bg-red-600 transition">
                                                    削除
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-6">
                {{ $todos->links('vendor.pagination.tailwind2') }}
            </div>
        </div>
    </div>
</x-admin-app>