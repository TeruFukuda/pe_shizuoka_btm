@extends('layouts.app')

@section('title', '問題に解答する')

@section('content')
<div class="problem-selection-container container py-4">

    <div class="selection-content">
        <div class="row g-4">
            <div class="col-md-5">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 fw-bold">ジャンル</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-uppercase small fw-bold">
                                    <th class="ps-4 py-3">ジャンル名</th>
                                    <th class="text-end pe-4 py-3">未解答問題数</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($genres as $genre)
                                <tr class="genre--row" data-genre-id="{{ $genre->id }}" style="cursor: pointer;">
                                    <td class="ps-4 fw-bold text-dark">{{ $genre->name }}</td>
                                    <td class="text-end pe-4">
                                        <span class="badge rounded-pill bg-white text-dark border border-dark px-3">
                                            {{ $genre->unanswered_count }} 件
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <span id="selectedGenreName">問題一覧</span>
                        </h5>
                    </div>
                    <div class="table-responsive" style="min-height: 400px;">
                        <table class="table table-hover align-middle mb-0">
                            <tbody id="questionsTableBody">
                                {{-- 初期状態のメッセージ --}}
                                <tr id="initialMessage">
                                    <td class="text-center py-5">
                                        <div class="py-5">
                                            <i class="bi bi-arrow-left-circle text-muted" style="font-size: 3rem;"></i>
                                            <p class="mt-3 text-muted">左のリストからジャンルを選択してください</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JSで複製するための雛形（画面には表示されない） --}}
<template id="questionRowTemplate">
    <tr>
        <td class="ps-4 py-3">
            <a href="" class="fw-bold text-decoration-none question-link question-title text-truncate-custom"></a>
        </td>
    </tr>
</template>

<style>
    .problem-selection-container {
        font-family: 'Helvetica Neue', Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif;
    }

    /* ジャンル行のホバーとアクティブ */
    .genre--row:hover {
        background-color: #f8f9fa !important;
    }
    .genre--row.active {
        background-color: #e9ecef !important;
        border-left: 5px solid #212529;
    }
    .genre--row.active td {
        color: #000 !important;
    }

    /* 問題タイトルのリンク色 */
    .question-title {
        color: #0d6efd;
        transition: color 0.2s;
        display: block;
    }
    .question-title:hover {
        color: #004db3;
        text-decoration: underline !important;
    }

    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    .table > :not(caption) > * > * {
        border-bottom-color: #eee;
    }
    /* 一行で収まらない場合に「...」にする設定 */
    .text-truncate-custom {
        display: block;
        width: 100%;
        white-space: nowrap;     /* 改行させない */
        overflow: hidden;        /* はみ出た分を隠す */
        text-overflow: ellipsis; /* はみ出た分を「...」にする */
    }

    /* 右側テーブルのレイアウトを安定させる */
    #questionsTableBody td {
        max-width: 0; /* これがないとテーブルが中身に合わせて広がってしまうのを防げます */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const genreRows = document.querySelectorAll('.genre--row');
    const questionsTableBody = document.getElementById('questionsTableBody');
    const selectedGenreName = document.getElementById('selectedGenreName');
    const template = document.getElementById('questionRowTemplate');

    genreRows.forEach(row => {
        row.addEventListener('click', async function() {
            // 1. 左側のアクティブ状態を切り替え
            genreRows.forEach(r => r.classList.remove('active'));
            this.classList.add('active');

            // 2. タイトルの更新
            const genreName = this.querySelector('td').innerText;
            selectedGenreName.innerText = `${genreName} の問題一覧`;

            // 3. データ取得
            const genreId = this.dataset.genreId;

            // ローディング表示（初期メッセージを消す）
            questionsTableBody.innerHTML = '<tr><td class="text-center py-5">読み込み中...</td></tr>';

            try {
                const response = await fetch(`/api/genres/${genreId}/questions`);

                const questions = await response.json();

                questionsTableBody.innerHTML = '';

                if (questions.length === 0) {
                    questionsTableBody.innerHTML = '<tr><td class="text-center py-5 text-muted">このジャンルには問題がありません。</td></tr>';
                    return;
                }

                // テンプレートを使って行を追加
                questions.forEach(question => {
                    const clone = template.content.cloneNode(true);
                    const link = clone.querySelector('.question-link');

                    link.innerText = question.question;
                    link.href = `/questions/${question.id}`; // 解答画面へのパス

                    questionsTableBody.appendChild(clone);
                });

            } catch (error) {
                console.error('Error:', error);
                questionsTableBody.innerHTML = '<tr><td class="text-center py-5 text-danger">データの取得に失敗しました。</td></tr>';
            }
        });
    });
});
</script>
@endsection