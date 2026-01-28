<?php

require_once "conexion.php";

class ModeloOportunidades
{

    /*=============================================
	MOSTRAR COTIZACIONES
	=============================================*/

    static public function mdlMostrarOportunidades($tabla, $fechaIni, $fechaFin)
    {
        global $stmt;

        if ($fechaFin != null && $fechaIni != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_creacion BETWEEN '$fechaFin' AND '$fechaIni'");
            $stmt->execute();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $stmt = null;
    }

    static public function mdlMostrarOportunidadesFilters($params)
    {
        global $stmt;

        // Validar los parámetros
        $valores = [];
        foreach ($params as $clave => $valor) {

            // Sanitizar valores para evitar SQL Injection
            // $valores[$clave] = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
            $valores[$clave] = urldecode($valor);
        }

        // Crear consulta dinámica
        $sql = "SELECT * FROM oportunidades WHERE 1=1"; // Query base
        foreach ($valores as $campo => $valor) {
            switch ($campo) {
                case 'mesExpedicion':
                    # code...
                    $sql .= " AND mes_expedicion = '$valor'";
                    break;
                case 'canal':
                    # code...
                    $sql .= " AND canal_oportunidad = '$valor'";
                    break;
                case 'aseguradoraOpo':
                    # code...
                    $sql .= " AND aseguradora = '$valor'";
                    break;
                case 'analistaGA':
                    # code...
                    $sql .= " AND analista_comercial = '$valor'";
                    break;
                case 'formaDePago':
                    # code...
                    $sql .= " AND forma_pago = '$valor'";
                    break;
                case 'anioOp':
                    # code...
                    $sql .= " AND YEAR(fecha_expedicion) = '$valor'";
                    break;
                case 'financiera':
                    # code...
                    $sql .= " AND financiera = '$valor'";
                    break;
                case 'nombreAsesor':
                    # code...
                    $sql .= " AND asesor_freelance = '$valor'";
                    break;
                case 'estado':
                    # code...
                    $sql .= " AND estado = '$valor'";
                    break;
                case 'ramo':
                    # code...
                    $sql .= " AND ramo = '$valor'";
                    break;
                case 'carpeta':
                    $carpeta = $valor == "Si" ? "Carpeta creada" : "Sin carpeta";
                    $sql .= " AND carpeta = '$carpeta'";
                    break;
                default:
                    # code...
                    break;
            }
        }
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();

        $numRows = $stmt->rowCount();

        if ($numRows > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    static public function mdlEliminarOportunidad($tabla, $id_oportunidad, $id_oferta)
    {

        // Check if the id_oportunidad and id_oferta exist in the database
        $stmtSearchOffert = Conexion::conectar()->prepare("SELECT * FROM ofertas WHERE id_oferta = :id_oferta");
        $stmtSearchOffert->bindParam(":id_oferta", $id_oferta, PDO::PARAM_INT);

        if ($stmtSearchOffert->execute()) {
            $response = $stmtSearchOffert->fetch(PDO::FETCH_ASSOC);
            $stmtSearchOffert->closeCursor();
            if (isset($response["id_oportunidad"]) && $response["id_oportunidad"] == $id_oportunidad && $response["id_oportunidad"] != null) {
                $stmtUpdateOffert = Conexion::conectar()->prepare("UPDATE ofertas SET id_oportunidad = NULL WHERE id_oferta = :id_oferta");
                $stmtUpdateOffert->bindParam(":id_oferta", $id_oferta, PDO::PARAM_INT);
                if ($stmtUpdateOffert->execute()) {
                    $stmtUpdateOffert->closeCursor();
                    $stmtDeleteOportunidad = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_oportunidad = :id_oportunidad");
                    $stmtDeleteOportunidad->bindParam(":id_oportunidad", $id_oportunidad, PDO::PARAM_INT);
                    if ($stmtDeleteOportunidad->execute()) {
                        $stmtDeleteOportunidad->closeCursor();
                        return array("status" => "success", "message" => "Oportunidad Borrada Correctamente", "statusCode" => 1);
                    } else {
                        return array("status" => "error", "message" => "Error al eliminar la oportunidad", "statusCode" => -2);
                    }
                } else {
                    return array("status" => "error", "message" => "Error al actualizar la oferta", "statusCode" => -1);
                }
            } else {
                $stmtDeleteOportunidad = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_oportunidad = :id_oportunidad");
                $stmtDeleteOportunidad->bindParam(":id_oportunidad", $id_oportunidad, PDO::PARAM_INT);
                if ($stmtDeleteOportunidad->execute()) {
                    return array("status" => "success", "message" => "Oportunidad Borrada Correctamente", "statusCode" => 1);
                } else {
                    return array("status" => "error", "message" => "Error al eliminar la oportunidad", "statusCode" => -2);
                }
            }
        } else {
            return array("status" => "error", "message" => "Error al buscar la oferta", "statusCode" => -3);
        }
    }
}
