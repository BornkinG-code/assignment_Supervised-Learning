<?php
$pageTitle = 'Orders | Nova Admin';
$activePage = 'orders';
$adminBaseUrl = '/admin';
$pageScripts = [
  $adminBaseUrl . '/assets/js/orders.js',
];

$orders = [
  [
    'id' => 'ORD-1042',
    'customer' => 'Ava Johnson',
    'total' => '$184.00',
    'status' => 'Paid',
    'items' => [
      ['name' => 'Premium Hoodie', 'qty' => 1],
      ['name' => 'Sport Water Bottle', 'qty' => 2],
    ],
  ],
  [
    'id' => 'ORD-1043',
    'customer' => 'Liam Smith',
    'total' => '$79.00',
    'status' => 'Pending',
    'items' => [
      ['name' => 'Classic T-Shirt', 'qty' => 1],
      ['name' => 'Beanie', 'qty' => 1],
      ['name' => 'Sticker Pack', 'qty' => 3],
    ],
  ],
  [
    'id' => 'ORD-1044',
    'customer' => 'Noah Williams',
    'total' => '$246.00',
    'status' => 'Paid',
    'items' => [
      ['name' => 'Wireless Mouse', 'qty' => 1],
      ['name' => 'Mechanical Keyboard', 'qty' => 1],
    ],
  ],
];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

<main class="content" id="contentArea">
  <header class="content__topbar">
    <button class="icon-btn mobile-menu-btn" id="mobileToggle" aria-label="Open navigation" type="button">☰</button>
    <h1 class="content__title">Orders</h1>
  </header>

  <section class="panel orders-panel">
    <h2 class="orders-panel__title">Recent Orders</h2>
    <div class="orders-table-wrap">
      <table class="orders-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td><?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($order['customer'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($order['total'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <button
                  class="view-order-btn"
                  type="button"
                  data-order-id="<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>"
                  data-customer="<?= htmlspecialchars($order['customer'], ENT_QUOTES, 'UTF-8') ?>"
                  data-items='<?= htmlspecialchars(json_encode($order['items']), ENT_QUOTES, 'UTF-8') ?>'
                >
                  View
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>

<div class="order-modal" id="orderModal" hidden>
  <div class="order-modal__overlay" id="orderModalOverlay"></div>
  <div class="order-modal__card" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <div class="order-modal__header">
      <h2 id="orderModalTitle">Order Items</h2>
      <button class="order-modal__close" id="orderModalClose" type="button" aria-label="Close order details">✕</button>
    </div>
    <p class="order-modal__meta" id="orderModalMeta"></p>
    <ul class="order-modal__items" id="orderModalItems"></ul>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
