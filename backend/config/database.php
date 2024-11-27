<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
require_once __DIR__ . '/ApiResponse.php';

class Database {
    private $conn;
    private $host = "localhost";
    private $dbname = "novo_db";
    private $username = "root";
    private $password = "";

    public function __construct() {
        try {
            // Suprimir advertencias para evitar salida HTML
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            
            // Intentar la conexión usando mysqli
            $this->conn = @new mysqli($this->host, $this->username, $this->password);
            
            // Verificar la conexión
            if ($this->conn->connect_error) {
                throw new Exception($this->conn->connect_error);
            }

            // Intentar seleccionar la base de datos
            if (!$this->conn->select_db($this->dbname)) {
                // Si la base de datos no existe, intentar crearla
                if (!$this->conn->query("CREATE DATABASE IF NOT EXISTS " . $this->dbname)) {
                    throw new Exception($this->conn->error);
                }
                $this->conn->select_db($this->dbname);
            }

            // Establecer charset
            $this->conn->set_charset("utf8");
            
        } catch(Exception $e) {
            $this->sendJsonError("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        if (!$this->conn || $this->conn->connect_error) {
            $this->sendJsonError("No hay conexión a la base de datos");
        }
        return $this->conn;
    }

    private function sendJsonError($message) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
            header("HTTP/1.1 500 Internal Server Error");
        }
        echo json_encode([
            "status" => "error",
            "message" => $message
        ]);
        exit();
    }
}
?> 