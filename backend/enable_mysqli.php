<?php
echo "Intentando habilitar mysqli...\n\n";

$php_ini_path = php_ini_loaded_file();
if (!$php_ini_path) {
    echo "Error: No se pudo encontrar el archivo php.ini\n";
    exit(1);
}

echo "Archivo php.ini encontrado en: " . $php_ini_path . "\n";

// Leer el contenido actual
$content = file_get_contents($php_ini_path);
if ($content === false) {
    echo "Error: No se pudo leer el archivo php.ini\n";
    exit(1);
}

// Verificar si mysqli ya está habilitado
if (strpos($content, 'extension=mysqli') !== false && strpos($content, ';extension=mysqli') === false) {
    echo "mysqli ya está habilitado en php.ini\n";
    exit(0);
}

// Habilitar mysqli
$content = str_replace(';extension=mysqli', 'extension=mysqli', $content);

// Intentar escribir el archivo
if (file_put_contents($php_ini_path, $content) === false) {
    echo "Error: No se pudo escribir en php.ini. Por favor, habilita mysqli manualmente:\n";
    echo "1. Abre " . $php_ini_path . " como administrador\n";
    echo "2. Busca la línea ';extension=mysqli'\n";
    echo "3. Quita el punto y coma del inicio\n";
    echo "4. Guarda el archivo y reinicia el servidor PHP\n";
    exit(1);
}

echo "¡mysqli ha sido habilitado!\n";
echo "Por favor, reinicia el servidor PHP para que los cambios surtan efecto.\n"; 