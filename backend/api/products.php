<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/database.php';

function sendJsonResponse($status, $message = null, $data = null, $httpCode = 200) {
    http_response_code($httpCode);
    $response = ["status" => $status];
    if ($message !== null) $response["message"] = $message;
    if ($data !== null) $response["data"] = $data;
    echo json_encode($response);
    exit();
}

// Para solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendJsonResponse("success", "Preflight OK");
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Obtener productos con filtros
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $category = isset($_GET['category']) ? $db->real_escape_string($_GET['category']) : null;
        $condition = isset($_GET['condition']) ? $db->real_escape_string($_GET['condition']) : null;
        $minPrice = isset($_GET['minPrice']) ? floatval($_GET['minPrice']) : null;
        $maxPrice = isset($_GET['maxPrice']) ? floatval($_GET['maxPrice']) : null;

        // Query base
        $query = "SELECT p.*, u.username as seller_name 
                FROM products p 
                JOIN users u ON p.user_id = u.id 
                WHERE 1=1";
        
        // Agregar filtros si existen
        if ($category) {
            $query .= " AND p.category = '$category'";
        }
        if ($condition) {
            $query .= " AND p.condition_state = '$condition'";
        }
        if ($minPrice !== null) {
            $query .= " AND p.price >= $minPrice";
        }
        if ($maxPrice !== null) {
            $query .= " AND p.price <= $maxPrice";
        }

        error_log("Query de productos: " . $query);

        $result = $db->query($query);
        if (!$result) {
            throw new Exception("Error en la consulta: " . $db->error);
        }

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        error_log("Productos encontrados: " . count($products));
        sendJsonResponse("success", null, $products);
    }

    // Crear nuevo producto
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents("php://input");
        error_log("Datos recibidos: " . $input);
        
        $data = json_decode($input);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decodificando JSON: " . json_last_error_msg());
        }

        if (!isset($data->title) || !isset($data->price) || !isset($data->category)) {
            sendJsonResponse("error", "Faltan datos requeridos", null, 400);
        }

        $title = $db->real_escape_string($data->title);
        $description = $db->real_escape_string($data->description ?? '');
        $price = floatval($data->price);
        $category = $db->real_escape_string($data->category);
        $condition = $db->real_escape_string($data->condition ?? 'nuevo');
        $image_url = $db->real_escape_string($data->image_url ?? '');
        $user_id = 1; // Temporal: Deberías obtener esto de la sesión del usuario

        $query = "INSERT INTO products (user_id, title, description, price, category, condition_state, image_url) 
                VALUES ($user_id, '$title', '$description', $price, '$category', '$condition', '$image_url')";

        error_log("Query de inserción: " . $query);

        if (!$db->query($query)) {
            throw new Exception("Error insertando producto: " . $db->error);
        }

        $product_id = $db->insert_id;
        sendJsonResponse("success", "Producto creado exitosamente", ["id" => $product_id], 201);
    }
} catch (Exception $e) {
    error_log("Error en products.php: " . $e->getMessage());
    sendJsonResponse("error", $e->getMessage(), null, 500);
}
?> 