<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Admin Dashboard</title>
  <link rel='stylesheet' href='../assets/css/style.css'>
  <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
</head>
<body class='admin-page'>
<div class='container admin-shell'>
  <header class='admin-topbar card'>
    <div>
      <div class='brand'>DigitalTable Admin</div>
      <div class='subtle'>Professional operations panel for your restaurant</div>
    </div>
    <div class='row'>
      <button class='btn btn-ghost' onclick='toggleTheme()'>Dark/Light</button>
      <a class='btn btn-ghost' href='../api/export_csv.php'>Export CSV</a>
      <a class='btn btn-danger' href='../api/admin_logout.php'>Logout</a>
    </div>
  </header>

  <nav class='admin-nav card'>
    <a class='btn btn-ghost active' href='index.php'>Dashboard</a>
    <a class='btn btn-ghost' href='menu.php'>Menu</a>
    <a class='btn btn-ghost' href='tables.php'>Tables</a>
    <a class='btn btn-ghost' href='invoices.php'>Invoices</a>
    <a class='btn btn-ghost' href='settings.php'>Settings</a>
  </nav>

  <section id='stats' class='stats-grid'></section>

  <section class='card'>
    <div class='card-body'>
      <h3>Live Orders</h3>
      <div class='toolbar'>
        <input id='search' class='input' placeholder='Search by order code, customer, or mobile'>
        <select id='statusFilter' class='input'>
          <option value=''>All status</option>
          <option>pending</option><option>accepted</option><option>rejected</option>
        </select>
        <button class='btn btn-add' onclick='loadOrders()'>Apply Filters</button>
      </div>
      <table class='table'><thead><tr><th>Order</th><th>Customer</th><th>Table</th><th>Items</th><th>Total</th><th>Status</th><th>Time</th><th>Actions</th></tr></thead><tbody id='ordersBody'></tbody></table>
    </div>
  </section>

  <section class='grid admin-charts'>
    <div class='card'><div class='card-body'><h3>Revenue Trend</h3><canvas id='dailyChart'></canvas></div></div>
    <div class='card'><div class='card-body'><h3>Top Selling Items</h3><canvas id='topChart'></canvas></div></div>
  </section>
</div>
<script src='../assets/js/admin.js'></script>
<script>
async function loadAnalytics(){
  const r=await fetch('../api/admin_analytics.php');const d=await r.json();if(!d.success)return;
  new Chart(document.getElementById('dailyChart'),{type:'line',data:{labels:d.daily.map(x=>x.day),datasets:[{label:'Revenue',data:d.daily.map(x=>x.revenue),borderColor:'#e23744',backgroundColor:'rgba(226,55,68,0.2)',fill:true}]}});
  new Chart(document.getElementById('topChart'),{type:'bar',data:{labels:d.top_items.map(x=>x.item_name),datasets:[{label:'Qty Sold',data:d.top_items.map(x=>x.sold_qty),backgroundColor:'#ff8a97'}]}});
}
dashboardStats();loadOrders();loadAnalytics();
</script>
</body>
</html>
