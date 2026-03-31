<?php
$pageTitle = 'Dashboard | Nova Admin';
$activePage = 'dashboard';
$adminBaseUrl = '/admin';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

<main class="content" id="contentArea">
  <header class="content__topbar">
    <button class="icon-btn mobile-menu-btn" id="mobileToggle" aria-label="Open navigation" type="button">☰</button>
    <h1 class="content__title">Admin Overview</h1>
  </header>

  <section class="panel-grid">
    <article class="panel">Revenue panel</article>
    <article class="panel">Active users panel</article>
    <article class="panel">Conversion panel</article>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
