<div class="questions-container">
    <div class="questions-header">
        <h2><i class="bi bi-file-text me-2"></i>作成済み問題一覧</h2>
        <p class="text-muted">これまでに作成した問題の一覧です。</p>
    </div>

    <div class="questions-content">
        <!-- 検索・フィルター -->
        <form method="GET" action="{{ route('questions.index') }}" id="filterForm" onsubmit="return false;">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="問題を検索..." 
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

        <!-- 問題一覧 -->
        <div class="questions-list">
            @if($quizQuestions && $quizQuestions->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
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
                        <tr data-genre-id="{{ $question->genre_id ?? '' }}">
                            <td>{{ $question->id }}</td>
                            <td>{{ Str::limit($question->question, 100) }}</td>
                            <td>
                                @if($question->genre)
                                    <span class="badge bg-info">{{ $question->genre->name }}</span>
                                @else
                                    <span class="badge bg-secondary">未分類</span>
                                @endif
                            </td>
                            <td>{{ $question->created_at->format('Y年m月d日') }}</td>
                            <td>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="loadQuestionEdit({{ $question->id }})">
                                    <i class="bi bi-pencil"></i> 編集
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h4 class="mt-3">問題がありません</h4>
                <p class="text-muted">まだ問題が作成されていません。</p>
                <a href="#" class="btn btn-primary" onclick="loadProblemCreation()">
                    <i class="bi bi-plus-circle me-1"></i>問題を作成
                </a>
            </div>
            @endif

            <!-- 空の状態表示 -->
            <div id="emptyState" style="display: none;">
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="mt-3">問題が見つかりません</h4>
                    <p class="text-muted">検索条件を変更して再度お試しください。</p>
                </div>
            </div>
        </div>

        <!-- ページネーション -->
        @if($quizQuestions->hasPages())
        <nav aria-label="問題一覧ページネーション" class="mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    全 {{ $quizQuestions->total() }} 件中 {{ $quizQuestions->firstItem() }}-{{ $quizQuestions->lastItem() }} 件を表示
                </div>
                <div>
                    <ul class="pagination pagination-sm mb-0">
                        @if($quizQuestions->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">前へ</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $quizQuestions->previousPageUrl() }}">前へ</a>
                            </li>
                        @endif

                        @foreach($quizQuestions->getUrlRange(1, $quizQuestions->lastPage()) as $page => $url)
                            @if($page == $quizQuestions->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $quizQuestions->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if($quizQuestions->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $quizQuestions->nextPageUrl() }}">次へ</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">次へ</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @endif
    </div>
</div>

<style>
.questions-container {
    padding: 2rem;
}

.questions-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.problem-card {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.problem-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.problem-meta {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f8f9fa;
}

.btn-group .btn {
    flex: 1;
}

/* ページネーションのスタイル */
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: #0d6efd;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.pagination .page-link:hover {
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

@media (max-width: 768px) {
    .questions-container {
        padding: 1rem;
    }
    
    .btn-group .btn {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .pagination {
        justify-content: center;
    }
    
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}
</style>

<script>
// 問題の編集
function editQuestion(questionId) {
    console.log('問題編集:', questionId);
    // TODO: 問題編集画面を実装
    alert('問題編集機能は準備中です。問題ID: ' + questionId);
}
</script>
