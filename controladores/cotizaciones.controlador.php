<?php

class ControladorCotizaciones
{

	/*=============================================
	MOSTRAR COTIZACIONES
	=============================================*/

	static public function ctrMostrarCotizaciones($item, $valor)
	{

		session_start();
		$tabla = "cotizaciones";
		$tabla2 = "clientes";
		$tabla3 = "tipos_documentos";
		$tabla4 = "estados_civiles";
		$tabla5 = "usuarios";
		$tabla6 = "ciudadesbolivar";


		$respuesta = ModeloCotizaciones::mdlMostrarCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $item, $valor);

		return $respuesta;
	}

	static public function ctrShowQuotesAssistCard($valor, $item)
	{

		session_start();
		$tabla = "Cotizaciones_Assistcard";

		$respuesta = ModeloCotizaciones::mdlShowQuotesAssistCard($tabla, $valor, $item);

		return $respuesta;
	}

	static public function ctrShowQuoteAssistCard($id)
	{

		$tabla = "Cotizaciones_Assistcard";
		$field = "id_cotizacion";

		$respuesta = ModeloCotizaciones::mdlShowQuoteAssistCard($tabla, $field, $id);

		return $respuesta;
	}



	/*=============================================
	MOSTRAR COTIZACION "OFERTAS"
	=============================================*/

	static public function ctrMostrarCotizaOfertas($item, $valor)
	{

		$tabla = "ofertas";

		$respuesta = ModeloCotizaciones::ctrMostrarCotizaOfertas($tabla, $item, $valor);

		return $respuesta;
	}

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS ASSISTCARD"
	=============================================*/

	static public function ctrShowOffertsQuoteAssistCard($id)
	{

		$tabla = "ofertas_assistcard";
		$field = "id_cotizacion";
		$respuesta = ModeloCotizaciones::ctrMostrarCotizaOfertasAssistCard($tabla, $field, $id);

		return $respuesta;
	}


	/*=============================================
	ELIMINAR COTIZACIÓN
	=============================================*/

	static public function ctrEliminarCotizacion()
	{

		if (isset($_GET["idCotizacion"])) {

			$tabla = "cotizaciones";
			$tabla2 = "ofertas";
			$datos = $_GET["idCotizacion"];

			$respuesta = ModeloCotizaciones::mdlEliminarCotizaciones($tabla, $tabla2, $datos);

			if ($respuesta == "ok") {

				echo '<script>

				swal({
					  type: "success",
					  title: "La cotización ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result){
								if (result.value) {

								window.location = "inicio";

								}
							})

				</script>';
			}
		}
	}


	/*=============================================
	RANGO FECHAS COTIZACIONES
	=============================================*/

	static public function ctrRangoFechasCotizaciones($fechaFinalCotizaciones, $fechaInicialCotizaciones)
	{

		$tabla = "cotizaciones";
		$tabla2 = "clientes";
		$tabla3 = "tipos_documentos";
		$tabla4 = "estados_civiles";
		$tabla5 = "usuarios";
		$tabla6 = "intermediario";

		$respuesta = ModeloCotizaciones::mdlRangoFechasCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $fechaInicialCotizaciones, $fechaFinalCotizaciones);

		return $respuesta;
	}

	static public function ctrRangoFechasCotizacionesAssistCard($fechaFinalCotizaciones, $fechaInicialCotizaciones)
	{

		$tabla = "cotizaciones_assistcard";
		$tabla5 = "usuarios";

		$respuesta = ModeloCotizaciones::mdlRangoFechasCotizacionesAssistCard($tabla, $tabla5, $fechaInicialCotizaciones, $fechaFinalCotizaciones);



		return $respuesta;
	}


	static public function ctrGetDataLastRegisters($fechaInicialCotizaciones, $fechaFinalCotizaciones, $condicion)
	{
		if (isset($_GET["fechaInicialCotizaciones"]) && isset($_GET["fechaFinalCotizaciones"])) {
			$respuesta = ModeloCotizaciones::mdlGetDataLastRegisters($fechaInicialCotizaciones, $fechaFinalCotizaciones, null);
		} else if ($fechaFinalCotizaciones = null && $fechaInicialCotizaciones = null) {
			$respuesta = ModeloCotizaciones::mdlGetDataLastRegisters(null, null, $condicion);
		} else {
			$respuesta = ModeloCotizaciones::mdlGetDataLastRegisters(null, null, null);
		}

		return $respuesta;
	}
}
