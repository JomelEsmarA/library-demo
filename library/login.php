<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Login</title>
<style>
:root{--bg:#071126;--card:#0f1724;--muted:#94a3b8;--accent:#38bdf8;--danger:#ef4444;--success:#10b981}
body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg);color:#e6eef8;font-family:system-ui,Arial;padding:24px}
.card{background:var(--card);padding:20px;border-radius:12px;width:420px;box-shadow:0 8px 30px rgba(0,0,0,0.6)}
label{display:block;font-size:13px;color:var(--muted);margin-top:12px}
input{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:inherit}
.row{display:flex;justify-content:space-between;align-items:center;margin-top:14px}
.btn{padding:10px 14px;border-radius:10px;border:0;cursor:pointer;font-weight:600}
.btn-ghost{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted)}
.btn-primary{background:linear-gradient(90deg,var(--accent),#60a5fa);color:#02203a}
.muted{color:var(--muted);font-size:13px}
.error{color:var(--danger)}
.a-row{display:flex;gap:8px;align-items:center;margin-top:12px}
.admin-link{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted);padding:8px 10px;border-radius:8px;cursor:pointer}
</style>
</head>
<body>
<div class="card" role="main" aria-live="polite">
  <h2 style="margin:0 0 6px 0">Log in</h2>
  <p class="muted" style="margin:0 0 12px 0">Welcome to NASH Library Management system</p>

  <form id="loginForm" autocomplete="on" novalidate>
    <label for="li-email">Email</label>
    <input id="li-email" type="email" required placeholder="youremail@example.com" />
    <label for="li-password">Password</label>
    <input id="li-password" type="password" required placeholder="Your password" />

    <div class="row" style="margin-top:12px">
      <div class="muted"></div>
      <button class="btn btn-primary" type="submit">Log in</button>
    </div>

    <div id="loginMessage" role="status" aria-live="polite" style="margin-top:10px"></div>
  </form>

  <div class="a-row">
    <button id="btnAdminSignIn" class="admin-link" type="button">Sign in as Admin</button>
    
  </div>

  <hr style="margin:16px 0;border:none;border-top:1px solid rgba(255,255,255,0.03)">

  <div style="display:flex;justify-content:space-between;align-items:center">
    <div class="muted">Need an account?</div>
    <a href="signup.php">Create account</a>
  </div>
</div>

<script>
const USERS_KEY = 'demo_accounts_v1';
const SESSION_KEY = 'demo_session_v1';

function loadUsers(){ try{ const raw = localStorage.getItem(USERS_KEY); return raw ? JSON.parse(raw) : {}; } catch(e){ return {}; } }
function saveSession(email){ sessionStorage.setItem(SESSION_KEY, email); }

async function hashPassword(password, salt){
  const enc = new TextEncoder();
  const data = enc.encode(salt + password);
  const h = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(h)).map(b=>b.toString(16).padStart(2,'0')).join('');
}

function setMessage(el, text, cls=''){ el.textContent = text; el.className = cls; }

document.getElementById('loginForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const email = document.getElementById('li-email').value.trim().toLowerCase();
  const password = document.getElementById('li-password').value;
  const msgEl = document.getElementById('loginMessage');
  setMessage(msgEl,'','');

  if(!email || !password){ setMessage(msgEl,'Please enter email and password.','error'); return; }

  const users = loadUsers();
  const user = users[email];
  if(!user){ setMessage(msgEl,'No account found with that email.','error'); return; }

  const candidateHash = await hashPassword(password, user.salt);
  if(candidateHash !== user.hash){ setMessage(msgEl,'Incorrect password.','error'); return; }

  saveSession(email);
  location.href = 'dashboard.php';
});

document.getElementById('btnAdminSignIn').addEventListener('click', function(){
  location.href = 'admin_login.php';
});

// If already logged in, go straight to dashboard (preserves previous behavior)
document.addEventListener('DOMContentLoaded', ()=>{
  if(sessionStorage.getItem(SESSION_KEY)) location.href = 'dashboard.php';
});
</script>
</body>
</html>
