<?php

session_start();
require_once __DIR__ . '/../modelos/conexion.php';
// Mostrar errores (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id_oportunidad_update = $_POST["id"];
$idCotizacion = $_POST["idCotizacion"];
$idCotAseguradora = $_POST["idCotAseguradora"] == "" ? "" : $_POST["idCotAseguradora"];
$valor_cotizacion = $_POST["valor_cotizacion"];
$mesOportunidad = $_POST["mesOportunidad"];
$canalOportunidad = $_POST['canalOportunidad'];
$razonPerdidaOportunidad = $_POST['razonPerdidaOportunidad'];
$otraRazon = $_POST['otraRazon'];
$asesor_freelance = $canalOportunidad == 'Directo' ? '' : $_POST["asesor_freelance"];
$id_user_freelance = $_POST["id_user_freelance"];
$ramo = $_POST["ramo"];
$placa = $_POST["placa"];
$oneroso = $_POST["oneroso"];
$aseguradora = $_POST["aseguradora"];
$analista_comercial = $_POST["analista_comercial"];
$estado = $_POST["estado"];
$noPoliza = $_POST["noPoliza"] === "undefined" ? null : $_POST["noPoliza"];
$asegurado = $_POST["asegurado"];
$prima_sin_iva = str_replace(',', '', $_POST["prima_sin_iva"]);
$gastos = str_replace(',', '', $_POST["gastos"]);
$asistencias = str_replace(',', '', $_POST["asistencias"]);
$iva = str_replace(',', '', $_POST["iva"]);
$valorTotal = str_replace(['$', '.', ','], '', trim($_POST["valorTotal"]));
$fechaExpedicion = $_POST["fechaExpedicion"] == "null" ? null : $_POST["fechaExpedicion"];
$mesExpedicion = $_POST["mesExpedicion"];
$formaDePago = trim($_POST["formaDePago"]);
$financiera = trim($_POST["financiera"]);
$carpeta = $_POST["carpeta"];
$observaciones = $_POST["observaciones"];
// $fechaActualizacion = $_POST["fechaActualizacion"] == "null" ? null : $_POST["fechaActualizacion"];
$fechaActualizacion = date("Y-m-d");

// Validar ID
if (empty($id_oportunidad_update)) {
    echo json_encode(array("statusCode" => 0, "message" => "Error al cargar la oportunidad, ID inválido"));
    exit;
}

try {
    $conexion = Conexion::conectar();
    $stmt = $conexion->prepare("
        UPDATE oportunidades 
        SET 
            id_cotizacion = :idCotizacion, 
            valor_cotizacion = :valorCotizacion, 
            mes_oportunidad = :mesOportunidad, 
            canal_oportunidad = :canalOportunidad, 
            razon_negocio_perdido = :razonPerdidaOportunidad, 
            otra_razon_negocio_perdido = :otraRazon, 
            asesor_freelance = :asesorFreelance, 
            id_user_freelance = :id_user_freelance,
            ramo = :ramo, 
            placa = :placa, 
            oneroso = :oneroso, 
            aseguradora = :aseguradora, 
            analista_comercial = :analistaComercial, 
            estado = :estado, 
            no_poliza = :noPoliza, 
            asegurado = :asegurado, 
            prima_sin_iva = :primaSinIva, 
            asist_otros = :asistencias, 
            gastos = :gastos, 
            iva = :iva, 
            valor_total = :valorTotal, 
            fecha_expedicion = :fechaExpedicion, 
            mes_expedicion = :mesExpedicion, 
            forma_pago = :formaDePago, 
            financiera = :financiera, 
            carpeta = :carpeta, 
            observaciones = :observaciones,
            id_cot_aseguradora = :idCotAseguradora,
            fecha_actualizacion = :fechaActualizacion
        WHERE id_oportunidad = :idOportunidad
    ");

    // Bind de parámetros
    $stmt->bindParam(':idCotizacion', $idCotizacion, PDO::PARAM_INT);
    $stmt->bindParam(':idCotAseguradora', $idCotAseguradora, PDO::PARAM_STR);
    $stmt->bindParam(':valorCotizacion', $valor_cotizacion, PDO::PARAM_INT);
    $stmt->bindParam(':mesOportunidad', $mesOportunidad, PDO::PARAM_STR);
    $stmt->bindParam(':canalOportunidad', $canalOportunidad, PDO::PARAM_STR);
    $stmt->bindParam(':razonPerdidaOportunidad', $razonPerdidaOportunidad, PDO::PARAM_STR);
    $stmt->bindParam(':otraRazon', $otraRazon, PDO::PARAM_STR);
    $stmt->bindParam(':asesorFreelance', $asesor_freelance, PDO::PARAM_STR);
    $stmt->bindParam(':id_user_freelance', $id_user_freelance, PDO::PARAM_INT);
    $stmt->bindParam(':ramo', $ramo, PDO::PARAM_STR);
    $stmt->bindParam(':placa', $placa, PDO::PARAM_STR);
    $stmt->bindParam(':oneroso', $oneroso, PDO::PARAM_STR);
    $stmt->bindParam(':aseguradora', $aseguradora, PDO::PARAM_STR);
    $stmt->bindParam(':analistaComercial', $analista_comercial, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':noPoliza', $noPoliza, PDO::PARAM_STR);
    $stmt->bindParam(':asegurado', $asegurado, PDO::PARAM_STR);
    $stmt->bindParam(':primaSinIva', $prima_sin_iva, PDO::PARAM_INT);
    $stmt->bindParam(':asistencias', $asistencias, PDO::PARAM_INT);
    $stmt->bindParam(':gastos', $gastos, PDO::PARAM_INT);
    $stmt->bindParam(':iva', $iva, PDO::PARAM_INT);
    $stmt->bindParam(':valorTotal', $valorTotal, PDO::PARAM_INT);
    $stmt->bindParam(':fechaExpedicion', $fechaExpedicion, PDO::PARAM_STR);
    $stmt->bindParam(':mesExpedicion', $mesExpedicion, PDO::PARAM_STR);
    $stmt->bindParam(':formaDePago', $formaDePago, PDO::PARAM_STR);
    $stmt->bindParam(':financiera', $financiera, PDO::PARAM_STR);
    $stmt->bindParam(':carpeta', $carpeta, PDO::PARAM_STR);
    $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
    $stmt->bindParam(':idOportunidad', $id_oportunidad_update, PDO::PARAM_INT);
    $stmt->bindParam(':fechaActualizacion', $fechaActualizacion, PDO::PARAM_STR);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $enlace->error);
    }
    if (!$stmt->execute()) {
        $errorInfo = $stmt->errorInfo();
        die("Error en la ejecución de la consulta: " . $errorInfo[2]);
    }
    

    if ($stmt->execute()) {
        echo json_encode(array("code" => 1, "message" => "Oportunidad actualizada correctamente"));
    } else {
        echo json_encode(array("code" => 0, "message" => "Error al actualizar la oportunidad"));
    }
} catch (PDOException $e) {
    echo json_encode(array("code" => 0, "message" => "Error de base de datos: " . $e->getMessage()));
}
?>
