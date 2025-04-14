<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/lib/jwt/src/JWT.php';
require_once __DIR__ . '/lib/jwt/src/Key.php';

use Firebase\JWT\JWT;

// ✅ Recibir datos JSON
$data = json_decode(file_get_contents('php://input'), true);

// ✅ Validación básica
if (!isset($data['email'], $data['password'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Email y contraseña son obligatorios']);
  exit;
}

$email = $data['email'];
$password = $data['password'];

// ✅ Validación de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['error' => 'Formato de email no válido']);
  exit;
}

// ✅ Validación de contraseña segura
if (
  strlen($password) < 8 ||
  !preg_match('/[A-Z]/', $password) ||      // al menos una mayúscula
  !preg_match('/[\W]/', $password)          // al menos un símbolo
) {
  http_response_code(400);
  echo json_encode([
    'error' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula y un símbolo.'
  ]);
  exit;
}

// ✅ Comprobar si el email ya existe
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
  http_response_code(409);
  echo json_encode(['error' => 'El email ya está registrado']);
  exit;
}

// ✅ Crear usuario
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->execute([$email, $hashedPassword]);
$userId = $pdo->lastInsertId();

// ✅ Generar token JWT
$payload = [
  'sub' => $userId,
  'email' => $email,
  'iat' => time(),
  'exp' => time() + 3600
];

$token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

// ✅ Respuesta final
echo json_encode([
  'success' => true,
  'message' => 'Usuario registrado correctamente',
  'token' => $token
]);
