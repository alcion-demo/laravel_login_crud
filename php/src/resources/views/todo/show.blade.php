@php
    use App\Enums\TodoStatus;
    use App\Enums\TodoPriority;
@endphp

<x-app>
    <x-slot name="title">
        詳細画面
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    詳細画面
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>タイトル</th>
                                <td>{{ $todo->title }}</td>
                            </tr>
                            <tr>
                                <th>詳細</th>
                                <td>{{ $todo->detail }}</td>
                            </tr>
                            <tr>
                                <th>状態</th>
                                <td>
                                    <span class="badge {{ TodoStatus::getStatusColor($todo->status) }}">
                                        {{ TodoStatus::getDescription($todo->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>期限日</th>
                                <td>{{ $todo->formatted_deadline }}</td>
                            </tr>
                            <tr>
                                <th>優先度</th>
                                <td>{{ TodoPriority::getDescription($todo->priority) }}</td>
                            </tr>
                            <tr>
                                <th>タグ</th>
                                <td>
                                    @foreach ($todo->tags as $tag)
                                        <span class="badge bg-secondary me-1">{{ $tag->tag_name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ url('todos') }}" class="btn btn-info">戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app>