<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * パスワードリセットページを表示
     */
    public function showResetForm()
    {
        return view('auth.password-reset');
    }

    /**
     * パスワードリセット処理
     */
    public function resetPassword(Request $request)
    {
        // バリデーション
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '有効なメールアドレスを入力してください',
            'email.exists' => 'このメールアドレスは登録されていません',
            'new_password.required' => '新しいパスワードを入力してください',
            'new_password.min' => 'パスワードは6文字以上で入力してください',
            'new_password.confirmed' => 'パスワードが一致しません',
        ]);

        // ユーザーを検索
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['このメールアドレスは登録されていません'],
            ]);
        }

        // パスワードを更新
        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return redirect('/login')
            ->with('success', 'パスワードが正常にリセットされました。新しいパスワードでログインしてください。');
    }
}
