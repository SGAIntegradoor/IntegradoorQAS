<?php
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");

// Capturar salida del include
ob_start();
$idCotizacion = $_GET['cotizacion'] ?? 0;
include 'pdfSalud.php';
$html_generado = ob_get_clean();

// Cargar en DOMDocument
$dom = new DOMDocument();
libxml_use_internal_errors(true); // evitar warnings por HTML no perfecto
$dom->loadHTML($html_generado, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

// Obtener el contenido del <body>
$body = $dom->getElementsByTagName("body")->item(0);

$contenido_body = $dom->saveHTML($body);

echo htmlspecialchars($contenido_body);
?>