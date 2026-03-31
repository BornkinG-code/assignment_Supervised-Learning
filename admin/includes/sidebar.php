<?php
$activePage = $activePage ?? 'dashboard';
$adminBaseUrl = $adminBaseUrl ?? '/admin';
?>
<aside class="sidebar" id="sidebar" aria-label="Admin navigation">
  <div class="sidebar__header">
    <button class="icon-btn sidebar__close-mobile" id="closeSidebar" aria-label="Close navigation" type="button">
      ✕
    </button>
    <a href="<?= htmlspecialchars($adminBaseUrl, ENT_QUOTES, 'UTF-8') ?>/dashboard.php" class="brand" aria-label="Dashboard home">
      <span class="brand__logo">◆</span>
      <span class="brand__text">Nova Admin</span>
    </a>
  </div>

  <nav class="sidebar__nav" aria-label="Sidebar">
    <a href="<?= htmlspecialchars($adminBaseUrl, ENT_QUOTES, 'UTF-8') ?>/dashboard.php" class="nav-item <?= $activePage === 'dashboard' ? 'is-active' : '' ?>" data-label="Dashboard">
      <span class="nav-item__icon">🏠</span>
      <span class="nav-item__label">Dashboard</span>
    </a>
    <a href="<?= htmlspecialchars($adminBaseUrl, ENT_QUOTES, 'UTF-8') ?>/orders.php" class="nav-item <?= $activePage === 'orders' ? 'is-active' : '' ?>" data-label="Orders">
      <span class="nav-item__icon">🛒</span>
      <span class="nav-item__label">Orders</span>
    </a>
    <a href="#" class="nav-item" data-label="Analytics">
      <span class="nav-item__icon">📈</span>
      <span class="nav-item__label">Analytics</span>
    </a>
    <a href="#" class="nav-item" data-label="Customers">
      <span class="nav-item__icon">👥</span>
      <span class="nav-item__label">Customers</span>
    </a>
    <a href="#" class="nav-item" data-label="Settings">
      <span class="nav-item__icon">⚙️</span>
      <span class="nav-item__label">Settings</span>
    </a>
  </nav>

  <div class="sidebar__footer">
    <button class="icon-btn collapse-btn" id="desktopToggle" aria-label="Collapse sidebar" aria-expanded="true" type="button">
      <span class="collapse-btn__icon">⇤</span>
      <span class="collapse-btn__label">Collapse</span>
    </button>
  </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay" hidden></div>
