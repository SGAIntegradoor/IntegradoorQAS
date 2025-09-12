<?php

require_once "conexion.php";

class ModeloConsultas
{
    static public function mdlMostrarNegocios($tabla, $tabla2, $tabla3, $fechaInicialCreacion, $fechaFinalCreacion, $idfreelance)
    {
        $stmt = Conexion::conectar()->prepare("SELECT p.id_poliza, a.fecha_exp_poliza, p.no_poliza, a.ramo_poliza, p.aseguradora_poliza, a.nombre_completo_asegurado, vp.placa_veh_poliza, a.fecha_inicio_vig_poliza, a.fecha_fin_vig_poliza
            FROM $tabla p
            INNER JOIN $tabla2 a ON p.id_poliza = a.id_poliza AND a.no_certificado = 0
            INNER JOIN vehiculo_poliza vp ON p.id_poliza = vp.id_poliza
            WHERE p.fecha_registro BETWEEN :fechaInicialCreacion AND :fechaFinalCreacion
            AND a.id_freelance = :idfreelance
            ORDER BY p.id_poliza DESC");

        $stmt->bindParam(":fechaInicialCreacion", $fechaInicialCreacion, PDO::PARAM_STR);
        $stmt->bindParam(":fechaFinalCreacion", $fechaFinalCreacion, PDO::PARAM_STR);
        $stmt->bindParam(":idfreelance", $idfreelance, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Eliminar filas duplicadas basadas en 'id_poliza'
            $polizas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // foreach ($polizas as $poliza) {
            //     $id_poliza = $poliza['id_poliza'];
            //     $stmt2 = Conexion::conectar()->prepare("SELECT SUM(valor_recibido) AS total_pagado FROM $tabla3 WHERE id_poliza = :id_poliza");
            //     $stmt2->bindParam(":id_poliza", $id_poliza, PDO::PARAM_INT);
            //     if ($stmt2->execute()) {
            //         $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
            //         var_dump($resultado);
            //         $poliza['pagos_realizados'] = $resultado['total_pagado'] ?? 0;
            //         $stmt2->closeCursor();
            //         $stmt2 = null;
            //     } else {
            //         return json_encode(array('error' => 'Error al ejecutar la consulta de pagos', 'details' => $stmt2->errorInfo()));
            //     }
            // }
            // var_dump($polizas);
            $stmt = null;
            return $polizas;
        } else {
            return json_encode(array('error' => 'Error al ejecutar la consulta', 'details' => $stmt->errorInfo()));
        }
    }
}
