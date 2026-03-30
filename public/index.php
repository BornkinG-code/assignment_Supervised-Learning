<?php
$tableId = (int) ($_GET['table_id'] ?? 0);
?>
<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>
<title>DigitalTable - Order</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body>
<div class='container'>
  <div class='header'><div class='brand'>DigitalTable</div><div>Table: <b id='tableName'>#<?= $tableId ?></b></div></div>
  <div id='menuWrap'></div>
  <div class='card' style='margin-top:16px'><div class='card-body'>
    <h3>Your Cart</h3><div id='cartItems'></div>
    <input type='hidden' id='gstPercent' value='5'>
    <p>Subtotal: ₹<span id='subtotal'>0.00</span></p>
    <p>GST: ₹<span id='gstAmt'>0.00</span></p>
    <h3>Total: ₹<span id='total'>0.00</span></h3>
    <input class='input' id='name' placeholder='Your name' required>
    <input class='input' id='mobile' maxlength='10' placeholder='Mobile number' required>
    <button class='btn' onclick='placeOrder()'>Place Order</button>
  </div></div>
</div>
<script src='../assets/js/customer.js'></script>
</body></html>
