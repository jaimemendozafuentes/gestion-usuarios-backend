<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/middlewares/authenticate.php';

try {
    $stmt = $pdo->query("SELECT id, email, created_at FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener usuarios',
        'error' => $e->getMessage()
    ]);
}
