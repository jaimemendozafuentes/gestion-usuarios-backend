<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/cors.php';

require_once __DIR__ . '/lib/jwt/src/JWT.php';
require_once __DIR__ . '/lib/jwt/src/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents('php://input'), true);

// Validación de los datos de entrada
if (empty($data['email']) || empty($data['password'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Email y contraseña son obligatorios']);
  exit;
}

$email = $data['email'];
$password = $data['password'];

// Consulta para obtener los datos del usuario
$stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Depuración: Puedes agregar más log si es necesario
// var_dump($user);

// Verificación de la contraseña
if (!$user || !password_verify($password, $user['password'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Credenciales incorrectas']);
  exit;
}

// Generación del JWT
$payload = [
  'sub' => $user['id'],
  'email' => $email,
  'iat' => time(),
  'exp' => time() + 3600,  // El token expirará en 1 hora
];

// Crear el token JWT
$token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

// Responder con el token JWT
echo json_encode([
  'success' => true,
  'message' => 'Inicio de sesión exitoso',
  'token' => $token
]);
exit;
