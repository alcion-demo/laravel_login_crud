<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\User;
use App\Models\Tag;
use App\Http\Requests\Todo\StoreTodo;
use App\Http\Requests\Todo\UpdateTodo;
use Illuminate\Pagination\Paginator;
use App\Services\DateKeywordParser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TodoController extends Controller
{
    use AuthorizesRequests;

    /**
     * __construct
     */
    public function __construct(protected Todo $todo, protected Tag $tag)
    {
        // $this->imageService = $imageService;
    }
    /**
     * Todo一覧表示
     * @return view
     */
    public function index(Request $request, DateKeywordParser $dateParser)
    {
        $keyword = trim($request->query('keyword'));

        $todosQuery = $this->todo->query();

        if (!auth()->user()->is_admin) {
            $todosQuery->where('user_id', auth()->id());
        }

        // 検索条件を追加
        $todosQuery->when($keyword, function ($query) use ($keyword, $dateParser) {
            $query->where(function ($q) use ($keyword, $dateParser) {
                $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('detail', 'like', "%{$keyword}%");

                if ($date = $dateParser->parse($keyword)) {
                    $q->orWhereDate('deadline', $date);
                }
            });
        });

        $todos = $todosQuery->paginate(10)->withQueryString();

        return view('todo.index', compact('todos', 'keyword'));
    }

    /**
     * 新規作成画面表示
     * @return view
     */
    public function create(Request $request)
    {
        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        return view('todo.create', compact('selectedDate'));
    }

    /**
     * Todoデータ登録
     * @param array $request
     * @return view
     */
    public function store(StoreTodo $request)
    {
        $validated = $request->validated();
        $todo = $this->todo->storeTodolist($request); 

        if ($request->filled('tags')) {
            $tagNames = explode(',', $request->input('tags')); 
            foreach ($tagNames as $name) {
                $tag = Tag::firstOrCreate(['tag_name' => trim($name)]);
                $todo->tags()->attach($tag->id);
            }
        }

        $redirectUrl = $request->input('previous_url') ?: url('todos');

        return redirect()->to($redirectUrl)->with('status', 'Todoを登録しました!');
    }

    /**
     * Todo編集表示
     * @param $id
     * @return view
     */
    public function show(string $id)
    {
        $todo = $this->todo->findEditId($id);
        $this->authorize('view', $todo);

        return view('todo.show', compact('todo'));
    }

    /**
     * Todo編集表示
     * @param $id
     * @return view
     */
    public function edit(string $id)
    {
        $todo = $this->todo->findEditId($id);

        return view('todo.edit', compact('todo'));
    }

    /**
     * Todo更新
     * @param array $request
     * @param $id
     * @return view
     */
    public function update(UpdateTodo $request, string $id)
    {
        $validated = $request->validated();
        $tags = $request->input('tags', []);

        $todo = $this->todo->findEditId($id);
        $this->authorize('update', $todo);
        $todo->updateTodolist(array_merge($validated, ['tags' => $tags]));

        $redirectUrl = $request->input('previous_url') ?: route('todos.index');

        return redirect()->to($redirectUrl)->with('status', '更新しました');
    }

    /**
     * Todo削除
     * @param $id
     * @return view
     */
    public function destroy(string $id)
    {

        $todo = $this->todo->findEditId($id);
        $this->authorize('delete', $todo);
        $todo->DeleteTodolist();
        return redirect()
            ->route('todos.index')
            ->with('status', 'Todoを削除しました');
    }
}
