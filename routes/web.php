<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\QuestionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| ここでWebアプリケーションのルートを登録します。
| これらのルートはRouteServiceProviderによってロードされ、
| "web"ミドルウェアグループに割り当てられます。
|
*/

// ホームページ - ログインページにリダイレクト
Route::get('/', function () {
    return redirect()->route('login');
});

// 認証ルート
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password-reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password-reset', [PasswordResetController::class, 'resetPassword']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // ダッシュボード
    Route::get('/dashboard', function () {
        $genres = \App\Models\Genre::active()->ordered()->get();
        
        // 出題状況のデータを取得
        $user = auth()->user();
        
        // ユーザーの出題状況（ジャンル別）
        $userGenreStats = \App\Models\QuizQuestion::where('user_id', $user->id)
            ->join('genres', 'quiz_questions.genre_id', '=', 'genres.id')
            ->selectRaw('genres.name, genres.id, COUNT(*) as count')
            ->groupBy('genres.id', 'genres.name')
            ->get()
            ->keyBy('id');
        
        // 全体の出題状況（ジャンル別）
        $totalGenreStats = \App\Models\QuizQuestion::join('genres', 'quiz_questions.genre_id', '=', 'genres.id')
            ->selectRaw('genres.name, genres.id, COUNT(*) as count')
            ->groupBy('genres.id', 'genres.name')
            ->get()
            ->keyBy('id');
        
        // 最近の出題傾向データ
        $recentStats = [
            'total_questions' => \App\Models\QuizQuestion::where('created_at', '>=', now()->subDays(30))->count(),
            'user_questions' => \App\Models\QuizQuestion::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays(30))->count(),
            'most_popular_genre' => \App\Models\QuizQuestion::join('genres', 'quiz_questions.genre_id', '=', 'genres.id')
                ->where('quiz_questions.created_at', '>=', now()->subDays(30))
                ->selectRaw('genres.name, COUNT(*) as count')
                ->groupBy('genres.id', 'genres.name')
                ->orderBy('count', 'desc')
                ->first()?->name ?? 'データなし'
        ];
        
        return view('dashboard', compact('genres', 'userGenreStats', 'totalGenreStats', 'recentStats'));
    })->name('dashboard');
    
    // 問題一覧
    Route::get('/questions', [QuestionsController::class, 'index'])->name('questions.index');
    
    // 問題作成
    Route::get('/questions/create', function () {
        return view('questions.create');
    })->name('questions.create');
    
    // 問題選択
    Route::get('/questions/select', function () {
        return view('questions.select');
    })->name('questions.select');
    
    // 問題編集
    Route::get('/questions/{question}/edit', function (\App\Models\QuizQuestion $question) {
        $genres = \App\Models\Genre::ordered()->get();
        return view('questions.edit', compact('question', 'genres'));
    })->name('questions.edit');
    
    // ジャンル管理
    Route::get('/genres', function () {
        try {
            // テーブルが存在するかチェック
            if (!\Schema::hasTable('genres')) {
                return response()->json([
                    'error' => 'genresテーブルが存在しません',
                    'message' => 'マイグレーションを実行してください: php artisan migrate'
                ], 500);
            }
            
            $genres = \App\Models\Genre::ordered()->get();
            return view('genres.index', compact('genres'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'ジャンル一覧の取得に失敗しました',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    })->name('genres.index');
    
    Route::get('/genres/create', function () {
        return view('genres.create');
    })->name('genres.create');
    
    Route::get('/genres/{genre}/edit', function (\App\Models\Genre $genre) {
        return view('genres.edit', compact('genre'));
    })->name('genres.edit');
    
    Route::post('/genres', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_disabled' => 'required|boolean',
        ]);
        
        \App\Models\Genre::create($request->all());
        
        return redirect()->route('genres.index')
            ->with('success', 'ジャンルが正常に登録されました。');
    })->name('genres.store');
    
    Route::put('/genres/{genre}', function (\Illuminate\Http\Request $request, \App\Models\Genre $genre) {
        $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_disabled' => 'required|boolean',
        ]);
        
        $genre->update($request->all());
        
        return redirect()->route('genres.index')
            ->with('success', 'ジャンルが正常に更新されました。');
    })->name('genres.update');
});

// API ルート（認証状態確認用）
Route::prefix('api')->group(function () {
    Route::get('/user', [LoginController::class, 'getUser']);
    Route::get('/auth/check', [LoginController::class, 'checkAuth']);
});
