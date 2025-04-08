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
    $direccion = $data['direccion'] ?? null;
    $ciudad = $data['ciudad'] ?? null;
    $codCiudad = $data['codLocalidad'] ?? null;
    $departamento = $data['departamento'] ?? null;
    $zona_riesgo = $data['zona_riesgo'] ?? null;
    $sub_zona = $data['sub_zona'] ?? null;
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
    $val_viv = $data['val_viv'] ?? null;
    $val_cn = $data['val_cn'] ?? null;
    $val_hur = $data['val_hur'] ?? null;
    $val_tr = $data['val_tr'] ?? null;
    $aseg_mascotas = $data['aseg_masc'] ?? null;
    $val_viv_sbs = $data['val_viv_sbs'] ?? null;
    $val_cnen_sbs = $data['val_cnen_sbs'] ?? null;
    $val_cnelec_sbs = $data['val_cnelec_sbs'] ?? null;
    $val_cnens_sbs = $data['val_cnens_sbs'] ?? null;
    $tot_cnn_sbs = $data['tot_cnn_sbs'] ?? null;
    $tot_cobertura_basica_sbs = $data['tot_cobertura_basica_sbs'] ?? null;
    $val_cnesp_sus_sbs = $data['val_cnesp_sus_sbs'] ?? null;
    $val_cnnor_sus_sbs = $data['val_cnnor_sus_sbs'] ?? null;
    $tot_cn_sus_sbs = $data['tot_cn_sus_sbs'] ?? null;
    $val_asegee_danos_sbs = $data['val_asegee_danos_sbs'] ?? null;
    $val_asegee_sus_sbs = $data['val_asegee_sus_sbs'] ?? null;
    $val_tr_sbs = $data['val_tr_sbs'] ?? null;


    $stmt = $pdo->prepare("INSERT INTO cotizaciones_hogar (id, fecha_cotizacion, direccion, codCiudad, ciudad, departamento, zona_riesgo, sub_zona, tipo_vivienda, no_piso, no_total_pisos, tipo_construccion, anio_construccion, area_total, zona_construccion, credito, tipo_asegurado, tipo_cobertura, val_viv, val_cn, val_hur, val_tr, aseg_mascota,val_viv_sbs, val_cnen_sbs, val_cnelec_sbs, val_cnens_sbs, tot_cnn_sbs, tot_cobertura_basica_sbs, val_cnesp_sus_sbs, val_cnnor_sus_sbs, tot_cn_sus_sbs, val_asegee_danos_sbs, val_asegee_sus_sbs, val_tr_sbs, id_cliente, id_usuario) 
                          VALUES (null, :fecha_cotizacion, :direccion, :codCiudad, :ciudad, :departamento , :zona_riesgo, :sub_zona, :tipo_vivienda, :no_piso, :no_total_pisos, :tipo_construccion, :anio_construccion, :area_total, :zona_construccion, :credito, :tipo_asegurado, :tipo_cobertura, :val_viv, :val_cn, :val_hur, :val_tr, :aseg_masc ,:val_viv_sbs, :val_cnen_sbs, :val_cnelec_sbs, :val_cnens_sbs, :tot_cnn_sbs, :tot_cobertura_basica_sbs, :val_cnesp_sus_sbs, :val_cnnor_sus_sbs, :tot_cn_sus_sbs, :val_asegee_danos_sbs, :val_asegee_sus_sbs, :val_tr_sbs, :id_cliente, :id_usuario)");

    $stmt->bindParam(':fecha_cotizacion', $fecha_cotizacion);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':ciudad', $ciudad);
    $stmt->bindParam(':codCiudad', $codCiudad);
    $stmt->bindParam(':departamento', $departamento);
    $stmt->bindParam(':zona_riesgo', $zona_riesgo);
    $stmt->bindParam(':sub_zona', $sub_zona);
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
    $stmt->bindParam(':val_viv', $val_viv);
    $stmt->bindParam(':val_cn', $val_cn);
    $stmt->bindParam(':val_hur', $val_hur);
    $stmt->bindParam(':val_tr', $val_tr);
    $stmt->bindParam(':aseg_masc', $aseg_mascotas);
    $stmt->bindParam(':val_viv_sbs', $val_viv_sbs);
    $stmt->bindParam(':val_cnen_sbs', $val_cnen_sbs);
    $stmt->bindParam(':val_cnelec_sbs', $val_cnelec_sbs);
    $stmt->bindParam(':val_cnens_sbs', $val_cnens_sbs);
    $stmt->bindParam(':tot_cnn_sbs', $tot_cnn_sbs);
    $stmt->bindParam(':tot_cobertura_basica_sbs', $tot_cobertura_basica_sbs);
    $stmt->bindParam(':val_cnesp_sus_sbs', $val_cnesp_sus_sbs);
    $stmt->bindParam(':val_cnnor_sus_sbs', $val_cnnor_sus_sbs);
    $stmt->bindParam(':tot_cn_sus_sbs', $tot_cn_sus_sbs);
    $stmt->bindParam(':val_asegee_danos_sbs', $val_asegee_danos_sbs);
    $stmt->bindParam(':val_asegee_sus_sbs', $val_asegee_sus_sbs);
    $stmt->bindParam(':val_tr_sbs', $val_tr_sbs);
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
