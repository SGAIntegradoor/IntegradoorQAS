<link rel="stylesheet" href="vistas/modulos/soat/css/styles.css">
<section class="content">
    <div id="loading-screen">
        <!-- <div class="loader"></div> -->
        <img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong>Procesando...</strong>
    </div>
    <label style="padding: 10px;" id="lblDataTrip2Top">Ingresa información para cotizar</label>
    <div class="box">
        <div class="box-body">

            <div id="formularioResumen">
                <!-- FORMULARIO RESUMEN PLACA -->
                <form method="Post" id="formResumAseg">
                    <div id="resumenAsegurado">
                        <div class="col-lg-12" id="headerAsegurado">
                            <div class="row row-aseg">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="">Información para cotizar y confirmación de datos</label>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-2">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div id="masAsegurado">
                                        <p id="masA" onclick="masAsegSoat();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                                    </div>
                                    <div id="menosAsegurado">
                                        <p id="menosA" onclick="menosAsegSoat();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="DatosAsegurado">
                            <div class="col-lg-12 form-resumAseg">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-6 col-md-3" id="contenSuperiorPlaca">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="conocesPlaca">
                                                <label>Vehículo 0 KM?</label>
                                                <div class="conten-conocesPlaca">
                                                    <label for="Si">No</label>
                                                    <input type="radio" name="conocesPlaca" id="txtConocesLaPlacaSi" value="Si" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label for="No">Si</label>
                                                    <input type="radio" name="conocesPlaca" id="txtConocesLaPlacaNo" value="No" required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenPlaca">
                                                <label for="placaVeh">Placa</label>
                                                <input type="text" minlength="6" maxlength="6" class="form-control" id="placaVeh" required placeholder="Placa">
                                                <input type="hidden" class="form-control" id="intermediario" value="<?php echo $_SESSION["intermediario"]; ?>">
                                                <input type="hidden" class="form-control" id="tipoDocumentoID" value="1">
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-xs-12 col-sm-6 col-md-2 form-group" id="contenBtnConsultarPlaca">
                                        <button type="button" class="btn btn-primary btn-block" id="btnConsultarPlaca">Cotizar</button>
                                    </div>

                                </div>


                                <div id="rowBoton" class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <div id="loaderPlaca"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <!-- FORMULARIO RESUMEN VEHICULO -->
                <form method="Post" id="formResumVeh">
                    <div id="resumenVehiculo">
                        <div class="col-lg-12" id="headerVehiculo" style="display: none;">
                            <div class="row row-veh" style="margin-bottom: 0px;">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="">Confirmación de datos</label>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div id="masVehiculo">
                                        <p id="masVeh" onclick="masVehSoat();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                                    </div>
                                    <div id="menosVehiculo">
                                        <p id="menosVeh" onclick="menosVehSoat();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="DatosVehiculo">
                            <div class="col-lg-12 form-resumVeh">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtClaseVeh">Clase</label>
                                        <input type="text" class="form-control" id="txtClaseVeh" placeholder="" disabled>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtMarcaVeh">Marca</label>
                                        <input type="text" class="form-control classMarcaVeh" id="txtMarcaVeh" placeholder="" disabled>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtModeloVeh">Modelo</label>
                                        <input type="text" class="form-control" id="txtModeloVeh" placeholder="" disabled>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtLinea">Línea</label>
                                        <input type="text" class="form-control classReferenciaVeh" id="txtLinea" placeholder="" disabled>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtServicio">Servicio</label>
                                        <input type="text" class="form-control" id="txtServicio" placeholder="" disabled>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" style="margin-bottom: 0px;">
                                        <div class="col-xs-6 col-sm-6 col-md-6 form-group" style="padding-left: 0px;">
                                            <label for="txtCilindraje">Cilindraje</label>
                                            <input type="text" class="form-control classReferenciaVeh" id="txtCilindraje" placeholder="" disabled>
                                        </div>

                                        <div class="col-xs-6 col-sm-6 col-md-6 form-group" style="padding-right: 0px;">
                                            <label for="txtPasajeros">Pasajeros</label>
                                            <input type="text" class="form-control classReferenciaVeh" id="txtPasajeros" placeholder="" disabled>
                                        </div>
                                    </div>


                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtMotor">Motor</label>
                                        <input type="text" class="form-control classReferenciaVeh" id="txtMotor" placeholder="" disabled>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtChasis">Chasis</label>
                                        <input type="text" class="form-control classReferenciaVeh" id="txtChasis" placeholder="" disabled>
                                        <div id="listaCiudades"></div>
                                    </div>

                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtFechaVencimiento">Fecha inicio proximo SOAT</label>
                                        <input type="text" class="form-control classReferenciaVeh" id="txtFechaVencimiento" placeholder="" disabled>
                                    </div> -->

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="claseVehSoat">Clase vehículo SOAT</label>
                                        <select class="form-control" id="claseVehSoat" required></select>
                                        <input type="hidden" id="nroDocPropietario">
                                        <input type="hidden" id="capacidadCarg">
                                        <input type="hidden" id="codigoClaseVeh">
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="">Servicio de tramite</label>
                                        <div style="display: flex; justify-content: center; align-items: end; gap: 25px;">
                                            <div class="radio">
                                                <label style="margin-right: 5px;">
                                                    <input id="radioSinComision" type="radio" name="servicio">
                                                    Sin comisión:
                                                    <span class="pull-right"> $20.000</span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label style="margin-right: 5px;">
                                                    <input id="radioConComision" type="radio" name="servicio">
                                                    Con comisión
                                                    <span class="pull-right"> $40.000</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="" style="color: white;">.</label>
                                        <button id="btnContinuarCoti" class="btn btn-primary btn-block" style="display: block;">Continuar</button>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="" style="color: white;">.</label>
                                        <button id="btnNuevaCoti" class="btn btn-primary btn-block" style="background-color: black;">Nueva cotización</button>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row" style="margin-top: 5rem;">
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                    <div id="loaderPlacaTwo"></div>
                </div>
            </div>

            <!-- RESUMEN COTIZACIÓN Y NOTAS IMPORTANTES -->
            <div class="containerResumenCoti" style="margin-top: 0px; display:none;">
                <div class="col-lg-12" id="headerVehiculo">
                    <div class="row row-veh" style="margin-bottom: 3rem;">
                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <label for="">Solicitud de Expedición</label>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <div id="masExpedicion">
                                <p style="text-align: end; display: none;" id="masExp" onclick="masExp();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                            </div>
                            <div id="menosExp">
                                <p style="text-align: end;" id="menosExp" onclick="menosExp();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="containerExpedicion" class="row">

                    <!-- Resumen Cotización -->
                    <div id="cardCotizacion" class="col-xs-12 col-sm-6 col-md-4" style="display: flex; flex-direction: column; align-items: end; padding-right: 2rem;">
                        <div class="summary-box" style="width: 100%;">
                            <h4 id="title-resumen-coti" class="summary-title">RESUMEN COTIZACIÓN SOAT XXX000</h4>

                            <div class="clearfix" style="margin-bottom:0px;">
                                <span class="pull-left">Fecha cotización</span>
                                <span id="fechaCoti" class="pull-right ">AAAA-MM-DD</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:0px;">
                                <span class="pull-left">Fecha inicio proximo SOAT</span>
                                <span id="txtFechaVencimiento" class="pull-right ">AAAA-MM-DD</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:0px;">
                                <span class="pull-left">Prima SOAT</span>
                                <span id="PrimaSoat" class="pull-right ">$ -</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:0px;">
                                <span class="pull-left">Contribución Fosyga</span>
                                <span id="contriFosyga" class="pull-right ">$ -</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:0px;">
                                <span class="pull-left">Tasa RUNT</span>
                                <span id="tasaRunt" class="pull-right ">$ -</span>
                            </div>

                            <hr>

                            <div class="clearfix" style="margin-bottom:15px;">
                                <span class="pull-left"><strong>Total SOAT</strong></span>
                                <span id="valorSoat" class="pull-right price">$ -</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:15px;">
                                <span class="pull-left"><strong>Servicio Tramite</strong></span>
                                <span id="servicioTramite" class="pull-right price">$ -</span>
                            </div>

                            <hr>

                            <div class="clearfix">
                                <span style="color: #7AC943; font-size: 18px;" class="pull-left"><strong id="lblTotalPagar">TOTAL A PAGAR:</strong></span>
                                <span id="totalPagarSoat" class="pull-right total">$ -</span>
                            </div>
                        </div>
                        <div style="width: 100%; display: flex; justify-content: center; gap: 2.5rem;">
                            <button id="btnCopiarImagen" onclick="copiarCardComoImagen()" class="btn" style="margin-top: 10px; width: 36%;">
                                <li class="fa fa-copy" style="color: #5c6258;"></li>
                                <span>Copiar</span>
                            </button>
                            <button id="btnDescargarImagen" onclick="descargarCardComoImagen()" class="btn" style="margin-top: 10px; width: 36%;">
                                <li class="fa fa-download" style="color: #5c6258;"></li>
                                <span>Descargar</span>
                            </button>
                        </div>

                    </div>

                    <!-- Notas importantes -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div style="font-size: 12px;">
                            <p class="notes-title">⚠️ Notas importantes antes de realizar el pago:</p>
                            <ul style="padding-left: 5px; list-style-position: inside;">
                                <li>Verifica la información del vehículo en el RUNT (pasajeros, capacidad de carga, clase y tipo de servicio) y confirma que el SOAT esté vencido o dentro de los 29 días previos al vencimiento.</li>
                                <li>El valor del pago debe coincidir exactamente con la cotización generada.</li>
                                <li>El SOAT se expide únicamente a nombre del propietario registrado en la Tarjeta de Propiedad.</li>
                                <li>Envía la información completa y legible: tarjeta de propiedad y datos de contacto del cliente.</li>
                                <li>Solicitudes con errores en RUNT o documentos incompletos serán devueltas para corrección.</li>
                                <li>El SOAT solo se emite con pago confirmado. Pagos desde otros bancos pueden tardar en verse reflejados.</li>
                                <li>Los tiempos de respuesta comienzan a contar desde la confirmación del pago en cuenta.
                                    <ul style="padding-left: 0; list-style-position: inside; margin-top: 5px;">
                                        <li>Sin novedades en RUNT: 1 a 3 horas hábiles.</li>
                                        <li>Con novedades en RUNT: hasta 1 día hábil.</li>
                                        <li>Vehículos 0 km: 1 día hábil (contacto previo obligatorio con el equipo SOAT).</li>
                                    </ul>
                                </li>
                                <li>Aseguradoras: AXA Colpatria, La Previsora, Seguros del Estado o Mundial (según disponibilidad).</li>
                            </ul>

                            <!-- <div class="buttons-container">
                                <button id="btnContinuarCoti" class="btn btn-green">Continuar</button>
                                <button id="btnNuevaCoti" class="btn btn-black">Realizar nueva cotización</button>
                            </div> -->
                        </div>
                    </div>

                    <!-- Resumen222 Cotización -->
                    <div class="col-xs-12 col-sm-6 col-md-5" style="padding-right: 15px;">

                        <div style="margin-top: 25px;">
                            <div class="col-md-6">
                                <label for="">Email tomador SOAT</label>
                                <input class="form-control" type="text" name="" id="correoTomadorSoat">
                            </div>
                            <div class="col-md-6">
                                <label for="">Celular tomador SOAT</label>
                                <input class="form-control" type="text" name="" id="celularTomadorSoat">
                            </div>

                            <div id="contenedor-subir-archivos" class="col-md-6" style="display: flex; flex-direction: column; margin-top: 25px;">
                                <label>Adjuntar soporte de pago y Docs</label>

                                <button type="button" id="btnUpload" class="btn btn-outline-primary">
                                    <i class="fa fa-cloud-upload" style="margin-right:5px;"></i> Añadir archivos
                                </button>

                                <input
                                    type="file"
                                    id="fileInput"
                                    hidden
                                    accept=".pdf,.jpg,.png,.doc,.docx"
                                    style="display: none;">

                                <small class="text-muted d-block mt-1">
                                    Máximo 3 archivos (1 MB cada uno)
                                </small>


                            </div>

                            <div id="contenedor-subir-archivos-preview" class="col-md-6" style="display: flex; flex-direction: column; margin-top: 25px;">
                                <label style="color: white;">subidos</label>
                                <div id="filePreview" class="mt-2"></div>
                            </div>

                        </div>


                    </div>
                        <div class="col-xs-12 col-sm-6 col-md-5" style="padding-right: 15px;">
                            <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenBtnEnviarSolicitud" style="margin-top: 15px;">
                            <button type="button" class="btn btn-primary btn-block" id="btnEnviarSolicitud" style="margin-top: 15px;">Solicitar Expedición</button>
                        </div>
                        </div>
                </div>
            </div>

            <!-- Parte final del formulario -->
            <div class="containerFinalForm " style="display: none; margin-top: 40px;">

                <div class="box-body row" id="section-final" style="display: none;">
                    <div class="col-xs-12 col-sm-12 col-md-6 form-group" id="contenedor-archivos" style="border: 2px solid #e5e5e5; padding: 15px; display: none; border-radius: 20px; justify-content: space-around; flex-wrap: wrap; row-gap: 2rem;"></div>

                    <div class="col-xs-12 col-sm-12 col-md-6 form-group" id="contenComentarios" style="margin-top: 0px;">

                        <div class="col-xs-12 col-sm-12 col-md-12 form-group" style="margin-top: 0px;">
                            <textarea id="txtComentarios" style="width: 100%;" placeholder="Comentarios..."></textarea>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenBtnEstadoAprobar" style="margin-top: 15px;">
                            <button type="button" class="btn btn-primary btn-block" id="btnEstadoAprobar" style="margin-top: 15px;">Aprobar</button>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenBtnEstadoDevolver" style="margin-top: 15px;">
                            <button type="button" class="btn btn-danger btn-block" id="btnEstadoDevolver" style="margin-top: 15px;">Devolver</button>
                        </div>

                        <!-- <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenBtnEstadoAprobar" style="margin-top: 15px;">
                            <button type="button" class="btn btn-primary btn-block" id="btnEstadoAprobar" style="margin-top: 15px;">Enviar</button>
                        </div> -->
                    </div>

                </div>

                <!-- <div class="col-xs-12 col-sm-3 col-md-3 form-group" id="contenBtnEnviarSolicitud" style="margin-top: 15px;">
                    <button type="button" class="btn btn-primary btn-block" id="btnEnviarSolicitud" style="margin-top: 15px;">Solicitar Expedición</button>
                </div> -->
            </div>

            <div id="container-subida-soat" style="display: none;">


                <div class="col-md-3" id="contenedor-subir-soat" style="width: 100%;display: flex; padding: 0px; flex-direction: row-reverse; justify-content: flex-end;">
                    <div class="col-md-2" style="display: flex; flex-direction: column; margin-top: 35px;">
                        <button id="btnSubirSoat" type="button" class="btn btn-primary btn-block" style="margin-top: 15px;">Enviar Soat</button>
                    </div>
                </div>
                <div id="destinoPreview"></div>

            </div>

        </div>
        <div id="contenedor-historial-comentarios" class="content" style="min-height: 0px; display: none;">
            <div class="box">
                <div class="box-body row">
                    <div class="form-group" style="margin-top: 20px;">
                        <label>Historial de comentarios:</label>
                        <div id="historialComentarios" style="background-color: #f5f5f5; padding: 10px; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; font-size: 14px; border-radius: 4px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<script src="vistas/modulos/soat/js/cotizar_soat.js?v=<?php echo (rand()); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<!-- <script src="vistas/modulos/soat/js/retoma_soat.js?v=<?php echo (rand()); ?>"></script> -->

<?php
$eliminarCotizacion = new ControladorCotizaciones();
$eliminarCotizacion->ctrEliminarCotizacion();

?>