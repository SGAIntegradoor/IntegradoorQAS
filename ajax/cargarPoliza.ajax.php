<?php

require_once "./../modelos/conexion.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1 || $_SESSION["rol"] == 22 || $_SESSION["rol"] == 19) {

    $tabla = "polizas";
    $tabla2 = "anexos_polizas";
    $tabla3 = "pagos_polizas";

    $id_poliza = $_POST["id_poliza"];

    $enlace = Conexion::conectar();

    $idfreelance = $_SESSION["permisos"]["usu_documento"];

    $query = "SELECT *
        FROM $tabla p
        INNER JOIN $tabla2 a ON p.id_poliza = a.id_poliza AND a.no_certificado = 0
        INNER JOIN vehiculo_poliza vp ON p.id_poliza = vp.id_poliza
        AND a.id_freelance = :idfreelance AND p.id_poliza = $id_poliza
        ORDER BY p.id_poliza DESC";

    $stmt = $enlace->prepare($query);
    $stmt->bindParam(':idfreelance', $idfreelance);
    if ($stmt->execute()) {
        // Eliminar filas duplicadas basadas en 'id_poliza'
        $poliza = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($poliza);
        $id_poliza = $poliza['id_poliza'];
        $stmt2 = Conexion::conectar()->prepare("SELECT SUM(valor_recibido) AS total_pagado FROM $tabla3 WHERE id_poliza = :id_poliza");
        $stmt2->bindParam(":id_poliza", $id_poliza, PDO::PARAM_INT);
        if ($stmt2->execute()) {
            $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
            // var_dump($resultado);
            $poliza['pagos_realizados'] = $resultado['total_pagado'] ?? 0;
            $stmt2->closeCursor();
            $stmt2 = null;
        } else {
            return json_encode(array('error' => 'Error al ejecutar la consulta de pagos', 'details' => $stmt2->errorInfo()));
        }

        
        echo json_encode($poliza);
    } else {
        return json_encode(array('error' => 'Error al ejecutar la consulta', 'details' => $stmt->errorInfo()));
    }
} else {
    echo json_encode(array("error" => "No tienes permiso para acceder a esta información."));
}
