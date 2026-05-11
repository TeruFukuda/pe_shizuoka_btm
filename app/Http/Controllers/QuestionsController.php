<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use DB;

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
     * ジャンルに紐づく未解答問題一覧を取得
     */
    public function getQuestionsNotAnsweredByGenre($genreId)
    {
        $userId = auth()->id(); // または $request->user()->id

        $questions = QuizQuestion::where('genre_id', $genreId)
            ->whereDoesntHave('answers', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('choices')
            ->get();

        return response()->json($questions);
    }

    public function answer(QuizQuestion $question)
    {
        $userId = auth()->id();
        $question->load(['choices', 'genre']);

        // 同じジャンル内で、自分がまだ回答していない「次の1問」を取得
        $nextQuestion = QuizQuestion::where('genre_id', $question->genre_id)
        ->where('id', '!=', $question->id) // 今表示している問題は除く
        ->whereDoesntHave('choices.answers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->first(); // 1件あればIDが取れる、なければnull

        return view('questions.answer', [
          'question' => $question,
          'next_question_id' => $nextQuestion ? $nextQuestion->id : null,
        ]);
    }

    /**
     * 作成画面の表示
     */
    public function create()
    {
        // 使用禁止（is_disabled）でないジャンルを取得
        $genres = Genre::all();

        return view('questions.create', compact('genres'));
    }

    /**
     * データの保存処理
     */
    public function store(Request $request)
    {

      \Log::info('リクエストデータ:', $request->all());
        // 1. バリデーション
        $validated = $request->validate([
            'genre_id' => 'required|exists:genres,id',
            'question' => 'required|string|max:1000',
            'choices' => 'required|array|min:2|max:6',
            'choices.*.text' => 'required|string|max:255',
            'correct_choice' => 'required|integer',
        ]);

        try {
            // 2. データベース保存（トランザクション開始）
            DB::transaction(function () use ($request) {

                // 問題テーブルに保存
                $question = QuizQuestion::create([
                    'user_id'  => auth()->id(),
                    'genre_id' => $request->genre_id,
                    'question' => $request->question,
                ]);

                // 選択肢をループして保存
                foreach ($request->choices as $index => $choiceData) {
                    $question->choices()->create([
                        'choice_text' => $choiceData['text'],
                        // 送信されたラジオボタンのindexと現在のループindexが一致すれば正解
                        'is_correct' => ($index == $request->correct_choice),
                    ]);
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'クイズを作成しました！'
            ]);

        } catch (\Exception $e) {
              return response()->json([
                'status' => 'error',
                'message' => '保存エラー: ' . $e->getMessage()
            ], 500);
        }
    }
}
