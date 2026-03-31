<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();
ensureDirs();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT id, table_name, is_active, created_at, CASE WHEN qr_path IS NULL OR qr_path='' THEN CONCAT('api/table_qr.php?table_id=', id) ELSE qr_path END AS qr_path FROM tables ORDER BY id ASC");
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

    $publicUrl = rtrim(BASE_URL ?: '', '/') . '/public/index.php?table_id=' . $id;
    $remoteQrUrl = 'https://quickchart.io/qr?size=300&text=' . urlencode($publicUrl);
    $qrPath = 'uploads/qrcodes/table_' . $id . '.png';
    $qrBinary = @file_get_contents($remoteQrUrl);
    $saved = false;
    if ($qrBinary !== false) {
        $saved = @file_put_contents(__DIR__ . '/../' . $qrPath, $qrBinary);
    }
    if ($saved === false || $saved === 0) {
        if (is_file(__DIR__ . '/../' . $qrPath) && filesize(__DIR__ . '/../' . $qrPath) === 0) {
            @unlink(__DIR__ . '/../' . $qrPath);
        }
        // fallback to dynamic QR endpoint so admin always sees a usable QR link
        $qrPath = 'api/table_qr.php?table_id=' . $id;
    }

    $upd = $pdo->prepare('UPDATE tables SET qr_path=? WHERE id=?');
    $upd->execute([$qrPath, $id]);

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
