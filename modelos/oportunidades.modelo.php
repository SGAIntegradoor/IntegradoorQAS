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

        if($fechaFin != null && $fechaIni != null){
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
            $valores[$clave] = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
        }

        // Crear consulta dinámica
        $sql = "SELECT * FROM oportunidades WHERE 1=1"; // Query base
        foreach ($valores as $campo => $valor) {
            switch ($campo) {
                case 'mesExpedicion':
                    # code...
                    $sql .= " AND mes_oportunidad = '$valor'";
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
                case 'onerosoOp':
                    # code...
                    $sql .= " AND oneroso = '$valor'";
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
        
        if($numRows > 0){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $stmt->closeCursor();
        $stmt = null;
    }

}
