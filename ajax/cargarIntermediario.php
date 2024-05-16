<?php
session_start();

require_once "../config/dbconfig.php";

if($_SESSION["rol"] == 18 ||$_SESSION["rol"] == 10 ||$_SESSION["rol"] == 1){
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