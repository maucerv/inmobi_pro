<?php 
// includes/db.php ya se encarga de conectar o crear la BD
require_once 'includes/db.php'; 
include 'includes/header.php'; 

// Consulta segura
try {
    $stmt = $pdo->query("SELECT * FROM propiedades WHERE destacado = 1 LIMIT 6");
} catch (Exception $e) {
    echo "Error al cargar propiedades: " . $e->getMessage();
    exit;
}

// Imagen por defecto si falla la carga
$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80";
?>

<style>
    :root {
        --color-gold: #c5a47e; /* Dorado más elegante, tipo Champagne */
        --color-dark: #0f172a;
        --color-text: #334155;
    }

    body {
        background-color: #f8fafc;
    }

    /* === HERO SECTION === */
    .hero-section {
        position: relative;
        height: 85vh;
        min-height: 600px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .video-bg {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        min-width: 100%; min-height: 100%;
        width: auto; height: auto;
        z-index: -2;
        object-fit: cover;
    }

    /* Overlay gradiente profesional */
    .hero-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: linear-gradient(180deg, rgba(15,23,42,0.3) 0%, rgba(15,23,42,0.7) 100%);
        z-index: -1;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    /* === TARJETAS === */
    .card-property {
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        overflow: hidden;
    }

    .card-property:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .image-container {
        position: relative;
        height: 260px;
        overflow: hidden;
    }

    .prop-img {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        transition: transform 0.7s ease;
    }

    .card-property:hover .prop-img {
        transform: scale(1.1);
    }

    .price-tag {
        position: absolute;
        bottom: 20px;
        right: 0;
        background: var(--color-dark);
        color: #fff;
        padding: 8px 20px 8px 15px;
        border-radius: 4px 0 0 4px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: -5px 5px 15px rgba(0,0,0,0.2);
    }

    .status-tag {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        color: var(--color-dark);
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-body h5 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--color-dark);
    }

    .feature-box {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        margin: 15px 0;
    }

    .feature-item {
        text-align: center;
        font-size: 0.85rem;
        color: #64748b;
    }

    .feature-item i {
        display: block;
        font-size: 1.2rem;
        color: var(--color-gold);
        margin-bottom: 5px;
    }

    .btn-view {
        background: transparent;
        border: 2px solid var(--color-dark);
        color: var(--color-dark);
        font-weight: 600;
        padding: 10px;
        width: 100%;
        transition: all 0.3s;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .btn-view:hover {
        background: var(--color-dark);
        color: #fff;
    }
</style>

<div class="hero-section">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="https://videos.pexels.com/video-files/7578544/7578544-hd_1920_1080_30fps.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    <div class="container text-center text-white position-relative" style="z-index: 2;">
        <span class="text-uppercase ls-2 mb-3 d-block fw-bold" style="color: var(--color-gold); letter-spacing: 3px;">Real Estate & Living</span>
        <h1 class="hero-title">Encuentra tu lugar<br>en el mundo</h1>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="mapa.php" class="btn btn-warning px-5 py-3 rounded-0 fw-bold shadow" style="background: var(--color-gold); border: none;">
                VER MAPA
            </a>
            <a href="propiedades.php" class="btn btn-outline-light px-5 py-3 rounded-0 backdrop-blur">
                CATÁLOGO
            </a>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold font-serif mb-2" style="font-family: 'Playfair Display', serif;">Propiedades Exclusivas</h2>
        <div style="height: 3px; width: 70px; background: var(--color-gold); margin: 0 auto;"></div>
    </div>
    
    <div class="row g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            $bgImage = !empty($row['imagen']) ? $row['imagen'] : $default_img;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card card-property">
                <div class="image-container">
                    <div class="prop-img" style="background-image: url('<?= htmlspecialchars($bgImage) ?>');"></div>
                    <div class="status-tag">En Venta</div>
                    <div class="price-tag">$<?= number_format($row['precio']) ?></div>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i class="bi bi-geo-alt-fill me-1" style="color: var(--color-gold);"></i>
                        <?= htmlspecialchars($row['ubicacion']) ?>
                    </div>

                    <h5 class="mb-3 text-truncate"><?= htmlspecialchars($row['titulo']) ?></h5>
                    
                    <div class="feature-box">
                        <div class="feature-item">
                            <i class="bi bi-door-closed"></i>
                            <strong><?= $row['habitaciones'] ?></strong> Habs
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-droplet"></i>
                            <strong><?= $row['banos'] ?></strong> Baños
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-arrows-angle-expand"></i>
                            <strong>120</strong> m²
                        </div>
                    </div>

                    <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-view mt-2">
                        Ver Propiedad
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>