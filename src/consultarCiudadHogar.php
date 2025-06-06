<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Conectar a la base de datos*/
require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos

$codigo = $_POST['codigoDpto'];

$condicion = "WHERE cod_departamento = '$codigo'";

if($codigo == 0) $condicion = "";

$sql = "SELECT codigo,ciudad,departamento, cod_departamento FROM ciudadeshogar $condicion ORDER BY ciudad ASC";

$res = mysqli_query($con, $sql);
$num_rows = mysqli_num_rows($res);

if ($num_rows > 0) {
	$data = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$data[] = $row;
	}
	$response = [
		"data" => $data,
		"success" => true
	];
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
	$response = [
		"mensaje" => "No hay Registros",
		"success" => false
	];
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
