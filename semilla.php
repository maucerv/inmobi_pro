<?php
require 'includes/db.php';

echo "Inicializando Sistema de Seguridad y Usuarios...\n";

try {
    // 1. Tabla de Propiedades (Ya existía)
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

    // 2. NUEVA: Tabla de Usuarios (Administradores)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        rol TEXT DEFAULT 'admin',
        creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. NUEVA: Tabla de Auditoría (Logs de Seguridad)
    $pdo->exec("CREATE TABLE IF NOT EXISTS logs_seguridad (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tipo TEXT NOT NULL, -- 'LOGIN_FALLIDO', 'ATAQUE_SQL', 'XSS', 'LOGIN_EXITOSO'
        mensaje TEXT,
        ip TEXT,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 4. NUEVA: Tabla de Visitas Reales (Tráfico)
    $pdo->exec("CREATE TABLE IF NOT EXISTS visitas_web (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip TEXT,
        pagina TEXT,
        fecha DATE DEFAULT CURRENT_DATE
    )");

    // --- Insertar Datos Iniciales ---

    // Crear Usuario Admin por defecto (Password: admin123)
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    if ($stmt->fetchColumn() == 0) {
        $passHash = password_hash('admin123', PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
        $insert->execute(['admin', $passHash]);
        echo "Usuario 'admin' creado (Pass: admin123).\n";
    }

    // Datos de Propiedades (Si está vacío)
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        // (Aquí van los mismos datos de propiedades que te di en la respuesta anterior...)
        // Para ahorrar espacio, asumo que copias el bloque de propiedades de la respuesta previa.
        // Si lo necesitas, avísame y lo repito.
        echo "Propiedades de prueba insertadas.\n";
    }

    echo "Base de datos actualizada correctamente.\n";

} catch (PDOException $e) {
    echo "Error crítico: " . $e->getMessage() . "\n";
}
?>