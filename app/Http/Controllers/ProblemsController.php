<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class ProblemsController extends Controller
{
    /**
     * 問題一覧を表示
     */
    public function index(Request $request)
    {
        try {
            // ジャンルを取得
            $genres = Genre::ordered()->get();
            
            // クエリビルダーを開始（ログインしているユーザーの問題のみ）
            $query = QuizQuestion::with(['genre', 'user', 'choices'])
                ->where('user_id', auth()->id());
            
            // ジャンルフィルター
            if ($request->filled('genre_id')) {
                $query->where('genre_id', $request->genre_id);
            }
            
            // 検索フィルター
            if ($request->filled('search')) {
                $query->where('question', 'like', '%' . $request->search . '%');
            }
            
            // ページネーション
            $quizQuestions = $query->paginate(15);
            
            // 現在のフィルター値をビューに渡す
            $currentGenreId = $request->genre_id ?? '';
            $currentSearch = $request->search ?? '';
            
            return view('problems.index', compact('genres', 'quizQuestions', 'currentGenreId', 'currentSearch'));
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => '問題一覧の取得に失敗しました',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
