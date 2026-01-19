<?php 
require 'includes/db.php';
// LÓGICA DE VISTAS (NUEVO)
$id = $_GET['id'] ?? 0;
if($id > 0) {
    $pdo->exec("UPDATE propiedades SET vistas = vistas + 1 WHERE id = $id");
}

include 'includes/header.php'; 
// ... (El resto del archivo sigue IGUAL que antes)
$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
$stmt->execute([$id]);
$prop = $stmt->fetch(PDO::FETCH_ASSOC);
$default_img = "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80";
?>
<div class="container my-5">
    <?php if($prop): $imagen = !empty($prop['imagen']) ? $prop['imagen'] : $default_img; ?>
    <?php else: ?><div class="alert alert-warning">No existe.</div><?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>