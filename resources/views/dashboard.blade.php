@extends('layouts.app')

@section('title','ダッシュボード')

@section('content')
<div id="mainContent">
    <div class="pagination-container" id="dashboardPagination">
        <div class="pagination-page active" id="page1">
            <div class="welcome-card">
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

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">能力分析</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div style="height: 300px;">
                                            <canvas id="radarChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>各分野の習熟度</h6>
                                        @php
                                            $badgeColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary', 'bg-dark'];
                                        @endphp
                                        @foreach($genres as $index => $genre)
                                        <div class="mb-2">
                                            <span class="badge {{ $badgeColors[$index % count($badgeColors)] }} me-2">{{ $genre->name }}</span>
                                            {{-- data-percent属性に数値をいれておくとJSで拾えます --}}
                                            <span class="fw-bold genre-percent" data-genre-name="{{ $genre->name }}">85%</span>
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

        <div class="pagination-page" id="page2">
            <div class="welcome-card">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">ジャンル別出題状況</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>あなたの出題傾向</h6>
                                        <div style="height: 250px;">
                                            <canvas id="userGenreChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>全体の出題傾向</h6>
                                        <div style="height: 250px;">
                                            <canvas id="totalGenreChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="pagination-nav prev" id="prevPage">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="pagination-nav next" id="nextPage">
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="pagination-indicator">
            <div class="pagination-dot active" data-page="1"></div>
            <div class="pagination-dot" data-page="2"></div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- 1. ページング(Slide)機能 ---
    let currentPage = 1;
    const totalPages = 2;
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const dots = document.querySelectorAll('.pagination-dot');
    const container = document.getElementById('dashboardPagination');

    function goToPage(page) {
        if (page < 1 || page > totalPages) return;

        document.querySelectorAll('.pagination-page').forEach((el, idx) => {
            el.classList.toggle('active', (idx + 1) === page);
        });

        dots.forEach((dot, idx) => {
            dot.classList.toggle('active', (idx + 1) === page);
        });

        currentPage = page;

        // ページ2に切り替わった時に棒グラフを再描画（サイズ調整のため）
        if (page === 2) {
            initBarCharts();
        }
    }

    if(prevBtn) prevBtn.addEventListener('click', () => goToPage(currentPage - 1));
    if(nextBtn) nextBtn.addEventListener('click', () => goToPage(currentPage + 1));
    dots.forEach((dot, idx) => dot.addEventListener('click', () => goToPage(idx + 1)));

    // マウスホバーで矢印を出す演出
    if (container) {
        container.addEventListener('mousemove', function(e) {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const width = rect.width;
            if (x > width * 0.8 && currentPage < totalPages) nextBtn.style.opacity = '1';
            else nextBtn.style.opacity = '0';
            if (x < width * 0.2 && currentPage > 1) prevBtn.style.opacity = '1';
            else prevBtn.style.opacity = '0';
        });
    }

    // --- 2. レーダーチャート初期化 ---
    function initRadarChart() {
        const ctx = document.getElementById('radarChart');
        if (!ctx) return;

        const labels = [];
        const data = [];
        document.querySelectorAll('.genre-percent').forEach(el => {
            labels.push(el.dataset.genreName);
            data.push(parseInt(el.textContent));
        });

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    label: '習熟度',
                    data: data,
                    backgroundColor: 'rgba(102, 126, 234, 0.2)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { r: { beginAtZero: true, max: 100 } }
            }
        });
    }

    // --- 3. 棒グラフ初期化 (Page 2用) ---
    function initBarCharts() {
        const barOptions = {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        };

        const userCtx = document.getElementById('userGenreChart');
        if (userCtx && !userCtx.dataset.rendered) {
            new Chart(userCtx, {
                type: 'bar',
                data: {
                    labels: ['PHP', 'Laravel', 'DB', 'AWS'], // 仮データ
                    datasets: [{ data: [12, 19, 3, 5], backgroundColor: 'rgba(102, 126, 234, 0.8)' }]
                },
                options: barOptions
            });
            userCtx.dataset.rendered = "true";
        }

        const totalCtx = document.getElementById('totalGenreChart');
        if (totalCtx && !totalCtx.dataset.rendered) {
            new Chart(totalCtx, {
                type: 'bar',
                data: {
                    labels: ['PHP', 'Laravel', 'DB', 'AWS'], // 仮データ
                    datasets: [{ data: [120, 150, 80, 45], backgroundColor: 'rgba(40, 167, 69, 0.8)' }]
                },
                options: barOptions
            });
            totalCtx.dataset.rendered = "true";
        }
    }

    // 初期実行
    initRadarChart();
});
</script>
@endpush

@push('styles')
<style>
    .pagination-container { position: relative; overflow: hidden; min-height: 500px; }
    .pagination-page { display: none; }
    .pagination-page.active { display: block; animation: fadeIn 0.4s ease-in-out; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .pagination-nav {
        position: absolute; top: 50%; transform: translateY(-50%);
        background: rgba(0,0,0,0.05); border: none; border-radius: 50%;
        width: 50px; height: 50px; opacity: 0; transition: all 0.3s; z-index: 10;
    }
    .pagination-nav.prev { left: 10px; }
    .pagination-nav.next { right: 10px; }
    .pagination-nav:hover { background: rgba(0,0,0,0.1); transform: translateY(-50%) scale(1.1); }

    .pagination-indicator { display: flex; justify-content: center; gap: 10px; margin-top: 20px; }
    .pagination-dot { width: 12px; height: 12px; border-radius: 50%; background: #ddd; cursor: pointer; transition: 0.3s; }
    .pagination-dot.active { background: #667eea; transform: scale(1.2); }
</style>
@endpush