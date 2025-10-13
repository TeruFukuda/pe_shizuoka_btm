<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - PE静岡</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2 class="login-title">ログイン</h2>
                <p class="login-subtitle">アカウントにログインしてください</p>
            </div>
            
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email"
                           placeholder="メールアドレスを入力">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">パスワード</label>
                    <div class="password-input-group">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="パスワードを入力">
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            ログイン状態を保持する
                        </label>
                    </div>
                    <a href="{{ route('password.reset') }}" class="forgot-password">パスワードを忘れた場合</a>
                </div>
                
                <button type="submit" class="btn btn-primary login-btn" id="loginBtn">
                    <span class="btn-text">ログイン</span>
                    <span class="btn-spinner d-none">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        ログイン中...
                    </span>
                </button>
                
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
            
            <div class="login-footer">
                <p>アカウントをお持ちでない場合は <a href="#" class="register-link">新規登録</a></p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
