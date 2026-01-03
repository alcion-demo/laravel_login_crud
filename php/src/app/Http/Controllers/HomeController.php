<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class HomeController extends Controller
{
    /**
     * __construct
     */
    public function __construct(protected Todo $todo) {}

    public function index()
    {
        $todo = $this->todo->todaySchedule();

        return view('dashboard', [
            'today' => $todo['today'],
            'todaySchedules' => $todo['events'],
        ]);
    }

}
