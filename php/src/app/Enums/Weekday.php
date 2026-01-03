<?php

namespace App\Enums;

enum Weekday: int
{
    /** 曜日見出しindex用 */
    case SUN = 0;
    case MON = 1;
    case TUE = 2;
    case WED = 3;
    case THU = 4;
    case FRI = 5;
    case SAT = 6;

    public const WEEKDAYS = 7;

    public const DAY_WEEK_LIST = [
        0 => '日',
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
    ];

    public const DAY_WEEK_ISO_LIST = [
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
        7 => '日',
    ];

    public const START_DAY_WEEK_NAMES = [
        self::SUN->value => '日曜始まり',
        self::MON->value => '月曜始まり',
    ];
}
