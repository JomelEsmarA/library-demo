<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Library Admin Dashboard</title>
<style>
:root{
  --bg:#0a1624; --panel:#0f2333; --soft:#13283a; --muted:#9fb3c8; --text:#e6f4fb;
  --accent:#2bb7ff; --accent-2:#66c2ff; --success:#22c55e; --danger:#ff6b6b;
  --glass: rgba(255,255,255,0.03); --radius:12px; --shadow: 0 10px 30px rgba(2,6,23,0.6);
  font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; font-size:15px;
}
*{box-sizing:border-box}
html,body{height:100%}
body{
  margin:0; background: linear-gradient(180deg,var(--bg),#071322); color:var(--text);
  -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; padding:20px;
}
.app{max-width:1280px;margin:0 auto;display:grid;grid-template-columns:260px 1fr;gap:20px;align-items:start}
.sidebar{
  background:linear-gradient(180deg,var(--panel),var(--soft));border-radius:var(--radius);
  padding:18px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:16px;min-height:560px;
}
.brand{display:flex;gap:12px;align-items:center}
.logo{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--accent),var(--accent-2));display:flex;align-items:center;justify-content:center;font-weight:700;color:#042033}
.brand h1{margin:0;font-size:15px}
.brand p{margin:0;font-size:12px;color:var(--muted)}
.nav{display:flex;flex-direction:column;gap:8px;margin-top:6px}
.nav button{
  background:transparent;border:0;color:var(--muted);text-align:left;padding:10px;border-radius:10px;cursor:pointer;font-weight:600;
  display:flex;justify-content:space-between;align-items:center;
}
.nav button.active{background:linear-gradient(90deg,rgba(43,183,255,0.12),rgba(102,194,255,0.06));color:var(--accent-2)}
.small{font-size:13px;color:var(--muted)}
.btn{padding:8px 12px;border-radius:10px;border:0;cursor:pointer;font-weight:700}
.btn-ghost{background:transparent;border:1px solid rgba(255,255,255,0.04);color:var(--muted)}
.btn-primary{background:linear-gradient(90deg,var(--accent),var(--accent-2));color:#042033}
.main{display:flex;flex-direction:column;gap:18px}
.header{display:flex;align-items:center;justify-content:space-between;gap:12px}
.header-left{display:flex;flex-direction:column}
.header-title{font-size:18px;font-weight:800}
.header-sub{font-size:13px;color:var(--muted)}
.header-actions{display:flex;gap:8px;align-items:center}
.grid{display:grid;grid-template-columns:1fr 380px;gap:18px}
@media (max-width:1100px){ .app{grid-template-columns:1fr} .grid{grid-template-columns:1fr} }
.card{background:linear-gradient(180deg,var(--panel),#0e1f2b);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow)}
.section-title{margin:0 0 10px 0;font-weight:700}
.table{width:100%;border-collapse:collapse;margin-top:10px}
.table th,.table td{padding:10px;border-bottom:1px solid var(--glass);text-align:left;font-size:14px;vertical-align:middle}
.table th{color:var(--muted);font-weight:700;font-size:13px}
.row-actions{display:flex;gap:8px}
.input,select,textarea{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:var(--text)}
.filterBar{display:flex;gap:8px;align-items:center}
.kpi{display:flex;gap:12px;align-items:center}
.kpi .stat{background:linear-gradient(180deg,rgba(255,255,255,0.02),transparent);padding:10px;border-radius:10px;min-width:110px;text-align:center}
.kpi .stat strong{display:block;font-size:18px}
.note{font-size:13px;color:var(--muted);margin-top:8px}
.table tr:hover td{background:linear-gradient(90deg, rgba(255,255,255,0.01), transparent)}
.badge{display:inline-block;padding:6px 8px;border-radius:999px;background:rgba(255,255,255,0.03);font-weight:700}
.hidden{display:none}
.link{color:var(--accent);text-decoration:none;font-weight:700}
.small-muted{color:var(--muted);font-size:13px}
</style>
</head>
<body>
<div class="app" role="application" aria-label="Admin dashboard">
  <aside class="sidebar" aria-label="Navigation">
    <div class="brand">
      <div class="logo" aria-hidden>LB</div>
      <div>
        <h1>Library Admin</h1>
        <p class="small">Dashboard · Manage accounts & loans</p>
      </div>
    </div>

    <nav class="nav" role="navigation" aria-label="Main sections">
      <button id="navAccounts" class="active" data-panel="accounts">Accounts</button>
      <button id="navBooks" data-panel="books">Books</button>
      <button id="navBorrowers" data-panel="borrowers">Borrowers & Due</button>
      <button id="navLoans" data-panel="loans">Loans</button>
      <button id="navAnalytics" data-panel="analytics">Analytics</button>
    </nav>

    <div class="section">
      <div class="small-muted">Quick actions</div>
      <div style="display:flex;gap:8px;margin-top:8px">
        <button id="btnNewBook" class="btn btn-primary">New book</button>
        <button id="btnExport" class="btn btn-ghost">Export</button>
      </div>
      <div class="note">Tip: click any section to open it in the main column.</div>
    </div>

    <div style="margin-top:12px" class="small-muted">Signed in as</div>
    <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
      <div style="flex:1">
        <div id="adminEmail" style="font-weight:700"></div>
        <div class="small-muted" id="adminRole">Administrator</div>
      </div>
      <button id="btnLogout" class="btn btn-ghost">Sign out</button>
    </div>
  </aside>

  <main class="main" role="main">
    <header class="header">
      <div class="header-left">
        <div class="header-title">Admin Dashboard</div>
        <div class="header-sub">Manage accounts, books, loans and borrower communications</div>
      </div>
      <div class="header-actions">
        <div class="kpi">
          <div class="stat badge" id="statTitles"><strong>0</strong><span class="small-muted">Titles</span></div>
          <div class="stat badge" id="statActive"><strong>0</strong><span class="small-muted">Active loans</span></div>
          <div class="stat badge" id="statOverdue"><strong>0</strong><span class="small-muted">Overdue</span></div>
        </div>
        <button id="btnRefresh" class="btn btn-ghost">Refresh</button>
        <button id="btnQuickAdd" class="btn btn-primary">Quick Add</button>
      </div>
    </header>

    <section class="grid" aria-live="polite">
      <div>
        <section id="panel-accounts" class="card" role="region" aria-labelledby="accountsHeading">
          <h2 id="accountsHeading" style="margin:0 0 8px 0">Manage Accounts</h2>
          <div style="display:flex;gap:12px;align-items:center">
            <input id="filterAccounts" class="input" placeholder="Filter by email or name" />
            <button id="btnCreateAccountOpen" class="btn btn-primary">Create account</button>
          </div>

          <table class="table" aria-label="Accounts table">
            <thead><tr><th>Email</th><th>Name</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody id="accountsBody"><tr><td colspan="5" class="small-muted">Loading accounts…</td></tr></tbody>
          </table>
        </section>

        <section id="panel-books" class="card" style="margin-top:16px" role="region" aria-labelledby="booksHeading">
          <h2 id="booksHeading" style="margin:0 0 8px 0">Books</h2>
          <div style="display:flex;gap:8px;margin-bottom:8px;align-items:center">
            <input id="filterBooks" class="input" placeholder="Search title, author or ISBN" />
            <button id="btnAddBookOpen" class="btn btn-ghost">Add</button>
          </div>

          <table class="table" aria-label="Books table">
            <thead><tr><th>Title</th><th>Author</th><th>Copies</th><th>Available</th><th>Actions</th></tr></thead>
            <tbody id="booksBody"><tr><td colspan="5" class="small-muted">No books</td></tr></tbody>
          </table>
        </section>

        <section id="panel-analytics" class="card hidden" style="margin-top:16px" role="region" aria-labelledby="analyticsHeading">
          <h2 id="analyticsHeading" style="margin:0 0 8px 0">Analytics</h2>
          <div id="analyticsSummary" class="small-muted">Summary of active loans, overdue items and top titles</div>

          <div style="margin-top:12px">
            <div class="small-muted">Top Most Borrowed (lifetime)</div>
            <div id="topBooks" style="margin-top:8px"></div>

            <div style="height:12px"></div>

            <div class="small-muted">Top Borrowers (lifetime)</div>
            <div id="topBorrowers" style="margin-top:8px"></div>
          </div>
        </section>

        <section id="panel-borrowers" class="card hidden" style="margin-top:16px" role="region" aria-labelledby="borrowersHeading">
          <h2 id="borrowersHeading" style="margin:0 0 8px 0">Borrowers & Due Details</h2>

          <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="filterBar" style="flex:1">
              <input id="filterBorrowers" class="input" placeholder="Filter by name, email or student number" />
              <button id="btnRefreshBorrowers" class="btn btn-ghost">Refresh</button>
            </div>
            <div>
              <button id="btnExportBorrowers" class="btn btn-primary">Export CSV</button>
            </div>
          </div>

          <table class="table" aria-label="Borrowers due table">
            <thead><tr><th>Borrower</th><th>Contact</th><th>Active</th><th>Next due</th><th>Status</th><th>Notes</th><th>Actions</th></tr></thead>
            <tbody id="borrowersBody"><tr><td colspan="7" class="small-muted">No borrower data</td></tr></tbody>
          </table>
        </section>

        <section id="panel-loans" class="card hidden" style="margin-top:16px" role="region" aria-labelledby="loansHeading">
          <h2 id="loansHeading" style="margin:0 0 8px 0">Loans</h2>
          <div class="note">View and manage active loans. Mark returns or email borrowers.</div>
          <table class="table" aria-label="Loans table" style="margin-top:12px">
            <thead><tr><th>Book</th><th>Borrower</th><th>Borrowed</th><th>Due</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody id="loansBody"><tr><td colspan="6" class="small-muted">No loans</td></tr></tbody>
          </table>
        </section>
      </div>

      <aside>
        <section class="card" role="region" aria-labelledby="quickTitle">
          <h3 id="quickTitle" style="margin:0 0 8px 0">Quick Create</h3>

          <div style="display:flex;flex-direction:column;gap:8px">
            <label class="small-muted">Create account</label>
            <input id="qName" class="input" placeholder="Full name" />
            <input id="qEmail" class="input" placeholder="email@example.com" />
            <input id="qPass" class="input" placeholder="password" type="password" />
            <select id="qRole" class="input"><option value="staff">Staff</option><option value="admin">Admin</option></select>
            <div style="display:flex;gap:8px">
              <button id="qCreateAcc" class="btn btn-primary">Create</button>
              <button id="qClearAcc" class="btn btn-ghost">Reset</button>
            </div>
            <div id="qAccMsg" class="small-muted"></div>
          </div>
        </section>

        <section class="card" style="margin-top:14px" role="region" aria-labelledby="analyticsMiniTitle">
          <h3 id="analyticsMiniTitle" style="margin:0 0 8px 0">Analytics Snapshot</h3>
          <div id="analyticsSummaryMini" class="small-muted">Quick KPIs reflect main analytics</div>
        </section>

        <section class="card" style="margin-top:14px" role="region" aria-labelledby="toolsTitle">
          <h3 id="toolsTitle" style="margin:0 0 8px 0">Tools</h3>
          <div style="display:flex;flex-direction:column;gap:8px">
            <button id="btnResetAnalytics" class="btn btn-ghost">Reset counters</button>
            <button id="btnClearAll" class="btn btn-danger">Clear books & loans</button>
          </div>
          <div class="note" style="margin-top:8px"></div>
        </section>
      </aside>
    </section>
  </main>
</div>

<script>
/* Storage keys */
const USERS_KEY='demo_accounts_v1', BOOKS_KEY='demo_books_v1', LOANS_KEY='demo_loans_v1', BOOK_COUNTS_KEY='demo_book_borrow_counts_v1', BORROWER_COUNTS_KEY='demo_borrower_borrow_counts_v1', SESSION_KEY='demo_session_v1';

/* DOM helpers */
const qs=(s,r=document)=> r.querySelector(s);
const qsa=(s,r=document)=> Array.from((r||document).querySelectorAll(s));

function loadJSON(k){ try{ const v=localStorage.getItem(k); return v?JSON.parse(v): null }catch(e){ return null } }
function saveJSON(k,v){ localStorage.setItem(k, JSON.stringify(v)); }

function loadUsers(){ return loadJSON(USERS_KEY) || {}; }
function loadBooks(){ return loadJSON(BOOKS_KEY) || {}; }
function loadLoans(){ return loadJSON(LOANS_KEY) || []; }
function saveUsers(u){ saveJSON(USERS_KEY,u); }
function saveBooks(b){ saveJSON(BOOKS_KEY,b); }
function saveLoans(l){ saveJSON(LOANS_KEY,l); }
function loadCounts(k){ return loadJSON(k) || {}; }
function saveCounts(k,o){ saveJSON(k,o); }

function requireAdmin(){ const raw = sessionStorage.getItem(SESSION_KEY); if(!raw){ location.href='admin_login.php'; return null } try{ const s = JSON.parse(raw); if(!s || s.role !== 'admin'){ location.href='admin_login.php'; return null } return s; }catch(e){ location.href='admin_login.php'; return null } }

function fmt(ts){ return ts? new Date(ts).toLocaleString() : '-'; }
function isoDate(ts){ return ts? new Date(ts).toISOString().split('T')[0] : '-'; }
function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[m])); }

/* Navigation */
function showPanel(name){
  ['accounts','books','borrowers','loans','analytics'].forEach(p=>{
    const el = qs('#panel-'+p);
    if(!el) return;
    if(p===name) el.classList.remove('hidden'); else el.classList.add('hidden');
  });
  qsa('.nav button').forEach(b=> b.classList.toggle('active', b.dataset.panel===name));
  if(name==='accounts') renderAccounts();
  if(name==='books') renderBooks();
  if(name==='borrowers') renderBorrowers();
  if(name==='loans') renderLoans();
  if(name==='analytics') renderAnalytics();
}

/* init */
document.addEventListener('DOMContentLoaded', ()=>{
  const me = requireAdmin(); if(!me) return;
  qs('#adminEmail').textContent = me.email || 'admin';
  qs('#adminRole').textContent = 'Administrator';

  qsa('.nav button').forEach(btn=> btn.addEventListener('click', ()=> showPanel(btn.dataset.panel)));
  qs('#btnRefresh').addEventListener('click', ()=> { renderAll(); flash('Refreshed'); });
  qs('#btnExport').addEventListener('click', exportFull);
  qs('#btnNewBook').addEventListener('click', ()=> { showPanel('books'); openAddBook(); });
  qs('#btnQuickAdd').addEventListener('click', ()=> qs('#qCreateAcc').click());
  qs('#btnLogout').addEventListener('click', ()=> { sessionStorage.removeItem(SESSION_KEY); location.href='admin_login.php'; });

  qs('#qCreateAcc').addEventListener('click', handleQuickCreateAccount);
  qs('#qClearAcc').addEventListener('click', ()=> { qs('#qName').value=''; qs('#qEmail').value=''; qs('#qPass').value=''; qs('#qRole').value='staff'; qs('#qAccMsg').textContent=''; });

  qs('#filterAccounts').addEventListener('input', renderAccounts);
  qs('#filterBooks').addEventListener('input', renderBooks);
  qs('#filterBorrowers').addEventListener('input', renderBorrowers);
  qs('#btnExportBorrowers') && qs('#btnExportBorrowers').addEventListener('click', exportBorrowersCSV);
  qs('#btnRefreshBorrowers') && qs('#btnRefreshBorrowers').addEventListener('click', renderBorrowers);
  qs('#btnResetAnalytics') && qs('#btnResetAnalytics').addEventListener('click', ()=> { if(confirm('Reset lifetime counters?')) { localStorage.removeItem(BOOK_COUNTS_KEY); localStorage.removeItem(BORROWER_COUNTS_KEY); renderAnalytics(); flash('Counters reset'); }});
  qs('#btnClearAll') && qs('#btnClearAll').addEventListener('click', ()=> { if(confirm('Clear books and loans?')){ localStorage.removeItem(BOOKS_KEY); localStorage.removeItem(LOANS_KEY); renderAll(); flash('Cleared demo data'); }});

  showPanel('accounts');
  renderAll();
});

/* account creation (quick) */
async function randomSalt(len=16){ const buf = crypto.getRandomValues(new Uint8Array(len)); return btoa(String.fromCharCode(...buf)); }
async function hashPassword(password, salt){ const enc = new TextEncoder(); const data = enc.encode(salt + password); const h = await crypto.subtle.digest('SHA-256', data); return Array.from(new Uint8Array(h)).map(b=>b.toString(16).padStart(2,'0')).join(''); }

async function handleQuickCreateAccount(){
  const name = qs('#qName').value.trim();
  const email = qs('#qEmail').value.trim().toLowerCase();
  const pass = qs('#qPass').value;
  const role = qs('#qRole').value || 'staff';
  const msg = qs('#qAccMsg'); msg.textContent='';
  if(!name || !email || !pass){ msg.textContent='Complete the fields'; return; }
  if(pass.length < 6){ msg.textContent='Password too short'; return; }
  const users = loadUsers();
  if(users[email]){ msg.textContent='Account exists'; return; }
  const salt = await randomSalt();
  const hash = await hashPassword(pass, salt);
  users[email] = { name, salt, hash, role, created: Date.now() };
  saveUsers(users);
  msg.textContent = 'Account created';
  qs('#qName').value=''; qs('#qEmail').value=''; qs('#qPass').value='';
  renderAccounts();
}

/* accounts UI */
function renderAccounts(){
  const users = loadUsers();
  const filter = (qs('#filterAccounts').value||'').trim().toLowerCase();
  const rows = Object.keys(users).sort().filter(email=>{
    if(!filter) return true;
    const u = users[email];
    return email.includes(filter) || (u.name||'').toLowerCase().includes(filter);
  }).map(email=>{
    const u = users[email];
    return `<tr data-email="${escapeHtml(email)}">
      <td>${escapeHtml(email)}</td>
      <td>${escapeHtml(u.name||'')}</td>
      <td>${escapeHtml(u.role||'staff')}</td>
      <td>${u.created? fmt(u.created) : '-'}</td>
      <td class="row-actions">
        <button class="btn btn-ghost" data-act="prom">Make admin</button>
        <button class="btn btn-danger" data-act="rm">Remove</button>
      </td>
    </tr>`;
  });
  qs('#accountsBody').innerHTML = rows.length ? rows.join('') : '<tr><td colspan="5" class="small-muted">No accounts</td></tr>';
  qsa('#accountsBody [data-act]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const tr = btn.closest('tr'); const email = tr.dataset.email;
      if(btn.dataset.act==='rm'){ if(!confirm(`Remove ${email}?`)) return; const u = loadUsers(); delete u[email]; saveUsers(u); renderAccounts(); }
      if(btn.dataset.act==='prom'){ if(!confirm(`Promote ${email} to admin?`)) return; const u = loadUsers(); if(u[email]){ u[email].role='admin'; saveUsers(u); renderAccounts(); } }
    });
  });
}

/* books UI */
function renderBooks(){
  const books = loadBooks();
  const filter = (qs('#filterBooks').value||'').trim().toLowerCase();
  const arr = Object.values(books).filter(b=>{
    if(!filter) return true;
    return (b.title||'').toLowerCase().includes(filter) || (b.author||'').toLowerCase().includes(filter) || (b.isbn||'').toLowerCase().includes(filter);
  }).sort((a,b)=> (a.title||'').localeCompare(b.title));
  const rows = arr.map(b=>{
    const borrowed = (loadLoans()||[]).filter(l=>l.bookId===b.id && !l.returnedAt).length;
    const avail = Math.max(0,(b.totalCopies||0)-borrowed);
    return `<tr data-id="${escapeHtml(b.id)}">
      <td>${escapeHtml(b.title)}</td>
      <td>${escapeHtml(b.author||'')}</td>
      <td>${b.totalCopies||0}</td>
      <td>${avail}</td>
      <td class="row-actions">
        <button class="btn btn-ghost" data-act="edit">Edit</button>
        <button class="btn btn-ghost" data-act="loans">Loans</button>
        <button class="btn btn-danger" data-act="del">Delete</button>
      </td>
    </tr>`;
  });
  qs('#booksBody').innerHTML = rows.length ? rows.join('') : '<tr><td colspan="5" class="small-muted">No books</td></tr>';
  qsa('#booksBody [data-act]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.closest('tr').dataset.id;
      if(btn.dataset.act==='del'){ if(!confirm('Delete book? Loan history remains.')) return; const b = loadBooks(); delete b[id]; saveBooks(b); renderBooks(); renderAnalytics(); flash('Book deleted'); }
      if(btn.dataset.act==='edit'){ openEditBook(id); }
      if(btn.dataset.act==='loans'){ showBookLoans(id); showPanel('loans'); }
    });
  });
}

/* quick add/edit book */
function openAddBook(){
  const title = prompt('Enter title'); if(!title) return;
  const author = prompt('Author','Unknown') || 'Unknown';
  const copies = parseInt(prompt('Copies','1')||'1',10) || 1;
  const books = loadBooks();
  const id = 'book_'+Math.random().toString(36).slice(2,9);
  books[id] = { id, title, author, totalCopies: copies, created: Date.now() };
  saveBooks(books); renderBooks(); renderAnalytics(); flash('Book added');
}
function openEditBook(id){
  const books = loadBooks(); const b = books[id]; if(!b) return alert('Book not found');
  const title = prompt('Title', b.title) || b.title;
  const author = prompt('Author', b.author) || b.author;
  const copies = parseInt(prompt('Copies', String(b.totalCopies||1))||String(b.totalCopies||1),10) || 1;
  const borrowed = (loadLoans()||[]).filter(l=>l.bookId===id && !l.returnedAt).length;
  if(copies < borrowed) return alert(`Cannot set copies ${copies} while ${borrowed} are borrowed`);
  b.title = title; b.author = author; b.totalCopies = copies; saveBooks(books); renderBooks(); renderAnalytics(); flash('Updated book');
}
function showBookLoans(bookId){
  const loans = loadLoans().filter(l=>l.bookId===bookId);
  if(!loans.length) return alert('No loans for this book');
  alert(loans.map(l=>`${loadBooks()[l.bookId]?.title || '(removed)'} — Borrower: ${l.borrowerName} (${l.borrowerStudent}) — Borrowed: ${fmt(l.borrowedAt)} — Due: ${l.dueDate? isoDate(l.dueDate):'N/A'} — ${l.returnedAt ? 'Returned' : 'Active'}`).join('\n'));
}

/* borrowers & loans helpers */
function borrowerKeyForLoan(l){ const st = l.borrowerStudent && l.borrowerStudent.trim()? l.borrowerStudent.trim() : ''; return st || ('no-student-'+(l.borrowerName||'').trim()) || 'no-student-unknown'; }
function extractEmail(text){ if(!text) return ''; const m = String(text).match(/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i); return m?m[0]:''; }
function countBorrowedCopies(bookId){ return loadLoans().filter(l=>l.bookId===bookId && !l.returnedAt).length; }

function buildBorrowerDetails(){
  const loans = loadLoans();
  const books = loadBooks();
  const map = {};
  loans.forEach(l=>{
    const key = borrowerKeyForLoan(l);
    if(!map[key]) map[key] = { key, name: l.borrowerName||'', student:l.borrowerStudent||'', email: l.borrowerEmail||extractEmail(l.borrowerAddress||''), address: l.borrowerAddress||'', loans: [] };
    map[key].loans.push(Object.assign({}, l, { title: books[l.bookId] ? books[l.bookId].title : '(removed)' }));
  });
  return Object.values(map).map(g=>{
    const active = g.loans.filter(x=>!x.returnedAt);
    const nextDue = active.map(x=>x.dueDate||null).filter(Boolean);
    const next = nextDue.length ? Math.min(...nextDue) : null;
    const overdue = next ? (Date.now() > next) : false;
    return { ...g, activeCount: active.length, nextDue: next, overdue };
  }).sort((a,b)=> (a.overdue === b.overdue)? ((a.nextDue||Infinity)-(b.nextDue||Infinity)) : (a.overdue? -1:1));
}

function renderBorrowers(){
  const details = buildBorrowerDetails();
  const filter = (qs('#filterBorrowers').value||'').trim().toLowerCase();
  const list = details.filter(d=>{
    if(!filter) return true;
    return (d.name||'').toLowerCase().includes(filter) || (d.email||'').toLowerCase().includes(filter) || (d.student||'').toLowerCase().includes(filter);
  });
  const rows = list.map(d=>{
    const next = d.nextDue ? isoDate(d.nextDue) : '—';
    const status = d.overdue ? `<span style="color:${'#ff6b6b'};font-weight:700">Overdue</span>` : (d.nextDue? 'Due':'Clear');
    const notes = [ d.activeCount===0? 'No active loans' : '', d.email? 'Has email':'' ].filter(Boolean).join(' · ');
    return `<tr data-key="${escapeHtml(d.key)}">
      <td><strong>${escapeHtml(d.name||'—')}</strong><div class="small-muted">${escapeHtml(d.address||'')}</div></td>
      <td>${d.email? `<a class="link" href="mailto:${escapeHtml(d.email)}">${escapeHtml(d.email)}</a>` : '—'}</td>
      <td>${d.activeCount}</td>
      <td>${next}</td>
      <td>${status}</td>
      <td class="small-muted">${escapeHtml(notes)}</td>
      <td class="row-actions">
        <button class="btn btn-primary" data-act="email">Email</button>
        <button class="btn btn-ghost" data-act="view">View</button>
        <button class="btn btn-ghost" data-act="mark">Mark returned</button>
      </td>
    </tr>`;
  });
  qs('#borrowersBody').innerHTML = rows.length ? rows.join('') : '<tr><td colspan="7" class="small-muted">No borrower data</td></tr>';
  qsa('#borrowersBody [data-act]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const tr = btn.closest('tr'); const key = tr.getAttribute('data-key');
      if(btn.dataset.act==='view') showLoansForBorrower(key);
      if(btn.dataset.act==='email') emailLoansForBorrower(key);
      if(btn.dataset.act==='mark') markAllReturnedForBorrower(key);
    });
  });
}

/* loans UI */
function renderLoans(){
  const loans = loadLoans().slice();
  const books = loadBooks();
  loans.sort((a,b)=> (b.borrowedAt||0) - (a.borrowedAt||0));
  const rows = loans.map(l=>{
    const book = books[l.bookId];
    const due = l.dueDate? isoDate(l.dueDate) : '—';
    const status = l.returnedAt? 'Returned' : (l.dueDate && Date.now()>l.dueDate ? `<span style="color:${'#ff6b6b'};font-weight:700">Overdue</span>` : 'Active');
    return `<tr data-id="${escapeHtml(l.id)}">
      <td>${escapeHtml(book?book.title:'(removed)')}</td>
      <td>${escapeHtml(l.borrowerName||'—')}</td>
      <td>${fmt(l.borrowedAt)}</td>
      <td>${due}</td>
      <td>${status}</td>
      <td class="row-actions">
        ${l.returnedAt? '' : '<button class="btn btn-primary" data-act="return">Mark return</button>'}
        <button class="btn btn-ghost" data-act="email">Email</button>
      </td>
    </tr>`;
  });
  qs('#loansBody').innerHTML = rows.length? rows.join('') : '<tr><td colspan="6" class="small-muted">No loans</td></tr>';
  qsa('#loansBody [data-act]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.closest('tr').dataset.id;
      if(btn.dataset.act==='return'){ markLoanReturned(id); }
      if(btn.dataset.act==='email'){ emailLoanByRecord(id); }
    });
  });
}

/* borrower actions */
function showLoansForBorrower(key){
  const loans = loadLoans().filter(l=> borrowerKeyForLoan(l) === key);
  if(!loans.length) return alert('No loans for this borrower');
  const out = loans.map(l=>`${(loadBooks()[l.bookId]?.title||'(removed)')} — Borrowed: ${fmt(l.borrowedAt)} — Due: ${l.dueDate? isoDate(l.dueDate):'N/A'} — ${l.returnedAt?'Returned':'Active'}`);
  alert(out.join('\n'));
}
function emailLoansForBorrower(key){
  const loans = loadLoans().filter(l=> borrowerKeyForLoan(l) === key && !l.returnedAt);
  if(!loans.length) return alert('No active loans to email');
  let to = loans[0].borrowerEmail || extractEmail(loans[0].borrowerAddress) || '';
  for(const l of loans){ if(!to) to = l.borrowerEmail || extractEmail(l.borrowerAddress||''); }
  const name = loans[0].borrowerName || '';
  const subject = `Library reminder: ${loans.length} loan(s)`;
  const body = loans.map(l=>`- ${(loadBooks()[l.bookId]?.title||'(removed)')} — Due: ${l.dueDate? isoDate(l.dueDate):'N/A'}`).join('\n');
  const url = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(to||'')}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(`Hi ${name},\nYou have active loans:\n${body}\n\nPlease return or contact the library.`)}`;
  window.open(url,'_blank','noopener');
}
function markAllReturnedForBorrower(key){
  if(!confirm('Mark all active loans for this borrower as returned?')) return;
  const loans = loadLoans();
  let changed = 0; const now = Date.now();
  loans.forEach(l=>{ if(borrowerKeyForLoan(l)===key && !l.returnedAt){ l.returnedAt = now; changed++; }});
  if(changed){ saveLoans(loans); renderAll(); alert(`${changed} loan(s) marked returned`); }
}
function markLoanReturned(id){
  const loans = loadLoans(); const i = loans.findIndex(x=>x.id===id);
  if(i===-1) return alert('Loan not found');
  if(loans[i].returnedAt) return alert('Already returned');
  loans[i].returnedAt = Date.now(); saveLoans(loans); renderAll(); flash('Marked returned');
}
function emailLoanByRecord(loanId){
  const loans = loadLoans(); const loan = loans.find(l=>l.id===loanId); if(!loan) return alert('Loan not found');
  let to = loan.borrowerEmail || extractEmail(loan.borrowerAddress||''); const title = (loadBooks()[loan.bookId]?.title||'a book');
  const subject = `Library reminder: ${title}`;
  const body = `Hi ${loan.borrowerName||''},\nReminder: "${title}" is due on ${loan.dueDate? isoDate(loan.dueDate):'N/A'}.\nPlease return or contact the library.`;
  window.open(`https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(to||'')}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`,'_blank','noopener');
}

/* analytics (moved to middle column) */
function renderAnalytics(){
  const kTitles = document.querySelector('#statTitles');
  const kActive = document.querySelector('#statActive');
  const kOverdue = document.querySelector('#statOverdue');
  const summaryEl = document.querySelector('#analyticsSummary');
  const topBooksEl = document.querySelector('#topBooks');
  const topBorrowersEl = document.querySelector('#topBorrowers');

  const books = Object.values(loadBooks());
  const loans = loadLoans();

  const totalTitles = books.length;
  const activeLoans = loans.filter(l => !l.returnedAt).length;
  const overdue = loans.filter(l => !l.returnedAt && l.dueDate && Date.now() > l.dueDate).length;

  if(kTitles){ const strong = kTitles.querySelector('strong'); if(strong) strong.textContent = totalTitles; else kTitles.textContent = totalTitles; }
  if(kActive){ const strong = kActive.querySelector('strong'); if(strong) strong.textContent = activeLoans; else kActive.textContent = activeLoans; }
  if(kOverdue){ const strong = kOverdue.querySelector('strong'); if(strong) strong.textContent = overdue; else kOverdue.textContent = overdue; }
  if(summaryEl) summaryEl.textContent = `Total titles: ${totalTitles} · Active loans: ${activeLoans} · Overdue: ${overdue}`;

  const lifetimeBookCounts = loadCounts(BOOK_COUNTS_KEY);
  const lifeBookArr = Object.keys(lifetimeBookCounts).map(id=>{
    const b = loadBooks()[id] || {};
    return { id, title: b.title || '(removed)', author: b.author || '', lifetime: lifetimeBookCounts[id] || 0, activeBorrowed: countBorrowedCopies(id) };
  }).sort((a,b)=> b.lifetime - a.lifetime || b.activeBorrowed - a.activeBorrowed).slice(0,10);

  if(topBooksEl){
    if(lifeBookArr.length === 0) topBooksEl.innerHTML = '<div class="small-muted">No borrowing activity yet</div>';
    else topBooksEl.innerHTML = lifeBookArr.map(b=>{
      return `<div style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.03)">
        <div style="font-weight:700">${escapeHtml(b.title)}</div>
        <div class="small-muted">${escapeHtml(b.author)} · Lifetime: <strong>${b.lifetime}</strong> · Active: <strong>${b.activeBorrowed}</strong></div>
      </div>`;
    }).join('');
  }

  const lifetimeBorrowerCounts = loadCounts(BORROWER_COUNTS_KEY);
  const topBorrowersArr = Object.keys(lifetimeBorrowerCounts).map(key=>{
    const sample = loadLoans().find(l => borrowerKeyForLoan(l) === key) || {};
    const name = sample.borrowerName || '';
    const student = sample.borrowerStudent || '';
    const email = sample.borrowerEmail || extractEmail(sample.borrowerAddress || '') || '';
    const lifetime = lifetimeBorrowerCounts[key] || 0;
    const active = loadLoans().filter(l => borrowerKeyForLoan(l) === key && !l.returnedAt).length;
    return { key, name, student, email, lifetime, active };
  }).sort((a,b)=> b.lifetime - a.lifetime || b.active - a.active).slice(0,10);

  if(topBorrowersEl){
    if(topBorrowersArr.length === 0) topBorrowersEl.innerHTML = '<div class="small-muted">No borrower activity yet</div>';
    else topBorrowersEl.innerHTML = topBorrowersArr.map(b=>{
      const displayName = b.name || (b.student ? `Student ${escapeHtml(b.student)}` : '—');
      const contact = b.email || b.student || '—';
      return `<div style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.03)">
        <div style="font-weight:700">${escapeHtml(displayName)}</div>
        <div class="small-muted">Contact: ${escapeHtml(contact)} · Lifetime: <strong>${b.lifetime}</strong> · Active: <strong>${b.active}</strong></div>
      </div>`;
    }).join('');
  }

  // mirror small summary to the aside snapshot area
  const mini = qs('#analyticsSummaryMini');
  if(mini) mini.textContent = `Titles ${totalTitles} · Active ${activeLoans} · Overdue ${overdue}`;
}

/* export */
function exportFull(){ const payload = { users: loadUsers(), books: loadBooks(), loans: loadLoans(), bookCounts: loadCounts(BOOK_COUNTS_KEY), borrowerCounts: loadCounts(BORROWER_COUNTS_KEY), exportedAt: Date.now() }; const blob = new Blob([JSON.stringify(payload,null,2)], { type:'application/json' }); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = 'library_export.json'; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url); }
function exportBorrowersCSV(){ const details = buildBorrowerDetails(); const rows=[['name','student','email','active_loans','next_due','overdue']]; details.forEach(d=> rows.push([d.name||'', d.student||'', d.email||'', String(d.activeCount), d.nextDue? isoDate(d.nextDue):'', d.overdue?'yes':'no'])); const csv = rows.map(r=> r.map(v=> `"${String(v).replace(/"/g,'""')}"`).join(',')).join('\n'); const blob = new Blob([csv], { type:'text/csv' }); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = `borrowers_${Date.now()}.csv`; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url); }

/* utilities */
function flash(text, ms=1400){ const el = document.createElement('div'); el.textContent = text; el.style.position='fixed'; el.style.right='20px'; el.style.bottom='20px'; el.style.background='linear-gradient(90deg,var(--accent),var(--accent-2))'; el.style.color='#042033'; el.style.padding='10px 14px'; el.style.borderRadius='10px'; el.style.fontWeight='700'; el.style.boxShadow='var(--shadow)'; document.body.appendChild(el); setTimeout(()=> el.remove(), ms); }
function renderAll(){ renderAccounts(); renderBooks(); renderBorrowers(); renderLoans(); renderAnalytics(); }
window.addEventListener('storage', (e)=> { if([USERS_KEY,BOOKS_KEY,LOANS_KEY,BOOK_COUNTS_KEY,BORROWER_COUNTS_KEY].includes(e.key)) renderAll(); });
</script>
</body>
</html>