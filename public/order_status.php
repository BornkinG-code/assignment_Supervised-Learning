<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Order Status</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body><div class='container'><div class='card'><div class='card-body'>
<h2>Track your order</h2><p id='code'></p><h3 id='status'>Loading...</h3>
</div></div></div>
<script>
const q=new URLSearchParams(location.search); const code=q.get('order_code'); document.getElementById('code').textContent='Order ID: '+code;
async function poll(){const r=await fetch('../api/order_status.php?order_code='+code);const d=await r.json();if(!d.success)return;document.getElementById('status').textContent='Status: '+d.order.status.toUpperCase();document.getElementById('status').className='status-'+d.order.status}
setInterval(poll,3000); poll();
</script></body></html>
