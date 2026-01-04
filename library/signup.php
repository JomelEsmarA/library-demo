<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Sign Up</title>
<style>
:root{--bg:#0f1724;--card:#111827;--muted:#94a3b8;--accent:#38bdf8;--danger:#ef4444;--success:#10b981}
body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg);color:#e6eef8;font-family:system-ui,Arial;padding:24px}
.card{background:var(--card);padding:20px;border-radius:12px;width:420px;box-shadow:0 8px 30px rgba(0,0,0,0.6)}
label{display:block;font-size:13px;color:var(--muted);margin-top:12px}
input,select{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:inherit}
.row{display:flex;justify-content:space-between;align-items:center;margin-top:14px}
.btn{padding:10px 14px;border-radius:10px;border:0;cursor:pointer;font-weight:600}
.btn-primary{background:linear-gradient(90deg,var(--accent),#60a5fa);color:#02203a}
.muted{color:var(--muted);font-size:13px}
.error{color:var(--danger)}
.success{color:var(--success)}
a{color:var(--accent);text-decoration:none;font-weight:600}
.small{font-size:12px;color:var(--muted);margin-top:6px}
.role-row{display:flex;gap:8px;align-items:center;margin-top:10px}
</style>
</head>
<body>
<div class="card" role="main" aria-live="polite">
  <h2 style="margin:0 0 6px 0">Create account</h2>
  <p class="muted" style="margin:0 0 12px 0">Create staff or admin account.</p>

  <form id="signupForm" autocomplete="on" novalidate>
    <label for="su-name">Full name</label>
    <input id="su-name" type="text" required placeholder="Full Name" />
    <label for="su-email">Email</label>
    <input id="su-email" type="email" required placeholder="youremail@example.com" />
    <label for="su-password">Password</label>
    <input id="su-password" type="password" required minlength="8" placeholder="At least 8 characters" />
    <label for="su-password-confirm">Confirm password</label>
    <input id="su-password-confirm" type="password" required minlength="8" placeholder="Repeat password" />

    <div id="adminCreateRow" class="role-row">
      <input id="su-admin-check" type="checkbox" />
      <label for="su-admin-check" style="margin:0">Create account as admin</label>
      <div class="small">Check this to create an admin account</div>
    </div>

    <div class="row">
      <div class="muted"></div>
      <button class="btn btn-primary" type="submit">Create account</button>
    </div>

    <div id="signupMessage" role="status" aria-live="polite" style="margin-top:10px"></div>
  </form>

  <hr style="margin:16px 0;border:none;border-top:1px solid rgba(255,255,255,0.03)">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <div class="muted">Already have an account?</div>
    <a href="login.php">Go to Login</a>
  </div>
</div>

<script>
const USERS_KEY = 'demo_accounts_v1';
function loadUsers(){ try{ const raw = localStorage.getItem(USERS_KEY); return raw ? JSON.parse(raw) : {}; } catch(e){ return {}; } }
function saveUsers(obj){ localStorage.setItem(USERS_KEY, JSON.stringify(obj)); }

async function randomSalt(len=16){
  const buf = crypto.getRandomValues(new Uint8Array(len));
  return btoa(String.fromCharCode(...buf));
}

async function hashPassword(password, salt){
  const enc = new TextEncoder();
  const data = enc.encode(salt + password);
  const h = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(h)).map(b=>b.toString(16).padStart(2,'0')).join('');
}

function setMessage(el, text, cls=''){ el.textContent = text; el.className = cls; }

document.getElementById('signupForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const name = document.getElementById('su-name').value.trim();
  const email = document.getElementById('su-email').value.trim().toLowerCase();
  const password = document.getElementById('su-password').value;
  const confirm = document.getElementById('su-password-confirm').value;
  const adminCheck = document.getElementById('su-admin-check').checked;
  const msgEl = document.getElementById('signupMessage');
  setMessage(msgEl,'','');

  if(!name || !email || !password || !confirm){ setMessage(msgEl,'Please fill all fields.','error'); return; }
  if(password.length < 8){ setMessage(msgEl,'Password must be at least 8 characters.','error'); return; }
  if(password !== confirm){ setMessage(msgEl,'Passwords do not match.','error'); return; }

  const users = loadUsers();
  if(users[email]){ setMessage(msgEl,'An account with that email already exists.','error'); return; }

  const role = adminCheck ? 'admin' : 'staff';
  const salt = await randomSalt();
  const hash = await hashPassword(password, salt);

  users[email] = { name, salt, hash, role, created: Date.now() };
  saveUsers(users);

  setMessage(msgEl,'Account created. Redirecting to Login...','success');
  setTimeout(()=> location.href = 'admin_login.php', 700);
});
</script>
</body>
</html>
