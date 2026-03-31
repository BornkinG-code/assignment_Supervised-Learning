let lastOrderCount=0;const ding=new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');

function toggleTheme(){document.body.classList.toggle('dark');localStorage.setItem('dt_theme',document.body.classList.contains('dark')?'dark':'light')}
(function(){if(localStorage.getItem('dt_theme')==='dark')document.body.classList.add('dark')})();

async function dashboardStats(){
  const res=await fetch('../api/admin_dashboard_stats.php');if(res.status===401)return location.href='login.php';
  const d=await res.json();if(!d.success)return;
  document.getElementById('stats').innerHTML=`<span class='pill'>Orders: ${d.summary.total_orders||0}</span><span class='pill'>Revenue: ₹${Number(d.summary.revenue||0).toFixed(2)}</span><span class='pill'>Pending: ${d.summary.pending||0}</span><span class='pill'>Accepted: ${d.summary.accepted||0}</span><span class='pill'>Rejected: ${d.summary.rejected||0}</span>`;
}

async function loadOrders(){
  const search=document.getElementById('search')?.value||'';const status=document.getElementById('statusFilter')?.value||'';
  const res=await fetch(`../api/admin_orders.php?search=${encodeURIComponent(search)}&status=${status}`);const d=await res.json(); if(!d.success)return;
  const tbody=document.getElementById('ordersBody'); if(!tbody) return;
  tbody.innerHTML=d.orders.map(o=>`<tr><td data-label='Order'>${o.order_code}</td><td data-label='Customer'>${o.customer_name}<br>${o.customer_mobile}</td><td data-label='Table'>${o.table_name}</td><td data-label='Items'>${o.items||''}</td><td data-label='Total'>₹${o.total_amount}</td><td data-label='Status' class='status-${o.status}'>${o.status}</td><td data-label='Time'>${o.order_date}</td><td data-label='Actions'>${o.status==='pending'?`<button class='btn ok' onclick='changeStatus(${o.id},"accepted")'>Accept</button><button class='btn bad' onclick='changeStatus(${o.id},"rejected")'>Reject</button>`:''}</td></tr>`).join('');
  if(d.orders.length>lastOrderCount && lastOrderCount!==0){ding.play().catch(()=>{})}
  lastOrderCount=d.orders.length;
}

async function changeStatus(id,status){await fetch('../api/update_order_status.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({order_id:id,status})});loadOrders();dashboardStats();}
setInterval(()=>{dashboardStats();loadOrders();},3000);
