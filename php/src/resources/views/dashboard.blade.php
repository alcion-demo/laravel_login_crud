<x-app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="bg-white rounded-2xl shadow p-6 mb-6 mt-4">
        <h3 class="text-lg font-semibold mb-3">
            📅 今日（{{ $today->format('Y年m月d日') }}）の予定
        </h3>

        @if($todaySchedules->isEmpty())
            <p class="text-gray-500">本日の予定はありません。</p>
        @else
            <ul class="space-y-2">
                @foreach($todaySchedules as $schedule)
                    <li class="border-b pb-2">
                        <p class="font-medium text-gray-800">
                            {{ $schedule->title }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $schedule->start_time }} 〜 {{ $schedule->end_time }}
                        </p>
                        @if($schedule->memo)
                            <p class="text-sm text-gray-500">{{ $schedule->memo }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app>