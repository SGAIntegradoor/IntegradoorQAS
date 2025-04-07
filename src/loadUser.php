<?php 

require_once "../config/dbconfig.php";
session_start();

if(isset($_POST['id']) && ($_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1 || $_SESSION["rol"] == 22 || $_SESSION["rol"] == 23)){
    $id = $_POST['id'];
    mysqli_set_charset($enlace, "utf8");
    $query = "SELECT * FROM usuarios WHERE id_usuario = $id";
    $ejecucion = mysqli_query($enlace,$query);
    $fila = $ejecucion->fetch_assoc();
    echo json_encode($fila);
}


?>