<div class="problems-edit-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-pencil-square me-2"></i>
            問題編集
        </h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="editQuestionForm">
                @csrf
                @method('PUT')
                
                <!-- 問題ID表示 -->
                <div class="mb-3">
                    <label class="form-label text-muted">問題ID</label>
                    <div class="form-control-plaintext">{{ $question->id }}</div>
                </div>

                <!-- 問題文 -->
                <div class="mb-3">
                    <label for="question" class="form-label">問題文 <span class="text-danger">*</span></label>
                    <textarea 
                        class="form-control" 
                        id="question" 
                        name="question" 
                        rows="4" 
                        placeholder="問題文を入力してください"
                        required
                    >{{ old('question', $question->question) }}</textarea>
                    @error('question')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ジャンル選択 -->
                <div class="mb-3">
                    <label for="genre_id" class="form-label">ジャンル <span class="text-danger">*</span></label>
                    <select class="form-select" id="genre_id" name="genre_id" required>
                        <option value="">ジャンルを選択してください</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" 
                                {{ old('genre_id', $question->genre_id) == $genre->id ? 'selected' : '' }}
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

                <!-- 選択肢 -->
                <div class="mb-4">
                    <label class="form-label">選択肢 <span class="text-danger">*</span></label>
                    <div id="choicesContainer">
                        @foreach($question->choices as $index => $choice)
                            <div class="choice-item mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-8">
                                        <input 
                                            type="text" 
                                            class="form-control choice-text" 
                                            name="choices[{{ $index }}][text]" 
                                            value="{{ old('choices.'.$index.'.text', $choice->choice_text) }}"
                                            placeholder="選択肢{{ $index + 1 }}を入力"
                                            required
                                        >
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input" 
                                                type="radio" 
                                                name="correct_choice" 
                                                value="{{ $index }}"
                                                id="correct_{{ $index }}"
                                                {{ $choice->is_correct ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="correct_{{ $index }}">
                                                正解
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        @if($index > 1)
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-choice">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addChoice">
                        <i class="bi bi-plus-circle me-1"></i>選択肢を追加
                    </button>
                    @error('choices')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ボタン -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" id="cancelButton">
                        <i class="bi bi-x-circle me-1"></i>キャンセル
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>保存
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// 編集画面のJavaScriptを即座に実行
(function() {
    let choiceIndex = {{ $question->choices->count() }};
    
    // 選択肢追加
    function setupAddChoice() {
        const addButton = document.getElementById('addChoice');
        if (addButton) {
            addButton.addEventListener('click', function() {
                if (choiceIndex >= 6) {
                    alert('選択肢は最大6個までです。');
                    return;
                }
                
                const container = document.getElementById('choicesContainer');
                const choiceItem = document.createElement('div');
                choiceItem.className = 'choice-item mb-3 p-3 border rounded';
                choiceItem.innerHTML = `
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control choice-text" name="choices[${choiceIndex}][text]" placeholder="選択肢${choiceIndex + 1}を入力" required>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct_choice" value="${choiceIndex}" id="correct_${choiceIndex}">
                                <label class="form-check-label" for="correct_${choiceIndex}">正解</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-choice">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                container.appendChild(choiceItem);
                choiceIndex++;
                
                // 削除ボタンのイベントリスナーを追加
                choiceItem.querySelector('.remove-choice').addEventListener('click', function() {
                    choiceItem.remove();
                });
            });
        }
    }
    
    // 選択肢削除
    function setupRemoveChoice() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-choice')) {
                e.target.closest('.choice-item').remove();
            }
        });
    }
    
    // フォーム送信
    function setupFormSubmit() {
        const form = document.getElementById('editQuestionForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // バリデーション
                const questionText = document.getElementById('question').value.trim();
                const genreId = document.getElementById('genre_id').value;
                const choices = document.querySelectorAll('.choice-text');
                const correctChoice = document.querySelector('input[name="correct_choice"]:checked');
                
                if (!questionText) {
                    alert('問題文を入力してください。');
                    return;
                }
                
                if (!genreId) {
                    alert('ジャンルを選択してください。');
                    return;
                }
                
                if (choices.length < 2) {
                    alert('選択肢は最低2個必要です。');
                    return;
                }
                
                if (!correctChoice) {
                    alert('正解の選択肢を選択してください。');
                    return;
                }
                
                // ここで実際の保存処理を行う
                alert('問題が保存されました。');
                
                // 問題一覧に戻る
                loadProblemList();
            });
        }
    }
    
    // キャンセルボタン
    function setupCancelButton() {
        const cancelButton = document.getElementById('cancelButton');
        if (cancelButton) {
            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();
                loadProblemList();
            });
        }
    }
    
    // 問題一覧に戻る関数
    function loadProblemList() {
        const mainContent = document.getElementById('mainContent');
        
        mainContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">読み込み中...</span>
                </div>
                <p class="mt-3">問題一覧を読み込み中...</p>
            </div>
        `;
        
        fetch('/problems')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const problemContent = doc.querySelector('.problems-container');
                
                if (problemContent) {
                    mainContent.innerHTML = problemContent.outerHTML;
                    if (typeof setupProblemListFilters === 'function') {
                        setupProblemListFilters();
                    }
                } else {
                    mainContent.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            問題一覧の読み込みに失敗しました。
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mainContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        エラーが発生しました: ${error.message}
                    </div>
                `;
            });
    }
    
    // グローバル関数として定義
    window.cancelEdit = function() {
        loadProblemList();
    };
    
    // 初期化
    setupAddChoice();
    setupRemoveChoice();
    setupFormSubmit();
    setupCancelButton();
})();
</script>
