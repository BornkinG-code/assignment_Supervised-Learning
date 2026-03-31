<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();

$daily = $pdo->query("SELECT DATE(order_date) as day, SUM(total_amount) as revenue, COUNT(*) as orders
                     FROM orders WHERE status='accepted' AND order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                     GROUP BY DATE(order_date) ORDER BY day")->fetchAll();

$monthly = $pdo->query("SELECT DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_amount) as revenue
                       FROM orders WHERE status='accepted'
                       GROUP BY DATE_FORMAT(order_date, '%Y-%m') ORDER BY month DESC LIMIT 6")->fetchAll();

$topItems = $pdo->query("SELECT item_name, SUM(qty) as sold_qty FROM order_items
                        GROUP BY item_name ORDER BY sold_qty DESC LIMIT 5")->fetchAll();

jsonResponse(['success' => true, 'daily' => $daily, 'monthly' => $monthly, 'top_items' => $topItems]);
