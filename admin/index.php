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
<body data-page='dashboard'>
  <div id='sidebarOverlay' class='sidebar-overlay' aria-hidden='true'></div>

  <div class='dashboard-shell'>
    <!-- Sidebar: reusable for all admin pages -->
    <aside id='adminSidebar' class='admin-sidebar' aria-label='Admin navigation'>
      <header class='sidebar-header'>
        <div class='brand-mark'>DT</div>
        <div class='brand-wrap'>
          <h2 class='brand-title'>DigitalTable</h2>
          <p class='brand-subtitle'>Restaurant Admin</p>
        </div>
        <button id='sidebarCloseMobile' class='icon-btn sidebar-close' aria-label='Close menu'>✕</button>
      </header>

      <nav class='sidebar-nav' aria-label='Primary'>
        <a class='nav-item' data-nav='dashboard' href='index.php' title='Dashboard'>
          <span class='nav-icon'><svg viewBox='0 0 24 24' fill='none'><path d='M4 13h6v7H4zM14 4h6v9h-6zM14 15h6v5h-6zM4 4h6v7H4z'/></svg></span><span class='nav-label'>Dashboard</span>
        </a>
        <a class='nav-item' data-nav='orders' href='index.php' title='Live Orders'>
          <span class='nav-icon'><svg viewBox='0 0 24 24' fill='none'><path d='M12 8v5l3 2M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0'/></svg></span><span class='nav-label'>Live Orders</span><span class='mini-badge'>3</span>
        </a>
        <a class='nav-item' data-nav='categories' href='categories.php' title='Categories'>
          <span class='nav-icon'><svg viewBox='0 0 24 24' fill='none'><path d='M5 7h14M5 12h14M5 17h14'/></svg></span><span class='nav-label'>Categories</span>
        </a>
        <a class='nav-item' data-nav='menu' href='menu.php' title='Menu Items'>
          <span class='nav-icon'><svg viewBox='0 0 24 24' fill='none'><path d='M6 4h12v16H6zM9 8h6M9 12h6M9 16h4'/></svg></span><span class='nav-label'>Menu Items</span>
        </a>
        <a class='nav-item' data-nav='tables' href='tables.php' title='Tables & QR'>
          <span class='nav-icon'><svg viewBox='0 0 24 24' fill='none'><path d='M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM16 13h4v7h-7v-4'/></svg></span><span class='nav-label'>Tables & QR</span>
        </a>
        <a class='nav-item' data-nav='kitchen' href='index.php' title='Kitchen Panel'>
          <span class='nav-icon'><svg viewBox='0 0 24 24' fill='none'><path d='M4 10h16v10H4zM7 10V6a5 5 0 1 1 10 0v4'/></svg></span><span class='nav-label'>Kitchen Panel</span>
        </a>
        <a class='nav-item' data-nav='settings' href='settings.php' title='Settings'>
          <span class='nav-icon'><svg viewBox='0 0 24 24' fill='none'><path d='M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM19.4 15a1 1 0 0 0 .2 1.1l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1 1 0 0 0-1.1-.2 1 1 0 0 0-.6.9V20a2 2 0 1 1-4 0v-.2a1 1 0 0 0-.7-.9 1 1 0 0 0-1.1.2l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1 1 0 0 0 .2-1.1 1 1 0 0 0-.9-.7H4a2 2 0 1 1 0-4h.2a1 1 0 0 0 .9-.7 1 1 0 0 0-.2-1.1L4.8 8a2 2 0 1 1 2.8-2.8l.1.1a1 1 0 0 0 1.1.2h.1a1 1 0 0 0 .6-.9V4a2 2 0 1 1 4 0v.2a1 1 0 0 0 .7.9 1 1 0 0 0 1.1-.2l.1-.1A2 2 0 1 1 19.2 8l-.1.1a1 1 0 0 0-.2 1.1v.1a1 1 0 0 0 .9.6h.2a2 2 0 1 1 0 4h-.2a1 1 0 0 0-.9.7Z'/></svg></span><span class='nav-label'>Settings</span>
        </a>
      </nav>

      <footer class='sidebar-footer'>
        <button id='sidebarCollapseBtn' class='btn alt full' aria-label='Collapse sidebar'>Collapse</button>
        <button class='btn alt full' onclick='toggleTheme()'>Theme</button>
        <a class='btn bad full' href='../api/admin_logout.php'>Logout</a>
      </footer>
    </aside>

    <!-- Main content sample area -->
    <main class='dashboard-main main-with-sidebar'>
      <header class='hero glass fade-up'>
        <div class='row'>
          <button id='sidebarOpenMobile' class='icon-btn' aria-label='Open menu'>☰</button>
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
              <option value=''>All Status</option><option>pending</option><option>accepted</option><option>rejected</option>
            </select>
            <button class='btn' onclick='loadOrders()'>Apply</button>
          </div>
        </div>
        <div id='ordersList' class='orders-stack skeleton'></div>
      </section>

      <section class='dashboard-grid'>
        <article class='panel fade-up'><div class='panel-head'><h2>Revenue Trend</h2></div><canvas id='dailyChart'></canvas></article>
        <article class='panel fade-up'><div class='panel-head'><h2>Top Items</h2></div><canvas id='topChart'></canvas></article>
      </section>
    </main>
  </div>

  <script src='../assets/js/admin.js'></script>
  <script>
    let dailyChart, topChart;
    async function loadAnalytics(){
      const r=await fetch('../api/admin_analytics.php'); const d=await r.json(); if(!d.success) return;
      if (dailyChart) dailyChart.destroy();
      dailyChart = new Chart(document.getElementById('dailyChart'), {type:'line',data:{labels:d.daily.map(x=>x.day),datasets:[{label:'Revenue',data:d.daily.map(x=>x.revenue),borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.15)',fill:true,tension:.35}]},options:{plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(148,163,184,.2)'}}}}});
      if (topChart) topChart.destroy();
      topChart = new Chart(document.getElementById('topChart'), {type:'bar',data:{labels:d.top_items.map(x=>x.item_name),datasets:[{label:'Qty Sold',data:d.top_items.map(x=>x.sold_qty),backgroundColor:'#14b8a6',borderRadius:8}]},options:{plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(148,163,184,.2)'}}}}});
    }
    dashboardStats(); loadOrders(); loadAnalytics();
  </script>
</body>
</html>
