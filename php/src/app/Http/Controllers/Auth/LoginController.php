<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    //
    public function create()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // 認証に成功したら、セッションを再生成する
            $request->session()->regenerate();

            // ダッシュボードにリダイレクトする
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません。',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        // 1. ユーザーをログアウトさせる
        Auth::logout();

        // 2. セッションを無効にする
        $request->session()->invalidate();

        // 3. 新しいCSRFトークンを再生成する
        $request->session()->regenerateToken();

        // 4. トップページにリダイレクトする
        return redirect()->route('login');
    }
}
