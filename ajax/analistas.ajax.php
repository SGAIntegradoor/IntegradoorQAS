<?php


require_once "../config/dbconfig.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "SELECT * FROM analistas_comerciales a INNER JOIN usuarios u ON a.id_usuario = u.id_usuario";
$respon = [];
$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
$arrayp = array();
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value = '$fila[usu_documento]' >" . $fila['usu_nombre']." ".$fila['usu_apellido']."</option>";
    array_push($arrayp, $fila);
} 

$respon['options'] = $opcion;
$respon['analistas'] = $arrayp;


return  json_encode($respon);
    

?>