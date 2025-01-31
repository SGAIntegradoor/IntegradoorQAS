<?php

require_once __DIR__ . "/../modelos/oportunidades.modelo.php";

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

	static public function ctrEliminarOportunidad($id_oportunidad, $id_oferta)
	{
		$tabla = "oportunidades";

		$respuesta = ModeloOportunidades::mdlEliminarOportunidad($tabla, $id_oportunidad, $id_oferta);

		return $respuesta;
	}
}