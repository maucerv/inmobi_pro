<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/../includes/db.php';

// --- CASO 1: BORRAR PROPIEDAD ---
if(isset($_GET['borrar'])) {
    $id = (int)$_GET['borrar'];
    $stmt = $pdo->prepare("DELETE FROM propiedades WHERE id = ?");
    $stmt->execute([$id]);
    
    // Registrar log
    $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('ELIMINACION', ?, ?)")
        ->execute(["Se eliminó la propiedad ID: $id", $_SERVER['REMOTE_ADDR']]);
        
    header('Location: gestion_propiedades.php');
    exit;
}

// --- CASO 2: GUARDAR / EDITAR ---
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recibir datos del formulario
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $ubicacion = $_POST['ubicacion'];
    $habitaciones = $_POST['habitaciones'];
    $banos = $_POST['banos'];
    $m2 = $_POST['m2'];
    $imagen = $_POST['imagen']; // Guardamos la URL
    $descripcion = $_POST['descripcion'];
    $lat = !empty($_POST['lat']) ? $_POST['lat'] : null;
    $lng = !empty($_POST['lng']) ? $_POST['lng'] : null;
    // Checkbox: si no está marcado, no envía nada, así que validamos
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    if(empty($id)) {
        // INSERTAR NUEVA
        $sql = "INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, m2, imagen, descripcion, lat, lng, destacado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$titulo, $precio, $ubicacion, $habitaciones, $banos, $m2, $imagen, $descripcion, $lat, $lng, $destacado];
        
        $accion = "CREACION";
        $msg = "Se creó la propiedad: $titulo";
    } else {
        // ACTUALIZAR EXISTENTE
        $sql = "UPDATE propiedades SET titulo=?, precio=?, ubicacion=?, habitaciones=?, banos=?, m2=?, imagen=?, descripcion=?, lat=?, lng=?, destacado=? 
                WHERE id=?";
        $params = [$titulo, $precio, $ubicacion, $habitaciones, $banos, $m2, $imagen, $descripcion, $lat, $lng, $destacado, $id];
        
        $accion = "EDICION";
        $msg = "Se actualizó la propiedad ID: $id";
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Registrar en log
        $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES (?, ?, ?)")
            ->execute([$accion, $msg, $_SERVER['REMOTE_ADDR']]);

        header('Location: gestion_propiedades.php');
        exit;
    } catch (PDOException $e) {
        die("Error al guardar: " . $e->getMessage());
    }
}
?>