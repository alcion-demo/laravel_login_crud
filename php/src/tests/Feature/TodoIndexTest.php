<?php

use App\Models\User;
use App\Models\Todo;
use function Pest\Laravel\{actingAs, get};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// 1. ログインしていないとリダイレクトされるか
it('guests are redirected to login', function () {
    get(route('todos.index'))
        ->assertRedirect(route('login'));
});

// 2. 自分のTodoだけが表示され、他人のものは表示されないか
it('displays only the authenticated user\'s todos', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $myTodo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => '私自身のTodo'
    ]);

    $otherTodo = Todo::factory()->create([
        'user_id' => $otherUser->id,
        'title' => '他人（あいつ）のTodo'
    ]);

    actingAs($user)
        ->get(route('todos.index'))
        ->assertStatus(200)
        ->assertSee('私自身のTodo')
        ->assertDontSee('他人（あいつ）のTodo');
});

// 3. 空の時のメッセージ（任意）
it('shows an empty message when no todos exist', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('todos.index'))
        ->assertStatus(200)
        ->assertSee('現在Todoはありません'); // Bladeに書いている文言に合わせてください
});

// 4. 管理者は全ユーザーのTodoが表示されるか
it('displays all todos for admin users', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $userA = User::factory()->create(['name' => 'ユーザーA']);
    $userB = User::factory()->create(['name' => 'ユーザーB']);

    $todoA = Todo::factory()->create(['user_id' => $userA->id, 'title' => 'AさんのTodo']);
    $todoB = Todo::factory()->create(['user_id' => $userB->id, 'title' => 'BさんのTodo']);

    actingAs($admin)
        ->get(route('todos.index')) // 管理者も同じ一覧ページを見ると想定
        ->assertStatus(200)
        ->assertSee('AさんのTodo')
        ->assertSee('BさんのTodo');
});

it('can view own todo detail', function () {
    $user = User::factory()->create();
    $todo = Todo::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->get(route('todos.show', $todo))
        ->assertStatus(200)
        ->assertSee($todo->title);
});

