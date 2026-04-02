<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Settings</title>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
  <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
  <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body class='admin-portal'>
<div class='admin-screen'>
  <header class='admin-topbar glass'>
    <div><p class='eyebrow'>System</p><h1>Settings</h1></div>
    <a class='btn alt' href='index.php'>Dashboard</a>
  </header>

  <section class='panel'>
    <div class='panel-head'><h2>Tax Configuration</h2><span class='pill'>Global</span></div>
    <form id='f' class='row'>
      <label for='gst'>GST %</label>
      <input class='input' type='number' step='0.01' name='gst_percent' id='gst' required>
      <button class='btn'>Save</button>
    </form>
  </section>
</div>
<script>
fetch('../api/admin_settings.php').then(r=>r.json()).then(d=>gst.value=d.gst_percent||5)
f.onsubmit=async e=>{e.preventDefault();await fetch('../api/admin_settings.php',{method:'POST',body:new FormData(f)});alert('Saved')}
</script></body></html>
