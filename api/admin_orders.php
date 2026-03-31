<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();
[$start, $end] = todayBounds();
$status = sanitizeString($_GET['status'] ?? '');
$search = sanitizeString($_GET['search'] ?? '');

$where = 'WHERE o.order_date BETWEEN ? AND ?';
$params = [$start, $end];
if (in_array($status, ['pending', 'accepted', 'rejected'], true)) {
    $where .= ' AND o.status = ?';
    $params[] = $status;
}
if ($search) {
    $where .= ' AND (o.order_code LIKE ? OR o.customer_name LIKE ? OR o.customer_mobile LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql = "SELECT o.id,o.order_code,o.customer_name,o.customer_mobile,o.total_amount,o.status,o.order_date,t.table_name,
               GROUP_CONCAT(CONCAT(oi.item_name, ' x', oi.qty) SEPARATOR ', ') as items
        FROM orders o
        JOIN tables t ON t.id = o.table_id
        LEFT JOIN order_items oi ON oi.order_id = o.id
        $where
        GROUP BY o.id
        ORDER BY o.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
jsonResponse(['success' => true, 'orders' => $stmt->fetchAll()]);
