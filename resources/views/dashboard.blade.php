<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード - PE静岡</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        /* サイドバー */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-item {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: white;
            color: white;
            text-decoration: none;
        }
        
        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left-color: white;
        }
        
        .menu-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* メインコンテンツ */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .welcome-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            color: white;
        }
        
        /* モバイル対応 */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: #667eea;
                color: white;
                border: none;
                border-radius: 5px;
                padding: 10px;
                font-size: 1.2rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }
        }
    </style>
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
                問題作成
            </a>
            <a href="#" class="menu-item" data-content="problem-selection">
                <i class="bi bi-list-check menu-icon"></i>
                問題選択
            </a>
            <a href="#" class="menu-item" data-content="problem-list">
                <i class="bi bi-file-text menu-icon"></i>
                作成済み問題一覧
            </a>
            <a href="#" class="menu-item" data-content="genre-list">
                <i class="bi bi-tags menu-icon"></i>
                ジャンル一覧
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
                    <h1 class="mb-0">ダッシュボード</h1>
                    <p class="mb-0">PE静岡システムへようこそ</p>
                </div>
                <div class="col-auto">
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
        <!-- メインコンテンツエリア -->
        <div id="mainContent">
            <div class="welcome-card" id="dashboardContent">
                <h2>ようこそ、{{ Auth::user()->name }}さん！</h2>
                <p class="text-muted">ログインが成功しました。</p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5>ユーザー情報</h5>
                        <ul class="list-unstyled">
                            <li><strong>名前:</strong> {{ Auth::user()->name }}</li>
                            <li><strong>メールアドレス:</strong> {{ Auth::user()->email }}</li>
                            <li><strong>登録日:</strong> {{ Auth::user()->created_at->format('Y年m月d日') }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>システム情報</h5>
                        <ul class="list-unstyled">
                            <li><strong>ログイン時刻:</strong> {{ now()->format('Y年m月d日 H:i:s') }}</li>
                            <li><strong>セッションID:</strong> {{ session()->getId() }}</li>
                        </ul>
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
    </div>

    </div> <!-- main-content の終了 -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // モバイルメニューの切り替え
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        });

        // サイドバー外をクリックしたらメニューを閉じる（モバイル）
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileMenuBtn.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });

        // メニュー項目のクリック処理
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // アクティブクラスを更新
                document.querySelectorAll('.menu-item').forEach(menu => {
                    menu.classList.remove('active');
                });
                this.classList.add('active');
                
                // コンテンツの切り替え
                const contentType = this.getAttribute('data-content');
                const mainContent = document.getElementById('mainContent');
                
                if (contentType === 'problem-list') {
                    // 問題一覧を表示
                    loadProblemList();
                } else if (contentType === 'problem-creation') {
                    // 問題作成を表示
                    loadProblemCreation();
                } else if (contentType === 'problem-selection') {
                    // 問題選択を表示
                    loadProblemSelection();
                } else if (contentType === 'genre-list') {
                    // ジャンル一覧を表示
                    loadGenreList();
                } else {
                    // ダッシュボードを表示
                    showDashboard();
                }
                
                // モバイルでメニューを閉じる
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('open');
                }
            });
        });

        // ダッシュボード表示関数
        function showDashboard() {
            const mainContent = document.getElementById('mainContent');
            mainContent.innerHTML = `
                <div class="welcome-card" id="dashboardContent">
                    <h2>ようこそ、{{ Auth::user()->name }}さん！</h2>
                    <p class="text-muted">ログインが成功しました。</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>ユーザー情報</h5>
                            <ul class="list-unstyled">
                                <li><strong>名前:</strong> {{ Auth::user()->name }}</li>
                                <li><strong>メールアドレス:</strong> {{ Auth::user()->email }}</li>
                                <li><strong>登録日:</strong> {{ Auth::user()->created_at->format('Y年m月d日') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>システム情報</h5>
                            <ul class="list-unstyled">
                                <li><strong>ログイン時刻:</strong> ${new Date().toLocaleString('ja-JP')}</li>
                                <li><strong>セッションID:</strong> {{ session()->getId() }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            `;
        }

        // 問題一覧表示関数
        function loadProblemList() {
            loadProblemListPage(1);
        }

        // 問題一覧ページ表示関数
        function loadProblemListPage(page = 1) {
            const mainContent = document.getElementById('mainContent');
            
            // ローディング表示
            mainContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">読み込み中...</span>
                    </div>
                    <p class="mt-3">問題一覧を読み込み中...</p>
                </div>
            `;
            
            console.log('問題一覧の読み込み開始 (ページ:', page, ')');
            
            // AJAXで問題一覧を取得
            fetch(`/problems?page=${page}`)
                .then(response => {
                    console.log('レスポンス受信:', response.status, response.statusText);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    return response.text();
                })
                .then(html => {
                    console.log('HTMLレスポンス受信:', html.length, '文字');
                    console.log('HTML内容（最初の500文字）:', html.substring(0, 500));
                    
                    // レスポンスから問題一覧部分のみを抽出
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const problemContent = doc.querySelector('.problems-container');
                    
                    console.log('問題コンテンツ要素:', problemContent);
                    
                    if (problemContent) {
                        console.log('問題一覧を表示');
                        mainContent.innerHTML = problemContent.outerHTML;
                        
                        // 読み込まれたコンテンツに対してJavaScriptを実行
                        setupProblemListFilters();
                    } else {
                        console.error('問題コンテンツが見つかりません');
                        console.log('利用可能な要素:', doc.querySelectorAll('*'));
                        mainContent.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                問題一覧の読み込みに失敗しました。
                                <br><small>HTMLレスポンス: ${html.substring(0, 200)}...</small>
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
                            <br><small>ページを再読み込みしてください。</small>
                        </div>
                    `;
                });
        }

        // 問題一覧のフィルター機能を設定
        function setupProblemListFilters() {
            console.log('問題一覧フィルター設定開始');
            
            const searchInput = document.getElementById('searchInput');
            const genreFilter = document.getElementById('genreFilter');
            const emptyState = document.getElementById('emptyState');
            
            console.log('要素取得結果:', {
                searchInput: searchInput,
                genreFilter: genreFilter,
                emptyState: emptyState
            });

            function filterProblems() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedGenreId = genreFilter.value;
                
                console.log('フィルター実行:', { searchTerm, selectedGenreId });
                
                // テーブル行を取得
                const tableRows = document.querySelectorAll('#problemsTableBody tr');
                let visibleCount = 0;

                console.log('見つかったテーブル行数:', tableRows.length);

                tableRows.forEach((row, index) => {
                    const questionText = row.cells[1].textContent.toLowerCase(); // 問題列（2番目の列）
                    const genreId = row.dataset.genreId;

                    const matchesSearch = searchTerm === '' || questionText.includes(searchTerm);
                    const matchesGenre = selectedGenreId === '' || genreId === selectedGenreId;

                    console.log(`行${index + 1}:`, {
                        questionText: questionText.substring(0, 50) + '...',
                        genreId,
                        matchesSearch,
                        matchesGenre
                    });

                    if (matchesSearch && matchesGenre) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                console.log('表示される行数:', visibleCount);

                // 空の状態表示の切り替え
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }

            // イベントリスナーの追加
            if (searchInput) {
                console.log('検索ボックスのイベントリスナーを追加');
                searchInput.addEventListener('input', function(e) {
                    console.log('検索ボックス入力イベント:', e.target.value);
                    filterProblems();
                });
            } else {
                console.error('検索ボックスが見つかりません');
            }
            
            if (genreFilter) {
                console.log('ジャンルフィルターのイベントリスナーを追加');
                genreFilter.addEventListener('change', function(e) {
                    console.log('ジャンルフィルター変更イベント:', e.target.value);
                    filterProblems();
                });
            } else {
                console.error('ジャンルフィルターが見つかりません');
            }
            
            // ページネーションリンクのイベントリスナーを設定
            const paginationLinks = document.querySelectorAll('.pagination-link[data-page]');
            console.log('ページネーションリンク数:', paginationLinks.length);
            
            paginationLinks.forEach((link, index) => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));
                    console.log(`ページネーションリンク${index + 1}クリック:`, page);
                    loadProblemListPage(page);
                });
            });
            
            // 初期化時にフィルターを実行
            console.log('初期化時にフィルターを実行');
            filterProblems();
        }

        // 問題作成表示関数
        function loadProblemCreation() {
            const mainContent = document.getElementById('mainContent');
            
            // ローディング表示
            mainContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">読み込み中...</span>
                    </div>
                    <p class="mt-3">問題作成画面を読み込み中...</p>
                </div>
            `;
            
            // AJAXで問題作成画面を取得
            fetch('/problems/create')
                .then(response => response.text())
                .then(html => {
                    // レスポンスから問題作成部分のみを抽出
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const creationContent = doc.querySelector('.problem-creation-container');
                    
                    if (creationContent) {
                        mainContent.innerHTML = creationContent.outerHTML;
                    } else {
                        mainContent.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                問題作成画面の読み込みに失敗しました。
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mainContent.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            エラーが発生しました。ページを再読み込みしてください。
                        </div>
                    `;
                });
        }

        // 問題選択表示関数
        function loadProblemSelection() {
            const mainContent = document.getElementById('mainContent');
            
            // ローディング表示
            mainContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">読み込み中...</span>
                    </div>
                    <p class="mt-3">問題選択画面を読み込み中...</p>
                </div>
            `;
            
            // AJAXで問題選択画面を取得
            fetch('/problems/select')
                .then(response => response.text())
                .then(html => {
                    // レスポンスから問題選択部分のみを抽出
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const selectionContent = doc.querySelector('.problem-selection-container');
                    
                    if (selectionContent) {
                        mainContent.innerHTML = selectionContent.outerHTML;
                    } else {
                        mainContent.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                問題選択画面の読み込みに失敗しました。
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mainContent.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            エラーが発生しました。ページを再読み込みしてください。
                        </div>
                    `;
                });
        }

        // ジャンル一覧表示関数
        function loadGenreList() {
            const mainContent = document.getElementById('mainContent');
            
            // ローディング表示
            mainContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">読み込み中...</span>
                    </div>
                    <p class="mt-3">ジャンル一覧を読み込み中...</p>
                </div>
            `;
            
            // AJAXでジャンル一覧を取得
            fetch('/genres')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    // レスポンスからジャンル一覧部分のみを抽出
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const genreContent = doc.querySelector('.genres-container');
                    
                    if (genreContent) {
                        mainContent.innerHTML = genreContent.outerHTML;
                        
                        // ジャンル一覧内のリンクを処理
                        setupGenreLinks();
                    } else {
                        mainContent.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                ジャンル一覧の読み込みに失敗しました。
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

        // ジャンル新規登録表示関数
        function loadGenreCreate() {
            const mainContent = document.getElementById('mainContent');
            
            // ローディング表示
            mainContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">読み込み中...</span>
                    </div>
                    <p class="mt-3">ジャンル新規登録画面を読み込み中...</p>
                </div>
            `;
            
            // AJAXでジャンル新規登録画面を取得
            fetch('/genres/create')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    // レスポンスからジャンル新規登録部分のみを抽出
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const createContent = doc.querySelector('.genre-create-container');
                    
                    if (createContent) {
                        mainContent.innerHTML = createContent.outerHTML;
                        
                        // フォームの送信を処理
                        setupGenreForm();
                    } else {
                        mainContent.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                ジャンル新規登録画面の読み込みに失敗しました。
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

        // ジャンル一覧内のリンクを処理
        function setupGenreLinks() {
            // 新規登録ボタンのクリック処理
            const createButtons = document.querySelectorAll('a[href*="/genres/create"]');
            createButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadGenreCreate();
                });
            });
            
            // 編集ボタンのクリック処理
            const editButtons = document.querySelectorAll('.edit-genre-btn');
            console.log('編集ボタン数:', editButtons.length);
            editButtons.forEach((button, index) => {
                console.log(`編集ボタン${index + 1}:`, button);
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const genreId = this.getAttribute('data-genre-id');
                    console.log('編集ボタンクリック:', genreId);
                    console.log('クリックされたボタン:', this);
                    loadGenreEdit(genreId);
                });
            });
            
            // イベント委譲を使用した追加の処理
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-genre-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const button = e.target.closest('.edit-genre-btn');
                    const genreId = button.getAttribute('data-genre-id');
                    console.log('イベント委譲で編集ボタンクリック:', genreId);
                    loadGenreEdit(genreId);
                }
            });
        }

        // ジャンルフォームの送信を処理
        function setupGenreForm() {
            const form = document.querySelector('form[action*="/genres"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    
                    fetch('/genres', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => {
                        if (response.redirected) {
                            // リダイレクトの場合はジャンル一覧を再読み込み
                            loadGenreList();
                        } else {
                            return response.text();
                        }
                    })
                    .then(html => {
                        if (html) {
                            // エラーメッセージを表示
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const errorContent = doc.querySelector('.alert-danger, .alert-warning');
                            
                            if (errorContent) {
                                document.getElementById('mainContent').innerHTML = errorContent.outerHTML;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('mainContent').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                エラーが発生しました: ${error.message}
                            </div>
                        `;
                    });
                });
            }
            
            // 新規登録のキャンセルボタンの処理
            const cancelCreateBtn = document.querySelector('#cancelCreateBtn');
            if (cancelCreateBtn) {
                cancelCreateBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('新規登録キャンセルボタンクリック: ジャンル一覧に戻る');
                    loadGenreList();
                });
            }
        }

        // ジャンル編集表示関数
        function loadGenreEdit(genreId) {
            const mainContent = document.getElementById('mainContent');
            
            if (!mainContent) {
                console.error('mainContent要素が見つかりません');
                return;
            }
            
            // ローディング表示
            mainContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">読み込み中...</span>
                    </div>
                    <p class="mt-3">ジャンル編集画面を読み込み中...</p>
                </div>
            `;
            
            console.log('ジャンル編集画面を読み込み中:', genreId);
            console.log('mainContent要素:', mainContent);
            
            // AJAXでジャンル編集画面を取得
            fetch(`/genres/${genreId}/edit`)
                .then(response => {
                    console.log('レスポンス受信:', response.status, response.statusText);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
                    }
                    return response.text();
                })
                .then(html => {
                    console.log('HTML受信:', html.length, '文字');
                    console.log('HTML内容（最初の500文字）:', html.substring(0, 500));
                    
                    // レスポンスからジャンル編集部分のみを抽出
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const editContent = doc.querySelector('.genre-edit-container');
                    
                    console.log('編集コンテンツ検索結果:', editContent ? '見つかった' : '見つからない');
                    
                    if (editContent) {
                        mainContent.innerHTML = editContent.outerHTML;
                        console.log('ジャンル編集画面を表示完了');
                        
                        // フォームの送信を処理
                        setupGenreEditForm();
                    } else {
                        // フォールバック: 直接HTMLを表示
                        console.log('フォールバック: 直接HTMLを表示');
                        mainContent.innerHTML = html;
                        
                        // フォームの送信を処理
                        setupGenreEditForm();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mainContent.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            エラーが発生しました: ${error.message}
                            <br><small>ジャンルID: ${genreId}</small>
                        </div>
                    `;
                });
        }

        // ジャンル編集フォームの送信を処理
        function setupGenreEditForm() {
            const form = document.querySelector('#genreEditForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    const genreId = form.action.split('/').pop();
                    
                    fetch(`/genres/${genreId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-HTTP-Method-Override': 'PUT'
                        }
                    })
                    .then(response => {
                        if (response.redirected) {
                            // リダイレクトの場合はジャンル一覧を再読み込み
                            loadGenreList();
                        } else {
                            return response.text();
                        }
                    })
                    .then(html => {
                        if (html) {
                            // エラーメッセージを表示
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const errorContent = doc.querySelector('.alert-danger, .alert-warning');
                            
                            if (errorContent) {
                                document.getElementById('mainContent').innerHTML = errorContent.outerHTML;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('mainContent').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                エラーが発生しました: ${error.message}
                            </div>
                        `;
                    });
                });
            }
            
            // キャンセルボタンの処理
            const cancelBtn = document.querySelector('#cancelEditBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('キャンセルボタンクリック: ジャンル一覧に戻る');
                    loadGenreList();
                });
            }
        }

        // グローバル関数として定義（ページネーションリンクから呼び出し可能）
        window.loadProblemListPage = function(page) {
            console.log('グローバル関数 loadProblemListPage が呼び出されました:', page);
            // 実際の関数を呼び出す（無限ループを防ぐ）
            const mainContent = document.getElementById('mainContent');
            
            // ローディング表示
            mainContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">読み込み中...</span>
                    </div>
                    <p class="mt-3">問題一覧を読み込み中...</p>
                </div>
            `;
            
            console.log('問題一覧の読み込み開始 (ページ:', page, ')');
            
            // AJAXで問題一覧を取得
            fetch(`/problems?page=${page}`)
                .then(response => {
                    console.log('レスポンス受信:', response.status, response.statusText);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    return response.text();
                })
                .then(html => {
                    console.log('HTMLレスポンス受信:', html.length, '文字');
                    
                    // レスポンスから問題一覧部分のみを抽出
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const problemContent = doc.querySelector('.problems-container');
                    
                    if (problemContent) {
                        console.log('問題一覧を表示');
                        mainContent.innerHTML = problemContent.outerHTML;
                        
                        // 読み込まれたコンテンツに対してJavaScriptを実行
                        setupProblemListFilters();
                    } else {
                        console.error('問題コンテンツが見つかりません');
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
        };

        // イベント委譲でページネーションリンクのクリックを処理（一度だけ設定）
        if (!window.paginationEventAdded) {
            document.addEventListener('click', function(e) {
                console.log('クリックイベント発生:', e.target);
                
                // data-page属性を使用したページネーションリンク
                if (e.target.matches('.pagination-link[data-page]')) {
                    e.preventDefault();
                    const page = parseInt(e.target.getAttribute('data-page'));
                    console.log('イベント委譲でページネーションクリック (data-page):', page);
                    window.loadProblemListPage(page);
                    return;
                }
                
                // onclick属性を使用したページネーションリンク（フォールバック）
                if (e.target.matches('.page-link[onclick*="loadProblemListPage"]')) {
                    e.preventDefault();
                    const onclick = e.target.getAttribute('onclick');
                    const match = onclick.match(/loadProblemListPage\((\d+)\)/);
                    if (match) {
                        const page = parseInt(match[1]);
                        console.log('イベント委譲でページネーションクリック (onclick):', page);
                        window.loadProblemListPage(page);
                        return;
                    }
                }
                
                // より広範囲なセレクターでページネーションリンクを検出
                if (e.target.closest('.pagination')) {
                    const link = e.target.closest('a[data-page]');
                    if (link) {
                        e.preventDefault();
                        const page = parseInt(link.getAttribute('data-page'));
                        console.log('ページネーションリンク検出 (closest):', page);
                        window.loadProblemListPage(page);
                        return;
                    }
                }
            });
            window.paginationEventAdded = true;
        }
    </script>
</body>
</html>
