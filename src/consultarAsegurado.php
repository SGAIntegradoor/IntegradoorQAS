<?php
session_start();

/* Conectar a la base de datos*/
require_once("../config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos


$tipoDocumento = $_POST['tipoDocumento'];
$numDocumento = $_POST['numDocumento'];
$intermediario = $_SESSION["intermediario"];


if ($tipoDocumento == 2 || $tipoDocumento == "2") {
    $res = mysqli_query($con, "SELECT * FROM `clientes` WHERE `cli_num_documento` LIKE '$numDocumento' AND `id_Intermediario` = '$intermediario' ");

    // Verifica si la consulta fue exitosa
    if ($res) {
        $num_rows = mysqli_num_rows($res);
        $data = $res->fetch_assoc();
        if ($num_rows >= 1) {
            $idClientNit = $data['id_cliente'];
            $res2 = mysqli_query($con, "SELECT * FROM `clientes_nit_repleg` WHERE id_cliente_asociado = $idClientNit ");
            // Verifica si la segunda consulta fue exitosa
            if ($res2) {
                $data2 = $res2->fetch_assoc();
                $data['estado'] = true;
                $data['rep_legal'] = $data2;
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                // Maneja el error si la segunda consulta falla
                $data = array('estado' => false, 'mensaje' => 'Error en la consulta de clientes_nit_repleg');
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $data = array('estado' => false, 'mensaje' => '! Es un Cliente Nuevo ¡');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
} else {
    $res = mysqli_query($con, "SELECT * FROM `clientes` WHERE `cli_num_documento` LIKE '$numDocumento' AND `id_Intermediario` = '$intermediario' ");
    // $num_rows = mysqli_num_rows($res);
    // $data = $res->fetch_assoc();
    $num_rows = mysqli_num_rows($res);
    $data = $res->fetch_assoc();
    if(isset($data['id_tipo_documento']) && $data['id_tipo_documento'] == 2){
        if ($res) {
            if ($num_rows >= 1) {
                $idClientNit = $data['id_cliente'];
                $res2 = mysqli_query($con, "SELECT * FROM `clientes_nit_repleg` WHERE id_cliente_asociado = $idClientNit ");
                // Verifica si la segunda consulta fue exitosa
                if ($res2) {
                    $data2 = $res2->fetch_assoc();
                    $data['estado'] = true;
                    $data['rep_legal'] = $data2;
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                } else {
                    // Maneja el error si la segunda consulta falla
                    $data = array('estado' => false, 'mensaje' => 'Error en la consulta de clientes_nit_repleg');
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                }
            } else {
                $data = array('estado' => false, 'mensaje' => '! Es un Cliente Nuevo ¡');
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        }
    } else {
        if ($num_rows >= 1) {
            $data['estado'] = true;
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            $data = array('estado' => false, 'mensaje' => '! Es un Cliente Nuevo ¡');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }

}
