<div class="answer-container">

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="mb-4">
                <label class="form-label text-muted small">問題</label>
                <div class="fs-5 fw-bold p-3 bg-light rounded">{{ $question->question }}</div>
            </div>

            <form id="answerForm">
                @csrf
                <div class="mb-4">
                    <label class="form-label">選択肢 <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        @foreach($question->choices as $choice)
                            <div class="col-12">
                                <div class="p-2 border rounded">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="choice_id"
                                               id="choice_{{ $choice->id }}" value="{{ $choice->id }}" required>
                                        <label class="form-check-label w-100 cursor-pointer" for="choice_{{ $choice->id }}">
                                            {{ $choice->choice_text }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="loadProblemSelection()">
                        <i class="bi bi-x-circle me-1"></i>問題一覧に戻る
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>回答する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>