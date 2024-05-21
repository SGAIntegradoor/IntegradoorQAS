<?php

require_once __DIR__.'/../controladores/usuarios.controlador.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (($_SESSION["rol"] == 1 || $_SESSION["rol"] == 10) && isset($_POST)) {
    $respuesta = ControladorUsuarios::ctrCrearUsuario();
    return $respuesta;
} else {
    return "Error: en el servicio";
}
