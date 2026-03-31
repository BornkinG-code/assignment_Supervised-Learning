<?php
require_once __DIR__ . '/../config/config.php';

$tableId = (int) ($_GET['table_id'] ?? 0);
if ($tableId <= 0) {
    http_response_code(400);
    exit('Invalid table_id');
}

$publicUrl = rtrim(BASE_URL ?: '', '/') . '/public/index.php?table_id=' . $tableId;
$qrUrl = 'https://quickchart.io/qr?size=300&text=' . urlencode($publicUrl);

header('Content-Type: image/png');
$img = @file_get_contents($qrUrl);
if ($img !== false) {
    echo $img;
    exit;
}

// Fallback tiny 1x1 PNG if remote QR service is unavailable.
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO5lR6QAAAAASUVORK5CYII=');
