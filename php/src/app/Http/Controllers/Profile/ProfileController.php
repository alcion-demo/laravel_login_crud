<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面表示
     * @return view
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * プロフィール情報更新
     * @param $request
     * @return view
     */
    public function update(Request $request)
    {
        // 管理者制限
        if (Auth::user()->is_admin) {
            abort(403, '管理者はプロフィール編集を利用できません。');
        }

        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ], [
            'avatar.max' => '画像サイズは2MB以内でアップロードしてください。',
            'current_password.required_with' => '新しいパスワードを設定するには、現在のパスワードを入力してください。',
            'new_password.min' => '新しいパスワードは8文字以上で入力してください。',
            'new_password.confirmed' => '新しいパスワード（確認用）と一致しません。',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        if ($request->filled('new_password')) {
            // 現在のパスワードチェック（$user->password を直接参照）
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => '現在のパスワードが正しくありません。']);
            }
            
            // パスワードをセット
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'プロフィールを更新しました！');
    }

    public function destroyAvatar(Request $request)
    {
        $user = Auth::user();
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->avatar_path = null;
            $user->save();
        }
        return back()->with('status', 'アバターを削除しました。');
    }
}
