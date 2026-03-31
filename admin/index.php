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
<body>
<div class='container'>
  <div class='header'>
    <div>
      <div class='brand'>DigitalTable Admin</div>
      <div class='muted'>Live operations dashboard</div>
    </div>
    <div class='row'>
      <button class='btn ghost' onclick='toggleTheme()'>Dark/Light</button>
      <a class='btn alt' href='../api/export_csv.php'>Export CSV</a>
      <a class='btn bad' href='../api/admin_logout.php'>Logout</a>
    </div>
  </div>

  <nav class='nav-pills'>
    <a class='btn alt' href='index.php'>Orders</a>
    <a class='btn alt' href='menu.php'>Menu</a>
    <a class='btn alt' href='tables.php'>Tables</a>
    <a class='btn alt' href='invoices.php'>Invoices</a>
    <a class='btn alt' href='settings.php'>Settings</a>
  </nav>

  <section id='stats' class='stat-grid'></section>

  <div class='card'>
    <div class='card-body'>
      <div class='row'>
        <input id='search' class='input' placeholder='Search order/customer/mobile'>
        <select id='statusFilter' class='input'>
          <option value=''>All Status</option>
          <option>pending</option>
          <option>accepted</option>
          <option>rejected</option>
        </select>
        <button class='btn' onclick='loadOrders()'>Apply</button>
      </div>
      <table class='table'>
        <thead>
          <tr><th>Order</th><th>Customer</th><th>Table</th><th>Items</th><th>Total</th><th>Status</th><th>Time</th><th>Actions</th></tr>
        </thead>
        <tbody id='ordersBody'></tbody>
      </table>
    </div>
  </div>

  <div class='grid' style='margin-top:14px'>
    <div class='card'><div class='card-body'><canvas id='dailyChart'></canvas></div></div>
    <div class='card'><div class='card-body'><canvas id='topChart'></canvas></div></div>
  </div>
</div>
<script src='../assets/js/admin.js'></script>
<script>
async function loadAnalytics(){
  const r=await fetch('../api/admin_analytics.php');
  const d=await r.json();
  if(!d.success)return;
  new Chart(document.getElementById('dailyChart'),{
    type:'line',
    data:{labels:d.daily.map(x=>x.day),datasets:[{label:'Revenue',data:d.daily.map(x=>x.revenue),borderColor:'#2563eb',tension:0.25}]}
  });
  new Chart(document.getElementById('topChart'),{
    type:'bar',
    data:{labels:d.top_items.map(x=>x.item_name),datasets:[{label:'Qty Sold',data:d.top_items.map(x=>x.sold_qty),backgroundColor:'#e23744'}]}
  });
}
dashboardStats();
loadOrders();
loadAnalytics();
</script>
</body>
</html>
