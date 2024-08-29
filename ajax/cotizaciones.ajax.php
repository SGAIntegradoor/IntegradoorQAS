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

	public $idCotizacionAssistCard;

	public function ajaxRetriveQuotationAssistCard(){


		$id = $this->idCotizacionAssistCard;

		$respuesta = ControladorCotizaciones::ctrShowQuoteAssistCard($id);

		echo json_encode($respuesta);

	}

	public $idCotizacionOfertas;

	public function ajaxRetriveOffertsQuotationAssistCard(){


		$id = $this->idCotizacionOfertas;

		$respuesta = ControladorCotizaciones::ctrShowOffertsQuoteAssistCard($id);

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
COTIZACIONES ASSISTCARD "OFERTAS"
=============================================*/
if(isset($_POST["cotAssistCard"])){

	$retriveQuoteAssistCard = new AjaxCotizaciones();
	$retriveQuoteAssistCard -> assistCardControl = $_POST["cotAssistCard"];
	$retriveQuoteAssistCard -> ajaxRetriveQuotationsAssistCard();
}

// /*=============================================
// RETOMAR COTIZACIONE ASSISTCARD POR ID
// =============================================*/
if(isset($_POST["idCotizacionAssistCard"])){

	$retriveQuoteAssistCard = new AjaxCotizaciones();
	$retriveQuoteAssistCard -> idCotizacionAssistCard = $_POST["idCotizacionAssistCard"];
	$retriveQuoteAssistCard -> ajaxRetriveQuotationAssistCard();
}

// /*=============================================
// RETOMAR COTIZACION OFERTAS ASSISTCARD POR ID
// =============================================*/
if(isset($_POST["ofertasCotizacion"])){

	$retriveQuoteAssist= new AjaxCotizaciones();
	$retriveQuoteAssist -> idCotizacionOfertas = $_POST["ofertasCotizacion"];
	$retriveQuoteAssist -> ajaxRetriveOffertsQuotationAssistCard();
}

