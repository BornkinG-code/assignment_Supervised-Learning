let lastOrderCount = 0;
const ding = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');

function toggleTheme() {
  document.body.classList.toggle('dark');
  localStorage.setItem('dt_theme', document.body.classList.contains('dark') ? 'dark' : 'light');
}

(function () {
  if (localStorage.getItem('dt_theme') === 'dark') document.body.classList.add('dark');
})();

async function dashboardStats() {
  const res = await fetch('../api/admin_dashboard_stats.php');
  if (res.status === 401) return location.href = 'login.php';
  const d = await res.json();
  if (!d.success) return;

  const s = d.summary;
  document.getElementById('stats').innerHTML = `
    <div class='stat-card'><small>Total Orders</small><strong>${s.total_orders || 0}</strong></div>
    <div class='stat-card'><small>Revenue</small><strong>₹${Number(s.revenue || 0).toFixed(2)}</strong></div>
    <div class='stat-card'><small>Pending</small><strong>${s.pending || 0}</strong></div>
    <div class='stat-card'><small>Accepted</small><strong>${s.accepted || 0}</strong></div>
    <div class='stat-card'><small>Rejected</small><strong>${s.rejected || 0}</strong></div>`;
}

async function loadOrders() {
  const search = document.getElementById('search')?.value || '';
  const status = document.getElementById('statusFilter')?.value || '';
  const res = await fetch(`../api/admin_orders.php?search=${encodeURIComponent(search)}&status=${status}`);
  const d = await res.json();
  if (!d.success) return;

  const tbody = document.getElementById('ordersBody');
  if (!tbody) return;

  tbody.innerHTML = d.orders.map((o) => `
    <tr>
      <td><strong>${o.order_code}</strong></td>
      <td>${o.customer_name}<br><span class='muted'>${o.customer_mobile}</span></td>
      <td>${o.table_name}</td>
      <td>${o.items || ''}</td>
      <td><strong>₹${o.total_amount}</strong></td>
      <td><span class='badge ${o.status}'>${o.status}</span></td>
      <td>${o.order_date}</td>
      <td>
        ${o.status === 'pending'
          ? `<button class='btn ok' onclick='changeStatus(${o.id},"accepted")'>Accept</button>
             <button class='btn bad' onclick='changeStatus(${o.id},"rejected")'>Reject</button>`
          : `<span class='muted'>No action</span>`}
      </td>
    </tr>`).join('');

  if (!d.orders.length) {
    tbody.innerHTML = `<tr><td colspan='8'><div class='empty-state'>No orders found for the applied filters.</div></td></tr>`;
  }

  if (d.orders.length > lastOrderCount && lastOrderCount !== 0) ding.play().catch(() => {});
  lastOrderCount = d.orders.length;
}

async function changeStatus(id, status) {
  await fetch('../api/update_order_status.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ order_id: id, status })
  });
  loadOrders();
  dashboardStats();
}

setInterval(() => {
  dashboardStats();
  loadOrders();
}, 3000);
