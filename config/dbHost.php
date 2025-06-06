<?php

// /*Datos de conexion a la base de datos*/
/*Datos de conexion a la base de datos*/
// define('DB_USER', 'grupoasi_cotizautos');//Usuario de tu base de datos
// define('DB_USER', 'root');//Usuario de tu base de datos.
// define('DB_PASS', '');//Contraseña del usuario de la base de datos
// define('DB_HOST', 'localhost:3307');//DB_HOST:  generalmente suele ser "127.0.0.1"
define('DB_HOST', '52.15.158.65');//DB_HOST:  generalmente suele ser "127.0.0.1"
define('DB_USER', 'grupoasi_cotizautos');//Usuario de tu base de datos
define('DB_PASS', 'M1graci0n123');//Contraseña del usuario de la base de datos

$DB_name = "";

$URI = explode("/", $_SERVER['REQUEST_URI']);

if (in_array("dev", $URI)) {
	$DB_name = 'grupoasi_cotizautos_dev';
} else if (in_array("QAS", $URI) || in_array("qas", $URI)) {
	$DB_name = 'grupoasi_cotizautos_qas';
} else {
	$DB_name = 'grupoasi_cotizautos';
}


define('DB_NAME', $DB_name);//Nombre de la base de datos


/*Datos de la empresa*/
define('NOMBRE_EMPRESA', 'SEGUROS GRUPO ASISTENCIA');
define('NIT', '900600470');
define('DIRECCION_EMPRESA', 'Cali, Colombia.');
define('TELEFONO_EMPRESA', '+(032) 6625161');
define('EMAIL_EMPRESA', 'lasceibas@grupoasistencia.com');

?>
