<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/../includes/db.php';

// Estadísticas rápidas
$total_props = $pdo->query("SELECT COUNT(*) FROM propiedades")->fetchColumn();
$usuarios_count = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$visitas_hoy = $pdo->query("SELECT COUNT(*) FROM visitas_web WHERE fecha = DATE('now')")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">Panel Admin</a>
        <div class="text-white">
            <span class="badge bg-warning text-dark me-2"><?= ucfirst($_SESSION['admin_rol']) ?></span>
            Hola, <strong><?= htmlspecialchars($_SESSION['admin_nombre']) ?></strong>
            <a href="logout.php" class="btn btn-danger btn-sm ms-3">Salir</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow border-0">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-houses-fill display-1 text-primary mb-3"></i>
                        <h2 class="fw-bold"><?= $total_props ?> Propiedades</h2>
                        <p class="text-muted">Inventario total</p>
                        <a href="gestion_propiedades.php" class="btn btn-primary btn-lg w-100">Gestionar Inventario</a>
                    </div>
                </div>
            </div>

            <?php if($_SESSION['admin_rol'] == 'superadmin'): ?>
            <div class="col-md-6">
                <div class="card h-100 shadow border-0 bg-dark text-white">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-people-fill display-1 text-warning mb-3"></i>
                        <h2 class="fw-bold"><?= $usuarios_count ?> Usuarios</h2>
                        <p class="text-white-50">Administradores y Editores</p>
                        <a href="usuarios.php" class="btn btn-warning w-100 fw-bold">Gestionar Usuarios</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>