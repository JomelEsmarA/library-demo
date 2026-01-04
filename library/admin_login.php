<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Admin Login</title>
<style>
:root{--bg:#071126;--card:#0f1724;--muted:#94a3b8;--accent:#38bdf8;--danger:#ef4444;--success:#10b981}
body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg);color:#e6eef8;font-family:system-ui,Arial;padding:24px}
.card{background:var(--card);padding:20px;border-radius:12px;width:420px;box-shadow:0 8px 30px rgba(0,0,0,0.6)}
label{display:block;font-size:13px;color:var(--muted);margin-top:12px}
input{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:inherit}
.row{display:flex;justify-content:space-between;align-items:center;margin-top:14px}
.btn{padding:10px 14px;border-radius:10px;border:0;cursor:pointer;font-weight:600}
.btn-primary{background:linear-gradient(90deg,var(--accent),#60a5fa);color:#02203a}
.muted{color:var(--muted);font-size:13px}
.error{color:var(--danger)}
a{color:var(--accent);text-decoration:none;font-weight:600}
</style>
</head>
<body>
<div class="card" role="main" aria-live="polite">
  <h2 style="margin:0 0 6px 0">Admin Sign in</h2>
  <p class="muted" style="margin:0 0 12px 0">Sign in with an admin account created on Sign Up.</p>

  <form id="adminLoginForm" autocomplete="on" novalidate>
    <label for="ai-email">Email</label>
    <input id="ai-email" type="email" required placeholder="admin@example.com" />
    <label for="ai-password">Password</label>
    <input id="ai-password" type="password" required placeholder="Your password" />

    <div class="row">
      <div class="muted">Admin only</div>
      <button class="btn btn-primary" type="submit">Sign in as admin</button>
    </div>

    <div id="adminLoginMessage" role="status" aria-live="polite" style="margin-top:10px"></div>
  </form>

  <hr style="margin:16px 0;border:none;border-top:1px solid rgba(255,255,255,0.03)">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <div class="muted">Need a normal login?</div>
    <a href="login.php">Go to Login</a>
  </div>
</div>

<script>
const USERS_KEY = 'demo_accounts_v1';
const SESSION_KEY = 'demo_session_v1';

function loadUsers(){ try{ const raw = localStorage.getItem(USERS_KEY); return raw ? JSON.parse(raw) : {}; } catch(e){ return {}; } }
function saveSession(obj){ sessionStorage.setItem(SESSION_KEY, JSON.stringify(obj)); }

async function hashPassword(password, salt){
  const enc = new TextEncoder();
  const data = enc.encode(salt + password);
  const h = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(h)).map(b=>b.toString(16).padStart(2,'0')).join('');
}

function setMessage(el, text, cls=''){ el.textContent = text; el.className = cls; }

document.getElementById('adminLoginForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const email = document.getElementById('ai-email').value.trim().toLowerCase();
  const password = document.getElementById('ai-password').value;
  const msgEl = document.getElementById('adminLoginMessage');
  setMessage(msgEl,'','');

  if(!email || !password){ setMessage(msgEl,'Please enter email and password.','error'); return; }

  const users = loadUsers();
  const user = users[email];
  if(!user){ setMessage(msgEl,'No account found with that email.','error'); return; }

  if((user.role || 'staff') !== 'admin'){ setMessage(msgEl,'This account is not an admin. Create an admin account on Sign Up.','error'); return; }

  const candidateHash = await hashPassword(password, user.salt);
  if(candidateHash !== user.hash){ setMessage(msgEl,'Incorrect password.','error'); return; }

  saveSession({ email, role: 'admin' });
  location.href = 'admin.php';
});

// redirect if already admin session
document.addEventListener('DOMContentLoaded', ()=>{
  const s = sessionStorage.getItem(SESSION_KEY);
  if(!s) return;
  try{
    const sess = JSON.parse(s);
    if(sess && sess.role === 'admin') location.href = 'admin.php';
  }catch(e){}
});
</script>
</body>
</html>
