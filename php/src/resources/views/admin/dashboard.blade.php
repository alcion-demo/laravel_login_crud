<x-admin-app>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm text-gray-500">登録ユーザー数</p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $userCount }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm text-gray-500">本日の予定数</p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $todayTodoCount }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm text-gray-500">システム状態</p>
                <p class="text-lg font-semibold text-green-600">
                    正常
                </p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">
                📅 今日（{{ $today->format('Y年m月d日') }}）の予定
            </h3>

            @if($todaySchedules->isEmpty())
                <p class="text-gray-500">本日の予定はありません。</p>
            @else
                <div class="space-y-3">
                    @foreach($todaySchedules as $schedule)
                        <div class="border rounded-xl p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-800">
                                    {{ $schedule->title }}
                                </p>
                                <span class="text-sm text-gray-500">
                                    {{ $schedule->start_time }} 〜 {{ $schedule->end_time }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mt-1">
                                👤 {{ $schedule->user->name }}
                            </p>

                            @if($schedule->memo)
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ $schedule->memo }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-admin-app>