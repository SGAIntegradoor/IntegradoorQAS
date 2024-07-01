<?php
$DB_host = "52.15.158.65:3306";
// $DB_host = "localhost";
$DB_user = "grupoasi_cotizautos";
$DB_pass = "M1graci0n123";
$DB_name = "grupoasi_cotizautos";
// $DB_pass = "";
// $DB_user = "root";
// $DB_name = "grupoasi_cotizautos";

try {
    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name};charset=utf8", $DB_user, $DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB_con->exec("set names utf8"); // Añadir esta línea para asegurar el juego de caracteres
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit; // Salir del script si hay error de conexión
}


$enlace = mysqli_connect("$DB_host", "$DB_user", "$DB_pass", "$DB_name");

