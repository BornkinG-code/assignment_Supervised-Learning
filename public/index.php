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
  <section class='hero'>
    <div class='header'>
      <div>
        <div class='brand'>DigitalTable</div>
        <div class='subtle'>Restaurant style online ordering</div>
      </div>
      <div class='subtle'>Table: <b id='tableName'>#<?= $tableId ?></b></div>
    </div>
    <div class='search-wrap'>
      <span>🔍</span>
      <input id='menuSearch' type='text' placeholder='Search dishes, drinks or desserts'>
    </div>
  </section>

  <div id='menuWrap'></div>

  <section class='cart' id='cartSection'>
    <div class='card'>
      <div class='card-body'>
        <h3>Your Cart</h3>
        <div id='cartItems'></div>
        <input type='hidden' id='gstPercent' value='5'>
        <p>Subtotal: ₹<span id='subtotal'>0.00</span></p>
        <p>GST: ₹<span id='gstAmt'>0.00</span></p>
        <h3>Total: ₹<span id='total'>0.00</span></h3>
        <input class='input' id='name' placeholder='Your name' required>
        <input class='input' id='mobile' maxlength='10' placeholder='Mobile number' required>
        <button class='btn btn-primary' onclick='placeOrder()'>Place Order</button>
      </div>
    </div>
  </section>
</div>

<button id='floatingCartBtn' class='floating-cart' onclick='scrollToCart()' aria-label='Open cart'>
  <span>🛒 Cart</span>
  <b id='floatingCartCount'>0</b>
  <small>₹<span id='floatingCartTotal'>0.00</span></small>
</button>

<script src='../assets/js/customer.js'></script>
</body>
</html>
