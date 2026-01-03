<?php

namespace App\Enums;

enum TodoStatus: int
{
    /** @var TodoStatus|Pending (未着手)*/
    case Pending = 1;
    /** @var TodoStatus|InProgress (着手中)*/
    case InProgress = 2;
    /** @var TodoStatus|Completed (完了)*/
    case Completed = 3;

    /**
     * label作成
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => '未着手',
            self::InProgress => '処理中',
            self::Completed => '完了',
        };
    }

    /**
     * 状態色設定
     */
    public static function getStatusColor(int $value): string
    {
        return match ($value) {
            1 => 'bg-gray-200 text-gray-800',
            2 => 'bg-yellow-200 text-yellow-800',
            3 => 'bg-green-200 text-green-800',
            default => 'bg-gray-200 text-gray-800',
        };
    }

    /**
     * 初期値設定
     */
    public static function default(): self
    {
        return self::Pending;
    }


    /**
     * ラベル取得
     */
    public static function labelForValue(int $value): string
    {
        return match ($value) {
            1 => '未着手',
            2 => '処理中',
            3 => '完了',
            default => '未着手',
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
