<?php
// includes/db.php

// 1. Detección de Driver
if (!in_array("sqlite", PDO::getAvailableDrivers())) {
    die("Error Crítico: Falta el driver pdo_sqlite en el servidor.");
}

// 2. Ruta inteligente (usa /tmp si no hay permisos en la carpeta actual)
$filename = 'inmobiliaria_lite.db';
$main_path = __DIR__ . '/' . $filename;
$tmp_path  = '/tmp/' . $filename;

$db_file = (is_writable(__DIR__)) ? $main_path : $tmp_path;

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // --- AUTO-CREACIÓN DE TABLAS ---

    // 1. Propiedades (Catálogo)
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

    // 2. Usuarios (Admin)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nombre TEXT,
        rol TEXT DEFAULT 'admin'
    )");

    // 3. Tablas de Seguridad y Estadísticas (SOLUCIÓN A TU ERROR)
    $pdo->exec("CREATE TABLE IF NOT EXISTS visitas_web (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip TEXT,
        fecha DATE DEFAULT (DATE('now')),
        pagina TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS logs_seguridad (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tipo TEXT,
        mensaje TEXT,
        ip TEXT,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // --- AUTO-RELLENO INICIAL ---
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        // Propiedades base
        $pdo->exec("INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, m2, imagen, destacado, lat, lng) VALUES 
        ('Penthouse Luxury View', 12500000, 'Polanco, CDMX', 3, 3.5, 210, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', 1, 19.435, -99.195),
        ('Residencia Moderna', 8900000, 'Juriquilla, QRO', 4, 4, 350, 'https://images.unsplash.com/photo-1600596542815-e36cb06c378e', 1, 20.693, -100.443)");
        
        // Usuario Admin por defecto
        $pdo->exec("INSERT INTO usuarios (email, password, nombre, rol) VALUES ('admin@test.com', '123456', 'Administrador', 'SuperAdmin')");
    }

} catch (PDOException $e) {
    die("Error de Base de Datos: " . $e->getMessage());
}
?>