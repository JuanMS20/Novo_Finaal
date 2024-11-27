<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Obtener perfil
        if(isset($_GET['user_id'])) {
            $query = "SELECT up.*, u.username, u.email 
                     FROM user_profiles up 
                     JOIN users u ON up.user_id = u.id 
                     WHERE up.user_id = :user_id";
            
            try {
                $stmt = $db->prepare($query);
                $stmt->bindParam(":user_id", $_GET['user_id']);
                $stmt->execute();
                
                if($stmt->rowCount() > 0) {
                    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode($profile);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Perfil no encontrado."));
                }
            } catch(PDOException $e) {
                http_response_code(503);
                echo json_encode(array("message" => "Error: " . $e->getMessage()));
            }
        }
        break;
        
    case 'POST':
        // Crear perfil
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->user_id)) {
            $query = "INSERT INTO user_profiles (user_id, full_name, phone, institution, bio, profile_image_url) 
                     VALUES (:user_id, :full_name, :phone, :institution, :bio, :profile_image_url)";
            
            try {
                $stmt = $db->prepare($query);
                
                $stmt->bindParam(":user_id", $data->user_id);
                $stmt->bindParam(":full_name", $data->full_name);
                $stmt->bindParam(":phone", $data->phone);
                $stmt->bindParam(":institution", $data->institution);
                $stmt->bindParam(":bio", $data->bio);
                $stmt->bindParam(":profile_image_url", $data->profile_image_url);
                
                if($stmt->execute()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Perfil creado exitosamente."));
                }
            } catch(PDOException $e) {
                http_response_code(503);
                echo json_encode(array("message" => "Error: " . $e->getMessage()));
            }
        }
        break;
        
    case 'PUT':
        // Actualizar perfil
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->user_id)) {
            $query = "UPDATE user_profiles SET 
                     full_name = :full_name,
                     phone = :phone,
                     institution = :institution,
                     bio = :bio,
                     profile_image_url = :profile_image_url
                     WHERE user_id = :user_id";
            
            try {
                $stmt = $db->prepare($query);
                
                $stmt->bindParam(":user_id", $data->user_id);
                $stmt->bindParam(":full_name", $data->full_name);
                $stmt->bindParam(":phone", $data->phone);
                $stmt->bindParam(":institution", $data->institution);
                $stmt->bindParam(":bio", $data->bio);
                $stmt->bindParam(":profile_image_url", $data->profile_image_url);
                
                if($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Perfil actualizado exitosamente."));
                }
            } catch(PDOException $e) {
                http_response_code(503);
                echo json_encode(array("message" => "Error: " . $e->getMessage()));
            }
        }
        break;
}
?> 