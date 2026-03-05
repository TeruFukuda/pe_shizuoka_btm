@extends('layouts.app')

@section('title','問題に解答する')

@section('content')
<div class="problem-selection-container">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 fixed-height-card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">ジャンル一覧</h5>
                    </div>
                    <div class="list-group list-group-flush scrollable-body" id="genreList">
                        @foreach($genres as $genre)
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center genre-item" data-genre-id="{{ $genre->id }}">
                            {{ $genre->name }}
                            <span class="badge bg-secondary rounded-pill">5</span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0 fixed-height-card">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">問題一覧</h5>
                    </div>

                    <div id="questionsContainer" class="scrollable-body bg-light-subtle">
                        <div class="d-flex flex-column h-100 align-items-center justify-content-center text-center p-5">
                            <div class="empty-state-icon mb-3">
                                <i class="bi bi-arrow-left-circle-fill display-1 text-dark opacity-75"></i>
                            </div>
                            <h4 class="fw-bold text-secondary">ジャンルを選択してください</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
