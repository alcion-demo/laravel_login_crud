<?php

use App\Models\User;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ログインしていればTodo一覧が見れる', function () {
    // 1. ユーザーを作ってログイン
    $user = User::factory()->create();
    
    // 2. そのユーザーでTodo一覧（/todos）にアクセス
    $response = $this->actingAs($user)->get('/todos');

    // 3. 200 OKが返ってくるか
    $response->assertStatus(200);
});

test('Todoを新しく登録できる', function () {
    $user = User::factory()->create();

    // POSTでTodoの内容を送る
    $response = $this->actingAs($user)->post('/todos', [
        'title' => '新しいタスク',
        'detail' => 'テストの内容です',
        'deadline' => now()->addDay()->format('Y-m-d'),
    ]);

    // 登録後は一覧画面へリダイレクトされるはず
    $response->assertRedirect('/todos');
    
    // DBに実際にデータが入ったか確認
    $this->assertDatabaseHas('todos', [
        'title' => '新しいタスク'
    ]);
});

test('タイトルが未入力の場合はエラーになる', function () {
    // 1. 準備（ログインする）
    $user = \App\Models\User::factory()->create();

    // 2. 実行（タイトルを「空」にして送信する）
    $response = $this->actingAs($user)->post('/todos', [
        'title' => '', // あえて空っぽにする
    ]);

    // 3. 検証：エラーがあることを確認
    $response->assertSessionHasErrors(['title']);
});

test('必須項目が未入力なら保存できない', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post('/todos', [
        'title' => '',
        'detail' =>'',
        'deadline' => '',
    ]);

    // まとめてチェック！
    $response->assertSessionHasErrors(['title', 'deadline', 'detail']);
});

test('タイトル未入力時の挙動をフルチェック', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post('/todos', [
        'title' => '',         // エラーになるはず
        'detail' => '詳細', // これは消えずに残ってほしい
    ]);

    // $response の後ろに続けて書ける
    $response
        ->assertStatus(302)               // 1. リダイレクト（画面移動）が発生したか
        ->assertSessionHasErrors(['title']) // 2. タイトルにエラーが出たか
        ->assertSessionHasInput('detail', '詳細'); // 3. 入力した「詳細」が保持されているか（old()機能）
});

test('詳細未入力時の挙動をフルチェック', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post('/todos', [
        'title' => '題名',         // エラーになるはず
        'detail' => '', // これは消えずに残ってほしい
    ]);

    // $response の後ろに続けて書ける
    $response
        ->assertStatus(302)               // 1. リダイレクト（画面移動）が発生したか
        ->assertSessionHasErrors(['detail']) // 2. タイトルにエラーが出たか
        ->assertSessionHasInput('title', '題名'); // 3. 入力した「詳細」が保持されているか（old()機能）
});

test('開始時間が空の場合', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post('/todos', [
        'start_time' => '',
        'end_time' =>'20:15:00',
    ]);

    // まとめてチェック！
    $response->assertSessionHasErrors(['start_time']);
});

test('終了時間が空の場合', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post('/todos', [
        'start_time' => '10:15:00',
        'end_time' =>'',
    ]);

    // まとめてチェック！
    $response->assertSessionHasErrors(['end_time']);
});

test('終了時間が開始時間より前の場合', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post('/todos', [
        'start_time' => '10:15:00',
        'end_time' =>'9:10:00',
    ]);

    // まとめてチェック！
    $response->assertSessionHasErrors(['end_time']);
});