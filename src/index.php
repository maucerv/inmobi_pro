<?php 
require 'db.php';
include 'includes/header.php'; 
$stmt = $pdo->query("SELECT * FROM propiedades WHERE destacado = 1 LIMIT 3");
?>

<div class="hero">
    <div class="text-center">
        <h1 class="display-3 fw-bold">Encuentra tu Hogar Ideal</h1>
        <a href="propiedades.php" class="btn btn-gold btn-lg mt-3">Ver Catálogo Completo</a>
    </div>
</div>

<div class="container my-5">
    <h2 class="text-center mb-4 text-dark">Propiedades Destacadas</h2>
    <div class="row">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <img src="<?= $row['imagen'] ?>" class="card-img-top" style="height: 250px; object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['titulo'] ?></h5>
                    <p class="text-muted">$<?= number_format($row['precio']) ?></p>
                    <a href="detalle.php?id=<?= $row['id'] ?>" class="btn btn-outline-dark w-100">Ver Detalles</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>