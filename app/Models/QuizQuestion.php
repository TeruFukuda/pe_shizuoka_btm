<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
