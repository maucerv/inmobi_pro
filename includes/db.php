<?php
// includes/db.php

// 1. Detección de Driver
if (!in_array("sqlite", PDO::getAvailableDrivers())) {
    die("Error Crítico: Falta el driver pdo_sqlite en el servidor.");
}

// 2. Ruta inteligente
$filename = 'inmobiliaria_lite.db';
$main_path = __DIR__ . '/' . $filename;
$tmp_path  = '/tmp/' . $filename;
$db_file = (is_writable(__DIR__)) ? $main_path : $tmp_path;

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // --- TABLAS ---

    // 1. Propiedades (Agregamos 'tipo_operacion')
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
        destacado INTEGER DEFAULT 0,
        tipo_operacion TEXT DEFAULT 'venta' 
    )");

    // 2. Usuarios (Rol: 'superadmin' o 'editor')
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nombre TEXT,
        rol TEXT DEFAULT 'editor'
    )");

    // 3. Logs y Visitas
    $pdo->exec("CREATE TABLE IF NOT EXISTS visitas_web (id INTEGER PRIMARY KEY AUTOINCREMENT, ip TEXT, fecha DATE DEFAULT (DATE('now')), pagina TEXT)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS logs_seguridad (id INTEGER PRIMARY KEY AUTOINCREMENT, tipo TEXT, mensaje TEXT, ip TEXT, fecha DATETIME DEFAULT CURRENT_TIMESTAMP)");

    // --- PARCHE PARA BASE EXISTENTE ---
    // Si ya tienes datos, esto agrega la columna 'tipo_operacion' si falta
    try {
        $pdo->query("SELECT tipo_operacion FROM propiedades LIMIT 1");
    } catch (Exception $e) {
        $pdo->exec("ALTER TABLE propiedades ADD COLUMN tipo_operacion TEXT DEFAULT 'venta'");
    }

    // --- AUTO-RELLENO ---
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    if ($stmt->fetchColumn() == 0) {
        // Usuario SuperAdmin por defecto (CÁMBIALO AL ENTRAR)
        // Pass: 123456
        $pdo->exec("INSERT INTO usuarios (email, password, nombre, rol) VALUES ('admin@test.com', '123456', 'Super Admin', 'superadmin')");
    }

} catch (PDOException $e) {
    die("Error DB: " . $e->getMessage());
}
?>