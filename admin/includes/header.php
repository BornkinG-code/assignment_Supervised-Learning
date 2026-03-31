<?php
$pageTitle = $pageTitle ?? 'Admin Panel';
$adminBaseUrl = $adminBaseUrl ?? '/admin';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars($adminBaseUrl, ENT_QUOTES, 'UTF-8') ?>/assets/css/admin-sidebar.css" />
</head>
<body>
  <div class="admin-shell" id="adminShell">
