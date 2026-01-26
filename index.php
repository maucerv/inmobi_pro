<?php 
require 'includes/db.php';
include 'includes/header.php'; 

// Propiedades destacadas
$stmt = $pdo->query("SELECT * FROM propiedades WHERE destacado = 1 LIMIT 6"); // Subí el limit a 6 para mantener simetría en grid de 3

// Imagen de respaldo
$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80";
?>

<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

<style>
    :root {
        --color-gold: #ffc107; /* Tu warning de Bootstrap */
        --color-dark: #0f172a;
        --font-serif: 'Playfair Display', serif;
        --font-sans: 'Lato', sans-serif;
    }

    body {
        font-family: var(--font-sans);
        color: #334155;
    }

    h1, h2, h3, h4, h5 {
        font-family: var(--font-serif);
    }

    /* === HERO SECTION === */
    .hero-section {
        position: relative;
        height: 90vh;
        min-height: 600px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .video-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -2;
        object-fit: cover;
    }

    /* Overlay gradiente para mejor lectura que el brightness plano */
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.8));
        z-index: -1;
    }

    .btn-custom-cta {
        background-color: var(--color-gold);
        color: var(--color-dark);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-custom-cta:hover {
        background-color: #fff;
        transform: translateY(-2px);
    }

    /* === TARJETAS DE PROPIEDADES === */
    .card-property {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .card-property:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .image-wrapper {
        height: 260px;
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .property-bg {
        height: 100%;
        width: 100%;
        background-size: cover;
        background-position: center;
        background-color: #e2e8f0;
        transition: transform 0.6s ease; /* Efecto Zoom Suave */
    }

    .card-property:hover .property-bg {
        transform: scale(1.1); /* El zoom al hacer hover */
    }

    /* Badges refinados */
    .price-badge {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.95);
        color: var(--color-dark);
        font-weight: 800;
        padding: 8px 18px;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        font-family: var(--font-sans);
        font-size: 1.1rem;
        backdrop-filter: blur(5px);
    }

    .status-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: var(--color-dark);
        color: var(--color-gold);
        font-size: 0.7rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        z-index: 2;
    }

    /* Iconos de caracteristicas */
    .amenities-row {
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
        margin-top: auto; /* Empuja al fondo */
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-size: 0.9rem;
    }

    .amenity-item i {
        color: var(--color-gold);
        font-size: 1.1rem;
    }

    .btn-detail {
        background: transparent;
        border: 1px solid var(--color-dark);
        color: var(--color-dark);
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-detail:hover {
        background: var(--color-dark);
        color: #fff;
    }
    
    /* Animación scroll down */
    .scroll-down {
        animation: bounce 2s infinite;
        cursor: pointer;
        opacity: 0.8;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-10px);}
        60% {transform: translateY(-5px);}
    }
</style>

<div class="hero-section">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="https://videos.pexels.com/video-files/7578544/7578544-hd_1920_1080_30fps.mp4" type="video/mp4">
    </video>
    
    <div class="hero-overlay"></div>

    <div class="container text-center text-white position-relative" style="z-index: 2;">
        <span class="text-warning fw-bold ls-2 text-uppercase mb-3 d-block animate__animated animate__fadeInDown">
            Experiencia Inmobiliaria
        </span>
        <h1 class="display-1 fw-bold mb-4 animate__animated animate__fadeInUp" style="letter-spacing: -1px;">
            Encuentra tu lugar<br>en el mundo
        </h1>
        <p class="lead mb-5 opacity-90 mx-auto fw-light animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 650px; font-size: 1.25rem;">
            Descubre propiedades exclusivas con la mejor ubicación, diseño arquitectónico y plusvalía garantizada.
        </p>
        
        <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeInUp animate__delay-1s">
            <a href="mapa.php" class="btn btn-custom-cta px-5 py-3 rounded-pill shadow fw-bold">
                <i class="bi bi-map me-2"></i> Explorar Mapa
            </a>
            <a href="propiedades.php" class="btn btn-outline-light px-5 py-3 rounded-pill backdrop-blur">
                Ver Catálogo
            </a>
        </div>
    </div>

    <div class="position-absolute bottom-0 mb-5 text-white text-center w-100 scroll-down">
        <small class="d-block text-uppercase ls-2 mb-2" style="font-size: 0.7rem;">Descubre Más</small>
        <i class="bi bi-arrow-down-circle h2"></i>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <span class="text-muted text-uppercase small fw-bold ls-2">Selección Exclusiva</span>
        <h2 class="fw-bold display-5 mt-2 mb-3">Oportunidades Destacadas</h2>
        <div class="bg-warning mx-auto rounded" style="width: 80px; height: 4px;"></div>
    </div>
    
    <div class="row g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            $bgImage = !empty($row['imagen']) ? $row['imagen'] : $default_img;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card card-property">
                
                <div class="image-wrapper">
                    <div class="property-bg" style="background-image: url('<?= htmlspecialchars($bgImage) ?>');"></div>
                    <div class="status-badge">En Venta</div>
                    <div class="price-badge">$<?= number_format($row['precio']) ?></div>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <div class="text-muted small mb-2 text-uppercase fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-warning"></i> 
                        <span class="text-truncate"><?= htmlspecialchars($row['ubicacion']) ?></span>
                    </div>

                    <h5 class="fw-bold text-dark mb-3 text-truncate" title="<?= htmlspecialchars($row['titulo']) ?>">
                        <?= htmlspecialchars($row['titulo']) ?>
                    </h5>
                    
                    <p class="text-muted small mb-4 line-clamp-2">
                        <?= !empty($row['descripcion_corta']) ? htmlspecialchars($row['descripcion_corta']) : 'Hermosa propiedad con acabados de lujo y excelente ubicación...' ?>
                    </p>
                    
                    <div class="amenities-row">
                        <div class="amenity-item">
                            <i class="bi bi-door-closed"></i>
                            <span><?= $row['habitaciones'] ?> <small>Habs</small></span>
                        </div>
                        <div class="amenity-item">
                            <i class="bi bi-droplet"></i>
                            <span><?= $row['banos'] ?> <small>Baños</small></span>
                        </div>
                        <div class="amenity-item">
                            <i class="bi bi-aspect-ratio"></i>
                            <span><?= !empty($row['m2']) ? $row['m2'] : '120' ?> <small>m²</small></span>
                        </div>
                    </div>

                    <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-detail w-100 mt-4 py-2 rounded-2">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>