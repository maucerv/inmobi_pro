<?php 
// 1. Configuración para evitar errores visuales
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);

require_once 'includes/db.php';
include 'includes/header.php'; 

// 2. Obtener ID y sanitizar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 3. Lógica de Contador de Visitas (Auto-Reparable)
if($id > 0) {
    try {
        // Verificar si existe la columna antes de sumar
        $check = $pdo->query("PRAGMA table_info(propiedades)");
        $cols = $check->fetchAll(PDO::FETCH_COLUMN, 1);
        if(in_array('vistas', $cols)) {
            $stmt_views = $pdo->prepare("UPDATE propiedades SET vistas = vistas + 1 WHERE id = ?");
            $stmt_views->execute([$id]);
        }
    } catch (Exception $e) {
        // Silenciar error para no romper la web
    }
}

// 4. Obtener datos de la propiedad
$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
$stmt->execute([$id]);
$prop = $stmt->fetch(PDO::FETCH_ASSOC);

$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80";
?>

<div class="container my-5">
    
    <?php if($prop): 
        $imagen = !empty($prop['imagen']) ? $prop['imagen'] : $default_img;
    ?>
    
    <div class="mb-4 animate-up">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small text-uppercase fw-bold">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Inicio</a></li>
                <li class="breadcrumb-item"><a href="propiedades.php" class="text-decoration-none text-muted">Propiedades</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">Detalle #<?= $prop['id'] ?></li>
            </ol>
        </nav>
        
        <h1 class="display-4 fw-bold text-dark font-serif"><?= htmlspecialchars($prop['titulo'] ?? 'Sin Título') ?></h1>
        <p class="lead text-muted">
            <i class="bi bi-geo-alt-fill text-warning me-2"></i>
            <?= htmlspecialchars($prop['ubicacion'] ?? 'Ubicación no especificada') ?>
        </p>
    </div>

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="rounded-4 overflow-hidden shadow-sm mb-5 position-relative bg-light" style="min-height: 300px;">
                <img src="<?= htmlspecialchars($imagen) ?>" class="w-100 h-100" style="object-fit: cover;" alt="Propiedad" onerror="this.src='<?= $default_img ?>'">
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border border-light">
                <h3 class="fw-bold mb-4 font-serif">Sobre esta residencia</h3>
                <div class="text-secondary lh-lg" style="font-size: 1.05rem;">
                    <?= nl2br(htmlspecialchars($prop['descripcion'] ?? 'Sin descripción disponible.')) ?>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                <h4 class="fw-bold mb-4 font-serif">Detalles Técnicos</h4>
                <div class="row g-4 text-center">
                    <div class="col-4 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <i class="bi bi-door-open fs-3 text-primary"></i>
                            <div class="fw-bold mt-1"><?= $prop['habitaciones'] ?? 0 ?> Habs</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <i class="bi bi-droplet fs-3 text-primary"></i>
                            <div class="fw-bold mt-1"><?= $prop['banos'] ?? 0 ?> Baños</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <i class="bi bi-aspect-ratio fs-3 text-primary"></i>
                            <div class="fw-bold mt-1"><?= $prop['m2'] ?? 0 ?> m²</div>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <i class="bi bi-eye fs-3 text-primary"></i>
                            <div class="fw-bold mt-1"><?= $prop['vistas'] ?? 0 ?> Vistas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg p-4 sticky-top bg-white" style="top: 100px; z-index: 10; border-radius: 16px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted text-uppercase small fw-bold">Precio de Lista</span>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Disponible</span>
                </div>
                
                <h2 class="text-primary fw-bold display-6 mb-4">
                    $<?= number_format($prop['precio'] ?? 0) ?> 
                    <span class="fs-6 text-muted">MXN</span>
                </h2>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-warning btn-lg shadow-sm text-white fw-bold">
                        <i class="bi bi-whatsapp me-2"></i> Contactar Visita
                    </button>
                </div>

                <hr class="my-4 opacity-10">

                <div class="d-flex align-items-center">
                    <div class="bg-light rounded-circle p-2 me-3 border border-warning">
                        <i class="bi bi-person-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Agente Inmobiliario</div>
                        <small class="text-muted">Prestige Real Estate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
        <div class="alert alert-warning text-center p-5 rounded-4 shadow-sm mt-5">
            <h1 class="display-1 text-warning"><i class="bi bi-exclamation-circle"></i></h1>
            <h3 class="fw-bold mt-3">Propiedad no encontrada</h3>
            <p class="text-muted">No pudimos encontrar la propiedad con ID: <strong><?= htmlspecialchars($id) ?></strong></p>
            <a href="propiedades.php" class="btn btn-dark mt-3">Volver al Catálogo</a>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>