<?php
$allowedOrigins = explode(',', $_ENV['ALLOWED_ORIGINS'] ?? '');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
  header("Access-Control-Allow-Origin: $origin");
}

header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}
