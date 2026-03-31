<?php
// Update these values according to your hosting database credentials.
define('DB_HOST', 'localhost');
define('DB_NAME', 'digitaltable');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', ''); // e.g. https://yourdomain.com/digitaltable

define('UPLOAD_MENU_DIR', __DIR__ . '/../uploads/menu_images/');
define('UPLOAD_QR_DIR', __DIR__ . '/../uploads/qrcodes/');
define('INVOICE_DIR', __DIR__ . '/../invoices/');
define('DEFAULT_GST', 5.00);

date_default_timezone_set('Asia/Kolkata');
