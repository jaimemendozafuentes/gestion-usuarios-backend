<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

// Cargar el archivo .env solo si está presente (en desarrollo o local)
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Comprobar si las variables de entorno necesarias están definidas
$required = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS', 'CORS_ORIGIN'];
foreach ($required as $key) {
  if (!isset($_ENV[$key])) {
    http_response_code(500);
    echo json_encode(['error' => "Falta la variable de entorno: $key"]);
    exit;
  }
}

// Solo loguea mensajes no sensibles
error_log("Conectando a la base de datos...");

try {
  // Intentamos la conexión a la base de datos usando las variables de entorno
  $pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
  );
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Log si la conexión es exitosa
  error_log("Conexión a la base de datos exitosa.");

  // 📦 Registrar nombre de la base de datos conectada
  $dbNameUsed = $pdo->query("SELECT DATABASE()")->fetchColumn();
  file_put_contents(__DIR__ . '/../registro.log', "📦 Base de datos usada: $dbNameUsed\n", FILE_APPEND);

} catch (PDOException $e) {
  // Log si hay un error de conexión
  error_log("Error de conexión a la base de datos: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Error al conectar con la base de datos.']);

  // No mostrar detalles del error en producción
  if (getenv('APP_ENV') === 'production') {
    ini_set('display_errors', 0);
    error_reporting(0);
  } else {
    ini_set('display_errors', 1); // Solo en desarrollo
    error_reporting(E_ALL);
  }
  exit;
}
