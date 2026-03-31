<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Settings</title>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body>
<div class='container admin-shell'>
  <header class='admin-topbar card'>
    <div><div class='brand'>Settings</div><div class='subtle'>Tax and platform preferences</div></div>
    <a class='btn btn-ghost' href='index.php'>Back to Dashboard</a>
  </header>

  <section class='card'>
    <div class='card-body'>
      <form id='f' class='toolbar'>
        <div style='min-width:220px;'>
          <label>GST %</label>
          <input class='input' type='number' step='0.01' name='gst_percent' id='gst' required>
        </div>
        <button class='btn btn-add'>Save</button>
      </form>
      <p class='subtle' id='msg'></p>
    </div>
  </section>
</div>
<script>
fetch('../api/admin_settings.php').then(r=>r.json()).then(d=>gst.value=d.gst_percent||5)
f.onsubmit=async e=>{e.preventDefault();await fetch('../api/admin_settings.php',{method:'POST',body:new FormData(f)});msg.textContent='Saved successfully'}
</script>
</body>
</html>
