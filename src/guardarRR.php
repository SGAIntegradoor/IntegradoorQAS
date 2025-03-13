<?php 

session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = Conexion::conectar();
    
    $request = $_POST['request'] ?? null;
    $response = $_POST['response'] ?? null;
    $aseguradora = $_POST['aseguradora'] ?? null;
    $cotizacion = $_POST['cotizacion'] ?? null;
    $fecha = date("Y-m-d H:i:s");

    // Convertir a JSON asegurando que no sea NULL
    $request_json = json_encode($request, JSON_UNESCAPED_UNICODE);
    $response_json = json_encode($response, JSON_UNESCAPED_UNICODE);

    if ($request_json === false) {
        throw new Exception("Error al codificar request: " . json_last_error_msg());
    }
    if ($response_json === false) {
        throw new Exception("Error al codificar response: " . json_last_error_msg());
    }

    $stmt = $pdo->prepare("INSERT INTO peticiones_hogar (id, peticion, respuesta, cotizacion, aseguradora, fecha_peticion) 
                          VALUES (null, :request, :response, :cotizacion, :aseguradora, :fecha)");

    $stmt->bindParam(':request', $request_json);
    $stmt->bindParam(':response', $response_json);
    $stmt->bindParam(':aseguradora', $aseguradora);
    $stmt->bindParam(':cotizacion', $cotizacion);
    $stmt->bindParam(':fecha', $fecha);

    if ($stmt->execute()) {
        echo "Guardado correctamente";
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Error al guardar: " . $errorInfo[2];
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
