<?php
session_start();

require_once "../config/dbconfig.php";

if($_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1){
    $query = "SELECT * FROM analistas_comerciales WHERE activo = 'activo'";
}


$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value =" . $fila['id_analista'].">". $fila['nombre']."</option>";
} 
echo $opcion;
    

?>