@extends('layouts.app')

@section('title', '作成済み問題一覧')

@section('content')
<div class="questions-container">
    <div class="questions-header">
        <h2><i class="bi bi-file-text me-2"></i>作成済み問題一覧</h2>
        <p class="text-muted">これまでに作成した問題の一覧です。</p>
    </div>

    <div class="questions-content">
        {{-- 検索・フィルターフォーム --}}
        <form method="GET" action="{{ route('questions.index') }}" id="filterForm">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text"
                               class="form-control"
                               name="search"
                               placeholder="問題をリアルタイム検索..."
                               value="{{ $currentSearch }}"
                               id="searchInput">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select" name="genre_id" id="genreFilter">
                        <option value="">すべてのジャンル</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}"
                                    {{ $currentGenreId == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <div class="questions-list" id="questionsListWrapper">
            @if($quizQuestions && $quizQuestions->count() > 0)
            <div class="table-responsive card">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>問題</th>
                            <th>ジャンル</th>
                            <th>作成日</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="questionsTableBody">
                        @foreach($quizQuestions as $question)
                        {{-- JSでフィルタリングしやすいように data属性を付与 --}}
                        <tr class="question-row" data-genre-id="{{ $question->genre_id }}">
                            <td>{{ $question->id }}</td>
                            <td class="question-text">{{ Str::limit($question->question, 80) }}</td>
                            <td>
                                @if($question->genre)
                                    <span class="badge bg-info text-dark">{{ $question->genre->name }}</span>
                                @else
                                    <span class="badge bg-secondary">未分類</span>
                                @endif
                            </td>
                            <td>{{ $question->created_at->format('Y/m/d') }}</td>
                            <td>
                                <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-pencil"></i> 編集
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 検索結果がゼロの時にJSで表示するための隠しメッセージ --}}
            <div id="emptyState" class="text-center py-5 border rounded bg-light mt-3" style="display: none;">
                <i class="bi bi-search display-1 text-muted"></i>
                <h4 class="mt-3">一致する問題が見つかりません</h4>
                <p class="text-muted">検索キーワードやジャンルを変えてみてください。</p>
            </div>

            <div class="mt-4 d-flex justify-content-center" id="paginationWrapper">
                {{ $quizQuestions->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>

            @else
            <div class="text-center py-5 border rounded bg-light">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h4 class="mt-3">問題がまだ登録されていません</h4>
                <p class="text-muted">新しく作成して学習を始めましょう！</p>
                <a href="{{ route('questions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>問題を作成
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const genreFilter = document.getElementById('genreFilter');
    const rows = document.querySelectorAll('.question-row');
    const emptyState = document.getElementById('emptyState');
    const tableCard = document.querySelector('.table-responsive');
    const paginationWrapper = document.getElementById('paginationWrapper');

    /**
     * クライアントサイドでのリアルタイムフィルタリング
     * ※ページを跨いでの検索が必要な場合はサーバー送信が必要ですが、
     * 表示中のページ内で即座に絞り込むにはこれが最速です。
     */
    function filterProblems() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedGenre = genreFilter.value;
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.querySelector('.question-text').textContent.toLowerCase();
            const genreId = row.dataset.genreId;

            const matchesSearch = text.includes(searchTerm);
            const matchesGenre = selectedGenre === "" || genreId === selectedGenre;

            if (matchesSearch && matchesGenre) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });

        // 全滅した時の表示切り替え
        if (visibleCount === 0) {
            if (tableCard) tableCard.style.display = "none";
            if (paginationWrapper) paginationWrapper.style.display = "none";
            emptyState.style.display = "block";
        } else {
            if (tableCard) tableCard.style.display = "block";
            if (paginationWrapper) paginationWrapper.style.display = "flex";
            emptyState.style.display = "none";
        }
    }

    // 入力イベントにデバウンス（少し待ってから実行）をかけて負荷軽減
    let timeout = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(filterProblems, 300);
    });

    // ジャンル変更時は即実行
    genreFilter.addEventListener('change', filterProblems);

    // フォームのEnterキーでの誤送信を防止（Ajax的な動きを優先）
    document.getElementById('filterForm').addEventListener('submit', (e) => {
        // もし完全にサーバーサイド検索に切り替えたい場合はここを削除
        if (searchInput.value.length > 0) {
            // そのまま送信（サーバーサイド検索実行）
        } else {
            e.preventDefault();
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .questions-container { padding: 1rem 0; }
    .questions-header { margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e9ecef; }
    .table th { font-weight: 600; }
    .badge { font-weight: 500; }
    /* 検索ヒット時のアニメーション */
    .question-row { transition: opacity 0.2s ease; }
</style>
@endpush