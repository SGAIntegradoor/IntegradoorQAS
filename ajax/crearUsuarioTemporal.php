<?php

require_once __DIR__ . '/../controladores/usuarios.controlador.php';

session_start();

if (($_SESSION["rol"] == 1 || $_SESSION["rol"] == 10) && isset($_POST)) {
    $respuesta = ControladorUsuarios::ctrCrearUsuario();
    if($respuesta['result'] == "Success"){
        echo json_encode(array("responseSuccess" => "Success"));
    }
} else {
    echo json_encode(array("responseError" => "Error"));
}
