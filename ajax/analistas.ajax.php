<?php

require_once "../config/dbconfig.php";

// Mostrar errores para depuración (desactivar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración del encabezado de la respuesta
header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => 'Ocurrió un error inesperado',
    'options' => '',
    'analistas' => []
];

try {
    // Validar la conexión
    if (!$enlace) {
        throw new Exception('Error de conexión a la base de datos: ' . mysqli_connect_error());
    }

    // Consulta SQL
    $query = "
        SELECT 
            *
        FROM analistas_comerciales a
        INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
    ";

    $ejecucion = mysqli_query($enlace, $query);

    // Verificar si la consulta fue exitosa
    if (!$ejecucion) {
        throw new Exception('Error al ejecutar la consulta: ' . mysqli_error($enlace));
    }

    // Procesar resultados
    $options = '';
    $analistas = [];

    while ($fila = $ejecucion->fetch_assoc()) {
        $options .= "<option value='{$fila['usu_documento']}'>{$fila['usu_nombre']} {$fila['usu_apellido']}</option>";
        $analistas[] = $fila;
    }

    // Preparar respuesta exitosa
    $response['status'] = 'success';
    $response['message'] = 'Datos obtenidos correctamente';
    $response['options'] = $options;
    $response['analistas'] = $analistas;
} catch (Exception $e) {
    // Manejo de excepciones
    $response['message'] = $e->getMessage();
    http_response_code(500); // Código de error del servidor
}

// Responder en formato JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
