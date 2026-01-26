<?php
session_start();
// Verificar sesión
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

// Conexión segura
require_once __DIR__ . '/../includes/db.php';

// Obtener propiedades
$props = $pdo->query("SELECT * FROM propiedades ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Propiedades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="bi bi-houses-fill me-2"></i>Inventario de Propiedades</h3>
            <div>
                <a href="index.php" class="btn btn-outline-secondary me-2">Volver al Panel</a>
                <a href="formulario.php" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i> Nueva Propiedad</a>
            </div>
        </div>
        
        <div class="card shadow border-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Imagen</th>
                            <th>Título</th>
                            <th>Precio</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($props as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <img src="<?= htmlspecialchars($p['imagen']) ?>" width="60" height="40" style="object-fit:cover; border-radius: 4px;">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($p['titulo']) ?></td>
                            <td>$<?= number_format($p['precio']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($p['ubicacion']) ?></td>
                            <td>
                                <?php if($p['destacado']): ?>
                                    <span class="badge bg-warning text-dark">Destacado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="formulario.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="guardar.php?borrar=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </a>
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