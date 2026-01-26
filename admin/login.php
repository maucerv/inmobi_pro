<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if(isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['user'];
    $pass = $_POST['pass'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    $login_exitoso = false;
    if ($usuario_db) {
        // Verificar pass (Hash o Texto plano para legacy)
        if (password_verify($pass, $usuario_db['password']) || $pass === $usuario_db['password']) {
            $login_exitoso = true;
        }
    }

    if($login_exitoso) {
        $_SESSION['admin_id'] = $usuario_db['id'];
        $_SESSION['admin_email'] = $usuario_db['email'];
        $_SESSION['admin_nombre'] = $usuario_db['nombre'];
        $_SESSION['admin_rol'] = $usuario_db['rol']; // IMPORTANTE: Guardamos el rol
        
        // Log
        $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('LOGIN', ?, ?)")
            ->execute(["Ingreso de: $email ({$usuario_db['rol']})", $_SERVER['REMOTE_ADDR']]);

        header('Location: index.php');
        exit;
    } else {
        $error = "Acceso denegado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#0f172a;height:100vh;display:flex;align-items:center;justify-content:center;}</style>
</head>
<body>
    <div class="card shadow-lg border-0" style="width: 350px;">
        <div class="card-body p-4">
            <h4 class="text-center mb-4 fw-bold text-dark">INICIAR SESIÓN</h4>
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger py-2 small"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="email" name="user" class="form-control" placeholder="Correo electrónico" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="pass" class="form-control" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>