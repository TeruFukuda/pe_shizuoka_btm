<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'name',
        'sort_order',
        'is_disabled',
    ];

    /**
     * 属性のキャスト
     */
    protected $casts = [
        'is_disabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * ソート順で取得
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * 有効なジャンルのみ取得
     */
    public function scopeActive($query)
    {
        return $query->where('is_disabled', false);
    }
}
