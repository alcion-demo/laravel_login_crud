<?php

use App\Models\User;
use App\Models\Todo;
use function Pest\Laravel\{actingAs, get};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('displays user own todos on the calendar', function () {
    $user = User::factory()->create();
    
    // 今月の日付でTodoを作成
    $todo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'カレンダーに載るはずのタスク',
        'deadline' => now()->format('Y-m-d'),
    ]);

    // 他人のTodo
    $otherTodo = Todo::factory()->create([
        'title' => '見えてはいけないタスク',
        'deadline' => now()->format('Y-m-d'),
    ]);

    actingAs($user)
        ->get(route('calendar.index')) // カレンダー用のルートがあると想定
        ->assertStatus(200)
        ->assertSee('カレンダーに載るはずのタスク')
        ->assertDontSee('見えてはいけないタスク');
});