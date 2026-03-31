<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="orders_report_' . date('Ymd_His') . '.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['Order Code', 'Customer', 'Mobile', 'Table', 'Status', 'Total', 'Date']);

$stmt = $pdo->query('SELECT o.order_code,o.customer_name,o.customer_mobile,t.table_name,o.status,o.total_amount,o.order_date
                     FROM orders o JOIN tables t ON t.id=o.table_id ORDER BY o.id DESC');
while ($row = $stmt->fetch()) {
    fputcsv($out, $row);
}
fclose($out);
exit;
