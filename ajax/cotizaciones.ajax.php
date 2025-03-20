<?php

require_once "../controladores/cotizaciones.controlador.php";
require_once "../modelos/cotizaciones.modelo.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

	public $idOfertaCotizacion;
	public $idOfertaFilter;

	public function ajaxFiltrosOfertas(){

		$item = "id_cotizacion";
		$item2 = "Categoria";
		$valor = $this->idOfertaCotizacion;
		$valor2 = $this->idOfertaFilter;

		$respuesta = ControladorCotizaciones::ctrMostrarOfertasCategoria($item, $valor, $item2, $valor2);

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

	public $idCotizacionHogar;

	public function ajaxRetriveQuotationHogar(){

		$id = $this->idCotizacionHogar;

		$respuesta = ControladorCotizaciones::ctrShowQuoteHogar($id);

		echo json_encode($respuesta);

	}

	public $idCotizacionOfertas;


	public function ajaxRetriveOffertsQuotationAssistCard(){


		$id = $this->idCotizacionOfertas;

		$respuesta = ControladorCotizaciones::ctrShowOffertsQuoteAssistCard($id);

		echo json_encode($respuesta);

	}

	public $idCotizacionOfertasHogar;


	public function ajaxRetriveOffertsQuotationHogar(){


		$id = $this->idCotizacionOfertasHogar;

		$respuesta = ControladorCotizaciones::ctrShowOffertsQuoteHogar($id);

		echo json_encode($respuesta);

	}
	public $idCotizacionSalud;

	public function ajaxRetriveOffertsQuotationSalud(){

		$id = $this->idCotizacionSalud;

		$respuesta = ControladorCotizaciones::ctrShowOffertsQuoteSaludID($id);

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
FILTRO CATEGORIA "OFERTAS"
=============================================*/
if(isset($_POST["idOfertaCotizacion"]) && isset($_POST["idOfertaFilter"])){
	$editarCotizaOfertas = new AjaxCotizaciones();
	$editarCotizaOfertas -> idOfertaCotizacion = $_POST["idOfertaCotizacion"];
	$editarCotizaOfertas -> idOfertaFilter = $_POST["idOfertaFilter"];
	$editarCotizaOfertas -> ajaxFiltrosOfertas();
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
// RETOMAR COTIZACIONES ASSISTCARD POR ID
// =============================================*/
if(isset($_POST["idCotizacionAssistCard"])){

	$retriveQuoteAssistCard = new AjaxCotizaciones();
	$retriveQuoteAssistCard -> idCotizacionAssistCard = $_POST["idCotizacionAssistCard"];
	$retriveQuoteAssistCard -> ajaxRetriveQuotationAssistCard();
}

// /*=============================================
// RETOMAR COTIZACIONES HOGAR POR ID
// =============================================*/

if(isset($_POST["idCotizacionHogar"])){

	$retriveQuoteHogar = new AjaxCotizaciones();
	$retriveQuoteHogar -> idCotizacionHogar = $_POST["idCotizacionHogar"];
	$retriveQuoteHogar -> ajaxRetriveQuotationHogar();
}

// /*=============================================
// RETOMAR COTIZACION OFERTAS ASSISTCARD POR ID
// =============================================*/
if(isset($_POST["ofertasCotizacion"])){

	$retriveQuoteAssist= new AjaxCotizaciones();
	$retriveQuoteAssist -> idCotizacionOfertas = $_POST["ofertasCotizacion"];
	$retriveQuoteAssist -> ajaxRetriveOffertsQuotationAssistCard();
}

// // /*=============================================
// // RETOMAR COTIZACIONE ASSISTCARD POR ID
// // =============================================*/
// if(isset($_POST["idCotizacionHogar"])){

// 	$retriveQuoteAssistCard = new AjaxCotizaciones();
// 	$retriveQuoteAssistCard -> idCotizacionAssistCard = $_POST["idCotizacionHogar"];
// 	$retriveQuoteAssistCard -> ajaxRetriveQuotationAssistCard();
// }

// /*=============================================
// RETOMAR COTIZACION OFERTAS HOGAR POR ID
// =============================================*/
if(isset($_POST["idCotizacionHogarOfferts"])){
	$retriveQuoteHogar= new AjaxCotizaciones();
	$retriveQuoteHogar -> idCotizacionOfertasHogar = $_POST["idCotizacionHogarOfferts"];
	$retriveQuoteHogar -> ajaxRetriveOffertsQuotationHogar();
}

// /*=============================================
// RETOMAR COTIZACIONES SALUD POR ID
// =============================================*/
if(isset($_POST["idCotizacionSalud"])){

	$retriveQuoteSalud = new AjaxCotizaciones();
	$retriveQuoteSalud -> idCotizacionSalud = $_POST["idCotizacionSalud"];
	$retriveQuoteSalud -> ajaxRetriveOffertsQuotationSalud();
}

