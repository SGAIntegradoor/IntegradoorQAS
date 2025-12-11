<?php

/* Conectar a la base de datos */
require_once("../config/db.php"); // Contiene las variables de configuración para conectar a la base de datos
require_once("../config/conexion.php"); // Contiene función que conecta a la base de datos

$idPlan = $_POST['idPlan'];
$idCotizacion = $_POST['idCotizacion'];

$sql1 = "SELECT * FROM planes_cotizaciones_salud pcs WHERE pcs.id_cotizacion = $idCotizacion AND pcs.id_plan = $idPlan;";
$resultado = $con->query($sql1);
$row = $resultado->fetch_assoc();

if ($row['seleccionar'] == 0) {
    $sql3 = "UPDATE planes_cotizaciones_salud SET seleccionar = 1 WHERE id_cotizacion = $idCotizacion AND id_plan = $idPlan;";
    $con->query($sql3);
    $seleccion = 'Seleccionado';
} else {
    $sql4 = "UPDATE planes_cotizaciones_salud SET seleccionar = 0 WHERE id_cotizacion = $idCotizacion AND id_plan = $idPlan;";
    $con->query($sql4);
    $seleccion = 'No seleccionado';
}

$response = array('seleccionar' => $seleccion,
'idPlan' => $idPlan,
'idCotizacion' => $idCotizacion);


echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>
