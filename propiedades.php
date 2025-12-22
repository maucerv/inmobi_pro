<?php 
require 'db.php';
include 'includes/header.php'; 
$stmt = $pdo->query("SELECT * FROM propiedades");
?>

<div class="container my-5">
    <h2 class="mb-4 border-bottom pb-2">Catálogo de Propiedades</h2>
    <div class="row g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <img src="<?= $row['imagen'] ?>" class="card-img-top" style="height: 200px; object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title fs-6"><?= $row['titulo'] ?></h5>
                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt"></i> <?= $row['ubicacion'] ?></p>
                    <p class="fw-bold text-primary">$<?= number_format($row['precio']) ?></p>
                    <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-gold btn-sm w-100">Ver Ficha</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>