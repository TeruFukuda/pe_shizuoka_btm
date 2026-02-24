<div class="problem-creation-container">
    <div class="creation-header">
        <h2><i class="bi bi-plus-circle me-2"></i>問題作成</h2>
        <p class="text-muted">新しい問題を作成します。</p>
    </div>
    <div class="creation-content">
        <div class="placeholder-content">
            <form id="editQuestionForm">
                @csrf
                @method('PUT')

                <!-- ジャンル選択 -->
                <div class="mb-3">
                    <label for="genre_id" class="form-label">ジャンル <span class="text-danger">*</span></label>
                    <select class="form-select" id="genre_id" name="genre_id" required>
                        <option value="">ジャンルを選択してください</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}"
                                {{ old('genre_id') == $genre->id ? 'selected' : '' }}
                                {{ $genre->is_disabled ? 'disabled' : '' }}
                            >
                                {{ $genre->name }}
                                @if($genre->is_disabled)
                                    (使用禁止)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('genre_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.problem-creation-container {
    padding: 2rem;
}

.creation-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.placeholder-content {
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px dashed #dee2e6;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .problem-creation-container {
        padding: 1rem;
    }
}
</style>
