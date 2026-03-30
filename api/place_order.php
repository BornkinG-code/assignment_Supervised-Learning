<?php
require_once __DIR__ . '/../config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$name = sanitizeString($input['name'] ?? '');
$mobile = sanitizeString($input['mobile'] ?? '');
$tableId = (int) ($input['table_id'] ?? 0);
$items = $input['items'] ?? [];

if (!$name || !$mobile || !$tableId || empty($items)) {
    jsonResponse(['success' => false, 'message' => 'Missing required fields'], 422);
}
if (!validateMobile($mobile)) {
    jsonResponse(['success' => false, 'message' => 'Invalid mobile number'], 422);
}

$pdo = getDB();
$tableStmt = $pdo->prepare('SELECT id FROM tables WHERE id = ? AND is_active = 1');
$tableStmt->execute([$tableId]);
if (!$tableStmt->fetch()) {
    jsonResponse(['success' => false, 'message' => 'Invalid table'], 404);
}

$ids = array_map(fn($i) => (int) $i['id'], $items);
$ids = array_values(array_filter($ids));
if (!$ids) {
    jsonResponse(['success' => false, 'message' => 'No valid items'], 422);
}
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id,item_name,price,is_available FROM menu_items WHERE id IN ($placeholders)");
$stmt->execute($ids);
$menuMap = [];
foreach ($stmt->fetchAll() as $m) {
    $menuMap[$m['id']] = $m;
}

$subtotal = 0;
$orderItems = [];
foreach ($items as $item) {
    $id = (int) ($item['id'] ?? 0);
    $qty = max(1, (int) ($item['qty'] ?? 1));
    if (!isset($menuMap[$id]) || (int) $menuMap[$id]['is_available'] !== 1) {
        continue;
    }
    $price = (float) $menuMap[$id]['price'];
    $lineTotal = $price * $qty;
    $subtotal += $lineTotal;
    $orderItems[] = [
        'id' => $id,
        'name' => $menuMap[$id]['item_name'],
        'qty' => $qty,
        'price' => $price,
        'line_total' => $lineTotal,
    ];
}

if (!$orderItems) {
    jsonResponse(['success' => false, 'message' => 'All selected items unavailable'], 422);
}

$gst = gstPercent($pdo);
$gstAmount = round(($subtotal * $gst) / 100, 2);
$total = round($subtotal + $gstAmount, 2);
$orderId = generateOrderId();

$pdo->beginTransaction();
try {
    $orderStmt = $pdo->prepare('INSERT INTO orders (order_code, table_id, customer_name, customer_mobile, subtotal, gst_percent, gst_amount, total_amount, status, order_date)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, "pending", NOW())');
    $orderStmt->execute([$orderId, $tableId, $name, $mobile, $subtotal, $gst, $gstAmount, $total]);
    $newOrderDbId = (int) $pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, menu_item_id, item_name, qty, unit_price, line_total)
                               VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($orderItems as $item) {
        $itemStmt->execute([$newOrderDbId, $item['id'], $item['name'], $item['qty'], $item['price'], $item['line_total']]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    jsonResponse(['success' => false, 'message' => 'Order failed'], 500);
}

jsonResponse([
    'success' => true,
    'order_code' => $orderId,
    'status' => 'pending',
]);
