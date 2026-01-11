<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Enums\TodoStatus;
use App\Enums\TodoPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'integer',
        'priority' => 'integer', 
        'day_of_week' => Weekday::class,
    ];

    protected $fillable = [
        'title', 'detail', 'status', 'priority', 'deadline', 'user_id', 'start_time', 'end_time'
    ];

    /**
     * 整形した期限日のアクセサメソッド
     *
     * @return string
     */
    public function getFormattedDeadlineAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['deadline'])->format('Y/m/d');
    }

    /**
     * userとTodo関連付け
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * ログインユーザーのidを取得する
     *
     * @return \Illuminate\Database\Eloquent\Model ログインユーザーのid
     */
    public function findUserId()
    {
        return $this->where('user_id', auth()->user()->id);
    }

    /**
     * tagとTodo関連付け
     *
     * @return App\Models\Tag
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_todo')
                    ->withTimestamps(); // 中間テーブルの timestamps を自動更新
    }

    /** * 編集するTodoのidを取得する
     *
     * @param string $id
     * @return \App\Models\Todo
     */
    public function findEditId(string $id)
    {
        $loginId = $this->find($id);
        return $loginId;
    }
    /**
     * Todoを保存する
     *
     * @param $request
     * @return \App\Models\Todo
     */
    public function storeTodolist($request)
    {
        $todo = $this->create([
            'title' => $request->input('title'),
            'user_id' => auth()->user()->id,
            'detail' => $request->input('detail'),
            'deadline' => $request->input('deadline'),
            'start_time' => $request->input('start_time'),
            'end_time'   => $request->input('end_time'),
        ]);

        return $todo;
    }


    /**
     * Todoリストに紐づくタグを取得する
     *
     * @param $request
     * @return \App\Models\Tag
     */
    public function findFirstTag($request)
    {
        $tagid = $this->tags()->first();

        return $tagid;
    }

    /**
     * Todoリストに紐づくタグを更新する
     * 空白は無視、sync できるID配列を生成
     * @param Request $request
     * @return \App\Models\Tag
     */
    public function updateTodolist(array $data): self
    {
        $this->update([
            'title'    => $data['title'] ?? $this->title,
            'detail'   => $data['detail'] ?? $this->detail,
            'status'   => $data['status'] ?? $this->status,
            'deadline' => $data['deadline'] ?? $this->deadline,
            'priority' => $data['priority'] ?? $this->priority,
            'start_time' => $data['start_time'] ?? $this->start_time,
            'end_time'   => $data['end_time'] ?? $this->end_time,
        ]);

        // タグ配列を取得（空文字は除去）
        $tags = collect($data['tags'] ?? [])
            ->filter(fn($t) => !empty(trim($t)))
            ->map(fn($t) => Tag::firstOrCreate(['tag_name' => $t])->id)
            ->toArray();

        // タグを同期（既存タグと差し替え）
        $this->tags()->sync($tags);

        return $this;
    }

    /**
     * 中間テーブルにタグを登録
     *
     * @param \App\Models\Tag
     * @return array 関連付け結果の配列
     */
    public function attachTag(Tag $tag)
    {
        $tagid = $this->tags()->sync([$tag->id]);

        return $tagid;
    }

    /**
     * Todoリストを削除する
     *
     * @return self 削除したモデルのオブジェクト
     */
    public function deleteTodolist()
    {
        $tags = $this->tags;
        $this->tags()->detach();
        $this->delete();

        foreach ($tags as $tag) {
            // 他の Todo にも紐づいていなければ削除
            if ($tag->todos()->count() === 0) {
                $tag->delete();
            }
        }

        return $this;
    }

    /**
     * 指定月のスケジュール取得
     * @param Carbon $currentDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function gettodosForMonth(Carbon $currentDate)
    {
        $todos = $this->where('user_id', auth()->id())
        ->whereMonth('deadline', $currentDate->month)
        ->whereYear('deadline', $currentDate->year)
        ->get()
        ->groupBy('deadline');
        return $todos;
    }

    /**
     * 本日の予定取得
     * @return array{today: \Carbon\Carbon, events: \Illuminate\Support\Collection}
     */
    public function todaySchedule() {
        $today = Carbon::today();
        $events = $this->where('user_id', auth()->id())
        ->whereDate('deadline', $today)
        ->with('user')
        ->orderBy('deadline', 'asc')
        ->get();

        return [
            'today' => $today,
            'events' => $events,
        ];
    }

}