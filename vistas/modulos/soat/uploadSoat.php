<?php
// 1. Evitar que los Warnings rompan el JSON de respuesta
error_reporting(0);
header('Content-Type: application/json');

$response = ["ok" => false, "error" => ""];

// 2. Definir la ruta de la carpeta (Relativa al archivo PHP)
$folderPath = "docsSoat/"; 

// 3. Verificar si la carpeta existe, si no, crearla
if (!file_exists($folderPath)) {
    mkdir($folderPath, 0777, true);
}

if (isset($_FILES['archivos'])) {
    $totalFiles = count($_FILES['archivos']['name']);
    $successCount = 0;

    for ($i = 0; $i < $totalFiles; $i++) {
        $fileName = $_FILES['archivos']['name'][$i];
        $tempPath = $_FILES['archivos']['tmp_name'][$i];
        
        // Limpiamos el nombre de posibles caracteres extraños o rutas malformadas
        $cleanName = basename($fileName);
        $targetFilePath = $folderPath . $cleanName;

        if (move_uploaded_file($tempPath, $targetFilePath)) {
            $successCount++;
        }
    }

    if ($successCount === $totalFiles) {
        $response["ok"] = true;
        $response["message"] = "Archivos subidos con éxito";
    } else {
        $response["error"] = "Error al mover algunos archivos.";
    }
} else {
    $response["error"] = "No se recibieron archivos.";
}

echo json_encode($response);