<?php

/* Conectar a la base de datos */
require_once("../config/db.php"); // Contiene las variables de configuración para conectar a la base de datos
require_once("../config/conexion.php"); // Contiene función que conecta a la base de datos

// Obtener los datos enviados en el cuerpo de la solicitud
$postBody = file_get_contents('php://input');
$arrayDatos = json_decode($postBody, true);

$producto = $arrayDatos['producto'];
$precio = $arrayDatos['precio'];
$modalidad = $arrayDatos['modalidad'];
$last_id = $arrayDatos['last_id'];

$data = []; // Inicializar el array para la respuesta

// Preparar la consulta de inserción
$sql = "INSERT INTO `ofertas_assistcard` (`id_oferta`, `producto`, `tipo_modalidad`, `precio`, `id_cotizacion`, `fecha_cotizado`) 
        VALUES (NULL, ?, ?, ?, ?, current_timestamp())";

$stmt = mysqli_prepare($con, $sql);

if ($stmt) {
    // Enlazar los parámetros a la consulta
    mysqli_stmt_bind_param($stmt, "ssdi", $producto, $modalidad, $precio, $last_id);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        $data['Message 2'] = 'Cotización creada exitosamente';
        $data['id_cotizacion'] = $last_id;
    } else {
        $data['Message 2'] = 'Error al crear la cotización: ' . mysqli_error($con);
    }

    // Cerrar la declaración preparada
    mysqli_stmt_close($stmt);
} else {
    $data['Message 2'] = 'Error en la preparación de la consulta: ' . mysqli_error($con);
}

// Devolver la respuesta en formato JSON
echo json_encode($data, JSON_UNESCAPED_UNICODE);

// Cerrar la conexión
mysqli_close($con);
?>
