<?php

require_once "../config/dbconfig.php";
header('Content-Type: text/html; charset=utf-8');
mysqli_set_charset($enlace, "utf8mb4");

$params = isset($_POST["param"]) && $_POST["param"] != "" ? $_POST["param"] : "";

$query = $params == "" ?
    "SELECT * FROM oportunidades o INNER JOIN usuarios u ON o.id_user_freelance = u.id_usuario GROUP BY o.id_user_freelance" :
    "SELECT * FROM usuarios u WHERE id_rol IN (19, 12, 11, 10, 1) AND usu_estado = 1 AND id_Intermediario = 3 ORDER BY u.usu_nombre ASC;";

$ejecucion = mysqli_query($enlace, $query);
$opcion = "";

while ($fila = $ejecucion->fetch_assoc()) {
    $opcion .= "<option value='" . htmlspecialchars($fila['id_usuario'], ENT_QUOTES, 'UTF-8') . "'>"
        . htmlspecialchars($fila['usu_nombre'], ENT_QUOTES, 'UTF-8') . " "
        . htmlspecialchars($fila['usu_apellido'], ENT_QUOTES, 'UTF-8')
        . "</option>";
}

echo $opcion;
