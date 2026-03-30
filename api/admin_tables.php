<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();
ensureDirs();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT * FROM tables ORDER BY id ASC');
    jsonResponse(['success' => true, 'tables' => $stmt->fetchAll()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = sanitizeString($_POST['table_name'] ?? '');
    $active = (int) ($_POST['is_active'] ?? 1);
    if (!$name) {
        jsonResponse(['success' => false, 'message' => 'Table name required'], 422);
    }

    if ($id) {
        $stmt = $pdo->prepare('UPDATE tables SET table_name=?, is_active=? WHERE id=?');
        $stmt->execute([$name, $active, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO tables (table_name, is_active) VALUES (?, ?)');
        $stmt->execute([$name, $active]);
        $id = (int) $pdo->lastInsertId();
    }

    $publicUrl = (BASE_URL ?: '') . '/public/index.php?table_id=' . $id;
    $qrUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($publicUrl);
    $qrImage = @file_get_contents($qrUrl);
    $qrPath = 'uploads/qrcodes/table_' . $id . '.png';
    if ($qrImage !== false) {
        file_put_contents(__DIR__ . '/../' . $qrPath, $qrImage);
        $upd = $pdo->prepare('UPDATE tables SET qr_path=? WHERE id=?');
        $upd->execute([$qrPath, $id]);
    }

    jsonResponse(['success' => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $input);
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM tables WHERE id=?');
    $stmt->execute([$id]);
    jsonResponse(['success' => true]);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
