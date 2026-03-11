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

// 問題一覧表示関数
function loadProblemList() {
  loadProblemListPage(1);
}

// 問題一覧ページ表示関数
function loadProblemListPage(page = 1, search = '', genreId = '') {
  console.log('loadProblemListPage関数が呼び出されました:', { page, search, genreId });

  const mainContent = document.getElementById('mainContent');
  console.log('mainContent要素:', mainContent);

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
          <p class="mt-3">問題一覧を読み込み中...</p>
      </div>
  `;

  console.log('問題一覧の読み込み開始 (ページ:', page, ', 検索:', search, ', ジャンル:', genreId, ')');

  // クエリパラメータを構築
  const params = new URLSearchParams();
  if (page > 1) params.append('page', page);
  if (search) params.append('search', search);
  if (genreId) params.append('genre_id', genreId);

  const queryString = params.toString();
  const url = `/questions${queryString ? '?' + queryString : ''}`;

  console.log('リクエストURL:', url);

  // AJAXで問題一覧を取得
  fetch(url)
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
          const problemContent = doc.querySelector('.questions-container');

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

  // 既存のイベントリスナーを削除（重複を防ぐ）
  if (searchInput) {
      searchInput.removeEventListener('input', searchInput._debouncedHandler);
  }
  if (genreFilter) {
      genreFilter.removeEventListener('change', genreFilter._changeHandler);
  }

  // サーバーサイドフィルタリングを使用するため、クライアントサイドフィルタリング関数は削除

  // サーバーサイドフィルタリングを使用するため、クライアントサイドフィルタリングは無効化
  console.log('サーバーサイドフィルタリングを使用します');

  // ページネーションリンクのイベントリスナーを設定
  const paginationLinks = document.querySelectorAll('.pagination .page-link[href]');
  console.log('ページネーションリンク数:', paginationLinks.length);

  paginationLinks.forEach((link, index) => {
      link.addEventListener('click', function(e) {
          e.preventDefault();
          const href = this.getAttribute('href');
          console.log(`ページネーションリンク${index + 1}クリック:`, href);

          // URLからページ番号を抽出
          const urlParams = new URLSearchParams(href.split('?')[1] || '');
          const page = parseInt(urlParams.get('page')) || 1;

          // フィルター条件を取得
          const searchInput = document.getElementById('searchInput');
          const genreFilter = document.getElementById('genreFilter');
          const search = searchInput ? searchInput.value : '';
          const genreId = genreFilter ? genreFilter.value : '';

          console.log('フィルター条件:', { page, search, genreId });
          loadProblemListPage(page, search, genreId);
      });
  });

  // フィルターフォームの送信処理を無効化
  const filterForm = document.getElementById('filterForm');
  if (filterForm) {
      console.log('フィルターフォームの送信を無効化');
      filterForm.addEventListener('submit', function(e) {
          e.preventDefault();
          console.log('フィルターフォーム送信を防止');
          return false;
      });
  }

  // クライアントサイドフィルタリング関数
  function filterProblems() {
      const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
      const selectedGenreId = genreFilter ? genreFilter.value : '';

      console.log('クライアントサイドフィルタリング実行:', { searchTerm, selectedGenreId });

      // テーブル行を取得
      const tableRows = document.querySelectorAll('#questionsTableBody tr');
      let visibleCount = 0;

      console.log('見つかったテーブル行数:', tableRows.length);

      tableRows.forEach((row, index) => {
          const questionText = row.cells[1].textContent.toLowerCase(); // 問題列（2番目の列）
          const genreId = row.dataset.genreId;

          const matchesSearch = searchTerm === '' || questionText.includes(searchTerm);
          const matchesGenre = selectedGenreId === '' || genreId === selectedGenreId;

          if (matchesSearch && matchesGenre) {
              row.style.display = '';
              visibleCount++;
          } else {
              row.style.display = 'none';
          }
      });

      console.log('表示される行数:', visibleCount);

      // 空の状態表示の切り替え
      if (emptyState) {
          if (visibleCount === 0) {
              emptyState.style.display = 'block';
          } else {
              emptyState.style.display = 'none';
          }
      }
  }

  // ジャンルフィルターのリアルタイム処理
  if (genreFilter) {
      console.log('ジャンルフィルターのリアルタイム処理を追加');
      const changeHandler = function(e) {
          console.log('ジャンルフィルター変更:', e.target.value);
          filterProblems();
      };

      // 既存のイベントリスナーを削除
      genreFilter.removeEventListener('change', genreFilter._changeHandler);
      genreFilter._changeHandler = changeHandler;
      genreFilter.addEventListener('change', changeHandler);
  } else {
      console.error('ジャンルフィルターが見つかりません');
  }

  // 検索ボックスのリアルタイム処理
  if (searchInput) {
      console.log('検索ボックスのリアルタイム処理を追加');
      let searchTimeout;
      const inputHandler = function(e) {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(() => {
              console.log('検索ボックス入力:', e.target.value);
              filterProblems();
          }, 300); // 300ms後に実行（デバウンス）
      };

      // 既存のイベントリスナーを削除
      searchInput.removeEventListener('input', searchInput._debouncedHandler);
      searchInput._debouncedHandler = inputHandler;
      searchInput.addEventListener('input', inputHandler);
  } else {
      console.error('検索ボックスが見つかりません');
  }

  // 初期化時にフィルタリングを実行
  console.log('初期化時にクライアントサイドフィルタリングを実行');
  filterProblems();

  // クライアントサイドフィルタリングを使用
  console.log('クライアントサイドフィルタリングを使用します');
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
  fetch('/questions/create')
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
  // 強制的に黒い幕を消す（SPAでの安全策）
  const backdrop = document.querySelector('.modal-backdrop');
  if (backdrop) {
      backdrop.remove();
  }
  // bodyのスクロールロックを解除
  document.body.classList.remove('modal-open');
  document.body.style.overflow = '';
  document.body.style.paddingRight = '';

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
  fetch('/questions/select')
      .then(response => response.text())
      .then(html => {
          // レスポンスから問題選択部分のみを抽出
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const selectionContent = doc.querySelector('.problem-selection-container');

          // ブラウザのタブ名（<title>）を更新
          const newTitle = doc.querySelector('title').innerText;
          document.title = newTitle;

          // 画面上の大見出し（@yield('title') の部分）を更新
          // レイアウト側の <h1> の中身を書き換えます
          const headerTitle = doc.querySelector('.dashboard-header h1');
          if (headerTitle) {
              // 現在の画面の h1 を、取得したHTMLの h1 の内容で書き換え
              document.querySelector('.dashboard-header h1').innerText = headerTitle.innerText;
          }

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

function showDashboard() {
  const mainContent = document.getElementById('mainContent');

  // ローディング表示
  mainContent.innerHTML = `
      <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">読み込み中...</span>
          </div>
          <p class="mt-3">ダッシュボードを読み込み中...</p>
      </div>
  `;

  // AJAXでジャンル新規登録画面を取得
  fetch('/dashboard')
      .then(response => {
          if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.text();
      })
      .then(html => {
          // レスポンスからダッシュボード部分のみを抽出
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const createContent = doc.querySelector('.pagination-container');

          // ブラウザのタブ名（<title>）を更新
          const newTitle = doc.querySelector('title').innerText;
          document.title = newTitle;

          // 画面上の大見出し（@yield('title') の部分）を更新
          // レイアウト側の <h1> の中身を書き換えます
          const headerTitle = doc.querySelector('.dashboard-header h1');
          if (headerTitle) {
              // 現在の画面の h1 を、取得したHTMLの h1 の内容で書き換え
              document.querySelector('.dashboard-header h1').innerText = headerTitle.innerText;
          }

          if (createContent) {
              mainContent.innerHTML = createContent.outerHTML;
              initRadarChart();
              initPagination();
          } else {
              mainContent.innerHTML = `
                  <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle me-2"></i>
                      ダッシュボードの読み込みに失敗しました。
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
  fetch(`/questions?page=${page}`)
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
          const problemContent = doc.querySelector('.questions-container');

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

// 問題編集画面を読み込む関数
function loadQuestionEdit(questionId) {
  console.log('問題編集画面の読み込み開始 (問題ID:', questionId, ')');
  const mainContent = document.getElementById('mainContent');

  mainContent.innerHTML = `
      <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">読み込み中...</span>
          </div>
          <p class="mt-3">問題編集画面を読み込み中...</p>
      </div>
  `;

  fetch(`/questions/${questionId}/edit`)
      .then(response => {
          console.log('編集画面レスポンス受信:', response.status, response.statusText);
          if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.text();
      })
      .then(html => {
          console.log('編集画面HTMLレスポンス受信:', html.length, '文字');
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const editContent = doc.querySelector('.questions-edit-container');

          if (editContent) {
              console.log('問題編集画面を表示');
              mainContent.innerHTML = editContent.outerHTML;

              // 編集画面のJavaScriptを実行
              const scriptTags = doc.querySelectorAll('script');
              scriptTags.forEach(script => {
                  if (script.textContent.trim()) {
                      try {
                          eval(script.textContent);
                      } catch (e) {
                          console.error('編集画面JavaScript実行エラー:', e);
                      }
                  }
              });
          } else {
              console.error('編集コンテンツが見つかりません');
              mainContent.innerHTML = `
                  <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle me-2"></i>
                      問題編集画面の読み込みに失敗しました。
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

// グローバル関数として定義（問題一覧から呼び出し可能）
window.loadQuestionEdit = loadQuestionEdit;

// レーダーチャートの初期化
function initRadarChart() {
  const ctx = document.getElementById('radarChart');
  if (!ctx) return;

  // ジャンルデータを取得
  const genreElements = document.querySelectorAll('[data-genre-id]');
  const labels = [];
  const data = [];

  genreElements.forEach(element => {
      const genreName = element.previousElementSibling.textContent.trim();
      const genreValue = parseInt(element.textContent.replace('%', ''));
      labels.push(genreName);
      data.push(genreValue);
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
              borderWidth: 2,
              pointBackgroundColor: 'rgba(102, 126, 234, 1)',
              pointBorderColor: '#fff',
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: 'rgba(102, 126, 234, 1)'
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
              legend: {
                  display: false
              }
          },
          scales: {
              r: {
                  beginAtZero: true,
                  max: 100,
                  min: 0,
                  ticks: {
                      stepSize: 20,
                      callback: function(value) {
                          return value + '%';
                      }
                  },
                  grid: {
                      color: 'rgba(0, 0, 0, 0.1)'
                  },
                  angleLines: {
                      color: 'rgba(0, 0, 0, 0.1)'
                  }
              }
          }
      }
  });
}

// ページング機能の実装
let currentPage = 1;
const totalPages = 2;

function initPagination() {
  const prevBtn = document.getElementById('prevPage');
  const nextBtn = document.getElementById('nextPage');
  const dots = document.querySelectorAll('.pagination-dot');
  const container = document.getElementById('dashboardPagination');

  // 前のページボタン
  if (prevBtn) {
      prevBtn.addEventListener('click', function() {
          if (currentPage > 1) {
              goToPage(currentPage - 1);
          }
      });
  }

  // 次のページボタン
  if (nextBtn) {
      nextBtn.addEventListener('click', function() {
          if (currentPage < totalPages) {
              goToPage(currentPage + 1);
          }
      });
  }

  // ドットクリック
  dots.forEach((dot, index) => {
      dot.addEventListener('click', function() {
          goToPage(index + 1);
      });
  });

  // マウスカーソルによるページ切り替え
  if (container) {
      container.addEventListener('mousemove', function(e) {
          const rect = container.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const width = rect.width;

          // 右端20%の範囲で次のページボタンを表示
          if (x > width * 0.8 && currentPage < totalPages) {
              nextBtn.style.opacity = '1';
              nextBtn.style.transform = 'translateY(-50%) scale(1.1)';
          } else {
              nextBtn.style.opacity = '0';
              nextBtn.style.transform = 'translateY(-50%) scale(1)';
          }

          // 左端20%の範囲で前のページボタンを表示
          if (x < width * 0.2 && currentPage > 1) {
              prevBtn.style.opacity = '1';
              prevBtn.style.transform = 'translateY(-50%) scale(1.1)';
          } else {
              prevBtn.style.opacity = '0';
              prevBtn.style.transform = 'translateY(-50%) scale(1)';
          }
      });

      // マウスが離れたらボタンを隠す
      container.addEventListener('mouseleave', function() {
          prevBtn.style.opacity = '0';
          nextBtn.style.opacity = '0';
          prevBtn.style.transform = 'translateY(-50%) scale(1)';
          nextBtn.style.transform = 'translateY(-50%) scale(1)';
      });
  }
}

function goToPage(page) {
  if (page < 1 || page > totalPages) return;

  const currentPageElement = document.getElementById(`page${currentPage}`);
  const targetPageElement = document.getElementById(`page${page}`);
  const currentDot = document.querySelector(`.pagination-dot[data-page="${currentPage}"]`);
  const targetDot = document.querySelector(`.pagination-dot[data-page="${page}"]`);

  if (currentPageElement && targetPageElement) {
      // 現在のページを非アクティブに
      currentPageElement.classList.remove('active');
      if (currentDot) currentDot.classList.remove('active');

      // アニメーション効果
      if (page > currentPage) {
          currentPageElement.classList.add('slide-left');
          targetPageElement.classList.add('slide-right');
      } else {
          currentPageElement.classList.add('slide-right');
          targetPageElement.classList.add('slide-left');
      }

      // 少し遅延してからページを切り替え
      setTimeout(() => {
          currentPageElement.classList.remove('slide-left', 'slide-right');
          targetPageElement.classList.remove('slide-left', 'slide-right');
          targetPageElement.classList.add('active');
          if (targetDot) targetDot.classList.add('active');

          currentPage = page;

          // ページ2の場合はジャンルグラフを初期化
          if (page === 2) {
              setTimeout(() => {
                  initGenreCharts();
              }, 100);
          }

      }, 250);
  }
}

// ジャンルごとの出題状況グラフの初期化
function initGenreCharts() {
  // サーバーから送信されたデータを使用
  const userGenreStats = [];
  const totalGenreStats = [];
  const genres = [];

  console.log('ジャンルグラフ初期化:', { userGenreStats, totalGenreStats, genres });

  const genreNames = [];
  const userData = [];
  const totalData = [];

  // ジャンルごとにデータを整理
  genres.forEach(genre => {
      genreNames.push(genre.name);
      userData.push(userGenreStats[genre.id]?.count || 0);
      totalData.push(totalGenreStats[genre.id]?.count || 0);
  });

  // ユーザーの出題状況グラフ
  const userCtx = document.getElementById('userGenreChart');
  if (userCtx) {
      console.log('ユーザーグラフ初期化中...', { genreNames, userData });
      new Chart(userCtx, {
          type: 'bar',
          data: {
              labels: genreNames,
              datasets: [{
                  label: '出題数',
                  data: userData,
                  backgroundColor: 'rgba(102, 126, 234, 0.8)',
                  borderColor: 'rgba(102, 126, 234, 1)',
                  borderWidth: 1
              }]
          },
          options: {
              indexAxis: 'y',
              responsive: true,
              maintainAspectRatio: false,
              aspectRatio: 1.5,
              plugins: {
                  legend: {
                      display: false
                  }
              },
              scales: {
                  x: {
                      beginAtZero: true,
                      grid: {
                          color: 'rgba(0, 0, 0, 0.1)'
                      }
                  },
                  y: {
                      grid: {
                          display: false
                      }
                  }
              }
          }
      });
  }

  // 全体の出題状況グラフ
  const totalCtx = document.getElementById('totalGenreChart');
  if (totalCtx) {
      console.log('全体グラフ初期化中...', { genreNames, totalData });
      new Chart(totalCtx, {
          type: 'bar',
          data: {
              labels: genreNames,
              datasets: [{
                  label: '出題数',
                  data: totalData,
                  backgroundColor: 'rgba(40, 167, 69, 0.8)',
                  borderColor: 'rgba(40, 167, 69, 1)',
                  borderWidth: 1
              }]
          },
          options: {
              indexAxis: 'y',
              responsive: true,
              maintainAspectRatio: false,
              aspectRatio: 1.5,
              plugins: {
                  legend: {
                      display: false
                  }
              },
              scales: {
                  x: {
                      beginAtZero: true,
                      grid: {
                          color: 'rgba(0, 0, 0, 0.1)'
                      }
                  },
                  y: {
                      grid: {
                          display: false
                      }
                  }
              }
          }
      });
  }
}

// ジャンル選択したとき、そのジャンルの未解答問題一覧を描画
document.addEventListener('click', async (event) => {
  // クリックされた要素、またはその親に .genre-item があるか探す
  const button = event.target.closest('.genre-item');

  // ジャンルボタン以外がクリックされたら何もしない
  if (!button) return;

  const genreId = button.dataset.genreId;
  const questionsContainer = document.getElementById('questionsContainer');

  if (!questionsContainer) return;

  // アクティブ表示の切り替え
  document.querySelectorAll('.genre-item').forEach(btn => btn.classList.remove('active'));
  button.classList.add('active');

  // ローディング表示
  questionsContainer.innerHTML = '<div class="text-center p-5"><div class="spinner-border"></div></div>';

  try {
      const response = await fetch(`/api/genres/${genreId}/questions`);
      const questions = await response.json();

      // 描画処理
      renderQuestionsList(questions, questionsContainer);
  } catch (error) {
      console.error('Error:', error);
  }
});

function renderQuestionsList(questions, container) {
  if (questions.length === 0) {
      container.innerHTML = '<p class="p-5 text-center">問題がありません</p>';
      return;
  }
  const html = questions.map(q => `
      <a href="javascript:void(0)"
         onclick="loadAnswerPage(${q.id})"
         class="list-group-item list-group-item-action border-0 border-bottom p-3">
          <div class="d-flex align-items-center">
              <h6 class="mb-0 fw-bold text-primary text-decoration-underline">
                  ${q.question}
              </h6>
          </div>
      </a>
  `).join('');

  container.innerHTML = `<div class="list-group list-group-flush">${html}</div>`;
}

// 問題解答画面への遷移
window.loadAnswerPage = function(questionId) {
  const mainContent = document.getElementById('mainContent');

  mainContent.innerHTML = `
      <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-3">問題を読み込み中...</p>
      </div>
  `;

  // 解答画面のHTMLを取得
  fetch(`/questions/${questionId}/answer`)
      .then(response => response.text())
      .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          // 解答画面のコンテナ（仮に .answer-container とする）を抽出
          const answerContent = doc.querySelector('.answer-container');

          if (answerContent) {
              mainContent.innerHTML = answerContent.outerHTML;
              // 解答画面専用の初期化関数があればここで呼ぶ
          }
      })
      .catch(error => {
          console.error('Error:', error);
      });
};

// 解答結果モーダルの表示
document.addEventListener('click', function(e) {

  // 1. クリックされた要素が「回答する」ボタン（またはその中のアイコン）か判定
  const submitBtn = e.target.closest('#submitBtn');

  // ボタン以外がクリックされたら何もしない
  if (!submitBtn) return;

  // 2. 本来のフォーム送信（ページ遷移）を止める
  e.preventDefault();

  // 3. フォーム要素を取得
  const answerForm = document.getElementById('answerForm');
  if (!answerForm) return;

  // 4. 選択されたラジオボタンを取得
  const selectedRadio = answerForm.querySelector('input[name="choice_id"]:checked');

  if (!selectedRadio) {
      alert("選択肢を選んでください！");
      return;
  }

  // 5. テキストの取得
  const userText = selectedRadio.closest('.p-2').querySelector('.choice-text').innerText;
  const correctRadio = answerForm.querySelector('input[data-is-correct="1"]');
  const correctText = correctRadio.closest('.p-2').querySelector('.choice-text').innerText;

  // 6. 正誤判定
  const isCorrect = selectedRadio.getAttribute('data-is-correct') === "1";

  // 7. モーダルへの反映
  const modalElem = document.getElementById('resultModal');
  const resultModal = bootstrap.Modal.getOrCreateInstance(modalElem);

  document.getElementById('resultMessage').innerText = isCorrect ? "素晴らしい！　正解です。" : "残念！　不正解です。";
  document.getElementById('userAnswerText').innerText = userText;
  document.getElementById('correctAnswerText').innerText = correctText;

  // アイコンの切り替え
  const icon = document.getElementById('resultIcon');
  icon.innerHTML = isCorrect
      ? '<i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>'
      : '<i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>';

  // 8. 表示！
  resultModal.show();
});

// ページ読み込み時にレーダーチャートとページング機能を初期化
document.addEventListener('DOMContentLoaded', function() {
  initRadarChart();
  initPagination();
  // ジャンルグラフも初期化
  setTimeout(() => {
      initGenreCharts();
  }, 200);
});