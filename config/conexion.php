<?php

include_once 'dbHost.php';


// Conexión a la base de datos
$con = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Validar la conexión
if (!$con) {
    die("Imposible conectarse: " . mysqli_connect_error());
}

// Configurar el conjunto de caracteres
if (!$con->set_charset("utf8")) {
    die("Error al establecer el conjunto de caracteres: " . $con->error);
}

?>
