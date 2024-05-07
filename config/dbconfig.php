<?php

// $DB_host = "52.15.158.65:3306";
$DB_host = "localhost";
$DB_user = "grupoasi_cotizautos";
$DB_pass = "M1graci0n123";
$DB_name = "grupoasi_cotizautos";
// $DB_pass = "";
// $DB_user = "root";
// $DB_name = "grupoasi_cotizautos";
try
{
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	$e->getMessage();
}

$enlace = mysqli_connect("$DB_host", "$DB_user", "$DB_pass", "$DB_name");
