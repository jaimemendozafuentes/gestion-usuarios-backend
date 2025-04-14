<?php
require_once __DIR__ . '/config/config.php';

echo json_encode([
  'env' => $_ENV['ALLOWED_ORIGINS'] ?? 'NO ENV FOUND'
]);
