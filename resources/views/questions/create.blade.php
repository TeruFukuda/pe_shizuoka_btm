@extends('layouts.app')

@section('title','問題を作成する')

@section('content')
<div class="problem-creation-container">
    <div class="creation-header">
        <h2><i class="bi bi-plus-circle me-2"></i>問題作成</h2>
        <p class="text-muted">新しいクイズ問題を作成します。</p>
    </div>

    <div class="creation-content">
        <div class="p-4 bg-light border rounded shadow-sm">
            <form id="createQuestionForm" action="{{ route('questions.store') }}" method="POST">
                @csrf

                <!-- ジャンル選択 -->
                <div class="mb-4">
                    <label for="genre_id" class="form-label fw-bold">ジャンル <span class="text-danger">*</span></label>
                    <select class="form-select @error('genre_id') is-invalid @enderror" id="genre_id" name="genre_id" required>
                        <option value="">ジャンルを選択してください</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }} {{ $genre->is_disabled ? 'disabled' : '' }}>
                                {{ $genre->name }} @if($genre->is_disabled) (使用禁止) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('genre_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 問題文 -->
                <div class="mb-4">
                    <label for="question" class="form-label fw-bold">問題文 <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('question') is-invalid @enderror" id="question" name="question" rows="4" placeholder="例：日本で一番高い山は何でしょう？" required>{{ old('question') }}</textarea>
                    @error('question')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 選択肢 -->
                <div class="mb-4">
                    <label class="form-label fw-bold d-flex justify-content-between">
                        選択肢 <span class="text-muted small">※正解を1つ選択してください (最大6個)</span>
                    </label>

                    <div id="choicesContainer">
                        @php
                            // バリデーションエラー時は old の値を、初期表示時は2つの空欄を表示
                            $oldChoices = old('choices', [['text' => ''], ['text' => '']]);
                        @endphp

                        @foreach($oldChoices as $index => $choice)
                            <div class="choice-item mb-3 p-3 border rounded bg-white shadow-sm">
                                <div class="row align-items-center gap-2 gap-md-0">
                                    <div class="col-md-8">
                                        <input type="text"
                                               class="form-control choice-text @error("choices.$index.text") is-invalid @enderror"
                                               name="choices[{{ $index }}][text]"
                                               value="{{ $choice['text'] ?? '' }}"
                                               placeholder="選択肢を入力" required>
                                    </div>
                                    <div class="col-md-3 col-8">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="correct_choice"
                                                   id="correct_{{ $index }}" value="{{ $index }}"
                                                   {{ old('correct_choice') == $index ? 'checked' : ($index === 0 ? 'checked' : '') }}>
                                            <label class="form-check-label" for="correct_{{ $index }}">正解</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-4 text-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-choice" title="削除">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addChoice">
                        <i class="bi bi-plus-circle me-1"></i>選択肢を追加
                    </button>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                <button type="button"
                        id="cancel-button__create"
                        class="btn btn-light border">
                    キャンセル
                </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-1"></i>問題を登録する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection