<?php
session_start();

// Establecer tiempo de espera para sockets
ini_set('default_socket_timeout', 3600); // 1 horas

// Establecer el tiempo máximo de ejecución del script
ini_set('max_execution_time', 3600); // 1 hora

// Establecer el tiempo máximo de entrada
ini_set('max_input_time', 3600); // 1 hora

// Establecer el límite de memoria
ini_set('memory_limit', '756M'); // 512 megabytes

$intermediario = $_SESSION['intermediario'];

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Bogota');

// Incluye la biblioteca TCPDF principal (busca la ruta de instalación).
require_once('tcpdf_include.php');

//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,array(150,  255), true, 'UTF-8', false);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

$identificador = $_GET['cotizacion'];

$user = "grupoasi_cotizautos";
$password = "M1graci0n123";

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


$query2 = "SELECT *	FROM cotizaciones, clientes WHERE cotizaciones.id_cliente = clientes.id_cliente AND `id_cotizacion` = $identificador";
$valor2 = $conexion->query($query2);
$fila = mysqli_fetch_array($valor2);

$query3 = "SELECT o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion 
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$valor3 = $conexion->query($query3);
$fila2 = mysqli_num_rows($valor3);

if ($fila2 == 0 || $fila2 == false || $fila2 == null) {
	//mysqli_free_result($valor3);
	$query3 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$valor3 = $conexion->query($query3);
	$fila2 = mysqli_num_rows($valor3);
}

// :::::::::::::::::::::::Query para imagen logo::::::::::::::::::::::::::.
$queryLogo = "SELECT urlLogo, intermediario_Fech_Vigen FROM intermediario  WHERE id_Intermediario = $intermediario";

$valorLogo = $conexion->query($queryLogo);
$valorLogo = mysqli_fetch_array($valorLogo);
$valorLog = $valorLogo['urlLogo'];

// var_dump($valorLogo);

$porciones = explode(".", $valorLog);

// Consulta las aseguradoras que fueron selecionadas para visualizar en el PDF
$queryAsegSelec = "SELECT DISTINCT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";

$valorAsegSelec = $conexion->query($queryAsegSelec);
$asegSelecionada = mysqli_num_rows($valorAsegSelec);

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


// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.

$pdf->SetFont('dejavusanscondensed', '', 11);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

if ($fila['id_tipo_documento'] == 2) {
	$pdf->Image('../../../vistas/img/logos/imagencotizador3.jpg', -5, 0, 0, 92, 'JPG', '', '', true, 200, '', false, false, 0, false, false, false);
} else {
	$pdf->Image('../../../vistas/img/logos/imagencotizador2.jpg', -5, 0, 0, 92, 'JPG', '', '', true, 200, '', false, false, 0, false, false, false);
}

$id_usuario_cot = $fila['id_usuario'];
$id_usuario = $_SESSION['idUsuario'];
$queryLogo2 = "SELECT usu_logo_pdf FROM usuarios WHERE id_usuario = $id_usuario_cot";

$valorLogo2 = $conexion->query($queryLogo2);
$valorLogo2 = mysqli_fetch_array($valorLogo2);
$valorLogo2 = $valorLogo2['usu_logo_pdf'];

// var_dump($valorLogo);
// var_dump($valorLogo2);

$id_usuario_log = $_SESSION['idUsuario'];

if ($valorLog == "undefined") {
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
	// var_dump("entre aqui");
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
} else if ($valorLog != "") {
	$urlSGA = "../../../vistas/img/logosIntermediario/" . $valorLog;
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
$pdf->Image('../../../vistas/img/logos/cheque.png', 100.5, 150.5, 0, -12, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);

$pdf->SetFont('dejavusanscondensed', 'B', 10);
$pdf->SetXY(158, 3);
$pdf->SetTextColor(104, 104, 104);
$pdf->Cell(25, 6, "No. cotización: " . $identificador);

$pdf->SetFont('dejavusanscondensed', '', 2);

$pdf->SetFont('dejavusanscondensed', 'B', 12);
$pdf->SetXY(97, 19.2);
$pdf->SetTextColor(235, 135, 39);
$pdf->Cell(25, 6, "  " . strtoupper($rest) . "" . strtoupper($rest2), 0, 1, '');


$pdf->SetFont('dejavusanscondensed', '', 7);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(18, 53);
$pdf->Cell(35, 6, $modelo, 0, 1, '');

// $pdf->SetXY(155, 24);
// $pdf->Cell(25, 6, strtoupper($nombre) . " " . strtoupper($apellido), 0, 1, '');

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
$pdf->Cell(25, 6, $valorLogo["intermediario_Fech_Vigen"] . " DIAS A PARTIR DEL " . $fechaVigencia, 0, 1, '');

$pdf->SetXY(130, 59.5);
$pdf->Cell(25, 6, strtoupper($nomAsesor), 0, 1, '');

$pdf->SetXY(130, 67.6);
$pdf->Cell(25, 6, strtoupper($emailAsesor), 0, 1, '');

$pdf->SetXY(130, 75);
$pdf->Cell(25, 6, strtoupper($telAsesor), 0, 1, '');

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('dejavusanscondensed', 'BI', 15);

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(46.5, 89);
$pdf->Cell(10, 0, 'Hemos  ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'BI', 15);
$pdf->SetTextColor(103, 181, 252);
$pdf->SetXY(90.5, 89);
$pdf->Cell(10, 0, ' cotizado ' . $asegSelecionada . ' aseguradora(s), ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(145.5, 89);
$pdf->Cell(10, 0, 'a continuación ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(98, 94);
$pdf->Cell(10, 0, 'te presentamos un comparativo de precios (IVA incluido)', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

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

<div class="second2">
<table class="second" cellpadding="2"  border="0">';

$html2 .= '<tr>';
$cont = 1;
$i = 0;
while ($i < count($resultados)) {

	$fondo_class = ($cont % 2 == 0) ? 'fondo' : 'fondo2';

	switch ($resultados[$i]['Aseguradora']) {
		case 'Axa Colpatria':
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
			<div style="font-size:9.8pt">&nbsp;</div>
			<span style="color:#666666;">' . $AxaProducto  . '</span>
			</td>';
			break;
		case 'Seguros del Estado':
		case 'Seguros Estado':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:5.5pt">&nbsp;</div>
			<img style="width:40px; marging-top: 20px;" src="../../../vistas/img/logos/estado.png" alt="">
			<div style="font-size:11.2pt">&nbsp;</div>
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
			<div style="font-size:10pt">&nbsp;</div>
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
			<div style="font-size:12pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
		case 'Zurich Seguros':
		case 'Zurich':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:7.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:10pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			</td>';
			break;
		case 'Allianz Seguros':
		case 'Allianz':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:6.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:11pt">&nbsp;</div>
			<span style="color:#666666;">' . ($resultados[$i]['Producto'] == 'Autos Esencial + Totales' ? 'Esen.+Totales' : $resultados[$i]['Producto']) . '</span>
			</td>';
			break;
		case 'Liberty Seguros':
		case 'Liberty':
		case 'HDI (Antes Liberty)':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<img style="width:30px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:11.2pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto']  . '</span>
			
			</td>';
			break;
		case 'Seguros Mapfre':
		case 'Mapfre':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style=" font-size: 6.5px; font-family:dejavusanscondensedb;">
			<img style="width:40px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size: 11pt">&nbsp;</div>
			<span style="color:#666666;">' . ($resultados[$i]['Producto'] == 'SUPER TREBOL' ? 'Super Trebol' : $resultados[$i]['Producto']) . '</span>
			</td>';
			break;
		case 'Equidad Seguros':
		case 'Equidad':
			$productosMap = [
				"AUTOPLUS LIGERO" => "Autoplus Ligero",
				"AUTOPLUS BÁSICO" => "Autoplus Básico",
				"AUTOPLUS FULL" => "Autoplus Full",
				"AUTOPLUS RCE" => "Autoplus RCE"
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
			<img style="width:40px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size: 9.5pt">&nbsp;</div>
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
			<div style="font-size:5.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:11.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $solidariaProducto . '</span>
			</td>';
			break;
		// bloque agregado Javier
		case 'Qualitas':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:5.5pt">&nbsp;</div>
			<img style="width:50px;" src="../../../vistas/img/logos/logo-qualitas-secundario.png" alt="">
			<div style="font-size:9.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto'] . '</span>
			</td>';
			break;
		// fin bloque Javier
		case 'Mundial':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:3.5pt">&nbsp;</div>
			<img style="width:50px;" src="../../../vistas/img/logos/mundial.png" alt="">
			<div style="font-size:6.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $resultados[$i]['Producto'] . '</span>
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
		$fondo_class = ($cont3 % 2 == 0) ? 'fondo' : 'fondo2';
		$font_size = ($rowValidate > 10) ? 7 : (($rowValidate == 10) ? 8 : 9);

		if ($viable) {
			if ($resultado['cuota_1'] != null) {
				if ($resultado['Aseguradora'] == "HDI (Antes Liberty)" || $resultado['Aseguradora'] == "Seguros Bolivar" || $resultado['Aseguradora'] == "Mapfre" || $resultado['Aseguradora'] == "Seguros Mapfre") {
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
			} else if ($resultado['Prima'] < "800000") {
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
			if ($resultado['Aseguradora'] == "HDI (Antes Liberty)" || $resultado['Aseguradora'] == "Seguros Bolivar" || $resultado['Aseguradora'] == "Mapfre" || $resultado['Aseguradora'] == "Seguros Mapfre") {
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

$html2 .= '</table>';

if ($rowValidateF > 0) {
	
	$html2 .= '<table cellpadding="0" cellspacing="0">
					<tr>
						<td width="1" height="10"></td> <!-- margen simulado -->
						<td width="550"><span style="font-size: 6.2px; color: grey;">*No se permite financiar a 12 cuotas si el vehículo tiene prenda y la póliza beneficiario oneroso; máximo 11 cuotas.</span></td>
					</tr>
				</table>';
}

$html2 .= '</div>';

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
// $rowValidate = mysqli_num_rows($valor6);

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
$valorTabla = (90 / $rowValidate);
$html3 .= '<td class="puntos fondo" style="width:10%;"></td>';

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
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center>
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
		} else if ($resultado['Aseguradora'] == 'Qualitas') {
			$html3 .= '<center><td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:45px;" src="../../../vistas/img/logos/logo-qualitas-secundario.png" alt=""></center>
			</td></center>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html3 .= '<center><td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:45px;" src="../../../vistas/img/logos/mundial.png" alt=""></center>
			</td></center>';
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
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center></td>';
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
		} else if ($resultado['Aseguradora'] == 'Qualitas') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:45px;" src="../../../vistas/img/logos/logo-qualitas-secundario.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/mundial.png" alt=""></center></td>';
		}
	}

	$cont3 += 1;
}
$html3 .= '</tr>';
$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Límite máximo </font><font size="7"> (En millones)</font></td>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA LIMITE MAXIMO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// $query9 = "SELECT o.Aseguradora, o.ValorRC
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery9 = $conexion->query($query9);
// $rowValidate = mysqli_num_rows($respuestaquery9);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery9);
// 	$query9 = "SELECT Aseguradora, ValorRC FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery9 = $conexion->query($query9);
// 	$rowValidate = mysqli_num_rows($respuestaquery9);
// }

// $valorlimiterow = mysqli_num_rows($respuestaquery9);
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

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="6" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="6" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		}

		$cont4 += 1;
	}
} else if ($rowValidate > 10) {
	foreach ($resultados as $resultado) {
		if (is_numeric($resultado['ValorRC'])) {
			$pdfValorRCM = $resultado['ValorRC'] / 1000000;
			$pdfValorRC = '$' . number_format($pdfValorRCM, 0, ',', '.'); // Agregar el símbolo de peso aquí
		} else {
			$pdfValorRC = $resultado['ValorRC'];
		}

		if ($cont4 % 2 == 0) {

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="5" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="5" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
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

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="7" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="7" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		}

		$cont4 += 1;
	}
}

$html3 .= '</tr>';





//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA DE DEDUCIBLES
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:4pt">&nbsp;</div><font size="8">Deducible</font></td>';


// $query8 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.ValorRC, o.PerdidaParcial, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery8 = $conexion->query($query8);
// $rowValidate = mysqli_num_rows($respuestaquery8);
// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery8);
// 	$query8 = "SELECT ValorRC, PerdidaParcial, Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery8 = $conexion->query($query8);
// 	$rowValidate = mysqli_num_rows($respuestaquery8);
// }

$cont5 = 1;

foreach ($resultados as $resultado) {
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	$valorRC = $resultado['ValorRC'];
	$perdidaParcial = $resultado['PerdidaParcial'];

	$queryConsultaAsistencia1 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' 
									AND `rce` LIKE '$valorRC'";
	$respuestaqueryAsistencia1 =  $conexion->query($queryConsultaAsistencia1);
	$rowRespuestaAsistencia1 = mysqli_fetch_assoc($respuestaqueryAsistencia1);
	if ($rowRespuestaAsistencia1 !== null) {
		if ($cont5 % 2 == 0) {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia1['deducible'] . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia1['deducible'] . '</font></center></td>';
		}
	} else {
		if ($cont5 % 2 == 0) {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">Sin deducible</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:4pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">Sin deducible</font></center></td>';
		}
	}

	$cont5 += 1;
}
$html3 .= '</tr>';


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS TOTAL DAÑOS
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr style="width: 100%;" class="izquierda">';
$html3 .= '<td style ="width: 100%; background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '"><div style="font-size:3pt">&nbsp;</div>COBERTURAS Y ASISTENCIAS DEL VEHÍCULO Y CONDUCTOR<div style="font-size:3pt">&nbsp;</div></td>';

$html3 .= '</tr>';

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:4pt">&nbsp;</div><font size="8">Pérdida total daños o hurto</font></td>';


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

$ptotales = "Deducible: 30% min 3 SMMLV";

$cont6 = 1;
foreach ($resultados as $resultado) {

	$existLinea = false;

	if (strpos($linea, "SPARK") !== false || strpos($linea, "spark") !== false || strpos($linea, "Spark") !== false) {
		$existLinea = true;
	}

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($cont6 % 2 == 0) {
		if ($existLinea && $nombreAseguradora == "Zurich") {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $ptotales . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaTotal'] . '</font></center></td>';
		}
	} else {
		if ($existLinea && $nombreAseguradora == "Zurich") {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $ptotales . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaTotal'] . '</font></center></td>';
		}
	}

	$cont6 += 1;
}
$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS PARCIAL DAÑOS
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Pérdida parcial por daño</font></td>';
$pp = "";
$cont7 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($cont7 % 2 == 0) {
		if ($nombreProducto == "MEDIUM" && $nombreAseguradora == "Zurich") {
			$pp = "Tope 50% Vr. Asegurado Deducible: 10% min. 1 SMMLV";
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $pp . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaParcial'] . '</font></center></td>';
		}
	} else {
		if ($nombreProducto == "MEDIUM" && $nombreAseguradora == "Zurich") {
			$pp = "Tope 50% Vr. Asegurado Deducible: 10% min. 1 SMMLV";
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $pp . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaParcial'] . '</font></center></td>';
		}
	}

	$cont7 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS PARCIAL HURTO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Pérdida parcial por hurto</font></td>';

$cont8 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($cont8 % 2 == 0) {
		if ($nombreProducto == "MEDIUM" && $nombreAseguradora == "Zurich") {
			$pph = "Tope 70% Vr. Asegurado Deducible: 10% min. 1 SMMLV";
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $pph . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaParcial'] . '</font></center></td>';
		}
	} else {
		if ($nombreProducto == "MEDIUM" && $nombreAseguradora == "Zurich") {
			$pph = "Tope 70% Vr. Asegurado Deducible: 10% min. 1 SMMLV";
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $pph . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $resultado['PerdidaParcial'] . '</font></center></td>';
		}
	}

	$cont8 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS EVENTO NATURAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font style="font-family:dejavusanscondensedb;" size="8">Cobertura por Eventos de la naturaleza</font></td>';

// $query13 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery13 = $conexion->query($query13);
// $rowValidate = mysqli_num_rows($respuestaquery13);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery13);
// 	$query13 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery13 = $conexion->query($query13);
// 	$rowValidate = mysqli_num_rows($respuestaquery13);
// }
$cont9 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	$valorCondicion = "";

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia5 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia5 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia5 =  $conexion->query($queryConsultaAsistencia5);
	$rowRespuestaAsistencia5 = mysqli_fetch_assoc($respuestaqueryAsistencia5);

	if ($nombreAseguradora != "Previsora") {
		if ($cont9 % 2 == 0) {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
		}
	} else {
		$queryConsultaAsistencia5Eve = "SELECT Eventos, Placa FROM ofertas WHERE `id_cotizacion` = $identificador AND `aseguradora` LIKE 'Previsora Seguros' AND `producto` LIKE '$nombreProducto'";
		$respuestaqueryAsistencia5Eve =  $conexion->query($queryConsultaAsistencia5Eve);
		$rowRespuestaAsistencia5Eve = mysqli_fetch_assoc($respuestaqueryAsistencia5Eve);

		if (isset($rowRespuestaAsistencia5Eve['Eventos']) && $rowRespuestaAsistencia5Eve['Eventos'] != null) {
			if ($cont9 % 2 == 0) {
				$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5Eve['Eventos'] . '</font></center></td>';
			} else {
				$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5Eve['Eventos'] . '</font></center></td>';
			}
		} else {
			if ($cont9 % 2 == 0) {
				$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
			} else {
				$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
			}
		}
	}


	$cont9 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA AMPARO PATRIMONIAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:2pt">&nbsp;</div><font size="8">Amparo patrimonial</font></td>';

// $query14 = "SELECT DISTINCT o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery14 = $conexion->query($query14);
// $rowValidate = mysqli_num_rows($respuestaquery14);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery14);
// 	$query14 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery14 = $conexion->query($query14);
// 	$rowValidate = mysqli_num_rows($respuestaquery14);
// }

$cont10 = 1;
foreach ($resultados as $resultado) {
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	$valorCondicion = "";

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia6 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia6 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
	$respuestaqueryAsistencia6 =  $conexion->query($queryConsultaAsistencia6);
	$rowRespuestaAsistencia6 = mysqli_fetch_assoc($respuestaqueryAsistencia6);


	if ($cont10 % 2 == 0) {
		if ($rowRespuestaAsistencia6['amparopatrimonial'] == "Si ampara") {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:7pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia6['amparopatrimonial'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia6['amparopatrimonial'] == "Si ampara") {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:7pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia6['amparopatrimonial'] . '</font></center></td>';
		}
	}


	$cont10 += 1;
}

$html3 .= '</tr>';

$html3 .= '</table>';


$html4 = '
<style>
  .puntos {
    border-bottom:1px solid grey;

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

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA GRUA
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
$pdf->SetFont('dejavusanscondensed', '', 8);

// $query6 = "SELECT o.Aseguradora
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

$html4 .= "<div style=font-size:4pt>&nbsp;</div>";

// $html4 .= '<tr style="width: 100%;" class="izquierda">';
// $html4 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '">
// <div style="font-size:3pt; margin-top:30px">&nbsp;</div>
//    ASISTENCIAS
//    <div style="font-size:3pt">&nbsp;</div>
// </td>';
// $html4 .= '</tr>';

// $query7 = "SELECT DISTINCT o.Aseguradora,
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery7 = $conexion->query($query6);
// $rowValidate = mysqli_num_rows($respuestaquery7);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery7);
// 	$query7 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery7 = $conexion->query($query7);
// 	$rowValidate = mysqli_num_rows($respuestaquery7);
// }

$html4 .= '<tr class="trborder">';
$valorTabla = (90 / $rowValidate);
$html4 .= '<td class="puntos fondo" style="width:10%;"></td>';
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
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
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
		} else if ($resultado['Aseguradora'] == 'Qualitas') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/logo-qualitas-secundario.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/mundial.png" alt="">
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
		} else if ($resultado['Aseguradora'] == 'Qualitas') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/logo-qualitas-secundario.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/mundial.png" alt=""></td>';
		}
	}

	$cont3f += 1;
}
$html4 .= '</tr>';

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:2pt">&nbsp;</div><font size="8">Grua varada o accidente</font></td>';
// $query15 = "SELECT DISTINCT o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery15 = $conexion->query($query15);
// $rowValidate = mysqli_num_rows($respuestaquery15);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery15);
// 	$query15 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery15 = $conexion->query($query15);
// 	$rowValidate = mysqli_num_rows($respuestaquery7);
// }

$cont11 = 1;
foreach ($resultados as $resultado) {

	$pdf->SetFont('dejavusanscondensed', '', 8);
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia7 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia7 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
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
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Carrotaller</font></td>';

// $query16 = "SELECT DISTINCT o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery16 = $conexion->query($query16);
// $rowValidate = mysqli_num_rows($respuestaquery16);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery16);
// 	$query16 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery16 = $conexion->query($query16);
// 	$rowValidate = mysqli_num_rows($respuestaquery7);
// }
$cont12 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia8 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia8 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia8 =  $conexion->query($queryConsultaAsistencia8);
	$rowRespuestaAsistencia8 = mysqli_fetch_assoc($respuestaqueryAsistencia8);

	if ($cont12 % 2 == 0) {
		if ($rowRespuestaAsistencia8['Carrotaller'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia8['Carrotaller'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia8['Carrotaller'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia8['Carrotaller'] . '</font></center></td>';
		}
	}

	$cont12 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA ASISTENCIA JURIDICA
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Asistencia juridica civil y penal </font></td>';

// $query17 = "SELECT DISTINCT o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery17 = $conexion->query($query17);
// $rowValidate = mysqli_num_rows($respuestaquery17);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery17);
// 	$query17 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery17 = $conexion->query($query17);
// 	$rowValidate = mysqli_num_rows($respuestaquery17);
// }
$cont13 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia9 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia9 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia9 =  $conexion->query($queryConsultaAsistencia9);
	$rowRespuestaAsistencia9 = mysqli_fetch_assoc($respuestaqueryAsistencia9);

	if ($cont13 % 2 == 0) {
		if ($rowRespuestaAsistencia9['Asistenciajuridica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:8pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia9['Asistenciajuridica'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia9['Asistenciajuridica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:8pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><div style="font-size:8pt">&nbsp;</div><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia9['Asistenciajuridica'] . '</font></center></td>';
		}
	}

	$cont13 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA TRANSPORTE PT
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Gastos de Transporte en pérdida total</font></td>';

// $query27 = "SELECT DISTINCT o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery27 = $conexion->query($query27);
// $rowValidate = mysqli_num_rows($respuestaquery27);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery27);
// 	$query27 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery27 = $conexion->query($query27);
// 	$rowValidate = mysqli_num_rows($respuestaquery27);
// }
$cont14 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia10 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia10 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

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

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Gastos de transporte en perdida parcial</font></td>';

// CONSULTA TRANSPORTE PP
// $query18 = "SELECT DISTINCT o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// // Ejecutar la consulta y verificar si se ejecuta correctamente
// $respuestaquery18 = $conexion->query($query18);
// if (!$respuestaquery18) {
// 	die('Error en la consulta: ' . $conexion->error);
// }

// $rowValidate = mysqli_num_rows($respuestaquery18);
// if ($rowValidate == 0) {
// 	mysqli_free_result($respuestaquery18);
// 	$query18 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery18 = $conexion->query($query18);
// 	if (!$respuestaquery18) {
// 		die('Error en la consulta secundaria: ' . $conexion->error);
// 	}
// 	$rowValidate = mysqli_num_rows($respuestaquery18);
// }

$cont15 = 1;
foreach ($resultados as $resultado) {
	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia11 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia11 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
	$respuestaqueryAsistencia11 = $conexion->query($queryConsultaAsistencia11);
	if (!$respuestaqueryAsistencia11) {
		die('Error en la consulta de asistencia: ' . $conexion->error);
	}
	$rowRespuestaAsistencia11 = mysqli_fetch_assoc($respuestaqueryAsistencia11);

	$fondo_class = ($cont15 % 2 == 0) ? 'fondo' : 'fondo2';
	if ($rowRespuestaAsistencia11['Gastosdetransportepp'] == "Si ampara") {
		$html4 .= '<td class="puntos ' . $fondo_class . '" style="width:' . $valorTabla . '%; text-align: center;">
        <div style="font-size:4pt">&nbsp;</div>
        <img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
	} else {
		$html4 .= '<td class="puntos ' . $fondo_class . '" style="width:' . $valorTabla . '%;">
        <div style="font-size:4pt">&nbsp;</div>
        <font size="7" style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia11['Gastosdetransportepp'] . '</font></center></td>';
	}
	$cont15 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA VEHICULO REEMPLAZO PERDIDA TOTAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Vehículo de reemplazo en pérdida total</font></td>';

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
	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia12 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia12 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
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
//CONSULTA VEHICULO REEMPLAZO PERDIDA PARCIAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class ="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Vehículo de reemplazo en pérdida parcial</font></td>';

// $query19 = "SELECT DISTINCT o.Producto, o.Aseguradora
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery19 = $conexion->query($query19);
// $rowValidate = mysqli_num_rows($respuestaquery19);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery19);
// 	$query19 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery19 = $conexion->query($query19);
// 	$rowValidate = mysqli_num_rows($respuestaquery19);
// }

$cont17 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia13 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia13 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
	$respuestaqueryAsistencia13 =  $conexion->query($queryConsultaAsistencia13);
	$rowRespuestaAsistencia13 = mysqli_fetch_assoc($respuestaqueryAsistencia13);


	if ($cont17 % 2 == 0) {
		if ($rowRespuestaAsistencia13['Vehiculoreemplazopp'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia13['Vehiculoreemplazopp'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia13['Vehiculoreemplazopp'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center;font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia13['Vehiculoreemplazopp'] . '</font></center></td>';
		}
	}

	$cont17 += 1;
}

$html4 .= '</tr>';


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA CONDUCTOR ELEGIDO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Conductor elegido</font></td>';

// $query20 = "SELECT DISTINCT o.Producto, o.Aseguradora
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery20 = $conexion->query($query20);
// $rowValidate = mysqli_num_rows($respuestaquery20);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery20);
// 	$query20 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery20 = $conexion->query($query20);
// 	$rowValidate = mysqli_num_rows($respuestaquery20);
// }
$cont18 = 1;

foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia14 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia14 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
	$respuestaqueryAsistencia14 =  $conexion->query($queryConsultaAsistencia14);
	$rowRespuestaAsistencia14 = mysqli_fetch_assoc($respuestaqueryAsistencia14);


	if ($cont18 % 2 == 0) {
		if ($rowRespuestaAsistencia14['Conductorelegido'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia14['Conductorelegido'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia14['Conductorelegido'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia14['Conductorelegido'] . '</font></center></td>';
		}
	}





	$cont18 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA Transporte del vehículo recuperado
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Transporte vehículo recuperado</font></td>';

$cont19 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);
	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia15 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia15 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
	$respuestaqueryAsistencia15 =  $conexion->query($queryConsultaAsistencia15);
	$rowRespuestaAsistencia15 = mysqli_fetch_assoc($respuestaqueryAsistencia15);

	if ($cont19 % 2 == 0) {
		if ($rowRespuestaAsistencia15['Transportevehiculorecuperdo'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:8pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:3pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia15['Transportevehiculorecuperdo'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia15['Transportevehiculorecuperdo'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:8pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:3pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia15['Transportevehiculorecuperdo'] . '</font></center></td>';
		}
	}


	$cont19 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA Transporte DE PASAJEROS POR ACCIDENTE
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class ="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Transporte pasajeros por accidente</font></td>';

$cont20 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia16 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia16 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia16 =  $conexion->query($queryConsultaAsistencia16);
	$rowRespuestaAsistencia16 = mysqli_fetch_assoc($respuestaqueryAsistencia16);

	if ($cont20 % 2 == 0) {
		if ($rowRespuestaAsistencia16['Transportepasajerosaccidente'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:12pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:14pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia16['Transportepasajerosaccidente'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia16['Transportepasajerosaccidente'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:12pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:14pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia16['Transportepasajerosaccidente'] . '</font></center></td>';
		}
	}


	$cont20 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//TRANSPORTE DE PASAJEROS POR VARADA
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Transporte pasajeros por varada</font></td>';

$cont22 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia17 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia17 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia17 =  $conexion->query($queryConsultaAsistencia17);
	$rowRespuestaAsistencia17 = mysqli_fetch_assoc($respuestaqueryAsistencia17);


	if ($cont22 % 2 == 0) {
		if ($rowRespuestaAsistencia17['Transportepasajerosvarada'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia17['Transportepasajerosvarada'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia17['Transportepasajerosvarada'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia17['Transportepasajerosvarada'] . '</font></center></td>';
		}
	}

	$cont22 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//ACCIDENTES PERSONALES
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%;"><div style="font-size:5pt">&nbsp;</div><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Indemnización por accidentes</font><span style="font-size: 6pt; text-align: center;"></span></td>';

$cont23 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia18 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia18 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia18 =  $conexion->query($queryConsultaAsistencia18);
	$rowRespuestaAsistencia18 = mysqli_fetch_assoc($respuestaqueryAsistencia18);

	if ($cont23 % 2 != 0) {
		if ($rowRespuestaAsistencia18['Accidentespersonales'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia18['Accidentespersonales'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia18['Accidentespersonales'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:6pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia18['Accidentespersonales'] . '</font></center></td>';
		}
	}


	$cont23 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//LLANTAS ESTALLADAS
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Llantas estalladas</font></td>';

$cont24 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia19 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia19 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}
	$respuestaqueryAsistencia19 =  $conexion->query($queryConsultaAsistencia19);
	$rowRespuestaAsistencia19 = mysqli_fetch_assoc($respuestaqueryAsistencia19);

	if ($cont24 % 2 != 0) {
		if ($rowRespuestaAsistencia19['Llantasestalladas'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia19['Llantasestalladas'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia19['Llantasestalladas'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia19['Llantasestalladas'] . '</font></center></td>';
		}
	}


	$cont24 += 1;
}


$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//PERDIDA DE LLAVES
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Pérdida de llaves</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['Perdidallaves'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['Perdidallaves'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['Perdidallaves'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['Perdidallaves'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Responsabilidad Civil General Familiar
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Responsabilidad Civil General Familiar</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['ResponsabilidadCivilGeneralFamiliar'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['ResponsabilidadCivilGeneralFamiliar'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['ResponsabilidadCivilGeneralFamiliar'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['ResponsabilidadCivilGeneralFamiliar'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Cobertura de vidrios Ampliada
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Cobertura de vidrios Ampliada</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['CoberturaDeVidrios'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['CoberturaDeVidrios'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['CoberturaDeVidrios'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['CoberturaDeVidrios'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Asistencia odontológica autos
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asistencia odontológica autos</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['AsistenciaOdontologica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['AsistenciaOdontologica'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['AsistenciaOdontologica'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['AsistenciaOdontologica'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html4 .= '</tr>';
$html4 .= '</table>';

$html8 = '
<style>
  .puntos {
    border-bottom:1px solid grey;

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

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA GRUA
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
$pdf->SetFont('dejavusanscondensed', '', 8);

// $query6 = "SELECT o.Aseguradora
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

$html8 .= "<div style=font-size:4pt>&nbsp;</div>";

// $html8 .= '<tr style="width: 100%;" class="izquierda">';
// $html8 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '">
// <div style="font-size:3pt; margin-top:30px">&nbsp;</div>
//    ASISTENCIAS
//    <div style="font-size:3pt">&nbsp;</div>
// </td>';
// $html8 .= '</tr>';

// $query7 = "SELECT DISTINCT o.Aseguradora,
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $respuestaquery7 = $conexion->query($query6);
// $rowValidate = mysqli_num_rows($respuestaquery7);

// if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
// 	mysqli_free_result($respuestaquery7);
// 	$query7 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$respuestaquery7 = $conexion->query($query7);
// 	$rowValidate = mysqli_num_rows($respuestaquery7);
// }

$html8 .= '<tr class="trborder">';
$valorTabla = (90 / $rowValidate);
$html8 .= '<td class="puntos fondo" style="width:10%;"></td>';
$cont3f = 1;

foreach ($resultados as $resultado) {
	$pdf->SetFont('dejavusanscondensed', '', 8);
	if ($cont3f % 2 == 0) {
		if ($resultado['Aseguradora'] == 'Axa Colpatria') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center>
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros del Estado') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI Seguros') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'SBS Seguros') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Bolivar') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Sura') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Zurich Seguros') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Zurich') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Allianz Seguros') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Allianz') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Mapfre') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Mapfre') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Equidad Seguros') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Equidad') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Previsora Seguros') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Previsora') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Aseguradora Solidaria') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Solidaria') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Qualitas') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/logo-qualitas-secundario.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/mundial.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		}
	} else {
		if ($resultado['Aseguradora'] == 'Axa Colpatria') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros del Estado') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'HDI Seguros') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'SBS Seguros') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Bolivar') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Sura') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Zurich Seguros') {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Zurich') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Allianz Seguros') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Allianz') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'HDI (Antes Liberty)') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Seguros Mapfre') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Mapfre') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Equidad Seguros') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Equidad') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Previsora Seguros') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Previsora') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Aseguradora Solidaria') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Solidaria') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Qualitas') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/logo-qualitas-secundario.png" alt=""></td>';
		} else if ($resultado['Aseguradora'] == 'Mundial') {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:45px;" src="../../../vistas/img/logos/mundial.png" alt=""></td>';
		}
	}

	$cont3f += 1;
}
$html8 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Exequias
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html8 .= '<tr>';
$html8 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Exequias</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['exequias'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['exequias'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['exequias'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['exequias'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html8 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Asesoría y Gestión de trámites de Tránsito
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html8 .= '<tr>';
$html8 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Asesoría y Gestión de trámites de Tránsito</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['Asesoria_Gestion_de_tramites'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html8 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Pequeños Accesorios
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html8 .= '<tr>';
$html8 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Pequeños Accesorios</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['Pequeniosaccesorios'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['Pequeniosaccesorios'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['Pequeniosaccesorios'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['Pequeniosaccesorios'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html8 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Lucro Cesante
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html8 .= '<tr>';
$html8 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Lucro Cesante</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['LucroCesante'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['LucroCesante'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['LucroCesante'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['LucroCesante'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html8 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//Gastos Médicos
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html8 .= '<tr>';
$html8 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Gastos Médicos</font></td>';

$cont25 = 1;
foreach ($resultados as $resultado) {

	$nombreAseguradora = nombreAseguradora($resultado['Aseguradora']);
	$nombreProducto = productoAseguradora($resultado['Aseguradora'], $resultado['Producto']);

	if ($nombreProducto == "Basico + PT") {
		$valorAsegurado = $fila["cot_valor_asegurado"];
		if ($valorAsegurado <= 150000000) {
			$valorCondicion = "1 SMMLV";
		} else {
			$valorCondicion = "Deducible: 10% MIN 1 SMMLV";
		}
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' AND `eventos` LIKE '$valorCondicion'";
	} else {
		$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	}

	$respuestaqueryAsistencia2O =  $conexion->query($queryConsultaAsistencia20);
	$rowRespuestaAsistencia20 = mysqli_fetch_assoc($respuestaqueryAsistencia2O);

	if ($cont25 % 2 != 0) {
		if ($rowRespuestaAsistencia20['GastosMedicos'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['GastosMedicos'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia20['GastosMedicos'] == "Si ampara") {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:4pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html8 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia20['GastosMedicos'] . '</font></center></td>';
		}
	}


	$cont25 += 1;
}

$html8 .= '</tr>';

$html8 .= '</table>';


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





$query29 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si' and `recomendar` = 'Si'";
$respuestaquery29 =  $conexion->query($query29);
$asegRecomendada = mysqli_num_rows($respuestaquery29);


$query40 = "SELECT * FROM cotizaciones WHERE `id_cotizacion` = $identificador";
$respuestaquery40 =  $conexion->query($query40);
$rowRespuestaAsistencia40 = mysqli_fetch_assoc($respuestaquery40);


$rest = substr($rowRespuestaAsistencia40['cot_fch_cotizacion'], 0, -9);

$contador = 0;

while ($rowRespuesta29 = mysqli_fetch_assoc($respuestaquery29)) {
	$contador++;
	//var_dump($rowRespuesta29);
	$nombreAseguradora = nombreAseguradora($rowRespuesta29['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta29['Aseguradora'], $rowRespuesta29['Producto']);
	$valorRC = $rowRespuesta29['ValorRC'];
	$perdidaParcial = $rowRespuesta29['PerdidaParcial'];

	$queryConsultaAsistencia29 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' 
									AND `rce` LIKE '$valorRC' AND `ppd` LIKE '$perdidaParcial'";

	//var_dump($queryConsultaAsistencia29);

	$respuestaqueryAsistencia29 =  $conexion->query($queryConsultaAsistencia29);
	$rowRespuestaAsistencia29 = mysqli_fetch_assoc($respuestaqueryAsistencia29);

	//var_dump($rowRespuestaAsistencia29);
	$color = $rowRespuestaAsistencia29['color'];

	$html6 .= '<table style="width: 100%;" class="second2" cellpadding="2"  border="0">';

	$html6 .= '<tr>';
	$html6 .= '<td class="redondeotabla" style ="border-radius:50px; width: 100%;  background-color: #88D600' . $color . '; color:white; font-family:dejavusanscondensedb; " colspan="' . ($rowValidate + 1) . '"><div style="font-size:3pt">&nbsp;</div>OPCIÓN ' . $contador . '<div style="font-size:3pt">&nbsp;</div></td>';
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
	}


	$html6 .= '<td style ="width: 60%; text-align: center;"><div style="font-size:4pt">&nbsp;</div><font style="color:#' . $color . '">Producto: </font>' . $rowRespuesta29['Producto'] . '<font style="color:#' . $color . '"> Fecha Vigencia: </font>' . date("d/m/Y", strtotime($rest)) . ' <br> <font style="color:#' . $color . '; font-size:22px; ">$' . number_format($rowRespuesta29['Prima'], 0, ',', '.') . '</font><div style="font-size:4pt">&nbsp;</div></td>';
	$html6 .= '</tr>';

	$html6 .= '<tr>';
	$html6 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '">COBERTURA DEL VEHÍCULO</td>';
	$html6 .= '</tr>';

	$html6 .= '<tr>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Daños a terceros</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 20%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Muertes a una persona</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 30%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Muerte a dos personas o más</font></td>';
	$html6 .= '<td class="fondo puntos2" style ="width: 10%; text-align: center; font-family:dejavusanscondensedb;" ><font size="8">Deducible</font></td>';
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
	$html6 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '">COBERTURA DEL VEHÍCULO</td>';
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
	$html6 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($rowValidate + 1) . '">ASISTENCIA</td>';
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
// die();
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

$pdf->SetXY(80, 98);
$pdf->writeHTML($html2, true, false, true, false, '');
$pdf->Ln();


// if ($rowValidateF > 0) {
// 	$pdf->SetFont('', '', 6.2);
// 	$pdf->SetTextColor(104, 104, 104);
// 	$pdf->SetXY(67, 132);
// 	$pdf->Cell(10, 0, '*No se permite financiar a 12 cuotas si el vehículo tiene prenda y la póliza beneficiario oneroso; máximo 11 cuotas.', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');
// 	$pdf->Ln();
// }

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(33.5, 139);
$pdf->Cell(10, 0, 'Si quieres', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'BI', 15);
$pdf->SetTextColor(15, 178, 241);
$pdf->SetXY(98.4, 139);
$pdf->Cell(10, 0, ' comparar las coberturas y asistencias', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(163, 139);
$pdf->Cell(10, 0, 'de todas', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(70, 146);
$pdf->Cell(10, 0, 'las aseguradoras, revisa', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'BI', 15);
$pdf->SetTextColor(235, 135, 39);
$pdf->SetXY(127, 146);
$pdf->Cell(10, 0, ' el siguiente cuadro', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 11);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(101, 153);
$pdf->Cell(10, 0, '(Recuerda que este icono       significa Si Aplica o Si Cubre)', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');


//$pdf->Cell(210, 0, 'las aseguradoras, revisa el siguiente cuadro', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');
$pdf->Ln();

$pdf->writeHTML($html3, true, false, true, false, '');

$pdf->Ln();
$pdf->writeHTML($html4, true, false, true, false, '');

$pdf->Ln();
$pdf->writeHTML($html8, true, false, true, false, '');

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
$pdf->Ln();

$pdf->SetXY(0, 262);
$htmlFooter = '<p style="font-size: 6.2px;">Nota: Esta cotización no constituye una oferta comercial. La misma se expide única y exclusivamente con un propósito informativo sobre los posibles costos del seguro y sus condiciones, los cuales serán susceptibles de modificación hasta tanto no se concreten y determinen las características de los respectivos riesgos. No se permite financiar a 12 cuotas si el vehículo tiene prenda y la póliza beneficiario oneroso; máximo 11 cuotas.</p>';
$pdf->writeHTML($htmlFooter, true, false, true, true, '');
$pdf->Ln();

$pdf->SetXY(0, 272);
$htmlFooter = '<p style="font-size: 6.2px; color: red">Importante: Algunas líneas de vehículos en las compañías Allianz, Previsora, Mundial y HDI requieren la instalación de un dispositivo de georreferenciación tipo Cazador. El incumplimiento de esta obligación (garantía) puede conllevar la aplicación de exclusiones a diferentes amparos, la ampliación de los deducibles a cargo del asegurado o incluso la aseguradora no será responsable de indemnizar al asegurado. Consulta con tu asesor si tu vehículo necesita este dispositivo antes de tomar tu póliza.</p>';
$pdf->writeHTML($htmlFooter, true, false, true, true, '');
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
	} else if ($codigoDpto == 16) {
		$nomDpto = "Cundinamarca";
	} else if ($codigoDpto == 15) {
		$nomDpto = "Córdoba";
	} else if ($codigoDpto == 14) {
		$nomDpto = "Choco";
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
	} else if ($data == 'Seguros Bolivar') {
		$resultado = "Bolivar";
	} else if ($data == 'Axa Colpatria') {
		$resultado = "Axa Colpatria";
	} else if ($data == 'HDI Seguros') {
		$resultado = "HDI";
	} else if ($data == 'SBS Seguros') {
		$resultado = "SBS";
	} else if ($data == 'Allianz Seguros') {
		$resultado = "Allianz";
	} else if ($data == 'Equidad Seguros') {
		$resultado = "Equidad";
	} else if ($data == 'Equidad') {
		$resultado = "Equidad";
	} else if ($data == 'Seguros Mapfre') {
		$resultado = "Mapfre";
	} else if ($data == 'Mapfre') {
		$resultado = "Mapfre";
	} else if ($data == 'HDI (Antes Liberty)') {
		$resultado = "HDI (Antes Liberty)";
	} else if ($data == 'Aseguradora Solidaria') {
		$resultado = "Solidaria";
	} else if ($data == 'Seguros Sura') {
		$resultado = "SURA";
	} else if ($data == 'Zurich Seguros') {
		$resultado = "Zurich";
	} else if ($data == 'Zurich') {
		$resultado = "Zurich";
	} else if ($data == 'Previsora Seguros') {
		$resultado = "Previsora";
	} else if ($data == 'Solidaria') {
		$resultado = "Solidaria";
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
	} else if ($aseguradora == 'Seguros del Estado' && $producto == 'Estado -Automovil Familiar') {
		$resultado = "Familiar 500";
	} else if ($aseguradora == 'Seguros del Estado' && $producto == 'Estado -Automovil Familiar Full') {
		$resultado = "Familiar 1000";
	} else if ($aseguradora == 'Seguros Bolivar' && $producto == 'Premium') {
		$resultado = "Premium";
	} else if ($aseguradora == 'Seguros Bolivar' && $producto == 'Estandar') {
		$resultado = "Estandar";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Tradicional') {
		$resultado = "Tradicional";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Tradicional8') {
		$resultado = "Tradicional";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Tradicional9') {
		$resultado = "Tradicional";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'Plus') {
		$resultado = "Plus";
	} else if ($aseguradora == 'Axa Colpatria' && $producto == 'PLUS1') {
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
	} else if ($aseguradora == 'Mapfre' && $producto == 'SUPER TREBOL') {
		$resultado = "SUPER TREBOL";
	} else if ($aseguradora == 'SBS Seguros' && $producto == 'Motocicletas') {
		$resultado = "Motocicletas";
	} else if ($aseguradora == 'Solidaria' && $producto == 'PARTICULAR FAMILIAR PLUS') {
		$resultado = "Plus";
	} else if ($aseguradora == 'Solidaria' && $producto == 'PARTICULAR FAMILIAR PREMIUM') {
		$resultado = "Premium";
	} else if ($aseguradora == 'Solidaria' && $producto == 'PARTICULAR FAMILIAR ELITE') {
		$resultado = "Elite";
	} else if ($aseguradora == 'Solidaria' && $producto == 'PARTICULAR FAMILIAR CLASICO') {
		$resultado = "Familiar Clasico";
	} else if ($aseguradora == 'Zurich' && $producto == 'FULL') {
		$resultado = "FULL";
	} else if ($aseguradora == 'Zurich' && $producto == 'MEDIUM') {
		$resultado = "MEDIUM";
	} else if ($aseguradora == 'Zurich' && $producto == 'BASIC') {
		$resultado = "BASIC";
	} else if ($aseguradora == 'Previsora' && $producto == 'LIVIANOS MIA - ') {
		$resultado = "Livianos MIA";
	} else if ($aseguradora == 'Previsora' && $producto == 'AU DEDUCIBLE UNICO LIVIANOS - ') {
		$resultado = "Au Ded.Unic";
	} else if ($aseguradora == 'Previsora' && $producto == 'PREVILIVIANOS INDIVIDUAL - ') {
		$resultado = "Prelivianos Individual";
	} else {
		$resultado = $producto;
	}
	return $resultado;
}

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$placa_limpia = trim($placa); // Eliminar espacios en blanco al inicio y al final
$filename = $placa_limpia . ' - comparativo de autos.pdf';
$pdf->Output($filename, 'I');
