<?php


require_once "../config/dbconfig.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "SELECT * FROM analistas_comerciales a INNER JOIN usuarios u ON a.id_usuario = u.id_usuario";

$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value = '$fila[id_usuario]' >" . $fila['usu_nombre']." ".$fila['usu_apellido']."</option>";
} 
echo $opcion;
    

?>