<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    //
    public function create()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        // 1. LoginRequest 内の authenticate() を実行（バリデーションと認証試行を同時に行う）
        $request->authenticate();

        // 2. 認証に成功したら、セッションを再生成する
        $request->session()->regenerate();

        // 3. 管理者判定
        if (Auth::user()->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // 4. ダッシュボードにリダイレクトする
        return redirect()->intended('dashboard');
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
