<?php 
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php'; 

// Obtenemos solo las propiedades que tienen coordenadas
try {
    $stmt = $pdo->query("SELECT * FROM propiedades WHERE lat IS NOT NULL AND lng IS NOT NULL");
    $propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $propiedades = [];
}
?>

<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 10px;
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 250px !important;
    }
    .popup-img {
        width: 100%;
        height: 140px;
        background-size: cover;
        background-position: center;
    }
    .popup-info {
        padding: 15px;
    }
</style>

<div class="container my-5 pt-4">
    <div class="text-center mb-4">
        <h2 class="display-5 fw-bold font-serif">Ubicación de Propiedades</h2>
        <p class="text-muted">Explora nuestras oportunidades en el mapa interactivo</p>
    </div>

    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 1. Inicializar el mapa (Centrado en México)
    var map = L.map('map').setView([20.0, -100.0], 5);

    // 2. Cargar capas de OpenStreetMap (Estilo claro/minimalista)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // 3. Icono personalizado (Opcional, usa el default si falla)
    var customIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34]
    });

    // 4. Datos de PHP a Javascript
    var propiedades = <?php echo json_encode($propiedades); ?>;

    // 5. Crear marcadores
    var bounds = []; // Para ajustar el zoom automáticamente

    propiedades.forEach(function(prop) {
        if(prop.lat && prop.lng) {
            var lat = parseFloat(prop.lat);
            var lng = parseFloat(prop.lng);
            
            // Crear contenido del popup (HTML)
            var popupContent = `
                <div class="popup-card">
                    <div class="popup-img" style="background-image: url('${prop.imagen}');"></div>
                    <div class="popup-info">
                        <h6 class="fw-bold mb-1">${prop.titulo}</h6>
                        <span class="badge bg-dark mb-2">$${new Intl.NumberFormat().format(prop.precio)}</span>
                        <br>
                        <a href="detalle.php?id=${prop.id}" class="btn btn-sm btn-outline-dark w-100 mt-2">Ver Detalles</a>
                    </div>
                </div>
            `;

            var marker = L.marker([lat, lng], {icon: customIcon})
                .addTo(map)
                .bindPopup(popupContent);
                
            bounds.push([lat, lng]);
        }
    });

    // 6. Ajustar vista para ver todos los marcadores
    if (bounds.length > 0) {
        map.fitBounds(bounds, {padding: [50, 50]});
    }
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>