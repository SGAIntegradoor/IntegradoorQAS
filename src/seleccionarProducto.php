<?php

// require_once ("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
// require_once ("../config/conexion.php"); //Contiene funcion que conecta a la base de datos

include_once '../config/dbconfig.php';

if ($_POST['aseguradora']) {

	$aseguradora = "";

	if ($_POST['aseguradora'] == 'Seguros del Estado') {
		$aseguradora = "Estado";
	} else if ($_POST['aseguradora'] == 'Seguros Bolivar') {
		$aseguradora = "Bolivar";
	} else if ($_POST['aseguradora'] == 'Axa Colpatria') {
		$aseguradora = "Axa Colpatria";
	} else if ($_POST['aseguradora'] == 'HDI Seguros') {
		$aseguradora = "HDI";
	} else if ($_POST['aseguradora'] == 'SBS Seguros') {
		$aseguradora = "SBS";
	} else if ($_POST['aseguradora'] == 'Allianz Seguros') {
		$aseguradora = "Allianz";
	} else if ($_POST['aseguradora'] == 'Equidad Seguros') {
		$aseguradora = "Equidad";
	} else if ($_POST['aseguradora'] == 'Seguros Mapfre') {
		$aseguradora = "Mapfre";
	} else if ($_POST['aseguradora'] == 'Liberty Seguros') {
		$aseguradora = "Liberty";
	} else if ($_POST['aseguradora'] == 'Aseguradora Solidaria') {
		$aseguradora = "Solidaria";
	} else if ($_POST['aseguradora'] == 'Seguros Sura') {
		$aseguradora = "SURA";
	} else if ($_POST['aseguradora'] == 'Zurich Seguros') {
		$aseguradora = "Zurich";
	} else if ($_POST['aseguradora'] == 'Previsora Seguros') {
		$aseguradora = "Previsora";
	} else {
		$aseguradora = $_POST['aseguradora'];
	}

	if ($aseguradora == "Liberty") {
		$stmt = $DB_con->prepare("SELECT aseguradora, producto, id_asistencias, pth, ppd, pph FROM asistencias WHERE aseguradora = :aseguradora ORDER BY id_asistencias");
		// var_dump($stmt->queryString);
		$stmt->execute(array(':aseguradora' => $aseguradora));
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	} else {
		$stmt = $DB_con->prepare("SELECT producto FROM asistencias WHERE aseguradora = :aseguradora GROUP BY producto ORDER BY id_asistencias");
		// var_dump($stmt->queryString);
		$stmt->execute(array(':aseguradora' => $aseguradora));
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
}
