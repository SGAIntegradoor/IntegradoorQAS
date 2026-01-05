<?php

require_once "../config/dbconfig.php";
session_start();

if (isset($_POST['id']) && ($_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1 || $_SESSION["rol"] == 22 || $_SESSION["rol"] == 23)) {
    $id = $_POST['id'];
    mysqli_set_charset($enlace, "utf8");

    $preQuery = "SELECT * FROM usuarios u WHERE u.id_usuario = $id";
    $preEjecucion = mysqli_query($enlace, $preQuery);
    $preFetch = $preEjecucion->fetch_assoc();

    if (!$preEjecucion) {
        die("Error en consulta: " . mysqli_error($enlace));
    }


    // Query Usuario con Informacion Financiera

    if ($preFetch["id_usuario"] == null) {
        echo json_encode(array(
            "info_usuario" => null,
            "info_usuario_canal" => null,
            "info_aseguradoras_user" => null
        ));
        exit;
    }

    if ($preFetch["id_rol"] == 19) {

        $condicion = "LEFT JOIN informacion_financiera_user ifu ON ifu.id_usuario = u.id_usuario";

        $queryUser = "SELECT * FROM usuarios u 
        $condicion
        WHERE u.id_usuario = $id";

        $ejecucion = mysqli_query($enlace, $queryUser);
        $fila = $ejecucion->fetch_assoc();

        // Query Usuario Informacion Canal
        $queryInfoCanal = "SELECT icu.*, u.id_usuario, d.* FROM informacion_canal_user icu
        LEFT JOIN usuarios u ON icu.id_usuario = u.id_usuario
        LEFT JOIN directores_comerciales d ON d.id_doc_director = icu.director_comercial
        WHERE icu.id_usuario = $id";

        // Query Usuario Informacion Aseguradoras
        $queryInfoAseguradoras = "SELECT cau.*, u.id_usuario FROM claves_aseguradoras_user cau
        LEFT JOIN usuarios u ON cau.id_usuario = u.id_usuario
        WHERE cau.id_usuario = $id";

        $ejecucion2 = mysqli_query($enlace, $queryInfoCanal);
        $fila2 = ($ejecucion2) ? $ejecucion2->fetch_assoc() : null;

        $ejecucion3 = mysqli_query($enlace, $queryInfoAseguradoras);
        $fila3 = ($ejecucion3) ? $ejecucion3->fetch_assoc() : null;

        echo json_encode(array(
            "info_usuario" => $fila,
            "info_usuario_canal" => $fila2,
            "info_aseguradoras_user" => $fila3
        ));

        // Cerrar la conexión
        mysqli_close($enlace);
    } else {
        $queryUser = "SELECT * FROM usuarios u 
        WHERE u.id_usuario = $id";

        $ejecucion = mysqli_query($enlace, $queryUser);
        $fila = $ejecucion->fetch_assoc();

        $queryInfoCanal = "SELECT icu.*, u.id_usuario, d.* FROM informacion_canal_user icu
        LEFT JOIN usuarios u ON icu.id_usuario = u.id_usuario
        LEFT JOIN directores_comerciales d ON d.documento_director = icu.director_comercial
        WHERE icu.id_usuario = $id";

        var_dump($queryInfoCanal);

        $ejecucion2 = mysqli_query($enlace, $queryInfoCanal);
        $fila2 = ($ejecucion2) ? $ejecucion2->fetch_assoc() : null;

        echo json_encode(array(
            "info_usuario" => $fila,
            "info_usuario_canal" => $fila2,
            "info_aseguradoras_user" => null
        ));
        // Cerrar la conexión
        mysqli_close($enlace);
    }
}
