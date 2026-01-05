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

// var_dump($data);

try {
    // Conectar con UTF-8 correctamente
    $pdo = Conexion::conectar();
    $pdo->exec("SET NAMES 'utf8mb4'");

    // Convertir arrays a JSON con codificación correcta

    $id_comision = $data['id_comision'] ?? null;
    $valor_comision = $data['valor_comision'] ?? null;
    $observaciones = $data['observaciones'] ?? null;

    if (!$id_comision) {
        throw new Exception("ID de comisión no proporcionado");
    }

    var_dump($data);

    // Delete con PDO correctamente estructurado
    $stmt = $pdo->prepare("UPDATE comisiones_usuarios
    SET valor_comision = :valor_comision,
    observaciones = :observaciones
    WHERE id_comision = :id_comision");

    $stmt->bindParam(":id_comision", $id_comision, PDO::PARAM_INT);
    $stmt->bindParam(":valor_comision", $valor_comision, PDO::PARAM_STR);
    $stmt->bindParam(":observaciones", $observaciones, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Se ha eliminado correctamente la comisión."
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No se encontró la comisión para eliminar."
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error de conexión: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
