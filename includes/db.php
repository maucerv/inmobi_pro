<?php
// includes/db.php
// Definimos que la base de datos es un archivo local
$db_file = __DIR__ . '/../inmobiliaria.sqlite';

try {
    // Usamos el driver de SQLite en lugar de MySQL
    $pdo = new PDO("sqlite:" . $db_file);
    
    // Configuración de errores y claves foráneas
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("PRAGMA foreign_keys = ON;");

} catch (PDOException $e) {
    die("<h3>Error de sistema:</h3> " . $e->getMessage());
}
?>