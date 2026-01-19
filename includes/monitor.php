<?php
// includes/monitor.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Aseguramos que la conexión a DB exista
require_once __DIR__ . '/db.php';

function monitor_log_safe($sql, $params) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } catch (Exception $e) {
        // Silencio: Si el log falla, no rompemos la página web
        error_log("Error Monitor: " . $e->getMessage());
    }
}

// 1. FIREWALL BÁSICO (WAF)
function detectar_amenazas() {
    $patrones = ['/union\s+select/i', '/drop\s+table/i', '/<script>/i', '/javascript:/i'];
    $entrada = array_merge($_GET, $_POST);
    
    foreach ($entrada as $key => $val) {
        if (is_array($val)) continue;
        foreach ($patrones as $patron) {
            if (preg_match($patron, $val)) {
                $ip = $_SERVER['REMOTE_ADDR'];
                monitor_log_safe("INSERT INTO logs_seguridad (tipo, mensaje, ip) VALUES ('ATAQUE_BLOQUEADO', ?, ?)", 
                    ["Intento de inyección en '$key': $val", $ip]);
                die("<h1>ACCESO DENEGADO</h1><p>Actividad sospechosa detectada.</p>");
            }
        }
    }
}

// 2. REGISTRAR VISITA
function registrar_trafico() {
    global $pdo; // Necesitamos PDO para verificar select antes
    try {
        $ip = $_SERVER['REMOTE_ADDR'];
        $pagina = basename($_SERVER['PHP_SELF']);
        $fecha = date('Y-m-d');

        // Verificar si ya visitó hoy
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM visitas_web WHERE ip = ? AND fecha = ?");
        $stmt->execute([$ip, $fecha]);
        
        if ($stmt->fetchColumn() == 0) {
            monitor_log_safe("INSERT INTO visitas_web (ip, pagina, fecha) VALUES (?, ?, ?)", [$ip, $pagina, $fecha]);
        }
    } catch (Exception $e) {
        // Ignorar errores de tráfico
    }
}

// Ejecutar
detectar_amenazas();
registrar_trafico();
?>