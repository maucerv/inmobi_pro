<?php 
require 'includes/db.php';
include 'includes/header.php'; 
$stmt = $pdo->query("SELECT id, titulo, precio, lat, lon, imagen, ubicacion FROM propiedades");
$props = $stmt->fetchAll(PDO::FETCH_ASSOC);
$default_img = "https://images.unsplash.com/photo-1600596542815-3ad19fb2a2b8?auto=format&fit=crop&w=400&q=80";
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="position-relative">
    <div id="map" style="height: calc(100vh - 80px); width: 100%; z-index: 1;"></div>
    
    <div class="position-absolute top-0 start-0 m-4 p-4 bg-white rounded-4 shadow-lg" style="z-index: 1000; max-width: 300px;">
        <h5 class="fw-bold mb-2">Mapa Interactivo</h5>
        <p class="text-muted small mb-0">Explora la ubicación exacta de nuestras propiedades exclusivas.</p>
    </div>
</div>

<script>
    var map = L.map('map').setView([19.4326, -99.1332], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; CARTO'
    }).addTo(map);

    var data = <?= json_encode($props) ?>;
    var defaultImg = "<?= $default_img ?>";

    data.forEach(item => {
        let img = item.imagen ? item.imagen : defaultImg;
        let precio = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(item.precio);
        
        // Popup Estilo Tarjeta Minimalista
        let popup = `
            <div style="width: 200px;">
                <div style="height: 120px; background: url('${img}') center/cover; border-radius: 8px;"></div>
                <div class="mt-2">
                    <h6 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans'">${item.titulo}</h6>
                    <div class="text-warning fw-bold">${precio}</div>
                    <a href="detalle.php?id=${item.id}" class="btn btn-dark btn-sm w-100 mt-2" style="font-size: 12px;">Ver Propiedad</a>
                </div>
            </div>
        `;
        L.marker([item.lat, item.lon]).addTo(map).bindPopup(popup);
    });
</script>

<?php include 'includes/footer.php'; ?>