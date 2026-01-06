<?php
header('Content-Type: application/json');

$id = $_GET['id'] ?? '';
$folderPath = "docsSoat/";
$archivosEncontrados = [];

if ($id !== '' && is_dir($folderPath)) {
    $todosLosArchivos = scandir($folderPath);

    foreach ($todosLosArchivos as $archivo) {
        // Buscamos si el archivo comienza con "ID-"
        if (strpos($archivo, $id . "-") === 0) {
            $archivosEncontrados[] = [
                "nombre" => $archivo,
                "url" => "vistas/modulos/soat/" . $folderPath . $archivo
            ];
        }
    }
}

echo json_encode($archivosEncontrados);
