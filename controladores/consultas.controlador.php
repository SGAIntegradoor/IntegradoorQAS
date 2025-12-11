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

	static public function ctrGetInsurers($fechaInicialCreacion, $fechaFinalCreacion, $idfreelance)
	{

		$tabla = "aseguradoras";

		// $respuesta = ModeloConsultas::mdlGetInsurer($tabla);

		// return $respuesta;
	}
	
	static public function ctrGetRamos()
	{

		$tabla = "aseguradoras";

		// $respuesta = ModeloConsultas::mdlGetInsurer($tabla);

		// return $respuesta;
	}


}
