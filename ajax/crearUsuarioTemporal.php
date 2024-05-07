<?php

require_once __DIR__.'/../controladores/usuarios.controlador.php';

session_start();

if (($_SESSION["rol"] == 1 || $_SESSION["rol"] == 10) && isset($_POST)) {
    $respuesta = ControladorUsuarios::ctrCrearUsuario();
    return $respuesta;
} else {
    return "Error: en el servicio";
}
