(() => {
  const shell = document.getElementById('adminShell');
  const desktopToggle = document.getElementById('desktopToggle');
  const mobileToggle = document.getElementById('mobileToggle');
  const closeSidebar = document.getElementById('closeSidebar');
  const overlay = document.getElementById('sidebarOverlay');

  if (!shell || !desktopToggle || !overlay) {
    return;
  }

  const navItems = document.querySelectorAll('.nav-item');
  const mobileMq = window.matchMedia('(max-width: 1024px)');

  function syncOverlayHidden() {
    const mobileOpen = shell.classList.contains('is-mobile-open');
    overlay.hidden = !mobileOpen;
  }

  function setDesktopCollapsed(collapsed) {
    shell.classList.toggle('is-collapsed', collapsed);
    desktopToggle.setAttribute('aria-expanded', String(!collapsed));
    desktopToggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
  }

  function openMobileSidebar() {
    shell.classList.add('is-mobile-open');
    document.body.style.overflow = 'hidden';
    syncOverlayHidden();
  }

  function closeMobileSidebar() {
    shell.classList.remove('is-mobile-open');
    document.body.style.overflow = '';
    syncOverlayHidden();
  }

  desktopToggle.addEventListener('click', () => {
    if (mobileMq.matches) return;
    setDesktopCollapsed(!shell.classList.contains('is-collapsed'));
  });

  if (mobileToggle) {
    mobileToggle.addEventListener('click', openMobileSidebar);
  }

  if (closeSidebar) {
    closeSidebar.addEventListener('click', closeMobileSidebar);
  }

  overlay.addEventListener('click', closeMobileSidebar);

  navItems.forEach((item) => {
    item.addEventListener('click', () => {
      navItems.forEach((node) => node.classList.remove('is-active'));
      item.classList.add('is-active');
      if (mobileMq.matches) closeMobileSidebar();
    });
  });

  mobileMq.addEventListener('change', (event) => {
    if (!event.matches) {
      closeMobileSidebar();
    }
  });

  syncOverlayHidden();
})();
