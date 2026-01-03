<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Todo;
use App\Models\Tag;
use App\Enums\TodoStatus;
use App\Enums\TodoPriority;


class UpdateTodo extends FormRequest
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
        return [
            'title' => 'required|max:20',
            'detail' => 'required|max:300',
            'deadline' => 'required|date',
            'status' => 'required|in:' . implode(',', array_keys(TodoStatus::toSelectArray())),
            'priority' => 'required|in:' . implode(',', array_keys(TodoPriority::toSelectArray())),
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
            'status' => '状態',
            'priority' => '優先度',
            'tag_name' => 'タグ',
        ];
    }

    /**
     * ルールに違反した場合にエラーメッセージを出力
     *
     * @return array
     */
    public function messages()
    {
        $statusLabels = TodoStatus::toSelectArray();
        $statusLabels = implode('、', $statusLabels);

        return [
            'status.in' => ':attribute には ' . $statusLabels . ' のいずれかを指定してください。',
        ];
    }
}
