<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Admin Login</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body><div class='container' style='max-width:420px'><div class='card'><div class='card-body'>
<h2>DigitalTable Admin</h2>
<form id='loginForm'>
<input class='input' name='email' type='email' placeholder='Email' required>
<input class='input' name='password' type='password' placeholder='Password' required>
<p id='error' style='color:#ef4444;display:none;'></p>
<button class='btn' type='submit'>Login</button>
</form></div></div></div>
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
