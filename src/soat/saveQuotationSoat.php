<?php
session_start();

require_once("../../modelos/conexion.php");

header('Content-Type: application/json');

$data = $_POST;
if ($data['Accion'] == 'Actualizar-valores-soat') {
    try {
        $pdo = Conexion::conectar();

        $stmt = $pdo->prepare("
        UPDATE cotizaciones_soat SET opcion = :opcion, estado = :estado,
            valor_comision = :valor_comision,
            fecha_vencimiento = :fecha_vencimiento, clase_soat = :clase_soat, valor_prima = :prima, valor_contribucion = :contribucion, valor_runt =:runt, total_soat=:total,
            total_pagar = :total_pagar
        WHERE `id_cotizacion`=:id_cotizacion;
    ");

        $stmt->bindParam(":opcion", $data['Opcion'], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $data['Estado'], PDO::PARAM_STR);
        $stmt->bindParam(":valor_comision", $data['Comision'], PDO::PARAM_STR);
        $stmt->bindParam(":total_pagar", $data['TotalSoat'], PDO::PARAM_STR);
        $stmt->bindParam(":id_cotizacion", $data['IdCotizacionSoat'], PDO::PARAM_STR);

        $stmt->bindParam(":fecha_vencimiento", $data['FechaVencimiento'], PDO::PARAM_STR);
        $stmt->bindParam(":clase_soat", $data['Clase'], PDO::PARAM_STR);

        $stmt->bindParam(":prima", $data['Prima'], PDO::PARAM_STR);
        $stmt->bindParam(":contribucion", $data['Contribucion'], PDO::PARAM_STR);
        $stmt->bindParam(":runt", $data['Runt'], PDO::PARAM_STR);
        $stmt->bindParam(":total", $data['totalSoat'], PDO::PARAM_STR);

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
} else if ($data['Accion'] == 'Actualizar-datos-soat') {
    try {
        $pdo = Conexion::conectar();

        $stmt = $pdo->prepare("
        UPDATE cotizaciones_soat SET correo = :correo,
            celular = :celular, estado = :estado
        WHERE `id_cotizacion`=:id_cotizacion;
    ");

        $stmt->bindParam(":correo", $data['Correo'], PDO::PARAM_STR);
        $stmt->bindParam(":celular", $data['Celular'], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $data['Estado'], PDO::PARAM_STR);
        $stmt->bindParam(":id_cotizacion", $data['IdCotizacionSoat'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Datos tomador SOAT actualizados correctamente",
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

// Insertar nueva cotización SOAT
try {
    $pdo = Conexion::conectar();

    $stmt = $pdo->prepare("
        INSERT INTO cotizaciones_soat (
            placa, clase, marca, modelo, linea, servicio, cilindraje, pasajeros, motor, chasis,  referencia, 
             creado_por, fecha_creacion
        ) VALUES (
            :placa, :clase, :marca, :modelo, :linea, :servicio, :cilindraje, :pasajeros, :motor, :chasis,  :referencia, 
            :creado_por, NOW()
        )
    ");

    $stmt->bindParam(":placa", $data['Placa'], PDO::PARAM_STR);
    $stmt->bindParam(":clase", $data['Clase'], PDO::PARAM_STR);
    $stmt->bindParam(":marca", $data['Marca'], PDO::PARAM_STR);
    $stmt->bindParam(":modelo", $data['Modelo'], PDO::PARAM_STR);
    $stmt->bindParam(":linea", $data['Linea'], PDO::PARAM_STR);
    $stmt->bindParam(":servicio", $data['Servicio'], PDO::PARAM_STR);
    $stmt->bindParam(":cilindraje", $data['Cilindraje'], PDO::PARAM_STR);
    $stmt->bindParam(":pasajeros", $data['Pasajeros'], PDO::PARAM_STR);
    $stmt->bindParam(":motor", $data['Motor'], PDO::PARAM_STR);
    $stmt->bindParam(":chasis", $data['Chasis'], PDO::PARAM_STR);
    $stmt->bindParam(":referencia", $data['Referencia'], PDO::PARAM_STR);

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
