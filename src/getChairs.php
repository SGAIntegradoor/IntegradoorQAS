<?php
session_start();

require_once "../config/dbconfig.php";

if($_SESSION["rol"] == 23 || $_SESSION["rol"] == 22 || $_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1){
    $query = "SELECT * FROM cargos";
}

mysqli_set_charset($enlace, "utf8");
$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value =" . $fila['id_cargo'].">". $fila['descripcion']."</option>";
} 
echo $opcion;
    

?>