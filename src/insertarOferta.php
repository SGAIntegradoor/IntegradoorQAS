<?php

// Conectar a la base de datos
require_once("../config/db.php"); // Contiene las variables de configuración para conectar a la base de datos
require_once("../config/conexion.php"); // Contiene función que conecta a la base de datos

mysqli_set_charset($con, "utf8mb4");

// Configurar la cabecera HTTP para UTF-8
header('Content-Type: text/html; charset=utf-8');

// Obtener datos de POST
$placa = $_POST['placa'];
$idCotizacion = $_POST['idCotizOferta'];
$numIdentificacion = $_POST['numIdentificacion'];
$aseguradora = $_POST['aseguradora'];
$valorPrima = str_replace('.', '', $_POST['valorPrima']);
$producto = $_POST['producto'];
$numCotizOferta = $_POST['numCotizOferta'];
$valorRC = str_replace('.', '', $_POST['valorRC']);
$PT = $_POST['PT'];
$PP = $_POST['PP'];
$CE = $_POST['CE'];
$GR = $_POST['GR'];
$logo = "vistas/img/logos/" . $_POST['logo'];
$UrlPdf = isset($_POST['UrlPdf']) ? $_POST['UrlPdf'] : NULL;
$categorias = isset($_POST['categorias']) ? json_encode($_POST['categorias']) : "[]";
$eventos = isset($_POST['eventos']) ? $_POST['eventos'] : NULL;
$manual = $_POST['manual'];
$actIdentity = isset($_POST['identityElement']) && $_POST['identityElement'] != NULL ? $_POST['identityElement'] : NULL;

// Verificar la conexión a la base de datos
if (!$con) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

if ($aseguradora == "Axa Colpatria") {
    $sql = "INSERT INTO `ofertas` (`id_oferta`, `Placa`, `Identificacion`, `NumCotizOferta`, `Aseguradora`, `Producto`, `Categoria`, `Prima`, 
                `ValorRC`, `PerdidaTotal`, `PerdidaParcial`, `ConductorElegido`, `Grua`, `Eventos`, `logo`, `UrlPdf`, `id_cotizacion`, `Manual`, 
                `ResponsabilidadCivilGeneralFamiliar`, `PerdidaParcialHurto`, `oferta_finesa`) 
            VALUES (NULL, '$placa', '$numIdentificacion', '$numCotizOferta', '$aseguradora', '$producto', '$categorias', '$valorPrima', '$valorRC', 
                    '$PT', '$PP', '$CE', '$GR', '$eventos', '$logo', '$UrlPdf', '$idCotizacion', '$manual', NULL, NULL, '$actIdentity')";
} else {
    $familiar = isset($_POST['responsabilidad_civil_familiar']) ? $_POST['responsabilidad_civil_familiar'] : NULL;
    $pph = isset($_POST['pph']) ? $_POST['pph'] : NULL;
    if ($pph) {
        $sql = "INSERT INTO `ofertas` (`id_oferta`, `Placa`, `Identificacion`, `NumCotizOferta`, `Aseguradora`, `Producto`, `Categoria`, `Prima`, 
                    `ValorRC`, `PerdidaTotal`, `PerdidaParcial`, `ConductorElegido`, `Grua`, `Eventos`, `logo`, `UrlPdf`, `id_cotizacion`, `Manual`, 
                    `ResponsabilidadCivilGeneralFamiliar`, `PerdidaParcialHurto`, `oferta_finesa`) 
                VALUES (NULL, '$placa', '$numIdentificacion', '$numCotizOferta', '$aseguradora', '$producto', '$categorias', '$valorPrima', '$valorRC', 
                        '$PT', '$PP', '$CE', '$GR', '$eventos', '$logo', '$UrlPdf', '$idCotizacion', '$manual', '$familiar', '$pph', '$actIdentity')";
    } else {
        $sql = "INSERT INTO `ofertas` (`id_oferta`, `Placa`, `Identificacion`, `NumCotizOferta`, `Aseguradora`, `Producto`, `Categoria`, `Prima`, 
                    `ValorRC`, `PerdidaTotal`, `PerdidaParcial`, `ConductorElegido`, `Grua`, `Eventos`, `logo`, `UrlPdf`, `id_cotizacion`, `Manual`, 
                    `ResponsabilidadCivilGeneralFamiliar`, `PerdidaParcialHurto`, `oferta_finesa`) 
                VALUES (NULL, '$placa', '$numIdentificacion', '$numCotizOferta', '$aseguradora', '$producto', '$categorias', '$valorPrima', '$valorRC', 
                        '$PT', '$PP', '$CE', '$GR', '$eventos', '$logo', '$UrlPdf', '$idCotizacion', '$manual', NULL, NULL, '$actIdentity')";
    }
}

// Ejecutar la consulta SQL y manejar errores
$res = mysqli_query($con, $sql);
$num_rows = mysqli_affected_rows($con);

if ($num_rows > 0) {
    $data['Success'] = true;
    $data['Message'] = 'La inserción fue exitosa';
} else {
    $data['Success'] = false;
    $data['Message'] = 'Error: ' . mysqli_error($con);
}

// Devolver la respuesta en formato JSON
echo json_encode($data, JSON_UNESCAPED_UNICODE);

// Cerrar la conexión a la base de datos
mysqli_close($con);
?>
