<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require '../includes/db.php';

// ESTADÍSTICAS
$total_props = $pdo->query("SELECT COUNT(*) FROM propiedades")->fetchColumn();
$visitas_hoy = $pdo->query("SELECT COUNT(*) FROM visitas_web WHERE fecha = DATE('now')")->fetchColumn();
$alertas_seguridad = $pdo->query("SELECT COUNT(*) FROM logs_seguridad WHERE tipo != 'LOGIN_EXITOSO' AND fecha >= DATE('now', '-7 days')")->fetchColumn();

// Obtener Logs Recientes
$logs = $pdo->query("SELECT * FROM logs_seguridad ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Obtener Usuarios
$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
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
        <a class="navbar-brand" href="#"><i class="bi bi-shield-lock-fill text-warning"></i> Security Dashboard</a>
        <div class="text-white">
            Hola, <strong><?= htmlspecialchars($_SESSION['admin_user']) ?></strong>
            <a href="logout.php" class="btn btn-danger btn-sm ms-3">Salir</a>
        </div>
    </nav>

    <div class="container my-5">
        
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-0">Visitantes Hoy</h6>
                                <h2 class="display-4 fw-bold my-2"><?= $visitas_hoy ?></h2>
                                <small>Personas reales (IPs únicas)</small>
                            </div>
                            <i class="bi bi-people-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-<?= $alertas_seguridad > 0 ? 'danger' : 'success' ?> shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-0">Nivel de Amenaza</h6>
                                <h2 class="display-4 fw-bold my-2"><?= $alertas_seguridad ?></h2>
                                <small>Eventos sospechosos (7 días)</small>
                            </div>
                            <i class="bi bi-shield-exclamation fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white text-dark shadow h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-0 text-muted">Inventario</h6>
                                <h2 class="display-4 fw-bold my-2"><?= $total_props ?></h2>
                                <a href="gestion_propiedades.php" class="btn btn-sm btn-outline-dark">Gestionar</a>
                            </div>
                            <i class="bi bi-house-door-fill fs-1 text-muted opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <h5 class="mb-0">Monitor de Seguridad (Tiempo Real)</h5>
                        <span class="badge bg-danger">Live</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0 text-small">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Mensaje</th>
                                    <th>IP</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($logs as $log): ?>
                                <tr>
                                    <td>
                                        <?php if($log['tipo'] == 'ATAQUE_BLOQUEADO'): ?>
                                            <span class="badge bg-danger">CRÍTICO</span>
                                        <?php elseif($log['tipo'] == 'LOGIN_FALLIDO'): ?>
                                            <span class="badge bg-warning text-dark">ALERTA</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">INFO</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($log['mensaje']) ?></td>
                                    <td class="font-monospace small"><?= htmlspecialchars($log['ip']) ?></td>
                                    <td class="small"><?= date('H:i:s', strtotime($log['fecha'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Usuarios del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach($usuarios as $u): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-person-circle me-2"></i> 
                                    <strong><?= htmlspecialchars($u['usuario']) ?></strong>
                                </div>
                                <span class="badge bg-secondary rounded-pill"><?= $u['rol'] ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <hr>
                        <h6>Agregar Admin</h6>
                        <form action="crear_usuario.php" method="POST">
                            <div class="input-group mb-2">
                                <input type="text" name="nuevo_user" class="form-control form-control-sm" placeholder="Usuario" required>
                                <input type="password" name="nuevo_pass" class="form-control form-control-sm" placeholder="Pass" required>
                            </div>
                            <button class="btn btn-dark btn-sm w-100">Crear Acceso</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="gestion_propiedades.php" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed">
                <i class="bi bi-pencil-square"></i> IR A GESTIÓN DE PROPIEDADES
            </a>
        </div>

    </div>
</body>
</html>