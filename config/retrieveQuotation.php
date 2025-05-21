<?php

require_once "modelos/conexion.php";

function retrieveQuotation($quotation) {
    try {

        /* Trae la cotización */ 

        $stmt = Conexion::conectar()->prepare("SELECT * FROM `cotizaciones` WHERE `id_cotizacion` = :valor");
        $stmt->bindParam(":valor", $quotation, PDO::PARAM_STR);
        $stmt->execute();

        /* Trae las ofertas */ 

        $stmt2 = Conexion::conectar()->prepare("SELECT * FROM `ofertas` WHERE `id_cotizacion` = :valor");
        $stmt2->bindParam(":valor", $quotation, PDO::PARAM_STR);
        $stmt2->execute();

        $cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);
        $cotizacion["ofertas"] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

       // var_dump($cotizacion);
        return $cotizacion;
    } catch (PDOException $e) {
        // Imprimir mensaje de error en caso de error en la consulta
        echo "Error en la consulta: " . $e->getMessage();
        return false; // Devolver false en caso de error
    }
}
?>