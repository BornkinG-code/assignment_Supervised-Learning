<?php require_once __DIR__ . '/_auth.php'; ?>
<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>DigitalTable — Premium Dashboard</title>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
  <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
  <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
  <link rel='stylesheet' href='../assets/css/style.css'>
  <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
</head>
<body>
  <div class='dashboard-shell'>
    <aside class='sidebar glass'>
      <div>
        <div class='brand-mark'>DT</div>
        <div class='brand-title'>DigitalTable</div>
        <p class='brand-subtitle'>Restaurant Control Center</p>
      </div>
      <nav class='sidebar-nav'>
        <a class='nav-item active' href='index.php'>Orders</a>
        <a class='nav-item' href='menu.php'>Menu</a>
        <a class='nav-item' href='tables.php'>Tables</a>
        <a class='nav-item' href='invoices.php'>Invoices</a>
        <a class='nav-item' href='settings.php'>Settings</a>
      </nav>
      <div class='sidebar-footer'>
        <button class='btn alt full' onclick='toggleTheme()'>Theme</button>
        <a class='btn bad full' href='../api/admin_logout.php'>Logout</a>
      </div>
    </aside>

    <main class='dashboard-main'>
      <header class='hero glass fade-up'>
        <div>
          <p class='eyebrow'>Welcome back</p>
          <h1>Blue Ember Bistro</h1>
          <div class='row'>
            <span class='status-chip live'>Live Service</span>
            <span class='muted'>Open now • Peak dinner prep</span>
          </div>
        </div>
        <div class='row'>
          <a class='btn alt' href='../api/export_csv.php'>Export</a>
          <button class='icon-btn' aria-label='Notifications'>🔔</button>
          <button class='avatar-btn' aria-label='Admin Profile'>A</button>
        </div>
      </header>

      <section id='statsCards' class='stats-grid stagger'></section>

      <section class='panel fade-up'>
        <div class='panel-head'>
          <div>
            <h2>Live Orders</h2>
            <p class='muted'>Incoming, pending, and actionable orders in real time.</p>
          </div>
          <div class='row'>
            <input id='search' class='input' placeholder='Search order / customer / mobile'>
            <select id='statusFilter' class='input'>
              <option value=''>All Status</option>
              <option>pending</option>
              <option>accepted</option>
              <option>rejected</option>
            </select>
            <button class='btn' onclick='loadOrders()'>Apply</button>
          </div>
        </div>
        <div id='ordersList' class='orders-stack skeleton'></div>
      </section>

      <section class='dashboard-grid'>
        <article class='panel fade-up'>
          <div class='panel-head'>
            <h2>Revenue Trend</h2>
          </div>
          <canvas id='dailyChart'></canvas>
        </article>

        <article class='panel fade-up'>
          <div class='panel-head'>
            <h2>Top Items</h2>
          </div>
          <canvas id='topChart'></canvas>
        </article>
      </section>

      <section class='dashboard-grid'>
        <article class='panel fade-up'>
          <div class='panel-head'>
            <h2>Menu Management</h2>
            <span class='pill emerald'>Smart inventory</span>
          </div>
          <div class='mini-grid'>
            <div class='menu-card'>
              <div><h3>Pizza Margherita</h3><p class='muted'>Main Course</p></div>
              <div class='row'><span class='pill'>₹299</span><span class='status-chip ok'>In Stock</span></div>
            </div>
            <div class='menu-card'>
              <div><h3>Cold Brew Latte</h3><p class='muted'>Beverage</p></div>
              <div class='row'><span class='pill'>₹179</span><span class='status-chip warn'>Low</span></div>
            </div>
            <div class='menu-card'>
              <div><h3>Chocolate Soufflé</h3><p class='muted'>Dessert</p></div>
              <div class='row'><span class='pill'>₹220</span><span class='status-chip bad'>Out</span></div>
            </div>
          </div>
        </article>

        <article class='panel fade-up'>
          <div class='panel-head'>
            <h2>Tables & QR</h2>
            <a href='tables.php' class='btn alt'>Manage</a>
          </div>
          <div class='mini-grid'>
            <div class='table-card'><strong>Table 01</strong><span class='muted'>2 scans today</span><button class='btn alt'>Show QR</button></div>
            <div class='table-card'><strong>Table 02</strong><span class='muted'>5 scans today</span><button class='btn alt'>Show QR</button></div>
            <div class='table-card'><strong>Table Patio</strong><span class='muted'>1 scan today</span><button class='btn alt'>Show QR</button></div>
          </div>
        </article>
      </section>
    </main>
  </div>

  <nav class='mobile-nav glass'>
    <a class='active' href='index.php'>Orders</a>
    <a href='menu.php'>Menu</a>
    <a href='tables.php'>Tables</a>
    <a href='settings.php'>Settings</a>
  </nav>

  <script src='../assets/js/admin.js'></script>
  <script>
    let dailyChart, topChart;
    async function loadAnalytics(){
      const r=await fetch('../api/admin_analytics.php');
      const d=await r.json();
      if(!d.success) return;

      if (dailyChart) dailyChart.destroy();
      dailyChart = new Chart(document.getElementById('dailyChart'), {
        type:'line',
        data:{
          labels:d.daily.map(x=>x.day),
          datasets:[{label:'Revenue',data:d.daily.map(x=>x.revenue),borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.15)',fill:true,tension:.35}]
        },
        options:{plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(148,163,184,.2)'}}}}
      });

      if (topChart) topChart.destroy();
      topChart = new Chart(document.getElementById('topChart'), {
        type:'bar',
        data:{
          labels:d.top_items.map(x=>x.item_name),
          datasets:[{label:'Qty Sold',data:d.top_items.map(x=>x.sold_qty),backgroundColor:'#14b8a6',borderRadius:8}]
        },
        options:{plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(148,163,184,.2)'}}}}
      });
    }

    dashboardStats();
    loadOrders();
    loadAnalytics();
  </script>
</body>
</html>
