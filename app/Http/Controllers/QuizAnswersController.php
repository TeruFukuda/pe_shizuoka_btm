<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Auth;

class QuizAnswersController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::id();
        $choiceId = $request->input('choice_id');

        // 解答を保存（すでにあれば更新、なければ新規作成）
        QuizAnswer::updateOrCreate(
            [
                'user_id' => $userId,
                'quiz_choice_id' => $choiceId
            ],
            [
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => '解答を記録しました'
        ]);
    }
}