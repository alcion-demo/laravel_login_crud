<?php

use App\Models\User;

// 1. これを追加
use Illuminate\Foundation\Testing\RefreshDatabase;

// 2. これを記述（これでテスト実行時にテーブルが自動作成されます）
uses(RefreshDatabase::class);

test('ログイン画面が表示される', function () {
    $response = $this->get('/auth/login');

    $response->assertStatus(200);
});

test('間違ったパスワードではログインできない', function () {
    $response = $this->post('/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    // ログインに失敗して、元の画面に戻されることを確認
    $response->assertSessionHasErrors();
});

test('正しい情報でログインし、ダッシュボードへリダイレクトされる', function () {
    $user = App\Models\User::factory()->create([
        'password' => bcrypt('correct-password'),
    ]);

    // 2. ログイン実行
    $response = $this->post('/auth/login', [
        'email' => $user->email,
        'password' => 'correct-password',
    ]);

    // 3. ダッシュボードへリダイレクトされたか確認
    $response->assertRedirect('/dashboard');
    
    // 4. 実際にログイン状態になっているか確認
    $this->assertAuthenticatedAs($user);
});

test('管理者は管理者ダッシュボードにアクセスできる', function () {
    // 1. 管理者ユーザーを作成（is_adminなどのフラグがあると想定）
    $admin = User::factory()->create(['is_admin' => true]);

    // 2. 管理者としてログインしてアクセス
    $response = $this->actingAs($admin)->get('/admin/dashboard');

    // 3. 正常に表示されること
    $response->assertStatus(200);
});

test('一般ユーザーは管理者ダッシュボードにアクセスできず、リダイレクトされる', function () {
    // 1. 一般ユーザーを作成
    $user = User::factory()->create(['is_admin' => false]);

    // 2. 一般ユーザーとしてログインしてアクセス
    $response = $this->actingAs($user)->get('/admin/dashboard');

    // 3. 拒否されること（403 Forbidden または ホームへのリダイレクト）
    // あなたのミドルウェアの実装に合わせて assertStatus(403) などに変えてください
    $response->assertForbidden(); 
});

test('管理者ページへのアクセス制限をチェック', function () {
    // 1. 一般ユーザーを作る（LaravelではUserモデルが「人」を表します）
    $user = \App\Models\User::factory()->create();

    // 2. そのユーザーで、管理者ページ（/admin/dashboard）を覗き見しようとする
    $response = $this->actingAs($user)->get('/admin/dashboard');

    // 3. 多分「403（権限なし）」か「どこかにリダイレクト」されるはず！
    // あなたが作った「adminミドルウェア」がどう動くかをこれで確認できます
    $response->assertForbidden(); 
});

test('名前が空の場合は登録できない', function () {
    $response = $this->post('/auth/register', [
        'name' => '', // 敢えて空にする
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    // セッションに 'name' に関するエラーがあることを確認
    $response->assertSessionHasErrors(['name']);
});

test('メールアドレスの形式でないと登録できない', function () {
    $response = $this->post('/auth/register', [
        'name' => 'テスト太郎',
        'email' => 'test@examplecom',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('パスワードが短すぎると登録できない', function () {
    $response = $this->post('/auth/register', [
        'password' => '1234567', // 7文字
    ]);

    $response->assertSessionHasErrors(['password']);
});

test('パスワードが異なると登録できない', function () {
    $response = $this->post('/auth/register', [
        'password' => 'あいうえお', // 7文字
    ]);

    $response->assertSessionHasErrors(['password']);
});