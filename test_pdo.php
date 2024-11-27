<?php
try {
    echo "PDO drivers disponibles:\n";
    print_r(PDO::getAvailableDrivers());
    
    echo "\nIntentando conectar a MySQL...\n";
    $pdo = new PDO("mysql:host=localhost;dbname=Base_novo", "root", "");
    echo "¡Conexión exitosa!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 