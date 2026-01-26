<?php
// includes/db.php

// 1. DETECCIÓN DE DRIVER (Para saber si el servidor soporta SQLite)
if (!in_array("sqlite", PDO::getAvailableDrivers())) {
    die("<div style='background:red;color:white;padding:20px;text-align:center;'>
            <h1>Error Crítico: Falta el Driver SQLite</h1>
            <p>Tu servidor (Docker/Render) no tiene activado 'pdo_sqlite'.<br>
            Debes instalarlo en tu Dockerfile con: <code>RUN docker-php-ext-install pdo_sqlite</code></p>
         </div>");
}

// 2. SELECCIÓN DE RUTA INTELIGENTE
// Intentamos guardar en la carpeta actual. Si no se puede (por permisos), usamos /tmp
$filename = 'inmobiliaria_lite.db';
$main_path = __DIR__ . '/' . $filename;
$tmp_path  = '/tmp/' . $filename;

// Verificamos permisos de escritura en la carpeta 'includes'
if (is_writable(__DIR__)) {
    $db_file = $main_path;
} else {
    // Si no tenemos permiso aquí, usamos la carpeta temporal del sistema (Siempre funciona en Render)
    $db_file = $tmp_path;
}

try {
    // 3. CONEXIÓN
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 4. AUTO-CREACIÓN DE TABLAS (Si no existen)
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

    // Tabla Usuarios (Admin)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nombre TEXT
    )");

    // 5. AUTO-RELLENO DE DATOS (Si la base está vacía)
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, m2, imagen, destacado) VALUES 
        ('Penthouse Luxury View', 12500000, 'Polanco, CDMX', 3, 3.5, 210, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', 1),
        ('Residencia Moderna', 8900000, 'Juriquilla, QRO', 4, 4, 350, 'https://images.unsplash.com/photo-1600596542815-e36cb06c378e', 1),
        ('Loft Industrial', 4500000, 'Centro, GDL', 1, 1.5, 95, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c', 1),
        ('Casa de Campo', 6700000, 'Valle de Bravo', 5, 5, 500, 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6', 1),
        ('Departamento Minimalista', 3200000, 'Roma Norte, CDMX', 2, 1, 80, 'https://images.unsplash.com/photo-1493809842364-78817add7ffb', 1)");
        
        // Usuario Admin (admin@test.com / 123456)
        $pdo->exec("INSERT INTO usuarios (email, password, nombre) VALUES ('admin@test.com', '123456', 'Admin')");
    }

} catch (PDOException $e) {
    die("<div style='background:darkred;color:white;padding:20px;font-family:sans-serif;'>
            <h3>Error de Base de Datos</h3>
            <p>No se pudo conectar ni crear la base de datos.</p>
            <p><strong>Detalle:</strong> " . $e->getMessage() . "</p>
            <p><strong>Intentando guardar en:</strong> " . $db_file . "</p>
         </div>");
}
?>