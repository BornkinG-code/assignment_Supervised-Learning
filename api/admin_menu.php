<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT m.*, c.category_name FROM menu_items m JOIN categories c ON c.id = m.category_id ORDER BY m.id DESC');
    jsonResponse(['success' => true, 'items' => $stmt->fetchAll()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ensureDirs();
    $id = (int) ($_POST['id'] ?? 0);
    $name = sanitizeString($_POST['item_name'] ?? '');
    $desc = sanitizeString($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $isVeg = (int) ($_POST['is_veg'] ?? 0);
    $isAvailable = (int) ($_POST['is_available'] ?? 1);

    if (!$name || !$price || !$categoryId) {
        jsonResponse(['success' => false, 'message' => 'Required fields missing'], 422);
    }

    $imagePath = $_POST['existing_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            jsonResponse(['success' => false, 'message' => 'Invalid image type'], 422);
        }
        $fileName = uniqid('menu_') . '.' . $ext;
        $target = UPLOAD_MENU_DIR . $fileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $imagePath = 'uploads/menu_images/' . $fileName;
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE menu_items SET item_name=?,description=?,price=?,category_id=?,is_veg=?,is_available=?,image_path=? WHERE id=?');
        $stmt->execute([$name, $desc, $price, $categoryId, $isVeg, $isAvailable, $imagePath, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO menu_items (item_name,description,price,category_id,is_veg,is_available,image_path) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$name, $desc, $price, $categoryId, $isVeg, $isAvailable, $imagePath]);
    }

    jsonResponse(['success' => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $input);
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM menu_items WHERE id = ?');
    $stmt->execute([$id]);
    jsonResponse(['success' => true]);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
