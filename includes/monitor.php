<?php
// includes/monitor.php
if (session_status() === PHP_SESSION_NONE) session_start();

// --- 1. FIREWALL BÁSICO (WAF) ---
function detectar_amenazas() {
    global $pdo;
    
    // Palabras prohibidas comunes en inyecciones SQL y XSS
    $patrones_peligrosos = [
        '/union\s+select/i', 
        '/information_schema/i', 
        '/drop\s+table/i', 
        '/<script>/i', 
        '/javascript:/i', 
        '/onload=/i'
    ];

    // Revisamos GET y POST
    $entrada = array_merge($_GET, $_POST);
    
    foreach ($entrada as $key => $val) {
        if (is_array($val)) continue; // Saltamos arrays por simplicidad
        
        foreach ($patrones_peligrosos as $patron) {
            if (preg_match($patron, $val)) {
                // ¡AMENAZA DETECTADA!
                $ip = $_SERVER['REMOTE_ADDR'];
                $mensaje = "Intento de inyección detectado en campo '$key': $val";
                
                // 1. Loguear el ataque en la base de datos
                $stmt = $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('ATAQUE_BLOQUEADO', ?, ?)");
                $stmt->execute([$mensaje, $ip]);
                
                // 2. Matar la ejecución
                die("<h1 style='color:red; text-align:center; margin-top:50px;'>ACCESO BLOQUEADO POR SEGURIDAD</h1><p style='text-align:center;'>Su IP ($ip) ha sido registrada por actividad sospechosa.</p>");
            }
        }
    }
}

// --- 2. REGISTRAR VISITA (Tráfico) ---
function registrar_trafico() {
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'];
    $pagina = basename($_SERVER['PHP_SELF']);
    $fecha = date('Y-m-d');

    // Solo contamos 1 visita por IP por día para no inflar números
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM visitas_web WHERE ip = ? AND fecha = ?");
    $stmt->execute([$ip, $fecha]);
    
    if ($stmt->fetchColumn() == 0) {
        $ins = $pdo->prepare("INSERT INTO visitas_web (ip, pagina, fecha) VALUES (?, ?, ?)");
        $ins->execute([$ip, $pagina, $fecha]);
    }
}

// Ejecutar automáticamente
detectar_amenazas();
registrar_trafico();
?>