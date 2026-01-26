<?php
session_start();
// Verificar sesión usando las variables correctas del login.php
if(!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit; 
}

// Incluir DB usando ruta segura
require_once __DIR__ . '/../includes/db.php';

// ESTADÍSTICAS (Manejo de errores si las tablas están vacías)
try {
    $total_props = $pdo->query("SELECT COUNT(*) FROM propiedades")->fetchColumn();
    // Usamos DATE('now') que es compatible con SQLite
    $visitas_hoy = $pdo->query("SELECT COUNT(*) FROM visitas_web WHERE fecha = DATE('now')")->fetchColumn();
    $alertas_seguridad = $pdo->query("SELECT COUNT(*) FROM logs_seguridad WHERE tipo != 'LOGIN_EXITOSO' AND fecha >= DATE('now', '-7 days')")->fetchColumn();

    // Logs Recientes
    $logs = $pdo->query("SELECT * FROM logs_seguridad ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

    // Usuarios
    $usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Si algo falla, inicializamos en 0 para que no rompa la página
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
    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#"><i class="bi bi-shield-lock-fill text-warning"></i> Panel Administrativo</a>
        <div class="text-white">
            Hola, <strong><?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Admin') ?></strong>
            <a href="logout.php" class="btn btn-danger btn-sm ms-3">Salir</a>
        </div>
    </nav>

    <div class="container my-5">
        
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase">Visitantes Hoy</h6>
                        <h2 class="display-4 fw-bold my-2"><?= $visitas_hoy ?></h2>
                        <small>IPs registradas hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-<?= $alertas_seguridad > 0 ? 'danger' : 'success' ?> shadow h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase">Alertas</h6>
                        <h2 class="display-4 fw-bold my-2"><?= $alertas_seguridad ?></h2>
                        <small>Eventos recientes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white text-dark shadow h-100">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase text-muted">Propiedades</h6>
                        <h2 class="display-4 fw-bold my-2"><?= $total_props ?></h2>
                        <a href="../propiedades.php" target="_blank" class="btn btn-sm btn-outline-dark">Ver Catálogo</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Actividad Reciente</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0 small">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Mensaje</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($logs)): ?>
                                    <tr><td colspan="3" class="text-center py-3">Sin actividad registrada</td></tr>
                                <?php else: ?>
                                    <?php foreach($logs as $log): ?>
                                    <tr>
                                        <td>
                                            <?php if(strpos($log['tipo'], 'FALLIDO') !== false): ?>
                                                <span class="badge bg-danger">ALERTA</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">INFO</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($log['mensaje']) ?></td>
                                        <td><?= date('H:i', strtotime($log['fecha'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Usuarios</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach($usuarios as $u): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-person-circle me-2"></i> 
                                    <strong><?= htmlspecialchars($u['email']) ?></strong> </div>
                                <span class="badge bg-secondary"><?= htmlspecialchars($u['rol'] ?? 'Admin') ?></span>
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