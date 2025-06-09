<?php

require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos
session_start();

$identidad = $_SESSION['permisos']['usu_documento'];
$cotHechas = $_POST['cotHechas'];

$sqlConfirm = "SELECT * from usuarios where usu_documento = $identidad";

$res = mysqli_query($con, $sqlConfirm);
if ($res) {
    $row = mysqli_fetch_assoc($res);
    $cotTotales = $row['cotizacionesTotales'];
    if ($cotTotales == null) {
        echo json_encode(array("result" => 2, "reason" => "No aplica"));
    } else {
            if ($cotTotales > $cotHechas) {
                echo json_encode(array("result" => 1, "reason" => "Aprobado"));
            } else {
                echo json_encode(array("result" => -1, "reason" => "Sin cotizaciones mensuales"));
            }
        }
    }
 else {
    echo json_encode(array("result" => 0, "reason" => "Error en la consulta SELECT del usuario"));
}

