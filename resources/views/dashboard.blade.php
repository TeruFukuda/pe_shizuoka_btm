@extends('layouts.app')

@section('title','ダッシュボード')

@section('content')
<!-- メインコンテンツエリア -->
<div id="mainContent">
    <div class="pagination-container" id="dashboardPagination">
        <!-- ページ1: 解答状況と能力分析 -->
        <div class="pagination-page active" id="page1">
            <div class="welcome-card">
                <!-- 解答状況と未解答問題のカード -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">解答状況</h5>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>・あなたが解答した問題数</span>
                                    <span class="fw-bold">23/64問</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>・あなたの正解率</span>
                                    <span class="fw-bold">73%</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>・前回解答日</span>
                                    <span class="fw-bold">2025/02/18</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">未解答問題</h5>
                                <div class="text-end">
                                    <div class="mb-1">No.104</div>
                                    <div class="mb-1">No.103</div>
                                    <div class="mb-1">No.102</div>
                                    <div class="mb-1">No.101</div>
                                    <div class="mb-1">No.100</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- レーダーチャート -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">能力分析</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <canvas id="radarChart" width="400" height="300"></canvas>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>各分野の習熟度</h6>
                                        @php
                                            $badgeColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary', 'bg-dark'];
                                        @endphp
                                        @foreach($genres as $index => $genre)
                                        <div class="mb-2">
                                            <span class="badge {{ $badgeColors[$index % count($badgeColors)] }} me-2">{{ $genre->name }}</span>
                                            <span class="fw-bold" data-genre-id="{{ $genre->id }}">85%</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ページ2: 出題情報 -->
        <div class="pagination-page" id="page2">
            <div class="welcome-card">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">出題情報</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>最近の出題傾向</h6>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>・過去30日間の出題数</span>
                                                <span class="fw-bold">{{ $recentStats['total_questions'] }}問</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>・あなたの出題数</span>
                                                <span class="fw-bold">{{ $recentStats['user_questions'] }}問</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>・最も出題されたジャンル</span>
                                                <span class="fw-bold">{{ $recentStats['most_popular_genre'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>今後の出題予定</h6>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>・次回出題予定日</span>
                                                <span class="fw-bold">2025/02/25</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>・予定問題数</span>
                                                <span class="fw-bold">25問</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>・重点分野</span>
                                                <span class="fw-bold">ネットワーク</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ページングナビゲーション -->
        <button class="pagination-nav prev" id="prevPage">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="pagination-nav next" id="nextPage">
            <i class="bi bi-chevron-right"></i>
        </button>

        <!-- ページインジケーター -->
        <div class="pagination-indicator">
            <div class="pagination-dot active" data-page="1"></div>
            <div class="pagination-dot" data-page="2"></div>
        </div>
    </div>
</div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endsection