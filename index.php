<?php 
/* 1. LÓGICA ORIGINAL INTACTA */
require 'includes/db.php';
include 'includes/header.php'; 

// Propiedades destacadas (Tu consulta original)
$stmt = $pdo->query("SELECT * FROM propiedades WHERE destacado = 1 LIMIT 5");

// Imagen de respaldo (Tu variable original)
$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80";
?>

<style>
    /* Configuración de colores y fuentes */
    :root {
        --color-gold: #ffc107; 
        --color-dark: #0f172a;
    }

    /* Hero Section - Diseño mejorado */
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
        filter: brightness(0.6); /* Oscurecemos un poco para que se lea el texto */
    }

    /* Tarjetas de Propiedades */
    .card-property {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-property:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .image-wrapper {
        height: 250px;
        width: 100%;
        position: relative;
        background-color: #e2e8f0;
    }

    .property-bg {
        height: 100%;
        width: 100%;
        background-size: cover;
        background-position: center;
        transition: transform 0.5s ease;
    }

    .card-property:hover .property-bg {
        transform: scale(1.1); /* Efecto zoom al pasar mouse */
    }

    /* Etiquetas de precio y estado */
    .price-badge {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: #fff;
        color: var(--color-dark);
        font-weight: 700;
        padding: 6px 15px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .status-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(15, 23, 42, 0.9);
        color: #fff;
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Iconos y detalles */
    .amenities-row {
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
        margin-top: auto;
        border-top: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #64748b;
    }

    .btn-detail {
        background-color: var(--color-dark);
        color: #fff;
        border: none;
        transition: background 0.3s;
    }
    
    .btn-detail:hover {
        background-color: #334155;
        color: #fff;
    }
</style>

<div class="hero-section">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="https://videos.pexels.com/video-files/7578544/7578544-hd_1920_1080_30fps.mp4" type="video/mp4">
    </video>

    <div class="container text-center text-white position-relative" style="z-index: 2;">
        <span class="text-warning fw-bold text-uppercase mb-3 d-block" style="letter-spacing: 2px;">Experiencia Inmobiliaria</span>
        <h1 class="display-2 fw-bold mb-4">Encuentra tu lugar<br>en el mundo</h1>
        
        <div class="d-flex justify-content-center gap-3 mt-5">
            <a href="mapa.php" class="btn btn-warning fw-bold px-5 py-3 rounded-pill shadow">
                Ver Mapa
            </a>
            <a href="propiedades.php" class="btn btn-outline-light px-5 py-3 rounded-pill">
                Catálogo
            </a>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-6">Oportunidades Destacadas</h2>
        <div class="bg-warning mx-auto mt-3 rounded" style="width: 60px; height: 4px;"></div>
    </div>
    
    <div class="row g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            // Lógica original de imagen
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
                    <div class="text-muted small mb-2 text-uppercase fw-bold">
                        <i class="bi bi-geo-alt-fill text-warning"></i> <?= htmlspecialchars($row['ubicacion']) ?>
                    </div>

                    <h5 class="fw-bold text-dark mb-3">
                        <?= htmlspecialchars($row['titulo']) ?>
                    </h5>
                    
                    <div class="amenities-row">
                        <div>
                            <span class="fw-bold text-dark d-block"><?= $row['habitaciones'] ?></span> 
                            <small>Habs</small>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block"><?= $row['banos'] ?></span> 
                            <small>Baños</small>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block">120</span> 
                            <small>m²</small>
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