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
  <div id='sidebarOverlay' class='sidebar-overlay'></div>
  <div class='dashboard-shell'>
    <aside id='adminSidebar' class='sidebar glass fixed-sidebar'>
      <div>
        <div class='sidebar-top'>
          <div class='brand-mark'>DT</div>
          <div class='brand-wrap'>
            <div class='brand-title'>DigitalTable</div>
            <p class='brand-subtitle'>Admin Panel</p>
          </div>
          <button id='sidebarCloseMobile' class='icon-btn sidebar-close'>✕</button>
        </div>

        <nav class='sidebar-nav'>
          <a class='nav-item active' href='index.php' title='Dashboard'><span class='nav-icon'>◫</span><span class='nav-label'>Dashboard</span></a>
          <a class='nav-item' href='index.php' title='Orders'><span class='nav-icon'>◷</span><span class='nav-label'>Orders</span><span class='mini-badge'>3</span></a>
          <a class='nav-item' href='menu.php' title='Menu Management'><span class='nav-icon'>☰</span><span class='nav-label'>Menu Management</span></a>
          <a class='nav-item' href='../api/admin_categories.php' title='Categories'><span class='nav-icon'>⌗</span><span class='nav-label'>Categories</span></a>
          <a class='nav-item' href='tables.php' title='Tables / QR'><span class='nav-icon'>⌁</span><span class='nav-label'>Tables / QR</span></a>
          <a class='nav-item' href='invoices.php' title='Reports'><span class='nav-icon'>◨</span><span class='nav-label'>Reports</span></a>
          <a class='nav-item' href='settings.php' title='Settings'><span class='nav-icon'>⚙</span><span class='nav-label'>Settings</span></a>
        </nav>
      </div>

      <div class='sidebar-footer'>
        <div class='profile-mini'>
          <div class='avatar-btn'>A</div>
          <div class='profile-text'><strong>Admin</strong><span>Restaurant Owner</span></div>
        </div>
        <div class='row'>
          <button id='sidebarCollapseBtn' class='btn alt full'>Collapse</button>
          <button class='btn alt full' onclick='toggleTheme()'>Theme</button>
        </div>
        <a class='btn bad full' href='../api/admin_logout.php'>Logout</a>
      </div>
    </aside>

    <main class='dashboard-main main-with-sidebar'>
      <header class='hero glass fade-up'>
        <div class='row'>
          <button id='sidebarOpenMobile' class='icon-btn'>☰</button>
          <div>
            <p class='eyebrow'>Welcome back</p>
            <h1>Blue Ember Bistro</h1>
            <div class='row'>
              <span class='status-chip live'>Live Service</span>
              <span class='muted'>Open now • Peak dinner prep</span>
            </div>
          </div>
        </div>
        <div class='row'>
          <a class='btn alt' href='../api/export_csv.php'>Export</a>
          <button class='icon-btn' aria-label='Notifications'>🔔</button>
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
          <div class='panel-head'><h2>Revenue Trend</h2></div>
          <canvas id='dailyChart'></canvas>
        </article>
        <article class='panel fade-up'>
          <div class='panel-head'><h2>Top Items</h2></div>
          <canvas id='topChart'></canvas>
        </article>
      </section>
    </main>
  </div>

  <dialog id='orderItemsModal' class='modal-dialog-box' aria-labelledby='orderItemsTitle'>
    <div class='panel'>
      <div class='panel-head'>
        <h2 id='orderItemsTitle'>Order Items</h2>
        <button class='icon-btn' type='button' onclick='closeOrderItemsModal()' aria-label='Close'>✕</button>
      </div>
      <p id='orderItemsMeta' class='muted'></p>
      <ul id='orderItemsList' class='order-items-list'></ul>
      <div class='row'>
        <button class='btn alt' type='button' onclick='closeOrderItemsModal()'>Close</button>
      </div>
    </div>
  </dialog>

  <script src='../assets/js/admin.js'></script>
  <script>
    let dailyChart, topChart;
    async function loadAnalytics(){
      const r=await fetch('../api/admin_analytics.php');
      const d=await r.json();
      if(!d.success) return;
      if (dailyChart) dailyChart.destroy();
      dailyChart = new Chart(document.getElementById('dailyChart'), {type:'line',data:{labels:d.daily.map(x=>x.day),datasets:[{label:'Revenue',data:d.daily.map(x=>x.revenue),borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.15)',fill:true,tension:.35}]},options:{plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(148,163,184,.2)'}}}}});
      if (topChart) topChart.destroy();
      topChart = new Chart(document.getElementById('topChart'), {type:'bar',data:{labels:d.top_items.map(x=>x.item_name),datasets:[{label:'Qty Sold',data:d.top_items.map(x=>x.sold_qty),backgroundColor:'#14b8a6',borderRadius:8}]},options:{plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(148,163,184,.2)'}}}}});
    }
    dashboardStats(); loadOrders(); loadAnalytics();
  </script>
</body>
</html>
