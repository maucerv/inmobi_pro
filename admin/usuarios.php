<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/../includes/db.php';

// SEGURIDAD: Solo SuperAdmin puede ver esto
if($_SESSION['admin_rol'] !== 'superadmin') {
    die("<div class='alert alert-danger m-5'>ACCESO DENEGADO: Se requieren permisos de Super Administrador. <a href='index.php'>Volver</a></div>");
}

// LOGICA: Guardar Nuevo Usuario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_usuario'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptamos
    $rol = $_POST['rol'];

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $pass, $rol]);
        $mensaje = "Usuario creado correctamente.";
    } catch(Exception $e) {
        $error = "Error: El email ya existe.";
    }
}

// LOGICA: Borrar Usuario
if(isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    // No te puedes borrar a ti mismo
    if($id_borrar != $_SESSION['admin_id']) {
        $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id_borrar]);
    }
    header('Location: usuarios.php');
    exit;
}

$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between mb-4">
            <h3><i class="bi bi-people-fill me-2"></i>Usuarios del Sistema</h3>
            <a href="index.php" class="btn btn-outline-secondary">Volver al Panel</a>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white">Nuevo Usuario</div>
                    <div class="card-body">
                        <?php if(isset($mensaje)) echo "<div class='alert alert-success small'>$mensaje</div>"; ?>
                        <?php if(isset($error)) echo "<div class='alert alert-danger small'>$error</div>"; ?>
                        
                        <form method="POST">
                            <input type="hidden" name="crear_usuario" value="1">
                            <div class="mb-2">
                                <label class="small fw-bold">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold">Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Rol</label>
                                <select name="rol" class="form-select">
                                    <option value="editor">Editor (Solo Propiedades)</option>
                                    <option value="superadmin">Super Administrador</option>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100">Crear Usuario</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($usuarios as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <?php if($u['rol'] == 'superadmin'): ?>
                                            <span class="badge bg-danger">Super Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-info text-dark">Editor</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if($u['id'] != $_SESSION['admin_id']): ?>
                                            <a href="usuarios.php?borrar=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar usuario?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <small class="text-muted">Tú</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>