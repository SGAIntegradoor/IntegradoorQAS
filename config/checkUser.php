<?php

require_once "modelos/conexion.php";

function checkUserStatus() {
  $user = json_encode($_SESSION['permisos']['usu_documento']);
  $respuesta = ControladorUsuarios::ctrUserCheckState($user);
    if($respuesta['usu_estado'] == 0){
      session_destroy();
      echo "<script>window.location.href = 'login.php';</script>";
      exit;
    }
}

