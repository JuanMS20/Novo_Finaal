<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/database.php';

function sendJsonResponse($status, $message, $data = null, $httpCode = 200) {
    http_response_code($httpCode);
    $response = [
        "status" => $status,
        "message" => $message
    ];
    if ($data !== null) {
        $response["data"] = $data;
    }
    echo json_encode($response);
    exit();
}

// Para solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendJsonResponse("success", "Preflight OK");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse("error", "Método no permitido", null, 405);
}

// Obtener datos enviados
$input = file_get_contents("php://input");
$data = json_decode($input);

if (json_last_error() !== JSON_ERROR_NONE) {
    sendJsonResponse("error", "JSON inválido: " . json_last_error_msg(), null, 400);
}

// Validar datos
if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
    sendJsonResponse("error", "Faltan datos requeridos", null, 400);
}

try {
    // Crear conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Verificar si el email ya existe
    $email = $db->real_escape_string($data->email);
    $result = $db->query("SELECT id FROM users WHERE email = '$email'");
    
    if ($result && $result->num_rows > 0) {
        sendJsonResponse("error", "El email ya está registrado", null, 409);
    }

    // Verificar si el username ya existe
    $username = $db->real_escape_string($data->username);
    $result = $db->query("SELECT id FROM users WHERE username = '$username'");
    
    if ($result && $result->num_rows > 0) {
        sendJsonResponse("error", "El nombre de usuario ya está en uso", null, 409);
    }

    // Insertar nuevo usuario
    $password_hash = password_hash($data->password, PASSWORD_DEFAULT);
    $password_hash = $db->real_escape_string($password_hash);
    
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";
    if ($db->query($query)) {
        $user_id = $db->insert_id;
        
        // Crear perfil de usuario
        $query = "INSERT INTO user_profiles (user_id) VALUES ($user_id)";
        if (!$db->query($query)) {
            throw new Exception("Error al crear el perfil de usuario: " . $db->error);
        }

        sendJsonResponse("success", "Usuario registrado exitosamente", [
            "username" => $data->username,
            "email" => $data->email
        ], 201);
    } else {
        throw new Exception("Error al registrar el usuario: " . $db->error);
    }
} catch (Exception $e) {
    error_log("Error en registro: " . $e->getMessage());
    sendJsonResponse("error", "Error en el servidor: " . $e->getMessage(), null, 500);
}
?> 