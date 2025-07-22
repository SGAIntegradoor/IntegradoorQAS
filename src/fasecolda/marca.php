<?php

require_once("../../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../../config/conexion.php"); //Contiene funcion que conecta a la base de datos

if ($_POST['id']) {
	$id = $_POST['id'];

	$sql = "";
	$sql = "SELECT f.marca FROM fasecolda f WHERE clase='" . $id . "' GROUP BY marca ORDER BY marca asc";

	$consulta = mysqli_query($con, $sql);

	$selectMarca = "";
	$selectMarca .= "<option value=''>Seleccione la Marca</option>";

	while ($row = mysqli_fetch_assoc($consulta)) {
		// la marca viene asi ZQ MOTORS
		// pero en el valor del option debe colocarlo con espacio y todo
		$selectMarca .= "<option value='" . $row['marca'] . "'>" . $row['marca'] . "</option>";
	}

	echo $selectMarca;
}
