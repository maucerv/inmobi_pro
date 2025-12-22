<?php 
require 'db.php';
include 'includes/header.php'; 
$stmt = $pdo->query("SELECT * FROM propiedades");
$props = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid p-0">
    <div id="map" style="height: 85vh; width: 100%;"></div>
</div>

<script>
    var map = L.map('map').setView([19.4326, -99.1332], 10);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: 'Map data &copy; OpenStreetMap'
    }).addTo(map);

    var data = <?= json_encode($props) ?>;
    data.forEach(item => {
        L.marker([item.lat, item.lon]).addTo(map)
            .bindPopup(`<b>${item.titulo}</b><br>$${item.precio}<br><a href='detalle.php?id=${item.id}'>Ver Detalles</a>`);
    });
</script>

<?php include 'includes/footer.php'; ?>