<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Conectar a la base de datos*/
require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos

$idCotizacionHogar = isset($_POST['idcotizacionHogar']) ? intval($_POST['idcotizacionHogar']) : 0;
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';

if ($idCotizacionHogar > 0 && in_array($estado, ['Cotizada', 'Pendiente'])) {
    
    $stmt = $con->prepare("UPDATE cotizaciones_hogar SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $idCotizacionHogar);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Estado actualizado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se actualizó ningún registro. Verifica el ID.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos.'
    ]);
}
