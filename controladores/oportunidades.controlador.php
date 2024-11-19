<?php

require_once "modelos/oportunidades.modelo.php";

class ControladorOportunidades
{
	/*=============================================
	MOSTRAR COTIZACIONES
	=============================================*/

	static public function ctrMostrarOportunidades($fechaIni, $fechaFin)
	{
		$tabla = "oportunidades";

		$respuesta = ModeloOportunidades::mdlMostrarOportunidades($tabla, $fechaIni, $fechaFin);

		return $respuesta;
	}
	
	static public function ctrMostrarOportunidadesFilters($params)
	{

		$respuesta = ModeloOportunidades::mdlMostrarOportunidadesFilters($params);

		return $respuesta;
	}
}