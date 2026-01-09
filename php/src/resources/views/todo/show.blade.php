@php
    use App\Enums\TodoStatus;
    use App\Enums\TodoPriority;
@endphp

<x-admin-app>
    <x-slot name="title">
        詳細画面
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">詳細画面</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <tbody>
                                <tr class="border-t">
                                    <th class="px-4 py-2 bg-gray-100 w-48 text-left">
                                        タイトル
                                    </th>
                                    <td class="px-4 py-2">
                                        {{ $todo->title }}
                                    </td>
                                </tr>

                                <tr class="border-t">
                                    <th class="px-4 py-2 bg-gray-100 text-left">
                                        詳細
                                    </th>
                                    <td class="px-4 py-2 whitespace-pre-line">
                                        {{ $todo->detail }}
                                    </td>
                                </tr>

                                <tr class="border-t">
                                    <th class="px-4 py-2 bg-gray-100 text-left">
                                        状態
                                    </th>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded {{ TodoStatus::getStatusColor($todo->status) }}">
                                            {{ TodoStatus::labelForValue($todo->status) }}
                                        </span>
                                    </td>
                                </tr>

                                <tr class="border-t">
                                    <th class="px-4 py-2 bg-gray-100 text-left">
                                        期限日・時間
                                    </th>
                                    <td class="px-4 py-2">
                                        <div>{{ $todo->formatted_deadline }}</div>
                                        @if($todo->start_time)
                                            <div class="text-xs text-gray-500">
                                                {{ substr($todo->start_time, 0, 5) }} 〜 {{ substr($todo->end_time, 0, 5) }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="border-t">
                                    <th class="px-4 py-2 bg-gray-100 text-left">
                                        優先度
                                    </th>
                                    <td class="px-4 py-2">
                                        {{ TodoPriority::labelForValue($todo->priority) }}
                                    </td>
                                </tr>

                                <tr class="border-t">
                                    <th class="px-4 py-2 bg-gray-100 text-left">
                                        タグ
                                    </th>
                                    <td class="px-4 py-2">
                                        @foreach ($todo->tags as $tag)
                                            <span class="px-2 py-1 rounded mr-1">
                                                {{ $tag->tag_name }}
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <x-button class="bg-gray-500 text-white hover:bg-gray-600">
                            <a href="{{ route('todos.index') }}">戻る</a>
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-app>
