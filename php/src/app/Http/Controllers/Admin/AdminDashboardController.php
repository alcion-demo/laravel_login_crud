<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * __construct
     */
    public function __construct(protected User $user, protected Todo $todo)
    {
    }

    /**
     * 新着通知表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $todo = $this->todo->todaySchedule();

        return view('admin.dashboard', [
            'today' => $todo['today'],
            'todaySchedules' => $todo['events'],
            'userCount' => $this->user->count(),
            'todayTodoCount' => $this->todo
                ->whereDate('deadline', $todo['today'])
                ->count(),
        ]);
    }

}
