<?php
session_start();

require_once "../config/dbconfig.php";

if($_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1 || $_SESSION["rol"] == 22){
    $query = "SELECT * FROM intermediario";
}else{
    $query = "SELECT * FROM intermediario WHERE id_intermediario =".$_SESSION["intermediario"] ;
}

$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value =" . $fila['id_Intermediario'].">". $fila['nombre']."</option>";
} 
echo $opcion;
    

?>