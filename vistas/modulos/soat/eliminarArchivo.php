<?php
$URI = explode("/", $_SERVER['REQUEST_URI']);

if (in_array("dev", $URI)) {
    $url = "/docsSoat/";
} elseif (in_array("QAS", $URI) || in_array("qas", $URI) || in_array("Pruebas", $URI)) {
    $url = "/docsSoatP/";
} else {
    $url = "/docsSoat/";
}

$folderPath = $_SERVER['DOCUMENT_ROOT'] . $url;

$data = json_decode(file_get_contents("php://input"), true);

$archivo = $data['archivo'];
$id = $data['id'];

$ruta = $folderPath . $archivo;

if (file_exists($ruta)) {
    unlink($ruta);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
