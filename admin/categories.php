<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Category Management</title>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body>
  <div class='container'>
    <div class='header'>
      <div class='brand'>Category Management</div>
      <a class='btn alt' href='index.php'>Back</a>
    </div>

    <div class='card'>
      <div class='card-body'>
        <h3>Create / Update Category</h3>
        <form id='categoryForm' class='row'>
          <input type='hidden' id='cat_id' name='id'>
          <input class='input' id='cat_name' name='category_name' placeholder='Category name (e.g. Starters)' required>
          <input class='input' id='cat_sort' name='sort_order' type='number' placeholder='Sort order' value='0'>
          <button class='btn'>Save Category</button>
          <button type='button' class='btn alt' onclick='resetCategoryForm()'>Clear</button>
        </form>
      </div>
    </div>

    <div class='card' style='margin-top:14px'>
      <div class='card-body'>
        <h3>All Categories</h3>
        <table class='table'>
          <thead><tr><th>Name</th><th>Sort</th><th>Action</th></tr></thead>
          <tbody id='categoryRows'></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const categoryMap = {};

    async function loadCategories(){
      const r = await fetch('../api/admin_categories.php');
      const d = await r.json();
      if(!d.success) return;

      d.categories.forEach((c) => categoryMap[c.id] = c);
      categoryRows.innerHTML = d.categories.map(c => `
        <tr>
          <td>${c.category_name}</td>
          <td>${c.sort_order}</td>
          <td>
            <button class='btn alt' onclick='editCategory(${c.id})'>Edit</button>
            <button class='btn bad' onclick='deleteCategory(${c.id})'>Delete</button>
          </td>
        </tr>
      `).join('');
    }

    function editCategory(id){
      const c = categoryMap[id];
      if(!c) return;
      cat_id.value = c.id;
      cat_name.value = c.category_name;
      cat_sort.value = c.sort_order;
      cat_name.focus();
    }

    function resetCategoryForm(){
      categoryForm.reset();
      cat_id.value = '';
      cat_sort.value = '0';
    }

    async function deleteCategory(id){
      if(!confirm('Delete this category? Menu items under it will also be deleted.')) return;
      await fetch('../api/admin_categories.php', {method:'DELETE', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:`id=${id}`});
      loadCategories();
    }

    categoryForm.onsubmit = async (e) => {
      e.preventDefault();
      const fd = new FormData(categoryForm);
      const r = await fetch('../api/admin_categories.php', {method:'POST', body:fd});
      const d = await r.json();
      if(!d.success){ alert(d.message || 'Unable to save category'); return; }
      resetCategoryForm();
      loadCategories();
    }

    loadCategories();
  </script>
</body>
</html>
