<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Table Management</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body><div class='container'><div class='header'><div class='brand'>Table Management</div><a class='btn alt' href='index.php'>Back</a></div>
<div class='card'><div class='card-body'>
<form id='tableForm'><input type='hidden' name='id' id='id'><input class='input' name='table_name' placeholder='Table name' required><select class='input' name='is_active'><option value='1'>Active</option><option value='0'>Inactive</option></select><button class='btn'>Save Table</button></form>
<table class='table'><thead><tr><th>Name</th><th>QR</th><th>Active</th><th>Actions</th></tr></thead><tbody id='rows'></tbody></table>
</div></div></div>
<script>
async function load(){const r=await fetch('../api/admin_tables.php');const d=await r.json();rows.innerHTML=d.tables.map(t=>`<tr><td>${t.table_name}</td><td>${t.qr_path?`<a href='../${t.qr_path}' download>Download QR</a>`:'-'}</td><td>${t.is_active==1?'Yes':'No'}</td><td><button class='btn alt' onclick='edit(${t.id},"${t.table_name}",${t.is_active})'>Edit</button><button class='btn bad' onclick='del(${t.id})'>Delete</button></td></tr>`).join('')}
function edit(i,n,a){id.value=i;tableForm.table_name.value=n;tableForm.is_active.value=a}
async function del(id){await fetch('../api/admin_tables.php',{method:'DELETE',body:`id=${id}`});load()}
tableForm.onsubmit=async e=>{e.preventDefault();await fetch('../api/admin_tables.php',{method:'POST',body:new FormData(tableForm)});tableForm.reset();load()}
load();
</script></body></html>
