<?php
require_once "config/dbconfig.php";

function obtenerCredenciales($enlace, $tabla, $columnas, $idIntermediario)
{
    $query = "SELECT $columnas FROM `$tabla` WHERE `id_intermediario` = '$idIntermediario'";
    $ejecucion = mysqli_query($enlace, $query);
    $numerofilas = mysqli_num_rows($ejecucion);
    $fila = mysqli_fetch_assoc($ejecucion);

    if ($numerofilas > 0) {
        return $fila;
    } else {

        return false;
    }
}

// FUNCION PARA OBTENER CREDENCIALES SBS
if ($aseguradoras['SBS']['C'] == "1") {

    $creSBS = obtenerCredenciales($enlace, 'Credenciales_SBS', '*', $_SESSION['intermediario']);
} else {

    $creSBS = obtenerCredenciales($enlace, 'Credenciales_SBS', '*', '3');
}
$cre_sbs_usuario = $creSBS['cre_sbs_usuario'];
$cre_sbs_contrasena = isset($creSBS['cre_sbs_contrasena']) ? $creSBS['cre_sbs_contrasena'] : "";

// Lógica para ALLIANZ
if ($aseguradoras['Allianz']['C'] == "1") {
    $creAllianz = obtenerCredenciales($enlace, 'Credenciales_Allianz', '*', $_SESSION['intermediario']);
} else {
    $creAllianz = obtenerCredenciales($enlace, 'Credenciales_Allianz', '*', '3');
}
$cre_alli_sslcertfile = $creAllianz['cre_alli_sslcertfile'];
$cre_alli_sslkeyfile = $creAllianz['cre_alli_sslkeyfile'];
$cre_alli_passphrase = $creAllianz['cre_alli_passphrase'];
$cre_alli_partnerid = $creAllianz['cre_alli_partnerid'];
$cre_alli_agentid = $creAllianz['cre_alli_agentid'];
$cre_alli_partnercode = $creAllianz['cre_alli_partnercode'];
$cre_alli_agentcode = $creAllianz['cre_alli_agentcode'];

// Lógica para ESTADO
if ($aseguradoras['Estado']['C'] == "1") {
    $creEstado = obtenerCredenciales($enlace, 'Credenciales_Estado', '*', $_SESSION['intermediario']);
} else {
    $creEstado = obtenerCredenciales($enlace, 'Credenciales_Estado', '*', '3');
}
$cre_est_usuario = $creEstado['cre_est_usuario'];
$cre_equ_contrasena = $creEstado['cre_equ_contrasena'];
$Cre_Est_Entity_Id = $creEstado['Cre_Est_Entity_Id'];
$cre_est_zona = $creEstado['cre_est_zona'];


// Lógica para AXA
if ($aseguradoras['AXA']['C'] == "1") {
    $creAXA = obtenerCredenciales($enlace, 'Credenciales_AXA', '*', $_SESSION['intermediario']);
} else {
    $creAXA = obtenerCredenciales($enlace, 'Credenciales_AXA', '*', '3');
}
$cre_axa_sslcertfile = $creAXA['cre_axa_sslcertfile'];
$cre_axa_sslkeyfile = $creAXA['cre_axa_sslkeyfile'];
$cre_axa_passphrase = $creAXA['cre_axa_passphrase'];
$cre_axa_codigoDistribuidor = $creAXA['cre_axa_codigoDistribuidor'];
$cre_axa_idTipoDistribuidor = $creAXA['cre_axa_idTipoDistribuidor'];
$cre_axa_codigoDivipola = $creAXA['cre_axa_codigoDivipola'];
$cre_axa_canal = $creAXA['cre_axa_canal'];
$cre_axa_validacionEventos = $creAXA['cre_axa_validacionEventos'];
$url_axa = $creAXA['url_axa'];
$cre_axa_livianos_productos = $creAXA['livianos_productos'];

// Lógica para SOLIDARIA
if ($aseguradoras['Solidaria']['C'] == "1") {
    $creSolidaria = obtenerCredenciales($enlace, 'Credenciales_Solidaria', '*', $_SESSION['intermediario']);
} else {
    $creSolidaria = obtenerCredenciales($enlace, 'Credenciales_Solidaria', '*', '3');
}
$cre_sol_id = $creSolidaria['cre_sol_id'] ?? null;
$id_Intermediario = $creSolidaria['id_Intermediario'] ?? null;
$cre_sol_cod_sucursal = $creSolidaria['cre_sol_cod_sucursal'] ?? null;
$cre_sol_cod_per = $creSolidaria['cre_sol_cod_per'] ?? null;
$cre_sol_cod_tipo_agente = $creSolidaria['cre_sol_cod_tipo_agente'] ?? null;
$cre_sol_cod_agente = $creSolidaria['cre_sol_cod_agente'] ?? null;
$cre_sol_cod_pto_vta = $creSolidaria['cre_sol_cod_pto_vta'] ?? null;
$cre_sol_grant_type = $creSolidaria['cre_sol_grant_type'] ?? null;
$cre_sol_Cookie_token = $creSolidaria['cre_sol_Cookie_token'] ?? null;
$cre_sol_token = $creSolidaria['cre_sol_token'] ?? null;
$cre_sol_fecha_token = $creSolidaria['cre_sol_fecha_token'] ?? null;

if ($aseguradoras['Previsora']['C'] == "1") {
    $crePrevisora = obtenerCredenciales($enlace, 'Credenciales_Previsora', '*', $_SESSION['intermediario']);
} else {
    $crePrevisora = obtenerCredenciales($enlace, 'Credenciales_Previsora', '*', '3');
}

$cre_pre_usu = $crePrevisora['cre_pre_Username'];
$cre_pre_pass = $crePrevisora['cre_pre_Password'];
$cre_pre_source_code = $_SESSION['intermediario'] == 153 ? 24 : ($_SESSION['intermediario'] == 3 ? 12 : 12);
$cre_pre_bussinessId = $_SESSION['intermediario'] == 153 ? 25 : ($_SESSION['intermediario'] == 3 ? 11 : 11);
$cre_pre_key = $crePrevisora['cre_pre_AgentCode'];

// Lógica para BOLIVAR
if ($aseguradoras['Bolivar']['C'] == "1") {
    $creBolivar = obtenerCredenciales($enlace, 'Credenciales_Bolivar', '*', $_SESSION['intermediario']);
} else {
    $creBolivar = obtenerCredenciales($enlace, 'Credenciales_Bolivar', '*', '3');
}
$cre_bol_api_key = $creBolivar['cre_bol_api_key'] ?? null;
$cre_bol_claveAsesor = $creBolivar['cre_bol_claveAsesor'] ?? null;

if ($_SESSION["permisos"]["Cotizarlivianos"] != "x") {

    echo '<script>

    window.location = "inicio";

  </script>';

    return;
}

$rolAsesor = $_SESSION['permisos']['id_rol'];
$idIntermediario = $_SESSION['permisos']['id_Intermediario'];

if (isset($_SESSION)) {
}

echo '<script>console.log(' . $idIntermediario . ", " . $rolAsesor . ')</script>';
?>

<style>
    .table-padding {
        padding: 15px;
    }

    .card-ofertas {
        padding: 20px;
    }

    .thTable {
        text-align: center;
    }

    .custom-title-messageFinesa {
        font-size: 16px;
        font-weight: bold;
        color: #000000 !important;
        ;
    }

    .custom-text-messageFinesa {
        font-size: 15px !important;
        width: 100%;
        text-align: center !important;
        font-weight: bold !important;
        color: #000000 !important;
        padding-left: 27px !important;
        padding-right: 27px !important;
    }

    .custom-popup-messageFinesa {
        border-radius: 10px;
        text-align: center;
        padding-top: 6px !important;
    }

    .custom-actions-messageFinesa {
        flex-direction: row-reverse;
        gap: 25px;
        padding-top: 10px;
    }

    .custom-confirmnButton-messageFinesa {
        background-color: #88d600 !important;
        color: white;
        width: 55px !important;
        height: 30px !important;
        border-radius: 5px !important;
    }

    .custom-cancelButton-messageFinesa {
        background-color: #000000 !important;
        color: white;
        width: 55px !important;
        height: 30px !important;
        border-radius: 5px !important;
    }

    #pTableModal {
        font-size: 12px !important;
    }

    .custom-swal-alertaMontoLivianos {
        display: flex;
        flex-direction: column;
        width: 30%;
        padding: 30px;
        border-radius: 15px !important;
    }

    .swal2-icon_monto {
        width: 90px;
        height: 90px;
        border: 4px solid #f8bb86 !important;
    }

    .custom-swal-popup {
        border-radius: 25px;
    }

    .swal2-actions {
        align-content: center !important;
        margin: 0 !important;
    }

    .custom-swal-confirm-button20 {
        font-size: 16px !important;
        height: 50px;
        width: 150px;
        border-radius: 10px !important;
    }

    .custom-swal-actions-livianos {
        padding-bottom: 25px !important;
    }


    @media (min-width: 320px) and (max-width: 577px) {

        #tableModal td {
            text-align: center;
            font-size: 12px;
        }

        .custom-swal-alertaMontoLivianos {
            display: flex;
            flex-direction: column;
            width: 95% !important;
            padding: 0px 0px 0px 0px !important;
            /* gap: 10px; */
            border-radius: 15px !important;
        }

        #tdAsegurado {
            width: 300px;
            text-align: center;
            font-size: 14px;
        }

        #tdCondiciones {
            width: 300px;
            text-align: center;
            font-size: 14px;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
            padding-top: 2px;
            padding-right: 12px;
            padding-left: 12px;
            /* padding-bottom: 6px; */
            align-items: center;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container>p {
            font-size: 17px;
            /* padding-top: 6px; */
            text-align: justify;
            margin: 0px;
        }


        #pTableModal {
            font-size: 10px !important;
        }

        .swal2-actions {
            align-content: center !important;
            margin: 0 !important;
        }

        .custom-swal-confirm-button20 {
            font-size: 16px !important;
            height: 50px;
            width: 150px;
            border-radius: 10px !important;
        }

        .custom-swal-titleLivianos {
            font-size: 17px !important;
            font-weight: bold;
            margin: 0px;
            padding: 0px
        }

        .swal2-icon_monto {
            width: 60px !important;
            height: 60px !important;
            border: 3px solid #f8bb86 !important;
        }

        .custom-swal-actions-livianos {
            padding-bottom: 3px !important;
        }

    }

    @media (min-width: 577px) and (max-width: 768px) {

        #tableModal td {
            text-align: center;
            font-size: 15px;
        }

        .custom-swal-alertaMontoLivianos {
            display: flex;
            flex-direction: column;
            width: 70% !important;
            padding: 20px 30px 20px 30px !important;
            gap: 10px;
            border-radius: 15px !important;
        }

        #tdAsegurado {
            width: 270px;
            text-align: center;
            font-size: 16px;
        }

        #tdCondiciones {
            width: 335px;
            text-align: center;
            font-size: 16px;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
            padding-right: 17px;
            padding-left: 17px;
            padding-bottom: 20px;
            align-items: center;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container>p {
            font-size: 17px;
            padding-top: 20px;
            text-align: justify;
        }


        .custom-swal-titleLivianos {
            font-size: 23px !important;
            font-weight: bold;
        }

        .swal2-actions {
            align-content: center !important;
            margin: 0 !important;
        }

        .custom-swal-confirm-button20 {
            font-size: 16px !important;
            height: 50px;
            width: 150px;
            border-radius: 10px !important;
        }

        #pTableModal {
            font-size: 12px !important;
        }

        .swal2-icon_monto {
            width: 60px !important;
            height: 60px !important;
            border: 3px solid #f8bb86 !important;
        }

        .custom-swal-actions-livianos {
            padding-bottom: 25px !important;
        }

    }

    @media (min-width: 769px) and (max-width: 992px) {
        #tableModal td {
            text-align: center;
            font-size: 15px;
        }

        .custom-swal-alertaMontoLivianos {
            display: flex;
            flex-direction: column;
            width: 60% !important;
            padding: 20px 30px 20px 30px !important;
            gap: 10px;
            border-radius: 15px !important;
        }

        #tdAsegurado {
            width: 330px;
            text-align: center;
            font-size: 16px;
        }

        #tdCondiciones {
            width: 335px;
            text-align: center;
            font-size: 16px;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
            padding-right: 17px;
            padding-left: 17px;
            padding-bottom: 20px;
            align-items: center;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container>p {
            font-size: 17px;
            padding-top: 20px;
            text-align: justify;
        }


        .custom-swal-titleLivianos {
            font-size: 23px !important;
            font-weight: bold;
        }

        .swal2-actions {
            align-content: center !important;
            margin: 0 !important;
        }

        .custom-swal-confirm-button20 {
            font-size: 16px !important;
            height: 50px;
            width: 150px;
            border-radius: 10px !important;
        }

        #pTableModal {
            font-size: 12px !important;
        }

        .swal2-icon_monto {
            width: 60px !important;
            height: 60px !important;
            border: 3px solid #f8bb86 !important;
        }

        .custom-swal-actions-livianos {
            padding-bottom: 25px !important;
        }

    }

    @media (min-width: 993px) and (max-width: 1200px) {
        #tableModal td {
            text-align: center;
            font-size: 14px;
        }

        .custom-swal-alertaMontoLivianos {
            display: flex;
            flex-direction: column;
            width: 50% !important;
            padding: 0px 12px 0px 12px !important;
            /* gap: 10px; */
            border-radius: 15px !important;
        }

        #tdAsegurado {
            width: 330px;
            text-align: center;
            font-size: 15px;
        }

        #tdCondiciones {
            width: 335px;
            text-align: center;
            font-size: 15px;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
            padding-top: 3px;
            padding-right: 17px;
            padding-left: 17px;
            padding-bottom: 5px;
            align-items: center;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container>p {
            margin: 0px;
            font-size: 15px;
            padding-top: 0px;
            text-align: justify;
        }


        .swal2-actions {
            align-content: center !important;
            margin: 0 !important;
        }

        .custom-swal-confirm-button20 {
            font-size: 16px !important;
            height: 50px;
            width: 150px;
            border-radius: 10px !important;
        }

        .custom-swal-titleLivianos {
            font-size: 20px !important;
            padding: 3px;
            font-weight: bold;
        }

        #pTableModal {
            font-size: 12px !important;
        }

        .swal2-icon_monto {
            margin-top: 10px;
            width: 60px !important;
            height: 60px !important;
            border: 3px solid #f8bb86 !important;
        }

        .custom-swal-actions-livianos {
            padding-bottom: 10px !important;
        }

    }

    @media (min-width: 1200px) and (max-width: 1440px) {
        #tableModal td {
            text-align: center;
            font-size: 12px;
        }

        .custom-swal-alertaMontoLivianos {
            display: flex;
            flex-direction: column;
            width: 43% !important;
            padding: 20px 20px 20px 20px !important;
            /* gap: 10px; */
            border-radius: 15px !important;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        #tdAsegurado {
            width: 330px;
            text-align: center;
            font-size: 15px;
        }

        #tdCondiciones {
            width: 335px;
            text-align: center;
            font-size: 15px;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
            padding-top: 3px;
            padding-right: 50px;
            padding-left: 50px;
            padding-bottom: 5px;
            align-items: center;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container>p {
            margin: 0px;
            font-size: 15px;
            padding-top: 0px;
            text-align: justify;
        }


        .swal2-actions {
            align-content: center !important;
            margin: 0 !important;
        }

        .custom-swal-confirm-button20 {
            font-size: 16px !important;
            height: 50px;
            width: 150px;
            border-radius: 10px !important;
        }

        .custom-swal-titleLivianos {
            font-size: 20px !important;
            padding-bottom: 8px;
            font-weight: bold;
        }

        #pTableModal {
            font-size: 12px !important;
        }

        .swal2-icon_monto {
            margin-top: 10px;
            width: 60px !important;
            height: 60px !important;
            border: 3px solid #f8bb86 !important;
        }

        .custom-swal-actions-livianos {
            padding-top: 7px !important;
            padding-bottom: 0px !important;
        }

    }

    @media (min-width: 1441px) {

        #tableModal {
            text-align: center;
        }

        #tableModal td {
            text-align: center;
            font-size: 14px;
        }

        .custom-swal-alertaMontoLivianos {
            display: flex !important;
            flex-direction: column;
            width: 35% !important;
            padding: 0px 27px 0px 27px;
            border-radius: 15px !important;
        }

        #tdAsegurado {
            width: 260px;
            font-size: 14px;
        }

        #tdCondiciones {
            width: 260px;
            font-size: 14px;
        }

        .custom-swal-titleLivianos {
            font-size: 18px !important;
            padding: 3px;
            font-weight: bold;
        }

        #pTableModalPesados {
            font-size: 13px !important;
            margin: 0;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container {
            display: flex !important;
            flex-direction: column;
            padding-right: 10px;
            padding-left: 10px;
            align-items: center;
        }

        .custom-swal-alertaMontoLivianos .swal2-html-container>p {
            font-size: 19px;
            padding-top: 10px;
            text-align: justify;
        }

        .swal2-actions {
            align-content: center !important;
            margin: 0 !important;
        }

        .custom-swal-confirm-button20 {
            font-size: 16px !important;
            height: 50px;
            width: 150px;
            border-radius: 10px !important;
            padding-bottom: 17px;
        }

        .swal2-icon_monto {
            margin-top: 14px;
            width: 90px !important;
            height: 90px !important;
            border: 3px solid #f8bb86 !important;
        }

        .custom-swal-actions-livianos {
            padding-bottom: 10px !important;
        }

        .swal2-icon-content {
            font-size: 65px !important;
        }

    }

    .btnConfirm {
        background: #88d600;
    }

    .form-coti {
        padding-top: 25px;
    }

    .divsButtonsModals {
        display: flex;
        flex-direction: row;
        gap: 10px;
    }

    .buttonsModal {
        height: 40px;
        width: 100%;
    }

    .no-close .ui-dialog-titlebar-close {
        display: none;
    }

    .ui-dialog-buttonset {
        width: 100%;
        display: flex !important;
        justify-content: space-between;
    }

    .ui-dialog-buttonset>button:first-child {
        background-color: #88d600 !important;
        border: 0 !important;
        border-radius: 5px;
        width: 150px;
        height: 30px;
        color: white;
        margin-left: 14px
    }

    .ui-dialog-buttonset>button:nth-child(2) {
        background-color: #88d600 !important;
        border: 0 !important;
        border-radius: 5px;
        width: 150px;
        height: 30px;
        color: white;
    }

    .ui-dialog .ui-dialog-title {
        text-align: center;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    .ui-dialog .ui-dialog-content {
        padding-top: 20px;
    }

    .center-btn {
        margin: 0 auto;
        display: block;
    }

    .ui-dialog-buttonset>button:first-child {
        background-color: #000000 !important;
        border: 0 !important;
        border-radius: 5px;
        width: 150px;
        height: 30px;
        color: white;
        margin-left: 14px
    }

    #btn-cerrar-fasecolda {
        background: #000000;
    }

    .ui-dialog-buttonset>button:nth-child(2) {
        background-color: #88d600 !important;
        border: 0 !important;
        border-radius: 5px;
        width: 150px;
        height: 30px;
        color: white;
    }
</style>

<div class="content-wrapper">

    <section class="content-header">

        <h1>

            Cotizar Seguro Autos Livianos Particular Familiar

        </h1>

        <ol class="breadcrumb">

            <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio</a></li>

            <li class="active">Selección de modulo</li>

        </ol>

    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
              


            </div>
        </div>
    </section>

</div>

<script src="vistas/js/cotizar.js?v=<?php echo (rand()); ?>"></script>
<script src="vistas/js/functionsViews.js?v=<?php echo (rand()); ?>"></script>


<?php

$eliminarCotizacion = new ControladorCotizaciones();
$eliminarCotizacion->ctrEliminarCotizacion();

?>