<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require '../includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['nuevo_user'];
    $pass = password_hash($_POST['nuevo_pass'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
        $stmt->execute([$user, $pass]);
    } catch(PDOException $e) {
        // Manejar error si el usuario ya existe
    }
    
    header('Location: index.php');
}
?>