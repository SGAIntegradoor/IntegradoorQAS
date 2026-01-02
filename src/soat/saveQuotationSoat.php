<?php
session_start();

require_once("../../modelos/conexion.php");

header('Content-Type: application/json');

$data = $_POST;
if ($data['Accion'] == 'Actualizar') {
    try {
        $pdo = Conexion::conectar();

        $stmt = $pdo->prepare("
        UPDATE cotizaciones_soat SET opcion = :opcion,
            valor_comision = :valor_comision,
            total_pagar = :total_pagar
        WHERE `id_cotizacion`=:id_cotizacion;
    ");

        $stmt->bindParam(":opcion", $data['Opcion'], PDO::PARAM_STR);
        $stmt->bindParam(":valor_comision", $data['Comision'], PDO::PARAM_STR);
        $stmt->bindParam(":total_pagar", $data['TotalSoat'], PDO::PARAM_STR);
        $stmt->bindParam(":id_cotizacion", $data['IdCotizacionSoat'], PDO::PARAM_STR);

        $usuario = $data['IdUsuario'];

        // $stmt->bindParam(":actualizado_por", $usuario, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Cotización SOAT actualizada correctamente",
                "lastId" => $data['IdCotizacionSoat']
            ]);
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode([
                "success" => false,
                "message" => "Error en la base de datos",
                "error" => $errorInfo[2]
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error de conexión",
            "error" => $e->getMessage()
        ]);
    }
    return;
}


try {
    $pdo = Conexion::conectar();

    $stmt = $pdo->prepare("
        INSERT INTO cotizaciones_soat (
            placa, clase, referencia, 
            valor_prima, valor_contribucion, valor_runt, total_soat, creado_por, fecha_creacion
        ) VALUES (
            :placa, :clase, :referencia, 
            :prima, :contribucion, :runt, :total, :creado_por, NOW()
        )
    ");

    $stmt->bindParam(":placa", $data['Placa'], PDO::PARAM_STR);
    $stmt->bindParam(":clase", $data['Clase'], PDO::PARAM_STR);
    $stmt->bindParam(":referencia", $data['Referencia'], PDO::PARAM_STR);

    $stmt->bindParam(":prima", $data['Prima'], PDO::PARAM_STR);
    $stmt->bindParam(":contribucion", $data['Contribucion'], PDO::PARAM_STR);
    $stmt->bindParam(":runt", $data['Runt'], PDO::PARAM_STR);
    $stmt->bindParam(":total", $data['totalSoat'], PDO::PARAM_STR);

    $usuario = $data['IdUsuario'];

    $stmt->bindParam(":creado_por", $usuario, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        echo json_encode([
            "success" => true,
            "message" => "Cotización SOAT guardada correctamente",
            "lastId" => $lastId
        ]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode([
            "success" => false,
            "message" => "Error en la base de datos",
            "error" => $errorInfo[2]
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión",
        "error" => $e->getMessage()
    ]);
}
