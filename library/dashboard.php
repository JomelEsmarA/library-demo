<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Library Dashboard</title>
  <style>
    :root{
      --bg:#071126;--card:#0f1724;--muted:#94a3b8;--accent:#38a5f0;--danger:#ef4444;--success:#10b981;
      font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial;
    }
    *{box-sizing:border-box}
    body{margin:0;min-height:100vh;background:linear-gradient(180deg,#071126,#0b243a);color:#e6eef8;padding:18px}
    header{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}
    .brand{font-weight:700;color:#e6f6ff}
    .userline{font-size:13px;color:var(--muted)}
    nav.menu{display:flex;gap:8px;flex-wrap:wrap}
    .menu button{background:transparent;border:1px solid rgba(255,255,255,0.04);padding:8px 12px;border-radius:10px;color:var(--muted);cursor:pointer}
    .menu button.active{background:linear-gradient(90deg,var(--accent),#60a5fa);color:#02203a;border-color:rgba(56,189,248,0.15)}
    .top-actions{display:flex;gap:8px;align-items:center}
    .btn{padding:8px 12px;border-radius:8px;border:0;cursor:pointer;font-weight:600}
    .btn-ghost{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted)}
    .btn-primary{background:linear-gradient(90deg,var(--accent),#60a5fa);color:#02203a}
    .btn-danger{background:var(--danger);color:white}
    .card{background:var(--card);padding:14px;border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,0.6)}
    main.layout{display:grid;grid-template-columns:320px 1fr;gap:16px}
    .pane{min-height:220px}
    label{font-size:13px;color:var(--muted);display:block;margin-top:8px}
    input[type="text"],input[type="number"],input[type="date"],select,textarea{width:100%;padding:8px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:inherit}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:10px;border-bottom:1px solid rgba(255,255,255,0.04);text-align:left;font-size:14px;vertical-align:middle}
    th{color:var(--muted);font-weight:600;font-size:13px}
    .muted{color:var(--muted)}
    .small{font-size:13px}
    .empty{color:var(--muted);padding:12px}
    footer{margin-top:18px;color:var(--muted);font-size:13px}
    .stat{display:inline-block;background:rgba(255,255,255,0.03);padding:8px;border-radius:8px;margin-right:8px}
    .actions{display:flex;gap:8px}
    .typeahead-wrap{position:relative}
    .typeahead-list{position:absolute;left:0;right:0;max-height:220px;overflow:auto;background:var(--card);border:1px solid rgba(255,255,255,0.04);z-index:30;border-radius:8px;margin-top:6px;padding:6px}
    .typeahead-item{padding:8px;border-radius:6px;cursor:pointer}
    .typeahead-item:hover{background:rgba(255,255,255,0.02)}
    .checkbox-list{display:flex;flex-direction:column;gap:8px;margin-top:8px}
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    @media (max-width:880px){ main.layout{grid-template-columns:1fr} nav.menu{overflow:auto} table{font-size:13px} .grid-2{grid-template-columns:1fr} }
  </style>
</head>
<body>
  <header>
    <div>
      <div class="brand">Library Management — Staff</div>
      <div id="userInfo" class="userline">Loading user info...</div>
    </div>

    <div style="display:flex;gap:10px;align-items:center">
      <nav class="menu" role="navigation" aria-label="Dashboard actions">
        <button data-panel="add" class="active">Add Book</button>
        <button data-panel="borrow">Borrow Book</button>
        <button data-panel="search">Search Book</button>
        <button data-panel="borrowers">Borrower Records</button>
        <button data-panel="returns">Returns</button>
        <button data-panel="analytics">Analytics</button>
        <button data-panel="due">Due Dates</button>
      </nav>

      <div class="top-actions">
        <button id="btnExport" class="btn btn-ghost" title="Export books and loans">Export JSON</button>
        <a id="logoutLink" class="btn btn-ghost" href="#" onclick="logoutKeepData(event)">Log out</a>
        <button id="btnClearAll" class="btn btn-danger" title="Clear ALL demo data">Clear all</button>
      </div>
    </div>
  </header>

  <main class="layout" role="main" aria-live="polite">
    <aside class="card pane" id="leftPane" aria-label="Quick actions">
      <h4 style="margin:0 0 8px 0">Quick</h4>

      <div class="small">Quick add</div>
      <form id="quickAdd" style="margin-top:8px">
        <label>Title</label><input id="qa-title" type="text" required />
        <label>Author</label><input id="qa-author" type="text" required />
        <label>Copies</label><input id="qa-copies" type="number" min="1" value="1" />
        <div style="margin-top:8px;display:flex;gap:8px">
          <button class="btn btn-primary" type="submit">Add</button>
          <button id="btnShowAdd" type="button" class="btn btn-ghost">Open Add</button>
        </div>
      </form>

      <hr style="margin:12px 0;border:none;border-top:1px solid rgba(255,255,255,0.03)">

      <div class="small">Search quick</div>
      <input id="qa-search" type="text" placeholder="Search title or author" />
      <div id="qa-search-results" style="margin-top:10px"></div>

      <hr style="margin:12px 0;border:none;border-top:1px solid rgba(255,255,255,0.03)">

      <div class="small">Stats</div>
      <div id="leftStats" style="margin-top:8px"></div>
    </aside>

    <section class="card pane" id="mainPane" aria-live="polite">
      <div data-panel="add" class="panel" style="display:block">
        <h3 style="margin:0 0 8px 0">Add / Edit Book</h3>
        <form id="bookForm">
          <input type="hidden" id="book-id" />
          <label>Title</label><input id="book-title" type="text" required />
          <label>Author</label><input id="book-author" type="text" required />
          <label>ISBN</label><input id="book-isbn" type="text" />
          <label>Copies</label><input id="book-copies" type="number" min="1" value="1" required />
          <label>Category</label><input id="book-category" type="text" placeholder="Optional" />
          <div style="margin-top:10px;display:flex;gap:8px">
            <button class="btn btn-primary" id="saveBookBtn" type="submit">Save</button>
            <button class="btn btn-ghost" id="resetBookBtn" type="button">Reset</button>
          </div>
        </form>

        <div id="booksList" style="margin-top:12px"></div>
      </div>

      <div data-panel="borrow" class="panel" style="display:none">
        <h3 style="margin:0 0 8px 0">Staff Borrowing</h3>
        <form id="staffBorrowForm" aria-label="Borrow book form">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div>
              <label>Borrower name</label>
              <input id="br-name" type="text" placeholder="Full name" required />
            </div>
            <div>
              <label>Student number</label>
              <input id="br-student" type="text" placeholder="Student number" required />
            </div>
            <div style="grid-column:1 / -1">
              <label>Address (include email if available)</label>
              <input id="br-address" type="text" placeholder="Address / contact details (you can include email)" required />
            </div>

            <div style="grid-column:1 / -1">
              <label>Book title (type to search)</label>
              <div class="typeahead-wrap">
                <input id="br-book-input" type="text" placeholder="Start typing book title" aria-autocomplete="list" autocomplete="off" required />
                <div id="br-suggestions" class="typeahead-list" style="display:none" role="listbox"></div>
              </div>
              <input id="br-book-id" type="hidden" />
            </div>

            <div>
              <label>Due date</label>
              <input id="br-due" type="date" required />
            </div>
            <div>
              <label>Copies to borrow</label>
              <input id="br-copies" type="number" min="1" value="1" required />
            </div>
          </div>

          <div style="margin-top:10px;display:flex;gap:8px">
            <button class="btn btn-primary" id="staffBorrowBtn" type="submit">Create loan</button>
            <button class="btn btn-ghost" id="staffResetBtn" type="button">Reset</button>
          </div>
        </form>

        <div style="margin-top:14px" id="staffLoansActive"></div>
      </div>

      <div data-panel="search" class="panel" style="display:none">
        <h3 style="margin:0 0 8px 0">Search Books</h3>
        <input id="searchInput" type="text" placeholder="Search by title, author, ISBN, category" />
        <div id="searchResults" style="margin-top:12px"></div>
      </div>

      <div data-panel="borrowers" class="panel" style="display:none">
        <h3 style="margin:0 0 8px 0">Borrower Records</h3>
        <div style="overflow:auto">
          <table id="borrowersTable" role="table" aria-label="Borrower records table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Student number</th>
                <th>Address</th>
                <th>Email</th>
                <th>Active loans</th>
                <th>Total loans</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="borrowersTableBody">
              <tr><td colspan="7" class="empty">No borrower records</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div data-panel="returns" class="panel" style="display:none">
        <h3 style="margin:0 0 8px 0">Return Books</h3>

        <div style="display:flex;gap:8px;align-items:end">
          <div style="flex:1">
            <label>Find borrower (student number or full name)</label>
            <input id="ret-search" type="text" placeholder="Enter student number or name" />
          </div>
          <div>
            <button id="retSearchBtn" class="btn btn-primary" style="margin-bottom:4px">Search</button>
          </div>
          <div>
            <button id="retClearBtn" class="btn btn-ghost" style="margin-bottom:4px">Clear</button>
          </div>
        </div>

        <div id="retResults" style="margin-top:14px">
          <div class="empty">Search for a borrower to view their active loans</div>
        </div>

        <div style="margin-top:12px;display:flex;gap:8px">
          <button id="retReturnSelected" class="btn btn-primary">Return selected</button>
          <button id="retReturnAll" class="btn btn-ghost">Return all active</button>
        </div>
      </div>

      <div data-panel="analytics" class="panel" style="display:none">
        <h3 style="margin:0 0 8px 0">Analytics</h3>

        <div id="analyticsStats" style="margin-bottom:12px"></div>

        <div class="grid-2">
          <div class="card" id="analyticsBorrowers">
            <h4 style="margin:0 0 8px 0">Top borrowers (lifetime)</h4>
            <div id="topBorrowers"></div>
          </div>

          <div class="card" id="analyticsBooks">
            <h4 style="margin:0 0 8px 0">Most-borrowed books (lifetime)</h4>
            <div id="topBooks"></div>
          </div>
        </div>

      </div>

      <div data-panel="due" class="panel" style="display:none">
        <h3 style="margin:0 0 8px 0">Due dates</h3>
        <div class="small">Active loans with due date and email borrower action (opens Gmail compose).</div>
        <div id="dueList" style="margin-top:12px"></div>
      </div>
    </section>
  </main>

  <footer>
    <div class="small">Data stored locally for demo. Log out removes session only and preserves books/loans; Clear all still erases demo data.</div>
  </footer>

  <script>
    // Storage keys
    const USERS_KEY = 'demo_accounts_v1';
    const SESSION_KEY = 'demo_session_v1';
    const BOOKS_KEY = 'demo_books_v1';
    const LOANS_KEY = 'demo_loans_v1';

    // Lifetime counters
    const BOOK_COUNTS_KEY = 'demo_book_borrow_counts_v1';
    const BORROWER_COUNTS_KEY = 'demo_borrower_borrow_counts_v1';

    const XE = (s, r=document) => r.querySelector(s);
    const qAll = (s, r=document) => Array.from((r||document).querySelectorAll(s));
    const uid = (p='id') => p + '_' + Math.random().toString(36).slice(2,9);

    function loadJSON(k){ try{ const raw=localStorage.getItem(k); return raw ? JSON.parse(raw) : null }catch(e){return null} }
    function saveJSON(k,v){ localStorage.setItem(k, JSON.stringify(v)); }
    function loadBooks(){ return loadJSON(BOOKS_KEY) || {}; }
    function loadLoans(){ return loadJSON(LOANS_KEY) || []; }
    function saveBooks(b){ saveJSON(BOOKS_KEY,b); }
    function saveLoans(a){ saveJSON(LOANS_KEY,a); }

    // counts helpers
    function loadCounts(key){ try{ return JSON.parse(localStorage.getItem(key) || '{}'); }catch(e){ return {}; } }
    function saveCounts(key, obj){ try{ localStorage.setItem(key, JSON.stringify(obj || {})); }catch(e){} }
    function incrementBookCount(bookId, n=1){ if(!bookId) return; const c = loadCounts(BOOK_COUNTS_KEY); c[bookId] = (c[bookId] || 0) + n; saveCounts(BOOK_COUNTS_KEY, c); }
    function incrementBorrowerCount(borrowerKey, n=1){ if(!borrowerKey) return; const c = loadCounts(BORROWER_COUNTS_KEY); c[borrowerKey] = (c[borrowerKey] || 0) + n; saveCounts(BORROWER_COUNTS_KEY, c); }
    function borrowerKeyForLoan(l){ const studentKey = l.borrowerStudent && l.borrowerStudent.trim() ? l.borrowerStudent.trim() : ''; const keyBase = studentKey || ('no-student-'+(l.borrowerName||'').trim()); return keyBase || 'no-student-unknown'; }

    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[m])); }
    function fmtDate(ts){ if(!ts) return '-'; return new Date(ts).toLocaleString(); }
    function todayISO(){ const d=new Date(); d.setHours(0,0,0,0); return d.toISOString().split('T')[0]; }

    function requireSession(){ const e = sessionStorage.getItem(SESSION_KEY); if(!e) location.href='login.html'; return e; }

    function logoutKeepData(ev){
      if(ev && ev.preventDefault) ev.preventDefault();
      try { sessionStorage.removeItem(SESSION_KEY); } catch(e){}
      try { localStorage.setItem('demo_last_action','signed_out'); } catch(e){}
      location.href = 'login.html';
    }

    function gmailComposeUrl({ to = '', subject = '', body = '' } = {}) {
      const base = 'https://mail.google.com/mail/?view=cm&fs=1';
      const params = new URLSearchParams();
      if (to) params.set('to', to);
      if (subject) params.set('su', subject);
      if (body) params.set('body', body);
      return base + '&' + params.toString();
    }

    function extractEmail(text){
      if(!text) return '';
      const m = String(text).match(/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i);
      return m ? m[0] : '';
    }

    // Init
    document.addEventListener('DOMContentLoaded', init);

    function init(){
      const sessionEmail = sessionStorage.getItem(SESSION_KEY);
      if(!sessionEmail) { location.href = 'login.php'; return; }
      const users = loadJSON(USERS_KEY) || {};
      const me = users[sessionEmail] || { name: sessionEmail };
      XE('#userInfo').textContent = `${me.name} — ${sessionEmail}`;

      qAll('nav.menu button').forEach(btn=> btn.addEventListener('click', ()=> switchPanel(btn.getAttribute('data-panel'))));

      XE('#quickAdd').addEventListener('submit', e=> {
        e.preventDefault();
        const title = XE('#qa-title').value.trim();
        const author = XE('#qa-author').value.trim();
        const copies = Math.max(1, parseInt(XE('#qa-copies').value,10) || 1);
        if(!title || !author) return alert('Provide title and author');
        const books = loadBooks();
        const id = uid('book');
        books[id] = { id, title, author, isbn:'', totalCopies: copies, category:'', created: Date.now() };
        saveBooks(books);
        XE('#qa-title').value=''; XE('#qa-author').value=''; XE('#qa-copies').value=1;
        renderAll();
      });
      XE('#btnShowAdd').addEventListener('click', ()=> switchPanel('add'));
      XE('#qa-search').addEventListener('input', ()=> renderQuickSearch());

      XE('#bookForm').addEventListener('submit', handleSaveBook);
      XE('#resetBookBtn').addEventListener('click', resetBookForm);

      XE('#staffBorrowForm').addEventListener('submit', handleStaffBorrow);
      XE('#staffResetBtn').addEventListener('click', ()=> { XE('#staffBorrowForm').reset(); hideSuggestions(); });

      const input = XE('#br-book-input');
      input && input.addEventListener('input', onTypeTitle);
      input && input.addEventListener('keydown', onTypeKeyDown);
      document.addEventListener('click', (e)=> { if(!e.target.closest('.typeahead-wrap')) hideSuggestions(); });

      const searchInput = XE('#searchInput');
      searchInput && searchInput.addEventListener('input', renderSearch);

      XE('#retSearchBtn').addEventListener('click', handleReturnSearch);
      XE('#retClearBtn').addEventListener('click', ()=> { XE('#ret-search').value=''; XE('#retResults').innerHTML = '<div class="empty">Search for a borrower to view their active loans</div>'; });
      XE('#retReturnSelected').addEventListener('click', handleReturnSelected);
      XE('#retReturnAll').addEventListener('click', handleReturnAllForFound);

      XE('#btnExport').addEventListener('click', handleExport);
      XE('#btnClearAll').addEventListener('click', handleClearAll);

      renderAll();
    }

    function switchPanel(name){
      qAll('nav.menu button').forEach(b=> b.classList.toggle('active', b.getAttribute('data-panel')===name));
      qAll('#mainPane .panel').forEach(p=> p.style.display = p.getAttribute('data-panel') === name ? 'block' : 'none');
      if(name === 'borrow') XE('#br-due').value = todayISO();
      if(name === 'borrowers') renderBorrowersTable();
      if(name === 'returns') { XE('#retResults').innerHTML = '<div class="empty">Search for a borrower to view their active loans</div>'; XE('#ret-search').value=''; }
      if(name === 'analytics') renderAnalytics();
      if(name === 'search') renderSearch();
      renderAll();
    }

    function renderAll(){
      renderLeftStats();
      renderBooksList();
      populateBorrowBookSelect();
      renderBorrowActive();
      renderSearch();
      renderBorrowersTable();
      renderAnalytics();
      renderDueList();
    }

    // Books CRUD
    function handleSaveBook(e){
      e.preventDefault();
      const id = XE('#book-id').value || uid('book');
      const title = XE('#book-title').value.trim();
      const author = XE('#book-author').value.trim();
      const isbn = XE('#book-isbn').value.trim();
      const copies = Math.max(1, parseInt(XE('#book-copies').value,10) || 1);
      const category = XE('#book-category').value.trim();
      if(!title || !author) return alert('Title and author required');
      const books = loadBooks();
      const existing = books[id];
      if(existing){
        const borrowed = countBorrowedCopies(id);
        if(copies < borrowed) return alert(`Cannot set copies to ${copies} while ${borrowed} are borrowed`);
      }
      books[id] = { id, title, author, isbn, totalCopies: copies, category, created: Date.now() };
      saveBooks(books);
      resetBookForm();
      renderAll();
    }
    function resetBookForm(){
      XE('#book-id').value=''; XE('#book-title').value=''; XE('#book-author').value=''; XE('#book-isbn').value=''; XE('#book-copies').value=1; XE('#book-category').value=''; XE('#saveBookBtn').textContent='Save';
    }
    function renderBooksList(){
      const wrap = XE('#booksList');
      const books = Object.values(loadBooks()).sort((a,b)=>a.title.localeCompare(b.title));
      if(!books.length) return wrap.innerHTML = '<div class="empty">No books added yet</div>';
      wrap.innerHTML = books.map(b=>{
        const avail = availableCopies(b.id);
        return `<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.03)">
          <div><div style="font-weight:600">${escapeHtml(b.title)}</div><div class="small muted">${escapeHtml(b.author)} ${b.isbn? '· ISBN '+escapeHtml(b.isbn):''}</div><div class="small muted">Available ${avail}/${b.totalCopies}</div></div>
          <div class="actions"><button class="btn btn-ghost" data-act="edit" data-id="${b.id}">Edit</button><button class="btn btn-danger" data-act="del" data-id="${b.id}">Delete</button></div>
        </div>`;
      }).join('');
      qAll('#booksList [data-act]').forEach(btn=> btn.addEventListener('click', e=>{
        const id = btn.getAttribute('data-id'), act = btn.getAttribute('data-act');
        if(act==='edit') editBook(id); if(act==='del') deleteBook(id);
      }));
    }
    function editBook(id){
      const books = loadBooks(); const b = books[id]; if(!b) return;
      XE('#book-id').value = b.id; XE('#book-title').value = b.title; XE('#book-author').value = b.author; XE('#book-isbn').value = b.isbn||''; XE('#book-copies').value = b.totalCopies; XE('#book-category').value = b.category||''; XE('#saveBookBtn').textContent='Update';
      window.scrollTo({top:0,behavior:'smooth'});
    }
    function deleteBook(id){
      if(!confirm('Delete this book? Loan history entries remain but book removed.')) return;
      const books = loadBooks(); delete books[id]; saveBooks(books); renderAll();
    }

    // Typeahead for borrow
    function getBookMatches(q){
      if(!q) return [];
      const books = Object.values(loadBooks());
      q = q.toLowerCase();
      return books
        .filter(b => (b.title||'').toLowerCase().includes(q) || (b.author||'').toLowerCase().includes(q) )
        .sort((a,b)=>a.title.localeCompare(b.title))
        .slice(0,200);
    }
    function onTypeTitle(e){
      const v = e.target.value.trim();
      const list = getBookMatches(v);
      const box = XE('#br-suggestions');
      if(!list.length){ box.style.display='none'; box.innerHTML=''; XE('#br-book-id').value=''; return; }
      box.innerHTML = list.map(b=>`<div class="typeahead-item" data-id="${b.id}" role="option">${escapeHtml(b.title)} <span class="small muted"> — ${escapeHtml(b.author)}</span></div>`).join('');
      box.style.display = 'block';
      qAll('.typeahead-item').forEach(it=> it.addEventListener('click', ()=> selectSuggestion(it.getAttribute('data-id'))));
    }
    function hideSuggestions(){ const box = XE('#br-suggestions'); box.style.display='none'; box.innerHTML=''; }
    function selectSuggestion(bookId){
      const books = loadBooks();
      const b = books[bookId];
      if(!b) return;
      XE('#br-book-input').value = b.title;
      XE('#br-book-id').value = b.id;
      hideSuggestions();
    }
    function onTypeKeyDown(e){
      const items = qAll('.typeahead-item');
      if(!items.length) return;
      const focused = items.findIndex(it=> it.classList.contains('focused'));
      if(e.key === 'ArrowDown'){ e.preventDefault(); const next = Math.min(items.length-1, Math.max(0, focused+1)); updateFocus(items, focused, next); }
      if(e.key === 'ArrowUp'){ e.preventDefault(); const prev = Math.max(0, focused-1); updateFocus(items, focused, prev); }
      if(e.key === 'Enter'){ if(focused >= 0){ e.preventDefault(); items[focused].click(); } }
      if(e.key === 'Escape'){ hideSuggestions(); }
    }
    function updateFocus(items, prev, next){
      if(prev >=0) items[prev].classList.remove('focused');
      items[next].classList.add('focused');
      items[next].scrollIntoView({ block: 'nearest' });
    }

    // Borrow flow (now increments lifetime counters)
    function populateBorrowBookSelect(){
      const sel = XE('#br-book');
      if(!sel) return;
      const books = Object.values(loadBooks()).sort((a,b)=>a.title.localeCompare(b.title));
      sel.innerHTML = books.length ? books.map(b=>`<option value="${b.id}">${escapeHtml(b.title)} — ${escapeHtml(b.author)} (Avail ${availableCopies(b.id)}/${b.totalCopies})</option>`).join('') : '<option value="">No books available</option>';
    }

    function handleStaffBorrow(e){
      e.preventDefault();
      const name = XE('#br-name').value.trim();
      const student = XE('#br-student').value.trim();
      const address = XE('#br-address').value.trim();
      const copies = Math.max(1, parseInt(XE('#br-copies').value,10) || 1);
      const due = XE('#br-due').value;
      const selectedId = XE('#br-book-id').value || (XE('#br-book') ? XE('#br-book').value : '');
      if(!name || !student || !address || !selectedId || !due) return alert('Please fill all borrower and book fields and select a book');
      if(availableCopies(selectedId) < copies) return alert('Not enough available copies to borrow');
      const loans = loadLoans();
      for(let i=0;i<copies;i++){
        const newLoan = {
          id: uid('loan'),
          bookId: selectedId,
          borrowerName: name,
          borrowerStudent: student,
          borrowerAddress: address,
          borrowerEmail: extractEmail(address),
          borrowedAt: Date.now(),
          dueDate: new Date(due).getTime(),
          returnedAt: null,
          createdBy: sessionStorage.getItem(SESSION_KEY)
        };
        loans.unshift(newLoan);

        // increment lifetime counters persistently
        incrementBookCount(selectedId, 1);
        const bk = borrowerKeyForLoan(newLoan);
        incrementBorrowerCount(bk, 1);
      }
      saveLoans(loans);
      XE('#staffBorrowForm').reset();
      XE('#br-due').value = todayISO();
      XE('#br-book-id').value = '';
      hideSuggestions();
      renderAll();
      switchPanel('borrowers');
    }

    // Active loans display
    function renderBorrowActive(){
      const loans = loadLoans().filter(l=>!l.returnedAt);
      const wrap = XE('#staffLoansActive');
      if(!wrap) return;
      if(!loans.length) return wrap.innerHTML = '<div class="empty">No active loans</div>';
      const books = loadBooks();
      wrap.innerHTML = loans.map(l=>{
        const b = books[l.bookId]; const title = b ? b.title : '(removed)';
        return `<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.03)">
          <div>
            <div style="font-weight:600">${escapeHtml(title)}</div>
            <div class="small muted">Borrower: ${escapeHtml(l.borrowerName)} · Student: ${escapeHtml(l.borrowerStudent)} · Borrowed: ${fmtDate(l.borrowedAt)} · Due: ${new Date(l.dueDate).toLocaleDateString()}</div>
          </div>
          <div class="actions">
            <button class="btn btn-primary" data-act="return" data-id="${l.id}">Return</button>
            <button class="btn btn-ghost" data-act="email" data-id="${l.id}">Email</button>
          </div>
        </div>`;
      }).join('');
      qAll('#staffLoansActive [data-act]').forEach(btn=> btn.addEventListener('click', ()=>{
        const id = btn.getAttribute('data-id'); const act = btn.getAttribute('data-act');
        if(act === 'return') returnLoan(id);
        if(act === 'email') emailLoanByRecord(id);
      }));
    }
    function returnLoan(id){
      const loans = loadLoans(); const idx = loans.findIndex(x=>x.id===id); if(idx === -1) return alert('Loan not found');
      loans[idx].returnedAt = Date.now(); saveLoans(loans); renderAll();
    }

    // Borrower records table
    function encodeBorrowerKey(key){ return encodeURIComponent(key).replace(/%/g,'_'); }
    function decodeBorrowerKey(enc){ return decodeURIComponent(enc.replace(/_/g,'%')); }

    function buildBorrowerGroups(){
      const loans = loadLoans();
      const groups = {};
      loans.forEach(l=>{
        const studentKey = l.borrowerStudent && l.borrowerStudent.trim() ? l.borrowerStudent.trim() : '';
        const keyBase = studentKey || ('no-student-'+(l.borrowerName||'').trim());
        const key = keyBase || 'no-student-unknown';
        if(!groups[key]) groups[key] = { student: l.borrowerStudent || '', name: l.borrowerName || '', address: l.borrowerAddress || '', email: l.borrowerEmail || extractEmail(l.borrowerAddress), loans: [] };
        groups[key].loans.push(l);
      });
      return Object.keys(groups).map(k=> ({ key: k, ...groups[k] }))
        .sort((a,b)=> {
          const aLatest = Math.max(...a.loans.map(x=>x.borrowedAt || 0));
          const bLatest = Math.max(...b.loans.map(x=>x.borrowedAt || 0));
          return bLatest - aLatest;
        });
    }

    function renderBorrowersTable(){
      const tbody = XE('#borrowersTableBody');
      const groups = buildBorrowerGroups();
      if(!groups.length){ tbody.innerHTML = '<tr><td colspan="7" class="empty">No borrower records</td></tr>'; return; }
      tbody.innerHTML = groups.map(g=>{
        const active = g.loans.filter(x=>!x.returnedAt).length;
        const total = g.loans.length;
        const email = g.email || '';
        const key = encodeBorrowerKey(g.key);
        return `<tr id="borrower-row-${key}">
          <td>${escapeHtml(g.name || '—')}</td>
          <td>${escapeHtml(g.student || '—')}</td>
          <td>${escapeHtml(g.address || '—')}</td>
          <td>${escapeHtml(email || '—')}</td>
          <td>${active}</td>
          <td>${total}</td>
          <td class="actions">
            <button class="btn btn-ghost btn-sm" data-act="view" data-stud="${key}">View</button>
            <button class="btn btn-ghost btn-sm" data-act="email" data-stud="${key}">Email</button>
          </td>
        </tr>`;
      }).join('');
      qAll('#borrowersTableBody [data-act="view"]').forEach(b=> b.addEventListener('click', (e)=>{
        const k = decodeBorrowerKey(b.getAttribute('data-stud'));
        showLoansForBorrower(k);
      }));
      qAll('#borrowersTableBody [data-act="email"]').forEach(b=> b.addEventListener('click', (e)=>{
        const k = decodeBorrowerKey(b.getAttribute('data-stud'));
        emailLoansForBorrower(k);
      }));
    }

    function showLoansForBorrower(key){
      const loans = loadLoans().filter(l=>{
        const studentKey = l.borrowerStudent && l.borrowerStudent.trim() ? l.borrowerStudent.trim() : '';
        const gKey = studentKey || ('no-student-'+(l.borrowerName||'').trim());
        return (gKey || 'no-student-unknown') === key;
      });
      if(!loans.length) return alert('No loans found for this borrower');
      const books = loadBooks();
      const rows = loans.map(l => `${books[l.bookId]?.title || '(removed)'} — Borrowed: ${fmtDate(l.borrowedAt)} — Due: ${l.dueDate ? new Date(l.dueDate).toLocaleDateString() : 'N/A'} — ${l.returnedAt ? 'Returned' : 'Active'}`);
      alert(rows.join('\n'));
    }

    function emailLoansForBorrower(key){
      const loans = loadLoans().filter(l=>{
        const studentKey = l.borrowerStudent && l.borrowerStudent.trim() ? l.borrowerStudent.trim() : '';
        const gKey = studentKey || ('no-student-'+(l.borrowerName||'').trim());
        return (gKey || 'no-student-unknown') === key && !l.returnedAt;
      });
      if(!loans.length) return alert('No active loans to email about');
      const books = loadBooks();
      const first = loans[0];
      const name = first.borrowerName || '';
      let to = first.borrowerEmail || extractEmail(first.borrowerAddress) || '';
      if(!to){
        for(const l of loans){
          const e = l.borrowerEmail || extractEmail(l.borrowerAddress);
          if(e){ to = e; break; }
        }
      }
      const subject = `Library reminder: ${loans.length} active loan(s)`;
      const bodyLines = loans.map(l=> {
        const title = books[l.bookId] ? books[l.bookId].title : '(removed)';
        return `- ${title} (Due: ${l.dueDate ? new Date(l.dueDate).toLocaleDateString() : 'N/A'})`;
      });
      const body = `Hi ${name},%0A%0AYou have ${loans.length} active loan(s):%0A${bodyLines.join('%0A')}%0A%0APlease return or contact the library.%0A%0AThank you.`;
      const url = gmailComposeUrl({ to, subject, body });
      window.open(url, '_blank');
    }

    // Returns feature
    function handleReturnSearch(){
      const q = XE('#ret-search').value.trim().toLowerCase();
      const wrap = XE('#retResults');
      if(!wrap) return;
      if(!q) { wrap.innerHTML = '<div class="empty">Enter student number or name to search</div>'; return; }
      const loans = loadLoans().filter(l => !l.returnedAt && (
        (l.borrowerStudent || '').toLowerCase().includes(q) ||
        (l.borrowerName || '').toLowerCase().includes(q)
      ));
      if(!loans.length){ wrap.innerHTML = '<div class="empty">No active loans found for that borrower</div>'; return; }
      const books = loadBooks();
      wrap.innerHTML = `<div class="small muted">Found ${loans.length} active loan(s)</div>
        <div class="checkbox-list" id="retList">
        ${loans.map(l => `<label style="display:flex;gap:8px;align-items:center"><input type="checkbox" data-loan="${l.id}" /> <span><strong>${escapeHtml(books[l.bookId]?.title || '(removed)')}</strong> — Borrowed: ${fmtDate(l.borrowedAt)} — Due: ${l.dueDate ? new Date(l.dueDate).toLocaleDateString() : 'N/A'} — Borrower: ${escapeHtml(l.borrowerName)} (${escapeHtml(l.borrowerStudent)})</span></label>`).join('')}
        </div>`;
      const retList = XE('#retList');
      if(retList) qAll('#retList input[type="checkbox"]').forEach(cb => cb.addEventListener('dblclick', ()=> { cb.checked = !cb.checked; }));
    }

    function handleReturnSelected(){
      const list = qAll('#retResults input[type="checkbox"]:checked');
      if(!list.length) return alert('Select at least one loan to mark returned');
      if(!confirm(`Mark ${list.length} selected loan(s) as returned?`)) return;
      const loans = loadLoans();
      const now = Date.now();
      const ids = list.map(cb => cb.getAttribute('data-loan'));
      let changed = 0;
      for(const id of ids){
        const idx = loans.findIndex(l => l.id === id);
        if(idx !== -1 && !loans[idx].returnedAt){
          loans[idx].returnedAt = now;
          changed++;
        }
      }
      if(changed) saveLoans(loans);
      renderAll();
      handleReturnSearch();
      alert(`${changed} loan(s) marked as returned`);
    }

    function handleReturnAllForFound(){
      const checkboxes = qAll('#retResults input[type="checkbox"]');
      if(!checkboxes.length) return alert('No active loans found in the current search');
      if(!confirm(`Return all ${checkboxes.length} active loan(s) shown?`)) return;
      const loans = loadLoans();
      const now = Date.now();
      let changed = 0;
      for(const cb of checkboxes){
        const id = cb.getAttribute('data-loan');
        const idx = loans.findIndex(l => l.id === id);
        if(idx !== -1 && !loans[idx].returnedAt){ loans[idx].returnedAt = now; changed++; }
      }
      if(changed) saveLoans(loans);
      renderAll();
      handleReturnSearch();
      alert(`${changed} loan(s) marked as returned`);
    }

    // Due list and email
    function renderDueList(){
      const loans = loadLoans().filter(l=>!l.returnedAt);
      const wrap = XE('#dueList');
      if(!wrap) return;
      if(!loans.length) return wrap.innerHTML = '<div class="empty">No active loans</div>';
      const now = Date.now(); const books = loadBooks();
      wrap.innerHTML = loans.map(l=>{
        const b = books[l.bookId]; const title = b? b.title : '(removed)';
        const due = l.dueDate || null;
        const overdue = due && now > due;
        const daysLeft = due ? Math.ceil((due - now)/(1000*60*60*24)) : null;
        return `<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.03)">
          <div>
            <div style="font-weight:600">${escapeHtml(title)}</div>
            <div class="small muted">Borrower: ${escapeHtml(l.borrowerName)} · Student: ${escapeHtml(l.borrowerStudent)} · Due: ${due ? new Date(due).toLocaleDateString() : 'N/A'}</div>
            <div class="small muted">${overdue ? '<span style="color:var(--danger)">Overdue</span>' : 'Days left: '+daysLeft}</div>
          </div>
          <div class="actions">
            <button class="btn btn-ghost" data-act="email" data-id="${l.id}">Email borrower</button>
            <button class="btn btn-primary" data-act="return" data-id="${l.id}">Return</button>
          </div>
        </div>`;
      }).join('');
      qAll('#dueList [data-act="return"]').forEach(b=> b.addEventListener('click', ()=> { returnLoan(b.getAttribute('data-id')); }));
      qAll('#dueList [data-act="email"]').forEach(b=> b.addEventListener('click', ()=> { emailLoanByRecord(b.getAttribute('data-id')); }));
    }

    function emailLoanByRecord(loanId){
      const loans = loadLoans();
      const loan = loans.find(l=>l.id===loanId);
      if(!loan){ alert('Loan not found'); return; }
      const books = loadBooks();
      const bookTitle = books[loan.bookId] ? books[loan.bookId].title : 'a book';
      const borrowerName = loan.borrowerName || '';
      let to = loan.borrowerEmail || extractEmail(loan.borrowerAddress) || '';
      const subject = `Library reminder: ${bookTitle}`;
      const body = `Hi ${borrowerName},%0A%0AThis is a reminder that the book "${bookTitle}" you borrowed is due on ${loan.dueDate ? new Date(loan.dueDate).toLocaleDateString() : 'N/A'}.%0A%0APlease return it or contact the library.%0A%0AThank you.`;
      const url = gmailComposeUrl({ to, subject, body });
      window.open(url, '_blank');
    }

    // ---------------- Analytics: lifetime counters ----------------
    function renderAnalytics(){
      const books = Object.values(loadBooks());
      const loans = loadLoans();
      const totalTitles = books.length;
      const totalCopies = books.reduce((s,b)=>s + (b.totalCopies||0),0);
      const activeLoans = loans.filter(l=>!l.returnedAt).length;
      const uniqueBorrowers = new Set(loans.map(l=> (l.borrowerStudent || l.borrowerName || '') )).size;

      XE('#analyticsStats').innerHTML = `<div class="stat">Titles: <strong>${totalTitles}</strong></div><div class="stat">Copies: <strong>${totalCopies}</strong></div><div class="stat">Active loans: <strong>${activeLoans}</strong></div><div class="stat">Borrowers: <strong>${uniqueBorrowers}</strong></div>`;

      // top books (lifetime counts)
      const lifetimeBookCounts = loadCounts(BOOK_COUNTS_KEY);
      const booksByCount = Object.keys(lifetimeBookCounts)
        .map(id => {
          const b = loadBooks()[id] || {};
          return { id, title: b.title || '(removed)', author: b.author || '', lifetime: lifetimeBookCounts[id] || 0, activeBorrowed: countBorrowedCopies(id) };
        })
        .sort((a,b)=> b.lifetime - a.lifetime || b.activeBorrowed - a.activeBorrowed)
        .slice(0,10);

      const topBooksWrap = XE('#topBooks');
      if(!booksByCount.length) topBooksWrap.innerHTML = '<div class="empty">No borrowing activity yet</div>';
      else {
        topBooksWrap.innerHTML = '<ol style="margin:0 0 0 16px;padding:0">';
        topBooksWrap.innerHTML = booksByCount.map(b=> `<li style="margin:6px 0"><div style="font-weight:600">${escapeHtml(b.title)}</div><div class="small muted">${escapeHtml(b.author)} · Borrowed lifetime: <strong>${b.lifetime}</strong>; Currently borrowed: <strong>${b.activeBorrowed}</strong></div></li>`).join('');
      }

      // top borrowers (lifetime counts)
      const lifetimeBorrowerCounts = loadCounts(BORROWER_COUNTS_KEY);
      const borrowersList = Object.keys(lifetimeBorrowerCounts)
        .map(key => {
          const sample = loadLoans().find(l => borrowerKeyForLoan(l) === key) || {};
          return {
            key,
            name: sample.borrowerName || '',
            student: sample.borrowerStudent || (key.startsWith('no-student-') ? '' : key),
            address: sample.borrowerAddress || '',
            email: sample.borrowerEmail || extractEmail(sample.borrowerAddress || ''),
            lifetime: lifetimeBorrowerCounts[key] || 0,
            active: loadLoans().filter(l => borrowerKeyForLoan(l) === key && !l.returnedAt).length
          };
        })
        .sort((a,b)=> b.lifetime - a.lifetime || b.active - a.active)
        .slice(0,10);

      const topBorrowersWrap = XE('#topBorrowers');
      if(!borrowersList.length) topBorrowersWrap.innerHTML = '<div class="empty">No borrower activity yet</div>';
      else {
        topBorrowersWrap.innerHTML = '<ol style="margin:0 0 0 16px;padding:0">';
        topBorrowersWrap.innerHTML = borrowersList.map(b=> {
          const displayName = b.name || (b.student ? 'Student '+b.student : '—');
          const addr = b.email || b.address || '—';
          return `<li style="margin:8px 0"><div style="font-weight:700">${escapeHtml(displayName)}</div><div class="small muted">Student: ${escapeHtml(b.student || '—')} · Contact: ${escapeHtml(addr)} · Borrowed lifetime: <strong>${b.lifetime}</strong> · Active: <strong>${b.active}</strong></div></li>`;
        }).join('');
      }
    }

    // functions used by analytics
    function availableCopies(bookId){
      const books = loadBooks(); const book = books[bookId]; if(!book) return 0;
      const total = book.totalCopies || 0;
      const borrowed = countBorrowedCopies(bookId);
      return Math.max(0, total - borrowed);
    }
    function countBorrowedCopies(bookId){
      const loans = loadLoans();
      return loans.filter(l=>l.bookId === bookId && !l.returnedAt).length;
    }

    // Quick search and helpers
    function renderSearch(){
      const wrap = XE('#searchResults');
      if(!wrap) return;
      const qEl = XE('#searchInput');
      const q = qEl ? qEl.value.trim().toLowerCase() : '';
      const books = Object.values(loadBooks()).sort((a,b)=>a.title.localeCompare(b.title));
      if(!books.length) { wrap.innerHTML = '<div class="empty">No books available</div>'; return; }
      const matches = q ? books.filter(b=> {
        const title = (b.title||'').toLowerCase();
        const author = (b.author||'').toLowerCase();
        const isbn = (b.isbn||'').toLowerCase();
        const cat = (b.category||'').toLowerCase();
        return title.includes(q) || author.includes(q) || isbn.includes(q) || cat.includes(q);
      }) : books;
      if(!matches.length) { wrap.innerHTML = '<div class="empty">No matches</div>'; return; }
      wrap.innerHTML = matches.map(b=> {
        const avail = availableCopies(b.id);
        return `<div style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.03)"><div style="font-weight:600">${escapeHtml(b.title)}</div><div class="small muted">${escapeHtml(b.author)} · ${escapeHtml(b.category||'')} · ISBN ${escapeHtml(b.isbn||'')}</div><div class="small muted">Available ${avail}/${b.totalCopies}</div></div>`;
      }).join('');
    }

    function renderLeftStats(){
      const books = Object.values(loadBooks());
      const loans = loadLoans();
      XE('#leftStats').innerHTML = `<div class="small muted">Titles: ${books.length} · Loans: ${loans.length} · Active: ${loans.filter(l=>!l.returnedAt).length}</div>`;
    }

    function renderQuickSearch(){
      const q = XE('#qa-search').value.trim().toLowerCase();
      const wrap = XE('#qa-search-results'); if(!wrap) return;
      if(!q) return wrap.innerHTML = '';
      const res = Object.values(loadBooks()).filter(b=> (b.title||'').toLowerCase().includes(q) || (b.author||'').toLowerCase().includes(q));
      if(!res.length) return wrap.innerHTML = '<div class="empty">No matches</div>';
      wrap.innerHTML = res.map(b=>`<div style="padding:6px 0;border-bottom:1px solid rgba(255,255,255,0.03)"><div style="font-weight:600">${escapeHtml(b.title)}</div><div class="small muted">${escapeHtml(b.author)}</div></div>`).join('');
    }

    function handleExport(){
      const payload = { books: loadBooks(), loans: loadLoans(), bookCounts: loadCounts(BOOK_COUNTS_KEY), borrowerCounts: loadCounts(BORROWER_COUNTS_KEY), exportedAt: Date.now() };
      const blob = new Blob([JSON.stringify(payload, null, 2)], { type: 'application/json' });
      const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = 'library_export.json'; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
    }

    function handleClearAll(){
      if(!confirm('Clear all books and loans in this browser? This removes local demo data.')) return;
      localStorage.removeItem(BOOKS_KEY); localStorage.removeItem(LOANS_KEY);
      // lifetime counters kept intentionally; if you want to reset them uncomment next lines:
      // localStorage.removeItem(BOOK_COUNTS_KEY); localStorage.removeItem(BORROWER_COUNTS_KEY);
      renderAll();
      alert('Demo data cleared. Your session remains; click Log out to sign out.');
    }

    // leftover helpers
    function getBookMatchesForTypeahead(q){
      if(!q) return [];
      const books = Object.values(loadBooks());
      q = q.toLowerCase();
      return books
        .filter(b => (b.title||'').toLowerCase().includes(q) || (b.author||'').toLowerCase().includes(q) )
        .sort((a,b)=>a.title.localeCompare(b.title))
        .slice(0,200);
    }
    // keep original function name used earlier
    function getBookMatches(q){ return getBookMatchesForTypeahead(q); }

    // storage sync
    window.addEventListener('storage', (e)=> { if([BOOKS_KEY, LOANS_KEY, BOOK_COUNTS_KEY, BORROWER_COUNTS_KEY].includes(e.key)) renderAll(); });

    // Optional: Bootstrap lifetime counters from existing loans (run once in console if desired)
    // (function bootstrapCountsFromLoans(){ const loans = loadLoans(); const bCounts = {}; const brCounts = {}; loans.forEach(l=>{ if(l.bookId) bCounts[l.bookId] = (bCounts[l.bookId]||0) + 1; const bk = borrowerKeyForLoan(l); brCounts[bk] = (brCounts[bk]||0) + 1; }); saveCounts(BOOK_COUNTS_KEY, bCounts); saveCounts(BORROWER_COUNTS_KEY, brCounts); console.log('Bootstrapped lifetime counters'); })();

  </script>
</body>
</html>
