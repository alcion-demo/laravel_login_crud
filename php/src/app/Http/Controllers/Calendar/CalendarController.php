<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CreateCalendarService;
use Carbon\Carbon;
use App\Enums\Weekday;
use App\Http\Requests\Todo\StoreTodo;
use App\Http\Requests\Todo\UpdateTodo;
use App\Models\Todo;

class CalendarController extends Controller
{
    /**
     * __construct
     */
    public function __construct(protected Todo $todo, protected CreateCalendarService $CreateCalendarService)
    {
    }

    /**
     * カレンダー表示
     * @param Request $request
     * @param CreateCalendarService $calendarService
     * @return view
     */
    public function index(Request $request, CreateCalendarService $calendarService)
    {
        $today = Carbon::today();
        $ym = $request->query('ym', $today->format('Y-m-d'));
        $currentDate = Carbon::parse($ym);

        $selectDayWeek = $request->query('sd');
        if (!isset($selectDayWeek)) {
            $selectDayWeek = Weekday::SUN->value;
        }

        if ($selectDayWeek == Weekday::SUN->value) {
            $weekdaylist = Weekday::DAY_WEEK_LIST;
        } else {
            $weekdaylist = Weekday::DAY_WEEK_ISO_LIST;
        }

        $calendar = $calendarService->calendarDate($currentDate);
        $prev = $calendarService->prevMonth($currentDate);
        $next = $calendarService->nextMonth($currentDate);

        /** 日付のみ配列から予定登録済み日付配列生成 */
        $weeks = $calendarService->drawDayArray($currentDate, $selectDayWeek, $weekdaylist);
        $todos = $this->todo->gettodosForMonth($currentDate);
        $weeksWithtodos  = $calendarService->attachTodos($weeks, $todos, $currentDate);

        return view('calendar.index', [
            'calendar' => $calendar,
            'weeks' => $weeksWithtodos, /** 予定付日付 */
            'prev' => $prev,
            'next' => $next,
            'selectDayWeek' => $selectDayWeek,
            'currentDate' => $currentDate,
            'weekdaylist' => $weekdaylist,
            'today' => $today,
            'todos' => $todos,
        ]);

    }

    /**
     * 選択日付の予定登録画面表示
     * @param　Request $request
     * @return view
     */
    public function create(Request $request)
    {
        $date = $request->query('date');
        return view('calendar.create', compact('date'));
    }

}
