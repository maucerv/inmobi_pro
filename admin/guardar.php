<?php
session_start();
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require '../includes/db.php';

// 1. Lógica para BORRAR
if(isset($_GET['borrar'])) {
    $id = $_GET['borrar'];
    $pdo->prepare("DELETE FROM propiedades WHERE id = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}

// 2. Lógica para GUARDAR / EDITAR
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $ubicacion = $_POST['ubicacion'];
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];
    $habs = $_POST['habitaciones'];
    $banos = $_POST['banos'];
    $desc = $_POST['descripcion'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    
    // Manejo de Imagen
    $imagen = $_POST['imagen_url']; // Por defecto usa la URL
    
    // Si subieron un archivo, lo procesamos
    if(isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] == 0) {
        $nombre = time() . "_" . $_FILES['imagen_file']['name'];
        $ruta = "../uploads/" . $nombre;
        if(move_uploaded_file($_FILES['imagen_file']['tmp_name'], $ruta)) {
            $imagen = "uploads/" . $nombre;
        }
    }

    if(empty($id)) {
        // INSERTAR NUEVO
        $sql = "INSERT INTO propiedades (titulo, precio, ubicacion, lat, lon, habitaciones, banos, descripcion, imagen, destacado, vistas) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $precio, $ubicacion, $lat, $lon, $habs, $banos, $desc, $imagen, $destacado]);
    } else {
        // ACTUALIZAR EXISTENTE
        $sql = "UPDATE propiedades SET titulo=?, precio=?, ubicacion=?, lat=?, lon=?, habitaciones=?, banos=?, descripcion=?, imagen=?, destacado=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $precio, $ubicacion, $lat, $lon, $habs, $banos, $desc, $imagen, $destacado, $id]);
    }

    header('Location: index.php');
    exit;
}
?>