<?php

    require_once "conexion.php";

    class ModeloAlertaAseguradora {
        static public function mdlObtenerAlertas($cotizacion) {
        $pdo = Conexion::conectar();

        // Asegurar que la conexión use utf8mb4
        $pdo->exec("SET NAMES utf8mb4");

        $stmt = $pdo->prepare("SELECT * FROM alertas_aseguradoras WHERE cotizacion = :cotizacion");
        $stmt->bindParam(":cotizacion", $cotizacion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   }
