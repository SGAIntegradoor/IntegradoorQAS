<?php
header('Content-Type: application/json');

$maxFiles = 3;
$maxSize = 1024 * 1024; // 1MB
$uploadDir = __DIR__ . "/docsSoat/";

if (!isset($_FILES['archivos'])) {
    echo json_encode(["ok" => false, "error" => "No se recibieron archivos"]);
    exit;
}

$files = $_FILES['archivos'];

if (count($files['name']) > $maxFiles) {
    echo json_encode(["ok" => false, "error" => "Máximo 3 archivos"]);
    exit;
}

for ($i = 0; $i < count($files['name']); $i++) {

    if ($files['size'][$i] > $maxSize) {
        echo json_encode(["ok" => false, "error" => "Archivo supera 1MB"]);
        exit;
    }

    if ($files['error'][$i] !== UPLOAD_ERR_OK) {
        echo json_encode(["ok" => false, "error" => "Error al subir archivo"]);
        exit;
    }

    $nombreSeguro = basename($files['name'][$i]);
    $rutaFinal = $uploadDir . $nombreSeguro;

    if (!move_uploaded_file($files['tmp_name'][$i], $rutaFinal)) {
        echo json_encode(["ok" => false, "error" => "No se pudo guardar el archivo"]);
        exit;
    }
}

echo json_encode(["ok" => true]);
