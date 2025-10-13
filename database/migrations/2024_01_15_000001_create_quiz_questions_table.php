<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id(); // 問題ID（サロゲートキー）
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 出題者ユーザーID
            $table->text('question'); // 問題
            $table->unsignedBigInteger('genre_id')->nullable(); // ジャンルID（外部キー制約なし）
            $table->timestamp('created_at')->useCurrent(); // 出題日時
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
