<?php
// includes/monitor.php
// ESTE ARCHIVO ES TU FIREWALL Y CONTADOR DE VISITAS

// Aseguramos conexión a DB (si no está incluida ya)
require_once __DIR__ . '/db.php';

$ip_actual = $_SERVER['REMOTE_ADDR'];
$pagina_actual = $_SERVER['REQUEST_URI'];
$fecha_hoy = date('Y-m-d');

// --- 1. SISTEMA ANTI-INYECCIÓN SQL (WAF Básico) ---
// Palabras prohibidas que suelen usar los hackers
$patrones_maliciosos = [
    'UNION SELECT', 'OR 1=1', 'DROP TABLE', 'DELETE FROM', 'INSERT INTO', 
    '<script>', 'javascript:', 'xp_cmdshell', '--', '1=1'
];

// Función para escanear datos
function escanear_amenaza($datos, $patrones) {
    $datos_str = strtoupper(json_encode($datos));
    foreach ($patrones as $patron) {
        if (strpos($datos_str, $patron) !== false) {
            return $patron; // Devuelve qué patrón encontró
        }
    }
    return false;
}

// Revisamos todo lo que entra por URL (GET) y Formularios (POST)
$amenaza_get = escanear_amenaza($_GET, $patrones_maliciosos);
$amenaza_post = escanear_amenaza($_POST, $patrones_maliciosos);

if ($amenaza_get || $amenaza_post) {
    $tipo_ataque = $amenaza_get ? "SQL Injection (URL) detectado: $amenaza_get" : "SQL Injection (Form) detectado: $amenaza_post";
    
    // 1. Registramos el ataque en la base de datos
    try {
        $stmt = $pdo->prepare("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('ATAQUE_BLOQUEADO', ?, ?)");
        $stmt->execute([$tipo_ataque, $ip_actual]);
    } catch (Exception $e) { /* Si falla el log, bloqueamos igual */ }

    // 2. Bloqueamos la ejecución INMEDIATAMENTE
    die("<div style='background:red; color:white; padding:20px; font-family:sans-serif; text-align:center;'>
            <h1>ACCESO BLOQUEADO</h1>
            <p>El sistema de seguridad ha detectado una solicitud maliciosa.</p>
            <p>Tu IP ($ip_actual) ha sido registrada.</p>
         </div>");
}

// --- 2. CONTADOR DE VISITAS (Tráfico Legítimo) ---
// Solo contamos si no es un archivo de admin o recursos estáticos
if (strpos($pagina_actual, '/admin') === false && strpos($pagina_actual, '.css') === false) {
    try {
        // Registramos la visita
        $stmt = $pdo->prepare("INSERT INTO visitas_web (ip, pagina, fecha) VALUES (?, ?, ?)");
        $stmt->execute([$ip_actual, $pagina_actual, $fecha_hoy]);
    } catch (Exception $e) {
        // Ignorar errores de log de visitas para no detener la página
    }
}
?>