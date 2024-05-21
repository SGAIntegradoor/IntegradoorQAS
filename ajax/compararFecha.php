<?php
session_start();
require_once '../config/dbconfig.php';

$id = $_SESSION['idUsuario'];
$fecha = $_POST['fecha'];
$query = "SELECT * FROM `cotizaciones` WHERE `cot_fch_cotizacion` LIKE '%$fecha%' AND `id_usuario` = $id";
$ejecucion = mysqli_query($enlace,$query);
echo mysqli_num_rows($ejecucion);
