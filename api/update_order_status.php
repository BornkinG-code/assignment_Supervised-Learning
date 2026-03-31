<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}
$input = json_decode(file_get_contents('php://input'), true);
$orderId = (int) ($input['order_id'] ?? 0);
$status = sanitizeString($input['status'] ?? '');
if (!$orderId || !in_array($status, ['accepted', 'rejected'], true)) {
    jsonResponse(['success' => false, 'message' => 'Invalid input'], 422);
}

$pdo = getDB();
$stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
$stmt->execute([$status, $orderId]);

if ($status === 'accepted') {
    ensureDirs();
    $orderStmt = $pdo->prepare('SELECT o.order_code,o.customer_name,o.customer_mobile,o.total_amount,o.order_date,t.table_name
                                FROM orders o JOIN tables t ON t.id=o.table_id WHERE o.id = ? LIMIT 1');
    $orderStmt->execute([$orderId]);
    $order = $orderStmt->fetch();
    $monthDir = INVOICE_DIR . date('Y-m', strtotime($order['order_date'])) . '/';
    if (!is_dir($monthDir)) {
        mkdir($monthDir, 0775, true);
    }
    $fileName = $order['order_code'] . '.html';
    $path = $monthDir . $fileName;
    $html = "<html><body><h2>DigitalTable Invoice</h2><p>Order: {$order['order_code']}</p><p>Customer: {$order['customer_name']} ({$order['customer_mobile']})</p><p>Table: {$order['table_name']}</p><p>Total: ₹{$order['total_amount']}</p></body></html>";
    file_put_contents($path, $html);

    $invStmt = $pdo->prepare('INSERT INTO invoices (order_id, invoice_month, file_path) VALUES (?, ?, ?)
                              ON DUPLICATE KEY UPDATE file_path = VALUES(file_path)');
    $invStmt->execute([$orderId, date('Y-m', strtotime($order['order_date'])), str_replace(__DIR__ . '/../', '', $path)]);
}

jsonResponse(['success' => true]);
