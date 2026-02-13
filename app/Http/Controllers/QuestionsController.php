<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * 問題一覧を表示
     */
    public function select(Request $request)
    {
        try {
            $userId = Auth::id();

            $genres = Genre::withCount(['quizQuestions as unanswered_count' => function ($query) use ($userId) {
                // quiz_answersテーブルにログインユーザーの回答が存在しない問題のみをカウント
                $query->whereDoesntHave('answers', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            }])->get();

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
     * 特定のジャンルの問題リストを返す
     */
    public function getListByGenre(Genre $genre)
    {
        // そのジャンルの問題を配列として返す（Laravelが自動でJSONにしてくれます）
        return response()->json($genre->quizQuestions);
    }
}
