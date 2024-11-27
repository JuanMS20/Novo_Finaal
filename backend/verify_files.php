<?php
header('Content-Type: text/plain');

echo "Verificando archivos necesarios...\n\n";

$phpDir = "C:\\php";
$extDir = $phpDir . "\\ext";

$archivosNecesarios = [
    $extDir . "\\php_pdo_mysql.dll",
    $extDir . "\\php_mysqli.dll",
    $phpDir . "\\libmysql.dll"
];

foreach ($archivosNecesarios as $archivo) {
    echo "Verificando " . basename($archivo) . "... ";
    if (file_exists($archivo)) {
        echo "✓ Encontrado\n";
    } else {
        echo "✗ No encontrado\n";
    }
}

echo "\nVerificando extensiones habilitadas en php.ini:\n";
$phpini = file_get_contents($phpDir . "\\php.ini");

$extensiones = [
    "extension=pdo_mysql",
    "extension=mysqli"
];

foreach ($extensiones as $ext) {
    echo "Buscando '$ext'... ";
    if (strpos($phpini, $ext) !== false && strpos($phpini, ";$ext") === false) {
        echo "✓ Habilitada\n";
    } else {
        echo "✗ No habilitada o comentada\n";
    }
}

echo "\nPara habilitar PDO_MYSQL:\n";
echo "1. Descarga PHP desde https://windows.php.net/download/\n";
echo "2. Del ZIP, copia:\n";
echo "   - php_pdo_mysql.dll a C:\\php\\ext\\\n";
echo "   - php_mysqli.dll a C:\\php\\ext\\\n";
echo "   - libmysql.dll a C:\\php\\\n";
echo "3. Edita C:\\php\\php.ini y descomenta:\n";
echo "   extension=pdo_mysql\n";
echo "   extension=mysqli\n";
echo "4. Reinicia el servidor PHP\n"; 