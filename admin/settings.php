<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Settings</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body><div class='container'><div class='header'><div class='brand'>Settings</div><a class='btn alt' href='index.php'>Back</a></div>
<div class='card'><div class='card-body'><form id='f'><label>GST %</label><input class='input' type='number' step='0.01' name='gst_percent' id='gst' required><button class='btn'>Save</button></form></div></div></div>
<script>
fetch('../api/admin_settings.php').then(r=>r.json()).then(d=>gst.value=d.gst_percent||5)
f.onsubmit=async e=>{e.preventDefault();await fetch('../api/admin_settings.php',{method:'POST',body:new FormData(f)});alert('Saved')}
</script></body></html>
