<?php
require 'includes/db.php';

echo "Inicializando Sistema Inmobiliario...\n";

try {
    // 1. Tabla Propiedades
    $pdo->exec("CREATE TABLE IF NOT EXISTS propiedades (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        precio DECIMAL(10,2) NOT NULL,
        ubicacion TEXT NOT NULL,
        lat REAL, lon REAL,
        habitaciones INTEGER, banos INTEGER,
        descripcion TEXT, imagen TEXT,
        destacado INTEGER DEFAULT 0,
        vistas INTEGER DEFAULT 0,
        fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Tabla Usuarios
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        rol TEXT DEFAULT 'admin',
        creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. Tabla Logs y Visitas
    $pdo->exec("CREATE TABLE IF NOT EXISTS logs_seguridad (id INTEGER PRIMARY KEY AUTOINCREMENT, tipo TEXT, mensaje TEXT, ip TEXT, fecha DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS visitas_web (id INTEGER PRIMARY KEY AUTOINCREMENT, ip TEXT, pagina TEXT, fecha DATE DEFAULT CURRENT_DATE)");

    // --- INSERTAR ADMIN ---
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    if ($stmt->fetchColumn() == 0) {
        $passHash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)")->execute(['admin', $passHash]);
        echo "Usuario Admin creado.\n";
    }

    // --- INSERTAR PROPIEDADES (DATOS BASE) ---
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        $datos = [
            ['Residencia Royal Polanco', 15500000, 'Polanco, CDMX', 19.4326, -99.2000, 4, 4, 'Lujo absoluto en el centro.', 'https://images.unsplash.com/photo-1600596542815-3ad19fb2a2b8?auto=format&fit=crop&w=800&q=80', 1, 120],
            ['Loft Industrial Roma', 4200000, 'Roma Norte, CDMX', 19.4150, -99.1600, 1, 2, 'Espacio abierto doble altura.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80', 1, 85],
            ['Casa de Campo Valle', 8900000, 'Valle de Bravo', 19.1900, -100.1300, 5, 6, 'Refugio de fin de semana.', 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80', 1, 45],
            ['Penthouse Sky View', 22000000, 'Santa Fe, CDMX', 19.3600, -99.2600, 3, 3, 'Vistas panorámicas.', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80', 1, 230]
        ];

        $insert = $pdo->prepare("INSERT INTO propiedades (titulo, precio, ubicacion, lat, lon, habitaciones, banos, descripcion, imagen, destacado, vistas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($datos as $d) {
            $insert->execute($d);
        }
        echo "Propiedades base insertadas.\n";
    }

} catch (PDOException $e) {
    echo "ERROR GRAVE: " . $e->getMessage() . "\n";
}
?>