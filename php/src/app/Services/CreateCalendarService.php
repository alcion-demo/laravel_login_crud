<?php

namespace App\Services;
use Carbon\Carbon;
use App\Enums\Weekday;
use App\Models\Todo;

class CreateCalendarService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * 年月日取得
     * @param Carbon $currentDate
     * @return carbon
     */
    public function calendarDate(Carbon $currentDate)
    {
        return [
            'year' => $currentDate->year,
            'month' => $currentDate->month,
            'day' => $currentDate->day,
        ];
    }

    /**
     * カレンダーの日付配列作成
     * @param Carbon $currentDate
     * @param int $startWeek
     * @param array $weekdaylist
     * @return array
     */
    public function drawDayArray(Carbon $currentDate, int $startWeek = Weekday::SUN->value, array $weekdaylist = Weekday::DAY_WEEK_LIST)
    {
        $weeks = [];
        $week = [];

        $firstWeekday = $currentDate->copy()->startOfMonth()->dayOfWeek;
        $lastweekday = $currentDate->copy()->endOfMonth()->dayOfWeek;
        $lastday = $currentDate->copy()->endOfMonth()->day;

        /** 月初余白計算 */
        if ($firstWeekday < $startWeek) {
            /** 月曜開始 */
            $blankCount = Weekday::WEEKDAYS - ($startWeek - $firstWeekday);
        } else {
            $blankCount = $firstWeekday - $startWeek;
        }

        for ($i = 0; $i < $blankCount; $i++) {
            $weekdayIndex = ($startWeek + $i) % Weekday::WEEKDAYS;
            $week[] = ['day' => null, 'weekday' => $weekdaylist[$weekdayIndex] ?? null];
        }

        for ($day = 1; $day <= $lastday; $day++) {
            $weekdayIndex = ($startWeek + count($week)) % Weekday::WEEKDAYS;
            $week[] = ['day' => $day, 'weekday' => $weekdaylist[$weekdayIndex] ?? null];

            if (count($week) === Weekday::WEEKDAYS) {
                /** 全体週分 */
                $weeks[] = $week;
                /** 1週間分 */
                $week = [];
            }
        }

        /** 月末余白計算 */
        $lastblank = Weekday::WEEKDAYS - count($week);
        if (!($startWeek == Weekday::SUN->value && $lastweekday == Weekday::SAT->value || $startWeek == Weekday::MON->value && $lastweekday == Weekday::SUN->value)) {
            for ($i = 0; $i < $lastblank; $i++) {
                $weekdayIndex = ($startWeek + count($week)) % Weekday::WEEKDAYS;
                $week[] = ['day' => null, 'weekday' => $weekdaylist[$weekdayIndex] ?? null];
            }
        }

        /** 最終週 */
        if ($week) {
            $weeks[] = $week;
        }
        return $weeks;

    }

    /**
     * 前月取得
     * @param Carbon $currentDate
     * @return Carbon
     */
    public function prevmonth(Carbon $currentDate)
    {
        return $currentDate->copy()->subMonth()->startOfMonth();
    }

    /**
     * 次月取得
     * @param Carbon $currentDate
     * @return Carbon
     */
    public function nextmonth(Carbon $currentDate)
    {
        return $currentDate->copy()->addMonth()->startOfMonth();
    }

    /**
     * スケジュール付与
     * @param array $weeks
     * @param mixed $schedules
     * @param Carbon $currentDate
     * @return array
     */
    public function attachTodos(array $weeks, $todos, Carbon $currentDate)
    {
        $newWeeks = [];

        foreach ($weeks as $week) {
            $newWeek = [];

            foreach ($week as $cell) {
                $cell['todos'] = collect();

                if (!empty($cell['day'])) {
                    $date = Carbon::createFromDate(
                        $currentDate->year,
                        $currentDate->month,
                        $cell['day']
                    )->format('Y-m-d');

                    if (isset($todos[$date])) {
                        $cell['todos'] = $todos[$date];
                    }
                }

                $newWeek[] = $cell;
            }

            $newWeeks[] = $newWeek;
        }

        return $newWeeks;
    }
}
