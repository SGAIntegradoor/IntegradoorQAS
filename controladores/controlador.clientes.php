<?php

require_once "../config/dbconfig.php";

// Obtener parámetros de la solicitud
$start = $_POST['start'];
$length = $_POST['length'];

if(isset($_POST['search'])){
}
$search = $_POST['search']['value'];

// Construir la consulta
$sql = "SELECT * FROM clientes WHERE 1";

if (!empty($search)) {
    $sql .= " AND (cli_nombre LIKE '%$search%' OR cli_email LIKE '%$search%' OR cli_telefono LIKE '%$search%')";
}

$totalFiltered = $enlace->query($sql)->num_rows;

$sql .= " LIMIT $start, $length";

$result = $enlace->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
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

echo json_encode($response);

$enlace->close();
