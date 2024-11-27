<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/database.php';
require_once '../config/ApiResponse.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $errors = [];
    if (empty($data->email)) {
        $errors['email'] = 'El email es requerido';
    }
    if (empty($data->password)) {
        $errors['password'] = 'La contraseña es requerida';
    }

    if (!empty($errors)) {
        echo ApiResponse::error('Error de validación', 400, $errors);
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        echo ApiResponse::error('Error de conexión a la base de datos', 500);
        exit();
    }

    try {
        $query = "SELECT u.*, up.full_name, up.phone, up.institution, up.bio, up.profile_image_url 
                 FROM users u 
                 LEFT JOIN user_profiles up ON u.id = up.user_id 
                 WHERE u.email = :email 
                 LIMIT 1";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $data->email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($data->password, $user['password'])) {
                // Remove sensitive data
                unset($user['password']);
                
                echo ApiResponse::success([
                    'user' => $user,
                    'token' => base64_encode($user['id'] . ':' . time())
                ], 'Login exitoso');
            } else {
                echo ApiResponse::error('Contraseña incorrecta', 401);
            }
        } else {
            echo ApiResponse::error('Usuario no encontrado', 404);
        }
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo ApiResponse::error('Error en el servidor', 503);
    }
} else {
    echo ApiResponse::error('Método no permitido', 405);
}
?> 