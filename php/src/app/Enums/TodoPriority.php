<?php

namespace App\Enums;

enum TodoPriority: int
{
    /** @var TodoPriority|S (大)*/
    case Critical = 1;
    /** @var TodoPriority|A (中)*/
    case Major = 2;
    /** @var TodoPriority|B (小)*/
    case Minor = 3;
    /** @var TodoPminerriority|C (低)*/
    case other = 4;

    public function label(): string
    {
        return match ($this) {
            self::Critical => 'Critical',
            self::Major => 'Major',
            self::Minor => 'Minor',
            self::other => 'other',
        };
    }

    /**
     *  * 初期値設定
     */
    public static function default(): self
    {
        return self::other;
    }

    /**
     * ラベル取得
     */
    public static function labelForValue(int|string $value): string
    {
        $value = (int)$value;

        return match ($value) {
            1 => 'Critical',
            2 => 'Major',
            3 => 'Minor',
            4 => 'other',
            default => 'other',
        };
    }

    /**
     * 選択項目
     */
    public static function toSelectArray(): array
    {
        $arr = [];
        foreach (self::cases() as $case) {
            $arr[$case->value] = $case->label();
        }
        return $arr;
    }
}
