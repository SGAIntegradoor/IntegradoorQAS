<?php
header('Content-Type: application/json');

$URI = explode("/", $_SERVER['REQUEST_URI']);

if (in_array("dev", $URI)) {
    $url = "localhost/docsSoat/";
    $path = "/docsSoat/";
} elseif (in_array("QAS", $URI) || in_array("qas", $URI) || in_array("Pruebas", $URI)) {
    $url = "integradoor.com/docsSoatP/";
    $path = "/docsSoatP/";
} else {
    $url = "integradoor.com/docsSoat/";
    $path = "/docsSoat/";
}

$id = $_GET['id'] ?? '';
$folderPath = $_SERVER['DOCUMENT_ROOT'] . $path;
$archivosEncontrados = [];

if ($id !== '' && is_dir($folderPath)) {
    $todosLosArchivos = scandir($folderPath);

    foreach ($todosLosArchivos as $archivo) {
        if (strpos($archivo, $id . "-") === 0) {
            $archivosEncontrados[] = [
                "nombre" => $archivo,
                "url" => $url . $archivo,
                "fecha" => filemtime($folderPath . $archivo)
            ];
        }
    }

    // Ordenar por fecha
    usort($archivosEncontrados, function ($a, $b) {
        return $b['fecha'] <=> $a['fecha'];
    });

    // quitar la fecha del JSON final
    foreach ($archivosEncontrados as &$archivo) {
        unset($archivo['fecha']);
    }
}

echo json_encode($archivosEncontrados);
