<?php
require_once 'config/database.php';
require_once 'config/ApiResponse.php';

try {
    // 1. Verificar extensiones
    $diagnostico = [
        'extensiones' => [
            'pdo' => extension_loaded('pdo'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'mysqli' => extension_loaded('mysqli')
        ]
    ];

    // 2. Intentar conexión básica a MySQL
    try {
        $pdo = new PDO("mysql:host=localhost", "root", "");
        $diagnostico['mysql_connection'] = true;
    } catch(PDOException $e) {
        $diagnostico['mysql_connection'] = false;
        $diagnostico['mysql_error'] = $e->getMessage();
    }

    // 3. Verificar base de datos
    if ($diagnostico['mysql_connection']) {
        try {
            $db = new Database();
            $result = $db->testConnection();
            $diagnostico['database_check'] = $result;
        } catch(Exception $e) {
            $diagnostico['database_check'] = false;
            $diagnostico['database_error'] = $e->getMessage();
        }
    }

    // 4. Verificar permisos de directorio
    $uploadDir = __DIR__ . '/uploads';
    $diagnostico['permisos'] = [
        'uploads_exists' => file_exists($uploadDir),
        'uploads_writable' => is_writable($uploadDir)
    ];

    // Enviar respuesta
    ApiResponse::success($diagnostico, "Diagnóstico completado");

} catch(Exception $e) {
    ApiResponse::error("Error en diagnóstico: " . $e->getMessage(), 500);
} 