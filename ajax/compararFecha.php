<?php
session_start();
require_once '../config/dbconfig.php';

$id = $_SESSION['idUsuario'];
$intermediario = $_SESSION['intermediario'];

// Mostrar errores (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Asegurarse de que las fechas están siendo recibidas correctamente desde el formulario
$fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : '';
$fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : '';

// Validar que las fechas no estén vacías
if (!empty($fechaInicio) && !empty($fechaFin)) {

    global $stmt;
    if ($intermediario != 3) {
        // Utilizar una consulta preparada para evitar inyección SQL
        if ($intermediario == 89) {
            $query = "SELECT * FROM `cotizaciones` WHERE `cot_fch_cotizacion` BETWEEN '$fechaInicio' AND '$fechaFin:23:59:59' AND `id_usuario` = $id";
        } else {
            $query = "SELECT * FROM cotizaciones c INNER JOIN usuarios us ON us.id_usuario = c.id_usuario WHERE cot_fch_cotizacion BETWEEN '$fechaInicio' AND '$fechaFin:23:59:59' AND us.id_Intermediario = $intermediario";
        }

        //$stmt->bind_param("ssi", $fechaInicio, $fechaFin, $id);
        $stmt = $enlace->prepare($query);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $enlace->error);
        }

        // Vincular los parámetros a la consulta
        // $stmt->bind_param("si", $fechaInicio, $fechaFin);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            die("Error en la ejecución de la consulta: " . $stmt->error);
        }

        // Vincular los resultados
        $stmt->store_result();
        $numeroDeFilas = $stmt->num_rows;

        echo $numeroDeFilas;

        // Cerrar la consulta preparada y la conexión
        $stmt->close();
        $enlace->close();
    } else {
        // Utilizar una consulta preparada para evitar inyección SQL
        $query = "SELECT * FROM `cotizaciones` WHERE `cot_fch_cotizacion` BETWEEN ? AND ? AND `id_usuario` = ?";
        $stmt = $enlace->prepare($query);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $enlace->error);
        }

        // Vincular los parámetros a la consulta
        $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $id);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            die("Error en la ejecución de la consulta: " . $stmt->error);
        }

        // Vincular los resultados
        $stmt->store_result();
        $numeroDeFilas = $stmt->num_rows;

        echo $numeroDeFilas;

        // Cerrar la consulta preparada y la conexión
        $stmt->close();
        $enlace->close();
    }
} else {
    echo "Las fechas no están definidas.";
}
