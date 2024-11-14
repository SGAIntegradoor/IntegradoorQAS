<?php
session_start();
require_once '../config/dbconfig.php';

// Mostrar errores (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$noCotizacion = $_POST['idCotizacion']; //INT

$idOferta = $_POST['idOferta']; //INT

$valor_cotizacion = $_POST['valor_cotizacion']; //INT
$mesOportunidad = $_POST['mesOportunidad']; //VARCHAR
$asesor_freelance = $_POST['asesor_freelance'];
$ramo = $_POST['ramo']; //VARCHAR
$placa = $_POST['placa']; //VARCHAR
$oneroso = $_POST['oneroso']; //VARCHAR
$aseguradora = $_POST['aseguradora']; //VARCHAR
$analista_comercial = $_POST['analista_comercial']; //VARCHAR
$estado = $_POST['estado']; //VARCHAR
$asegurado = $_POST['asegurado']; //VARCHAR
$observaciones = $_POST['observaciones']; //LONGTEXT

$query = "INSERT INTO oportunidades (id_oportunidad, id_cotizacion, valor_cotizacion, mes_oportunidad, asesor_freelance, ramo, placa, oneroso, aseguradora, analista_comercial, estado, no_poliza, asegurado, prima_sin_iva, asist_otros, gastos, iva, valor_total, fecha_expedicion, mes_expedicion, forma_pago, financiera, carpeta, observaciones, id_oferta) VALUES (null, $noCotizacion, $valor_cotizacion, '$mesOportunidad', '$asesor_freelance', '$ramo', '$placa', '$oneroso', '$aseguradora', '$analista_comercial', '$estado', null, '$asegurado', null, null, null, null, null, null, null, null, null, null, '$observaciones', $idOferta)";

$stmt = $enlace->prepare($query);

if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $enlace->error);
}

if (!$stmt->execute()) {
    die("Error en la ejecución de la consulta: " . $stmt->error);
}

$rows = $stmt->affected_rows;

if ($rows > 0) {
    
    $query2 = "UPDATE ofertas SET id_oportunidad = $stmt->insert_id WHERE id_oferta = $idOferta";
    $stmt2 = $enlace->prepare($query2);

    if (!$stmt2->execute()) {
        die("Error en la ejecución de la consulta: " . $stmt2->error);
    }
    $rows2 = $stmt2->affected_rows;
    if($rows2 > 0){
        $data = array("status" => "success", "code" => 1 ,"message" => "Oportunidad Insertada Correctamente", "inserted_id" => $stmt->insert_id, "offert_updated" => $idOferta );
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        $data = array("status" => "failed", "code" => 0 ,"message" => "Oportunidad insertada pero con errores al modificar la oferta");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
} else {
    $data = array("status" => "failed", "code" => 0 ,"message" => "Oportunidad no insertada");
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
