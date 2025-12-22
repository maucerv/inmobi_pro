<?php
// Detectar credenciales desde Variables de Entorno (Render)
// Si no existen (estás en local), usa los valores por defecto.

$host = getenv('DB_HOST') ?: 'db'; // 'db' es para tu docker-compose local
$db   = getenv('DB_NAME') ?: 'inmobiliaria_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'root';
$port = getenv('DB_PORT') ?: 3306;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    // En producción no muestres el error real al usuario, registra en log
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión con el sistema. Intente más tarde.");
}
?>