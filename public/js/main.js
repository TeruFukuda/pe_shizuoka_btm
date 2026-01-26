/**
 * main.js - 全画面共通のUI制御
 */
document.addEventListener('DOMContentLoaded', function() {
  // 1. サイドバーの開閉制御（モバイル用）
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  if (mobileMenuBtn && sidebar) {
      mobileMenuBtn.addEventListener('click', function() {
          sidebar.classList.toggle('active');
          if (overlay) overlay.classList.toggle('active');
      });
  }

  // 2. オーバーレイクリックでサイドバーを閉じる
  if (overlay) {
      overlay.addEventListener('click', function() {
          sidebar.classList.remove('active');
          overlay.classList.remove('active');
      });
  }

  // 3. フラッシュメッセージ（通知）を数秒後に自動で消す
  const alerts = document.querySelectorAll('.alert-dismissible');
  alerts.forEach(alert => {
      setTimeout(() => {
          if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
              const bsAlert = new bootstrap.Alert(alert);
              bsAlert.close();
          } else {
              alert.style.display = 'none';
          }
      }, 5000); // 5秒後に消去
  });
});