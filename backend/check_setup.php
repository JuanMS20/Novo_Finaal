<?php
header('Content-Type: text/plain');

echo "Verificando configuración...\n\n";

// 1. Verificar extensiones de PHP
echo "1. Verificando extensiones PHP:\n";
echo "PDO instalado: " . (extension_loaded('pdo') ? 'Sí' : 'No') . "\n";
echo "PDO MySQL instalado: " . (extension_loaded('pdo_mysql') ? 'Sí' : 'No') . "\n";
echo "MySQL instalado: " . (extension_loaded('mysqli') ? 'Sí' : 'No') . "\n\n";

// 2. Verificar conexión MySQL
echo "2. Intentando conectar a MySQL:\n";
try {
    $pdo = new PDO(
        "mysql:host=localhost",
        "root",
        ""
    );
    echo "Conexión básica a MySQL: Exitosa\n";
} catch(PDOException $e) {
    echo "Error conectando a MySQL: " . $e->getMessage() . "\n";
}

// 3. Verificar base de datos
echo "\n3. Verificando base de datos novo_db:\n";
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=novo_db",
        "root",
        ""
    );
    echo "Conexión a novo_db: Exitosa\n";
    
    // Verificar tablas
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tablas encontradas: " . implode(", ", $tables) . "\n";
} catch(PDOException $e) {
    echo "Error con la base de datos: " . $e->getMessage() . "\n";
}

// 4. Verificar permisos de escritura
echo "\n4. Verificando permisos de escritura:\n";
$uploadDir = __DIR__ . '/uploads';
echo "Directorio de uploads: " . $uploadDir . "\n";
echo "Existe: " . (file_exists($uploadDir) ? 'Sí' : 'No') . "\n";
echo "Escribible: " . (is_writable($uploadDir) ? 'Sí' : 'No') . "\n";

// 5. Verificar configuración CORS
echo "\n5. Verificando configuración CORS:\n";
$headers = apache_get_modules();
echo "mod_headers habilitado: " . (in_array('mod_headers', $headers) ? 'Sí' : 'No') . "\n";
?> 