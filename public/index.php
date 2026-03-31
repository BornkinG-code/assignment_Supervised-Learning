<?php
$tableId = (int) ($_GET['table_id'] ?? 0);
?>
<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>DigitalTable - Order</title>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body>
<div class='container'>
  <div class='header'>
    <div>
      <div class='brand'>DigitalTable</div>
      <div class='muted'>Simple, fast ordering for your table.</div>
    </div>
    <div class='pill'>Table: <b id='tableName'>#<?= $tableId ?></b></div>
  </div>

  <section class='page-hero'>
    <h1>Browse Menu & Place Order</h1>
    <p>Pick your favourites, review your cart, and place your order in seconds.</p>
  </section>

  <div class='customer-layout' style='margin-top:16px'>
    <main>
      <div id='menuWrap'></div>
    </main>

    <aside class='order-summary'>
      <div class='card'>
        <div class='card-body'>
          <h3 class='section-title'>Your Cart</h3>
          <div id='cartItems'></div>

          <input type='hidden' id='gstPercent' value='5'>
          <div class='total-row'><span>Subtotal</span><strong>₹<span id='subtotal'>0.00</span></strong></div>
          <div class='total-row'><span>GST</span><strong>₹<span id='gstAmt'>0.00</span></strong></div>
          <div class='total-row grand'><span>Total</span><strong>₹<span id='total'>0.00</span></strong></div>

          <input class='input' id='name' placeholder='Your name' required>
          <input class='input' id='mobile' maxlength='10' placeholder='Mobile number' required>
          <button class='btn' style='width:100%' onclick='placeOrder()'>Place Order</button>
        </div>
      </div>
    </aside>
  </div>
</div>
<script src='../assets/js/customer.js'></script>
</body>
</html>
