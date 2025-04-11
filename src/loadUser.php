<?php 

require_once "../config/dbconfig.php";
session_start();

if(isset($_POST['id']) && ($_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1 || $_SESSION["rol"] == 22 || $_SESSION["rol"] == 23)){
    $id = $_POST['id'];
    mysqli_set_charset($enlace, "utf8");

    // Query Usuario con Informacion Financiera
    $queryUser = "SELECT * FROM usuarios u 
    JOIN informacion_financiera_user ifu ON ifu.id_usuario = u.id_usuario
    WHERE u.id_usuario = $id";

    // Query Usuario Informacion Canal
    $queryInfoCanal = "SELECT icu.*, u.id_usuario, d.* FROM informacion_canal_usuarios icu
    INNER JOIN usuarios u ON icu.id_usuario = u.id_usuario
    INNER JOIN directores_comerciales d ON d.id_usuario = u.id_usuario
    WHERE icu.id_usuario = $id";

    // Query Usuario Informacion Aseguradoras
    $queryInfoAseguradoras = "SELECT cau.*, u.id_usuario FROM claves_aseguradoras_user cau
    INNER JOIN usuarios u ON cau.id_usuario = u.id_usuario
    WHERE cau.id_usuario = $id";

    $ejecucion = mysqli_query($enlace,$queryUser);
    $fila = $ejecucion->fetch_assoc();
    $ejecucion2 = mysqli_query($enlace,$queryInfoCanal);
    $fila2 = $ejecucion2->fetch_assoc();
    $ejecucion3 = mysqli_query($enlace,$queryInfoAseguradoras);
    $fila3 = $ejecucion3->fetch_assoc();

    echo json_encode(array(
        "info_usuario_finan" => $fila,
        "info_usuario_canal" => $fila2,
        "info_aseguradoras_user" => $fila3
    ));

    // Cerrar la conexión
    mysqli_close($enlace);
}
?>
