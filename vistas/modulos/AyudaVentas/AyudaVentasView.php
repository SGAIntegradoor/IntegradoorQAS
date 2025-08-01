<?php

if ($_SESSION["permisos"]["Ayudaventas"] != "x" && $_SESSION['rol'] == 2) {

    echo '<script>
  
      window.location = "inicio";
  
    </script>';

    return;
}

if ($_SESSION["permisos"]["AyudaVentasFreelance"] == "x") {
    $formasDePago = "x";
}

// Configurar la localización
$locale = 'es_ES';

// Obtener el nombre del mes en mayúsculas
$formatter = new IntlDateFormatter($locale, IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM');
$nombreMes = strtoupper($formatter->format(new DateTime()));

// Obtener el año actual
$anio = date("Y");

// Crear el nombre del archivo
$nombreArchivo = "COTIZADOR VIGENTE FINESA $nombreMes $anio.xlsx";
?>

<style>
    th {
        border: 0 !important;
        vertical-align: middle !important;
        padding: 20px !important;
    }

    .table-media {
        overflow-x: auto;
    }

    .table th,
    .table td {
        vertical-align: middle;
        /* Puedes usar "top" o "bottom" en lugar de "middle" según tus necesidades */
    }

    .btn.btn-alert {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .text-config {
        font-size: 13px;
    }

    .columna-enlace {
        word-wrap: break-word;
    }

    .tablas-asistencias td {
        max-width: 200px;
        min-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sectionDropdown {
        display: flex;
        flex-direction: row;
    }

    .textDropdown {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-weight: 600;
    }

    .hide {
        display: none;
    }

    .visible {
        display: block;
    }

    .textDropdown:hover {
        cursor: pointer;
    }

    .tabla-ayuda {
        min-width: 1000px
    }

    .table-ayuda-ventas th,
    .table-ayuda-ventas td {
        text-align: center;

    }

    ol.olEmision li::marker {
        font-weight: bold;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #e4e4e4;
    }

    .table-bordered>tbody>tr>td {
        /* border: 1px solid #d2d2d2; */
        border-top: 1px solid #f4f4f4;
    }

    #bodyAyudaVentas {
        font-size: 13px;
    }

    #bodyAyudaVentas span {
        font-size: 13px !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Ayuda Ventas</h1>

        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Ayuda Ventas</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">


                <div style="text-align: right !important; margin-right: 2%">
                    <p id="fech_ult">
                        <b>Fecha ultima actualización: 14/04/2025</b>
                    </p>
                </div>

                <!-- Form -->
                <form action="javascript:void(0);" class="form-editar-ayuda-venta" style="display: none; ">

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 ">
                            <h4>Centros de inspección</h4>
                            <div id="centros_de_inspeccion"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <h4>Continuidades</h4>
                            <div id="continuidades"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <h4>Formas De Pago</h4>
                            <div id="formas_de_pago"></div>
                        </div>
                    </div>

                    <input type="hidden" id="aseguradora">
                    <input type="hidden" id="id_ayuda_venta">
                    <!-- <input type="hidden" id="rol" value="<?php echo $_SESSION["rol"]; ?>"> -->
                    <input type="hidden" id="rol" value="<?php echo $formasDePago; ?>">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Linea de atención</label>
                            <input type="text" class="form-control" id="linea_atencion">
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12 ">
                            <label>Link Clausulado</label>
                            <input type="text" class="form-control" id="clausulado">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Sarlaft PN</label>
                            <input type="file" class="form-control" id="sarlaft">
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Sarlaft PJ</label>
                            <input type="file" class="form-control" id="sarlaft2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Continuidad</label>
                            <input type="text" class="form-control" id="continuidad">
                            <button id="agregarContinuidad">Agregar Continuidad</button>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Centro de inspección</label>
                            <input type="text" class="form-control" id="centro_inspeccion">
                            <button id="agregarCentroDeInspeccion">Agregar Centro</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Formas de pago</label>
                            <input type="text" class="form-control" id="forma_de_pago">
                            <button id="agregarFormaDePago">Agregar Forma De Pago</button>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Tips de expedición</label>
                            <input type="text" class="form-control" id="tips_expedicion">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <button id="editarAyudaVenta" class="btn btn-primary">
                                Editar
                            </button>
                        </div>
                    </div>
                </form>
                <!-- END Form -->


                <div class="table-media" style="max-height: 550px; overflow-y: auto; overflow-x: auto;">
                    <table class="tabla-ayuda table table-striped table-bordered" style="width: 100%; border-collapse: collapse; ">

                        <thead style="background: #88d600; color: #FFF; position: sticky; top: 0; z-index: 10; ">
                            <tr>
                                <th style="width: 500px; text-align: center">Aseguradora</th>
                                <th style="width: 4000px; text-align: center">Proceso de emisión autos (AC Analista Comercial / AF Asesor Freelance)</th>
                                <!-- <th style="width: 7%; text-align: center">Sarlaft PJ</th> -->
                                <th style="width: 500px; text-align: center">Politicas de Asegurabilidad: Antiguedad, Continuidad y Centros de Inspección</th>
                                <th style="text-align: center">Políticas cambio de intermediario</th>
                                <th style="text-align: center">Formas de pago</th>
                                <?php
                                // if($_SESSION["permisos"]["ayudaventas_freelance"] == "x"){
                                //     echo '<th style="width: 15%; text-align: center">Formas de pago</th>';
                                // }
                                // if ($_SESSION["permisos"]["Editarinformaciondelayudaventas"] == "x") {
                                //     echo '<th style="text-align: center">Editar</th>';
                                // }
                                // 
                                ?>
                            </tr>
                        </thead>
                        <tbody id="bodyAyudaVentas">
                            <tr style="height: 100%;">
                                <td style="padding-top: 0px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px; justify-content: center;">
                                        <img src="vistas/img/logos/allianz.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#265</p>
                                        </div>
                                        <br>
                                        <br>
                                        <a href="https://allianzfcc.co/#/login" target="_blank">Sarlaft Digital</a>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.allianz.co/seguros/vehiculos/Autos.html" target="_blank">https://www.allianz.co/seguros/vehiculos/Autos.html</a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside; width: 716px !important;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> AF debe diligenciar datos en este <a style="font-weight: bold;" href="https://allianzfcc.co/#/login" target="_blank">link</a>. El cliente realiza el proceso de validación de identidad y recibe al correo confirmación en PDF. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se realiza pre-emisión de la póliza y se carga orden de inspección física o virtual según políticas. La física se hace en Colserautos y la virtual se envia por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se desbloquea la póliza y se descarga el PDF. Si es financiada se debe realizar proceso de financiación con Finesa.</li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación. Si requiere cazador, AF le informa al cliente.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago y, si aplica, la legalización del crédito y la instalación del cazador.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; text-align: justify; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 35 años</li>
                                        <li>Vehículos pesados 25 años</li>
                                        <li>Motos 10 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 10 años para particulares</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad</li>
                                        <li>No haber presentado siniestros en el ultimo año</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Colserautos - <a href="https://www.colserauto.com/sedes" target="_blank">https://www.colserauto.com/sedes</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none; width: 350px !important;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 3 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 3 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.
                                            Cambios despues del vencimiento se pueden autorizar como excepción.
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px; width: 300px !important;">
                                    <ul style="list-style:none; width: 300px !important;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiación con Finesa</li>
                                        <li>✅ Más informacion <a href="https://www.allianz.co/clientes/todos-los-clientes/pagos.html" target="_blank">AQUÍ</a></li>
                                    </ul>

                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/axa.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#247</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;">
                                                <a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft/3_AXA_Sarlaft.pdf" target="_blank">Sarlaft PN</a></button>
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft2/3_AXA_Sarlaft2.pdf " target="_blank">Sarlaft PJ</a></button>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.axacolpatria.co/es/portalpublico-lf/personas/seguro-autos/autos#coberturas" target="_blank">https://www.axacolpatria.co/es/portalpublico-lf/personas/seguro-autos/autos#coberturas</a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Solo se realiza físicamente por computador o a mano y se pone firma manuscrita. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión. </li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Solicitar confirmación de forma de pago para emitir. Si es financiada se debe realizar proceso de financiación con Finesa. Si el cliente cambia la forma de pago, la póliza debe cancelarse y emitirse de nuevo.</li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 15 años (renovación hasta 20 años)</li>
                                        <li>Vehiculos pesados 25 años (renovación hasta 30 años)</li>
                                        <li>Motos 5 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 15 años para particulares</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad</li>
                                        <li>No haber presentado siniestros en los últimos 3 años</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 3 días hábiles antes del vencimiento.</li>
                                        <li>✅ Tiempo de autorización: 3 días hábiles.</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiación directa con Colpatria (Finanseguro)</li>
                                        <li>✅ Financiacion con Finesa como tomador</li>
                                        <li>✅ Más informacion <a href="https://www.axacolpatria.co/documents/42201273/77996558/medios-pago-axa-colpatria.pdf" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/bolivar.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#322</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <p style="color: #337ab7; cursor: pointer;" id="sarlaftBolivar">Sarlaft</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://d9b6rardqz97a.cloudfront.net/wp-content/uploads/2019/10/29095628/AU-112-digital_autos-particulares.pdf" target="_blank">
                                                    https://d9b6rardqz97a.cloudfront.net/wp-content/uploads/2019/10/29095628/AU-112-digital_autos-particulares.pdf</a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Se crea y envía link digital al cliente por SMS (datos requeridos: # cédula y celular). Si el proceso digital falla, se diligencia por computador o a mano y se pone firma manuscrita (Bolívar se toma 1 día hábil para autorizar el físico). Personas PEP solo pueden hacerlo físico. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión. </li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Solicitar confirmación de forma de pago para emitir. Si es financiada se debe realizar proceso de financiación directamente por Bolívar.</li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito (primera cuota).</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 20 años, pero 15 años si valor asegurado es mayor a $200 millones</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 8 años para particulares</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad</li>
                                        <li>No haber presentado siniestros en los últimos 3 años</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 10 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 10 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiación directa con Bolívar</li>
                                        <li>✅ Financiacion con Finesa como tomador</li>
                                        <li>✅ Más informacion <a href="https://transac.segurosbolivar.com/RecaudosElectronicos/faces/muestrapagos.jspx/pages/layout/consultUser.action" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/equidad.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#324</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <a href="https://servicios.laequidadseguros.coop/FormSarlaft/" target="_blank">Sarlaft Digital</a>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.laequidadseguros.coop/productos/seguros-generales/seguros-de-autos-y-rc/auto-protegido/" target="_blank">
                                                    https://www.laequidadseguros.coop/productos/seguros-generales/seguros-de-autos-y-rc/auto-protegido/
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Cliente lo diligencia en <a href="https://servicios.laequidadseguros.coop/FormSarlaft/" target="_blank">este link</a>. Si el proceso digital falla, se diligencia por computador o a mano y se pone firma manuscrita. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se realiza pre-emisión de la póliza y se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se desbloquea la póliza y se descarga el PDF. Si es financiada se debe realizar proceso de financiación con Finesa.</li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 30 años</li>
                                        <li>Vehiculos pesados 10 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 5 años para particulares</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad</li>
                                        <li>No haber presentado siniestros en el ultimo año</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 7 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 6 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://servicios.laequidadseguros.coop/clientes/pagoExterno/consultaPoliza" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/hdi.png" alt="" width="80">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#224</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;">
                                                <a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft/6_HDI_Sarlaft.pdf" target="_blank">Sarlaft PN</a></button>
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft2/6_HDI_Sarlaft2.pdf" target="_blank">Sarlaft PJ</a></button>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.hdiseguros.com.co/personas/seguros-autos/autos" target="_blank">
                                                    https://www.hdiseguros.com.co/personas/seguros-autos/autos
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Solo se realiza físicamente por computador o a mano y se pone firma manuscrita. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se realiza pre-emisión de la póliza y se carga orden de inspección física o virtual según políticas. La física se hace en Ajustev y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se desbloquea la póliza y se descarga el PDF. Si es financiada se debe realizar proceso de financiación con HDI (Financia Ya).</li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente. Si requiere cazador, AF le informa al cliente.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, de contado o primera cuota y la instalación del cazador.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 30 años</li>
                                        <li>Vehiculos pesados 10 años</li>
                                        <li>Motos 10 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 8 años para particulares</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad</li>
                                        <li>No haber presentado siniestros en los ultimos 5 años</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Ajustev - Colserauto - <a href="https://www.hdiseguros.com.co/sites/default/files/2025-01/Centros%20de%20diagn%C3%B3stico%20automotor%20-%20HDI%20Seguros.pdf" target="_blank">https://www.hdiseguros.com.co/sites/default/files/2025-01/Centros%20de%20diagn%C3%B3stico%20automotor%20-%20HDI%20Seguros.pdf</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 3 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 5 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://www.hdiseguros.com.co/pagos" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/mapfre.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#624</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;">
                                                <a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft/8_Mapfre_Sarlaft.pdf" target="_blank">Sarlaft PN</a></button>
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft2/8_Mapfre_Sarlaft2.pdf" target="_blank">Sarlaft PJ</a></button>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.mapfre.com.co/seguros-carros/familiar/" target="_blank">
                                                    https://www.mapfre.com.co/seguros-carros/familiar/
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Solo se realiza físicamente por computador o a mano y se pone firma manuscrita. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Solo se carga orden de inspección virtual por medio de plataforma de Mapfre. Se envía link de inspección al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Solicitar confirmación de forma de pago para emitir. Si es financiada se debe realizar proceso de financiación directamente por Credimapfre.
                                        </li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 20 años</li>
                                        <li>Motos 10 años o 150 mil Km.</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 10 años para particulares.</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad.</li>
                                        <li>No haber presentado siniestros en el ultimo año</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Cismap - <a href="https://www.mapfre.com.co/seguros-co/servicios/cismap/" target="_blank">https://www.mapfre.com.co/seguros-co/servicios/cismap/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 15 días hábiles antes del vencimiento.</li>
                                        <li>✅ Tiempo de autorización: 15 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiación directa con Mapfre (Credimapfre)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://cotiza.mapfre.com.co/pagosWeb/vista/paginas/noFilterIniPagosPublico.jsf" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/mundial.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#935</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;">
                                                <a style="text-decoration: none; color: white;" href="https://app.legops.com/forms/f/92c704c9-1967-4c90-b460-212af6bfa7fd" target="_blank">Sarlaft PN</a></button>
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="text-decoration: none; color: white;" href="https://sarlaft.segurosmundial.com.co/forms/f/9211808c-f920-4af2-8eaf-d50ee3c3140d" target="_blank">Sarlaft PJ</a></button>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.segurosmundial.com.co/soluciones-personales/soluciones-de-movilidad/" target="_blank">
                                                    https://www.segurosmundial.com.co/soluciones-personales/soluciones-de-movilidad/
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Cliente persona natural lo diligencia en <a href="https://app.legops.com/forms/f/92c704c9-1967-4c90-b460-212af6bfa7fd" target="_blank">este link</a> y Jurídico en <a href="https://sarlaft.segurosmundial.com.co/forms/f/9211808c-f920-4af2-8eaf-d50ee3c3140d" target="_blank">este link</a>. Si el proceso digital falla, se diligencia por computador o a mano y se pone firma manuscrita. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se realiza pre-emisión de la póliza y se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se autoriza la emisión y, se descarga el PDF. Si es financiada se debe realizar proceso de financiación con Finesa.
                                        </li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación. Si requiere cazador, AF le informa al cliente.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago y, si aplica, la legalización del crédito y la instalación del cazador.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 15 años</li>
                                        <li>Vehiculos pesados 35 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>No aplica para continuidad, todos los vehículos realizan inspección</li>
                                    </ul>
                                    <b>Continuidad Pesados:</b>
                                    <ul>
                                        <li>Modelos hasta 8 años para pesados.</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad.</li>
                                        <li>No haber presentado siniestros en el ultimo año.</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 3 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 7 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiación directa con Mundial (Mundial Financia)</li>
                                        <li>✅ Más informacion <a href="https://www.segurosmundial.com.co/pagos/" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/previsora.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#345</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="color: white;" href="https://idocumentos-webclient-previsora.azurewebsites.net/?a=autogestion&em=860002400" target="_blank">Sarlaft PN</a></button>
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft2/FORMATO DE CONOCIMIENTO DEL CLIENTE.xlsx" target="_blank">Sarlaft PJ</a></button>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.previsora.gov.co/web/personas/seguro-de-automoviles" target="_blank">
                                                    https://www.previsora.gov.co/web/personas/seguro-de-automoviles
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Cliente lo diligencia en <a href="https://idocumentos-webclient-previsora.azurewebsites.net/?a=autogestion&em=860002400" target="_blank">este link</a> (solo persona natural). Si el proceso digital falla, se diligencia por computador o a mano y se pone firma manuscrita (Previsora se toma 1 día hábil para autorizar el físico). Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Solicitar confirmación de forma de pago para emitir. Si es financiada se debe realizar proceso de financiación con Finesa. Si el cliente cambia la forma de pago, la póliza debe cancelarse y emitirse de nuevo.
                                        </li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación. Si requiere cazador, AF le informa al cliente.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago y, si aplica, la legalización del crédito y la instalación del cazador.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 16 años</li>
                                        <li>Vehiculos pesados 25 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 12 años para particulares según fecha de matricula</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad.</li>
                                        <li>No haber presentado siniestros en el ultimo año.</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 3 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 2 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://www.previsora.gov.co/web/guest/previpagos" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 325px;">
                                        <img src="vistas/img/logos/sbs.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#360</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;">
                                                <a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft/11_SBS_Sarlaft.pdf" target="_blank">Sarlaft PN</a></button>
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="text-decoration: none; color: white;" href="https://integradoor.com/app/vistas/modulos/AyudaVentas/pdf/sarlaft2/11_SBS_Sarlaft2.pdf" target="_blank">Sarlaft PJ</a></button>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.sbseguros.co/seguros-autos/carros" target="_blank">
                                                    https://www.sbseguros.co/seguros-autos/carros
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Solo se realiza físicamente por computador o a mano y se pone firma manuscrita. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se realiza pre-emisión de la póliza y se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión. </li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se autoriza la emisión y, se descarga el PDF. Si es financiada se debe realizar proceso de financiación con Finesa.
                                        </li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 20 años</li>
                                        <li>Vehiculos pesados 10 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>No aplica para continuidad, todos los vehículos realizan inspección.</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 4 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 2 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.</li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://www.sbseguros.co/servicio-al-cliente/alternativas-pagos" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 325px;">
                                        <img src="vistas/img/logos/solidaria.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#789</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <a href="https://www.solidaria.com.co/WA_DigitalClient/#/login" target="_blank">Sarlaft Digital</a>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://aseguradorasolidaria.com.co/PN-tu-patrimonio/seguros-para-vehiculos/seguro-de-automoviles-particular-hide.aspx" target="_blank">
                                                    https://aseguradorasolidaria.com.co/PN-tu-patrimonio/seguros-para-vehiculos/seguro-de-automoviles-particular-hide.aspx
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Cliente lo diligencia en <a href="https://www.solidaria.com.co/WA_DigitalClient/#/login" target="_blank">este link</a>. Si el proceso digital falla, se diligencia por computador o a mano y se pone firma manuscrita (Solidaria se toma 1 día hábil para autorizar el físico). Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se realiza pre-emisión de la póliza y se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión. </li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se autoriza la emisión y, se descarga el PDF. Si es financiada se debe realizar proceso de financiación con Finesa.
                                        </li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 30 años</li>
                                        <li>Vehiculos pesados 15 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Modelos hasta 7 años para particulares.</li>
                                        <li>Documentos: póliza vigente, Sarlfat, cedula y Tarjeta de propiedad.</li>
                                        <li>No haber presentado siniestros en el ultimo año.</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 5 días hábiles antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 6 días hábiles</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio</li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://aseguradorasolidaria.com.co/pagos/multipago.aspx" target="_blank">AQUÍ</a></li>
                                    </ul>

                                </td>
                            </tr>
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/estado.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#388</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <p style="color: #337ab7; cursor: pointer;" id="sarlaftEstado">Sarlaft</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.segurosdelestado.com/productos/productos/1114" target="_blank">
                                                    https://www.segurosdelestado.com/productos/productos/1114
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Se crea y envía link digital al cliente por correo (datos requeridos: nombre completo, # cédula, celular y correo). Si el proceso digital falla, se diligencia por computador o a mano y se pone firma manuscrita (Estado se toma 1 día hábil para autorizar el físico). Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se carga orden de inspección virtual por medio de plataforma Valora de Seguros del Estado. Se envía link de inspección al cliente. Según siniestralidad e historial en seguros de autos, se requiere inspección física.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se solicita la emisión directamente a la aseguradora (un día hábil). Se descarga el PDF y si es financiada se debe realizar proceso de financiación con Finesa.
                                        </li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 20 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>No aplica para continuidad, todos los vehículos realizan inspección.</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Virtual en la Plataforma Valora
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 7 días hábiles antes del vencimiento.</li>
                                        <li>✅ Tiempo de autorización: 7 días hábiles.</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.</li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://www.segurosdelestado.com/pages/Tips" target="_blank">AQUÍ</a></li>
                                    </ul>

                                </td>
                            </tr>
                            <!-- Zurich -->
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/zurich.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#723</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <p style="color: #337ab7; cursor: pointer;" id="sarlaftZurich">Sarlaft</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.zurichseguros.com.co/lineas-personales/movilidad" target="_blank">
                                                    https://www.zurichseguros.com.co/lineas-personales/movilidad
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Declaración de Renta (si aplica), Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad).</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Si prima mayor a 6 millones o valor asegurado mayor a 120 millones, se realiza físicamente por computador o a mano y se pone firma manuscrita (Zurich se toma 1 día hábil para autorizar el físico). Si prima y valor asegurado son menores, no requiere SARLAFT, solo dirección de residencia y ocupación del cliente.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Se carga orden de inspección física o virtual según políticas. La física se hace en Automas y la virtual se envía por link al cliente. Se informa al AF cuando quede cargada la orden.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF. Si hay cambios en valor asegurado o Código Fasecolda se actualiza cotización y se envía al AF para confirmar emisión.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se solicita la emisión directamente a la aseguradora (un día hábil). Se descarga el PDF y si es financiada se debe realizar proceso de financiación con Finesa.
                                        </li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 15 años</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>Para ciertos casos de acuerdo a parametros automaticos del sistema donde se revisa comportamiento del cliente y siniestralidad.</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Automas - <a href="https://automas.com.co/cobertura/" target="_blank">https://automas.com.co/cobertura/</a>
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible)</li>
                                        <li>✅ Tiempo límite de recepción: 5 días hábiles antes del vencimiento.</li>
                                        <li>✅ Tiempo de autorización: 2 días hábiles.</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.</li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://www.zurichseguros.com.co/es-co/formas-de-pago" target="_blank">AQUÍ</a></li>
                                        <li>✅ Portal de pagos <a href="https://web.zurichseguros.com.co/zcc-pp-web-app/login" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <!-- Fin Zurich -->
                            <!-- Qualitas -->
                            <tr style="height: 100%;">
                                <td style="padding-top: 30px; text-align: center; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; align-items: center; height: 350px;">
                                        <img src="vistas/img/logos/logo-qualitas-secundario.png" alt="" width="105">
                                        <br>
                                        <br>
                                        <div style="width: 100%; text-align: center;">
                                            <span style="font-size: 16px;">Línea de atención:</span>
                                            <p>#963</p>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;">
                                                <a style="text-decoration: none; color: white;" href="" target="_blank">Sarlaft PN</a></button>
                                            <button
                                                class="btn btn-alert"
                                                style="background: red; color: #fff; font-weight: 500; margin-bottom: 5px; width: 90px;"><a style="text-decoration: none; color: white;" href="" target="_blank">Sarlaft PJ</a></button>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <a href="https://conocimientodigital.qualitascolombia.com.co:8443/microsite/" target="_blank">Sarlaft Digital</a>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="text-align: center; display: flex; flex-direction: column;">
                                            <span>Clausulado</span>
                                            <button class="btn btn-alert" style="border-color: #88d600; width: 160px; color: #88d600; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <a style="text-decoration: none; color: #88d600;" href="https://www.qualitascolombia.com.co/documents/20125/596596/1352-P-03-AUTOLIVIANOPER03-D00I.pdf/be192050-5914-b2b7-4dc1-d1f3b70479ef?t=1745351030407" target="_blank">
                                                    https://www.qualitascolombia.com.co/documents/20125/596596/1352-P-03-AUTOLIVIANOPER03-D00I.pdf/be192050-5914-b2b7-4dc1-d1f3b70479ef?t=1745351030407
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 30px; text-align: justify;">
                                    <ol class="olEmision" style="padding-left: 10px; list-style-position: inside;">
                                        <li style="margin-bottom: 5px;"><b>Documentos:</b> TP, Cédula, Contrato de Compraventa (si aplica), Factura (0 km), Póliza anterior (si hay continuidad). Declaración de renta (si aplica)</li>
                                        <li style="margin-bottom: 5px;"><b>SARLAFT:</b> Cliente lo diligencia en este link. Si el vehículo tiene un valor asegurado mayor a 180 SMMLV, debe realizar sarlaft ordinario (lo selecciona en la sección 3 del Sarlaft Virtual). De lo contrario, debe seleccionar sarlaft simplificado. Si el proceso digital falla, se diligencia por computador o a mano y se pone firma manuscrita. Siempre se debe enviar el PDF al AC.</li>
                                        <li style="margin-bottom: 5px;"><b>Enviar docs y confirmar forma de pago:</b> Si están completos pasan; si no, se devuelven. 0 km o en continuidad pasan a emisión; los demás, a inspección.</li>
                                        <li style="margin-bottom: 5px;"><b>Orden de inspección:</b> Solo se carga orden de inspección virtual por medio de plataforma de Qualitas. Se envía link de inspección al celular del cliente.</li>
                                        <li style="margin-bottom: 5px;"><b>Inspección:</b> Se completa y el AF confirma a su AC, de lo contrario, no se puede emitir.</li>
                                        <li style="margin-bottom: 5px;"><b>Validar inspección:</b> Según el resultado de la inspección, se informa al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Emisión y Financiación:</b> Se realiza la emisión y se descarga el PDF. Si es financiada se debe realizar proceso de financiación con Finesa.</li>
                                        <li style="margin-bottom: 5px;"><b>Revisión y envío:</b> AC verifica y envía la póliza al AF.</li>
                                        <li style="margin-bottom: 5px;"><b>Entrega:</b> AF entrega póliza al cliente y, si aplica, el cupón de pago de la financiación.</li>
                                        <li style="margin-bottom: 5px;"><b>Seguimiento:</b> AF hace seguimiento al pago, si aplica, la legalización del crédito.</li>
                                    </ol>
                                </td>
                                <td style="padding: 30px; vertical-align: top;">
                                    <b>Antigüedad Máxima:</b>
                                    <ul>
                                        <li>Vehiculos livianos 25 años.</li>
                                    </ul>

                                    <b>Continuidad Livianos:</b>
                                    <ul>
                                        <li>No aplica para continuidad, todos los vehículos realizan inspección.</li>
                                    </ul>

                                    <b>Centros de Inspección:</b>
                                    <br>
                                    <ul>
                                        <li>
                                            Virtual en la Plataforma Qualitas
                                        </li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Doc.: Carta, Sarlaft, Cédula, Tarjeta de Propiedad y Condiciones de Renovación (de ser posible).</li>
                                        <li>✅ Tiempo límite de recepción: 15 días calendario antes del vencimiento</li>
                                        <li>✅ Tiempo de autorización: 3 días antes de terminar la vigencia</li>
                                        <li>✅ Nota: No puede estar recaudada la renovación para autorizar cambio.</li>
                                    </ul>
                                </td>
                                <td style="padding: 30px;">
                                    <ul style="list-style: none;">
                                        <li>✅ Bancos o corresponsales bancarios</li>
                                        <li>✅ Pago en linea (PSE o Tarjeta de crédito)</li>
                                        <li>✅ Financiacion con Finesa</li>
                                        <li>✅ Más informacion <a href="https://www.qualitascolombia.com.co/web/qco/medios-de-pago" target="_blank">AQUÍ</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <!-- Fin Qualitas -->
                        </tbody>
                    </table>
                </div>
                <br>
                <p>* PN: Persona Natural. PJ: Persona Jurídica</p>
                <div style="margin-left: 1em; padding-bottom: 1em;">
                    <div class="sectionDropdown" id="dropdownbtn">
                        <div id="svgDropdown" style="margin-right: 5px; margin-left: 0">
                            <img class="textDropdown" src="vistas/img/arrowright.png" alt="" width="13" height="13">
                        </div>
                        <p class="textDropdown">Sarlaft Superintendencia Financiera</p>
                    </div>
                    <div id="boxDropdown" class="hide">
                        <div style="display: flex; flex-direction: row;">
                        </div>
                        <b>Sarlaft general PN:</b>
                        <button class="btn btn-primary" id="safGenNat" style="background: red; color: #fff; font-weight: 500;">PDF</button>

                        <?php if ($_SESSION["permisos"]["Editarinformaciondelayudaventas"] == "x") {
                            echo '<button class="btn btn-primary" style="font-weight: 500;" id="btn_edit_generic1">Editar</button>';
                        } else {
                            echo '<div style="display: none;"><button class="btn btn-primary" style="font-weight: 500;" id="btn_edit_generic1">Editar</button></div>';
                        } ?>

                        <p>
                        <form action="javascript:void(0);" class="form-editar-generic1" style="display: none; ">
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" class="form-control" id="sarlaftGeneric1">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <button id="editargeneric1" class="btn btn-primary">Editar</button>
                                </div>
                            </div>
                        </form>
                        </p>
                        <p>
                            <b>Sarlaft general PJ:</b>
                            <button class="btn btn-primary" id="safGenJur" style="background: red; color: #fff; font-weight: 500; margin-left: 5px">PDF</button>

                            <?php if ($_SESSION["permisos"]["Editarinformaciondelayudaventas"] == "x") {

                                echo '<button class="btn btn-primary" style="font-weight: 500;" id="btn_edit_generic2">Editar</button>';
                            } else {
                                echo '<div style="display: none;"><button class="btn btn-primary" style="font-weight: 500;" id="btn_edit_generic2">Editar</button></div>';
                            } ?>
                        </p>
                        <p>
                        <form action="javascript:void(0);" class="form-editar-generic2" style="display: none; ">
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" class="form-control" id="sarlaftGeneric2">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <button id="editargeneric2" class="btn btn-primary">Editar</button>
                                </div>
                            </div>
                        </form>
                        </p>
                    </div>
                    <div id="dropdownbtn1" class="sectionDropdown">
                        <div id="svgDropdown1" style="margin-right: 5px; margin-left: 0">
                            <img class="textDropdown" src="vistas/img/arrowright.png" alt="" width="14" height="14">
                        </div>
                        <p class="textDropdown">Formatos Financieras</p>
                    </div>
                    <div id="boxDropdown1" class="sectionDropdown hide">
                        <div style="display: flex; flex-direction: row; align-items: center; gap: 5px;">
                            <p style="font-weight: bold;">Financiación Finesa: </p>
                            <a href="vistas/modulos/AyudaVentas/pdf/pdf-finesa/<?php echo $nombreArchivo; ?>" download="<?php echo $nombreArchivo; ?>">
                                <img src="vistas/img/excelIco.png" style="margin-bottom: 6px;" />
                            </a>
                            </img>
                        </div>
                    </div>
                    <div id="dropdownbtn2" class="sectionDropdown">
                        <div id="svgDropdown2" style="margin-right: 5px; margin-left: 0">
                            <img class="textDropdown" src="vistas/img/arrowright.png" alt="" width="14" height="14">
                        </div>
                        <p class="textDropdown">Guias Fasecolda</p>
                    </div>
                    <div id="boxDropdown2" class="sectionDropdown hide">
                        <div style="display: flex; flex-direction: row; align-items: center; gap: 5px;">
                            <a href="vistas/modulos/AyudaVentas/pdf/fasecolda/Guia_Excel_343 - Formato Unico.xlsx" download="Guia_Excel_343 - Formato Unico.xlsx">
                                <div style="display: flex; flex-direction: row; align-items: center; gap: 7px;">
                                    <img src="vistas/img/excelIco.png" />
                                    <p style="margin-bottom: 0px !important;">Fecha de Cargue: 22/04/2025</p>
                                </div>
                            </a>
                            </img>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script src="./vistas/modulos/AyudaVentas/ayuda-ventas.js?v=<?php echo (rand()); ?>"></script>