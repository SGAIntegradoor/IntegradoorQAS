<?php


require_once "../config/dbconfig.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['saludo'])){
    $mensaje = $_POST['saludo'];
}

if(isset($_POST['idRol'])){
    $idRol = $_POST['idRol'];
}

if(isset($idRol) && ($idRol == 1 || $idRol == 10 || $idRol == 22)){
    $query = "SELECT * FROM roles";
}else if(isset($idRol) && $idRol == 12){
    $query = "SELECT * FROM roles WHERE id_rol IN (19, 11, 12)";
}
$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value =" . $fila['id_rol'].">". $fila['rol_descripcion']."</option>";
} 
echo $opcion;
    

?>