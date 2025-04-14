<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/lib/jwt/src/JWT.php';
require_once __DIR__ . '/lib/jwt/src/Key.php';

use Firebase\JWT\JWT;

// Recibir datos
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// Validaciones
if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Email y contraseña son obligatorios']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['error' => 'Formato de email no válido']);
  exit;
}

if (
  strlen($password) < 8 ||
  !preg_match('/[A-Z]/', $password) ||
  !preg_match('/[\W]/', $password)
) {
  http_response_code(400);
  echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula y un símbolo.']);
  exit;
}

// Verificar si existe
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$existing = $stmt->fetch();

if ($existing) {
  http_response_code(409);
  echo json_encode(['error' => 'El email ya está registrado']);
  exit;
}

// Crear usuario
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$success = $stmt->execute([$email, $hashedPassword]);

// LOG: resultado
file_put_contents('registro.log', json_encode([
  'email' => $email,
  'success' => $success,
  'lastInsertId' => $pdo->lastInsertId(),
  'errorInfo' => $stmt->errorInfo()
], JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

if (!$success) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al registrar usuario.']);
  exit;
}

$userId = $pdo->lastInsertId();

// Crear token
$payload = [
  'sub' => $userId,
  'email' => $email,
  'iat' => time(),
  'exp' => time() + 3600
];
$token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

// Respuesta final
echo json_encode([
  'success' => true,
  'message' => 'Usuario registrado correctamente',
  'token' => $token,
  'user_id' => $userId
]);
