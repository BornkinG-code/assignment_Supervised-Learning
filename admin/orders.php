<?php
$pageTitle = 'Orders | Nova Admin';
$activePage = 'orders';
$adminBaseUrl = '/admin';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

<main class="content" id="contentArea">
  <header class="content__topbar">
    <button class="icon-btn mobile-menu-btn" id="mobileToggle" aria-label="Open navigation" type="button">☰</button>
    <h1 class="content__title">Orders</h1>
  </header>

  <section class="panel-grid">
    <article class="panel">Pending orders</article>
    <article class="panel">Fulfillment status</article>
    <article class="panel">Returns summary</article>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
