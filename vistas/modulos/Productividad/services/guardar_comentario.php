<?php
require_once "../../../../config/QAStoPRD.php";
$enlace->set_charset("utf8mb4");

$idAsesor   = $_POST['id_asesor'] ?? null;
$idAutor    = $_POST['id_autor'] ?? null;
$comentario = trim($_POST['comentario'] ?? '');

if (!$idAsesor || !$idAutor || $comentario === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos requeridos']);
    exit;
}

$sql = "INSERT INTO comentarios_asesores (id_asesor, id_autor, comentario) VALUES (?, ?, ?)";
$stmt = $enlace->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("iis", $idAsesor, $idAutor, $comentario);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'id_comentario' => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar el comentario']);
}

$stmt->close();
?>