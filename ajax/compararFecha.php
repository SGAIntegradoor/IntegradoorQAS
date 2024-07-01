<?php
session_start();
require_once '../config/dbconfig.php';

$id = $_SESSION['idUsuario'];

// Asegurarse de que las fechas están siendo recibidas correctamente desde el formulario
$fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : '';
$fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : '';

// Validar que las fechas no estén vacías
if (!empty($fechaInicio) && !empty($fechaFin)) {

    // Utilizar una consulta preparada para evitar inyección SQL
    $query = "SELECT * FROM `cotizaciones` WHERE `cot_fch_cotizacion` BETWEEN ? AND ? AND `id_usuario` = ?";
    $debugQuery = "SELECT * FROM `cotizaciones` WHERE `cot_fch_cotizacion` BETWEEN '$fechaInicio' AND '$fechaFin' AND `id_usuario` = $id";
    $stmt = $enlace->prepare($query);

    // var_dump($debugQuery);


    // Vincular los parámetros a la consulta
    $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Contar el número de filas
    $numeroDeFilas = $result->num_rows;
    
    echo $numeroDeFilas;
    // Cerrar la consulta preparada y la conexión

} else {
    echo "Las fechas no están definidas.";
}