<?php 
// 1. CARGA SEGURA DE RECURSOS
// Usamos __DIR__ para asegurar que encuentra los archivos
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php'; 

// 2. CONSULTA SEGURA
// Si la tabla aún no se crea (raro con el nuevo db.php), esto lo maneja
try {
    $stmt = $pdo->query("SELECT * FROM propiedades WHERE destacado = 1 LIMIT 6");
} catch (Exception $e) {
    echo "<div class='alert alert-danger text-center m-5'>Error cargando propiedades: " . $e->getMessage() . "</div>";
    exit;
}

// Imagen por defecto
$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80";
?>

<style>
    /* VARIABLES DE DISEÑO */
    :root {
        --color-gold: #c5a47e; 
        --color-dark: #0f172a;
    }

    body { background-color: #f8fafc; }

    /* HERO SECTION */
    .hero-section {
        position: relative;
        height: 85vh;
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .video-bg {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        min-width: 100%; min-height: 100%;
        object-fit: cover;
        z-index: -2;
    }

    .hero-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: linear-gradient(180deg, rgba(15,23,42,0.4) 0%, rgba(15,23,42,0.8) 100%);
        z-index: -1;
    }

    /* TARJETAS */
    .card-property {
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .card-property:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.1);
    }

    .image-wrapper {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .prop-bg {
        width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        transition: transform 0.5s ease;
    }

    .card-property:hover .prop-bg { transform: scale(1.1); }

    .price-tag {
        position: absolute;
        bottom: 15px; right: 15px;
        background: #fff;
        color: var(--color-dark);
        font-weight: 800;
        padding: 5px 15px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .status-tag {
        position: absolute;
        top: 15px; left: 15px;
        background: rgba(15,23,42,0.9);
        color: #fff;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .feature-list {
        display: flex;
        justify-content: space-between;
        border-top: 1px solid #f1f5f9;
        padding-top: 15px;
        margin-top: auto;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .btn-view {
        background: var(--color-dark);
        color: white;
        width: 100%;
        padding: 10px;
        margin-top: 15px;
        border: none;
        transition: background 0.3s;
    }
    .btn-view:hover { background: #334155; color: white; }
</style>

<div class="hero-section">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="https://videos.pexels.com/video-files/7578544/7578544-hd_1920_1080_30fps.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    <div class="container text-center text-white position-relative" style="z-index: 2;">
        <span class="text-uppercase fw-bold mb-2 d-block text-warning ls-2">Real Estate</span>
        <h1 class="display-3 fw-bold mb-4 font-serif">Encuentra tu lugar<br>en el mundo</h1>
        <div class="d-flex justify-content-center gap-3">
            <a href="mapa.php" class="btn btn-warning px-5 py-3 rounded-pill fw-bold shadow">Ver Mapa</a>
            <a href="propiedades.php" class="btn btn-outline-light px-5 py-3 rounded-pill">Catálogo</a>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="display-6 fw-bold">Propiedades Destacadas</h2>
        <div class="bg-warning mx-auto mt-3" style="width: 60px; height: 3px;"></div>
    </div>
    
    <div class="row g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            $bgImage = !empty($row['imagen']) ? $row['imagen'] : $default_img;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card card-property">
                <div class="image-wrapper">
                    <div class="prop-bg" style="background-image: url('<?= htmlspecialchars($bgImage) ?>');"></div>
                    <div class="status-tag">En Venta</div>
                    <div class="price-tag">$<?= number_format($row['precio']) ?></div>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <div class="text-muted small mb-2 d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-warning me-1"></i>
                        <?= htmlspecialchars($row['ubicacion']) ?>
                    </div>

                    <h5 class="fw-bold text-dark mb-3"><?= htmlspecialchars($row['titulo']) ?></h5>
                    
                    <div class="feature-list">
                        <div><i class="bi bi-door-closed text-warning"></i> <?= $row['habitaciones'] ?> Habs</div>
                        <div><i class="bi bi-droplet text-warning"></i> <?= $row['banos'] ?> Baños</div>
                        <div><i class="bi bi-aspect-ratio text-warning"></i> 120 m²</div>
                    </div>

                    <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-view rounded-2">Ver Detalles</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>