<?php
session_start();
// Verificar sesión
if(!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit; 
}

require_once __DIR__ . '/../includes/db.php';

// Definir Rol
$rol_usuario = $_SESSION['admin_rol'] ?? 'editor'; // Por defecto editor si no hay rol

// ESTADÍSTICAS GENERALES (Visibles para todos)
try {
    $total_props = $pdo->query("SELECT COUNT(*) FROM propiedades")->fetchColumn();
    $mis_props = $total_props; // Podrías filtrar por usuario si quisieras
} catch (Exception $e) { $total_props = 0; }

// --- DATOS CONFIDENCIALES (SOLO SUPER ADMIN) ---
$visitas_hoy = 0;
$alertas_seguridad = 0;
$logs = [];
$usuarios = [];

if ($rol_usuario === 'superadmin') {
    try {
        // 1. Visitas de hoy
        $visitas_hoy = $pdo->query("SELECT COUNT(*) FROM visitas_web WHERE fecha = DATE('now')")->fetchColumn();
        
        // 2. Alertas de seguridad (Ataques o logins fallidos en los últimos 7 días)
        $alertas_seguridad = $pdo->query("SELECT COUNT(*) FROM logs_seguridad WHERE (tipo LIKE '%ATAQUE%' OR tipo LIKE '%FALLIDO%') AND fecha >= DATE('now', '-7 days')")->fetchColumn();

        // 3. Últimos 10 Logs
        $logs = $pdo->query("SELECT * FROM logs_seguridad ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

        // 4. Lista de Usuarios
        $usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        // Error silencioso en stats
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel de Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand navbar-dark bg-dark px-4 shadow-sm">
        <a class="navbar-brand" href="index.php">
            <?php if($rol_usuario === 'superadmin'): ?>
                <i class="bi bi-shield-lock-fill text-danger me-2"></i>Centro de Comando
            <?php else: ?>
                <i class="bi bi-laptop text-info me-2"></i>Panel Editor
            <?php endif; ?>
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Resumen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="gestion_propiedades.php">
                        <i class="bi bi-houses-fill me-1"></i>Inventario
                    </a>
                </li>
            </ul>
            <div class="text-white d-flex align-items-center">
                <span class="badge bg-<?= $rol_usuario === 'superadmin' ? 'danger' : 'secondary' ?> me-2">
                    <?= strtoupper($rol_usuario) ?>
                </span>
                <small class="me-3">Hola, <strong><?= htmlspecialchars($_SESSION['admin_nombre']) ?></strong></small>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white border-start border-4 border-warning">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Propiedades Activas</h6>
                            <h2 class="display-5 fw-bold mb-0"><?= $total_props ?></h2>
                        </div>
                        <div>
                            <a href="gestion_propiedades.php" class="btn btn-dark btn-lg px-4">
                                <i class="bi bi-pencil-square me-2"></i>Gestionar Inventario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($rol_usuario === 'superadmin'): ?>
            <h5 class="mb-3 fw-bold text-secondary"><i class="bi bi-activity me-2"></i>Monitoreo en Tiempo Real</h5>
            
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card text-white bg-primary shadow border-0 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase opacity-75">Tráfico Hoy</h6>
                                    <h2 class="display-3 fw-bold my-0"><?= $visitas_hoy ?></h2>
                                    <small class="opacity-75">Páginas vistas</small>
                                </div>
                                <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-white bg-<?= $alertas_seguridad > 0 ? 'danger' : 'success' ?> shadow border-0 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase opacity-75">Nivel de Amenaza</h6>
                                    <h2 class="display-3 fw-bold my-0"><?= $alertas_seguridad ?></h2>
                                    <small class="opacity-75"><?= $alertas_seguridad > 0 ? 'Intentos Bloqueados (7 días)' : 'Sistema Seguro' ?></small>
                                </div>
                                <i class="bi bi-shield-check fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card shadow border-0 h-100">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-terminal me-2"></i>Log de Eventos</h6>
                            <span class="badge bg-danger animate-pulse">Live</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0 small align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Estado</th>
                                            <th>Detalle del Evento</th>
                                            <th>IP Origen</th>
                                            <th>Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($logs)): ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted">Sin eventos recientes</td></tr>
                                        <?php else: ?>
                                            <?php foreach($logs as $log): ?>
                                            <tr>
                                                <td>
                                                    <?php if(strpos($log['tipo'], 'ATAQUE') !== false): ?>
                                                        <span class="badge bg-danger">BLOQUEADO</span>
                                                    <?php elseif(strpos($log['tipo'], 'FALLIDO') !== false): ?>
                                                        <span class="badge bg-warning text-dark">ALERTA</span>
                                                    <?php elseif(strpos($log['tipo'], 'LOGIN') !== false): ?>
                                                        <span class="badge bg-info text-dark">ACCESO</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">INFO</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($log['mensaje']) ?>">
                                                    <?= htmlspecialchars($log['mensaje']) ?>
                                                </td>
                                                <td class="font-monospace"><?= htmlspecialchars($log['ip']) ?></td>
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

                <div class="col-lg-4 mb-4">
                    <div class="card shadow border-0 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i>Equipo</h6>
                            <a href="usuarios.php" class="btn btn-sm btn-outline-dark">Administrar</a>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <?php foreach($usuarios as $u): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="lh-1">
                                            <strong class="d-block small"><?= htmlspecialchars($u['nombre']) ?></strong>
                                            <small class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($u['email']) ?></small>
                                        </div>
                                    </div>
                                    <?php if($u['rol'] == 'superadmin'): ?>
                                        <span class="badge bg-danger">SuperAdmin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Editor</span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info border-0 shadow-sm">
                <i class="bi bi-info-circle-fill me-2"></i>
                Estás visualizando el panel en modo <strong>Editor</strong>. Contacta a un Super Administrador para ver reportes de seguridad.
            </div>
        <?php endif; ?>

    </div>
</body>
</html>