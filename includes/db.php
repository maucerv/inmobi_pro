<?php
// includes/db.php

// 1. Definir ruta segura DENTRO de la carpeta includes para evitar errores de permisos
// __DIR__ es la carpeta actual.
$db_file = __DIR__ . '/inmobiliaria_lite.db';

try {
    // 2. Conexión SQLite
    $pdo = new PDO("sqlite:" . $db_file);
    
    // Configuración de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 3. AUTO-REPARACIÓN: Crear tabla si no existe
    // Esto evita que la página falle si es la primera vez que la subes
    $queryTabla = "CREATE TABLE IF NOT EXISTS propiedades (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        precio DECIMAL(10,2) NOT NULL,
        ubicacion TEXT NOT NULL,
        habitaciones INTEGER,
        banos DECIMAL(3,1),
        imagen TEXT,
        destacado INTEGER DEFAULT 0
    )";
    $pdo->exec($queryTabla);

    // 4. DATOS DE EJEMPLO: Si está vacía, le ponemos 3 propiedades para que el diseño luzca
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, imagen, destacado) VALUES 
        ('Penthouse Luxury View', 12500000, 'Polanco, CDMX', 3, 3.5, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', 1),
        ('Residencia Moderna', 8900000, 'Juriquilla, QRO', 4, 4, 'https://images.unsplash.com/photo-1600596542815-e36cb06c378e', 1),
        ('Loft Industrial', 4500000, 'Centro, GDL', 1, 1.5, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c', 1)");
    }

} catch (PDOException $e) {
    // Si falla, muestra un mensaje limpio en lugar de romper la página
    echo "Conexión fallida: " . $e->getMessage();
    exit; // Detiene la ejecución para no mostrar errores feos después
}
?>