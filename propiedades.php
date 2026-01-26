<?php 
// propiedades.php
// Usamos rutas absolutas de sistema para los includes. 
// Esto evita errores de "File not found" si el script se ejecuta en un contexto extraño.
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php'; 

// Lógica de consulta
try {
    // Traemos TODAS las propiedades para el catálogo
    $stmt = $pdo->query("SELECT * FROM propiedades");
} catch (Exception $e) {
    echo "<div class='container my-5 text-danger'>Error al cargar propiedades.</div>";
}

$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80";
?>

<div class="container my-5 py-5">
    <div class="text-center mb-5">
        <span class="text-muted text-uppercase small fw-bold ls-2">Nuestro Portafolio</span>
        <h2 class="fw-bold display-5 mt-2 mb-3">Catálogo Completo</h2>
        <div class="bg-warning mx-auto rounded" style="width: 80px; height: 4px;"></div>
    </div>
    
    <div class="row g-4">
        <?php if (isset($stmt)): ?>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            $bgImage = !empty($row['imagen']) ? $row['imagen'] : $default_img;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card card-property h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                
                <div style="height: 250px; position: relative;">
                    <div style="background-image: url('<?= htmlspecialchars($bgImage) ?>'); height: 100%; width: 100%; background-size: cover; background-position: center;"></div>
                    <div class="position-absolute bottom-0 end-0 m-3 px-3 py-1 bg-white rounded-pill shadow-sm fw-bold">
                        $<?= number_format($row['precio']) ?>
                    </div>
                    <div class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-dark text-white rounded-pill text-uppercase" style="font-size: 0.7rem;">
                        En Venta
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i class="bi bi-geo-alt-fill text-warning me-2"></i> 
                        <?= htmlspecialchars($row['ubicacion']) ?>
                    </div>

                    <h5 class="fw-bold text-dark mb-3 text-truncate">
                        <?= htmlspecialchars($row['titulo']) ?>
                    </h5>
                    
                    <div class="d-flex justify-content-between border-top pt-3 mt-3 text-muted small">
                        <span><i class="bi bi-door-closed"></i> <?= $row['habitaciones'] ?> Habs</span>
                        <span><i class="bi bi-droplet"></i> <?= $row['banos'] ?> Baños</span>
                        <span><i class="bi bi-aspect-ratio"></i> <?= isset($row['m2']) ? $row['m2'] : 120 ?> m²</span>
                    </div>

                    <a href="/detalle.php?id=<?= $row['id'] ?>" class="btn btn-dark w-100 mt-4 rounded-pill">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>