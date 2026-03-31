<?php
require_once __DIR__ . '/../config/helpers.php';
$code = sanitizeString($_GET['order_code'] ?? '');
if (!$code) {
    jsonResponse(['success' => false, 'message' => 'Order code required'], 422);
}
$pdo = getDB();
$stmt = $pdo->prepare('SELECT order_code,status,total_amount,created_at FROM orders WHERE order_code = ? LIMIT 1');
$stmt->execute([$code]);
$order = $stmt->fetch();
if (!$order) {
    jsonResponse(['success' => false, 'message' => 'Order not found'], 404);
}
jsonResponse(['success' => true, 'order' => $order]);
