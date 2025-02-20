<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class TareasAPI {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Crear nueva tarea
    public function crear() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->titulo) && !empty($data->descripcion)) {
            $query = "INSERT INTO tareas (titulo, descripcion, estado) VALUES (:titulo, :descripcion, :estado)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":titulo", $data->titulo);
            $stmt->bindParam(":descripcion", $data->descripcion);
            $stmt->bindParam(":estado", $data->estado);
            
            if($stmt->execute()) {
                http_response_code(201);
                echo json_encode(array("mensaje" => "Tarea creada con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("mensaje" => "No se pudo crear la tarea."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensaje" => "No se puede crear la tarea. Datos incompletos."));
        }
    }

    // Leer todas las tareas
    public function leer() {
        $query = "SELECT * FROM tareas";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $tareas_arr = array();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($tareas_arr, $row);
            }
            
            http_response_code(200);
            echo json_encode($tareas_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("mensaje" => "No se encontraron tareas."));
        }
    }

    // Actualizar tarea
    public function actualizar() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $query = "UPDATE tareas 
                     SET titulo = :titulo, 
                         descripcion = :descripcion, 
                         estado = :estado 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":titulo", $data->titulo);
            $stmt->bindParam(":descripcion", $data->descripcion);
            $stmt->bindParam(":estado", $data->estado);
            $stmt->bindParam(":id", $data->id);
            
            if($stmt->execute()) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Tarea actualizada."));
            } else {
                http_response_code(503);
                echo json_encode(array("mensaje" => "No se pudo actualizar la tarea."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensaje" => "No se puede actualizar la tarea. ID no proporcionado."));
        }
    }

    // Eliminar tarea
    public function eliminar() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $query = "DELETE FROM tareas WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $data->id);
            
            if($stmt->execute()) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Tarea eliminada."));
            } else {
                http_response_code(503);
                echo json_encode(array("mensaje" => "No se pudo eliminar la tarea."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensaje" => "No se puede eliminar la tarea. ID no proporcionado."));
        }
    }
}

// Procesar la solicitud
$tareas = new TareasAPI();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Max-Age: 86400");
    header("Content-Length: 0");
    header("Content-Type: text/plain");
    die();
}

switch($method) {
    case 'POST':
        $tareas->crear();
        break;
    case 'GET':
        $tareas->leer();
        break;
    case 'PUT':
        $tareas->actualizar();
        break;
    case 'DELETE':
        $tareas->eliminar();
        break;
    default:
        http_response_code(405);
        echo json_encode(array("mensaje" => "Método no permitido"));
        break;
}
?> 