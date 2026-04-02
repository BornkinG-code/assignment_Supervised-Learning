<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>Admin Login</title>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
  <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
  <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body class='admin-portal'>
  <div class='login-shell'>
    <div class='login-hero'>
      <p class='eyebrow'>DigitalTable Suite</p>
      <h1>Premium Restaurant Command Center</h1>
      <p>Track orders in real time, manage menu instantly, and keep service performance at peak.</p>
      <div class='row'>
        <span class='status-chip live'>Live Monitoring</span>
        <span class='pill emerald'>Smart Insights</span>
      </div>
    </div>

    <div class='panel login-card'>
      <h2>Admin Login</h2>
      <p class='muted'>Sign in to continue to your dashboard.</p>
      <form id='loginForm' class='admin-form-grid'>
        <input class='input' name='email' type='email' placeholder='Email' required>
        <input class='input' name='password' type='password' placeholder='Password' required>
        <p id='error' style='color:#ef4444;display:none;'></p>
        <button class='btn' type='submit'>Login</button>
      </form>
    </div>
  </div>
<script>
loginForm.addEventListener('submit', async (e)=>{
  e.preventDefault();
  error.style.display='none';
  const fd = new FormData(loginForm);
  const res = await fetch('../api/admin_login.php',{method:'POST',body:fd});
  const data = await res.json();
  if(data.success){
    window.location.href = 'index.php';
    return;
  }
  error.textContent = data.message || 'Login failed';
  error.style.display='block';
});
</script>
</body></html>
