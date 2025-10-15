<?php

require_once "../config/QAStoPRD.php";
header('Content-Type: text/html; charset=utf-8');
mysqli_set_charset($enlace, "utf8mb4");

// Respuesta inicial
$response = [
    'status' => 'error',
    'message' => 'Error inesperado',
    'options' => '',
    'asesores' => []
];

try {
    if (!$enlace) {
        throw new Exception('Error de conexión: ' . mysqli_connect_error());
    }

    // Consulta fija: obtener asesores activos con roles específicos
    $query = "
    SELECT * 
        FROM usuarios 
        WHERE id_rol IN (19, 12, 11, 10, 1) and id_usuario not in (197,416,570,636,1144,1159,1186,1283,1345,1377,1428,1586,1652)
        ORDER BY usu_nombre ASC
    ";

    $result = mysqli_query($enlace, $query);

    if (!$result) {
        throw new Exception('Error en la consulta: ' . mysqli_error($enlace));
    }

    $options = "<option value=''>Seleccione una opción</option>";
    $asesores = [];
    
    while ($row = $result->fetch_assoc()) {
        $nombre = htmlspecialchars($row['usu_nombre'], ENT_QUOTES, 'UTF-8');
        $apellido = htmlspecialchars($row['usu_apellido'], ENT_QUOTES, 'UTF-8');
        $id_usuario = htmlspecialchars($row['id_usuario'], ENT_QUOTES, 'UTF-8');
    
        $options .= "<option value='{$id_usuario}'>{$nombre} {$apellido}</option>";
        $asesores[] = $row;
    }

    $response['status'] = 'success';
    $response['message'] = 'Asesores obtenidos correctamente';
    $response['options'] = $options;
    $response['asesores'] = $asesores;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(500);
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
