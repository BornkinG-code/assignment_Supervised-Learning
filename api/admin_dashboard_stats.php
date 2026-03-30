<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();
[$start, $end] = todayBounds();

$summaryStmt = $pdo->prepare('SELECT COUNT(*) as total_orders,
                                     SUM(CASE WHEN status = "accepted" THEN total_amount ELSE 0 END) as revenue,
                                     SUM(status = "pending") as pending,
                                     SUM(status = "accepted") as accepted,
                                     SUM(status = "rejected") as rejected
                              FROM orders
                              WHERE order_date BETWEEN ? AND ?');
$summaryStmt->execute([$start, $end]);
$summary = $summaryStmt->fetch();

jsonResponse(['success' => true, 'summary' => $summary]);
