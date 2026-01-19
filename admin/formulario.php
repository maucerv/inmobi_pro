<?php
session_start();
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require '../includes/db.php';

$p = null;
if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 800px;">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><?= $p ? 'Editar Propiedad' : 'Nueva Propiedad' ?></h5>
            </div>
            <div class="card-body">
                <form action="guardar.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $p['id'] ?? '' ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control" value="<?= $p['titulo'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Precio</label>
                            <input type="number" name="precio" class="form-control" value="<?= $p['precio'] ?? '' ?>" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control" value="<?= $p['ubicacion'] ?? '' ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Habitaciones</label>
                            <input type="number" name="habitaciones" class="form-control" value="<?= $p['habitaciones'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Baños</label>
                            <input type="number" name="banos" class="form-control" value="<?= $p['banos'] ?? '' ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Latitud (Mapa)</label>
                            <input type="text" name="lat" class="form-control" value="<?= $p['lat'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitud (Mapa)</label>
                            <input type="text" name="lon" class="form-control" value="<?= $p['lon'] ?? '' ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">URL de Imagen (Prioridad sobre archivo)</label>
                            <input type="text" name="imagen_url" class="form-control" value="<?= $p['imagen'] ?? '' ?>" placeholder="https://...">
                            <small class="text-muted">O sube un archivo (Ojo: en Render gratuito los archivos subidos se borran al reiniciar, usa URL externa mejor).</small>
                            <input type="file" name="imagen_file" class="form-control mt-2">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"><?= $p['descripcion'] ?? '' ?></textarea>
                        </div>
                        
                        <div class="col-12 form-check ms-3">
                            <input class="form-check-input" type="checkbox" name="destacado" value="1" <?= ($p['destacado'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label">Destacar en Inicio</label>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>