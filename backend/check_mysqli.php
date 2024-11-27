<?php
echo "Verificando configuración de PHP y MySQL...\n\n";

// Verificar si mysqli está instalado
echo "Extensión mysqli: " . (extension_loaded('mysqli') ? "Instalada ✓" : "No instalada ✗") . "\n";

// Verificar archivo php.ini
$php_ini_path = php_ini_loaded_file();
echo "Archivo php.ini: " . ($php_ini_path ? $php_ini_path : "No encontrado") . "\n";

// Verificar si la extensión está habilitada en php.ini
if ($php_ini_path) {
    $php_ini = file_get_contents($php_ini_path);
    $mysqli_enabled = strpos($php_ini, 'extension=mysqli') !== false && 
                     strpos($php_ini, ';extension=mysqli') === false;
    echo "mysqli habilitado en php.ini: " . ($mysqli_enabled ? "Sí ✓" : "No ✗") . "\n";
}

echo "\nPara habilitar mysqli:\n";
echo "1. Abra el archivo php.ini\n";
echo "2. Busque la línea ';extension=mysqli'\n";
echo "3. Quite el punto y coma del inicio de la línea para que quede 'extension=mysqli'\n";
echo "4. Guarde el archivo y reinicie el servidor PHP\n"; 