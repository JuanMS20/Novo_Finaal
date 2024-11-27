<?php
require_once 'config/database.php';
require_once 'config/ApiResponse.php';

try {
    // Primero intentar conectar solo a MySQL
    $pdo = new PDO(
        "mysql:host=localhost",
        "root",
        ""
    );
    
    // Si llegamos aquí, la conexión básica funciona
    // Ahora intentar conectar a la base de datos específica
    try {
        $database = new Database();
        $db = $database->getConnection();
        ApiResponse::success(null, "¡Conexión exitosa a la base de datos!");
    } catch(PDOException $e) {
        ApiResponse::error("Error conectando a la base de datos: " . $e->getMessage(), 500);
    }
} catch(PDOException $e) {
    ApiResponse::error("Error conectando a MySQL: " . $e->getMessage(), 500);
} 