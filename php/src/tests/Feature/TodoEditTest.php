<?php

use App\Models\User;
use App\Models\Todo;
use function Pest\Laravel\{actingAs, patch, assertDatabaseHas, assertDatabaseMissing};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
/**
 * 正常系：更新時（すべての項目を入力して更新）
 */
it('can update a todo with all fields', function () {
    $user = User::factory()->create();
    $todo = Todo::factory()->create(['user_id' => $user->id]);

    $updatedData = [
        'title'      => '新しいタイトル',
        'detail'     => '新しい詳細',
        'status'     => '3',
        'deadline'   => '2026-12-31',
        'start_time' => '10:00',
        'end_time'   => '11:00',
        'priority'   => 3,
    ];

    actingAs($user)
        ->patch(route('todos.update', $todo), $updatedData)
        ->assertRedirect(route('todos.index'))
        ->assertSessionHas('status');

    assertDatabaseHas('todos', array_merge(['id' => $todo->id], $updatedData));
});

/**
 * 異常系：未入力時（すべて空で送信）
 */
it('fails to update when all fields are empty', function () {
    $user = User::factory()->create();
    $todo = Todo::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->patch(route('todos.update', $todo), [
            'title' => '',
            'detail' => '',
            // 他のバリデーションのかかる項目も空に
        ])
        ->assertSessionHasErrors(['title']); // titleが必須の場合

    // DBが変わっていないことを確認
    assertDatabaseHas('todos', [
        'id' => $todo->id,
        'title' => $todo->title, // 元のタイトルのまま
    ]);
});

/**
 * 異常系：更新＋未入力（一部更新しようとしたが必須項目が空）
 */
it('fails to update when some fields are valid but required fields are empty', function () {
    $user = User::factory()->create();
    $todo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => '元のタイトル'
    ]);

    $invalidData = [
        'title'    => '',           // 未入力（エラーになるはず）
        'detail'   => '詳細は入力した', // 入力済み
        'priority' => 5,            // 入力済み
    ];

    actingAs($user)
        ->patch(route('todos.update', $todo), $invalidData)
        ->assertSessionHasErrors(['title']);

    // DBが書き換わっていない（Missing）ことを確認
    assertDatabaseMissing('todos', [
        'id' => $todo->id,
        'detail' => '詳細は入力した',
    ]);
});

it('cannot view someone else\'s todo detail', function () {
    $user = User::factory()->create();
    $otherTodo = Todo::factory()->create(); // user_idは別のユーザーになる

    actingAs($user)
        ->get(route('todos.show', $otherTodo))
        ->assertStatus(403); // もしくは 404
});