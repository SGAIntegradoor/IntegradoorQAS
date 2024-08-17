<?php

require_once "../controladores/cotizaciones.controlador.php";
require_once "../modelos/cotizaciones.modelo.php";

class AjaxCotizaciones {

	/*=============================================
	EDITAR COTIZACIONES
	=============================================*/	

	public $idCotizacion;

	public function ajaxEditarCotizacion(){

		$item = "id_cotizacion";
		$valor = $this->idCotizacion;

		$respuesta = ControladorCotizaciones::ctrMostrarCotizaciones($item, $valor);

		echo json_encode($respuesta);

	}

	/*=============================================
	EDITAR COTIZACIONES "OFERTAS"
	=============================================*/

	public $idCotizaOferta;

	public function ajaxEditarCotizaOfertas(){

		$item = "id_cotizacion";
		$valor = $this->idCotizaOferta;

		$respuesta = ControladorCotizaciones::ctrMostrarCotizaOfertas($item, $valor);

		echo json_encode($respuesta);

	}

	public $assistCardControl;

	public function ajaxRetriveQuotationsAssistCard(){

		
		$valor = $this->assistCardControl;
		$item = "assistcard_cots";

		$respuesta = ControladorCotizaciones::ctrShowQuotesAssistCard($valor, $item);

		echo json_encode($respuesta);

	}

}


/*=============================================
EDITAR COTIZACIONES
=============================================*/
if(isset($_POST["idCotizacion"])){

	$editarCotizacion = new AjaxCotizaciones();
	$editarCotizacion -> idCotizacion = $_POST["idCotizacion"];
	$editarCotizacion -> ajaxEditarCotizacion();

}

/*=============================================
EDITAR COTIZACIONES "OFERTAS"
=============================================*/
if(isset($_POST["idCotizaOferta"])){

	$editarCotizaOfertas = new AjaxCotizaciones();
	$editarCotizaOfertas -> idCotizaOferta = $_POST["idCotizaOferta"];
	$editarCotizaOfertas -> ajaxEditarCotizaOfertas();

}

/*=============================================
EDITAR COTIZACIONES "OFERTAS"
=============================================*/
if(isset($_POST["cotAssistCard"])){

	$retriveQuoteAssistCard = new AjaxCotizaciones();
	$retriveQuoteAssistCard -> assistCardControl = $_POST["cotAssistCard"];
	$retriveQuoteAssistCard -> ajaxRetriveQuotationsAssistCard();

}


