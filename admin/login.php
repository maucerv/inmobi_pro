<?php
// admin/login.php

// 1. Iniciar sesión para poder guardar los datos del usuario
session_start();

// 2. Incluir conexión con ruta absoluta (Más seguro)
require_once __DIR__ . '/../includes/db.php';

// Si ya está logueado, mandar al index
if(isset($_SESSION['admin_id'])) { 
    header('Location: index.php'); 
    exit; 
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['user']; // El formulario envía 'user', pero lo tratamos como email
    $pass = $_POST['pass'];

    // 3. CORRECCIÓN: Buscar por 'email', no por 'usuario'
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. CORRECCIÓN: Verificar password (soporta texto plano para el admin inicial)
    $login_exitoso = false;
    
    if ($usuario_db) {
        // Opción A: Es un hash seguro (password_verify)
        if (password_verify($pass, $usuario_db['password'])) {
            $login_exitoso = true;
        } 
        // Opción B: Es texto plano (Como el usuario '123456' por defecto)
        elseif ($pass === $usuario_db['password']) {
            $login_exitoso = true;
        }
    }

    if($login_exitoso) {
        // Guardar datos en sesión
        $_SESSION['admin_id'] = $usuario_db['id'];
        $_SESSION['admin_email'] = $usuario_db['email'];
        $_SESSION['admin_nombre'] = $usuario_db['nombre'];
        
        header('Location: index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0f172a;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="card card-login shadow-lg">
        <div class="card-header bg-warning text-center py-4">
            <h4 class="mb-0 fw-bold text-dark">PRESTIGE ADMIN</h4>
        </div>
        <div class="card-body p-4 bg-white">
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger text-center small"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Email</label>
                    <input type="email" name="user" class="form-control form-control-lg" placeholder="admin@test.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Contraseña</label>
                    <input type="password" name="pass" class="form-control form-control-lg" placeholder="••••••" required>
                </div>
                <button type="submit" class="btn btn-dark w-100 btn-lg">Ingresar</button>
            </form>
            
            <div class="text-center mt-3 text-muted small">
                Credenciales por defecto:<br>
                <strong>admin@test.com</strong> / <strong>123456</strong>
            </div>
        </div>
    </div>
</body>
</html>