<?php

require_once "modelos/consultas.modelo.php";
class ControladorConsultas {
    static public function ctrMostrarNegocios($fechaInicialCreacion, $fechaFinalCreacion, $idfreelance)
	{

		$tabla = "polizas";
		$tabla2 = "anexos_polizas";
		$tabla3 = "pagos_polizas";

		$respuesta = ModeloConsultas::mdlMostrarNegocios($tabla, $tabla2, $tabla3, $fechaInicialCreacion, $fechaFinalCreacion, $idfreelance);

		return $respuesta;
	}

	static public function ctrGetInsurers()
	{

		$tabla = "aseguradoras";

	    $respuesta = ModeloConsultas::mdlGetInsurers($tabla);

		return $respuesta;
	}
	
	static public function ctrGetRamos()
	{

		$tabla = "ramos";

		$respuesta = ModeloConsultas::mdlGetRamos($tabla);

		return $respuesta;
	}


}
