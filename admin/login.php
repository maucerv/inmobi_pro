<?php
require '../includes/db.php';
require '../includes/monitor.php'; // Para usar logs de seguridad

if(isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $ip = $_SERVER['REMOTE_ADDR'];

    // Buscar usuario en DB
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$user]);
    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar Password (Hash)
    if($usuario_db && password_verify($pass, $usuario_db['password'])) {
        // LOGIN EXITOSO
        $_SESSION['admin_id'] = $usuario_db['id'];
        $_SESSION['admin_user'] = $usuario_db['usuario'];
        
        // Registrar evento positivo
        $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('LOGIN_EXITOSO', ?, ?)")
            ->execute(["Usuario $user accedió al panel", $ip]);
            
        header('Location: index.php');
        exit;
    } else {
        // LOGIN FALLIDO (Seguridad)
        $error = "Usuario o contraseña incorrectos";
        
        // Registrar evento sospechoso
        $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('LOGIN_FALLIDO', ?, ?)")
            ->execute(["Intento fallido con usuario: $user", $ip]);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#0f172a;height:100vh;display:flex;align-items:center;justify-content:center;}</style>
</head>
<body>
    <div class="card p-4 shadow-lg" style="width:350px;">
        <h4 class="text-center mb-4 text-dark">Acceso Seguro</h4>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3"><input type="text" name="user" class="form-control" placeholder="Usuario" required></div>
            <div class="mb-3"><input type="password" name="pass" class="form-control" placeholder="Contraseña" required></div>
            <button type="submit" class="btn btn-dark w-100">Entrar</button>
        </form>
    </div>
</body>
</html>