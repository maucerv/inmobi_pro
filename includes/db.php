<?php
// includes/db.php

// 1. Definir ruta segura (dentro de la carpeta del proyecto para evitar permisos denegados en Render)
// Usamos __DIR__ para ubicarlo relativo a este archivo.
$db_path = __DIR__ . '/../inmobiliaria.sqlite';

try {
    // 2. Conexión
    $pdo = new PDO("sqlite:" . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 3. AUTO-REPARACIÓN: Crear tabla si no existe
    // Esto soluciona el error de "Table not found" en Render
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

    // 4. AUTO-RELLENO: Si está vacía, insertar datos de prueba
    $stmt = $pdo->query("SELECT COUNT(*) FROM propiedades");
    if ($stmt->fetchColumn() == 0) {
        $sqlInsert = "INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, imagen, destacado) VALUES 
        ('Penthouse Luxury View', 12500000, 'Polanco, CDMX', 3, 3.5, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', 1),
        ('Residencia Moderna', 8900000, 'Juriquilla, QRO', 4, 4, 'https://images.unsplash.com/photo-1600596542815-e36cb06c378e', 1),
        ('Loft Industrial', 4500000, 'Centro, GDL', 1, 1.5, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c', 1)";
        $pdo->exec($sqlInsert);
    }

} catch (PDOException $e) {
    // En caso de error fatal, mostramos algo limpio
    echo "<div style='background:red; color:white; padding:20px; text-align:center;'>
            <h3>Error Crítico de Base de Datos</h3>
            <p>" . $e->getMessage() . "</p>
          </div>";
    die();
}
?>