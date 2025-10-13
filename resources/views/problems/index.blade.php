<div class="problems-container">
    <div class="problems-header">
        <h2><i class="bi bi-file-text me-2"></i>作成済み問題一覧</h2>
        <p class="text-muted">これまでに作成した問題の一覧です。</p>
    </div>

    <div class="problems-content">
        <!-- 検索・フィルター -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="問題を検索..." id="searchInput">
                </div>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="genreFilter">
                    <option value="">すべてのジャンル</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- 問題一覧 -->
        <div class="problems-list">
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
                    <tbody id="problemsTableBody">
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
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="editQuestion({{ $question->id }})">
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
                                <a class="page-link pagination-link" href="#" data-page="{{ $quizQuestions->currentPage() - 1 }}">前へ</a>
                            </li>
                        @endif

                        @foreach($quizQuestions->getUrlRange(1, $quizQuestions->lastPage()) as $page => $url)
                            @if($page == $quizQuestions->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link pagination-link" href="#" data-page="{{ $page }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if($quizQuestions->hasMorePages())
                            <li class="page-item">
                                <a class="page-link pagination-link" href="#" data-page="{{ $quizQuestions->currentPage() + 1 }}">次へ</a>
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
.problems-container {
    padding: 2rem;
}

.problems-header {
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
    .problems-container {
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
console.log('JavaScript読み込み開始');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded イベント発火');
    
    const searchInput = document.getElementById('searchInput');
    const genreFilter = document.getElementById('genreFilter');
    const emptyState = document.getElementById('emptyState');
    
    console.log('要素取得結果:', {
        searchInput: searchInput,
        genreFilter: genreFilter,
        emptyState: emptyState
    });

    function filterProblems() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedGenreId = genreFilter.value;
        
        console.log('フィルター実行:', { searchTerm, selectedGenreId });
        
        // テーブル行を取得
        const tableRows = document.querySelectorAll('#problemsTableBody tr');
        let visibleCount = 0;

        console.log('見つかったテーブル行数:', tableRows.length);

        tableRows.forEach((row, index) => {
            const questionText = row.cells[1].textContent.toLowerCase(); // 問題列（2番目の列）
            const genreId = row.dataset.genreId;

            const matchesSearch = searchTerm === '' || questionText.includes(searchTerm);
            const matchesGenre = selectedGenreId === '' || genreId === selectedGenreId;

            console.log(`行${index + 1}:`, {
                questionText: questionText.substring(0, 50) + '...',
                genreId,
                matchesSearch,
                matchesGenre
            });

            if (matchesSearch && matchesGenre) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        console.log('表示される行数:', visibleCount);

        // 空の状態表示の切り替え
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // イベントリスナーの追加
    if (searchInput) {
        console.log('検索ボックスのイベントリスナーを追加');
        searchInput.addEventListener('input', function(e) {
            console.log('検索ボックス入力イベント:', e.target.value);
            filterProblems();
        });
    } else {
        console.error('検索ボックスが見つかりません');
    }
    
    if (genreFilter) {
        console.log('ジャンルフィルターのイベントリスナーを追加');
        genreFilter.addEventListener('change', function(e) {
            console.log('ジャンルフィルター変更イベント:', e.target.value);
            filterProblems();
        });
    } else {
        console.error('ジャンルフィルターが見つかりません');
    }
    
    // 初期化時にフィルターを実行
    console.log('初期化時にフィルターを実行');
    filterProblems();
});

// 問題の編集
function editQuestion(questionId) {
    console.log('問題編集:', questionId);
    // TODO: 問題編集画面を実装
    alert('問題編集機能は準備中です。問題ID: ' + questionId);
}

console.log('JavaScript読み込み完了');
</script>
