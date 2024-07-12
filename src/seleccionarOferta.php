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

echo "Placa: ".$placa."<br/> Id Cotizacion: ".$idCotizacion."<br/> Aseguradora: ".$aseguradora."<br/> Producto: ".$producto."<br/> Num Coti: ".$numCotizOferta."<br/> Valor Prima: ".$valorPrima."<br/> Seleccionar: ".$seleccionar."<br/>";

$sql = "UPDATE `ofertas` SET `seleccionar` = ? WHERE `Placa` LIKE ? AND `NumCotizOferta` LIKE ? 
        AND `Aseguradora` LIKE ? AND `Producto` LIKE ? AND `Prima` LIKE ? AND `id_cotizacion` = ?";

if ($stmt = mysqli_prepare($con, $sql)) {
    mysqli_stmt_bind_param($stmt, "ssssssi", $seleccionar, $placa, $numCotizOferta, $aseguradora, $producto, $valorPrima, $idCotizacion);
    
    if (mysqli_stmt_execute($stmt)) {
        $num_rows = mysqli_stmt_affected_rows($stmt);
        
        if ($num_rows > 0) {
            $data['Success'] = true;
            $data['Message'] = 'La actualización fue exitosa';
        } else {
            $data['Success'] = false;
            $data['Message'] = 'No se afectaron filas o no se encontraron registros coincidentes';
        }
    } else {
        $data['Success'] = false;
        $data['Message'] = 'Error en la ejecución: ' . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
} else {
    $data['Success'] = false;
    $data['Message'] = 'Error en la preparación de la consulta: ' . mysqli_error($con);
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>
