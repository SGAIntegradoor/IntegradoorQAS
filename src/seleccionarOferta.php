<?php

/* Conectar a la base de datos */
require_once("../config/db.php"); // Contiene las variables de configuración para conectar a la base de datos
require_once("../config/conexion.php"); // Contiene función que conecta a la base de datos

$placa = $_POST['placa'];
$idCotizacion = $_POST['idCotizacion'];
$aseguradora = $_POST['aseguradora'];
$producto = $_POST['producto'];
$numCotizOferta = $_POST['numCotizOferta'];
$valorPrima = str_replace('.', '', $_POST['valorPrima']);
$seleccionar = $_POST['seleccionar'];

echo " Placa: ".$placa."<br/> Id Cotizacion: ".$idCotizacion."<br/> Aseguradora: ".$aseguradora."<br/> Producto ".$producto."<br/> Num Coti: ".$numCotizOferta."<br/> Valor Prima: ".$valorPrima."<br/> Seleccionar: ".$seleccionar."<br/>";

$sql = "UPDATE `ofertas` SET `seleccionar` = '$seleccionar' WHERE `Placa` LIKE '$placa' AND `NumCotizOferta` LIKE '$numCotizOferta' 
        AND `Aseguradora` LIKE '$aseguradora' AND `Producto` LIKE '$producto' AND `Prima` LIKE '$valorPrima' AND `id_cotizacion` = $idCotizacion";

echo $sql;

$res = mysqli_query($con, $sql);

if ($res === false) {
    $data['Success'] = false;
    $data['Message'] = 'Error: ' . mysqli_error($con);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$num_rows = mysqli_affected_rows($con);

if ($num_rows > 0) {
    $data['Success'] = true;
    $data['Message'] = 'La actualización fue exitosa';
} else {
    $data['Success'] = false;
    $data['Message'] = 'No se afectaron filas o error en la consulta';
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>