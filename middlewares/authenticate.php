<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/jwt/src/JWT.php';
require_once __DIR__ . '/../lib/jwt/src/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Obtener token del header Authorization
$headers = getallheaders(); // compatible con CLI y Apache
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
file_put_contents(__DIR__ . '/../debug_token.log', print_r($authHeader, true)); // ⚠️ TEMPORAL

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
  http_response_code(401);
  echo json_encode(['error' => 'Token no proporcionado o malformado']);
  exit;
}

$jwt = $matches[1];

try {
  $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));

  // Verificar si el token ha expirado manualmente (opcional)
  if (isset($decoded->exp) && $decoded->exp < time()) {
    http_response_code(401);
    echo json_encode(['error' => 'Token expirado']);
    exit;
  }

  // Guardar el userId para usar en el resto del script
  $userId = $decoded->sub;

  // Si quieres devolver el usuario completo (opcional)
  // $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  // $stmt->execute([$userId]);
  // $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (Exception $e) {
  http_response_code(401);
  echo json_encode(['error' => 'Token inválido: ' . $e->getMessage()]);
  exit;
}
