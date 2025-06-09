<?php

$DB_host = "52.15.158.65";
$DB_user = "grupoasi_cotizautos";
$DB_pass = "M1graci0n123";
$DB_name = "";


$URI = explode("/", $_SERVER['REQUEST_URI']);

if (in_array("dev", $URI)) {
	$DB_name = "grupoasi_cotizautos_dev";
} elseif (in_array("QAS", $URI) || in_array("qas", $URI)) {
	$DB_name = "grupoasi_cotizautos_qas";
} else {
	$DB_name = "grupoasi_cotizautos";
}


try {
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	$e->getMessage();
}


$enlace = mysqli_connect("$DB_host", "$DB_user", "$DB_pass", "$DB_name");
