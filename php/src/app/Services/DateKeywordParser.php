<?php

namespace App\Services;
use Carbon\Carbon;

class DateKeywordParser
{
    /**
     * 日付っぽい検索キーワードを Y-m-d に正規化する
     *
     * @param string $keyword
     * @return string|null
     */
    public function parse(string $keyword): ?string
    {
        try {
            $keyword = trim($keyword);

            // 1/3, 01-03 など（年なし）
            if (preg_match('#^\d{1,2}[/-]\d{1,2}$#', $keyword)) {
                $year = now()->year;

                return Carbon::createFromFormat(
                    'Y/m/d',
                    "{$year}/" . str_replace('-', '/', $keyword)
                )->format('Y-m-d');
            }

            // 2026-01-03
            if (preg_match('#^\d{4}-\d{2}-\d{2}$#', $keyword)) {
                return $keyword;
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}
