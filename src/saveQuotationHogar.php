<?php

session_start();

/* Conectar a la base de datos */
require_once("../modelos/conexion.php"); // Contiene función que conecta a la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = Conexion::conectar();

    $data = $_POST ?? null;

    $fecha_cotizacion = date("Y-m-d H:i:s");
    $direccion = $data['direccion'].', '.$data["resto"] ?? null;
    $ciudad = $data['codLocalidad'] ?? null;
    $zona_riesgo = $data['zona_riesgo'] ?? null;
    $tipo_vivienda = $data['tipoDeVivienda'] ?? null;
    $no_piso = $data['pisoUbicacionApto'] ?? null;
    $no_total_pisos = $data['numeroTotalDePisos'] ?? null;
    $tipo_construccion = $data['tipoDeConstruccion'] ?? null;
    $anio_construccion = $data['anoConstruccion'] ?? null;
    $area_total = $data['areaTotal'] ?? null;
    $zona_construccion = $data['zonaConstruccion'] ?? null;
    $credito = $data['tieneCredito'] ?? null;
    $tipo_asegurado = $data['categoriaDeRiesgo'] ?? null;
    $tipo_cobertura = $data['tipoCobertura'] ?? null;
    $id_cliente = $data['idCliente'] ?? null;
    $id_usuario = $data['idUsuario'] ?? null;
    
    $stmt = $pdo->prepare("INSERT INTO cotizaciones_hogar (id, fecha_cotizacion, direccion, ciudad, zona_riesgo, tipo_vivienda, no_piso, no_total_pisos, tipo_construccion, anio_construccion, area_total, zona_construccion, credito, tipo_asegurado, tipo_cobertura, id_cliente, id_usuario) 
                          VALUES (null, :fecha_cotizacion, :direccion, :ciudad, :zona_riesgo, :tipo_vivienda, :no_piso, :no_total_pisos, :tipo_construccion, :anio_construccion, :area_total, :zona_construccion, :credito, :tipo_asegurado, :tipo_cobertura, :id_cliente, :id_usuario)");

    $stmt->bindParam(':fecha_cotizacion', $fecha_cotizacion);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':ciudad', $ciudad);
    $stmt->bindParam(':zona_riesgo', $zona_riesgo);
    $stmt->bindParam(':tipo_vivienda', $tipo_vivienda);
    $stmt->bindParam(':no_piso', $no_piso);
    $stmt->bindParam(':no_total_pisos', $no_total_pisos);
    $stmt->bindParam(':tipo_construccion', $tipo_construccion);
    $stmt->bindParam(':anio_construccion', $anio_construccion);
    $stmt->bindParam(':area_total', $area_total);
    $stmt->bindParam(':zona_construccion', $zona_construccion);
    $stmt->bindParam(':credito', $credito);
    $stmt->bindParam(':tipo_asegurado', $tipo_asegurado);
    $stmt->bindParam(':tipo_cobertura', $tipo_cobertura);
    $stmt->bindParam(':id_cliente', $id_cliente);
    $stmt->bindParam(':id_usuario', $id_usuario);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId(); // Obtener el último ID insertado
        echo json_encode(["success" => true, "message" => "Guardado correctamente", "last_id" => $lastId]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(["success" => false, "message" => "Error al guardar", "error" => $errorInfo[2]]);
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
