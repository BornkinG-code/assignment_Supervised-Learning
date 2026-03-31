let lastOrderCount = 0;
let ordersById = {};
const ding = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');

function toggleTheme() {
  document.body.classList.toggle('dark');
  localStorage.setItem('dt_theme', document.body.classList.contains('dark') ? 'dark' : 'light');
}

(function initTheme(){
  if (localStorage.getItem('dt_theme') === 'dark') document.body.classList.add('dark');
})();


function initSidebar() {
  const body = document.body;
  const sidebar = document.getElementById('adminSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const collapseBtn = document.getElementById('sidebarCollapseBtn');
  const openMobileBtn = document.getElementById('sidebarOpenMobile');
  const closeMobileBtn = document.getElementById('sidebarCloseMobile');
  if (!sidebar) return;

  const collapsed = localStorage.getItem('dt_sidebar') === 'collapsed';
  if (collapsed) body.classList.add('sidebar-collapsed');

  function syncCollapseButton() {
    if (!collapseBtn) return;
    collapseBtn.textContent = body.classList.contains('sidebar-collapsed') ? 'Expand' : 'Collapse';
    collapseBtn.title = body.classList.contains('sidebar-collapsed') ? 'Expand sidebar' : 'Collapse sidebar';
  }
  syncCollapseButton();

  collapseBtn?.addEventListener('click', () => {
    body.classList.toggle('sidebar-collapsed');
    localStorage.setItem('dt_sidebar', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
    syncCollapseButton();
  });

  function toggleMobile(open) {
    sidebar.classList.toggle('mobile-open', open);
    overlay?.classList.toggle('open', open);
  }

  openMobileBtn?.addEventListener('click', () => toggleMobile(true));
  closeMobileBtn?.addEventListener('click', () => toggleMobile(false));
  overlay?.addEventListener('click', () => toggleMobile(false));
}

function animateNumber(el, target, prefix = '', suffix = '') {
  const duration = 500;
  const start = Number(el.dataset.value || 0);
  const end = Number(target || 0);
  const startTime = performance.now();

  function tick(now) {
    const progress = Math.min((now - startTime) / duration, 1);
    const current = start + (end - start) * progress;
    el.textContent = `${prefix}${Math.round(current).toLocaleString()}${suffix}`;
    if (progress < 1) requestAnimationFrame(tick);
    else el.dataset.value = String(end);
  }
  requestAnimationFrame(tick);
}

async function dashboardStats() {
  const res = await fetch('../api/admin_dashboard_stats.php');
  if (res.status === 401) return location.href = 'login.php';
  const d = await res.json();
  if (!d.success) return;

  const summary = d.summary || {};
  const statData = [
    { label: 'Orders Today', value: Number(summary.total_orders || 0), type: 'count' },
    { label: 'Pending', value: Number(summary.pending || 0), type: 'count' },
    { label: 'Accepted', value: Number(summary.accepted || 0), type: 'count' },
    { label: 'Rejected', value: Number(summary.rejected || 0), type: 'count' },
    { label: 'Active Tables', value: Number(summary.active_tables || 0), type: 'count' },
    { label: 'Revenue', value: Number(summary.revenue || 0), type: 'money' }
  ];

  const statsCards = document.getElementById('statsCards');
  if (statsCards) {
    statsCards.innerHTML = statData.map((s, i) => `
      <article class='stat-card' style='animation-delay:${i * 70}ms'>
        <div class='label'>${s.label}</div>
        <div class='value' data-stat='${s.label}' data-value='0'>${s.type === 'money' ? '₹0' : '0'}</div>
      </article>
    `).join('');

    statData.forEach((s) => {
      const node = statsCards.querySelector(`[data-stat='${s.label}']`);
      if (!node) return;
      animateNumber(node, s.value, s.type === 'money' ? '₹' : '');
    });
  }

  const legacyStats = document.getElementById('stats');
  if (legacyStats) {
    legacyStats.innerHTML = `
      <span class='pill'>Orders: ${summary.total_orders || 0}</span>
      <span class='pill'>Revenue: ₹${Number(summary.revenue || 0).toFixed(2)}</span>
      <span class='pill'>Pending: ${summary.pending || 0}</span>
      <span class='pill'>Accepted: ${summary.accepted || 0}</span>
      <span class='pill'>Rejected: ${summary.rejected || 0}</span>`;
  }
}

function orderStatusChip(status) {
  const normalized = (status || '').toLowerCase();
  if (normalized === 'accepted') return "<span class='status-chip ok'>Accepted</span>";
  if (normalized === 'rejected') return "<span class='status-chip bad'>Rejected</span>";
  return "<span class='status-chip warn'>Pending</span>";
}

function getOrderItems(itemsText) {
  return String(itemsText || '')
    .split(',')
    .map(item => item.trim())
    .filter(Boolean);
}

function showOrderItemsModal(orderId) {
  const modal = document.getElementById('orderItemsModal');
  const itemsList = document.getElementById('orderItemsList');
  const meta = document.getElementById('orderItemsMeta');
  if (!modal || !itemsList || !meta) return;
  const order = ordersById[orderId];
  if (!order) return;

  const items = getOrderItems(order.items);
  meta.textContent = `${order.order_code} • ${order.customer_name}`;
  itemsList.innerHTML = items.length
    ? items.map(item => `<li>${item}</li>`).join('')
    : "<li class='muted'>No items found for this order.</li>";

  modal.classList.add('open');
  modal.setAttribute('aria-hidden', 'false');
}

function closeOrderItemsModal() {
  const modal = document.getElementById('orderItemsModal');
  if (!modal) return;
  modal.classList.remove('open');
  modal.setAttribute('aria-hidden', 'true');
}

async function loadOrders() {
  const search = document.getElementById('search')?.value || '';
  const status = document.getElementById('statusFilter')?.value || '';
  const res = await fetch(`../api/admin_orders.php?search=${encodeURIComponent(search)}&status=${status}`);
  const d = await res.json();
  if (!d.success) return;
  ordersById = Object.fromEntries((d.orders || []).map(order => [order.id, order]));

  const ordersList = document.getElementById('ordersList');
  if (ordersList) {
    ordersList.classList.add('loaded');

    if (!d.orders?.length) {
      ordersList.innerHTML = "<div class='panel'><h3>No orders found</h3><p class='muted'>New incoming orders will appear here instantly.</p></div>";
    } else {
      ordersList.innerHTML = d.orders.map(o => `
        <article class='order-item ${o.status === 'pending' ? 'pulse' : ''}'>
          <div>
            <div class='order-title'>${o.order_code} • ${o.customer_name}</div>
            <div class='order-sub'>${o.customer_mobile || 'No mobile'} • ${o.items || 'No items listed'}</div>
          </div>
          <div><div class='order-sub'>Table</div><strong>${o.table_name || '-'}</strong></div>
          <div><div class='order-sub'>Amount</div><strong>₹${o.total_amount}</strong></div>
          <div>${orderStatusChip(o.status)}<div class='order-sub'>${o.order_date}</div></div>
          <div class='actions'>
            ${o.status === 'pending' ? `<button class='btn' onclick='changeStatus(${o.id},"accepted")'>Accept</button><button class='btn bad' onclick='changeStatus(${o.id},"rejected")'>Reject</button>` : ''}
            <button class='btn alt' onclick='showOrderItemsModal(${o.id})'>View</button>
          </div>
        </article>
      `).join('');
    }
  }

  const tbody = document.getElementById('ordersBody');
  if (tbody) {
    tbody.innerHTML = d.orders.map(o => `<tr><td>${o.order_code}</td><td>${o.customer_name}<br>${o.customer_mobile}</td><td>${o.table_name}</td><td>${o.items || ''}</td><td>₹${o.total_amount}</td><td class='status-${o.status}'>${o.status}</td><td>${o.order_date}</td><td>${o.status === 'pending' ? `<button class='btn ok' onclick='changeStatus(${o.id},"accepted")'>Accept</button><button class='btn bad' onclick='changeStatus(${o.id},"rejected")'>Reject</button>` : ''}</td></tr>`).join('');
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
}, 4000);

initSidebar();
document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') closeOrderItemsModal();
});
document.addEventListener('click', (event) => {
  if (event.target?.id === 'orderItemsModal') closeOrderItemsModal();
});
