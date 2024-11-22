<?php


require_once "../config/dbconfig.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$params = isset($_POST["param"]) && $_POST["param"] != "" ? $_POST["param"]: "";

if($params == ""){
    $query = "SELECT * FROM oportunidades o INNER JOIN usuarios u ON o.id_user_freelance = u.id_usuario GROUP BY o.id_user_freelance";
} else {
    $query = "SELECT * FROM usuarios u WHERE id_rol = 19 AND id_Intermediario = 3";
}

$ejecucion = mysqli_query($enlace,$query);

$opcion = "";

while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value = '$fila[id_usuario]' >" . $fila['usu_nombre']." ".$fila['usu_apellido']."</option>";
} 

echo $opcion;
    

?>