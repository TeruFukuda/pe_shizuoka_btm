<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\QuizAnswer;
use App\Models\QuizChoice;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question',
        'genre_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 出題者とのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 選択肢とのリレーション
     */
    public function choices(): HasMany
    {
        return $this->hasMany(QuizChoice::class);
    }

    /**
     * ジャンルとのリレーション
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function answers()
    {
        // QuestionからAnswerへ、Choiceを経由して紐付け（HasManyThrough）
        // もしくは単純にAnswerにquestion_idがある場合はhasMany
        // 今回の構造（Choice経由）の場合は以下のように定義します
        return $this->hasManyThrough(
            QuizAnswer::class,        // 1. 最終的に取得したいモデル（解答）
            QuizChoice::class,        // 2. 経由するモデル（選択肢）
            'quiz_question_id',   // 3. 経由モデル(Choice)にある、このモデル(Question)のID
            'quiz_choice_id',     // 4. 最終モデル(Answer)にある、経由モデル(Choice)のID
            'id',                 // 5. このモデル(Question)のローカルキー
            'id'                  // 6. 経由モデル(Choice)のローカルキー
        );
    }
}
