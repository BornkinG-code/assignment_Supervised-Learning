<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1, viewport-fit=cover'>
  <title>DigitalTable — Order Status</title>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
  <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
  <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body class='customer-body'>
  <main class='status-wrap'>
    <section class='status-card fade-up'>
      <div id='successBurst' class='success-burst hidden'>✓</div>
      <h1>Order Status</h1>
      <p id='code' class='muted'></p>
      <div class='status-line'>
        <span id='statusDot' class='dot pending'></span>
        <h2 id='status'>Loading...</h2>
      </div>
      <p id='statusMessage' class='muted'>Checking with restaurant...</p>
      <div id='progressTrack' class='progress'><i></i></div>
      <a href='index.php?table_id=1' class='btn alt'>Order More</a>
    </section>
  </main>

  <script>
    const q = new URLSearchParams(location.search);
    const code = q.get('order_code');
    const justPlaced = q.get('just_placed') === '1';

    document.getElementById('code').textContent = 'Order ID: ' + code;

    if (justPlaced) {
      const burst = document.getElementById('successBurst');
      burst.classList.remove('hidden');
      setTimeout(() => burst.classList.add('hidden'), 1600);
    }

    function paint(status){
      const dot = document.getElementById('statusDot');
      const statusEl = document.getElementById('status');
      const msg = document.getElementById('statusMessage');
      const progress = document.querySelector('#progressTrack i');

      statusEl.textContent = status.toUpperCase();
      dot.className = 'dot ' + status;
      progress.className = status;

      if (status === 'accepted') msg.textContent = 'Your order is being prepared 🍽️';
      else if (status === 'rejected') msg.textContent = 'Please modify your order and try again.';
      else msg.textContent = 'Waiting for restaurant approval';
    }

    async function poll(){
      const r = await fetch('../api/order_status.php?order_code=' + code);
      const d = await r.json();
      if (!d.success) return;
      paint(d.order.status);
    }

    setInterval(poll, 3000);
    poll();
  </script>
</body>
</html>
