<?php
$adminBaseUrl = $adminBaseUrl ?? '/admin';
$pageScripts = $pageScripts ?? [];
?>
  </div>
  <script src="<?= htmlspecialchars($adminBaseUrl, ENT_QUOTES, 'UTF-8') ?>/assets/js/admin-sidebar.js"></script>
  <?php foreach ($pageScripts as $scriptPath): ?>
    <script src="<?= htmlspecialchars($scriptPath, ENT_QUOTES, 'UTF-8') ?>"></script>
  <?php endforeach; ?>
</body>
</html>
