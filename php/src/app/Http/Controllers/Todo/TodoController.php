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

class TodoController extends Controller
{
    public function __construct(protected Todo $todo, protected Tag $tag)
    {
        // $this->imageService = $imageService;
    }
    /**
     * Todo一覧表示
     * @return view
     */
    public function index()
    {
        $todos = $this->todo->findUserId()->paginate(10);
        return view('todo.index', compact('todos'));
    }

    /**
     * 新規作成画面表示
     * @return view
     */
    public function create()
    {
        return view('todo.create');
    }

    /**
     * Todoデータ登録
     * @param array $request
     * @return view
     */
    public function store(StoreTodo $request)
    {
        $validated = $request->validated();
        $todo = $this->todo->storeTodoList($request);
        $tag = $this->tag->storeTag($request);
        $todo->AttachTag($tag);

        return redirect('todos')->with('status', 'Todoを登録しました!');
    }

    /**
     * Todo編集表示
     * @param $id
     * @return view
     */
    public function show(string $id)
    {
        $todo = $this->todo->findEditId($id);

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
        $todo->updateTodolist(array_merge($validated, ['tags' => $tags]));

        return redirect('todos')->with('status', 'Todoを更新しました!');
    }

    /**
     * Todo削除
     * @param $id
     * @return view
     */
    public function destroy(string $id)
    {
        $todo = $this->todo->findEditId($id);
        $todo->DeleteTodolist();
        return redirect()
            ->route('todos.index')
            ->with('status', 'Todoを削除しました');
    }
}
