<?php

// Conectar a la base de datos
require_once("../config/db.php");
require_once("../config/conexion.php");

// Validar que el dato exista
$codigo = isset($_POST['data']) ? intval($_POST['data']) : 0;

// Mapeo de códigos
$codigoMap = [
	32 => 30,
	2 => 2,
	4 => 4,
	5 => 4,
	7 => 5,
	8 => 6,
	9 => 7,
	10 => 10,
	11 => 11,
	12 => 12,
	13 => 11,
	15 => 13,
	16 => 14,
	18 => 44,
	20 => 18,
	21 => 19,
	22 => 20,
	23 => 21,
	24 => 22,
	25 => 25,
	26 => 24,
	27 => 25,
	29 => 27,
	30 => 28,
	31 => 29
];

// Obtener el valor mapeado o usar el mismo si no existe en el mapeo
$codigoV = $codigoMap[$codigo] ?? $codigo;

// Construcción de la consulta
switch ($codigo) {
	case 5:
		$sql = "SELECT DISTINCT `Nombre`, `Departamento`, `Codigo` 
                FROM `ciudadesbolivar` 
                WHERE `Codigo` = '4000' 
                ORDER BY `Nombre` ASC";
		break;

	case 6:
		$sql = "SELECT DISTINCT `Nombre`, `Departamento`, `Codigo` 
                FROM `ciudadesbolivar` 
                WHERE `Codigo` = '14000' 
                ORDER BY `Nombre` ASC";
		break;

	case 0:
		$sql = "SELECT DISTINCT `Nombre`, `Departamento`, `Codigo` 
                FROM `ciudadesbolivar` 
                ORDER BY `Nombre` ASC";
		break;

	case 18:
		$sql = "SELECT DISTINCT `ciudad` as `Nombre`, `departamento` as `Departamento`, `codigo` as `Codigo` 
                FROM `ciudades` 
				WHERE `Codigo` LIKE '44%'
                ORDER BY `Nombre` ASC";
		break;

	default:
		$sql = "SELECT DISTINCT `Nombre`, `Departamento`, `Codigo` 
                FROM `ciudadesbolivar` 
                WHERE `Departamento` = " . intval($codigoV) . " 
                ORDER BY `Nombre` ASC";
		break;
}

$res = mysqli_query($con, $sql);

if ($res === false) {
	$data['mensaje'] = "No hay Registros";
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}

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
