<?php
session_start();
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require '../includes/db.php';

// Obtener estadísticas
$total_props = $pdo->query("SELECT COUNT(*) FROM propiedades")->fetchColumn();
$total_views = $pdo->query("SELECT SUM(vistas) FROM propiedades")->fetchColumn();
$props = $pdo->query("SELECT * FROM propiedades ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Inmobiliaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">Panel de Control</a>
        <div>
            <a href="../index.php" target="_blank" class="btn btn-outline-light btn-sm me-2">Ver Sitio Web</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Salir</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Propiedades Activas</h5>
                        <p class="card-text display-4 fw-bold"><?= $total_props ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Visitas Totales</h5>
                        <p class="card-text display-4 fw-bold"><?= $total_views ? $total_views : 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                <h5 class="mb-0">Inventario de Propiedades</h5>
                <a href="formulario.php" class="btn btn-dark"><i class="bi bi-plus-lg"></i> Nueva Propiedad</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Imagen</th>
                            <th>Título</th>
                            <th>Precio</th>
                            <th>Ubicación</th>
                            <th class="text-center">Vistas</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($props as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <img src="<?= $p['imagen'] ?>" class="rounded" width="50" height="50" style="object-fit:cover;">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($p['titulo']) ?></td>
                            <td>$<?= number_format($p['precio']) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($p['ubicacion']) ?></td>
                            <td class="text-center"><span class="badge bg-info text-dark"><?= $p['vistas'] ?></span></td>
                            <td class="text-end pe-4">
                                <a href="formulario.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <a href="guardar.php?borrar=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>