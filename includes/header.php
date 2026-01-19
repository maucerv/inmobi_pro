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
    
    <link rel="stylesheet" href="css/estilos.css">

    <style>
        /* ESTILOS PERSONALIZADOS PARA EL NAVBAR */
        .navbar-custom {
            background-color: #0f172a; /* Fondo Azul Oscuro (Navy) */
            padding: 1rem 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3); /* Sombra elegante */
        }

        /* Texto Blanco por defecto */
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff !important;
            font-family: 'Lato', sans-serif; /* Fuente legible */
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease; /* Transición suave */
        }

        /* Marca (Logo) con fuente elegante */
        .navbar-custom .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* EFECTO HOVER: Amarillo al pasar el mouse */
        .navbar-custom .nav-link:hover,
        .navbar-custom .navbar-brand:hover {
            color: #ffc107 !important; /* Amarillo Brillante */
            transform: translateY(-2px); /* Pequeña elevación */
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5); /* Brillo sutil */
        }

        /* Botón hamburguesa para móvil (Blanco) */
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
        }
        /* Esto hace que las líneas del menú móvil sean blancas */
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Ajuste del menú desplegable en móvil */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: #0f172a; /* Mismo fondo oscuro */
                padding: 15px;
                border-radius: 0 0 10px 10px;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-buildings text-warning me-2"></i>PRESTIGE
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="propiedades.php">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="mapa.php">
                            <i class="bi bi-map-fill me-1"></i> Mapa Interactivo
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>