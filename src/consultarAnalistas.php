<?php

/* Conectar a la base de datos*/
require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos

$sql = "SELECT DISTINCT `nombre_analista`,`id_analista` FROM `analistas_freelances` WHERE `nombre_analista` != ''";

$res = mysqli_query($con, $sql);
$num_rows = mysqli_num_rows($res);

if ($num_rows > 0) {
	while ($row = mysqli_fetch_array($res)) {
		$data[] = $row;
	}
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
	$data['mensaje'] = "No hay Registros";
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
