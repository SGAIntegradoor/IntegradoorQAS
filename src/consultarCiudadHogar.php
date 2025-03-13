<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Conectar a la base de datos*/
require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos


$codigo = $_POST['codigoDpto'];


$sql = "SELECT `codigo`,`ciudad`,`departamento`, `cod_departamento` FROM `ciudadeshogar` WHERE `cod_departamento` = '$codigo' ORDER BY `codigo` ASC";

$res = mysqli_query($con, $sql);
$num_rows = mysqli_num_rows($res);


if ($num_rows > 0) {
	echo json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_UNESCAPED_UNICODE);
} else {
	$data['mensaje'] = "No hay Registros";
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
