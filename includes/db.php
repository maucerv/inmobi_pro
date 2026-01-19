<?php
$host = 'localhost';
$db = 'inmobiliaria_db';
$user = 'root';
$pass = ''; // En XAMPP por defecto la contraseña está vacía.

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<h3>Error de conexión:</h3> " . $e->getMessage());
}
?>