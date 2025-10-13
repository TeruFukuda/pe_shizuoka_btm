<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードリセット - PE静岡</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .reset-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }
        .reset-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .reset-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .reset-subtitle {
            color: #666;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <h2 class="reset-title">パスワードリセット</h2>
                <p class="reset-subtitle">新しいパスワードを設定してください</p>
            </div>
            
            <form method="POST" action="{{ route('password.reset') }}">
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
                           placeholder="登録済みのメールアドレスを入力">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="new_password" class="form-label">新しいパスワード</label>
                    <input type="password" 
                           class="form-control @error('new_password') is-invalid @enderror" 
                           id="new_password" 
                           name="new_password" 
                           required 
                           autocomplete="new-password"
                           placeholder="新しいパスワードを入力">
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">新しいパスワード（確認）</label>
                    <input type="password" 
                           class="form-control" 
                           id="new_password_confirmation" 
                           name="new_password_confirmation" 
                           required 
                           autocomplete="new-password"
                           placeholder="新しいパスワードを再入力">
                </div>
                
                <button type="submit" class="btn btn-reset">
                    パスワードをリセット
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
            
            <div class="login-link">
                <p>パスワードを覚えている場合は <a href="{{ route('login') }}">ログイン</a></p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
