<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Order Status</title>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body>
<div class='container' style='max-width:760px'>
  <div class='page-hero'>
    <h2>Track your order</h2>
    <p id='code' class='muted'></p>
  </div>
  <div class='card' style='margin-top:14px'>
    <div class='card-body'>
      <p class='muted' style='margin-top:0'>Current status</p>
      <h3 id='status'>Loading...</h3>
    </div>
  </div>
</div>
<script>
const q = new URLSearchParams(location.search);
const code = q.get('order_code');
document.getElementById('code').textContent = 'Order ID: ' + code;

async function poll() {
  const r = await fetch('../api/order_status.php?order_code=' + code);
  const d = await r.json();
  if (!d.success) return;
  const el = document.getElementById('status');
  el.textContent = 'Status: ' + d.order.status.toUpperCase();
  el.className = 'badge ' + d.order.status;
}

setInterval(poll, 3000);
poll();
</script>
</body>
</html>
