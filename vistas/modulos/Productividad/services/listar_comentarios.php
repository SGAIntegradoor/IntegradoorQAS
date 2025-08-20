<?php
require_once "../../../../config/dbconfig.php";
$enlace->set_charset("utf8mb4");

$idAsesor = $_GET['id_asesor'] ?? null;

if (!$idAsesor) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de asesor faltante']);
    exit;
}

$sql = "
  SELECT 
    c.comentario, 
    c.fecha_creacion, 
    CONCAT(u.usu_nombre, ' ', u.usu_apellido) AS autor
  FROM comentarios_asesores c
  INNER JOIN usuarios u ON u.id_usuario = c.id_autor
  WHERE c.id_asesor = ?
  ORDER BY c.fecha_creacion DESC
";

$stmt = $enlace->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("i", $idAsesor);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al ejecutar la consulta']);
    exit;
}

$stmt->bind_result($comentario, $fecha_creacion, $autor);

$comentarios = [];
while ($stmt->fetch()) {
    $comentarios[] = [
        'comentario'     => $comentario,
        'fecha_creacion' => $fecha_creacion,
        'autor'          => $autor
    ];
}

$stmt->close();

echo json_encode($comentarios, JSON_UNESCAPED_UNICODE);
?>