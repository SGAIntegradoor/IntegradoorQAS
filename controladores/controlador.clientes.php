<?php

require_once "modelos/conexion.php";

// Obtener el número total de registros (para la paginación)
$totalRegistros = // Tu consulta para contar el total de registros en tu base de datos

// Parámetros para la paginación
$limit = $_GET['length']; // Número de registros por página
$start = $_GET['start']; // Índice de inicio para la consulta

// Consulta para obtener los datos de la página actual
$stmt = Conexion::conectar()->prepare("SELECT * FROM clientes LIMIT $start, $limit");
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatear los resultados en un formato compatible con DataTables
$data = array();
foreach ($resultado as $fila) {
    $tempArray = array();
    $tempArray[] = $fila['Numero']; // Asignar el valor de la primera columna (Número)
    $tempArray[] = $fila['Tipo']; // Asignar el valor de la segunda columna (Tipo)
    $tempArray[] = $fila['Documento']; // Asignar el valor de la tercera columna (Documento)
    $tempArray[] = $fila['Nombre']; // Asignar el valor de la cuarta columna (Nombre)
    $tempArray[] = $fila['F_Nacimiento']; // Asignar el valor de la quinta columna (Fecha de Nacimiento)
    $tempArray[] = $fila['Gen']; // Asignar el valor de la sexta columna (Género)
    $tempArray[] = $fila['Estado_Civil']; // Asignar el valor de la séptima columna (Estado Civil)
    $tempArray[] = $fila['Telefono']; // Asignar el valor de la octava columna (Teléfono)
    $tempArray[] = $fila['Email']; // Asignar el valor de la novena columna (Email)

    $data[] = $tempArray;
}

// Crear un arreglo con la información requerida por DataTables
$output = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRegistros, // Total de registros en tu base de datos
    "recordsFiltered" => $totalRegistros, // Total de registros después de aplicar filtros (en este caso, no hay filtros)
    "data" => $data // Los datos para la tabla
);

// Enviar la respuesta como JSON
echo json_encode($output);
?>
