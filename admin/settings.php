<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Settings</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body class='admin-page'><div class='container admin-shell'>
<header class='admin-topbar card'><div><div class='brand'>Settings</div><div class='subtle'>Configure taxes and core billing preferences</div></div><a class='btn btn-ghost' href='index.php'>Dashboard</a></header>
<nav class='admin-nav card'><a class='btn btn-ghost' href='index.php'>Dashboard</a><a class='btn btn-ghost' href='menu.php'>Menu</a><a class='btn btn-ghost' href='tables.php'>Tables</a><a class='btn btn-ghost' href='invoices.php'>Invoices</a><a class='btn btn-ghost active' href='settings.php'>Settings</a></nav>
<section class='card'><div class='card-body'><form id='f' class='toolbar'><div style='min-width:220px;'><label>GST %</label><input class='input' type='number' step='0.01' name='gst_percent' id='gst' required></div><button class='btn btn-add'>Save Changes</button></form><p class='subtle' id='msg'></p></div></section>
</div>
<script>
fetch('../api/admin_settings.php').then(r=>r.json()).then(d=>gst.value=d.gst_percent||5)
f.onsubmit=async e=>{e.preventDefault();await fetch('../api/admin_settings.php',{method:'POST',body:new FormData(f)});msg.textContent='Settings saved successfully.'}
</script></body></html>
