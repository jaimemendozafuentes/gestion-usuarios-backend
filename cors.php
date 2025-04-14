<?php
$allowedOrigins = array_map('trim', explode(',', $_ENV['ALLOWED_ORIGINS'] ?? ''));
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

error_log("🌐 Origin recibido: $origin");
error_log("🎯 Orígenes permitidos: " . json_encode($allowedOrigins));

if (in_array($origin, $allowedOrigins)) {
  header("Access-Control-Allow-Origin: $origin");
  header("Vary: Origin"); // importante para proxies
} else {
  // header("Access-Control-Allow-Origin: *"); // ← solo si no usas cookies/token por header
}

header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  error_log("⚙️ Petición OPTIONS respondida");
  http_response_code(200);
  exit;
}
