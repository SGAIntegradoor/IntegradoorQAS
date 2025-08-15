<?php

    require_once "../modelos/alerta_aseguradora.modelo.php";
    header('Content-Type: text/html; charset=utf-8');
    
;
   class AlertaAseguradora {
        
        public function obtenerAlertas($cotizacion) {
            $resultado = ModeloAlertaAseguradora::mdlObtenerAlertas($cotizacion);  
            if (!$resultado) { return false; }

            echo json_encode($resultado);
        }
        public function obtenerAlertasHogar($cotizacion) {
            $resultado = ModeloAlertaAseguradora::mdlObtenerAlertasHogar($cotizacion);  
            if (!$resultado) { return false; }

            echo json_encode($resultado);
        }

        public function obtenerAlertasSalud($cotizacion) {
            $resultado = ModeloAlertaAseguradora::mdlObtenerAlertasSalud($cotizacion);  
            if (!$resultado) { return false; }

            echo json_encode($resultado);
        }
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['obtenerAlertas'])) {
        $alertaAseguradora = new AlertaAseguradora();
        $alertaAseguradora->obtenerAlertas($data['cotizacion']);
    }

    if (isset($data['alertasHogar'])) {
        $alertaAseguradora = new AlertaAseguradora();
        $alertaAseguradora->obtenerAlertasHogar($data['cotizacion']);
    }

    if (isset($data['alertaSalud'])) {
        $alertaAseguradora = new AlertaAseguradora();
        $alertaAseguradora->obtenerAlertasSalud($data['cotizacion']);
    }

