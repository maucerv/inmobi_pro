<?php

// admin/guardar.php
session_start();

// 1. SEGURIDAD: Solo usuarios logueados pueden guardar/borrar
if(!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit; 
}

require_once __DIR__ . '/../includes/db.php';

// --- CASO 1: BORRAR PROPIEDAD ---
if(isset($_GET['borrar'])) {
    $id = (int)$_GET['borrar'];
    
    // Verificamos si es SuperAdmin (Opcional: Si quieres que solo SuperAdmin borre, descomenta esto)
    /*
    if($_SESSION['admin_rol'] !== 'superadmin') {
        die("Acceso denegado: Solo el SuperAdmin puede borrar.");
    }
    */

    $stmt = $pdo->prepare("DELETE FROM propiedades WHERE id = ?");
    $stmt->execute([$id]);
    
    // Registrar en log de seguridad
    $ip = $_SERVER['REMOTE_ADDR'];
    $user = $_SESSION['admin_nombre'];
    $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('ELIMINACION', ?, ?)")
        ->execute(["Usuario $user eliminó propiedad ID: $id", $ip]);
        
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
    $imagen = $_POST['imagen'];
    $descripcion = $_POST['descripcion'];
    
    // Nuevos campos
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $lat = !empty($_POST['lat']) ? (float)$_POST['lat'] : null;
    $lng = !empty($_POST['lng']) ? (float)$_POST['lng'] : null;
    $precio = (float)$_POST['precio'];// Usamos 'lng' consistente con db.php
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    // Preparar SQL dinámico
    if(empty($id)) {
        // INSERTAR (Nueva)
        $sql = "INSERT INTO propiedades (titulo, precio, ubicacion, habitaciones, banos, m2, imagen, descripcion, lat, lng, destacado, tipo_operacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$titulo, $precio, $ubicacion, $habitaciones, $banos, $m2, $imagen, $descripcion, $lat, $lng, $destacado, $tipo_operacion];
        $accion_log = "CREACION";
    } else {
        // ACTUALIZAR (Existente)
        $sql = "UPDATE propiedades SET titulo=?, precio=?, ubicacion=?, habitaciones=?, banos=?, m2=?, imagen=?, descripcion=?, lat=?, lng=?, destacado=?, tipo_operacion=? 
                WHERE id=?";
        $params = [$titulo, $precio, $ubicacion, $habitaciones, $banos, $m2, $imagen, $descripcion, $lat, $lng, $destacado, $tipo_operacion, $id];
        $accion_log = "EDICION";
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Registrar Log
        $user = $_SESSION['admin_nombre'];
        $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES (?, ?, ?)")
            ->execute([$accion_log, "$user gestionó: $titulo ($tipo_operacion)", $_SERVER['REMOTE_ADDR']]);

        header('Location: gestion_propiedades.php');
        exit;
    } catch (PDOException $e) {
        die("Error al guardar en base de datos: " . $e->getMessage());
    }
}
?>