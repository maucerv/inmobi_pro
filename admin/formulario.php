<?php
session_start();
// --- CORRECCIÓN IMPORTANTE ---
// Ahora verificamos 'admin_id' en lugar de 'admin'
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require '../includes/db.php';

$p = null;
// Si nos pasan un ID, buscamos los datos para EDITAR
if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= $p ? 'Editar' : 'Nueva' ?> Propiedad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <?= $p ? 'Editar Propiedad' : 'Nueva Propiedad' ?>
            </span>
            <a href="gestion_propiedades.php" class="btn btn-outline-light btn-sm">Volver</a>
        </div>
    </nav>

    <div class="container pb-5" style="max-width: 800px;">
        <div class="card shadow">
            <div class="card-body p-4">
                <form action="guardar.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $p['id'] ?? '' ?>">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Título de la Propiedad</label>
                            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($p['titulo'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio (MXN)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio" class="form-control" value="<?= $p['precio'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control" value="<?= htmlspecialchars($p['ubicacion'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Habitaciones</label>
                            <input type="number" name="habitaciones" class="form-control" value="<?= $p['habitaciones'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Baños</label>
                            <input type="number" name="banos" class="form-control" value="<?= $p['banos'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Latitud</label>
                            <input type="text" name="lat" class="form-control" value="<?= $p['lat'] ?? '' ?>" placeholder="19.4326">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Longitud</label>
                            <input type="text" name="lon" class="form-control" value="<?= $p['lon'] ?? '' ?>" placeholder="-99.1332">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Imagen Principal</label>
                            
                            <div class="mb-2">
                                <label class="small text-muted">Opción A: Pegar enlace de internet (Recomendado)</label>
                                <input type="text" name="imagen_url" class="form-control" 
                                       placeholder="https://images.unsplash.com/..." 
                                       value="<?= strpos($p['imagen'] ?? '', 'http') === 0 ? ($p['imagen'] ?? '') : '' ?>">
                            </div>

                            <div class="mb-2">
                                <label class="small text-muted">Opción B: Subir archivo (Si usas Render Gratis, esto se borra al reiniciar)</label>
                                <input type="file" name="imagen_file" class="form-control" accept="image/*">
                            </div>

                            <?php if(!empty($p['imagen'])): ?>
                                <div class="mt-2 p-2 bg-light border rounded">
                                    <small class="d-block text-muted mb-1">Imagen Actual:</small>
                                    <img src="<?= (strpos($p['imagen'], 'http') === 0) ? $p['imagen'] : '../' . $p['imagen'] ?>" 
                                         height="60" class="rounded border">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"><?= htmlspecialchars($p['descripcion'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="destacado" value="1" id="destacadoCheck" <?= ($p['destacado'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="destacadoCheck">Mostrar en la sección "Destacados" del inicio</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                            <a href="gestion_propiedades.php" class="btn btn-secondary px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">
                                <i class="bi bi-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>