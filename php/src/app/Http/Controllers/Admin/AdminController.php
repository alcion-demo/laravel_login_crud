<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\UpdateAdmin;

class AdminController extends Controller
{
    /**
     * __construct
     */
    public function __construct(protected User $user, protected Todo $todo)
    {
    }

    /**
     * ユーザー一覧表示
     * @param $request
     * @return view
     */
    public function index(Request $request)
    {

        $keyword = $request->input('keyword', '');
        $users = $this->user->userList($keyword);

        return view('admin.index', compact('users', 'keyword'));
    }

    /**
     * ユーザー編集表示
     * @param App\Models\User
     * @return view
     */
    public function edit(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    public function update(UpdateAdmin $request, User $user)
    {
        $data = $request->validated();

        $data['is_admin'] = $request->has('is_admin');
        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'ユーザーを更新しました');
    }

    /**
     * 投稿内容削除
     * @param App\Models\User
     * @return view
     */
    public function destroy(User $user)
    {
        $user->todos()->delete();

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'ユーザーを削除しました');
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = $this->user->storeAdministrator($validated);
        // 既存の登録フォームを再利用
        return redirect()->route('admin.index')->with('status', '管理者を追加しました');
    }
}


