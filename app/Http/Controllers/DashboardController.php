<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $genres = Genre::active()->ordered()->get();
        $user = Auth::user();

        $userGenreStats = QuizQuestion::where('user_id', $user->id)
            ->join('genres', 'quiz_questions.genre_id', '=', 'genres.id')
            ->selectRaw('genres.name, genres.id, COUNT(*) as count')
            ->groupBy('genres.id', 'genres.name')
            ->get()
            ->keyBy('id');

        $totalGenreStats = QuizQuestion::join('genres', 'quiz_questions.genre_id', '=', 'genres.id')
            ->selectRaw('genres.name, genres.id, COUNT(*) as count')
            ->groupBy('genres.id', 'genres.name')
            ->get()
            ->keyBy('id');

        $recentStats = [
            'total_questions' => QuizQuestion::where('created_at', '>=', now()->subDays(30))->count(),
            'user_questions' => QuizQuestion::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays(30))->count(),
            'most_popular_genre' => QuizQuestion::join('genres', 'quiz_questions.genre_id', '=', 'genres.id')
                ->where('quiz_questions.created_at', '>=', now()->subDays(30))
                ->selectRaw('genres.name, COUNT(*) as count')
                ->groupBy('genres.id', 'genres.name')
                ->orderBy('count', 'desc')
                ->first()?->name ?? 'データなし'
        ];

        return view('dashboard', compact('genres', 'userGenreStats', 'totalGenreStats', 'recentStats'));
    }
}