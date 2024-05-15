<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

require_once '../config/dbconfig.php';

$enlace = mysqli_connect("$DB_host", "$DB_user", "$DB_pass", "$DB_name");

if(!$enlace ){

    die("Conexion Fallida ".mysqli_connect_error());

}

$query = "SELECT * FROM tipos_documentos";
$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value =" . $fila['id_tipo_documento'].">". $fila['tip_doc_abreviatura']."</option>";
} 
echo $opcion;
    

?>