<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Todo;
use App\Models\Tag;

class StoreTodo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // 送信元（previous_url）がカレンダー画面かどうかを判定
        $isFromCalendar = str_contains($this->input('previous_url', ''), 'calendar');

        return [
            'title' => 'required|max:20',
            'detail' => 'required|max:300',
            // カレンダー経由なら日付のみ、それ以外（一覧等）なら今日以降を強制
            'deadline'   => $isFromCalendar
                            ? 'required|date'
                            : 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i|required_with:end_time',
            'end_time' => 'nullable|date_format:H:i|required_with:start_time|after:start_time',
            'tag_name' => 'nullable|string',
        ];
    }

        /**
     * リクエストのnameなどの値を再定義するメソッド
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'detail' => '内容',
            'deadline' => '期限日',
            'tag_name' => 'タグ',
            'start_time' => '開始時刻',
            'end_time' => '終了時刻',
        ];
    }

    /**
     * ルールに違反した場合にエラーメッセージを出力する
     *
     * @return array
     */
    public function messages()
    {
        return [
            'deadline.after_or_equal' => ':attribute には今日以降の日付を入力してください。',
            'start_time.required_with' => '終了時刻を入力する場合は、開始時刻も入力してください。',
            'end_time.required_with'   => '開始時刻を入力する場合は、終了時刻も入力してください。',
            'end_time.after_or_equal' => '終了時刻は開始時刻より後にしてください。',
        ];
    }
}
