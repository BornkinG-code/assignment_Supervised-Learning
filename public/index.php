<?php
$tableId = (int) ($_GET['table_id'] ?? 0);
?>
<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1, viewport-fit=cover'>
  <title>DigitalTable — Order</title>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
  <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
  <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
  <link rel='stylesheet' href='../assets/css/style.css'>
</head>
<body class='customer-body'>
  <div id='landingScreen' class='landing-screen'>
    <div class='landing-hero fade-up'>
      <p class='eyebrow'>Welcome to</p>
      <h1 id='restaurantName'>DigitalTable Restaurant</h1>
      <p class='muted'>Scan. Tap. Enjoy. A premium QR ordering flow designed for speed.</p>
      <div class='row'>
        <span class='pill emerald'>Table <b id='tableName'>#<?= $tableId ?></b></span>
        <span class='status-chip live'>Live Menu</span>
      </div>
      <button class='btn cta' id='browseBtn'>Browse Menu</button>
    </div>
  </div>

  <main id='menuScreen' class='customer-app hidden'>
    <header class='menu-top glass'>
      <div>
        <h2 id='restaurantNameTop'>DigitalTable Restaurant</h2>
        <p class='muted'>Table <span id='tableNameTop'>#<?= $tableId ?></span></p>
      </div>
      <div class='row'>
        <button id='searchToggle' class='icon-btn' aria-label='Search'>🔍</button>
        <button id='cartOpenBtn' class='icon-btn cart-btn' aria-label='Open cart'>🛒 <span id='cartCountBadge' class='badge'>0</span></button>
      </div>
      <input id='menuSearch' class='input full-search hidden' placeholder='Search dishes...'>
    </header>

    <div id='categoryTabs' class='category-tabs'></div>
    <section id='menuWrap' class='menu-sections'></section>
  </main>

  <button id='stickyCartBar' class='sticky-cart hidden'>
    <span><b id='stickyCount'>0</b> items</span>
    <span>View Cart • ₹<b id='stickyTotal'>0.00</b></span>
  </button>

  <section id='cartSheet' class='cart-sheet'>
    <div class='cart-handle'></div>
    <div class='panel-head'>
      <h2>Your Cart</h2>
      <button id='cartCloseBtn' class='btn alt'>Close</button>
    </div>

    <div id='cartItems' class='cart-items'></div>

    <input type='hidden' id='gstPercent' value='5'>
    <div class='bill'>
      <p><span>Subtotal</span><strong>₹<span id='subtotal'>0.00</span></strong></p>
      <p><span>GST</span><strong>₹<span id='gstAmt'>0.00</span></strong></p>
      <p class='bill-total'><span>Total</span><strong>₹<span id='total'>0.00</span></strong></p>
    </div>

    <div class='checkout-form'>
      <input class='input' id='name' placeholder='Your name' autocomplete='name' required>
      <input class='input' id='mobile' maxlength='10' inputmode='numeric' placeholder='Mobile number' autocomplete='tel' required>
      <p class='form-error hidden' id='formError'>Please enter a valid name and 10-digit mobile number.</p>
      <button class='btn cta full' onclick='placeOrder()'>Place Order</button>
    </div>
  </section>

  <div id='sheetBackdrop' class='sheet-backdrop'></div>

  <script src='../assets/js/customer.js'></script>
</body>
</html>
