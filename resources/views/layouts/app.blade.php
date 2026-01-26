<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>技術部屋システム - @yield('title')</title>

    {{-- CSSは先読み --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
    @stack('styles')
</head>

<body>
    {{-- モバイル用メニューボタン（main.jsで動くようにID付与） --}}
    <button id="mobileMenuBtn" class="btn btn-dark d-md-none position-fixed top-0 end-0 m-2" style="z-index: 1050;">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3 class="sidebar-title">PE静岡</h3>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door menu-icon"></i>ダッシュボード
            </a>
            <a href="{{ route('questions.create') }}" class="menu-item {{ request()->routeIs('questions.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle menu-icon"></i>問題を作成する
            </a>
            <a href="{{ route('questions.select') }}" class="menu-item {{ request()->routeIs('questions.select') ? 'active' : '' }}">
                <i class="bi bi-list-check menu-icon"></i>問題に解答する
            </a>
            <a href="{{ route('questions.index') }}" class="menu-item {{ request()->routeIs('questions.index') ? 'active' : '' }}">
                <i class="bi bi-file-text menu-icon"></i>あなたが作った問題
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <h1 class="ms-4">@yield('title')</h1>
        </div>
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    {{-- JSは最後に読み込む（高速化とエラー防止） --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- 共通JS --}}
    <script src="{{ asset('/js/main.js') }}"></script>

    {{-- 画面個別のJS（ここで個別画面のグラフ描画などが動く） --}}
    @stack('scripts')
</body>

</html>