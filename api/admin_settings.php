<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $gst = gstPercent($pdo);
    jsonResponse(['success' => true, 'gst_percent' => $gst]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gst = (float) ($_POST['gst_percent'] ?? DEFAULT_GST);
    if ($gst < 0 || $gst > 100) {
        jsonResponse(['success' => false, 'message' => 'Invalid GST'], 422);
    }

    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('gst_percent', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    $stmt->execute([$gst]);
    jsonResponse(['success' => true]);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
