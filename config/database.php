<?php
class Database {
    private $host = "localhost";
    private $database_name = "tareas_db";
    private $username = "root";
    private $password = "1234";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Verificar las extensiones disponibles
        if (!extension_loaded('pdo_mysql') && !extension_loaded('mysqli')) {
            die(json_encode(array(
                "error" => true,
                "mensaje" => "No hay extensiones de MySQL disponibles. Por favor, instale pdo_mysql o mysqli"
            )));
        }
        
        try {
            // Intentar conexión con PDO
            if (extension_loaded('pdo_mysql')) {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->database_name,
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            }
            
            die(json_encode(array(
                "error" => true,
                "mensaje" => "No se pudo establecer la conexión con la base de datos"
            )));
            
        } catch(Exception $e) {
            die(json_encode(array(
                "error" => true,
                "mensaje" => "Error de conexión: " . $e->getMessage()
            )));
        }
    }
}
?> 