<?php
// includes/db.php

// 1. Usar una ruta segura dentro de /includes para evitar problemas de permisos
$db_file = __DIR__ . '/inmobiliaria_lite.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // --- AUTO-REPARACIÓN DE TABLAS ---
    
    // 1. Tabla Propiedades
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
        destacado INTEGER DEFAULT 0
    )");

    // 2. Tabla Usuarios (Para el admin)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nombre TEXT
    )");

    // 3. Tablas del Monitor (Para evitar error si se activa)
    $pdo->exec("CREATE TABLE IF NOT EXISTS visitas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip TEXT,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
        pagina TEXT
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS bloqueos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip TEXT,
        motivo TEXT,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // --- AUTO-RELLENO (Solo si está vacía) ---
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, m2, imagen, destacado) VALUES 
        ('Penthouse Luxury View', 12500000, 'Polanco, CDMX', 3, 3.5, 210, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', 1),
        ('Residencia Moderna', 8900000, 'Juriquilla, QRO', 4, 4, 350, 'https://images.unsplash.com/photo-1600596542815-e36cb06c378e', 1),
        ('Loft Industrial', 4500000, 'Centro, GDL', 1, 1.5, 95, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c', 1)");
        
        // Crear usuario admin por defecto (admin@test.com / 123456)
        // Nota: En producción usa password_hash()
        $pdo->exec("INSERT INTO usuarios (email, password, nombre) VALUES ('admin@test.com', '123456', 'Administrador')");
    }

} catch (PDOException $e) {
    echo "Error crítico de conexión: " . $e->getMessage();
    exit;
}
?>