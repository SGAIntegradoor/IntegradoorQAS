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
		$table7 = "clientes_nit_repleg";


		$respuesta = ModeloCotizaciones::mdlMostrarCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $table7, $item, $valor);

		return $respuesta;
	}

	static public function ctrShowQuotesAssistCard($valor, $item)
	{

		session_start();
		$tabla = "Cotizaciones_Assistcard";

		$respuesta = ModeloCotizaciones::mdlShowQuotesAssistCard($tabla, $valor, $item);

		return $respuesta;
	}

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS ASSISTCARD"
	=============================================*/

	static public function ctrShowQuoteAssistCard($id)
	{
		session_start();
		$tabla = "cotizaciones_assistcard";
		$field = "id_cotizacion";
		$respuesta = ModeloCotizaciones::mdlShowQuoteAssistCard($tabla, $field, $id);

		return $respuesta;
	}

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS HOGAR"
	=============================================*/

	static public function ctrShowQuoteHogar($id)
	{
		session_start();
		$tabla = "cotizaciones_hogar";
		$tabla2 = "clientes";
		$field = "id";

		$respuesta = ModeloCotizaciones::mdlShowQuoteHogar($tabla, $tabla2, $field, $id);

		return $respuesta;
	}

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS ASSISTCARD"
	=============================================*/

	static public function ctrShowOffertsQuoteAssistCard($id)
	{

		session_start();
		$tabla = "ofertas_assistcard";
		$field = "id_cotizacion";
		$respuesta = ModeloCotizaciones::ctrMostrarCotizaOfertasAssistCard($tabla, $field, $id);

		return $respuesta;
	}

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS HOGAR"
	=============================================*/


	static public function ctrShowOffertsQuoteHogar($id)
	{
		session_start();
		$tabla = "ofertas_hogar";
		$field = "id_cotizacion";
		$respuesta = ModeloCotizaciones::ctrMostrarCotizaOfertasHogar($tabla, $field, $id);

		return $respuesta;
	}

	// /*=============================================
	// MOSTRAR COTIZACION DE SALUD POR ID
	// =============================================*/

	// static public function ctrShowQuoteSaludID($id)
	// {
	// 	session_start();
	// 	$tabla = "cotizaciones_salud";
	// 	$field = "id_cotizacion";

	// 	$respuesta = ModeloCotizaciones::mdlShowQuoteSalud($tabla, $field, $id);

	// 	return $respuesta;
	// }

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS SALUD POR ID"
	=============================================*/

	static public function ctrShowOffertsQuoteSaludID($id, $filtro)
	{
		session_start();
		$tabla = "cotizaciones_salud";
		$tabla2 = "tomadores_cotizaciones_salud";
		$tabla3 = "asegurados_cotizaciones_salud";
		$tabla4 = "planes_cotizaciones_salud";
		$tabla5 = "usuarios";
		$tabla7 = "coberturas_salud";
		$tabla8 = "planes_salud";
		$tabla9 = "aseguradoras_salud";
		$tabla6 = "(SELECT
						ch.codigo AS id_ciudad,
						ch.cod_departamento AS id_departamento,
						ch.ciudad AS ciudad_asegurado,
						ch.departamento AS departamento_asegurado
				    FROM ciudadeshogar ch)";
		$field = "id_cotizacion";
		$respuesta = ModeloCotizaciones::mdlShowQuoteSalud($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $tabla7, $tabla8, $tabla9, $field, $id, $filtro);

		return $respuesta;
	}

	/*=============================================
		MOSTRAR COTIZACION "OFERTAS"
	=============================================*/

	static public function ctrMostrarCotizaOfertas($item, $valor)
	{
		session_start();
		$tabla = "ofertas";

		$respuesta = ModeloCotizaciones::mdlMostrarCotizaOfertas($tabla, $item, $valor);

		return $respuesta;
	}

	/*=============================================
		MOSTRAR "OFERTAS" CATEGORIA
	=============================================*/

	static public function ctrMostrarOfertasCategoria($item, $valor, $item2, $valor2)
	{
		session_start();
		$tabla = "ofertas";

		$respuesta = ModeloCotizaciones::mdlCategoriaOfertas($tabla, $item, $valor, $item2, $valor2);

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

	static public function ctrRangoFechasCotizacionesExequias($fechaFinalCotizaciones, $fechaInicialCotizaciones)
	{

		$tabla = "segurosexequiales";
		$tabla5 = "usuarios";

		$respuesta = ModeloCotizaciones::mdlRangoFechasCotizacionesExequias($tabla, $tabla5, $fechaInicialCotizaciones, $fechaFinalCotizaciones);



		return $respuesta;
	}

	static public function ctrRangoFechasCotizacionesSalud($fechaFinalCotizaciones, $fechaInicialCotizaciones)
	{

		$tabla = "cotizaciones_salud";
		$tabla2 = "tomadores_cotizaciones_salud";
		$tabla3 = "asegurados_cotizaciones_salud";
		$tabla4 = "planes_cotizaciones_salud";
		$tabla5 = "usuarios";

		$respuesta = ModeloCotizaciones::mdlRangoFechasCotizacionesSalud($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $fechaInicialCotizaciones, $fechaFinalCotizaciones);



		return $respuesta;
	}
	static public function ctrRangoFechasCotizacionesHogar($fechaFinalCotizaciones, $fechaInicialCotizaciones)
	{

		$tabla = "cotizaciones_hogar";
		$tabla2 = "ofertas_hogar";
		$tabla3 = "clientes";
		$tabla4 = "usuarios";

		$respuesta = ModeloCotizaciones::mdlRangoFechasCotizacionesHogar($tabla, $tabla2, $tabla3, $tabla4, $fechaInicialCotizaciones, $fechaFinalCotizaciones);

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

	static public function ctrMostrarCotizacionesFilters($params)
	{
		$respuesta = ModeloCotizaciones::mdlMostrarCotizacionesFilters($params);

		return $respuesta;
	}
}
