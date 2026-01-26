<?php
session_start();
// Verificar sesión
if(!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit; 
}

require_once __DIR__ . '/../includes/db.php';

// ESTADÍSTICAS
try {
    $total_props = $pdo->query("SELECT COUNT(*) FROM propiedades")->fetchColumn();
    $visitas_hoy = $pdo->query("SELECT COUNT(*) FROM visitas_web WHERE fecha = DATE('now')")->fetchColumn();
    $alertas_seguridad = $pdo->query("SELECT COUNT(*) FROM logs_seguridad WHERE tipo != 'LOGIN_EXITOSO' AND fecha >= DATE('now', '-7 days')")->fetchColumn();

    // Logs Recientes
    $logs = $pdo->query("SELECT * FROM logs_seguridad ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

    // Usuarios
    $usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $total_props = 0;
    $visitas_hoy = 0;
    $alertas_seguridad = 0;
    $logs = [];
    $usuarios = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Centro de Comando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand navbar-dark bg-dark px-4 shadow-sm">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-shield-lock-fill text-warning me-2"></i>Panel Admin
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="gestion_propiedades.php">
                        <i class="bi bi-houses-fill me-1"></i>Propiedades
                    </a>
                </li>
            </ul>
            <div class="text-white">
                <small>Hola, <strong><?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Admin') ?></strong></small>
                <a href="logout.php" class="btn btn-danger btn-sm ms-3">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow h-100 border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase opacity-75">Visitantes Hoy</h6>
                            <h2 class="display-4 fw-bold my-0"><?= $visitas_hoy ?></h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-<?= $alertas_seguridad > 0 ? 'danger' : 'success' ?> shadow h-100 border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase opacity-75">Alertas (7 días)</h6>
                            <h2 class="display-4 fw-bold my-0"><?= $alertas_seguridad ?></h2>
                        </div>
                        <i class="bi bi-shield-exclamation fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-white text-dark shadow h-100 border-0 border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="card-title text-uppercase text-muted">Inventario</h6>
                                <h2 class="display-4 fw-bold my-0"><?= $total_props ?></h2>
                            </div>
                            <i class="bi bi-house-door-fill fs-1 text-warning opacity-50"></i>
                        </div>
                        <div class="d-grid">
                            <a href="gestion_propiedades.php" class="btn btn-dark">
                                <i class="bi bi-pencil-square me-2"></i>Gestionar / Agregar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-dashed border-2 bg-transparent">
                    <div class="card-body text-center py-4">
                        <h5 class="text-muted mb-3">Acciones Rápidas</h5>
                        <a href="formulario.php" class="btn btn-success btn-lg px-5 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i> Publicar Nueva Propiedad
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-activity me-2"></i>Actividad del Sistema</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 small align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Estado</th>
                                        <th>Evento</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($logs)): ?>
                                        <tr><td colspan="3" class="text-center py-4 text-muted">Sin actividad reciente</td></tr>
                                    <?php else: ?>
                                        <?php foreach($logs as $log): ?>
                                        <tr>
                                            <td>
                                                <?php if(strpos($log['tipo'], 'FALLIDO') !== false || strpos($log['tipo'], 'ELIMINACION') !== false): ?>
                                                    <span class="badge bg-danger">ALERTA</span>
                                                <?php elseif(strpos($log['tipo'], 'EDICION') !== false): ?>
                                                    <span class="badge bg-warning text-dark">EDICIÓN</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">INFO</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($log['mensaje']) ?></td>
                                            <td class="text-muted"><?= date('d/m H:i', strtotime($log['fecha'])) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i>Administradores</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach($usuarios as $u): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2 text-primary">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div class="small">
                                        <strong><?= htmlspecialchars($u['nombre'] ?? 'Usuario') ?></strong><br>
                                        <span class="text-muted"><?= htmlspecialchars($u['email']) ?></span>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($u['rol'] ?? 'Admin') ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>