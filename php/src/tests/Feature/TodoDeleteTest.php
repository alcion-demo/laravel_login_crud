<?php
use App\Models\User;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{actingAs};

uses(RefreshDatabase::class);

it('can delete a todo', function () {
    $user = User::factory()->create();
    $todo = Todo::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->delete(route('todos.destroy', $todo))
        ->assertRedirect(route('todos.index'));

    // DBから消えていることを確認
    $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
});

it('cannot delete other users todo', function () {
    $user = User::factory()->create();
    $otherTodo = Todo::factory()->create(); // 他人のTodo

    actingAs($user)
        ->delete(route('todos.destroy', $otherTodo))
        ->assertStatus(403); // 禁止

    // DBに残っていることを確認
    $this->assertDatabaseHas('todos', ['id' => $otherTodo->id]);
});