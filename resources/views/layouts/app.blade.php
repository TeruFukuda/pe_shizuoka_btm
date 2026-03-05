<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>技術部屋システム - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/js/main.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('scripts')
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}" >
    @stack('styles')
</head>
<body>
    <!-- サイドバー -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3 class="sidebar-title">PE静岡</h3>
        </div>
        <nav class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i class="bi bi-house-door menu-icon"></i>
                ダッシュボード
            </a>
            <a href="#" class="menu-item" data-content="problem-creation">
                <i class="bi bi-plus-circle menu-icon"></i>
                問題を作成する
            </a>
            <a href="#" class="menu-item" data-content="problem-selection">
                <i class="bi bi-list-check menu-icon"></i>
                問題に解答する
            </a>
            <a href="#" class="menu-item" data-content="problem-list">
                <i class="bi bi-file-text menu-icon"></i>
                あなたが作った問題
            </a>
        </nav>
    </div>

    <!-- モバイルメニューボタン -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="bi bi-list"></i>
    </button>

    <!-- メインコンテンツ -->
    <div class="main-content">
        <div class="dashboard-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="mb-0">@yield('title')</h1>
                        <p class="mb-0">PE静岡システムへようこそ</p>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <span class="me-3 text-white">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name }}さん
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">
                                ログアウト
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            @yield('content')
        </div>
    </div>
</body>
</html>
