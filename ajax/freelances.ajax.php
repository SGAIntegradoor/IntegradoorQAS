<?php


require_once "../config/dbconfig.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
$query = "SELECT * FROM oportunidades o INNER JOIN usuarios u ON o.id_user_freelance = u.id_usuario GROUP BY o.id_user_freelance";


$ejecucion = mysqli_query($enlace,$query);

$opcion = "";


while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value = '$fila[id_usuario]' >" . $fila['usu_nombre']." ".$fila['usu_apellido']."</option>";
} 

echo $opcion;
    

?>