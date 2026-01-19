<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require '../includes/db.php';

// Mostrar errores PHP para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // 1. Lógica para BORRAR
    if(isset($_GET['borrar'])) {
        $pdo->prepare("DELETE FROM propiedades WHERE id = ?")->execute([$_GET['borrar']]);
        header('Location: gestion_propiedades.php');
        exit;
    }

    // 2. Lógica para GUARDAR / EDITAR
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        // Recolectar datos y asegurar que lat/lon no sean vacíos (SQLite prefiere NULL o 0)
        $lat = !empty($_POST['lat']) ? $_POST['lat'] : 0;
        $lon = !empty($_POST['lon']) ? $_POST['lon'] : 0;
        $destacado = isset($_POST['destacado']) ? 1 : 0;

        $imagen = $_POST['imagen_url'];
        
        // Manejo de archivo subido
        if(isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] == 0) {
            $nombre = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['imagen_file']['name']);
            $ruta_destino = "../uploads/" . $nombre;
            if(move_uploaded_file($_FILES['imagen_file']['tmp_name'], $ruta_destino)) {
                $imagen = "uploads/" . $nombre;
            } else {
                throw new Exception("Error al mover el archivo subido. Verifica permisos de carpeta uploads/.");
            }
        }

        if(empty($id)) {
            // INSERTAR
            $sql = "INSERT INTO propiedades (titulo, precio, ubicacion, lat, lon, habitaciones, banos, descripcion, imagen, destacado, vistas) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
            $params = [$_POST['titulo'], $_POST['precio'], $_POST['ubicacion'], $lat, $lon, $_POST['habitaciones'], $_POST['banos'], $_POST['descripcion'], $imagen, $destacado];
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        } else {
            // ACTUALIZAR
            $sql = "UPDATE propiedades SET titulo=?, precio=?, ubicacion=?, lat=?, lon=?, habitaciones=?, banos=?, descripcion=?, imagen=?, destacado=? WHERE id=?";
            $params = [$_POST['titulo'], $_POST['precio'], $_POST['ubicacion'], $lat, $lon, $_POST['habitaciones'], $_POST['banos'], $_POST['descripcion'], $imagen, $destacado, $id];
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }

        // Si todo sale bien, redirigir
        header('Location: gestion_propiedades.php');
        exit;
    }
} catch (Exception $e) {
    // SI FALLA, MUESTRA EL ERROR EN GRANDE
    die("<div style='background:red; color:white; padding:20px; font-family:sans-serif;'>
            <h1>Error al guardar</h1>
            <p>" . $e->getMessage() . "</p>
            <a href='formulario.php' style='color:yellow'>Volver</a>
         </div>");
}
?>