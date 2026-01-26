<?php
// includes/header.php
// Solo requerimos la DB, quitamos el monitor para evitar errores 500
require_once __DIR__ . '/db.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inmobiliaria Prestige</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="css/estilos.css">

    <style>
        /* ESTILOS NAVBAR LUXURY */
        .navbar-custom {
            background-color: #0f172a; 
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .navbar-custom .navbar-brand {
            color: #ffffff !important;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .navbar-custom .nav-link {
            color: #e2e8f0 !important;
            font-family: 'Lato', sans-serif;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: #c5a47e !important; /* Dorado suave */
            transform: translateY(-2px);
        }
        
        .navbar-toggler { border-color: rgba(255,255,255,0.2); }
        .navbar-toggler-icon { filter: invert(1); }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-buildings text-warning me-2"></i>PRESTIGE
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="propiedades.php">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link" href="mapa.php">Mapa</a></li>
                </ul>
            </div>
        </div>
    </nav>