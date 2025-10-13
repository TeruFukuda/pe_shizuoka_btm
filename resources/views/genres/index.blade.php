<div class="genres-container">
    <div class="genres-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-tags me-2"></i>ジャンル一覧</h2>
                <p class="text-muted">ジャンルの管理を行います。</p>
            </div>
            <a href="{{ route('genres.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>新規登録
            </a>
        </div>
    </div>

    <div class="genres-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($genres->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>ジャンル名称</th>
                            <th>ソート順</th>
                            <th>状態</th>
                            <th>作成日</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($genres as $genre)
                            <tr>
                                <td>{{ $genre->id }}</td>
                                <td>
                                    <strong>{{ $genre->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $genre->sort_order }}</span>
                                </td>
                                <td>
                                    @if ($genre->is_disabled)
                                        <span class="badge bg-danger">無効</span>
                                    @else
                                        <span class="badge bg-success">有効</span>
                                    @endif
                                </td>
                                <td>{{ $genre->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('genres.edit', $genre) }}" class="btn btn-outline-success edit-genre-btn" data-genre-id="{{ $genre->id }}">
                                            <i class="bi bi-pencil"></i> 編集
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h4 class="mt-3">ジャンルが登録されていません</h4>
                <p class="text-muted">最初のジャンルを登録してください。</p>
                <a href="{{ route('genres.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>新規登録
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.genres-container {
    padding: 2rem;
}

.genres-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

@media (max-width: 768px) {
    .genres-container {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
