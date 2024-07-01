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

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Incluye la biblioteca TCPDF principal (busca la ruta de instalación).
require_once('tcpdf_include.php');

//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,array(150,  255), true, 'UTF-8', false);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

$identificador = $_GET['cotizacion'];

$server = "localhost";
$user = "grupoasi_cotizautos";
$password = "M1graci0n123"; //poner tu propia contraseña, si tienes una.
$bd = "grupoasi_cotizautos";

$conexion = mysqli_connect($server, $user, $password, $bd);
if (!$conexion) {
	die('Error de Conexión: ' . mysqli_connect_errno());
}
$conexion->set_charset("utf8");


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

if($fila2 == 0 || $fila2 == false || $fila2 == null){
	//mysqli_free_result($valor3);
	$query3 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$valor3 = $conexion->query($query3);
	$fila2 = mysqli_num_rows($valor3);
}

// :::::::::::::::::::::::Query para imagen logo::::::::::::::::::::::::::.
$queryLogo = "SELECT urlLogo FROM intermediario  WHERE id_Intermediario = $intermediario";

$valorLogo = $conexion->query($queryLogo);
$valorLogo = mysqli_fetch_array($valorLogo);
$valorLogo = $valorLogo['urlLogo'];

$porciones = explode(".", $valorLogo);

// $query3s = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.Producto
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";

// $valor3s = $conexion->query($query3s);
// $fila2 = mysqli_num_rows($valor3s);

// if ($fila2 == 0 || $fila2 == false || $fila2 == null) {
// 	mysqli_free_result($valor3s);
// 	$query3s = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
// 	$valor3s = $conexion->query($query3s);
// 	$fila2 = mysqli_num_rows($valor3s);
// }


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

// Consulta la Ciudad a partir del codigo
$respNomCiudad = $conexion->query("SELECT `Nombre` FROM `ciudadesbolivar` WHERE `Codigo` = $codCiudad");
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

$pdf->Image('../../../vistas/img/logos/imagencotizador2.jpg', -5, 0, 0, 92, 'JPG', '', '', true, 200, '', false, false, 0, false, false, false);

if ($porciones[1] == 'png') {

	$pdf->Image('../../../vistas/img/logosIntermediario/' . $valorLogo, 8, 13, 0, 20, 'PNG', '', '', true, 160, '', false, false, 0, false, false, false);
} else {
	$pdf->Image('../../../vistas/img/logosIntermediario/' . $valorLogo, 8, 13, 0, 20, 'JPG', '', '', true, 160, '', false, false, 0, false, false, false);
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

$pdf->SetXY(155, 24);
$pdf->Cell(25, 6, strtoupper($nombre) . " " . strtoupper($apellido), 0, 1, '');

$pdf->SetXY(166, 31.5);
$pdf->Cell(25, 6, $identificacion, 0, 1, '');

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
$pdf->Cell(25, 6, "8 DIAS A PARTIR DEL " . $fechaVigencia, 0, 1, '');

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
$pdf->Cell(10, 0, 'Hemos ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'BI', 15);
$pdf->SetTextColor(103, 181, 252);
$pdf->SetXY(90.5, 89);
$pdf->Cell(10, 0, 'cotizado ' . $asegSelecionada . ' aseguradoras,', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(145.5, 89);
$pdf->Cell(10, 0, 'a continuación ', 0, $ln = 0, 'C', 0, '', 0, false, 'C', 'C');

$pdf->SetFont('dejavusanscondensed', 'I', 15);
$pdf->SetTextColor(104, 104, 104);
$pdf->SetXY(98, 97);
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

$query4 = "SELECT cf.identityElement, o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery4 = $conexion->query($query4);
$rowValidate = mysqli_num_rows($respuestaquery4);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	$query4 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery4 = $conexion->query($query4);
	$rowValidate = mysqli_num_rows($respuestaquery4);
}

$cont = 1;
$html2 .= '<tr>';
while ($rowRespuesta4 = mysqli_fetch_assoc($respuestaquery4)) {

	$fondo_class = ($cont % 2 == 0) ? 'fondo' : 'fondo2';

	switch ($rowRespuesta4['Aseguradora']) {
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
			$productoOriginal = $rowRespuesta4['Producto'];
			$AxaProducto = isset($productosMap[$productoOriginal]) ? $productosMap[$productoOriginal] : $productoOriginal;
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style=" font-size: 6.5px; font-family:dejavusanscondensedb;"><center>
			<img style="width:40px;" src="../../../vistas/img/logos/axa.png" alt=""></center>
			<div style="font-size:6.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $AxaProducto  . '</span>
			</td>';
			break;
		case 'Seguros del Estado':
		case 'Seguros Estado':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:7.5pt">&nbsp;</div>
			<img style="width:40px; marging-top: 20px;" src="../../../vistas/img/logos/estado.png" alt="">
			<div style="font-size:6	pt">&nbsp;</div>
			<span style="color:#666666;">' . $rowRespuesta4['Producto']  . '</span>
			</td>';
			break;
		case 'Seguros HDI':
		case 'HDI Seguros':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<img style="width:40px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:t">&nbsp;</div>
			<span style="color:#666666;">' . ($rowRespuesta4['Producto'] == 'VEHICULO SEGURO HDI PEAU 100%' ? 'HDI Peau 100%' : $rowRespuesta4['Producto']) . '</span>
			</td>';
			break;
		case 'SBS Seguros':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:6.5pt">&nbsp;</div>
			<img style="width:40px; padding-top: 0px" src="../../../vistas/img/logos/sbs.png" alt="">
			<div style="font-size:6.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $rowRespuesta4['Producto']  . '</span>
			</td>';
			break;
		case 'Seguros Bolivar':
			$producto = (
				$rowRespuesta4['Producto'] == 'ESTANDAR' ? 'Estandar' : ($rowRespuesta4['Producto'] == 'CLASICO' ? 'Clasico' : ($rowRespuesta4['Producto'] == 'PREMIUM' ? 'Premium' :
					$rowRespuesta4['Producto']))
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
			<span style="color:#666666;">' . $rowRespuesta4['Producto']  . '</span>
			</td>';
			break;
		case 'Zurich Seguros':
		case 'Zurich':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:7.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:7pt">&nbsp;</div>
			<span style="color:#666666;">' . $rowRespuesta4['Producto']  . '</span>
			</td>';
			break;
		case 'Allianz Seguros':
		case 'Allianz':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:6.5pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:7pt">&nbsp;</div>
			<span style="color:#666666;">' . ($rowRespuesta4['Producto'] == 'Autos Esencial + Totales' ? 'Esen.+Totales' : $rowRespuesta4['Producto']) . '</span>
			</td>';
			break;
		case 'Liberty Seguros':
		case 'Liberty':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="  font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:1pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/liberty.png" alt="">
			<div style="font-size:5.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $rowRespuesta4['Producto']  . '</span>
			
			</td>';
			break;
		case 'Seguros Mapfre':
		case 'Mapfre':
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style=" font-size: 6.5px; font-family:dejavusanscondensedb;">
			<img style="width:40px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size: 7pt">&nbsp;</div>
			<span style="color:#666666;">' . ($rowRespuesta4['Producto'] == 'SUPER TREBOL' ? 'Super Trebol' : $rowRespuesta4['Producto']) . '</span>
			</td>';
			break;
		case 'Equidad Seguros':
		case 'Equidad':
			$productosMap = [
				"AUTOPLUS LIGERO" => "Autoplus Ligero",
				"AUTOPLUS BÁSICO" => "Autoplus Básico",
				"AUTOPLUS FULL" => "Autoplus Full"
			];
			$productoOriginal = $rowRespuesta4['Producto'];
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
			$productoOriginal = $rowRespuesta4['Producto'];
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
			$productoOriginal = $rowRespuesta4['Producto'];
			$solidariaProducto = isset($productosMap[$productoOriginal]) ? $productosMap[$productoOriginal] : $productoOriginal;
			$html2 .= '<td class="puntos td2 ' . $fondo_class . '" style="font-size: 6.5px; font-family:dejavusanscondensedb;">
			<div style="font-size:8pt">&nbsp;</div>
			<img style="width:40px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:6.5pt">&nbsp;</div>
			<span style="color:#666666;">' . $solidariaProducto . '</span>
			</td>';
			break;
	}

	$cont++;
}
$html2 .= '</tr>';


$pdf->SetFont('dejavusanscondensed', '', 12);

$query5 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.Prima
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery5 = $conexion->query($query5);
$rowValidate = mysqli_num_rows($respuestaquery5);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery5);
	$query5 = "SELECT Aseguradora, Prima FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery5 = $conexion->query($query5);
	$rowValidate = mysqli_num_rows($respuestaquery5);
}

// $valor = mysqli_num_rows($respuestaquery5);
if ($rowValidate == 10) {
	$html2 .= '<tr>';
	$cont2 = 1;
	while ($rowRespuesta5 = mysqli_fetch_assoc($respuestaquery5)) {
		if ($cont2 % 2 == 0) {
			$html2 .= '<td style="font-size:7px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($rowRespuesta5['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		} else {
			$html2 .= '<td style="font-size:7px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo2">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($rowRespuesta5['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		}
		$cont2 += 1;
	}
} else if ($rowValidate > 10) {
	$html2 .= '<tr>';
	$cont2 = 1;
	while ($rowRespuesta5 = mysqli_fetch_assoc($respuestaquery5)) {
		if ($cont2 % 2 == 0) {
			$html2 .= '<td style="font-size:6px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo">
			<text style="text-align: center;">$' . number_format($rowRespuesta5['Prima'], 0, ',', '.') . '</text>
			</td>';
		} else {
			$html2 .= '<td style="font-size:6	px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo2">
			<text style="text-align: center;">$' . number_format($rowRespuesta5['Prima'], 0, ',', '.') . '</text>
			</td>';
		}
		$cont2 += 1;
	}
} else {

	$html2 .= '<tr>';
	$cont2 = 1;
	while ($rowRespuesta5 = mysqli_fetch_assoc($respuestaquery5)) {
		if ($cont2 % 2 == 0) {
			$html2 .= '<td style="font-size:9px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($rowRespuesta5['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		} else {
			$html2 .= '<td style="font-size:9px; color:#666666; font-family:dejavusanscondensedb; text-align: center;" class="puntos td2 fondo2">
			<div style="font-size:2pt">&nbsp;</div>
			<text style="text-align: center;">$' . number_format($rowRespuesta5['Prima'], 0, ',', '.') . '</text>
			<div style="font-size:2pt">&nbsp;</div>
			</td>';
		}
		$cont2 += 1;
	}
}
$html2 .= '</tr>';

// Cuotas de Finesa en cada cotizacion
// $query5f = "SELECT DISTINCT cf.identityElement, cf.cuotas, cf.cuota_1
// FROM cotizaciones_finesa cf 
// INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
// WHERE o.seleccionar = 'Si' 
// AND cf.identityElement = o.oferta_finesa
// AND cf.id_cotizacion = $identificador";
// // $query5f = "SELECT o.*, cf.identityElement, cf.cuota_1, cf.cuotas
// // FROM cotizaciones_finesa cf 
// // INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion 
// // INNER JOIN cotizaciones c ON o.id_cotizacion = cf.id_cotizacion 
// // WHERE o.seleccionar = 'Si' 
// // AND CONVERT(cf.identityElement USING utf8mb3) = CONVERT(o.oferta_finesa USING utf8mb3) 
// // AND cf.id_cotizacion = $identificador 
// // GROUP BY cf.identityElement";

$query5f = "SELECT DISTINCT o.Aseguradora, o.Producto, cf.cuota_1, cf.cuotas
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery5f = $conexion->query($query5f);
$rowValidate = mysqli_num_rows($respuestaquery5f);

if ($respuestaquery5f === false) {
	echo "Error en la consulta: " . $conexion->error;
} else {
	$valor_f = mysqli_num_rows($respuestaquery5f);
	$cont3 = 1;
	if($valor_f > 0){
		$html2 .= '<tr>';
		while ($rowRespuesta5f = mysqli_fetch_assoc($respuestaquery5f)) {
			$fondo_class = ($cont3 % 2 == 0) ? 'fondo' : 'fondo2';
			$font_size = ($valor_f > 10) ? 7 : (($valor_f == 10) ? 8 : 9);
	
			$html2 .= '<td style="font-size:' . ($font_size - 2) . 'px; color:#666666; font-family:dejavusanscondensedb;" class="puntos td2 ' . $fondo_class . '">
			$ ' . number_format($rowRespuesta5f['cuota_1'], 0, ',', '.') . '
			<br>
			(' . $rowRespuesta5f['cuotas'] . ' Cuotas)
			</td>';
			$cont3++;
		}
		$html2 .= '</tr>';
	} 
}
$html2 .= '</table></div>';

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

$query6 = "SELECT DISTINCT o.Aseguradora, cf.identityElement
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$valor6 = $conexion->query($query6);
$fila6 = mysqli_num_rows($valor6);

if ($fila6 == 0 || $fila6 == false || $fila6 == null) {
	mysqli_free_result($valor6);
	$query6 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$valor6 = $conexion->query($query6);
	$fila6 = mysqli_num_rows($valor6);
}

$html3 .= '<tr style="width: 100%;" class="izquierda">';
$html3 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($fila6 + 1) . '">
<div style="font-size:3pt">&nbsp;</div>
   RESPONSABILIDAD CIVIL EXTRACONTRACTUAL
   <div style="font-size:3pt">&nbsp;</div>
</td>';
$html3 .= '</tr>';

$query7 = "SELECT DISTINCT o.Aseguradora, cf.identityElement
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery7 = $conexion->query($query7);
$rowValidate = mysqli_num_rows($respuestaquery7);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery7);
	$query7 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery7 = $conexion->query($query7);
	$rowValidate = mysqli_num_rows($respuestaquery7);
}

$html3 .= '<tr class="trborder">';
$valorTabla = (90 / $fila6);
$html3 .= '<td class="puntos fondo" style="width:10%;"></td>';

$cont3 = 1;

while ($rowRespuesta7 = mysqli_fetch_assoc($respuestaquery7)) {

	if ($cont3 % 2 == 0) {
		if ($rowRespuesta7['Aseguradora'] == 'Axa Colpatria') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros del Estado') {
			$html3 .= '<center><td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt=""></center>
			</td></center>';
		} else if ($rowRespuesta7['Aseguradora'] == 'HDI Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'SBS Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Bolivar') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Sura') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Mapfre') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Mapfre') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:0.5pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Aseguradora Solidaria') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Solidaria') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center>
			</td>';
		}
	} else {
		if ($rowRespuesta7['Aseguradora'] == 'Axa Colpatria') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros del Estado') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'HDI Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'SBS Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Bolivar') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Sura') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich Seguros') {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Mapfre') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Mapfre') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:4pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora Seguros') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:1pt">&nbsp;</div>
			<center><img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Aseguradora Solidaria') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Solidaria') {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<center><img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></center></td>';
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
$query9 = "SELECT o.Aseguradora, o.ValorRC
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery9 = $conexion->query($query9);
$rowValidate = mysqli_num_rows($respuestaquery9);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery9);
	$query9 = "SELECT Aseguradora, ValorRC FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery9 = $conexion->query($query9);
	$rowValidate = mysqli_num_rows($respuestaquery9);
}

$valorlimiterow = mysqli_num_rows($respuestaquery9);
$cont4 = 1;

if ($valorlimiterow == 10) {
	while ($rowRespuesta9 = mysqli_fetch_assoc($respuestaquery9)) {
		if (is_numeric($rowRespuesta9['ValorRC'])) {
			$pdfValorRCM = $rowRespuesta9['ValorRC'] / 1000000;
			$pdfValorRC = '$' . number_format($pdfValorRCM, 0, ',', '.'); // Agregar el símbolo de peso aquí
		} else {
			$pdfValorRC = $rowRespuesta9['ValorRC'];
		}

		if ($cont4 % 2 == 0) {

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="6" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="6" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		}

		$cont4 += 1;
	}
} else if ($valorlimiterow > 10) {
	while ($rowRespuesta9 = mysqli_fetch_assoc($respuestaquery9)) {
		if (is_numeric($rowRespuesta9['ValorRC'])) {
			$pdfValorRCM = $rowRespuesta9['ValorRC'] / 1000000;
			$pdfValorRC = '$' . number_format($pdfValorRCM, 0, ',', '.'); // Agregar el símbolo de peso aquí
		} else {
			$pdfValorRC = $rowRespuesta9['ValorRC'];
		}

		if ($cont4 % 2 == 0) {

			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="5" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		} else {

			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%; font-family:dejavusanscondensed;"><center><font size="5" style="text-align: center;"><div style="font-size:4pt">&nbsp;</div>' . $pdfValorRC . '</font></center></td>';
		}

		$cont4 += 1;
	}
} else {
	while ($rowRespuesta9 = mysqli_fetch_assoc($respuestaquery9)) {

		if (is_numeric($rowRespuesta9['ValorRC'])) {
			$pdfValorRCM = $rowRespuesta9['ValorRC'] / 1000000;
			$pdfValorRC = '$' . number_format($pdfValorRCM, 0, ',', '.'); // Agregar el símbolo de peso aquí
		} else {
			$pdfValorRC = $rowRespuesta9['ValorRC'];
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
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Deducible</font></td>';


$query8 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.ValorRC, o.PerdidaParcial, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery8 = $conexion->query($query8);
$rowValidate = mysqli_num_rows($respuestaquery8);
if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery8);
	$query8 = "SELECT ValorRC, PerdidaParcial, Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery8 = $conexion->query($query8);
	$rowValidate = mysqli_num_rows($respuestaquery8);
}

$cont5 = 1;

while ($rowRespuesta8 = mysqli_fetch_assoc($respuestaquery8)) {
	$nombreAseguradora = nombreAseguradora($rowRespuesta8['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta8['Aseguradora'], $rowRespuesta8['Producto']);
	$valorRC = $rowRespuesta8['ValorRC'];
	$perdidaParcial = $rowRespuesta8['PerdidaParcial'];

	$queryConsultaAsistencia1 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto' 
									AND `rce` LIKE '$valorRC'";
	$respuestaqueryAsistencia1 =  $conexion->query($queryConsultaAsistencia1);
	$rowRespuestaAsistencia1 = mysqli_fetch_assoc($respuestaqueryAsistencia1);
	if ($rowRespuestaAsistencia1 !== null) {
		if ($cont5 % 2 == 0) {
			$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia1['deducible'] . '</font></center></td>';
		} else {
			$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia1['deducible'] . '</font></center></td>';
		}
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">Sin Deducible</font></center></td>';
	}

	$cont5 += 1;
}
$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS TOTAL DAÑOS
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr style="width: 100%;" class="izquierda">';
$html3 .= '<td style ="width: 100%; background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($fila6 + 1) . '"><div style="font-size:3pt">&nbsp;</div>COBERTURAS AL VEHÍCULO <div style="font-size:3pt">&nbsp;</div></td>';

$html3 .= '</tr>';

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Pérdida total daños o hurto</font></td>';


$query10 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.PerdidaTotal, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";;

$respuestaquery10 = $conexion->query($query10);
$rowValidate = mysqli_num_rows($respuestaquery10);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery10);
	$query10 = "SELECT Aseguradora, PerdidaTotal, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery10 = $conexion->query($query10);
	$rowValidate = mysqli_num_rows($respuestaquery10);
}

$cont6 = 1;
while ($rowRespuesta10 = mysqli_fetch_assoc($respuestaquery10)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta10['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta10['Aseguradora'], $rowRespuesta10['Producto']);

	if ($cont6 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuesta10['PerdidaTotal'] . '</font></center></td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuesta10['PerdidaTotal'] . '</font></center></td>';
	}

	$cont6 += 1;
}
$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS PARCIAL DAÑOS
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Pérdida parcial por daño</font></td>';

$query11 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.PerdidaParcial, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery11 = $conexion->query($query11);
$rowValidate = mysqli_num_rows($respuestaquery11);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery11);
	$query11 = "SELECT PerdidaParcial, Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery11 = $conexion->query($query11);
	$rowValidate = mysqli_num_rows($respuestaquery11);
}
$cont7 = 1;

while ($rowRespuesta11 = mysqli_fetch_assoc($respuestaquery11)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta11['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta11['Aseguradora'], $rowRespuesta11['Producto']);

	if ($cont7 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuesta11['PerdidaParcial'] . '</font></center></td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuesta11['PerdidaParcial'] . '</font></center></td>';
	}

	$cont7 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS PARCIAL HURTO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Pérdida parcial por hurto</font></td>';

$query12 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.PerdidaParcial, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery12 = $conexion->query($query12);
$rowValidate = mysqli_num_rows($respuestaquery12);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery12);
	$query12 = "SELECT Aseguradora, PerdidaParcial, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery12 = $conexion->query($query12);
	$rowValidate = mysqli_num_rows($respuestaquery12);
}

$cont8 = 1;

while ($rowRespuesta12 = mysqli_fetch_assoc($respuestaquery12)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta12['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta12['Aseguradora'], $rowRespuesta12['Producto']);

	if ($cont8 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuesta12['PerdidaParcial'] . '</font></center></td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuesta12['PerdidaParcial'] . '</font></center></td>';
	}

	$cont8 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA COBERTURAS EVENTO NATURAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font style="font-family:dejavusanscondensedb;" size="8">Cobertura por Eventos de la naturaleza</font></td>';

$query13 = "SELECT DISTINCT cf.identityElement, o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery13 = $conexion->query($query13);
$rowValidate = mysqli_num_rows($respuestaquery13);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery13);
	$query13 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery13 = $conexion->query($query13);
	$rowValidate = mysqli_num_rows($respuestaquery13);
}
$cont9 = 1;
while ($rowRespuesta13 = mysqli_fetch_assoc($respuestaquery13)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta13['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta13['Aseguradora'], $rowRespuesta13['Producto']);

	$queryConsultaAsistencia5 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia5 =  $conexion->query($queryConsultaAsistencia5);
	$rowRespuestaAsistencia5 = mysqli_fetch_assoc($respuestaqueryAsistencia5);


	if ($cont9 % 2 == 0) {
		$html3 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
	} else {
		$html3 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:4pt">&nbsp;</div><font size="7"style="text-align: center;  font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia5['eventos'] . '</font></center></td>';
	}

	$cont9 += 1;
}

$html3 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA AMPARO PATRIMONIAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html3 .= '<tr>';
$html3 .= '<td class="puntos fondo" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:2pt">&nbsp;</div><font size="8">Amparo patrimonial</font></td>';

$query14 = "SELECT DISTINCT o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery14 = $conexion->query($query14);
$rowValidate = mysqli_num_rows($respuestaquery14);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery14);
	$query14 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery14 = $conexion->query($query14);
	$rowValidate = mysqli_num_rows($respuestaquery14);
}

$cont10 = 1;
while ($rowRespuesta14 = mysqli_fetch_assoc($respuestaquery14)) {
	$nombreAseguradora = nombreAseguradora($rowRespuesta14['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta14['Aseguradora'], $rowRespuesta14['Producto']);

	$queryConsultaAsistencia6 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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

$query6 = "SELECT o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$valor6 = $conexion->query($query6);
$fila6 = mysqli_num_rows($valor6);

if ($fila6 == 0 || $fila6 == false || $fila6 == null) {
	mysqli_free_result($valor6);
	$query6 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$valor6 = $conexion->query($query6);
	$fila6 = mysqli_num_rows($valor6);
}

$html4 .= '<tr style="width: 100%;" class="izquierda">';
$html4 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($fila6 + 1) . '">
<div style="font-size:3pt">&nbsp;</div>
   ASISTENCIAS
   <div style="font-size:3pt">&nbsp;</div>
</td>';
$html4 .= '</tr>';

$query7 = "SELECT DISTINCT o.Aseguradora,
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery7 = $conexion->query($query6);
$rowValidate = mysqli_num_rows($respuestaquery7);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery7);
	$query7 = "SELECT Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery7 = $conexion->query($query7);
	$rowValidate = mysqli_num_rows($respuestaquery7);
}

$html4 .= '<tr class="trborder">';
$valorTabla = (90 / $fila6);
$html4 .= '<td class="puntos fondo" style="width:10%;"></td>';
$cont3f = 1;

while ($rowRespuesta7 = mysqli_fetch_assoc($respuestaquery7)) {
	$pdf->SetFont('dejavusanscondensed', '', 8);
	if ($cont3f % 2 == 0) {
		if ($rowRespuesta7['Aseguradora'] == 'Axa Colpatria') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center>
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros del Estado') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'HDI Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'SBS Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Bolivar') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Sura') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Mapfre') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Mapfre') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Aseguradora Solidaria') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Solidaria') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt="">
			<div style="font-size:5pt">&nbsp;</div>
			</td>';
		}
	} else {
		if ($rowRespuesta7['Aseguradora'] == 'Axa Colpatria') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><center>
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/axa.png" alt=""></center></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros del Estado') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/estado.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'HDI Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/hdi.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'SBS Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sbs.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Bolivar') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/bolivar.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Sura') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/sura.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich Seguros') {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Zurich') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/zurich.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Allianz') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/allianz.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Liberty') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/liberty.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Seguros Mapfre') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Mapfre') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/mapfre.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Equidad') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/equidad.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora Seguros') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Previsora') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/previsora.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Aseguradora Solidaria') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
		} else if ($rowRespuesta7['Aseguradora'] == 'Solidaria') {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;">
			<div style="font-size:5pt">&nbsp;</div>
			<img style="width:35px;" src="../../../vistas/img/logos/solidaria.png" alt=""></td>';
		}
	}

	$cont3f += 1;
}
$html4 .= '</tr>';

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:2pt">&nbsp;</div><font size="8">Grua varada o accidente.</font></td>';
$query15 = "SELECT DISTINCT o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery15 = $conexion->query($query15);
$rowValidate = mysqli_num_rows($respuestaquery15);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery15);
	$query15 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery15 = $conexion->query($query15);
	$rowValidate = mysqli_num_rows($respuestaquery7);
}

$cont11 = 1;
while ($rowRespuesta15 = mysqli_fetch_assoc($respuestaquery15)) {

	$pdf->SetFont('dejavusanscondensed', '', 8);
	$nombreAseguradora = nombreAseguradora($rowRespuesta15['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta15['Aseguradora'], $rowRespuesta15['Producto']);

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
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><div style="font-size:2pt">&nbsp;</div><font size="8">Carrotaller </font> <font size="5"> (desvare por: llanta, batería, gasolina o cerrajería).</font></td>';

$query16 = "SELECT DISTINCT o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery16 = $conexion->query($query16);
$rowValidate = mysqli_num_rows($respuestaquery16);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery16);
	$query16 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery16 = $conexion->query($query16);
	$rowValidate = mysqli_num_rows($respuestaquery7);
}
$cont12 = 1;

while ($rowRespuesta16 = mysqli_fetch_assoc($respuestaquery16)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta16['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta16['Aseguradora'], $rowRespuesta16['Producto']);

	$queryConsultaAsistencia8 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia8 =  $conexion->query($queryConsultaAsistencia8);
	$rowRespuestaAsistencia8 = mysqli_fetch_assoc($respuestaqueryAsistencia8);

	if ($cont12 % 2 == 0) {
		if ($rowRespuestaAsistencia8['Carrotaller'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:14pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:14pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia8['Carrotaller'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia8['Carrotaller'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:14pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:14pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia8['Carrotaller'] . '</font></center></td>';
		}
	}

	$cont12 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA ASISTENCIA JURIDICA
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Asistencia juridica en proceso penal </font></td>';

$query17 = "SELECT DISTINCT o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery17 = $conexion->query($query17);
$rowValidate = mysqli_num_rows($respuestaquery17);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery17);
	$query17 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery17 = $conexion->query($query17);
	$rowValidate = mysqli_num_rows($respuestaquery17);
}
$cont13 = 1;

while ($rowRespuesta17 = mysqli_fetch_assoc($respuestaquery17)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta17['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta17['Aseguradora'], $rowRespuesta17['Producto']);

	$queryConsultaAsistencia9 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia9 =  $conexion->query($queryConsultaAsistencia9);
	$rowRespuestaAsistencia9 = mysqli_fetch_assoc($respuestaqueryAsistencia9);

	if ($cont13 % 2 == 0) {
		if ($rowRespuestaAsistencia9['amparopatrimonial'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia9['amparopatrimonial'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia9['amparopatrimonial'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><font size="7"style="text-align: center;">' . $rowRespuestaAsistencia9['amparopatrimonial'] . '</font></center></td>';
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

$query27 = "SELECT DISTINCT o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery27 = $conexion->query($query27);
$rowValidate = mysqli_num_rows($respuestaquery27);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery27);
	$query27 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery27 = $conexion->query($query27);
	$rowValidate = mysqli_num_rows($respuestaquery27);
}
$cont14 = 1;

while ($rowRespuesta27 = mysqli_fetch_assoc($respuestaquery27)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta27['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta27['Aseguradora'], $rowRespuesta27['Producto']);

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

$html4 .= '<tr>';
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Gastos de transporte en perdida parcial</font></td>';

// CONSULTA TRANSPORTE PP
$query18 = "SELECT DISTINCT o.Aseguradora, o.Producto
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

// Ejecutar la consulta y verificar si se ejecuta correctamente
$respuestaquery18 = $conexion->query($query18);
if (!$respuestaquery18) {
    die('Error en la consulta: ' . $conexion->error);
}

$rowValidate = mysqli_num_rows($respuestaquery18);
if ($rowValidate == 0) {
    mysqli_free_result($respuestaquery18);
    $query18 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
    $respuestaquery18 = $conexion->query($query18);
    if (!$respuestaquery18) {
        die('Error en la consulta secundaria: ' . $conexion->error);
    }
    $rowValidate = mysqli_num_rows($respuestaquery18);
}

$cont15 = 1;
while ($rowRespuesta18 = mysqli_fetch_assoc($respuestaquery18)) {
    $nombreAseguradora = nombreAseguradora($rowRespuesta18['Aseguradora']);
    $nombreProducto = productoAseguradora($rowRespuesta18['Aseguradora'], $rowRespuesta18['Producto']);

    $queryConsultaAsistencia11 = "
    SELECT * FROM asistencias 
    WHERE `aseguradora` LIKE '$nombreAseguradora' 
    AND `producto` LIKE '$nombreProducto'";
    $respuestaqueryAsistencia11 = $conexion->query($queryConsultaAsistencia11);
    if (!$respuestaqueryAsistencia11) {
        die('Error en la consulta de asistencia: ' . $conexion->error);
    }
    $rowRespuestaAsistencia11 = mysqli_fetch_assoc($respuestaqueryAsistencia11);

    $fondo_class = ($cont15 % 2 == 0) ? 'fondo' : 'fondo2';
    if ($rowRespuestaAsistencia11['Gastosdetransportepp'] == "Si ampara") {
        $html4 .= '<td class="puntos ' . $fondo_class . '" style="width:' . $valorTabla . '%; text-align: center;">
        <div style="font-size:10pt">&nbsp;</div>
        <img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
    } else {
        $html4 .= '<td class="puntos ' . $fondo_class . '" style="width:' . $valorTabla . '%;">
        <center><div style="font-size:12pt">&nbsp;</div>
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

$query28 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery28 = $conexion->query($query28);
$rowValidate = mysqli_num_rows($respuestaquery28);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery28);
	$query28 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery28 = $conexion->query($query28);
	$rowValidate = mysqli_num_rows($respuestaquery28);
}
$cont16 = 1;
while ($rowRespuesta28 = mysqli_fetch_assoc($respuestaquery28)) {
	$nombreAseguradora = nombreAseguradora($rowRespuesta28['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta28['Aseguradora'], $rowRespuesta28['Producto']);

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
//CONSULTA VEHICULO REEMPLAZO PERDIDA PARCIAL
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class ="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Vehículo de reemplazo en pérdida parcial</font></td>';

$query19 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery19 = $conexion->query($query19);
$rowValidate = mysqli_num_rows($respuestaquery19);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery19);
	$query19 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery19 = $conexion->query($query19);
	$rowValidate = mysqli_num_rows($respuestaquery19);
}

$cont17 = 1;

while ($rowRespuesta19 = mysqli_fetch_assoc($respuestaquery19)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta19['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta19['Aseguradora'], $rowRespuesta19['Producto']);

	$queryConsultaAsistencia13 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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

$query20 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery20 = $conexion->query($query20);
$rowValidate = mysqli_num_rows($respuestaquery20);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery20);
	$query20 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery20 = $conexion->query($query20);
	$rowValidate = mysqli_num_rows($respuestaquery20);
}
$cont18 = 1;

while ($rowRespuesta20 = mysqli_fetch_assoc($respuestaquery20)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta20['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta20['Aseguradora'], $rowRespuesta20['Producto']);

	$queryConsultaAsistencia14 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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
$html4 .= '<td class="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Transporte del vehículo recuperado</font></td>';

$query21 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery21 = $conexion->query($query21);
$rowValidate = mysqli_num_rows($respuestaquery21);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery21);
	$query21 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery21 = $conexion->query($query21);
	$rowValidate = mysqli_num_rows($respuestaquery21);
}
$cont19 = 1;
while ($rowRespuesta21 = mysqli_fetch_assoc($respuestaquery21)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta21['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta21['Aseguradora'], $rowRespuesta21['Producto']);

	$queryConsultaAsistencia15 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
	$respuestaqueryAsistencia15 =  $conexion->query($queryConsultaAsistencia15);
	$rowRespuestaAsistencia15 = mysqli_fetch_assoc($respuestaqueryAsistencia15);

	if ($cont19 % 2 == 0) {
		if ($rowRespuestaAsistencia15['Transportevehiculorecuperdo'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia15['Transportevehiculorecuperdo'] . '</font></center></td>';
		}
	} else {
		if ($rowRespuestaAsistencia15['Transportevehiculorecuperdo'] == "Si ampara") {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;text-align: center;"><div style="font-size:10pt">&nbsp;</div><img style="width:16px;" src="../../../vistas/img/logos/cheque.png" alt=""></td>';
		} else {
			$html4 .= '<td class="puntos fondo2" style="width:' . $valorTabla . '%;"><center><div style="font-size:12pt">&nbsp;</div><font size="7"style="text-align: center; font-family:dejavusanscondensed;">' . $rowRespuestaAsistencia15['Transportevehiculorecuperdo'] . '</font></center></td>';
		}
	}


	$cont19 += 1;
}

$html4 .= '</tr>';

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//CONSULTA Transporte DE PASAJEROS POR ACCIDENTE
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$html4 .= '<tr>';
$html4 .= '<td class ="fondo puntos" style="width:10%; text-align: center; font-family:dejavusanscondensedb;"><font size="8">Transporte de pasajeros por accidente</font></td>';

$query22 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery22 = $conexion->query($query22);
$rowValidate = mysqli_num_rows($respuestaquery22);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery22);
	$query22 = "SELECT * FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery22 = $conexion->query($query22);
	$rowValidate = mysqli_num_rows($respuestaquery22);
}

$cont20 = 1;
while ($rowRespuesta22 = mysqli_fetch_assoc($respuestaquery22)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta22['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta22['Aseguradora'], $rowRespuesta22['Producto']);

	$queryConsultaAsistencia16 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Transporte de pasajeros por varada</font></td>';

$query24 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery24 = $conexion->query($query24);
$rowValidate = mysqli_num_rows($respuestaquery24);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery24);
	$query24 = "SELECT Aseguradora, Producto FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery24 = $conexion->query($query24);
	$rowValidate = mysqli_num_rows($respuestaquery24);
}

$cont22 = 1;
while ($rowRespuesta24 = mysqli_fetch_assoc($respuestaquery24)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta24['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta24['Aseguradora'], $rowRespuesta24['Producto']);

	$queryConsultaAsistencia17 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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
$html4 .= '<td class="fondo puntos" style="width:10%;"><font size="8" style="font-family:dejavusanscondensedb; text-align: center;">Accidentes personales</font></td>';

$query25 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery25 = $conexion->query($query25);
$rowValidate = mysqli_num_rows($respuestaquery25);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery25);
	$query25 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery25 = $conexion->query($query25);
	$rowValidate = mysqli_num_rows($respuestaquery25);
}
$cont23 = 1;
while ($rowRespuesta25 = mysqli_fetch_assoc($respuestaquery25)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta25['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta25['Aseguradora'], $rowRespuesta25['Producto']);

	$queryConsultaAsistencia18 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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

$query26 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery26 = $conexion->query($query26);
$rowValidate = mysqli_num_rows($respuestaquery26);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery26);
	$query26 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery26 = $conexion->query($query26);
	$rowValidate = mysqli_num_rows($respuestaquery26);
}

$cont24 = 1;
while ($rowRespuesta26 = mysqli_fetch_assoc($respuestaquery26)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta26['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta26['Aseguradora'], $rowRespuesta26['Producto']);

	$queryConsultaAsistencia19 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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

$query28 = "SELECT DISTINCT o.Producto, o.Aseguradora
FROM cotizaciones_finesa cf 
INNER JOIN ofertas o ON o.id_cotizacion = cf.id_cotizacion
WHERE o.seleccionar = 'Si' 
AND cf.identityElement = o.oferta_finesa
AND cf.id_cotizacion = $identificador";

$respuestaquery28 = $conexion->query($query28);
$rowValidate = mysqli_num_rows($respuestaquery28);

if ($rowValidate == 0 || $rowValidate == false || $rowValidate == null) {
	mysqli_free_result($respuestaquery28);
	$query28 = "SELECT Producto, Aseguradora FROM ofertas WHERE `id_cotizacion` = $identificador AND `seleccionar` = 'Si'";
	$respuestaquery28 = $conexion->query($query28);
	$rowValidate = mysqli_num_rows($respuestaquery28);
}

$cont25 = 1;
while ($rowRespuesta28 = mysqli_fetch_assoc($respuestaquery28)) {

	$nombreAseguradora = nombreAseguradora($rowRespuesta28['Aseguradora']);
	$nombreProducto = productoAseguradora($rowRespuesta28['Aseguradora'], $rowRespuesta28['Producto']);

	$queryConsultaAsistencia20 = "SELECT * FROM asistencias WHERE `aseguradora` LIKE '$nombreAseguradora' AND `producto` LIKE '$nombreProducto'";
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
	} else if ($rowRespuesta29['Aseguradora'] == 'Liberty Seguros') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/liberty.png" alt=""></td>';
	} else if ($rowRespuesta29['Aseguradora'] == 'Liberty') {
		$html6 .= '<td style="width:40%;text-align: center;">
		<div style="font-size:6pt">&nbsp;</div>
		<img style="width:75px;" src="../../../vistas/img/logos/liberty.png" alt=""></td>';
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
	$html6 .= '<td style ="width: 100%;  background-color: #D1D1D1; font-family:dejavusanscondensedb;" colspan="' . ($fila6 + 1) . '">COBERTURA DEL VEHÍCULO</td>';
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
$html7 .= '<td style ="width: 100%;" colspan="' . ($fila6 + 1) . '"><font  size="18" style="text-align: center;">Queremos sugerirte <font style="color: #EC8923;">las ' . $asegRecomendada . ' mejores</font> aseguradoras</font></td>';
$html7 .= '</tr>';

$html7 .= '</table>';

$pdf->SetXY(80, 103);
$pdf->writeHTML($html2, true, false, true, false, '');
$pdf->Ln();

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
		$service = "PUBLICO URBANO";
	} else if ($dato == 12) {
		$service = "PUBLICO MUNICIPAL";
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
	} else if ($data == 'Liberty Seguros') {
		$resultado = "Liberty";
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
$pdf->Output('cotizacionAutos.pdf', 'I');
