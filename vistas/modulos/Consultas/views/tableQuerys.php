<style>
    .swal2-custom-zindex {
        z-index: 9999 !important;
        /* Mayor que el modal */
    }

    /* Hacer que el encabezado del modal sea fijo */
    .ui-dialog .ui-dialog-titlebar {
        position: sticky !important;
        /* Mantenerlo fijo en la parte superior */
        top: 0 !important;
        /* Asegurarse de que esté pegado a la parte superior */
        z-index: 1 !important;
        /* Mantenerlo sobre el contenido desplazable */
        background: #88d600 !important;
        /* Fondo del encabezado */
        color: white !important;
        /* Color del texto */
        padding: 10px !important;
        /* Espaciado interno */
        border-bottom: 1px solid #ddd !important;
        /* Línea divisoria */
    }

    .ui-dialog .ui-dialog-titlebar-close {
        position: absolute;
        right: 0.6em;
        top: 46%;
        width: 30px;
        margin: -13px 0 0 0;
        padding: 1px;
        height: 20px;
    }

    /* Contenedor principal del modal */
    .ui-dialog {
        max-height: 100vh !important;
        /* Altura máxima del modal */
        overflow: hidden !important;
        /* Ocultar scroll externo */
        padding: 0 !important;
        /* Eliminar padding innecesario */
    }

    /* Hacer desplazable solo el contenido debajo del encabezado */
    .ui-dialog .ui-dialog-content {
        max-height: calc(100vh - 160px) !important;
        /* Restar el alto del encabezado */
        overflow-y: auto !important;
        /* Habilitar scroll vertical */
        padding: 15px;
        /* Opcional: Espaciado interno */
    }

    /* Contenedor de los botones */
    .ui-dialog .ui-dialog-buttonpane {
        position: sticky !important;
        /* Fijo en la parte inferior del área desplazable */
        bottom: 0 !important;
        /* Pegado al fondo */
        z-index: 1 !important;
        /* Mantenerlo sobre el contenido */
        background: white !important;
        /* Fondo de los botones */
        /* Línea divisoria */
        /* padding: 10px !important; */
        /* Espaciado interno */
    }

    /* Estilo del scrollbar en el contenido desplazable */
    .ui-dialog-content::-webkit-scrollbar {
        width: 5px !important;
        /* Ancho del scroll vertical */
    }

    .ui-dialog-content::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        /* Fondo claro del track */
        border-radius: 5px !important;
        /* Bordes redondeados */
    }

    .ui-dialog-content::-webkit-scrollbar-thumb {
        background: #888 !important;
        /* Color del thumb */
        border-radius: 5px !important;
        /* Bordes redondeados */
    }

    .ui-dialog-content::-webkit-scrollbar-thumb:hover {
        background: #555 !important;
        /* Color más oscuro al hacer hover */
    }


    .top {
        display: flex;
        justify-content: space-between;
        /* Distribuye el espacio entre los elementos */
        align-items: center;
        /* Alinea los elementos verticalmente */
        width: 100%;
        /* Asegura que ocupe el espacio completo */
    }

    /* Agrupa el botón de Excel y el selector en una sola sección */
    .top .dt-buttons {
        display: flex;
        align-items: center;
        /* Alinea verticalmente el contenido del botón de Excel */
        margin-right: 10px;
        /* Espacio a la derecha */
    }

    /* Asegura que el campo de búsqueda esté al final */
    .top .dataTables_filter {
        margin-left: auto;
        /* Empuja el buscador al final */
    }

    /* Espaciado entre el selector y el botón */
    .top .dataTables_length {
        margin-right: 10px;
        /* Añadir espacio entre el selector y el botón de Excel */
    }

    .btnConsultar,
    .btnCancelar {
        margin-top: 26px;
    }

    .select2-container .select2-selection--single {
        height: 34px !important;
        /* Ajusta este valor según el tamaño deseado */
        line-height: 30px;
        /* Alinea el texto verticalmente */
    }


    /* Elimina completamente la barra de título del modal */
    .custom-dialog2 .ui-dialog-titlebar {
        display: block;
    }

    /* Personaliza el cuadro del modal */
    .custom-dialog2 {
        background-color: white;
        /* Fondo blanco */
        /* Borde gris claro */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Sombra suave */
        /* padding: 20px; */
        /* Espaciado interno */
        /* border-radius: 8px; */
        /* Bordes redondeados opcionales */
    }

    /* Botones del modal en la parte inferior */
    .custom-dialog2 .ui-dialog-buttonpane {
        /* Centra los botones */
        background: none;
        /* Sin color de fondo en el contenedor de botones */
        border-top: none;
        /* Sin borde superior */
    }

    .custom-dialog2 .ui-dialog-buttonpane .ui-dialog-buttonset {
        float: none !important;
        text-align: center !important;
    }

    .custom-dialog2 .ui-dialog-buttonset button {
        /* margin-top: 60px; */
        padding: 10px 20px;
        background-color: #007BFF;
        /* Azul para botón aceptar */
        color: white;
        border: none;
        cursor: pointer;
    }

    .custom-dialog2 .ui-dialog-titlebar-close {
        font-size: 18px;
        color: white;
        border: none;
        background: #88d600;
    }

    #closeButtonModal {
        /* padding: 0 !important; */
        margin: 0 !important;
        font-weight: 100;
        border: 1px solid white;
        padding: 0 5px 0 5px !important;
        border-radius: 4px;
    }

    #closeButtonModal:hover {
        /* padding: 0 !important; */
        scale: 1.1;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .custom-dialog2 .ui-widget-header {
        border: 0;
    }

    .custom-dialog2 .ui-resizable-handle {
        /* position: absolute; */
        /* font-size: 0.1px; */
        /* display: block; */
        -ms-touch-action: none;
        touch-action: none;
    }

    .ui-dialog {
        width: 100% !important;
        /* Ocupar todo el ancho disponible */
        max-width: 600px;
        /* Ancho máximo del modal */
        position: fixed !important;
        /* Fijar el modal en relación al viewport */
        top: 50%;
        /* Centrar verticalmente */
        left: 50%;
        /* Centrar horizontalmente */
        transform: translate(-50%, -50%);
        /* Ajustar la posición para centrar exactamente */
        margin: 0;
        /* Elimina márgenes adicionales */
        z-index: 9999;
        /* Asegura que el modal esté por encima de otros elementos */
        max-height: 100vh;
        /* Limita la altura al 90% del viewport */
        overflow-y: auto;
        /* Habilita el scroll vertical si el contenido es extenso */
    }

    .custom-dialog2 .ui-dialog-content {
        max-height: calc(80vh - 150px);
        /* Ajusta el contenido según el viewport */
        overflow-y: auto;
        /* Habilita el scroll dentro del contenido */
        padding: 0 !important;
        /* Elimina el padding del contenido */
    }

    .ui-dialog-titlebar {
        padding: 0;
        /* Elimina el padding del encabezado */
    }

    .custom-dialog2 .ui-dialog .col-lg-12 {
        padding: 0;
        /* Elimina márgenes adicionales del contenido */
    }

    .custom-dialog2 {
        width: 90%;
        /* Se adapta al contenedor */
        box-sizing: border-box;
        /* Incluye padding y borde en el ancho total */
    }

    #btnGuardar {
        background-color: #88d600;
        min-width: 100px;
    }

    #btnCerrar {
        background-color: black;
        min-width: 100px;
        margin-right: 36px;
    }

    .custom-dialog2 .ui-dialog-title {
        color: white;
        font-weight: 600;
        font-size: 13px;
        padding-left: 15px;
    }


    .custom-dialog2 .ui-dialog-titlebar {
        padding: 1.0em 1em;
        position: relative;
    }

    .custom-dialog2 .ui-corner-all,
    .ui-corner-bottom,
    .ui-corner-right,
    .ui-corner-br {
        border-bottom-right-radius: 0px;
    }

    .custom-dialog2 .ui-corner-all,
    .ui-corner-bottom,
    .ui-corner-left,
    .ui-corner-bl {
        border-bottom-left-radius: 0px;
    }

    .ui-widget-content {
        border: 0;
    }

    #myModal2 {
        padding: 11px 48px 0px 48px !important;
    }

    .ui-widget.ui-widget-content {
        border: 0;
    }

    .ui-dialog {
        padding: 0;
    }

    .no-resize {
        /* resize: vertical; Permite cambiar solo la altura, no el ancho */
        /* Si deseas deshabilitar completamente el redimensionamiento, usa: */
        resize: none;
        margin: 0 !important;
    }

    .full-width-textarea {
        width: 100%;
        /* Ocupa el 100% del ancho disponible */
        box-sizing: border-box;
        /* Incluye el padding y el borde en el cálculo del ancho */
    }
    
</style>


<div class="container-fluid" id="containerTable" style="margin-top: 20px;">
    <button type="button" class="btn btn-default pull-right" id="daterange-btnCotizacionesSalud">
        <span>
            <i class="fa fa-calendar"></i>
            <?php
            if (isset($_GET["fechaInicialCreacion"])) {
                echo $_GET["fechaInicialCreacion"] . " - " . $_GET["fechaFinalCreacion"];
            } else {
                echo 'Rango de fecha';
            }
            ?></span>
        <i class="fa fa-caret-down"></i>
    </button>
    <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas-negocios-user" width="100%">

            <thead>

                <tr>

                    <th style="font-weight: bold; text-align: center;">N°</th>
                    <th style="font-weight: bold; text-align: center;">Fecha Expedición</th>
                    <th style="font-weight: bold; text-align: center;">No. Poliza</th>
                    <th style="font-weight: bold; text-align: center;">Ramo</th>
                    <th style="font-weight: bold; text-align: center;">Aseguradora</th>
                    <th style="font-weight: bold; text-align: center;">Asegurado</th>
                    <!-- <th style="font-weight: bold; text-align: center;">Genero</th> -->
                    <th style="font-weight: bold; text-align: center;">Placa</th>
                    <th style="font-weight: bold; text-align: center;">Fecha Inicio Vigencia</th>
                    <th style="font-weight: bold; text-align: center;">Fecha Fin Vigencia</th>
                    <th style="font-weight: bold; text-align: center;">Acciones</th>

                </tr>

            </thead>

            <tbody>
                <?php

                require_once "controladores/consultas.controlador.php";

                $documento_asesor = $_SESSION["permisos"]["usu_documento"];

                if (isset($_GET["fechaInicialCreacion"])) {
                    $fechaInicialCreacion = $_GET["fechaInicialCreacion"];
                    $fechaFinalCreacion = $_GET["fechaFinalCreacion"];
                    $respuesta = ControladorConsultas::ctrMostrarNegocios($fechaFinalCreacion, $fechaInicialCreacion, $documento_asesor);
                } else {
                    $fechaActual = new DateTime();

                    // Obtener la fecha de inicio de los últimos 90 días
                    $inicioMes = clone $fechaActual;
                    $inicioMes->modify('-90 days');
                    $inicioMes = $inicioMes->format('Y-m-d');

                    // Obtener la fecha de fin (la fecha actual)
                    $fechaActual->modify('+1 day');
                    $fechaActual = $fechaActual->format('Y-m-d');


                    $respuesta = ControladorConsultas::ctrMostrarNegocios($inicioMes, $fechaActual, $documento_asesor);
                }

                $tipoRamo = ControladorConsultas::ctrGetRamos();
                $aseguradoras = ControladorConsultas::ctrGetInsurers();;

                foreach ($respuesta as $key => $value) {
                    //   <td class="text-center" style="font-size: 14px">' . date('Y/m/d', strtotime($value['fch_nacimiento'])) . '</td>
                    echo '<tr>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['id_poliza'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_exp_poliza'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['no_poliza'] . '</td>
                    <td class="text-center" style="font-sizse: 14px; text-align: center;">' . $tipoRamo[(int)$value['ramo_poliza']["ramo"]] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $aseguradoras[$value['aseguradora_poliza']] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['nombre_completo_asegurado'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['placa_veh_poliza'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_inicio_vig_poliza'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_fin_vig_poliza'] . '</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button id="btnVerPoliza" class="btn btn-primary btnVerPoliza" onclick="abrirDialogoCrear(' . $value['id_poliza'] . ')">Seleccionar</button>';
                    echo '</div>
                    </td>
                  </tr>';
                }

                ?>

            </tbody>

        </table>


    </div>
</div>

<link rel="stylesheet" href="vistas\modulos\Consultas\css\styles.css">
<script src="vistas\modulos\Consultas\js\functions.js?v=<?php echo (rand()); ?>" defer></script>
<!-- use version 0.20.3 -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>