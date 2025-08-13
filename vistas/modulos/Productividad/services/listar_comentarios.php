<?php
require_once "../../../../config/QAStoPRD.php";
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
$stmt->bind_param("i", $idAsesor);
$stmt->execute();

$result = $stmt->get_result();
$comentarios = [];

while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}

echo json_encode($comentarios, JSON_UNESCAPED_UNICODE);
?>
