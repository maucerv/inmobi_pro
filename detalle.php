<?php 
require 'db.php';
include 'includes/header.php'; 

$id = $_GET['id'] ?? 1;
$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
$stmt->execute([$id]);
$prop = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <?php if($prop): ?>
    <div class="row">
        <div class="col-md-8">
            <img src="<?= $prop['imagen'] ?>" class="w-100 rounded shadow" alt="Casa">
        </div>
        <div class="col-md-4">
            <div class="bg-white p-4 shadow-sm rounded">
                <h2 class="fw-bold text-primary"><?= $prop['titulo'] ?></h2>
                <h3 class="text-warning my-3">$<?= number_format($prop['precio']) ?></h3>
                <p class="text-muted"><?= $prop['ubicacion'] ?></p>
                <hr>
                <p><?= $prop['descripcion'] ?></p>
                <ul class="list-unstyled">
                    <li>🛏 <b>Habitaciones:</b> <?= $prop['habitaciones'] ?></li>
                    <li>🚿 <b>Baños:</b> <?= $prop['banos'] ?></li>
                </ul>
                <button class="btn btn-gold w-100 mt-3">Contactar Agente</button>
                <a href="propiedades.php" class="btn btn-link w-100 mt-2">Volver al catálogo</a>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="alert alert-danger">Propiedad no encontrada.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>