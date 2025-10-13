// ログインページのJavaScript
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');
    const loginBtn = document.getElementById('loginBtn');
    const btnText = document.querySelector('.btn-text');
    const btnSpinner = document.querySelector('.btn-spinner');

    // パスワード表示/非表示の切り替え
    passwordToggle.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // アイコンの切り替え
        if (type === 'text') {
            passwordIcon.className = 'bi bi-eye-slash';
        } else {
            passwordIcon.className = 'bi bi-eye';
        }
    });

    // フォーム送信時の処理
    loginForm.addEventListener('submit', function(e) {
        // バリデーション
        if (!validateForm()) {
            e.preventDefault();
            return;
        }

        // ローディング状態の表示
        showLoadingState();
    });

    // フォームバリデーション
    function validateForm() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        let isValid = true;

        // ユーザー名のバリデーション
        if (username === '') {
            showFieldError('username', 'ユーザー名またはメールアドレスを入力してください');
            isValid = false;
        } else {
            clearFieldError('username');
        }

        // パスワードのバリデーション
        if (password === '') {
            showFieldError('password', 'パスワードを入力してください');
            isValid = false;
        } else if (password.length < 6) {
            showFieldError('password', 'パスワードは6文字以上で入力してください');
            isValid = false;
        } else {
            clearFieldError('password');
        }

        return isValid;
    }

    // フィールドエラーの表示
    function showFieldError(fieldName, message) {
        const field = document.getElementById(fieldName);
        const formGroup = field.closest('.form-group');
        
        // 既存のエラーメッセージを削除
        const existingError = formGroup.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        // エラークラスを追加
        field.classList.add('is-invalid');

        // エラーメッセージを追加
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error invalid-feedback';
        errorDiv.textContent = message;
        formGroup.appendChild(errorDiv);
    }

    // フィールドエラーのクリア
    function clearFieldError(fieldName) {
        const field = document.getElementById(fieldName);
        const formGroup = field.closest('.form-group');
        
        field.classList.remove('is-invalid');
        
        const existingError = formGroup.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }

    // ローディング状態の表示
    function showLoadingState() {
        loginBtn.disabled = true;
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
    }

    // 入力フィールドのリアルタイムバリデーション
    const usernameField = document.getElementById('username');
    const passwordField = document.getElementById('password');

    usernameField.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value !== '') {
            clearFieldError('username');
        }
    });

    passwordField.addEventListener('blur', function() {
        const value = this.value;
        if (value !== '' && value.length >= 6) {
            clearFieldError('password');
        }
    });

    // Enterキーでのフォーム送信
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !loginBtn.disabled) {
            loginForm.dispatchEvent(new Event('submit'));
        }
    });

    // パスワード強度の表示（オプション）
    passwordField.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        
        // パスワード強度インジケーターがあれば更新
        const strengthIndicator = document.querySelector('.password-strength');
        if (strengthIndicator) {
            updatePasswordStrength(strengthIndicator, strength);
        }
    });

    // パスワード強度の計算
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        return Math.min(strength, 5);
    }

    // パスワード強度の表示更新
    function updatePasswordStrength(indicator, strength) {
        const strengthTexts = ['非常に弱い', '弱い', '普通', '強い', '非常に強い'];
        const strengthColors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#198754'];
        
        indicator.textContent = strengthTexts[strength - 1] || '';
        indicator.style.color = strengthColors[strength - 1] || '#6c757d';
    }
});
