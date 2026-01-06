@php
use App\Enums\Weekday;
use Carbon\Carbon;
@endphp

<x-app>
    <div class="max-w-7xl mx-auto">
        {{-- ヘッダー：タイトルと年月ナビ --}}
        <div class="flex items-center justify-between mb-8">
        {{-- 左側：タイトル --}}
        <div class="mb-6 pt-2">
            <div class="flex items-baseline gap-2">
                <h1 class="text-2xl font-black text-gray-900 tracking-tighter">
                    {{ Auth::user()->name }}
                </h1>
                <span class="text-sm font-medium text-gray-400">/ Schedule</span>
            </div>
            <div class="mt-2 w-8 h-0.5 bg-blue-500 rounded-full"></div>
        </div>
            <div class="flex items-center bg-white shadow-sm border border-gray-200 rounded-xl p-1">
                <a href="?ym={{ $prev->format('Y-m-d') }}&sd={{ $selectDayWeek }}" class="p-2 hover:bg-gray-100 rounded-lg transition">&lt;</a>
                <span class="px-6 font-bold text-lg text-gray-700">{{ $calendar['year'] }}年 {{ $calendar['month'] }}月</span>
                <a href="?ym={{ $next->format('Y-m-d') }}&sd={{ $selectDayWeek }}" class="p-2 hover:bg-gray-100 rounded-lg transition">&gt;</a>
            </div>
        </div>

        <x-message type="status" />

        <div class="mt-4">
            <table class="table-fixed w-full border-separate border-spacing-2">
                <thead>
                    <tr>
                        @foreach ($weekdaylist as $weekday)
                            @php
                                $headClass = 'py-3 text-sm font-semibold uppercase text-center';
                                if ($weekday === '日') $headClass .= ' text-red-500';
                                elseif ($weekday === '土') $headClass .= ' text-blue-500';
                                else $headClass .= ' text-gray-400';
                            @endphp
                            <th class="{{ $headClass }}">
                                {{ $weekday }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($weeks as $week)
                        <tr>
                            @foreach($week as $cell)
                                @php
                                    $tdClass = 'h-36 min-w-[120px] rounded-2xl align-top p-2 border border-transparent transition-all duration-200';
                                    $dateTextClass = 'text-gray-900';

                                    if (!empty($cell['day'])) {
                                        $tdClass = 'h-36 min-w-[120px] rounded-2xl align-top p-2 border border-gray-100 bg-white shadow-sm transition-all duration-200 hover:shadow-md';

                                        // ★元のコードの判定ロジックを忠実に再現
                                        if (in_array($cell['weekday'], [$weekdaylist[Weekday::SUN->value] ?? null], true)) {
                                            $dateTextClass = 'text-red-500';
                                        } elseif (in_array($cell['weekday'], [$weekdaylist[Weekday::SAT->value] ?? null], true)) {
                                            $dateTextClass = 'text-blue-500';
                                        }

                                        // 当日ハイライト
                                        if ($currentDate->year == $today->year && $currentDate->month == $today->month && $cell['day'] == $today->day) {
                                            $tdClass = 'h-36 min-w-[120px] rounded-2xl align-top p-2 border border-yellow-200 bg-yellow-50 shadow-yellow-100 transition-all duration-200';
                                            $dateTextClass .= ' font-bold';
                                        }
                                    }
                                @endphp

                                <td class="{{ $tdClass }}">
                                    @if (!empty(trim((string)($cell['day'] ?? ''))))
                                        <div class="mb-1">
                                            <a href="{{ url('/todos/create?date=' . $currentDate->copy()->day($cell['day'])->format('Y-m-d') . '&previous_url=' . urlencode(request()->fullUrl())) }}"
                                               class="text-sm font-bold {{ $dateTextClass }} hover:underline p-1 block">
                                                {{ $cell['day'] }}
                                            </a>
                                        </div>

                                        @if (!empty($cell['todos']) && $cell['todos']->count() > 0)
                                            @php
                                                $maxVisible = 3;
                                                $total = $cell['todos']->count();
                                                $visibletodos = $cell['todos']->take($maxVisible);
                                                $hiddentodos = $cell['todos']->skip($maxVisible);
                                            @endphp

                                            <div class="space-y-1" x-data="{ showAll: false }">
                                                {{-- 最初の3件（青付箋） --}}
                                                @foreach ($visibletodos as $todo)
                                                    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r px-1.5 py-0.5 truncate hover:bg-blue-100 transition shadow-sm">
                                                        <a href="{{ route('todos.edit', ['todo' => $todo->id, 'previous_url' => request()->fullUrl()]) }}" class="block">
                                                            <div class="flex flex-col text-[10px] leading-tight text-gray-700">
                                                                @if($todo->start_time)
                                                                    <span class="text-[9px] text-blue-600 font-bold mb-0.5">
                                                                        {{ substr($todo->start_time, 0, 5) }}〜{{ substr($todo->end_time, 0, 5) }}
                                                                    </span>
                                                                @endif
                                                                <span class="font-semibold truncate">{{ $todo->title }}</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach

                                                {{-- 4件目以降（ここも同じ bg-blue-50 に統一しました） --}}
                                                <div x-show="showAll" x-cloak class="space-y-1 mt-1">
                                                    @foreach ($hiddentodos as $todo)
                                                        <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r px-1.5 py-0.5 truncate hover:bg-blue-100 transition shadow-sm">
                                                            <a href="{{ route('todos.edit', ['todo' => $todo->id, 'previous_url' => request()->fullUrl()]) }}" class="block">
                                                                <div class="flex flex-col text-[10px] leading-tight text-gray-700">
                                                                    @if($todo->start_time)
                                                                        <span class="text-[9px] text-blue-600 font-bold mb-0.5">
                                                                            {{ substr($todo->start_time, 0, 5) }}〜{{ substr($todo->end_time, 0, 5) }}
                                                                        </span>
                                                                    @endif
                                                                    <span class="font-semibold truncate">{{ $todo->title }}</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if ($total > $maxVisible)
                                                    <button 
                                                        type="button"
                                                        class="text-[9px] text-blue-500 font-bold hover:underline mt-1 block w-full text-left"
                                                        x-on:click="showAll = !showAll"
                                                        x-text="showAll ? '閉じる' : '＋{{ $total - $maxVisible }}件の予定'">
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="block p-1">&nbsp;</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 下部：月曜/日曜始まり切り替え --}}
        <div class="flex justify-end mt-8 pb-10">
            <div class="inline-flex bg-white p-1 rounded-xl shadow-sm border border-gray-200">
                <a href="?sd={{ Weekday::SUN->value }}&ym={{ $currentDate->format('Y-m-d') }}"
                   class="px-4 py-2 text-sm font-bold rounded-lg transition {{ $selectDayWeek == Weekday::SUN->value ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                    日曜始まり
                </a>
                <a href="?sd={{ Weekday::MON->value }}&ym={{ $currentDate->format('Y-m-d') }}"
                   class="px-4 py-2 text-sm font-bold rounded-lg transition {{ $selectDayWeek == Weekday::MON->value ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                    月曜始まり
                </a>
            </div>
        </div>
    </div>
</x-app>