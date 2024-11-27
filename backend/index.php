<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config/database.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Si es la ruta raíz, mostrar información del sistema
if ($path === '/' || $path === '/index.php') {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        echo json_encode([
            "status" => "error",
            "message" => "Error de conexión a la base de datos"
        ]);
        exit();
    }

    try {
        // Obtener usuarios registrados
        $users_query = "SELECT id, username, email, created_at FROM users ORDER BY created_at DESC";
        $users_stmt = $db->query($users_query);
        $users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener estadísticas
        $stats = [
            "total_users" => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            "total_products" => $db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            "recent_registrations" => $db->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn()
        ];

        // Obtener últimos productos
        $products_query = "SELECT p.*, u.username as seller 
                         FROM products p 
                         JOIN users u ON p.user_id = u.id 
                         ORDER BY p.created_at DESC 
                         LIMIT 5";
        $products_stmt = $db->query($products_query);
        $recent_products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "status" => "running",
            "message" => "Backend API is running",
            "server_time" => date('Y-m-d H:i:s'),
            "statistics" => $stats,
            "endpoints" => [
                "login" => "/api/login.php",
                "register" => "/api/register.php",
                "products" => "/api/products.php",
                "profile" => "/api/profile.php",
                "upload" => "/api/upload.php"
            ],
            "registered_users" => $users,
            "recent_products" => $recent_products
        ], JSON_PRETTY_PRINT);
        exit();
    } catch(PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener datos: " . $e->getMessage()
        ]);
        exit();
    }
}

// Si la ruta comienza con /api/, buscar el archivo correspondiente
if (strpos($path, '/api/') === 0) {
    $api_file = __DIR__ . $path;
    if (file_exists($api_file)) {
        require_once $api_file;
        exit();
    }
}

// Si no se encuentra el endpoint, devolver 404
http_response_code(404);
echo json_encode([
    "status" => "error",
    "message" => "Endpoint not found",
    "path" => $path
]);
exit();
?> 