let lastOrderCount = 0;
const ding = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');

function toggleTheme() {
  document.body.classList.toggle('dark');
  localStorage.setItem('dt_theme', document.body.classList.contains('dark') ? 'dark' : 'light');
}

(function initTheme() {
  if (localStorage.getItem('dt_theme') === 'dark') document.body.classList.add('dark');
})();

async function dashboardStats() {
  const statsEl = document.getElementById('stats');
  if (!statsEl) return;

  const res = await fetch('../api/admin_dashboard_stats.php');
  if (res.status === 401) {
    location.href = 'login.php';
    return;
  }

  const d = await res.json();
  if (!d.success) return;

  const summary = d.summary || {};
  statsEl.innerHTML = `
    <article class='stat-card'><div class='stat-label'>Total Orders</div><div class='stat-value'>${summary.total_orders || 0}</div></article>
    <article class='stat-card'><div class='stat-label'>Revenue</div><div class='stat-value'>₹${Number(summary.revenue || 0).toFixed(2)}</div></article>
    <article class='stat-card'><div class='stat-label'>Pending</div><div class='stat-value'>${summary.pending || 0}</div></article>
    <article class='stat-card'><div class='stat-label'>Accepted</div><div class='stat-value'>${summary.accepted || 0}</div></article>
    <article class='stat-card'><div class='stat-label'>Rejected</div><div class='stat-value'>${summary.rejected || 0}</div></article>
  `;
}

async function loadOrders() {
  const tbody = document.getElementById('ordersBody');
  if (!tbody) return;

  const search = document.getElementById('search')?.value || '';
  const status = document.getElementById('statusFilter')?.value || '';

  const res = await fetch(`../api/admin_orders.php?search=${encodeURIComponent(search)}&status=${status}`);
  const d = await res.json();
  if (!d.success) return;

  tbody.innerHTML = d.orders.map((o) => `
    <tr>
      <td>${o.order_code}</td>
      <td>${o.customer_name}<br>${o.customer_mobile}</td>
      <td>${o.table_name}</td>
      <td>${o.items || ''}</td>
      <td>₹${o.total_amount}</td>
      <td class='status-${o.status}'>${o.status}</td>
      <td>${o.order_date}</td>
      <td>${o.status === 'pending' ? `<button class='btn btn-add' onclick='changeStatus(${o.id},"accepted")'>Accept</button> <button class='btn btn-danger' onclick='changeStatus(${o.id},"rejected")'>Reject</button>` : '-'}</td>
    </tr>
  `).join('');

  if (d.orders.length > lastOrderCount && lastOrderCount !== 0) ding.play().catch(() => {});
  lastOrderCount = d.orders.length;
}

async function changeStatus(id, status) {
  await fetch('../api/update_order_status.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ order_id: id, status }),
  });
  loadOrders();
  dashboardStats();
}

setInterval(() => {
  dashboardStats();
  loadOrders();
}, 4000);
