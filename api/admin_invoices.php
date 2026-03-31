<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT i.id,i.invoice_month,i.file_path,o.order_code,o.total_amount,o.customer_name
                         FROM invoices i JOIN orders o ON o.id = i.order_id ORDER BY i.id DESC');
    jsonResponse(['success' => true, 'invoices' => $stmt->fetchAll()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $input);
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT file_path FROM invoices WHERE id=?');
    $stmt->execute([$id]);
    $path = $stmt->fetchColumn();
    if ($path && file_exists(__DIR__ . '/../' . $path)) {
        unlink(__DIR__ . '/../' . $path);
    }
    $del = $pdo->prepare('DELETE FROM invoices WHERE id=?');
    $del->execute([$id]);
    jsonResponse(['success' => true]);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
