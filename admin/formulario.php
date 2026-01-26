<?php
// admin/formulario.php
session_start();

// 1. SEGURIDAD: Verificar que el usuario esté logueado
if(!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit; 
}

require_once __DIR__ . '/../includes/db.php';

// Variables por defecto (Vacías para crear nueva)
$p = [
    'id' => '', 'titulo' => '', 'precio' => '', 'ubicacion' => '', 
    'habitaciones' => '', 'banos' => '', 'm2' => '', 
    'descripcion' => '', 'imagen' => '', 
    'lat' => '', 'lng' => '', 'destacado' => 0,
    'tipo_operacion' => 'venta' // Valor por defecto
];

// 2. MODO EDICIÓN: Si recibimos un ID, cargamos los datos de la BD
if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if($data) { $p = $data; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editor de Propiedad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 850px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i><?= $p['id'] ? 'Editar Propiedad' : 'Nueva Publicación' ?></h4>
            <a href="gestion_propiedades.php" class="btn btn-outline-secondary btn-sm">Cancelar y Volver</a>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-4">
                
                <form action="guardar.php" method="POST">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-primary">Tipo de Operación</label>
                            <select name="tipo_operacion" class="form-select border-primary" required>
                                <option value="venta" <?= ($p['tipo_operacion'] ?? '') == 'venta' ? 'selected' : '' ?>>En Venta</option>
                                <option value="renta" <?= ($p['tipo_operacion'] ?? '') == 'renta' ? 'selected' : '' ?>>En Renta</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Título del Anuncio</label>
                            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($p['titulo']) ?>" placeholder="Ej: Casa minimalista en el centro" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Precio (MXN)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio" class="form-control" value="<?= $p['precio'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control" value="<?= htmlspecialchars($p['ubicacion']) ?>" placeholder="Ciudad, Estado o Zona" required>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded border mb-3">
                        <h6 class="fw-bold text-muted mb-3"><i class="bi bi-sliders me-1"></i>Detalles</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">Habitaciones</label>
                                <input type="number" name="habitaciones" class="form-control" value="<?= $p['habitaciones'] ?>">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">Baños</label>
                                <input type="number" step="0.5" name="banos" class="form-control" value="<?= $p['banos'] ?>">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">Metros Cuadrados (m²)</label>
                                <input type="number" name="m2" class="form-control" value="<?= $p['m2'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Principal (URL)</label>
                        <input type="url" name="imagen" class="form-control" placeholder="https://ejemplo.com/foto.jpg" value="<?= htmlspecialchars($p['imagen']) ?>" required>
                        <div class="form-text">Pega el enlace directo de la imagen (Recomendado para Render/Hosting gratuito).</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="5" placeholder="Describe los detalles de la propiedad..."><?= htmlspecialchars($p['descripcion']) ?></textarea>
                    </div>

                    <div class="accordion mb-4" id="advOptions">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMap">
                                    <i class="bi bi-geo-alt me-2"></i> Configuración de Mapa y Destacados
                                </button>
                            </h2>
                            <div id="collapseMap" class="accordion-collapse collapse" data-bs-parent="#advOptions">
                                <div class="accordion-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small">Latitud</label>
                                            <input type="text" name="lat" class="form-control form-control-sm" value="<?= $p['lat'] ?>" placeholder="Ej: 19.4326">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Longitud</label>
                                            <input type="text" name="lng" class="form-control form-control-sm" value="<?= $p['lng'] ?>" placeholder="Ej: -99.1332">
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="destacado" value="1" id="dest" <?= $p['destacado'] ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold" for="dest">Destacar propiedad en página de inicio</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 py-3 fw-bold text-uppercase">
                        <i class="bi bi-save me-2"></i>Guardar Propiedad
                    </button>
                </form>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>