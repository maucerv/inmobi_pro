<?php
// includes/db.php

// Ruta segura
$db_file = __DIR__ . '/inmobiliaria_lite.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 1. CREACIÓN BASE (Si no existe)
    // Agregamos lat y lng a la definición inicial por si es una instalación limpia
    $pdo->exec("CREATE TABLE IF NOT EXISTS propiedades (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        precio DECIMAL(10,2) NOT NULL,
        ubicacion TEXT NOT NULL,
        habitaciones INTEGER,
        banos DECIMAL(3,1),
        m2 INTEGER DEFAULT 120,
        imagen TEXT,
        descripcion TEXT,
        lat DECIMAL(10,8),
        lng DECIMAL(11,8),
        destacado INTEGER DEFAULT 0
    )");

    // 2. PARCHE PARA BASE DE DATOS EXISTENTE (Solución a tu error)
    // Intentamos seleccionar 'lat'. Si falla, significa que la tabla vieja existe pero sin coordenadas.
    try {
        $pdo->query("SELECT lat FROM propiedades LIMIT 1");
    } catch (Exception $e) {
        // Si entra aquí, es porque faltan las columnas. Las agregamos.
        $pdo->exec("ALTER TABLE propiedades ADD COLUMN lat DECIMAL(10,8)");
        $pdo->exec("ALTER TABLE propiedades ADD COLUMN lng DECIMAL(11,8)");
        
        // Actualizamos las propiedades de ejemplo con coordenadas reales
        // Polanco, CDMX
        $pdo->exec("UPDATE propiedades SET lat=19.432608, lng=-99.133209 WHERE ubicacion LIKE '%Polanco%' OR titulo LIKE '%Penthouse%'");
        // Juriquilla, QRO
        $pdo->exec("UPDATE propiedades SET lat=20.702958, lng=-100.444791 WHERE ubicacion LIKE '%Juriquilla%' OR titulo LIKE '%Residencia%'");
        // Centro, GDL
        $pdo->exec("UPDATE propiedades SET lat=20.659698, lng=-103.349609 WHERE ubicacion LIKE '%GDL%' OR titulo LIKE '%Loft%'");
    }

    // Creación de tabla usuarios (si falta)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nombre TEXT
    )");

    // 3. AUTO-RELLENO (Solo si está vacía)
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        // Insertamos con coordenadas
        $sql = "INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, m2, imagen, destacado, lat, lng) VALUES 
        ('Penthouse Luxury View', 12500000, 'Polanco, CDMX', 3, 3.5, 210, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', 1, 19.435, -99.195),
        ('Residencia Moderna', 8900000, 'Juriquilla, QRO', 4, 4, 350, 'https://images.unsplash.com/photo-1600596542815-e36cb06c378e', 1, 20.693, -100.443),
        ('Loft Industrial', 4500000, 'Centro, GDL', 1, 1.5, 95, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c', 1, 20.676, -103.347)";
        $pdo->exec($sql);
    }

} catch (PDOException $e) {
    echo "Error DB: " . $e->getMessage();
    exit;
}
?>