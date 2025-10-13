<div class="genre-edit-container">
    <div class="genre-edit-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-pencil-square me-2"></i>ジャンル編集</h2>
                <p class="text-muted">ジャンル「{{ $genre->name }}」を編集します。</p>
            </div>
            <a href="{{ route('genres.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>一覧に戻る
            </a>
        </div>
    </div>

    <div class="genre-edit-content">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">ジャンル情報</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('genres.update', $genre) }}" id="genreEditForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    ジャンル名称 <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $genre->name) }}" 
                                       required 
                                       placeholder="ジャンル名称を入力してください">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sort_order" class="form-label">
                                    ソート順 <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $genre->sort_order) }}" 
                                       required 
                                       min="0" 
                                       placeholder="ソート順を入力してください">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">数値が小さいほど上位に表示されます。</div>
                            </div>

                            <div class="mb-3">
                                <!-- チェックボックスがチェックされていない場合の隠しフィールド（先に配置） -->
                                <input type="hidden" name="is_disabled" value="0">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_disabled" 
                                           name="is_disabled" 
                                           value="1" 
                                           {{ old('is_disabled', $genre->is_disabled) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_disabled">
                                        新規使用禁止フラグ
                                    </label>
                                </div>
                                <div class="form-text">チェックすると、このジャンルは新規問題作成時に選択できなくなります。</div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-secondary me-md-2" id="cancelEditBtn">
                                    キャンセル
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>更新
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.genre-edit-container {
    padding: 2rem;
}

.genre-edit-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

@media (max-width: 768px) {
    .genre-edit-container {
        padding: 1rem;
    }
}
</style>
