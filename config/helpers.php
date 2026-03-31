<?php
require_once __DIR__ . '/db.php';

function jsonResponse($data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sanitizeString(?string $value): string
{
    return trim(filter_var((string) $value, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

function validateMobile(string $mobile): bool
{
    return (bool) preg_match('/^[6-9][0-9]{9}$/', $mobile);
}

function generateOrderId(): string
{
    return 'DT' . date('YmdHis') . random_int(10, 99);
}

function gstPercent(PDO $pdo): float
{
    $stmt = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'gst_percent' LIMIT 1");
    $value = $stmt->fetchColumn();
    return $value !== false ? (float) $value : DEFAULT_GST;
}

function adminAuthRequired(): void
{
    session_start();
    if (empty($_SESSION['admin_id'])) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }
}

function todayBounds(): array
{
    return [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')];
}

function ensureDirs(): void
{
    foreach ([UPLOAD_MENU_DIR, UPLOAD_QR_DIR, INVOICE_DIR] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }
}
