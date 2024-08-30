<?php


// require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos

// session_start();

// $identidad = $_SESSION['permisos']['usu_documento'];
// $cotHechas = $_POST['cotHechas'];

// $sqlConfirm = "SELECT * from usuarios where usu_documento = $identidad";

// $res = mysqli_query($con, $sqlConfirm);
// if ($res) {
//     $row = mysqli_fetch_assoc($res);
//     $cotizacionesTotalesAntes = $row['cotizacionesTotales'];
//     if ($cotizacionesTotalesAntes == null) {
//         echo json_encode(array("result" => 2, "reason" => "No aplica"));
//     } else {
//         $sql = "UPDATE usuarios SET cotizacionesTotales = GREATEST(cotizacionesTotales - 1, 0) WHERE usu_documento = $identidad;";
//         $res = mysqli_query($con, $sql);
//         if ($res) {
//             $resultAfterUpdate = mysqli_query($con, $sqlConfirm);
//             $rowAfterUpdate = mysqli_fetch_assoc($resultAfterUpdate);
//             $cotizacionesTotalesDespues = $rowAfterUpdate['cotizacionesTotales'];

//             if ($cotizacionesTotalesAntes != $cotizacionesTotalesDespues) {
//                 $_SESSION['permisos']['cotizacionesTotales'] = strval(intval($_SESSION['permisos']['cotizacionesTotales']) - 1);
//                 echo json_encode(array("result" => 1, "reason" => "Reduccion"));
//             } else {
//                 echo json_encode(array("result" => -1, "reason" => "Cotizaciones totales en 0"));
//             }
//         } else {
//             echo json_encode(array("result" => 0, "reason" => "Error en la consulta de UPDATE del usuario"));
//         }
//     }
// } else {
//     echo json_encode(array("result" => 0, "reason" => "Error en la consulta SELECT del usuario"));
// }

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

