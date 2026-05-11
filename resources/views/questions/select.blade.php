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
                    <div class="table-responsive scrollable-body" id="genreList">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th scope="col" class="ps-3">ジャンル名</th>
                                    <th scope="col" class="text-end pe-3">未解答問題数</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($genres as $genre)
                                <tr class="genre-item cursor-pointer" data-genre-id="{{ $genre->id }}" style="cursor: pointer;">
                                    <td class="ps-3 fw-bold text-dark">
                                        {{ $genre->name }}
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($genre->unanswered_count > 0)
                                            <span class="badge rounded-pill shadow-sm" style="background-color: #0d47a1; color: white;">
                                                {{ $genre->unanswered_count }}
                                            </span>
                                        @else
                                            <span class="badge bg-success rounded-pill shadow-sm">
                                                <i class="bi bi-check-lg"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
