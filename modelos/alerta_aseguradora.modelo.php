<?php

    require_once "conexion.php";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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

    static public function mdlObtenerAlertasHogar($cotizacion) {
        $pdo = Conexion::conectar();
        // Asegurar que la conexión use utf8mb4

        $stmt = $pdo->prepare("SELECT * FROM alertas_hogar WHERE cotizacion = :cotizacion");
        $stmt->bindParam(":cotizacion", $cotizacion);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   }
