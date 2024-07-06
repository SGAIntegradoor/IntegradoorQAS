<?php
session_start();
require_once '../config/dbconfig.php';

$id = $_SESSION['idUsuario'];

// Mostrar errores (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Asegurarse de que las fechas están siendo recibidas correctamente desde el formulario
$fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : '';
$fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : '';

// Validar que las fechas no estén vacías
if (!empty($fechaInicio) && !empty($fechaFin)) {

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

} else {
    echo "Las fechas no están definidas.";
}