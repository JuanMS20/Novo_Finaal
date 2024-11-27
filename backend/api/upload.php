<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image'])) {
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
        
        $extensions = array("jpeg", "jpg", "png");
        
        if (!in_array($file_ext, $extensions)) {
            $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
        }
        
        if ($file_size > 5242880) {
            $errors[] = 'El archivo debe ser menor a 5 MB';
        }
        
        if (empty($errors)) {
            $unique_name = uniqid() . '.' . $file_ext;
            $upload_path = '../uploads/' . $unique_name;
            
            // Crear directorio de uploads si no existe
            if (!file_exists('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                echo json_encode(array(
                    "message" => "Imagen subida exitosamente",
                    "file_path" => '/uploads/' . $unique_name
                ));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Error al subir la imagen"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("errors" => $errors));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "No se recibió ninguna imagen"));
    }
}
?> 