<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if(isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['user'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // SOLO password_verify, nada de texto plano legacy
    if ($user && password_verify($pass, $user['password'])) {
        session_regenerate_id(true); // Previene fijación de sesión
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_rol'] = $user['rol'];
        $_SESSION['admin_nombre'] = $user['nombre'];

        $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('LOGIN', ?, ?)")
            ->execute(["Ingreso: $email", $_SERVER['REMOTE_ADDR']]);

        header('Location: index.php');
        exit;
    } else {
        $error = "Credenciales inválidas.";
    }
}
// ... resto del HTML igual