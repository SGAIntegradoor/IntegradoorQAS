<?php 

session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

// Mostrar errores en desarrollo
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $data = json_decode(file_get_contents("php://input"), true);
} else {
    $data = $_POST;
}

try {
    // Conectar con UTF-8 correctamente
    $pdo = Conexion::conectar();
    $pdo->exec("SET NAMES 'utf8mb4'");
    
    // Convertir arrays a JSON con codificación correcta

    $id_comision = $data['id_comision'] ?? null;

    if (!$id_comision) {
        throw new Exception("ID de comisión no proporcionado");
    }

    // Delete con PDO correctamente estructurado
    $stmt = $pdo->prepare("
        DELETE FROM comisiones_usuarios 
        WHERE id_comision = :id_comision
    "); 

    // Asociar valores a la consulta
    $stmt->bindParam(':id_comision', $id_comision, PDO::PARAM_STR);

    // Ejecutar consulta
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Se ha eliminado correctamente la comision."], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al eliminar la comision."], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error de conexión: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
