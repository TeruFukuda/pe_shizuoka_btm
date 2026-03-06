<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuestionsController extends Controller
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

            return view('questions.index', compact('genres', 'quizQuestions', 'currentGenreId', 'currentSearch'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => '問題一覧の取得に失敗しました',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * 問題選択画面を表示
     */
    public function select(Request $request)
    {
        try {
            $userId = auth()->id();

            $genres = Genre::withCount([
                // 1. ジャンルに紐づく全問題数
                'quizQuestions as total_count',

                // 2. 解答済み（quiz_answersにレコードがある）問題数
                'quizQuestions as answered_count' => function ($query) use ($userId) {
                    $query->whereHas('choices.answers', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
                }
            ])->get();

            // 未解答数をプロパティとして追加
            foreach ($genres as $genre) {
                $genre->unanswered_count = $genre->total_count - $genre->answered_count;
            }
            return view('questions.select', compact('genres'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'ジャンル一覧の取得に失敗しました',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * ジャンルに紐づく問題一覧を取得
     */
    public function getQuestionsByGenre($genreId)
    {
        // ジャンルに紐づく問題を取得（リレーションが設定されている前提）
        $questions = QuizQuestion::where('genre_id', $genreId)->get();

        // JSON形式でレスポンスを返す
        return response()->json($questions);
    }
}
