<div class="answer-container">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="mb-4">
                <label class="form-label text-muted small">問題</label>
                <div class="fs-5 fw-bold p-3 bg-light rounded">{{ $question->question }}</div>
            </div>

            <form id="answerForm" class="needs-validation" novalidate>
                @csrf
                <div class="mb-4">
                    <label class="form-label">選択肢 <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        @foreach($question->choices as $choice)
                            <div class="col-12">
                                <div class="p-2 border rounded">
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="choice_id"
                                      id="choice_{{ $choice->id }}"
                                      value="{{ $choice->id }}"
                                      data-is-correct="{{ $choice->is_correct }}"
                                      required
                                    >
                                      <label class="form-check-label w-100 cursor-pointer choice-text" for="choice_{{ $choice->id }}">
                                          {{ $choice->choice_text }}
                                      </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="select-error" class="text-danger small mt-2 d-none">
                        <i class="bi bi-exclamation-triangle me-1"></i>選択肢を選んでください。
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="loadProblemSelection()">
                        <i class="bi bi-x-circle me-1"></i>問題一覧に戻る
                    </button>
                    <button type="button" id="submitBtn" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>解答する
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div
        class="modal"
        id="resultModal"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="resultTitle"></h5>
                </div>
                <div class="modal-body text-center">
                    <div id="resultIcon"></div>
                    <p id="resultMessage"></p>

                    <div class="mt-4 p-3 bg-light rounded text-start">
                        <div class="mb-2">
                            <span class="badge bg-success me-2">正解</span>
                            <span id="correctAnswerText" class="fw-bold text-success"></span>
                        </div>
                        <div>
                            <span class="badge bg-secondary me-2">あなたの解答</span>
                            <span id="userAnswerText" class="fw-bold text-dark"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center gap-3">
                @if(isset($next_question_id))
                    <button type="button" class="btn btn-secondary px-4 py-2" onclick="loadProblemSelection()">
                        一覧に戻る
                    </button>

                    <button type="button" class="btn btn-primary px-4 py-2" id="nextQuestionBtn" onclick="loadAnswerPage({{ $next_question_id }})">
                        次の問題へ
                    </button>
                @else
                    <button type="button" class="btn btn-primary px-5 py-2" onclick="loadProblemSelection()">
                        一覧に戻る
                    </button>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
