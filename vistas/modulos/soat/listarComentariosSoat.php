<?php
session_start();

require_once("../../../modelos/conexion.php");

// Crear la conexión usando tu clase Conexion
$enlace = Conexion::conectar();
$enlace->exec("SET NAMES utf8mb4");

// Obtener parámetros
$idAsesor  = $_GET['id_asesor'] ?? null;
$idGeneral = $_GET['id_general'] ?? null;

if (!$idAsesor) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de asesor faltante']);
    exit;
}
if (!$idGeneral) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de cotizacion']);
    exit;
}

// Consulta SQL
$sql = "
SELECT 
    c.comentario AS comentario, 
    DATE_FORMAT(c.fecha_comentario, '%d/%m/%Y %h:%i %p') AS fecha_creacion, 
    c.nombre_usuario_comentario AS autor
FROM comentarios_usuarios c
INNER JOIN usuarios u ON u.id_usuario = c.id_user_comentario
INNER JOIN cotizaciones_soat cs ON cs.id_cotizacion = c.id_general
WHERE c.id_user_comentario = :idAsesor
  AND c.id_general = :idGeneral
  AND c.modulo = 'Soat'
ORDER BY c.fecha_comentario DESC
";

try {
    $stmt = $enlace->prepare($sql);

    // Vincular parámetros usando PDO
    $stmt->bindParam(':idAsesor', $idAsesor, PDO::PARAM_INT);
    $stmt->bindParam(':idGeneral', $idGeneral, PDO::PARAM_INT);

    $stmt->execute();

    // Obtener resultados como arreglo asociativo
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($comentarios, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    exit;
}
?>
