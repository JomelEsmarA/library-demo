<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Logging out</title>
  <style>
    body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:system-ui,Arial;background:#0f1724;color:#e6eef8}
    .card{background:#111827;padding:20px;border-radius:12px;width:420px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,0.6)}
    .muted{color:#94a3b8}
    .link{color:#38bdf8;text-decoration:none;font-weight:600}
  </style>
</head>
<body>
  <div class="card" role="status" aria-live="polite">
    <h2 style="margin:0 0 8px 0">Signing out</h2>
    <div id="msg" class="muted">Ending session and returning to Login page...</div>
    <div style="margin-top:12px"><a id="loginLink" class="link" href="login.php">Go to Login now</a></div>
  </div>

  <script>
    // This page is dedicated to ending the session. Keeping logic here separates concerns.
    const SESSION_KEY = 'demo_session_v1';
    function clearSession(){ sessionStorage.removeItem(SESSION_KEY); }
    clearSession();
    // show message then redirect shortly
    setTimeout(()=> location.href = 'login.php', 900);
  </script>
</body>
</html>
