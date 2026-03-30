<?php
require_once __DIR__ . '/../config/helpers.php';
adminAuthRequired();
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY sort_order, id');
    jsonResponse(['success' => true, 'categories' => $stmt->fetchAll()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = sanitizeString($_POST['category_name'] ?? '');
    $sort = (int) ($_POST['sort_order'] ?? 0);
    if (!$name) {
        jsonResponse(['success' => false, 'message' => 'Category name required'], 422);
    }
    if ($id) {
        $stmt = $pdo->prepare('UPDATE categories SET category_name=?, sort_order=? WHERE id=?');
        $stmt->execute([$name, $sort, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO categories (category_name, sort_order) VALUES (?, ?)');
        $stmt->execute([$name, $sort]);
    }
    jsonResponse(['success' => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $input);
    $id = (int) ($input['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id=?');
    $stmt->execute([$id]);
    jsonResponse(['success' => true]);
}

jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
