@php
use App\Enums\Weekday;
use Carbon\Carbon;
@endphp

<x-app>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
        {{ Auth::user()->name }}の予定表
    </h2>

    <x-message type="status" />
    <div class="overflow-x-auto bg-white shadow rounded-xl border border-gray-300 overflow-hidden mt-4">
        <table class="table-fixed w-full text-lg">
            <thead>
                <tr>
                    <th colspan="7" class="text-center text-2xl py-4">
                        <a href="?ym={{ $prev->format('Y-m-d') }}&sd={{ $selectDayWeek }}">&lt;</a>
                        {{ $calendar['year'] }}年{{ $calendar['month'] }}月
                        <a href="?ym={{ $next->format('Y-m-d') }}&sd={{ $selectDayWeek }}">&gt;</a>
                    </th>
                </tr>

                <tr>
                    @foreach ($weekdaylist as $weekday)
                        @php
                            $class = 'border border-gray-300 h-24 w-24 text-center text-xl bg-gray-100';
                            if ($weekday === '日') $class .= ' text-red-500';
                            elseif ($weekday === '土') $class .= ' text-blue-500';
                            if (isset($selectDayWeek) && $selectDayWeek === $weekday) $class .= ' bg-blue-100';
                        @endphp
                        <th class="{{ $class }}">
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
                                $tdClass = 'border border-gray-300 h-24 w-24 text-center align-middle text-lg';
                                if (!empty($cell['day'])) {
                                    if (in_array($cell['weekday'], [$weekdaylist[Weekday::SUN->value] ?? null], true)) $tdClass .= ' text-red-500';
                                    elseif (in_array($cell['weekday'], [$weekdaylist[Weekday::SAT->value] ?? null], true)) $tdClass .= ' text-blue-500';

                                    foreach ($weekdaylist as $key => $name) {
                                        if ($key === $selectDayWeek && $cell['weekday'] === $name) {
                                            $tdClass .= ' font-bold text-blue-600';
                                            break;
                                        }
                                    }
                                    if ($currentDate->year == $today->year
                                        && $currentDate->month == $today->month
                                        && $cell['day'] == $today->day
                                    ) {
                                        $tdClass .= ' bg-yellow-200 font-bold';
                                    }
                                }
                            @endphp

                            <td class="{{ $tdClass }}">
                                @if (!empty(trim((string)($cell['day'] ?? ''))))
                                    <a href="{{ url('/todos/create?date=' . $currentDate->copy()->day($cell['day'])->format('Y-m-d') . '&previous_url=' . urlencode(request()->fullUrl())) }}"
                                       class="block font-bold hover:bg-blue-50 rounded p-1">
                                        {{ $cell['day'] }}
                                    </a>
                                @else
                                    <div class="block p-1">&nbsp;</div>
                                @endif

                                @if (!empty($cell['todos']) && $cell['todos']->count() > 0)
                                    @php
                                        $maxVisible = 3;
                                        $total = $cell['todos']->count();
                                        $visibletodos = $cell['todos']->take($maxVisible);
                                        $hiddentodos = $cell['todos']->skip($maxVisible);
                                    @endphp

                                    <div class="text-sm mt-1 space-y-1" x-data="{ showAll: false }">
                                        @foreach ($visibletodos as $todo)
                                            <div class="bg-blue-100 rounded px-1 truncate hover:bg-blue-200">
                                                <a href="{{ route('todos.edit', ['todo' => $todo->id, 'previous_url' => request()->fullUrl()]) }}">
                                                    <span class="font-semibold text-xs">
                                                        {{-- 開始時間がある場合のみ表示 --}}
                                                        @if($todo->start_time)
                                                            {{ substr($todo->start_time, 0, 5) }}〜{{ substr($todo->end_time, 0, 5) }}：
                                                        @endif
                                                        {{ $todo->title }}
                                                    </span>
                                                </a>
                                            </div>
                                        @endforeach

                                        {{-- alpine.js --}}
                                        <template x-if="showAll">
                                            <div>
                                                @foreach ($hiddentodos as $todo)
                                                    <div class="bg-blue-50 rounded px-1 truncate hover:bg-blue-200">
                                                        <a href="{{ route('todos.edit', ['todo' => $todo->id, 'previous_url' => request()->fullUrl()]) }}">
                                                            <span class="font-semibold text-xs">
                                                                @if($todo->start_time)
                                                                    {{ substr($todo->start_time, 0, 5) }}〜{{ substr($todo->end_time, 0, 5) }}：
                                                                @endif
                                                                {{ $todo->title }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </template>

                                        @if ($total > $maxVisible)
                                            <div 
                                                class="text-xs text-red-500 cursor-pointer underline hover:text-gray-700"
                                                x-on:click="showAll = !showAll"
                                                x-text="showAll ? '閉じる' : '＋{{ $total - $maxVisible }}件の予定'">
                                            </div>
                                        @endif
                                    </div>
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

    <div class="col-12 text-right mt-4 space-x-2">
        <a href="?sd={{ Weekday::SUN->value }}&ym={{ $currentDate }}" 
           class="px-3 py-1 border border-blue-500 text-blue-500 rounded hover:bg-blue-50">
            {{ Weekday::START_DAY_WEEK_NAMES[Weekday::SUN->value] }}
        </a>
        <a href="?sd={{ Weekday::MON->value }}&ym={{ $currentDate }}" 
           class="px-3 py-1 border border-blue-500 text-blue-500 rounded hover:bg-blue-50">
            {{ Weekday::START_DAY_WEEK_NAMES[Weekday::MON->value] }}
        </a>
    </div>
</x-app>