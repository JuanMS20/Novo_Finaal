<?php
class ApiResponse {
    public static function success($data = null, $message = 'Success') {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
        exit();
    }

    public static function error($message = 'Error', $code = 400, $errors = null) {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ]);
        exit();
    }
} 