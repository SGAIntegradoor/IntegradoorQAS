<?php

require_once "../config/dbconfig.php";
session_start();

if (isset($_POST['id_comision']) && ($_SESSION["rol"] == 12 || $_SESSION["rol"] == 11 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1 || $_SESSION["rol"] == 22 || $_SESSION["rol"] == 23)) {
    $id_comision = $_POST['id_comision'];
    mysqli_set_charset($enlace, "utf8");
    $query = "SELECT * FROM comisiones_usuarios WHERE id_comision = $id_comision";
    $ejecucion = mysqli_query($enlace, $query);
    $result = array();
    while ($fila = $ejecucion->fetch_assoc()) {
        $result[] = array(
            'id_comision' => $fila['id_comision'],
            'ramo' => $fila['ramo'],
            'unidad_negocio' => $fila['unidad_negocio'],
            'tipo_negocio' => $fila['tipo_negocio'],
            'tipo_expedicion' => $fila['tipo_expedicion'],
            'valor_comision' => $fila['valor_comision'],
            'observaciones' => $fila['observaciones']
        );
    }
    echo json_encode($result);
}
