<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Table Management</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body><div class='container'><div class='header'><div class='brand'>Table Management</div><a class='btn alt' href='index.php'>Back</a></div>
<div class='card'><div class='card-body'>
<form id='tableForm'><input type='hidden' name='id' id='id'><input class='input' name='table_name' placeholder='Table name' required><select class='input' name='is_active'><option value='1'>Active</option><option value='0'>Inactive</option></select><button class='btn'>Save Table</button></form>
<table class='table'><thead><tr><th>Name</th><th>QR</th><th>Active</th><th>Actions</th></tr></thead><tbody id='rows'></tbody></table>
</div></div></div>
<script>
let tableCache=[];
async function load(){
  const r=await fetch('../api/admin_tables.php');
  const d=await r.json();
  tableCache=d.tables||[];
  rows.innerHTML=tableCache.map(t=>`<tr>
    <td data-label='Name'>${t.table_name}</td>
    <td data-label='QR'>${t.qr_path?`<a href='../${t.qr_path}' target='_blank' download>Download QR</a>`:'<button class="btn alt" onclick="regen('+t.id+')">Generate QR</button>'}</td>
    <td data-label='Active'>${t.is_active==1?'Yes':'No'}</td>
    <td data-label='Actions'>
      <button class='btn alt' onclick='edit(${t.id})'>Edit</button>
      <button class='btn alt' onclick='regen(${t.id})'>Regenerate QR</button>
      <button class='btn bad' onclick='delTable(${t.id})'>Delete</button>
    </td>
  </tr>`).join('')
}
function edit(idVal){
  const t=tableCache.find(x=>Number(x.id)===Number(idVal));
  if(!t) return;
  id.value=t.id;
  tableForm.table_name.value=t.table_name;
  tableForm.is_active.value=t.is_active;
}
async function regen(id){
  const t=tableCache.find(x=>Number(x.id)===Number(id));
  if(!t) return;
  const fd=new FormData();
  fd.append('id', t.id); fd.append('table_name', t.table_name); fd.append('is_active', t.is_active);
  await fetch('../api/admin_tables.php',{method:'POST',body:fd});
  load();
}
async function delTable(id){await fetch('../api/admin_tables.php',{method:'DELETE',body:`id=${id}`});load()}
tableForm.onsubmit=async e=>{e.preventDefault();await fetch('../api/admin_tables.php',{method:'POST',body:new FormData(tableForm)});tableForm.reset();id.value='';load()}
load();
</script></body></html>
