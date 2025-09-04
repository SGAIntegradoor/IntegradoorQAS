<?php
session_start();

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Bogota');


// Incluye la biblioteca TCPDF principal (busca la ruta de instalación).
require_once('tcpdf_include.php');

//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,array(150,  255), true, 'UTF-8', false);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

$identificador = $_GET['cotizacion'];

$user = "grupoasi_cotizautos";
$password = "M1graci0n123"; //poner tu propia contraseña, si tienes una.

$URI = explode("/", $_SERVER['REQUEST_URI']);

if (in_array("dev", $URI)) {
	$server = "52.15.158.65";
	$bd = "grupoasi_cotizautos_dev";
} else if (in_array("QAS", $URI)) {
	$server = "52.15.158.65";
	$bd = "grupoasi_cotizautos_qas";
} else if (in_array("Pruebas", $URI)) {
	$server = "52.15.158.65";
	$bd = "grupoasi_cotizautos_qas";
} else {
	$server = "52.15.158.65";
	$bd = "grupoasi_cotizautos";
}

$conexion = mysqli_connect($server, $user, $password, $bd);
if (!$conexion) {
	die('Error de Conexión: ' . mysqli_connect_errno());
}
$conexion->set_charset("utf8");
$intermediario = $_SESSION['intermediario'];


$query2 = "SELECT *	FROM cotizaciones, clientes WHERE cotizaciones.id_cliente = clientes.id_cliente AND `id_cotizacion` = $identificador";
$valor2 = $conexion->query($query2);
$fila = mysqli_fetch_array($valor2);

$query5x = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si' ORDER BY Aseguradora ASC";
$respuestaquery5x = $conexion->query($query5x);
$rowValidate = mysqli_num_rows($respuestaquery5x);

$query5f = "SELECT DISTINCT o.*, cf.cuota_1, cf.cuotas, cf.identityElement
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";
$respuestaquery5f = $conexion->query($query5f);
$rowValidateF = mysqli_num_rows($respuestaquery5f);

$finesa_cot = [];
$ofertas_cot = [];

while ($rowRespuesta5x = $respuestaquery5x->fetch_assoc()) {
	$ofertas_cot[] = $rowRespuesta5x;
}

while ($rowRespuesta5f = $respuestaquery5f->fetch_assoc()) {
	$finesa_cot[] = $rowRespuesta5f;
}

$resultados = [];
foreach ($ofertas_cot as $oferta) {
	$encontrado = false;
	foreach ($finesa_cot as $finesa) {
		if ($oferta['oferta_finesa'] == $finesa['identityElement']) {
			$resultados[] = array_merge($oferta, $finesa);
			$encontrado = true;
			break;
		}
	}
	if (!$encontrado) {
		$resultados[] = array_merge($oferta, ['cuota_1' => null, 'cuotas' => null, 'identityElement' => null]);
	}
}
// :::::::::::::::::::::::Query para imagen logo::::::::::::::::::::::::::.
$queryLogo = "SELECT urlLogo FROM intermediario  WHERE id_Intermediario = $intermediario";

$valorLogo = $conexion->query($queryLogo);
$valorLogo = mysqli_fetch_array($valorLogo);
$valorLogo = $valorLogo['urlLogo'];

$porciones = explode(".", $valorLogo);

// Consulta las aseguradoras que fueron selecionadas para visualizar en el PDF
$queryAsegSelec = "SELECT DISTINCT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
$valorAsegSelec = $conexion->query($queryAsegSelec);
$asegSelecionada = mysqli_num_rows($valorAsegSelec);

// Consultar cuantas Ofertas fueron selecionadas para visualizarlas en el PDF
$queryPDF = "SELECT * from ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
$valorPDF = $conexion->query($queryPDF);
$ofertasPDF = mysqli_num_rows($valorPDF);

$fechaCotiz = substr($fila['cot_fch_cotizacion'], 0, -9);
$fechaVigencia = date("d-m-Y", strtotime($fechaCotiz));

$placa = $fila["cot_placa"] . " ";
if ($placa == "WWW404 ") {
	$placa = "  0 KMM";
}
$modelo = $fila["cot_modelo"];
$marca = $fila["cot_marca"];
$linea = $fila["cot_linea"];
$fasecolda = $fila["cot_fasecolda"];
$valorA = number_format($fila["cot_valor_asegurado"], 0, '.', '.');
$clase = claseV($fila["cot_clase"]);
$servicio = servise($fila["cot_tip_servicio"]);
$departamento = DptoVehiculo($fila["cot_departamento"]);
$codCiudad = $fila["cot_ciudad"];

/*
* INICIO: Consultar nombre de la ciudad
* Se consulta ciudad dependiendo del departamento seleccionado.
* Ejm. Florencia (Caqueta) tiene el codigo 18001, pero el departamento es 10 segun la BD.
* Este genera el cambio en la sentencia SQL para que lo busque correctamente.
*/

$codDepto = $fila["cot_departamento"];
$queryCiudad = "SELECT `Nombre` FROM `ciudadesbolivar` WHERE `Codigo` = $codCiudad";

if ($codDepto == 10) {
	$queryCiudad = "SELECT `Nombre` FROM `ciudadesbolivar` WHERE `Codigo` = $codCiudad AND `Departamento` = 10";
} else if ($codDepto == 18) {
	$queryCiudad = "SELECT `ciudad` as `Nombre` FROM `ciudades` WHERE `Codigo` = $codCiudad";
}

$respNomCiudad = $conexion->query($queryCiudad);

/*
* FIN: Consultar nombre de la ciudad
*/

$nomCiudad = $respNomCiudad->fetch_assoc();
$explodeCiudad = explode('-', $nomCiudad["Nombre"]);
$ciudad = $explodeCiudad[0];

$rest = substr($placa, 0, 3);
$rest2 = substr($placa, 3, -1);

$identificacion = $fila["cli_num_documento"];
$nombre = $fila["cli_nombre"];
$apellido = $fila["cli_apellidos"];
$edad = calculaedad($fila["cli_fch_nacimiento"]);
$genero = $fila["cli_genero"];

if ($genero == 1) {
	$nomGenero = "Masculino";
} else if ($genero == 2) {
	$nomGenero = "Femenino";
} else {
	$nomGenero = "No aplica";
}

$generarPDF = $_GET['generar_pdf'] ?? '';
$ocultarAsesor = ($generarPDF == 1);
if ($ocultarAsesor) {

	$idUsuario = $fila["id_usuario"];
	$respAsesor = $conexion->query("SELECT usu_nombre, usu_apellido, usu_telefono, usu_email FROM usuarios WHERE id_usuario = $idUsuario");
	$asesor = $respAsesor->fetch_assoc();

	$nomAsesor = '  ' . $asesor["usu_nombre"] . ' ' . $asesor["usu_apellido"];
	$telAsesor = '  ' . $asesor["usu_telefono"];
	$emailAsesor = '  ' . $asesor["usu_email"];
} else {

	$nomAsesor = '   ASESOR DIGITAL';
	$telAsesor = '   NO APLICA';
	$emailAsesor = '   NO APLICA';
}

$real = "";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Seguros Grupo Asistencia');
$pdf->SetTitle('Parrilla de Cotizaciones');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set header and footer fonts
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(12, 12, 12, 12);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(12);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 12);

$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.

$pdf->SetFont('dejavusanscondensed', '', 11);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

//$pdf->Image('../../../vistas/img/logos/imagencotizador.jpg', -5, 0, 0, 92, 'JPG', '', '', true, 160, '', false, false, 0, false, false, false);
//$pdf->Image('../../../vistas/img/logos/cheque.png', 99.5, 159.5, 0, 0, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);

if ($fila['id_tipo_documento'] == 2) {
	$pdf->Image('../../../vistas/img/logos/imagencotizador3.jpg', -5, 0, 0, 92, 'JPG', '', '', true, 200, '', false, false, 0, false, false, false);
} else {
	$pdf->Image('../../../vistas/img/logos/imagencotizador2.jpg', -5, 0, 0, 92, 'JPG', '', '', true, 200, '', false, false, 0, false, false, false);
}

// $id_usuario = $_SESSION['idUsuario'];
$id_usuario_cot = $fila['id_usuario'];
$queryLogo2 = "SELECT usu_logo_pdf FROM usuarios WHERE id_usuario = $id_usuario_cot ";

$valorLogo2 = $conexion->query($queryLogo2);
$valorLogo2 = mysqli_fetch_array($valorLogo2);
$valorLogo2 = $valorLogo2['usu_logo_pdf'];

if ($valorLogo == "undefined") {
	$height = 20;
	$pieces = explode(".", $valorLogo2);
	if ($intermediario == "89" || $intermediario == 89) {
		$urlSGA = "../../../vistas/img/intermediario/SEGUROS GRUPO ASISTENCIA SAS/LogoIntegradoor.jpg";
		$height = 20;
	} else {
		$urlSGA = "../../../vistas/img/intermediario/SEGUROS GRUPO ASISTENCIA SAS/LogoGA.png";
	}

	$width = 40;  // El ancho que deseas en el PDF
	if ($pieces[0] == "") {
		list($imgWidth2, $imgHeight2) = getimagesize($urlSGA);
		$pdf->Image($urlSGA, 10, 13, 0, $height, 'JPG', '', '', false, 160, '', false, false, 0, false, false, false);
	} else if ($pieces[1] == 'png') {
		list($imgWidth, $imgHeight) = getimagesize('../../../' . $valorLogo2);
		$height = ($imgHeight / $imgWidth) * $width;  // Mantener la relación de aspecto
		$pdf->Image('../../../' . $valorLogo2, 10, 13, 0, $height, 'PNG', '', '', false, 160, '', false, false, 0, false, false, false);
	} else {
		//var_dump("entre aqui");
		list($imgWidth, $imgHeight) = getimagesize('../../../' . $valorLogo2);
		$height = ($imgHeight / $imgWidth) * $width;  // Mantener la relación de aspecto
		$pdf->Image('../../../' . $valorLogo2, 10, 13, 0, $height, 'JPG', '', '', false, 160, '', false, false, 0, false, false, false);
	}
} else if ($valorLogo !== "undefined" && !empty($valorLogo2)) {

	$pieces = explode(".", $valorLogo2);

	// Ruta completa de la imagen
	$imagePath = '../../../' . $valorLogo2;

	// Obtener dimensiones de la imagen original
	list($imgWidth, $imgHeight) = getimagesize($imagePath);

	// Dimensiones máximas permitidas para la imagen en el PDF
	$maxWidth = 100; // Ajusta según el espacio disponible
	$maxHeight = 20;

	if ($imgWidth > 1080 && $imgHeight < 428) {
		$maxHeight = 15;
	}
	// Escalar la imagen manteniendo la proporción
	if ($imgWidth > $maxWidth || $imgHeight > $maxHeight) {

		$scaleFactor = min($maxWidth / $imgWidth, $maxHeight / $imgHeight);

		$imgWidth = $imgWidth * $scaleFactor;
		$imgHeight = $imgHeight * $scaleFactor;
	}

	// Coordenadas de posición inicial
	$xPosition = 10; // Ajusta según la posición horizontal deseada
	$yPosition = 13; // Ajusta según la posición vertical deseada

	// Verificar el formato de la imagen y agregarla al PDF
	if ($pieces[1] == 'PNG' || $pieces[1] == 'png') {
		$pdf->Image($imagePath, $xPosition, $yPosition, $imgWidth, $imgHeight, 'PNG',  '', '', false, 300, '', false, false, 0, false, false, false);
	} else {
		$pdf->Image($imagePath, $xPosition, $yPosition, $imgWidth, $imgHeight, 'JPG',  '', '', false, 300, '', false, false, 0, false, false, false);
	}
} else if ($valorLogo != "") {
	$urlSGA = "../../../vistas/img/logosIntermediario/" . $valorLogo;
	$pdf->Image($urlSGA, 8, 13, 0, 20, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);
} else {
	if ($intermediario == "89" || $intermediario == 89) {
		$urlSGA = "../../../vistas/img/intermediario/SEGUROS GRUPO ASISTENCIA SAS/LogoIntegradoor.png";
		$height = 15;
		$pdf->Image($urlSGA, 8, 13, 0, $height, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);
	} else {
		$urlSGA = "../../../vistas/img/intermediario/SEGUROS GRUPO ASISTENCIA SAS/LogoGA.png";
		$pdf->Image($urlSGA, 8, 13, 0, 20, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);
	}
}
// $pdf->Image('../../../vistas/img/logosIntermediario/LogoGA.png', 8, 13, 0, 20, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);

$pdf->Image('../../../vistas/img/logos/cheque.png', 100.5, 170.5, 0, -12, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);

$pdf->SetFont('dejavusanscondensed', 'B', 10);
$pdf->SetXY(158, 3);
$pdf->SetTextColor(104, 104, 104);
$pdf->Cell(25, 6, "No. cotización: " . $identificador);

$pdf->Image(
	'../../../vistas/img/logos/moto.jpg', // Ruta de la imagen
	21.5, // Posición X (izquierda)
	121, // Posición Y (ajustar según sea necesario)
	47, // Ancho (ajustar según sea necesario)
	0, // Altura (0 indica autoajuste proporcional)
	'JPG',
	'',
	'',
	true,
	160,
	'',
	false,
	false,
	0,
	false,
	false,
	false
);

//$pdf->Image('images/img/QUIMERA_BONO_FINAL3.jpg', 0, 130, 0, 117, 'JPG', '', '', false, 140, '', false, false, 0, false, false, false);

$pdf->SetFont('dejavusanscondensed', '', 2);

$pdf->SetFont('dejavusanscondensed', 'B', 12);
$pdf->SetXY(100.7, 19.2);
$pdf->SetTextColor(235, 135, 39);
$pdf->Cell(25, 6, strtoupper($rest) . "" . strtoupper($rest2), 0, 1, '');


$pdf->SetFont('dejavusanscondensed', '', 7);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(18, 53);
$pdf->Cell(35, 6, $modelo, 0, 1, '');

if ($fila['id_tipo_documento'] == 2) {

	$digitoVerif = $fila["digitoVerificacion"];

	$pdf->SetFont('dejavusanscondensed', '', 8.5);
	$pdf->SetXY(141.5, 23.6);
	$pdf->Cell(25, 6, $nombre . " " . $apellido, 0, 1, '');

	$pdf->SetFont('dejavusanscondensed', '', 7);
	$pdf->SetXY(166, 31.5);
	$pdf->Cell(25, 6, $identificacion . "-" . $digitoVerif, 0, 1, '');
} else {

	$pdf->SetFont('dejavusanscondensed', '', 7);
	$pdf->SetXY(166, 31.5);
	$pdf->Cell(25, 6, $identificacion, 0, 1, '');

	$pdf->SetFont('dejavusanscondensed', '', 7.5);
	$pdf->SetXY(155, 23.6);
	$pdf->Cell(25, 6, strtoupper($nombre) . " " . strtoupper($apellido), 0, 1, '');
}

$pdf->SetXY(138, 39.2);
$pdf->Cell(25, 6, $edad . " Años", 0, 1, '');

$pdf->SetXY(169.5, 39.2);
$pdf->Cell(25, 6, $nomGenero, 0, 1, '');

$pdf->SetXY(38, 53);
$pdf->Cell(35, 6, $marca . " " . $linea, 0, 1, '');

$pdf->SetXY(16, 63);
$pdf->Cell(25, 6, $clase, 0, 1, '');

$pdf->SetXY(44, 63);
$pdf->Cell(25, 6, $servicio, 0, 1, '');

$pdf->SetXY(72, 63);
$pdf->Cell(25, 6, $fasecolda, 0, 1, '');

$pdf->SetXY(16, 73);
$pdf->Cell(25, 6, "$ " . $valorA, 0, 1, '');

$pdf->SetXY(69, 73);
$pdf->Cell(25, 6, strtoupper($departamento), 0, 1, '');

$pdf->SetXY(39, 73);
$pdf->Cell(25, 6, $ciudad, 0, 1, '');

$pdf->SetXY(35, 79);
$pdf->Cell(25, 6, "15 DIAS A PARTIR DEL " . $fechaVigencia, 0, 1, '');

$pdf->SetXY(155, 56);
$pdf->Cell(25, 6, strtoupper($nomAsesor), 0, 1, '');

$pdf->SetXY(153, 64.1);
$pdf->Cell(25, 6, strtoupper($emailAsesor), 0, 1, '');

$pdf->SetXY(155, 72);
$pdf->Cell(25, 6, strtoupper($telAsesor), 0, 1, '');

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('dejavusanscondensed', 'BI', 15);
//$pdf->Cell(180, 0, 'Hemos cotizado ' . $asegSelecionada . ' aseguradoras, a continuación', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');
//$pdf->Cell(180, 0, 'te presentamos un comparativo de precios', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');



$htmlpesado1 = '
<style>
.second{

}
.fondo {
    background-color:#0FB2F1;
	font-family:dejavusanscondensedb;
	width: 100%;
	text-align:center;
}
</style>
';

$htmlpesado1 .= '
<table style="width:600px;" class="second" cellpadding="2"  border="0">
<tr>
<td class="fondo">
<div style="font-size:14pt">&nbsp;
</div>
<b style="color:white; font-family:dejavusanscondensedbi; font-size:20px;">SEGURO PARA MOTOCICLETAS</b>
<div style="font-size:5pt">&nbsp;
</div>
</td>
</tr>
</table>
';

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetXY(0, 88);
$pdf->writeHTML($htmlpesado1, true, false, true, false, '');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(46.5, 109.5);
$pdf->Cell(10, 0, 'Hemos   ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'BI', 15);
$pdf->SetTextColor(103, 181, 252);
$pdf->SetXY(90.5, 109.5);
$pdf->Cell(10, 0, ' cotizado ' . $asegSelecionada . ' aseguradora(s), ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(145.5, 109.5);
$pdf->Cell(10, 0, 'a continuación ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(98, 114);
$pdf->Cell(10, 0, 'te presentamos un comparativo de precios', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetAlpha(0.7);

$pdf->SetFont('dejavusanscondensed', '', 8);

$pdf->SetAlpha(1.0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 128);

$pdf->SetFont('dejavusanscondensed', 'B', 9);
$pdf->StartTransform();
$pdf->SetXY(203, 250);
$pdf->Rotate(90);
$pdf->setAlpha(0.5);
$pdf->SetTextColor(104, 104, 104);
$pdf->Cell(25, 6, "Elaborado por Software Integradoor propiedad del proveedor tecnológico Strategico Technologies SAS BIC Nit: 901.542.216-8", 0, 1, '');
$pdf->StopTransform();

$pdf->SetAlpha(0.7);

$pdf->SetFont('dejavusanscondensed', '', 8);

$pdf->SetAlpha(1.0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(0, 107);

$html2 = '
<style>
.td2 {
	text-align: center;
	
  }

.fondo {
    background-color:#EBEBEB;
}

.fondo3 {
    background-color:#F8F8F8;
}

  .puntos {
    border-bottom:1px solid grey;
}

.second2 {
	padding-left: 105px;
}

</style>

<div style="margin-left:40px;" class="second2">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table style="width:350px !important;" class="second" cellpadding="2"  border="0">';
$founded = false;
foreach ($resultados as $resultado) {
	if ($resultado['Aseguradora'] == "HDI (Antes Liberty)") {
		$founded = true;
	}
}

$html2 .= '<tr>';
$cont = 1;
$i = 0;
while ($i < count($resultados)) {
	$fondo_class = ($cont % 2 == 0) ? 'fondo' : 'fondo2';

	switch ($resultados[$i]['Aseguradora']) {
		case 'Axa Colpatria':

			if (count($resultados))
				$productosMap = [
					"Plus con asistencia esencial" => "Plus Asis. Esenc",
					"Plus con asistencia plus" => "Plus Asis. Plus",
					"VIP con asistencia plus" => "VIP Asis. Plus",
					"VIP con asistencia esencial" => "VIP Asis. Esenc",
					"Esencial con asistencia plus" => "Esenc Asis. Plus",
					"Esencial con asistencia esencial" => "Esenc Asis. Esen",
					"Tradicional con asistencia plus" => "Trad. Asis. Plus",
					"Tradicional con asistencia vip" => "Trad. Asis. Plus",
				];
			$productoOriginal = $resultados[$i]['Producto'];
			$AxaProducto = isset($productosMap[$productoOriginal]) ? $productosMap[$productoOriginal] : $productoOriginal;
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style=" font-size: 6.5px; font-family:dejavusanscondensedb;"><center>
			<img style="width:40px;" src="../../../vistas/img/logos/axa.png" alt=""></center>
			<div style="font-size:12">&nbsp;</div>
			<span style="color:#666666;">' . $AxaProducto  . '</span>
			</td>';
			break;
		case 'Seguros del Estado':
		case 'Seguros Estado':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:7.5pt">&nbsp;</div>
			<img style="width:40px; marging-top: 20px;" src="../../../vistas/img/logos/estado.png" alt="">
			<div style="font-size:6pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
		case 'Seguros HDI':
		case 'HDI Seguros':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<img style="width:40px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:3pt">&nbsp;</div>
			<span style="color:#666666;">' . ($resultados[$i]['Producto'] == 'VEHICULO SEGURO HDI PEAU 100%' ? 'HDI Peau 100%' : $resultados[$i]['Producto']) . '</span>
			</td>';
			break;
		case 'SBS Seguros':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:6.5pt">&nbsp;</div>
			<img style="width:40px; padding-top: 0px" src="../../../vistas/img/logos/sbs.png" alt="">
			<div style="font-size:12pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
		case 'Seguros Bolivar':
			$producto = (
				$resultados[$i]['Producto'] == 'ESTANDAR' ? 'Estandar' : ($resultados[$i]['Producto'] == 'CLASICO' ? 'Clasico' : ($resultados[$i]['Producto'] == 'PREMIUM' ? 'Premium' :
					$resultados[$i]['Producto']))
			);
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:7.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/bolivar.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			<span style="color:#666666;">' . $producto . '</span>
			</td>';
			break;
		case 'Seguros Sura':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<img style="width:40px;" src="../../../vistas/img/logos/sura.png" alt="">
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
		case 'Zurich Seguros':
		case 'Zurich':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:7.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:7pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
		case 'Allianz Seguros':
		case 'Allianz':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:6.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:12pt">&nbsp;</div>
			<span style="color:#666666;">' . ($resultados[$i]['Producto'] == 'Autos Esencial + Totales' ? 'Esen.+Totales' : $resultados[$i]['Producto']) . '</span>
			</td>';
			break;
		case 'Liberty Seguros':
		case 'Liberty':
		case 'HDI (Antes Liberty)':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:1pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:10pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
		case 'Seguros Mapfre':
		case 'Mapfre':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style=" font-size: 6.5px; font-family:dejavusanscondensedb;">
			<img style="width:40px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size: 7pt">&nbsp;</div>
			<span style="color:#666666;">' . ($resultados[$i]['Producto'] == 'SUPER TREBOL' ? 'Super Trebol' : $resultados[$i]['Producto']) . '</span>
			</td>';
			break;
		case 'Equidad Seguros':
		case 'Equidad':
			$productosMap = [
				"AUTOPLUS LIGERO" => "Autoplus Ligero",
				"AUTOPLUS BÁSICO" => "Autoplus Básico",
				"AUTOPLUS FULL" => "Autoplus Full"
			];
			$productoOriginal = $resultados[$i]['Producto'];
			$equidadProducto = isset($productosMap[$productoOriginal]) ? $productosMap[$productoOriginal] : $productoOriginal;
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style=" font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size: 7pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/equidad.png" alt="">
			<div style="font-size: 5.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $equidadProducto  . '</span>
			</td>';
			break;
		case 'Previsora Seguros':
			// Mapeo de productos
			$productosMap = [
				"PREVILIVIANOS INDIVIDUAL - " => "Preliv. Ind",
				"AU DEDUCIBLE UNICO LIVIANOS - " => "Au Ded.Unic",
				"LIVIANOS MIA - " => "Livianos MIA"
			];
			// Obtener el producto mapeado o el valor original si no existe en el mapeo
			$productoOriginal = $resultados[$i]['Producto'];
			$previsoraProducto = isset($productosMap[$productoOriginal]) ? $productosMap[$productoOriginal] : $productoOriginal;
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size: 1.2pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size: 5.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $previsoraProducto . '</span>
			</td>';
			break;
		case 'Aseguradora Solidaria':
		case 'Solidaria':
			$productosMap = [
				"PARTICULAR FAMILIAR PLUS" => "Plus",
				"PARTICULAR FAMILIAR PREMIUM" => "Premium",
				"PARTICULAR FAMILIAR ELITE" => "Elite",
				"PARTICULAR FAMILIAR CLASICO" => "Familiar Clasico",
			];
			$productoOriginal = $resultados[$i]['Producto'];
			$solidariaProducto = isset($productosMap[$productoOriginal]) ? $productosMap[$productoOriginal] : $productoOriginal;
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:8pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:6.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $solidariaProducto . '</span>
			</td>';
			break;
		case 'Mundial':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:8pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/mundial.png" alt="">
			<div style="font-size:6.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
	}
	$i++;
	$cont++;
}
$html2 .= '</tr>';


$pdf->SetFont('dejavusanscondensed', '', 12);

$i1 = 0;
if ($rowValidate == 10) {
	$html2 .= '<tr>';
	$cont2 = 1;
	foreach ($resultados as $resultado) {
		if ($cont2 % 2 == 0) {
			$html2 .= '<td style="font-size:7px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($resultado['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		} else {
			$html2 .= '<td style="font-size:7px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo2">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($resultado['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		}
		$cont2 += 1;
	}
} else if ($rowValidate > 10) {
	$html2 .= '<tr>';
	$cont2 = 1;
	foreach ($resultados as $resultado) {
		if ($cont2 % 2 == 0) {
			$html2 .= '<td style="font-size:6px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo">
			<text style="text-align: center;">$' . number_format($resultado['Prima'], 0, ',', '.') . '</text>
			</td>';
		} else {
			$html2 .= '<td style="font-size:6	px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo2">
			<text style="text-align: center;">$' . number_format($resultado['Prima'], 0, ',', '.') . '</text>
			</td>';
		}
		$i1++;
		$cont2 += 1;
	}
} else {

	$html2 .= '<tr>';
	$cont2 = 1;
	foreach ($resultados as $resultado) {
		if ($cont2 % 2 == 0) {
			$html2 .= '<td style="font-size:9px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($resultado['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		} else {
			$html2 .= '<td style="font-size:9px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo2">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($resultado['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		}
		$cont2 += 1;
		$i1++;
	}
}
$html2 .= '</tr>';

$viable = true;

if ($rowValidateF >= 1) {
	$contV = 0;
	foreach ($resultados as $resultado) {
		if ($resultado['cuota_1'] == null) {
			$contV++;
		}
	}
	if ($contV == $rowValidateF) {
		$viable = false;
	}
}

$cont3 = 1;


if ($rowValidateF >= 1) {
	$html2 .= '<tr>';
	foreach ($resultados as $resultado) {

		// var_dump($resultado);
		// var_dump($resultado["Prima"] >= "1000000" ? true: false);
		// var_dump($resultado["cuota_1"] != null ? true: false);
		// var_dump(($resultado['cuota_1'] != null && $resultado['Prima'] >= "1000000") ? true: false);
		$fondo_class = ($cont3 % 2 == 0) ? 'fondo' : 'fondo2';
		$font_size = ($rowValidate > 10) ? 7 : (($rowValidate == 10) ? 8 : 9);

		if ($viable) {
			if ($resultado['cuota_1'] != null && $resultado['Prima'] >= "800000") {
				if ($resultado['Aseguradora'] == "HDI (Antes Liberty)" || $resultado['Aseguradora'] == "Seguros Bolivar") {
					$html2 .= '<td style="font-size:' . ($font_size - 2) . 'px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 ' . $fondo_class . '">
					Pdte. cotizar 
					<br>
					financiación
					</td>';
				} else {
					$html2 .= '<td style="font-size:' . ($font_size - 2) . 'px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 ' . $fondo_class . '">
					$' . number_format($resultado['cuota_1'], 0, ',', '.') . '
					<br>
					(' . $resultado['cuotas'] . ' Cuotas)*
					</td>';
				}
				$cont3++;
			} else if (($resultado['Prima'] < "800000" && $resultado['Aseguradora'] != "HDI (Antes Liberty)") && ($resultado['Prima'] < "800000" && $resultado['Aseguradora'] != "Seguros Bolivar")) {
				$html2 .= '<td style="font-size:' . ($font_size - 2) . 'px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 ' . $fondo_class . '">
				No Aplica
				<br>
				financiación
				</td>';
				$cont3++;
			} else {
				$html2 .= '<td style="font-size:' . ($font_size - 2) . 'px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 ' . $fondo_class . '">
					Pdte. cotizar 
					<br>
					financiación
					</td>';
				$cont3++;
			}
		} else {
			if ($resultado['Aseguradora'] == "HDI (Antes Liberty)" || $resultado['Aseguradora'] == "Seguros Bolivar") {
				$html2 .= '<td style="font-size:' . ($font_size - 2) . 'px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 ' . $fondo_class . '">
                Pdte. cotizar 
                <br>
                financiación
                </td>';
			} else {
				$html2 .= '<td style="font-size:' . ($font_size - 2) . 'px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 ' . $fondo_class . '">
                No aplica
                <br>
                financiación
                </td>';
			}
			$cont3++;
		}
	}
	$html2 .= '</tr>';
}
$html2 .= '</table></div>';

// var_dump($resultados);
// die();

$html3 = '
<style>
  .puntos {
    border-bottom:1px solid grey;
}

.second2 {
	width:100%;
}

.izquierda{
	text-align: left;
}

.fondo {
    background-color:#EBEBEB;
}

.fondo2 {
	background-color:#FFFFFF;
}

.fondo3 {
    background-color:#FFFFFF;
}

</style>
  
<table style="width: 100%;" class="second2" cellpadding="2"  border="0">';

$pdf->SetFont('dejavusanscondensed', '', 8);

// $query6 = "SELECT DISTINCT o.Aseguradora, cf.identityElement
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $valor6 = $conexion->query($query6);
// $fila6 = mysqli_num_rows($valor6);

// if ($fila6 == 0 || $fila6 == false || $fila6 == null) {
// 	mysqli_free_result($valor6);
// 	$query6 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$valor6 = $conexion->query($query6);
// 	$fila6 = mysqli_num_rows($valor6);
// }

$html3 .= '<tr style="width: 100%;" class="izquierda">';
$html3 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '">
<div style="font-size:3pt">&nbsp;</div>
   RESPONSABILIDAD CIVIL EXTRACONTRACTUAL
   <div style="font-size:3pt">&nbsp;</div>
</td>';
$html3 .= '</tr>';

// $query7 = "SELECT DISTINCT o.Aseguradora, cf.identityElement
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery7 = $conexion->query($query7);
// $rowValidate = mysqli_num_rows($respuestaquery7);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery7);
// 	$query7 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery7 = $conexion->query($query7);
// 	$rowValidate = mysqli_num_rows($respuestaquery7);
// }

$html3 .= '<tr class="trborder">';
$valorTabla = (75 / $rowValidate);
$html3 .= '<td class="puntos fondo" style="width:25%;"></td>';

$cont3 = 1;

foreach ($resultados as $resultado) {

	if ($cont3 % 2 == 0) {
		if ($resultado['Aseguradora'] == 'Axa Colpatria') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros del Estado') {
			$html3 .= '<center><td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt=""></center>
			</td></center>';
		} else if ($resultado['Aseguradora'] == 'HDI Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'SBS Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Bolivar') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Sura') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Zurich Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Zurich') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Allianz Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Allianz') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:30px;" src="../../../vistas/img/logos/hdi.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:30px;" src="../../../vistas/img/logos/hdi.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Mapfre') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Mapfre') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Equidad Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Equidad') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Previsora Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Previsora') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Aseguradora Solidaria') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Solidaria') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center>
			</td>';
		}
	} else {
		if ($resultado['Aseguradora'] == 'Axa Colpatria') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros del Estado') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'HDI Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'SBS Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Bolivar') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Sura') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Zurich Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Zurich') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Allianz Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Allianz') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:25px;" src="../../../vistas/img/logos/hdi.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:25px;" src="../../../vistas/img/logos/hdi.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Mapfre') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Mapfre') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Equidad Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Equidad') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Previsora Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Previsora') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Aseguradora Solidaria') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Solidaria') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/mundial.png" alt=""></center></td>';
		}
	}

	$cont3 += 1;
}
$html3 .= '</tr>';
$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:7pt">&nbsp;</div><font size="8">Límite máximo </font><font size="7"> (En millones)</font></td>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA LIMITE MAXIMO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$cont4 = 1;

if ($rowValidate == 10) {
	foreach ($resultados as $resultado) {
		if (is_numeric($resultado['ValorRC'])) {
			$pdfValorRCM = $resultado['ValorRC'] / 1000000;
			$pdfValorRC = '$' . number_format($pdfValorRCM, 0, ',', '.'); // Agregar el símbolo de peso aquí
		} else {
			$pdfValorRC = $resultado['ValorRC'];
		}

		if ($cont4 % 2 == 0) {

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="6" style="text-align: center;"><div style="font-size:2pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="6" style="text-align: center;"><div style="font-size:2pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		}

		$cont4 += 1;
	}
} else if ($rowValidate > 10) {
	foreach ($resultados as $resultado) {
		if (is_numeric($resultado['ValorRC'])) {
			$pdfValorRCM = $resultado['ValorRC'] / 1000000;
			$pdfValorRC = '$' . number_format($pdfValorRCM, 0, ',', '.'); // Agregar el símbolo de peso aquí
		} else {
			$pdfValorRC = $rowRespuesta9['ValorRC'];
		}

		if ($cont4 % 2 == 0) {

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="5" style="text-align: center;"><div style="font-size:2pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="5" style="text-align: center;"><div style="font-size:2pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		}

		$cont4 += 1;
	}
} else {
	foreach ($resultados as $resultado) {

		if (is_numeric($resultado['ValorRC'])) {
			$pdfValorRCM = $resultado['ValorRC'] / 1000000;
			$pdfValorRC = '$' . number_format($pdfValorRCM, 0, ',', '.'); // Agregar el símbolo de peso aquí
		} else {
			$pdfValorRC = $resultado['ValorRC'];
		}

		if ($cont4 % 2 == 0) {

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="7" style="text-align: center;"><div style="font-size:2pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="7" style="text-align: center;"><div style="font-size:2pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		}

		$cont4 += 1;
	}
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA DE DEDUCIBLES
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:4pt">&nbsp;</div><font size="8">Deducible</font></td>';


$cont5 = 0;
foreach ($resultados as $resultado) {
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	$valorRC = $resultado['ValorRC'];
	$perdidaParcial = $resultado['PerdidaParcial'];

	$queryConsultaAsistencia1 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' 
									AND `rce` LIKE '$valorRC'";
	// var_dump($queryConsultaAsistencia1);
	$respuestaqueryAsistencia1 =  $conexion->query($queryConsultaAsistencia1);
	$rowRespuestaAsistencia1 = mysqli_fetch_assoc($respuestaqueryAsistencia1);
	// var_dump($rowRespuestaAsistencia1);
	if ($rowRespuestaAsistencia1 !== null) {
		//echo '<script>console.log('.$cont5.')</script>';
		if ($cont5 % 2 == 0) {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia1['deducible'] . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia1['deducible'] . '</font></center></td>';
		}
	} else {
		if ($cont5 % 2 == 0) {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">Sin deducible</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">Sin deducible</font></center></td>';
		}
	}
	$cont5++;
}
$html3 .= '</tr>';


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS TOTAL DAÑOS
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr style="width: 100%;" class="izquierda">';
$html3 .= '<td style ="width: 100%; background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '"><div style="font-size:3pt">&nbsp;</div>COBERTURAS Y ASISTENCIAS DEL VEHÍCULO Y CONDUCTOR<div style="font-size:3pt">&nbsp;</div></td>';

$html3 .= '</tr>';

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:4pt">&nbsp;</div><font size="8">Pérdida total daños o hurto</font></td>';


// $query10 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.PerdidaTotal, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";;

// $respuestaquery10 = $conexion->query($query10);
// $rowValidate = mysqli_num_rows($respuestaquery10);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery10);
// 	$query10 = "SELECT Aseguradora, PerdidaTotal, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery10 = $conexion->query($query10);
// 	$rowValidate = mysqli_num_rows($respuestaquery10);
// }

$cont6 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($cont6 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaTotal'] . '</font></center></td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaTotal'] . '</font></center></td>';
	}

	$cont6 += 1;
}
$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS PARCIAL DAÑOS
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Pérdida parcial por daño</font></td>';

// $query11 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.PerdidaParcial, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery11 = $conexion->query($query11);
// $rowValidate = mysqli_num_rows($respuestaquery11);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery11);
// 	$query11 = "SELECT PerdidaParcial, Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery11 = $conexion->query($query11);
// 	$rowValidate = mysqli_num_rows($respuestaquery11);
// }
$cont7 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($cont7 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaParcial'] . '</font></center></td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaParcial'] . '</font></center></td>';
	}

	$cont7 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS PARCIAL HURTO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:7pt">&nbsp;</div><font size="8">Pérdida parcial por hurto</font></td>';

$cont8 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($cont8 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;"><div style="font-size:7pt">&nbsp;</div>' . $resultado['PerdidaParcial'] . '</font></center>
		
		</td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;"><div style="font-size:7pt">&nbsp;</div>' . $resultado['PerdidaParcial'] . '</font></center>
		
		</td>';
	}
	$cont8 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS EVENTO NATURAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><font style="font-family:dejavusanscondensedb;" size="8">Cobertura por Eventos de la naturaleza</font></td>';

$cont9 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia5 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia5 =  $conexion->query($queryConsultaAsistencia5);
	$rowRespuestaAsistencia5 = mysqli_fetch_assoc($respuestaqueryAsistencia5);

	if ($cont9 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:7pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:7pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
	}

	$cont9 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA AMPARO PATRIMONIAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$html3 .= '</table>';


$html4 = '
<style>
  .puntos {
    border-bottom:1px solid grey;

.second2 {
	width:100%;
	padding-top: 100px;
	margin-top: 100px;
}

.izquierda{
	text-align: left;
}

.fondo {
    background-color:#EBEBEB;
}

.fondo2 {
	background-color:#FFFFFF;
}

.fondo3 {
    background-color:#FFFFFF;
}

</style>
  
<table style="width: 100%; " class="second2" cellpadding="2"  border="0">';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA GRUA
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
$pdf->SetFont('dejavusanscondensed', '', 8);


$html4 .= '<tr class="trborder">';
$valorTabla = (75 / $rowValidate);
$html4 .= '<td class="puntos fondo" style="width:25%;"></td>';
$cont3f = 1;

foreach ($resultados as $resultado) {
	$pdf->SetFont('dejavusanscondensed', '', 8);
	if ($cont3f % 2 == 0) {
		if ($resultado['Aseguradora'] == 'Axa Colpatria') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center>
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros del Estado') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'SBS Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Bolivar') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Sura') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Zurich Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Zurich') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Allianz Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Allianz') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:30px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:30px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Mapfre') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Mapfre') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Equidad Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Equidad') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Previsora Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Previsora') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Aseguradora Solidaria') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Solidaria') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mundial.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		}
	} else {
		if ($resultado['Aseguradora'] == 'Axa Colpatria') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros del Estado') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'HDI Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'SBS Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Bolivar') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Sura') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Zurich Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Zurich') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Allianz Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Allianz') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Mapfre') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Mapfre') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Equidad Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Equidad') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Previsora Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Previsora') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Aseguradora Solidaria') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Solidaria') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mundial.png" alt=""></td>';
		}
	}

	$cont3f += 1;
}
$html4 .= '</tr>';

$html4 .= '<tr>';
$html4 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:2pt">&nbsp;</div><font size="8">Amparo patrimonial</font></td>';

$cont10 = 1;
foreach ($resultados as $resultado) {
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia6 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia6 =  $conexion->query($queryConsultaAsistencia6);
	$rowRespuestaAsistencia6 = mysqli_fetch_assoc($respuestaqueryAsistencia6);


	if ($cont10 % 2 == 0) {
		if ($rowRespuestaAsistencia6['amparopatrimonial'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:7pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia6['amparopatrimonial'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia6['amparopatrimonial'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:7pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia6['amparopatrimonial'] . '</font></center></td>';
		}
	}


	$cont10 += 1;
}

$html4 .= '</tr>';


$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:10pt">&nbsp;</div><font size="8">Grua varada o accidente</font></td>';

$cont11 = 1;
foreach ($resultados as $resultado) {

	$pdf->SetFont('dejavusanscondensed', '', 8);
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia7 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia7 =  $conexion->query($queryConsultaAsistencia7);
	$rowRespuestaAsistencia7 = mysqli_fetch_assoc($respuestaqueryAsistencia7);

	if ($cont11 % 2 == 0) {
		if ($rowRespuestaAsistencia7['Grua'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:6pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia7['Grua'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia7['Grua'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:6pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia7['Grua'] . '</font></center></td>';
		}
	}
	$cont11 += 1;
}
$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA CARRO TALLER
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:2pt">&nbsp;</div><font size="8"> Asistencia Jurídica Civil y Penal</font></td>';

$cont12 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia8 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia8 =  $conexion->query($queryConsultaAsistencia8);
	$rowRespuestaAsistencia8 = mysqli_fetch_assoc($respuestaqueryAsistencia8);

	if ($cont12 % 2 == 0) {
		if ($rowRespuestaAsistencia8['Asistenciajuridica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:5pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:14pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia8['Asistenciajuridica'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia8['Asistenciajuridica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:5pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:14pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia8['Asistenciajuridica'] . '</font></center></td>';
		}
	}

	$cont12 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// Accidentes personales
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:5pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Indemnización por accidentes</font><span style="font-size: 6pt; text-align: center;"><br>(asegurado, conductor u ocupantes)</span></td>';

$cont13 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia9 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia9 =  $conexion->query($queryConsultaAsistencia9);
	$rowRespuestaAsistencia9 = mysqli_fetch_assoc($respuestaqueryAsistencia9);

	if ($cont13 % 2 == 0) {
		if ($rowRespuestaAsistencia9['Accidentespersonales'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia9['Accidentespersonales'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia9['Accidentespersonales'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia9['Accidentespersonales'] . '</font></center></td>';
		}
	}

	$cont13 += 1;
}

$html4 .= '</tr>';

$html4 .= '<tr>';

if ($rowValidate > 3) {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:3pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asistencia en viajes</font></td>';
} else if ($rowValidate > 1) {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:3pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asistencia en viajes</font></td>';
} else {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:3pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asistencia en viajes</font></td>';
}

$cont19 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia15 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia15 =  $conexion->query($queryConsultaAsistencia15);
	$rowRespuestaAsistencia15 = mysqli_fetch_assoc($respuestaqueryAsistencia15);

	if ($cont19 % 2 == 0) {
		if ($rowRespuestaAsistencia15['AsistenciaViajes'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:5pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia15['AsistenciaViajes'] == '' ? 'No cubre' : $rowRespuestaAsistencia15['AsistenciaViajes']) . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia15['AsistenciaViajes'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:5pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia15['AsistenciaViajes'] == '' ? 'No cubre' : $rowRespuestaAsistencia15['AsistenciaViajes']) . '</font></center></td>';
		}
	}


	$cont19 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA TRANSPORTE PT
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Gastos de Transporte en pérdida total</font></td>';

$cont14 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia10 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia10 =  $conexion->query($queryConsultaAsistencia10);
	$rowRespuestaAsistencia10 = mysqli_fetch_assoc($respuestaqueryAsistencia10);

	if ($cont14 % 2 == 0) {
		if ($rowRespuestaAsistencia10['Gastosdetransportept'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:9pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:9pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia10['Gastosdetransportept'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia10['Gastosdetransportept'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:9pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:9pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia10['Gastosdetransportept'] . '</font></center></td>';
		}
	}


	$cont14 += 1;
}

$html4 .= '</tr>';


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA VEHICULO REEMPLAZO PERDIDA TOTAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="puntos fondo" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Vehículo Sustituto</font></td>';

// $query28 = "SELECT DISTINCT o.Producto, o.Aseguradora
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery28 = $conexion->query($query28);
// $rowValidate = mysqli_num_rows($respuestaquery28);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery28);
// 	$query28 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery28 = $conexion->query($query28);
// 	$rowValidate = mysqli_num_rows($respuestaquery28);
// }
$cont16 = 1;
foreach ($resultados as $resultado) {
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia12 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia12 =  $conexion->query($queryConsultaAsistencia12);
	$rowRespuestaAsistencia12 = mysqli_fetch_assoc($respuestaqueryAsistencia12);


	if ($cont16 % 2 == 0) {
		if ($rowRespuestaAsistencia12['Vehiculoreemplazopt'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:8pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:8pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia12['Vehiculoreemplazopt'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia12['Vehiculoreemplazopt'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:8pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:8pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia12['Vehiculoreemplazopt'] . '</font></center></td>';
		}
	}

	$cont16 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA CONDUCTOR ELEGIDO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Responsabilidad Civil General Familiar</font></td>';

$cont18 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia14 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia14 =  $conexion->query($queryConsultaAsistencia14);
	$rowRespuestaAsistencia14 = mysqli_fetch_assoc($respuestaqueryAsistencia14);

	if ($cont18 % 2 == 0) {
		if ($rowRespuestaAsistencia14['ResponsabilidadCivilGeneralFamiliar'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia14['ResponsabilidadCivilGeneralFamiliar'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia14['ResponsabilidadCivilGeneralFamiliar'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia14['ResponsabilidadCivilGeneralFamiliar'] . '</font></center></td>';
		}
	}





	$cont18 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA Transporte del vehículo recuperado
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:4pt">&nbsp;</div><font size="8">Cobertura de vidrios</font></td>';

$cont19 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia15 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia15 =  $conexion->query($queryConsultaAsistencia15);
	$rowRespuestaAsistencia15 = mysqli_fetch_assoc($respuestaqueryAsistencia15);

	if ($cont19 % 2 == 0) {
		if ($rowRespuestaAsistencia15['CoberturaDeVidrios'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:8pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia15['CoberturaDeVidrios'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia15['CoberturaDeVidrios'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:8pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia15['CoberturaDeVidrios'] . '</font></center></td>';
		}
	}


	$cont19 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Asistencia Odontologica
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class ="fondo puntos" style="width:25%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:4pt">&nbsp;</div><font size="8">Asistencia odontologica</font></td>';

$cont21 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia16 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia16 =  $conexion->query($queryConsultaAsistencia16);
	$rowRespuestaAsistencia16 = mysqli_fetch_assoc($respuestaqueryAsistencia16);

	if ($cont21 % 2 == 0) {
		if ($rowRespuestaAsistencia16['AsistenciaOdontologica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:5pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:7pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia16['AsistenciaOdontologica'] == '' ? 'No cubre' : $rowRespuestaAsistencia16['AsistenciaOdontologica']) . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia16['AsistenciaOdontologica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:5pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:7pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia16['AsistenciaOdontologica'] == '' ? 'No cubre' : $rowRespuestaAsistencia16['AsistenciaOdontologica']) . '</font></center></td>';
		}
	}


	$cont21 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Auxilio de paralización del vehículo/Lucro cesante
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Auxilio de paralización del vehículo / Lucro cesante </font></td>';

$cont22 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia17 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia17 =  $conexion->query($queryConsultaAsistencia17);
	$rowRespuestaAsistencia17 = mysqli_fetch_assoc($respuestaqueryAsistencia17);


	if ($cont22 % 2 == 0) {
		if ($rowRespuestaAsistencia17['LucroCesante'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:7pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia17['LucroCesante'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia17['LucroCesante'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:7pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia17['LucroCesante'] . '</font></center></td>';
		}
	}

	$cont22 += 1;
}

$html4 .= '</tr>';


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// Exequias
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:4pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Exequias</font></td>';

$cont24 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia19 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia19 =  $conexion->query($queryConsultaAsistencia19);
	$rowRespuestaAsistencia19 = mysqli_fetch_assoc($respuestaqueryAsistencia19);

	if ($cont24 % 2 != 0) {
		if ($rowRespuestaAsistencia19['gastosfunerarios'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia19['gastosfunerarios'] == '' ? 'No cubre' : $rowRespuestaAsistencia19['gastosfunerarios']) . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia19['gastosfunerarios'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia19['gastosfunerarios'] == '' ? 'No cubre' : $rowRespuestaAsistencia19['gastosfunerarios']) . '</font></center></td>';
		}
	}


	$cont24 += 1;
}


$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// Asesoria y gestión de trámites de Tránsito
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';

if ($rowValidate > 3) {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:3pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asesoria y gestión de trámites de Tránsito</font></td>';
} else if ($rowValidate > 1) {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:4pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asesoria y gestión de trámites de Tránsito</font></td>';
} else {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:4pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asesoria y gestión de trámites de Tránsito</font></td>';
}

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:6pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] == '' ? 'No cubre' : $rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites']) . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:lpt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] == '' ? 'No cubre' : $rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites']) . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html4 .= '</tr>';

$html4 .= '<tr>';
if ($rowValidate > 3) {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:3pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Gastos médicos</font></td>';
} else if ($rowValidate > 1) {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:4pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Gastos médicos</font></td>';
} else {
	$html4 .= '<td class="fondo puntos" style="width:25%;"><div style="font-size:4pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Gastos médicos</font></td>';
}
// var_dump($resultados);

$cont25 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	$perdidaParcial = $resultado["PerdidaParcial"];

	$queryConsultaAsistencia21xs = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `ppd` LIKE '$perdidaParcial'";

	$respuestaqueryAsistencia21xs =  $conexion->query($queryConsultaAsistencia21xs);
	$rowRespuestaAsistencia21xs = mysqli_fetch_assoc($respuestaqueryAsistencia21xs);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia21xs['GastosMedicos'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia21xs['GastosMedicos'] == '' ? 'No cubre' : $rowRespuestaAsistencia21xs['GastosMedicos']) . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia21xs['GastosMedicos'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . ($rowRespuestaAsistencia21xs['GastosMedicos'] == '' ? 'No cubre' : $rowRespuestaAsistencia21xs['GastosMedicos']) . '</font></center></td>';
		}
	}


	$cont25 += 1;
}
$html4 .= '</tr>';

$html4 .= '</table>';


$html6 = '
<style>
  .puntos {
    border-bottom:1px solid grey;
}

.puntos2 {
    border-right:1px solid grey;
}





.second2 {
	width:100%;
}

.izquierda{
	text-align: left;
}

.redondeotabla{
	border-radius: 5px 30px 45px 60px;
-moz-border-radius: 5px 30px 45px 60px;
-webkit-border-radius: 15px;
}

.fondo {
    background-color:#EBEBEB;
}

</style>';





$query29 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si' and `recomendar` = 'Si'";
$respuestaquery29 =  $conexion->query($query29);
$asegRecomendada = mysqli_num_rows($respuestaquery29);


$query40 = "SELECT * FROM cotizaciones WHERE `id_cotizacion` = $identificador";
$respuestaquery40 =  $conexion->query($query40);
$rowRespuestaAsistencia40 = mysqli_fetch_assoc($respuestaquery40);


$rest = substr($rowRespuestaAsistencia40['cot_fch_cotizacion'], 0, -9);

$contador = 0;

while ($rowRespuesta29 = mysqli_fetch_assoc($respuestaquery29)) {
	$contador++;

	$nombreAseguradora = nombreAseguradora($rowRespuesta29['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta29['Aseguradora'], $rowRespuesta29['Producto']);
	$valorRC = $rowRespuesta29['ValorRC'];
	$perdidaParcial = $rowRespuesta29['PerdidaParcial'];

	$queryConsultaAsistencia29 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' 
									AND `rce` LIKE '$valorRC' AND `ppd` LIKE '$perdidaParcial'";
	$respuestaqueryAsistencia29 =  $conexion->query($queryConsultaAsistencia29);
	$rowRespuestaAsistencia29 = mysqli_fetch_assoc($respuestaqueryAsistencia29);

	$color = $rowRespuestaAsistencia29['color'];

	$html6 .= '<table style="width: 100%;" class="second2" cellpadding="2"  border="0">';

	$html6 .= '<tr>';
	$html6 .= '<td class="redondeotabla" style ="border-radius:50px; width: 100%;  background-color: #88D600' . $color . '; color:white; font-family:dejavusanscondensedb; " colspan="' . ($fila6 + 1) . '"><div style="font-size:3pt">&nbsp;</div>OPCIÓN ' . $contador . '<div style="font-size:3pt">&nbsp;</div></td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';

	if ($rowRespuesta29['Aseguradora'] == 'Axa Colpatria') {
		$html6 .= '<td style="width:40%;text-align: center;"><center>
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:65px;" src="../../../vistas/img/logos/axa.png" alt=""></center></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Seguros del Estado') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/estado.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'HDI Seguros') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'SBS Seguros') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/sbs.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Seguros Bolivar') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/bolivar.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Seguros Sura') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/sura.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Zurich Seguros') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Zurich') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Allianz Seguros') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Allianz') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'HDI (Antes Liberty)') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'HDI (Antes Liberty)') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Seguros Mapfre') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Mapfre') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Equidad Seguros') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Equidad') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Previsora Seguros') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Previsora') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Aseguradora Solidaria') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Solidaria') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
	} else if ($resultado['Aseguradora'] == 'Mundial') {
		$html6 .= '<td style="width:40%;text-align: center">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/mundial.png" alt=""></td>';
	}


	$html6 .= '<td style ="width: 60%; text-align: center;"><div style="font-size:4pt">&nbsp;</div><font style="color:#' . $color . '">Producto: </font>' . $rowRespuesta29['Producto'] . '<font style="color:#' . $color . '"> Fecha Vigencia: </font>' . date("d/m/Y", strtotime($rest)) . ' <br> <font style="color:#' . $color . '; font-size:22px; ">$' . number_format($rowRespuesta29['Prima'], 0, ',', '.') . '</font><div style="font-size:4pt">&nbsp;</div></td>';
	$html6 .= '</tr>';

	$html6 .= '<tr>';
	$html6 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($fila6 + 1) . '">COBERTURA DEL VEHÍCULO</td>';
	$html6 .= '</tr>';

	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Daños a terceros</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Muertes a una persona</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 30%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Muerte a dos personas o más</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 25%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Deducible</font></td>';
	$html6 .= '<td class="fondo" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Asistencia Jurídica</font></td>';
	$html6 .= '</tr>';

	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center;" ><font size="7">' . number_format($rowRespuestaAsistencia29['rce'], 0, ',', '.') . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center;" ><font size="7">' . number_format($rowRespuestaAsistencia29['rce'], 0, ',', '.') . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 30%; text-align: center;" ><font size="7">' . number_format($rowRespuestaAsistencia29['rce'], 0, ',', '.') . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 10%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['deducible'] . '</font></td>';
	if ($rowRespuestaAsistencia29['Asistenciajuridica'] == "Si ampara") {
		$html6 .= '<td class="fondo" style="width:20%;text-align: center;"><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
	} else {
		$html6 .= '<td class="fondo" style="width:20%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia29['Asistenciajuridica'] . '</font></center></td>';
	}
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td>';
	$html6 .= '</td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($fila6 + 1) . '">COBERTURA DEL VEHÍCULO</td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Pérdida total daños</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Pérdida parcial daños</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 30%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Pérdida total hurto</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 10%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Deducible</font></td>';
	$html6 .= '<td class="fondo" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Desastre natural</font></td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center;" ><font size="7">' . $rowRespuesta29['PerdidaTotal'] . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center;" ><font size="7">' . $rowRespuesta29['PerdidaParcial'] . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 30%; text-align: center;" ><font size="7">' . $rowRespuesta29['PerdidaTotal'] . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 10%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['deducible'] . '</font></td>';
	if ($rowRespuestaAsistencia29['eventos'] == "Si ampara") {
		$html6 .= '<td class="fondo" style="width:20%;text-align: center;"><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
	} else {
		$html6 .= '<td class="fondo" style="width:20%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia29['eventos'] . '</font></center></td>';
	}
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td>';
	$html6 .= '</td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($fila6 + 1) . '">ASISTENCIA</td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Gastos de movilización ante pérdidas parciales</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Gastos de movilización ante pérdidas totales</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Vehículo de reemplazo ante pérdidas parciales</font></td>';
	$html6 .= '<td class="fondo" style ="width: 25%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Vehículo de reemplazo ante pérdidas totales</font></td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos puntos2" style ="width: 25%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Gastosdetransportepp'] . '</font></td>';
	$html6 .= '<td class="fondo puntos puntos2" style ="width: 25%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Gastosdetransportept'] . '</font></td>';
	$html6 .= '<td class="fondo puntos puntos2" style ="width: 25%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Vehiculoreemplazopp'] . '</font></td>';
	$html6 .= '<td class="fondo puntos" style ="width: 25%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Vehiculoreemplazopt'] . '</font></td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Asesoría con abogado en caso de accidente</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Revisión antes de viaje</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 30%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Carro-Taller</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Conductor elegido</font></td>';
	$html6 .= '<td class="fondo" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Servicio de grúa ante accidente o varada</font></td>';
	$html6 .= '</tr>';
	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Asistenciajuridica'] . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style="width:20%;text-align: center;"><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 30%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Carrotaller'] . '</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 10%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Conductorelegido'] . '</font></td>';
	$html6 .= '<td class="fondo" style ="width: 20%; text-align: center;" ><font size="7">' . $rowRespuestaAsistencia29['Grua'] . '</font></td>';
	$html6 .= '</tr>';
	$html6 .= '</table>';
	$html6 .= '<p></p>';
}

$html7 = '
<style>
  .puntos {
    border-bottom:1px solid grey;
}

.second2 {
	width:100%;
}

.izquierda{
	text-align: left;
}

</style>';

$html7 .= '<table style="width: 100%;" class="second2" cellpadding="2"  border="0">';

$html7 .= '<tr>';
$html7 .= '<td style ="width: 100%;" colspan="' . ($rowValidate + 1) . '"><font  size="18" style="text-align: center;">Queremos sugerirte <font style="color: #EC8923;">las ' . $asegRecomendada . ' mejores</font> aseguradoras</font></td>';
$html7 .= '</tr>';

$html7 .= '</table>';



$pdf->SetXY(80, 119);
$pdf->writeHTML($html2, true, false, true, false, '');

$pdf->SetFont('', '', 5.5);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(121, 153);
$pdf->Cell(10, 0, '*No se permite financiar a 12 cuotas si el vehiculo tiene prenda y la póliza tiene beneficiario oneros; máximo 11 cuotas.', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');
$pdf->Ln();

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(33.5, 161);
$pdf->Cell(10, 0, 'Si quieres', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'BI', 15);
$pdf->SetTextColor(15, 178, 241);
$pdf->SetXY(98.4, 161);
$pdf->Cell(10, 0, ' comparar las coberturas y asistencias', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(163, 161);
$pdf->Cell(10, 0, 'de todas', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(70, 167);
$pdf->Cell(10, 0, 'las aseguradoras, revisa', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'BI', 15);
$pdf->SetTextColor(235, 135, 39);
$pdf->SetXY(127, 167);
$pdf->Cell(10, 0, ' el siguiente cuadro', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 11);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(101, 173);
$pdf->Cell(10, 0, '(Recuerda que este icono       significa Si Aplica o Si Cubre)', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

//$pdf->Cell(210, 0, 'las aseguradoras, revisa el siguiente cuadro', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');
$pdf->Ln();
// $pdf->SetXY(80, 122);
$pdf->writeHTML($html3, true, false, true, false, '');
//$pdf->AddPage();
//$pdf->writeHTML($html3s, true, false, true, false, '');
$pdf->AddPage();
$pdf->writeHTML($html4, true, false, true, false, '');
$pdf->Ln();
//$pdf->writeHTML($html5, true, false, true, false, '');
//$pdf->Ln();


//$pdf->lastPage();


if ($asegRecomendada > 0) {

	$pdf->AddPage();
	$pdf->writeHTML($html7, true, false, true, false, '');
	$pdf->writeHTML($html6, true, false, true, false, '');
}

$pdf->SetFont('dejavusanscondensed', 'B', 9);
$pdf->StartTransform();
$pdf->SetXY(203, 250);
$pdf->Rotate(90);
$pdf->setAlpha(0.5);
$pdf->SetTextColor(104, 104, 104);
$pdf->Cell(25, 6, "Elaborado por Software Integradoor propiedad del proveedor tecnológico Strategico Technologies SAS BIC Nit: 901.542.216-8", 0, 1, '');
$pdf->StopTransform();

$pdf->SetXY(0, 274);
// $pdf->SetY(-45);
$htmlFooter = '<p style="font-size: 6.2px;">Nota: Esta cotización no constituye una oferta comercial. La misma se expide única y exclusivamente con un propósito informativo sobre los posibles costos del seguro y sus condiciones, los cuales serán susceptibles de modificación hasta tanto no se concreten y determinen las características de los respectivos riesgos.</p>';
$pdf->writeHTML($htmlFooter, true, false, true, false, '');
$pdf->Ln();

// Consulta el servicio del vehiculo segun su codigo
function servise($dato)
{
	$service = "";

	if ($dato == 14) {
		$service = "PARTICULAR";
	} else if ($dato == 11) {
		$service = "PUBLICO";
	} else if ($dato == 12) {
		$service = "PUBLICO";
	}
	return $service;
}
// Consulta la Clase correspondiente al Vehiculo
function claseV($dato)
{
	$service = "";

	if ($dato == "UTILITARIOS DEPORTIVOS") {
		$service = "UTILITARIOS DE.";
	} else {
		$service = $dato;
	}
	return $service;
}


// Calcula la Edad a partir de la Fecha de Nacimiento
function calculaedad($fechaNacimiento)
{

	list($ano, $mes, $dia) = explode("-", $fechaNacimiento);
	$ano_diferencia = date("Y") - $ano;
	$mes_diferencia = date("m") - $mes;
	$dia_diferencia = date("d") - $dia;
	if ($dia_diferencia < 0 || $mes_diferencia < 0)
		$ano_diferencia--;
	return $ano_diferencia;
}


// Consulta el nombre del Departamento segun el Codigo
function DptoVehiculo($codigoDpto)
{

	if ($codigoDpto == 1) {
		$nomDpto = "Amazonas";
	} else if ($codigoDpto == 2) {
		$nomDpto = "Antioquia";
	} else if ($codigoDpto == 3) {
		$nomDpto = "Arauca";
	} else if ($codigoDpto == 4) {
		$nomDpto = "Atlántico";
	} else if ($codigoDpto == 5) {
		$nomDpto = "Barranquilla";
	} else if ($codigoDpto == 6) {
		$nomDpto = "Bogotá";
	} else if ($codigoDpto == 7) {
		$nomDpto = "Bolívar";
	} else if ($codigoDpto == 8) {
		$nomDpto = "Boyacá";
	} else if ($codigoDpto == 9) {
		$nomDpto = "Caldas";
	} else if ($codigoDpto == 10) {
		$nomDpto = "Caquetá";
	} else if ($codigoDpto == 11) {
		$nomDpto = "Casanare";
	} else if ($codigoDpto == 12) {
		$nomDpto = "Cauca";
	} else if ($codigoDpto == 13) {
		$nomDpto = "Cesar";
	} else if ($codigoDpto == 14) {
		$nomDpto = "Chocó";
	} else if ($codigoDpto == 15) {
		$nomDpto = "Córdoba";
	} else if ($codigoDpto == 16) {
		$nomDpto = "Cundinamarca";
	} else if ($codigoDpto == 17) {
		$nomDpto = "Guainía";
	} else if ($codigoDpto == 18) {
		$nomDpto = "La Guajira";
	} else if ($codigoDpto == 19) {
		$nomDpto = "Guaviare";
	} else if ($codigoDpto == 20) {
		$nomDpto = "Huila";
	} else if ($codigoDpto == 21) {
		$nomDpto = "Magdalena";
	} else if ($codigoDpto == 22) {
		$nomDpto = "Meta";
	} else if ($codigoDpto == 23) {
		$nomDpto = "Nariño";
	} else if ($codigoDpto == 24) {
		$nomDpto = "Norte de Santander";
	} else if ($codigoDpto == 25) {
		$nomDpto = "Putumayo";
	} else if ($codigoDpto == 26) {
		$nomDpto = "Quindío";
	} else if ($codigoDpto == 27) {
		$nomDpto = "Risaralda";
	} else if ($codigoDpto == 28) {
		$nomDpto = "San Andrés";
	} else if ($codigoDpto == 29) {
		$nomDpto = "Santander";
	} else if ($codigoDpto == 30) {
		$nomDpto = "Sucre";
	} else if ($codigoDpto == 31) {
		$nomDpto = "Tolima";
	} else if ($codigoDpto == 32) {
		$nomDpto = "Valle del Cauca";
	} else if ($codigoDpto == 33) {
		$nomDpto = "Vaupés";
	} else if ($codigoDpto == 34) {
		$nomDpto = "Vichada";
	} else {
		$nomDpto = "No Disponible";
	}
	return $nomDpto;
}


function nombreAseguradora($data)
{
	$resultado = "";
	if ($data == 'Seguros del Estado') {
		$resultado = "Estado";
	} else if ($data == 'Seguros Estado') {
		$resultado = "Estado";
	} else if ($data == 'Seguros Bolivar') {
		$resultado = "Bolivar";
	} else if ($data == 'Axa Colpatria') {
		$resultado = "Axa Colpatria";
	} else if ($data == 'HDI Seguros') {
		$resultado = "HDI";
	} else if ($data == 'Seguros HDI') {
		$resultado = "HDI";
	} else if ($data == 'SBS Seguros') {
		$resultado = "SBS";
	} else if ($data == 'Allianz Seguros') {
		$resultado = "Allianz";
	} else if ($data == 'Equidad Seguros') {
		$resultado = "Equidad";
	} else if ($data == 'Seguros Mapfre') {
		$resultado = "Mapfre";
	} else if ($data == 'HDI (Antes Liberty)') {
		$resultado = "HDI (Antes Liberty)";
	} else if ($data == 'Aseguradora Solidaria') {
		$resultado = "Solidaria";
	} else if ($data == 'Seguros Sura') {
		$resultado = "SURA";
	} else if ($data == 'Zurich Seguros') {
		$resultado = "Zurich";
	} else if ($data == 'Previsora Seguros') {
		$resultado = "Previsora";
	} else {
		$resultado = $data;
	}
	return $resultado;
}


function productoAseguradora($aseguradora, $producto)
{

	$resultado = "";
	if ($aseguradora == 'Seguros del Estado' && $producto == 'Familiar 1000') {
		$resultado = "Familiar 1000";
	} else if ($aseguradora == 'Seguros del Estado' && $producto == 'Familiar 500') {
		$resultado = "Familiar 500";
	} else if ($aseguradora == 'Seguros Bolivar' && $producto == 'Premium') {
		$resultado = "Premium";
	} else if ($aseguradora == 'Seguros Bolivar' && $producto == 'Estandar') {
		$resultado = "Estandar";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Tradicional') {
		$resultado = "Tradicional";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Plus') {
		$resultado = "Plus";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Esencial') {
		$resultado = "Esencial";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'VIP') {
		$resultado = "VIP";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Motos Plus') {
		$resultado = "Motos Plus";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Motos Esencial') {
		$resultado = "Motos Esencial";
	} else if ($aseguradora == 'HDI Seguros' && $producto == 'Automovil Familiar') {
		$resultado = "Fomula Sicura";
	} else if ($aseguradora == 'SBS Seguros' && $producto == 'Full') {
		$resultado = "Full";
	} else if ($aseguradora == 'SBS Seguros' && $producto == 'Motocicletas') {
		$resultado = "Motocicletas";
	} else if ($aseguradora == 'Seguros Mundial' && $producto == 'Pesados') {
		$resultado = "Pesados";
	} else {
		$resultado = $producto;
	}
	return $resultado;
}


// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$placa_limpia = trim($placa); // Eliminar espacios en blanco al inicio y al final
$filename = $placa_limpia . ' - comparativo de autos.pdf';
$pdf->Output($filename, 'I');
