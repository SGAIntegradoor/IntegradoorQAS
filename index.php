<?php

ini_set('session.gc_maxlifetime', 1800);
ini_set('session.cookie_lifetime', 1800);

require_once "controladores/plantilla.controlador.php";

require_once "controladores/usuarios.controlador.php";

require_once "controladores/clientes.controlador.php";

require_once "controladores/cotizaciones.controlador.php";



require_once "modelos/usuarios.modelo.php";

require_once "modelos/clientes.modelo.php";

require_once "modelos/cotizaciones.modelo.php";

require_once "extensiones/vendor/autoload.php";

$plantilla = new ControladorPlantilla();

$plantilla -> ctrPlantilla();
