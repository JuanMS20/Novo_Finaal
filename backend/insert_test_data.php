<?php
header('Content-Type: application/json');
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Crear un usuario de prueba si no existe
    $query = "INSERT IGNORE INTO users (username, email, password) VALUES 
             ('usuario_prueba', 'test@example.com', '" . password_hash('password123', PASSWORD_DEFAULT) . "')";
    
    if (!$db->query($query)) {
        throw new Exception("Error creando usuario de prueba: " . $db->error);
    }

    // Obtener el ID del usuario
    $user_id = $db->insert_id ?: 1;

    // Datos de productos de prueba
    $products = [
        [
            'title' => 'Libro de Cálculo Avanzado',
            'description' => 'Texto completo para cursos de cálculo universitario',
            'price' => 75000,
            'category' => 'libros',
            'condition_state' => 'nuevo',
            'image_url' => 'https://th.bing.com/th/id/OIP.YJg6c6rZqAzJTCBwZThSggHaKc?rs=1&pid=ImgDetMain'
        ],
        [
            'title' => 'Pack de Apuntes de Biología',
            'description' => 'Resúmenes detallados de biología celular y molecular',
            'price' => 30000,
            'category' => 'apuntes',
            'condition_state' => 'usado',
            'image_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3'
        ],
        [
            'title' => 'Laptop para Estudiantes',
            'description' => 'Perfecta para tomar notas y hacer trabajos',
            'price' => 500000,
            'category' => 'electronica',
            'condition_state' => 'seminuevo',
            'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853'
        ]
    ];

    // Insertar productos de prueba
    foreach ($products as $product) {
        $query = "INSERT INTO products (user_id, title, description, price, category, condition_state, image_url) 
                 VALUES (
                     $user_id,
                     '" . $db->real_escape_string($product['title']) . "',
                     '" . $db->real_escape_string($product['description']) . "',
                     " . floatval($product['price']) . ",
                     '" . $db->real_escape_string($product['category']) . "',
                     '" . $db->real_escape_string($product['condition_state']) . "',
                     '" . $db->real_escape_string($product['image_url']) . "'
                 )";

        if (!$db->query($query)) {
            throw new Exception("Error insertando producto: " . $db->error);
        }
    }

    echo json_encode([
        "status" => "success",
        "message" => "Datos de prueba insertados correctamente"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
} 