<?php 

session_start();
require_once __DIR__ . '/../modelos/conexion.php';

// Mostrar errores (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$idOportunidad = $_POST["id_oportunidad_edit"];

if($idOportunidad == null || $idOportunidad == ""){
    return json_encode(array("statusCode" => 0, "message" => "Error al cargar la oportunidad, ID Invalido"));
}

$stmt = Conexion::conectar()->prepare("SELECT * FROM oportunidades WHERE id_oportunidad = :id_oportunidad");
$stmt->bindParam(':id_oportunidad', $idOportunidad, PDO::PARAM_INT);



if($stmt->execute()){

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) > 0) {
        echo json_encode($result);
    } else {
        echo json_encode(array("statusCode" => 0, "message" => "Error: no existe una oportunidad con el id suministrado"));
    }
}else{
    echo json_encode(array("statusCode" => 0, "message" => "Error: no existe una oportunidad con el id suministrado"));
}





?>