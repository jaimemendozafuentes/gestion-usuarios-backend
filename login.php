<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/cors.php';

// Incluir JWT manualmente
require_once __DIR__ . '/lib/jwt/src/JWT.php';
require_once __DIR__ . '/lib/jwt/src/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ✅ Entrada JSON
$data = json_decode(file_get_contents('php://input'), true);

// ✅ Validación simple
if (empty($data['email']) || empty($data['password'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Email y contraseña son obligatorios']);
  exit;
}

$email = $data['email'];
$password = $data['password'];

// ✅ Consulta usuario
$stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ LOG: guardar lo recibido y resultado de password_verify()
file_put_contents("debug.log", json_encode([
  'email_recibido' => $email,
  'password_recibido' => $password,
  'hash_en_bd' => $user['password'] ?? 'NO USER FOUND',
  'verifica' => isset($user['password']) ? password_verify($password, $user['password']) : 'NO PASSWORD TO VERIFY'
], JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

// ✅ Verificar credenciales
if (!$user || !password_verify($password, $user['password'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Credenciales incorrectas']);
  exit;
}

// ✅ Rehash si es necesario
if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
  $newHash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->execute([$newHash, $user['id']]);
}

// ✅ Crear JWT
$payload = [
  'sub' => $user['id'],
  'email' => $email,
  'iat' => time(),
  'exp' => time() + 3600, // 1 hora
];

$token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

// ✅ Respuesta
echo json_encode([
  'success' => true,
  'message' => 'Inicio de sesión exitoso',
  'token' => $token
]);
