<?php

require_once 'modelos/intermediario.modelo.php';


/**
 * Controller para intermediarios directamente extraido de PHP.
 */

class IntermediarioController {

    /**
    * Metodo estatico para traer los intermediarios directamente de la BD.
    *
    * Consulta protegida contra Inyeccion de SQL con BindParam.
    */
    public static function ctrGetIntermediarios (){
        $tabla = "intermediario";
        $respuesta = ModeloInternediario::mdlGetIntermediarios($tabla);
        return $respuesta;
    }

}





?>