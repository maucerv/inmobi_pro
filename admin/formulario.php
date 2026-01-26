<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/../includes/db.php';

// Variables por defecto (Vacías)
$p = [
    'id' => '', 'titulo' => '', 'precio' => '', 'ubicacion' => '', 
    'habitaciones' => '', 'banos' => '', 'm2' => '', 
    'descripcion' => '', 'imagen' => '', 'lat' => '', 'lng' => '', 'destacado' => 0
];

// Si viene un ID, estamos EDITANDO -> Cargamos datos
if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editor de Propiedad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 800px;">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= $p['id'] ? 'Editar Propiedad' : 'Nueva Propiedad' ?></h5>
                <a href="gestion_propiedades.php" class="btn btn-sm btn-secondary">Cancelar</a>
            </div>
            <div class="card-body p-4">
                
                <form action="guardar.php" method="POST">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Título del Anuncio</label>
                            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($p['titulo']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Precio (MXN)</label>
                            <input type="number" name="precio" class="form-control" value="<?= $p['precio'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ubicación (Ciudad/Zona)</label>
                        <input type="text" name="ubicacion" class="form-control" value="<?= htmlspecialchars($p['ubicacion']) ?>" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Habitaciones</label>
                            <input type="number" name="habitaciones" class="form-control" value="<?= $p['habitaciones'] ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Baños</label>
                            <input type="number" step="0.5" name="banos" class="form-control" value="<?= $p['banos'] ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Metros Cuadrados (m²)</label>
                            <input type="number" name="m2" class="form-control" value="<?= $p['m2'] ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">URL de la Imagen</label>
                        <input type="url" name="imagen" class="form-control" placeholder="https://ejemplo.com/foto.jpg" value="<?= htmlspecialchars($p['imagen']) ?>" required>
                        <div class="form-text">Pega aquí el enlace directo a la imagen (Unsplash, Imgur, etc).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción Detallada</label>
                        <textarea name="descripcion" class="form-control" rows="4"><?= htmlspecialchars($p['descripcion']) ?></textarea>
                    </div>

                    <div class="p-3 bg-light rounded border mb-4">
                        <h6 class="fw-bold mb-3">Configuración Avanzada</h6>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label small">Latitud (Mapa)</label>
                                <input type="text" name="lat" class="form-control form-control-sm" value="<?= $p['lat'] ?>" placeholder="Ej: 19.4326">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small">Longitud (Mapa)</label>
                                <input type="text" name="lng" class="form-control form-control-sm" value="<?= $p['lng'] ?>" placeholder="Ej: -99.1332">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="destacado" value="1" id="dest" <?= $p['destacado'] ? 'checked' : '' ?>>
                                    <label class="form-check-label small fw-bold" for="dest">Destacado</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Guardar Cambios</button>
                </form>

            </div>
        </div>
    </div>
</body>
</html>