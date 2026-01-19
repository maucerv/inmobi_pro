<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require '../includes/db.php';
$props = $pdo->query("SELECT * FROM propiedades ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between mb-4">
            <h3>Gestión de Propiedades</h3>
            <div>
                <a href="index.php" class="btn btn-outline-secondary">Volver al Dashboard</a>
                <a href="formulario.php" class="btn btn-dark"><i class="bi bi-plus-lg"></i> Nueva</a>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Foto</th>
                            <th>Título</th>
                            <th>Precio</th>
                            <th>Vistas</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($props as $p): ?>
                        <tr>
                            <td class="ps-3"><img src="<?= $p['imagen'] ?>" width="50" height="40" style="object-fit:cover;" class="rounded"></td>
                            <td><?= htmlspecialchars($p['titulo']) ?></td>
                            <td>$<?= number_format($p['precio']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= $p['vistas'] ?></span></td>
                            <td class="text-end pe-3">
                                <a href="formulario.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                <a href="guardar.php?borrar=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Borrar?');"><i class="bi bi-trash"></i></a>
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