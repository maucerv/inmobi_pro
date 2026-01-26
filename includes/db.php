<?php
// includes/db.php

// Ruta segura
$db_file = __DIR__ . '/inmobiliaria_lite.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 1. CREACIÓN BASE (Si no existe)
    // Agregamos 'vistas' a la definición inicial
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
        vistas INTEGER DEFAULT 0,
        destacado INTEGER DEFAULT 0
    )");

    // 2. PARCHE AUTO-EVOLUTIVO (Corrige tu base de datos actual)
    
    // A) Chequeo de Coordenadas (Lat/Lng)
    try {
        $pdo->query("SELECT lat FROM propiedades LIMIT 1");
    } catch (Exception $e) {
        $pdo->exec("ALTER TABLE propiedades ADD COLUMN lat DECIMAL(10,8)");
        $pdo->exec("ALTER TABLE propiedades ADD COLUMN lng DECIMAL(11,8)");
    }

    // B) Chequeo de Vistas (SOLUCIÓN A TU ERROR)
    try {
        $pdo->query("SELECT vistas FROM propiedades LIMIT 1");
    } catch (Exception $e) {
        // Si falla el select, es que falta la columna. La agregamos.
        $pdo->exec("ALTER TABLE propiedades ADD COLUMN vistas INTEGER DEFAULT 0");
    }

    // Creación de tabla usuarios
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nombre TEXT
    )");

    // 3. AUTO-RELLENO (Solo si está vacía)
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        // Insertamos datos de prueba
        $sql = "INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, m2, imagen, destacado, lat, lng, vistas) VALUES 
        ('Penthouse Luxury View', 12500000, 'Polanco, CDMX', 3, 3.5, 210, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', 1, 19.435, -99.195, 150),
        ('Residencia Moderna', 8900000, 'Juriquilla, QRO', 4, 4, 350, 'https://images.unsplash.com/photo-1600596542815-e36cb06c378e', 1, 20.693, -100.443, 85),
        ('Loft Industrial', 4500000, 'Centro, GDL', 1, 1.5, 95, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c', 1, 20.676, -103.347, 320)";
        $pdo->exec($sql);
        
        $pdo->exec("INSERT INTO usuarios (email, password, nombre) VALUES ('admin@test.com', '123456', 'Admin')");
    }

} catch (PDOException $e) {
    echo "Error DB: " . $e->getMessage();
    exit;
}
?>