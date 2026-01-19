<?php 
require 'includes/db.php';
include 'includes/header.php'; 

// Propiedades destacadas
$stmt = $pdo->query("SELECT * FROM propiedades WHERE destacado = 1 LIMIT 5");

// Imagen de respaldo (por si falla la de la BD)
$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80";
?>

<style>
    /* 1. Corrección del Hero para que no se trabe */
    .video-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
        filter: brightness(0.5); /* Oscurece para que el texto resalte */
    }

    /* 2. Corrección de SIMETRÍA en Tarjetas */
    .card-property {
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%; /* Fuerza a que todas midan lo mismo */
        display: flex;
        flex-direction: column;
    }

    .card-property:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    /* TRUCO: Usar background-image en lugar de img tag */
    .property-image-container {
        height: 250px; /* Altura FIJA: Jamás se moverá */
        width: 100%;
        background-size: cover;
        background-position: center;
        background-color: #e2e8f0; /* Color gris por si la imagen no carga */
        position: relative;
    }

    /* Precios y Badges */
    .price-badge {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: #fff;
        color: #0f172a;
        font-weight: 700;
        padding: 5px 15px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        font-family: 'Lato', sans-serif;
    }

    .status-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(15, 23, 42, 0.85);
        color: #fff;
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="position-relative d-flex align-items-center justify-content-center" style="height: 90vh; overflow: hidden;">
    
    <video autoplay muted loop playsinline class="video-bg">
        <source src="https://videos.pexels.com/video-files/7578544/7578544-hd_1920_1080_30fps.mp4" type="video/mp4">
    </video>

    <div class="container text-center text-white position-relative" style="z-index: 2;">
        <div class="animate-up">
            <span class="text-warning fw-bold ls-2 text-uppercase mb-3 d-block">Experiencia Inmobiliaria</span>
            <h1 class="display-2 fw-bold mb-4 font-serif">Encuentra tu lugar<br>en el mundo</h1>
            <p class="lead mb-5 opacity-75 mx-auto" style="max-width: 700px;">
                Descubre propiedades exclusivas con la mejor ubicación y plusvalía garantizada.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="mapa.php" class="btn btn-warning fw-bold px-5 py-3 rounded-pill shadow">
                    <i class="bi bi-map me-2"></i> Ver Mapa
                </a>
                <a href="propiedades.php" class="btn btn-outline-light px-5 py-3 rounded-pill">
                    Catálogo
                </a>
            </div>
        </div>
    </div>

    <div class="position-absolute bottom-0 mb-4 text-white text-center w-100" style="animation: bounce 2s infinite;">
    </div>
</div>

<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold font-serif display-6">Oportunidades Destacadas</h2>
        <div class="bg-warning mx-auto mt-3" style="width: 60px; height: 3px;"></div>
    </div>
    
    <div class="row g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            // Lógica de imagen: Si viene vacía, usa la default
            $bgImage = !empty($row['imagen']) ? $row['imagen'] : $default_img;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card card-property h-100">
                
                <div class="property-image-container" style="background-image: url('<?= htmlspecialchars($bgImage) ?>');">
                    <div class="status-badge">En Venta</div>
                    <div class="price-badge">$<?= number_format($row['precio']) ?></div>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <div class="text-muted small mb-2 text-uppercase fw-bold">
                        <i class="bi bi-geo-alt-fill text-warning"></i> <?= htmlspecialchars($row['ubicacion']) ?>
                    </div>

                    <h5 class="fw-bold text-dark mb-3 font-serif">
                        <?= htmlspecialchars($row['titulo']) ?>
                    </h5>
                    
                    <div class="row g-0 text-center mt-auto py-3 border-top border-bottom bg-light rounded-3">
                        <div class="col border-end">
                            <span class="d-block fw-bold"><?= $row['habitaciones'] ?></span>
                            <small class="text-muted" style="font-size: 11px;">Habs</small>
                        </div>
                        <div class="col border-end">
                            <span class="d-block fw-bold"><?= $row['banos'] ?></span>
                            <small class="text-muted" style="font-size: 11px;">Baños</small>
                        </div>
                        <div class="col">
                            <span class="d-block fw-bold">120</span>
                            <small class="text-muted" style="font-size: 11px;">m²</small>
                        </div>
                    </div>

                    <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-dark w-100 mt-3 py-2 rounded-2">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>