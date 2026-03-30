<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Menu Management</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body><div class='container'><div class='header'><div class='brand'>Menu Management</div><a class='btn alt' href='index.php'>Back</a></div>
<div class='card'><div class='card-body'>
<form id='menuForm' enctype='multipart/form-data'>
<input type='hidden' name='id' id='id'><input type='hidden' name='existing_image' id='existing_image'>
<input class='input' name='item_name' placeholder='Item name' required>
<input class='input' name='description' placeholder='Description'>
<input class='input' type='number' step='0.01' name='price' placeholder='Price' required>
<select class='input' name='category_id' id='categorySelect'></select>
<select class='input' name='is_veg'><option value='1'>Veg</option><option value='0'>Non-Veg</option></select>
<select class='input' name='is_available'><option value='1'>Available</option><option value='0'>Unavailable</option></select>
<input class='input' type='file' name='image' accept='image/*'>
<button class='btn'>Save Item</button>
</form>
<table class='table'><thead><tr><th>Item</th><th>Category</th><th>Price</th><th>Avail</th><th>Action</th></tr></thead><tbody id='menuRows'></tbody></table>
</div></div></div>
<script>
async function loadCategories(){const r=await fetch('../api/admin_categories.php');const d=await r.json();categorySelect.innerHTML=d.categories.map(c=>`<option value='${c.id}'>${c.category_name}</option>`).join('')}
async function loadMenu(){const r=await fetch('../api/admin_menu.php');const d=await r.json();menuRows.innerHTML=d.items.map(i=>`<tr><td>${i.item_name}</td><td>${i.category_name}</td><td>${i.price}</td><td>${i.is_available==1?'Yes':'No'}</td><td><button class='btn alt' onclick='edit(${JSON.stringify(i)})'>Edit</button><button class='btn bad' onclick='del(${i.id})'>Delete</button></td></tr>`).join('')}
function edit(i){for(const k in i){const el=document.querySelector(`[name=${k}]`); if(el)el.value=i[k]} id.value=i.id; existing_image.value=i.image_path||''}
async function del(id){await fetch('../api/admin_menu.php',{method:'DELETE',body:`id=${id}`});loadMenu()}
menuForm.onsubmit=async e=>{e.preventDefault();const fd=new FormData(menuForm);await fetch('../api/admin_menu.php',{method:'POST',body:fd});menuForm.reset();loadMenu()}
loadCategories();loadMenu();
</script></body></html>
