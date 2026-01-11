<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'day_of_week' => Weekday::class,
        ];
    }

    /**
     * ユーザー一覧検索
     * @param request $request
     * @return \App\Models\User
     */
    public function userList($request) {
        $query = $this->query();

        if (!empty($request)) {
            // 検索条件を ( ) で囲むように修正（grouping）
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request}%")
                  ->orWhere('email', 'LIKE', "%{$request}%");
            });
        }

        $users = $query->latest()->paginate(25);

        return $users;
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }
}
