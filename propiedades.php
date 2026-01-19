<?php 
require 'includes/db.php';
include 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM propiedades");
$default_img = "https://images.unsplash.com/photo-1600596542815-3ad19fb2a2b8?auto=format&fit=crop&w=800&q=80";
?>

<style>
    /* Tarjeta Base */
    .card-property {
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-property:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    /* Contenedor de Imagen (La clave de la simetría) */
    .property-image-container {
        height: 250px; /* Altura FIJA */
        width: 100%;
        background-size: cover;
        background-position: center;
        background-color: #e2e8f0; /* Fondo gris si falla la imagen */
        position: relative;
    }

    /* Etiquetas Flotantes */
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
        background: rgba(15, 23, 42, 0.9);
        color: #fff;
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Efecto Hover en el botón */
    .btn-details {
        background-color: #0f172a;
        color: white;
        transition: all 0.3s;
    }
    .btn-details:hover {
        background-color: #d4af37; /* Dorado al pasar mouse */
        color: white;
    }
</style>

<div class="position-relative d-flex align-items-center justify-content-center py-5 mb-5" style="min-height: 40vh; background: url('https://images.unsplash.com/photo-1613545325278-f24b0cae1224?auto=format&fit=crop&w=1920&q=80') center/cover fixed;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(15, 23, 42, 0.7);"></div>
    
    <div class="container position-relative z-2 text-center text-white">
        <span class="text-warning fw-bold ls-2 text-uppercase mb-2 d-block">Inventario Disponible</span>
        <h1 class="display-3 fw-bold font-serif mb-3">Catálogo Exclusivo</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 600px;">
            Encuentra el espacio que se adapta a tu estilo de vida. Propiedades seleccionadas bajo los más altos estándares.
        </p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            $imagen = !empty($row['imagen']) ? $row['imagen'] : $default_img;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card card-property h-100">
                
                <div class="property-image-container" style="background-image: url('<?= htmlspecialchars($imagen) ?>');">
                    <div class="status-badge">En Venta</div>
                    <div class="price-badge">$<?= number_format($row['precio']) ?></div>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <div class="text-muted small mb-2 text-uppercase fw-bold">
                        <i class="bi bi-geo-alt-fill text-warning"></i> <?= htmlspecialchars($row['ubicacion']) ?>
                    </div>

                    <h5 class="fw-bold text-dark mb-3 font-serif text-truncate">
                        <?= htmlspecialchars($row['titulo']) ?>
                    </h5>
                    
                    <div class="row g-0 text-center mt-auto py-3 border-top border-bottom bg-light rounded-3 mb-3">
                        <div class="col border-end">
                            <span class="d-block fw-bold"><?= $row['habitaciones'] ?></span>
                            <small class="text-muted" style="font-size: 11px;">Habs</small>
                        </div>
                        <div class="col border-end">
                            <span class="d-block fw-bold"><?= $row['banos'] ?></span>
                            <small class="text-muted" style="font-size: 11px;">Baños</small>
                        </div>
                        <div class="col">
                            <span class="d-block fw-bold"><i class="bi bi-check-circle text-success"></i></span>
                            <small class="text-muted" style="font-size: 11px;">Disp.</small>
                        </div>
                    </div>

                    <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-details w-100 py-2 rounded-2 fw-bold shadow-sm">
                        VER DETALLES
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>