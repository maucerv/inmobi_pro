<?php 
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php'; 

// Consulta segura con manejo de errores
try {
    $stmt = $pdo->query("SELECT * FROM propiedades WHERE destacado = 1 LIMIT 6");
} catch (Exception $e) {
    // Si falla, mostramos un mensaje bonito en vez de error de código
    echo "<div class='container my-5 text-center'><h3>Cargando propiedades...</h3></div>";
    // Opcional: Log del error
    $stmt = null; 
}

$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80";
?>

<style>
    /* Estilos del Home */
    body { background-color: #f8fafc; }
    .hero-section {
        position: relative; height: 85vh; min-height: 500px;
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .video-bg {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        min-width: 100%; min-height: 100%; object-fit: cover; z-index: -2;
    }
    .hero-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(180deg, rgba(15,23,42,0.4) 0%, rgba(15,23,42,0.8) 100%); z-index: -1;
    }
    .card-property {
        border: none; border-radius: 12px; background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; height: 100%; overflow: hidden;
    }
    .card-property:hover { transform: translateY(-8px); box-shadow: 0 20px 30px rgba(0,0,0,0.1); }
    .prop-bg {
        height: 250px; background-size: cover; background-position: center; transition: 0.5s;
    }
    .card-property:hover .prop-bg { transform: scale(1.1); }
    .price-tag {
        position: absolute; bottom: 15px; right: 15px; background: #fff; color: #0f172a;
        font-weight: 800; padding: 5px 15px; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .btn-view {
        background: #0f172a; color: white; width: 100%; padding: 10px; border: none; transition: 0.3s;
    }
    .btn-view:hover { background: #334155; color: white; }
</style>

<div class="hero-section">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="https://videos.pexels.com/video-files/7578544/7578544-hd_1920_1080_30fps.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    <div class="container text-center text-white position-relative" style="z-index: 2;">
        <span class="text-uppercase fw-bold mb-2 d-block text-warning" style="letter-spacing: 3px;">Real Estate</span>
        <h1 class="display-3 fw-bold mb-4" style="font-family: 'Playfair Display', serif;">Encuentra tu lugar<br>en el mundo</h1>
        <div class="d-flex justify-content-center gap-3">
            <a href="propiedades.php" class="btn btn-warning px-5 py-3 rounded-pill fw-bold shadow">Catálogo</a>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="display-6 fw-bold">Propiedades Destacadas</h2>
        <div class="bg-warning mx-auto mt-3" style="width: 60px; height: 3px;"></div>
    </div>
    
    <div class="row g-4">
        <?php if ($stmt): ?>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                $bgImage = !empty($row['imagen']) ? $row['imagen'] : $default_img;
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card card-property">
                    <div style="position: relative; overflow: hidden;">
                        <div class="prop-bg" style="background-image: url('<?= htmlspecialchars($bgImage) ?>');"></div>
                        <div class="price-tag">$<?= number_format($row['precio']) ?></div>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-warning"></i> <?= htmlspecialchars($row['ubicacion']) ?></div>
                        <h5 class="fw-bold text-dark mb-3"><?= htmlspecialchars($row['titulo']) ?></h5>
                        <div class="d-flex justify-content-between border-top pt-3 text-muted small">
                            <span><i class="bi bi-door-closed"></i> <?= $row['habitaciones'] ?> Habs</span>
                            <span><i class="bi bi-droplet"></i> <?= $row['banos'] ?> Baños</span>
                            <span><i class="bi bi-arrows-angle-expand"></i> <?= isset($row['m2']) ? $row['m2'] : 120 ?> m²</span>
                        </div>
                        <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-view mt-3 rounded-2">Ver Detalles</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No hay propiedades destacadas por el momento.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>