<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class GenreController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('genres')) {
            return response()->json(['error' => 'genresテーブルが存在しません'], 500);
        }
        $genres = Genre::ordered()->get();
        return view('genres.index', compact('genres'));
    }

    public function create()
    {
        return view('genres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_disabled' => 'required|boolean',
        ]);
        Genre::create($request->all());
        return redirect()->route('genres.index')->with('success', 'ジャンルが登録されました。');
    }

    public function edit(Genre $genre)
    {
        return view('genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_disabled' => 'required|boolean',
        ]);
        $genre->update($request->all());
        return redirect()->route('genres.index')->with('success', 'ジャンルが更新されました。');
    }
}