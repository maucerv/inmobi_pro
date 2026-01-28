<?php
// includes/db.php
if (!in_array("sqlite", PDO::getAvailableDrivers())) {
    die("Error Crítico: Falta el driver pdo_sqlite.");
}

$db_file = __DIR__ . '/inmobiliaria_lite.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Tabla Propiedades (Consistencia en lat/lng)
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
        lat REAL,
        lng REAL, 
        vistas INTEGER DEFAULT 0,
        destacado INTEGER DEFAULT 0,
        tipo_operacion TEXT DEFAULT 'venta' 
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nombre TEXT,
        rol TEXT DEFAULT 'editor'
    )");

    // Parche dinámico por si falta la columna tipo_operacion
    $cols = $pdo->query("PRAGMA table_info(propiedades)")->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('tipo_operacion', $cols)) {
        $pdo->exec("ALTER TABLE propiedades ADD COLUMN tipo_operacion TEXT DEFAULT 'venta'");
    }

} catch (PDOException $e) {
    die("Error DB: " . $e->getMessage());
}