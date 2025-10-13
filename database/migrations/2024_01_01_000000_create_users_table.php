<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ユーザーテーブルを作成するマイグレーション
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ユーザーID（サロゲートキー）
            $table->string('name'); // ユーザー名
            $table->string('email')->unique(); // メールアドレス（一意）
            $table->string('password'); // パスワード（ハッシュ化）
            $table->rememberToken(); // ログイン状態保持用トークン
            $table->timestamps(); // 作成日時・更新日時
        });
    }

    /**
     * マイグレーションをロールバック
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
