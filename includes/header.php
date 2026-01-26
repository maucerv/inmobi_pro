<?php
// includes/header.php
require_once __DIR__ . '/db.php'; 

// --- AQUÍ ACTIVAMOS LA SEGURIDAD ---
require_once __DIR__ . '/monitor.php'; 
// -----------------------------------
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <link rel="stylesheet" href="/css/estilos.css">

    <style>
        :root { --color-gold: #c5a47e; --color-dark: #0f172a; }
        
        .navbar-custom {
            background-color: var(--color-dark); 
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            color: #fff !important;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-family: 'Lato', sans-serif;
            margin: 0 10px;
            transition: 0.3s;
        }
        .nav-link:hover { color: var(--color-gold) !important; }
        .navbar-toggler { border-color: rgba(255,255,255,0.3); }
        .navbar-toggler-icon { filter: invert(1); }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/index.php">
                <i class="bi bi-buildings text-warning me-2"></i>PRESTIGE
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="/propiedades.php">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link" href="/mapa.php">Mapa</a></li>
                </ul>
            </div>
        </div>
    </nav>