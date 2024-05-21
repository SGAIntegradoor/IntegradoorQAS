<?php
require_once "../config/dbconfig.php";

// Obtener parámetros de la solicitud
$start = $_POST['start'];
$length = $_POST['length'];

$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Construir la consulta
$sql = "SELECT * FROM clientes WHERE 1";

if (!empty($search)) {
    // Convertir la cadena de búsqueda a UTF-8 si es necesario
    $search = mb_convert_encoding($search, 'UTF-8', 'UTF-8');

    $sql .= " AND (cli_nombre LIKE '%$search%' OR cli_email LIKE '%$search%' OR cli_telefono LIKE '%$search%')";
}

$totalFiltered = $enlace->query($sql)->num_rows;

$sql .= " LIMIT $start, $length";

$result = $enlace->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    // Convertir los datos a UTF-8 si es necesario
    foreach ($row as &$value) {
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
    unset($value); // Limpiar la referencia al último elemento del array

    $data[] = $row;
}

// Obtener el total de registros
$total = $enlace->query("SELECT COUNT(*) as count FROM clientes")->fetch_assoc()['count'];

$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($total),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

// Establecer la cabecera de respuesta como JSON
header('Content-Type: application/json');

// Imprimir el JSON
echo json_encode($response);

// Cerrar la conexión a la base de datos
$enlace->close();
?>