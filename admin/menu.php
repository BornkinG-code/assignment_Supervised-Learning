<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Menu Management</title>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
  <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
  <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body class='admin-portal'>
<div class='admin-screen'>
  <header class='admin-topbar glass'>
    <div>
      <p class='eyebrow'>Control Center</p>
      <h1>Menu Studio</h1>
    </div>
    <div class='row'>
      <a class='btn alt' href='categories.php'>Categories</a>
      <a class='btn alt' href='index.php'>Dashboard</a>
    </div>
  </header>

  <section class='admin-grid-two'>
    <article class='panel'>
      <div class='panel-head'><h2>Add / Update Menu Item</h2></div>
      <form id='menuForm' enctype='multipart/form-data' class='admin-form-grid'>
        <input type='hidden' name='id' id='id'><input type='hidden' name='existing_image' id='existing_image'>
        <input class='input' name='item_name' placeholder='Item name' required>
        <input class='input' name='description' placeholder='Description'>
        <input class='input' type='number' step='0.01' name='price' placeholder='Price' required>
        <select class='input' name='category_id' id='categorySelect' required></select>
        <select class='input' name='is_veg'><option value='1'>Veg</option><option value='0'>Non-Veg</option></select>
        <select class='input' name='is_available'><option value='1'>Available</option><option value='0'>Unavailable</option></select>
        <input class='input' type='file' name='image' accept='image/*'>
        <div class='row'>
          <button class='btn'>Save Item</button>
        </div>
      </form>
    </article>

    <article class='panel'>
      <div class='panel-head'>
        <h2>Menu Items (Category Wise)</h2>
        <span class='pill emerald'>Live Inventory</span>
      </div>
      <div id='menuGroups'></div>
    </article>
  </section>
</div>

<script>
let menuItems = [];

async function loadCategories(){
  const r = await fetch('../api/admin_categories.php');
  const d = await r.json();
  categorySelect.innerHTML = d.categories.map(c=>`<option value='${c.id}'>${c.category_name}</option>`).join('');
}

function renderMenuGroups(){
  const grouped = {};
  menuItems.forEach((i) => {
    const key = i.category_name || 'Uncategorized';
    if(!grouped[key]) grouped[key] = [];
    grouped[key].push(i);
  });

  const categories = Object.keys(grouped);
  if(!categories.length){
    menuGroups.innerHTML = '<p class="muted">No menu items yet. Create a category and add items.</p>';
    return;
  }

  menuGroups.innerHTML = categories.map((cat) => `
    <section style='margin-bottom:16px'>
      <h4>${cat}</h4>
      <table class='table'>
        <thead><tr><th>Item</th><th>Price</th><th>Type</th><th>Avail</th><th>Action</th></tr></thead>
        <tbody>
          ${grouped[cat].map(i=>`<tr>
            <td>${i.item_name}</td>
            <td>₹${i.price}</td>
            <td>${i.is_veg==1?'Veg':'Non-Veg'}</td>
            <td>${i.is_available==1?'Yes':'No'}</td>
            <td>
              <button class='btn alt' onclick='edit(${i.id})'>Edit</button>
              <button class='btn bad' onclick='delItem(${i.id})'>Delete</button>
            </td>
          </tr>`).join('')}
        </tbody>
      </table>
    </section>
  `).join('');
}

async function loadMenu(){
  const r = await fetch('../api/admin_menu.php');
  const d = await r.json();
  menuItems = d.items || [];
  renderMenuGroups();
}

function edit(id){
  const i = menuItems.find(x => +x.id === +id);
  if(!i) return;
  ['item_name','description','price','category_id','is_veg','is_available'].forEach((k)=>{
    const el = document.querySelector(`[name=${k}]`);
    if(el) el.value = i[k];
  });
  document.getElementById('id').value = i.id;
  document.getElementById('existing_image').value = i.image_path || '';
  window.scrollTo({top:0, behavior:'smooth'});
}

async function delItem(id){
  if(!confirm('Delete this menu item?')) return;
  await fetch('../api/admin_menu.php',{method:'DELETE',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`id=${id}`});
  loadMenu();
}

menuForm.onsubmit = async e => {
  e.preventDefault();
  const fd = new FormData(menuForm);
  const r = await fetch('../api/admin_menu.php',{method:'POST',body:fd});
  const d = await r.json();
  if(!d.success){ alert(d.message || 'Unable to save menu item'); return; }
  menuForm.reset();
  id.value=''; existing_image.value='';
  loadMenu();
}

loadCategories();
loadMenu();
</script>
</body></html>
