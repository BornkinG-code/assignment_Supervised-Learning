<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Menu Management</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body>
<div class='container'>
  <div class='header'>
    <div class='brand'>Menu Management</div>
    <div class='row'>
      <a class='btn alt' href='categories.php'>Manage Categories</a>
      <a class='btn alt' href='index.php'>Back</a>
    </div>
  </div>

  <div class='card'><div class='card-body'>
    <h3>Add / Update Menu Item</h3>
    <form id='menuForm' enctype='multipart/form-data'>
      <input type='hidden' name='id' id='id'><input type='hidden' name='existing_image' id='existing_image'>
      <div class='grid'>
        <input class='input' name='item_name' placeholder='Item name' required>
        <input class='input' name='description' placeholder='Description'>
        <input class='input' type='number' step='0.01' name='price' placeholder='Price' required>
        <select class='input' name='category_id' id='categorySelect' required></select>
        <select class='input' name='is_veg'><option value='1'>Veg</option><option value='0'>Non-Veg</option></select>
        <select class='input' name='is_available'><option value='1'>Available</option><option value='0'>Unavailable</option></select>
        <input class='input' type='file' name='image' accept='image/*'>
      </div>
      <button class='btn'>Save Item</button>
    </form>
  </div></div>

  <div class='card' style='margin-top:14px'><div class='card-body'>
    <h3>Menu Items (Category Wise)</h3>
    <div id='menuGroups'></div>
  </div></div>
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
