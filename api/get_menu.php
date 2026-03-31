<?php
require_once __DIR__ . '/../config/helpers.php';
$pdo = getDB();

$tableId = (int) ($_GET['table_id'] ?? 0);
$stmtTable = $pdo->prepare('SELECT id, table_name FROM tables WHERE id = ? AND is_active = 1');
$stmtTable->execute([$tableId]);
$table = $stmtTable->fetch();
if (!$table) {
    jsonResponse(['success' => false, 'message' => 'Invalid table'], 404);
}

$stmt = $pdo->query('SELECT m.id,m.item_name,m.description,m.price,m.image_path,m.is_veg,m.is_available,c.category_name
                     FROM menu_items m
                     INNER JOIN categories c ON c.id = m.category_id
                     ORDER BY c.sort_order, m.item_name');
$items = $stmt->fetchAll();

$grouped = [];
foreach ($items as $item) {
    if (!isset($grouped[$item['category_name']])) {
        $grouped[$item['category_name']] = [];
    }
    $grouped[$item['category_name']][] = $item;
}

$gst = gstPercent($pdo);
jsonResponse(['success' => true, 'table' => $table, 'menu' => $grouped, 'gst_percent' => $gst]);
