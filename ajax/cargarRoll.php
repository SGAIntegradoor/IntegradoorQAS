<?php


require_once "../config/dbconfig.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "";

if(isset($_POST['saludo'])){
    $mensaje = $_POST['saludo'];
}

if(isset($_POST['idRol'])){
    $idRol = $_POST['idRol'];
}

if(isset($idRol) && in_array($idRol, [1,11, 12, 22, 23, 10])){
    $query = "SELECT * FROM roles";
}

$ejecucion = mysqli_query($enlace,$query);
$opcion = "";
while($fila = $ejecucion->fetch_assoc()){
    $opcion.= "<option value =" . $fila['id_rol'].">". $fila['rol_descripcion']."</option>";
} 
echo $opcion;
    

?>