@extends('layouts.app')

@section('title','問題に解答する')

@section('content')
<div class="problem-selection-container">
    <div class="selection-header">
        <h2><i class="bi bi-list-check me-2"></i>問題選択</h2>
        <p class="text-muted">問題を選択して学習を開始します</p>
    </div>

    <div class="selection-content">
        <!-- ジャンル一覧 -->
        <div class="genres-list">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ジャンル</th>
                            <th>未解答問題数</th>
                        </tr>
                    </thead>
                    <tbody id="questionsTableBody">
                        @foreach($genres as $genre)
                        <tr
                            class="genre--row"
                            data-genre-id="{{ $genre->id ?? '' }}"
                        >
                            <td>{{ $genre->name }}</td>
                            <td>5件</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
