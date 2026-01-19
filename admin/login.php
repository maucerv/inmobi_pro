<?php
session_start();
if(isset($_SESSION['admin'])) { header('Location: index.php'); exit; }

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    // CREDENCIALES FIJAS (Cámbialas si quieres)
    if($user === 'admin' && $pass === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Acceso denegado";
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
        <h3 class="text-center mb-4">Admin Panel</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3"><input type="text" name="user" class="form-control" placeholder="Usuario" required></div>
            <div class="mb-3"><input type="password" name="pass" class="form-control" placeholder="Contraseña" required></div>
            <button type="submit" class="btn btn-dark w-100">Entrar</button>
        </form>
    </div>
</body>
</html>