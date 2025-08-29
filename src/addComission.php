<?php 

session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    $ramo = isset($data['ramo']) ? json_encode($data['ramo'], JSON_UNESCAPED_UNICODE) : "[]";
    $unidadNegocio = isset($data['unidadNegocio']) ? json_encode($data['unidadNegocio'], JSON_UNESCAPED_UNICODE) : "[]";
    $tipoNegocio = isset($data['tipoNegocio']) ? json_encode($data['tipoNegocio'], JSON_UNESCAPED_UNICODE) : "[]";
    $tipoExpedicion = isset($data['tipoExpedicion']) ? json_encode($data['tipoExpedicion'], JSON_UNESCAPED_UNICODE) : "[]";
    
    $valorComision = $data['valorComision'];
    $id_usuario = $data['id_usuario'] ?? null;
    $id_super_usuario = $data['id_super_usuario'] ?? null;
    $observaciones = $data['observaciones'] ?? "";

    // Insert con PDO correctamente estructurado
    $stmt = $pdo->prepare("INSERT INTO comisiones_usuarios 
        (id_comision, ramo, unidad_negocio, tipo_negocio, tipo_expedicion, valor_comision, id_usuario, id_super_usuario, observaciones)
        VALUES 
        (NULL, :ramo, :unidad_negocio, :tipo_negocio, :tipo_expedicion, :valor_comision, :id_usuario, :id_super_usuario, :observaciones)
    "); 

    // Asociar valores a la consulta
    $stmt->bindParam(':ramo', $ramo, PDO::PARAM_STR);
    $stmt->bindParam(':unidad_negocio', $unidadNegocio, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_negocio', $tipoNegocio, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_expedicion', $tipoExpedicion, PDO::PARAM_STR);
    $stmt->bindParam(':valor_comision', $valorComision, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_super_usuario', $id_super_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);

    // Ejecutar consulta
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Guardado correctamente"], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->errorInfo()], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error de conexión: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
} 
