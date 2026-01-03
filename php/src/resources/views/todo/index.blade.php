@php
use App\Enums\TodoStatus;
use App\Enums\TodoPriority;
@endphp

<x-app>
    <x-slot name="title">
        一覧画面
    </x-slot>
    <x-message type="status" />
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- 登録ボタン -->
                    <x-button class="bg-green-500 text-white hover:bg-green-600 mb-4">
                        <a href="{{ url('todos/create') }}">登録</a>
                    </x-button>

                    <!-- Todo 一覧 -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">タイトル</th>
                                    <th class="px-4 py-2 text-left">詳細</th>
                                    <th class="px-4 py-2 text-left">状態</th>
                                    <th class="px-4 py-2 text-left">期限日</th>
                                    <th class="px-4 py-2 text-left">優先度</th>
                                    <th class="px-4 py-2 text-left">タグ</th>
                                    <th class="px-4 py-2"></th>
                                    <th class="px-4 py-2"></th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todos as $todo)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $todo->title }}</td>
                                        <td class="px-4 py-2">{{ Str::limit($todo->detail, 30, '...') }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded {{ TodoStatus::getStatusColor($todo->status) }}">
                                                {{ TodoStatus::labelForValue($todo->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $todo->formatted_deadline }}</td>
                                        <td class="px-4 py-2">{{ TodoPriority::labelForValue($todo->priority) }}</td>
                                        <td class="px-4 py-2">
                                            @foreach ($todo->tags as $tag)
                                                <span class="px-2 py-1 rounded mr-1">{{ $tag->tag_name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-2">
                                            <x-button class="bg-blue-500 text-white hover:bg-blue-600">
                                                <a href="{{ url('todos/' . $todo->id) }}">詳細</a>
                                            </x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <x-button class="bg-indigo-500 text-white hover:bg-indigo-600">
                                                <a href="{{ url('todos/' . $todo->id . '/edit') }}">編集</a>
                                            </x-button>
                                        </td>
                                        <td class="px-4 py-2">
                                            <form method="POST" action="{{ url('todos/' . $todo->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" class="bg-red-500 text-white hover:bg-red-600">
                                                    削除
                                                </x-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- ページネーション -->
                    <div class="mt-4">
                        {{ $todos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
