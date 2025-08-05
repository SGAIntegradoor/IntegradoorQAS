<?php

require_once "../config/dbconfig.php";
header('Content-Type: text/html; charset=utf-8');
mysqli_set_charset($enlace, "utf8mb4");

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
       SELECT * FROM cotizaciones c
       GROUP BY c.cot_clase;
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
        $options .= "<option value='{$fila['cot_clase']}'>{$fila['cot_clase']}</option>";
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
