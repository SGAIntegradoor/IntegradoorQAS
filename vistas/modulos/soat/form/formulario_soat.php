<link rel="stylesheet" href="vistas/modulos/soat/css/styles.css">
<section class="content">

    <label style="padding: 10px;" id="lblDataTrip2Top">Ingresa información para cotizar</label>
    <div class="box">
        <div class="box-body">

            <div id="formularioResumen">
                <!-- FORMULARIO RESUMEN PLACA -->
                <form method="Post" id="formResumAseg">
                    <div id="resumenAsegurado">
                        <div class="col-lg-12" id="headerAsegurado">
                            <div class="row row-aseg">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="">Ingresa información para cotizar</label>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div id="masAsegurado">
                                        <p id="masA" onclick="masAseg();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                                    </div>
                                    <div id="menosAsegurado">
                                        <p id="menosA" onclick="menosAseg();">Ver menos <i class="fa fa-minus-square-o"></i></p>
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
                                                <label>Vehiculo 0 KM?</label>
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
                        <div class="col-lg-12" id="headerVehiculo">
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
                                        <p id="masVeh" onclick="masVeh();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                                    </div>
                                    <div id="menosVehiculo">
                                        <p id="menosVeh" onclick="menosVeh();">Ver menos <i class="fa fa-minus-square-o"></i></p>
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

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtFechaVencimiento">Fecha vencimiento SOAT</label>
                                        <input type="text" class="form-control classReferenciaVeh" id="txtFechaVencimiento" placeholder="" disabled>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="">Servicio de tramite</label>
                                        <div style="display: flex; justify-content: center; align-items: end; gap: 25px;">
                                            <div class="radio">
                                                <label style="margin-right: 5px;">
                                                    <input id="radioConComision" type="radio" name="servicio" checked>
                                                    Con Comisión:
                                                    <span class="pull-right"> $ 45.000</span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label style="margin-right: 5px;">
                                                    <input id="radioSinComision" type="radio" name="servicio">
                                                    Sin Comisión:
                                                    <span class="pull-right"> $ 20.000</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label style="color: white" for="">.</label>
                                        <button id="btnContinuarCoti" class="btn btn-primary btn-block" style="display: block;">Continuar</button>
                                    </div>
                                    <label style="color: white" for="">.</label>
                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <button id="btnNuevaCoti" class="btn btn-primary btn-block" style="background-color: black;">Nueva cotización</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <div id="loaderPlacaTwo"></div>
                                    </div>
                                </div>

                                <!-- <div class="row">
                                    <div id="contenBtnCotizar">
                                        <div class="col-lg-12 conten-cotizar">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="loaderOfertaBox">
                                                    <div id="loaderOferta"></div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="loaderRecotOfertaBox">
                                                    <div id="loaderRecotOferta"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- RESUMEN COTIZACIÓN Y NOTAS IMPORTANTES -->
            <div class="containerResumenCoti" style="margin-top:40px; display:none;">
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
                    <div class="col-md-6" style="padding-right: 5rem;">
                        <div class="summary-box">
                            <h4 id="title-resumen-coti" class="summary-title">RESUMEN COTIZACIÓN SOAT PLACA XXX000</h4>

                            <div class="clearfix" style="margin-bottom:15px;">
                                <span class="pull-left"><strong>Fecha cotización</strong></span>
                                <span id="fechaCoti" class="pull-right price">AAAA-MM-DD</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:15px;">
                                <span class="pull-left"><strong>Prima SOAT</strong></span>
                                <span id="PrimaSoat" class="pull-right price">$ -</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:15px;">
                                <span class="pull-left"><strong>Contribución Fosyga</strong></span>
                                <span id="contriFosyga" class="pull-right price">$ -</span>
                            </div>

                            <div class="clearfix" style="margin-bottom:15px;">
                                <span class="pull-left"><strong>Tasa RUNT</strong></span>
                                <span id="tasaRunt" class="pull-right price">$ -</span>
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
                                <span class="pull-left"><strong>Total a pagar:</strong></span>
                                <span id="totalPagarSoat" class="pull-right total">$ -</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notas importantes -->
                    <div class="col-md-6">
                        <div style="font-size: 12px;">
                            <p class="notes-title">Notas importantes:</p>
                            <ul>
                                <li>Verifica la información del vehículo en el RUNT antes de solicitar la emisión, especialmente cant. de pasajeros y cap. de carga.</li>
                                <li>Ten en cuenta los tiempos de respuesta:
                                    <ul>
                                        <li>Sin inconsistencias en RUNT: 2 a 5 horas hábiles.</li>
                                        <li>Con inconsistencias en RUNT: 5 horas hasta 1 día hábil.</li>
                                    </ul>
                                </li>
                                <li>Envía la información completa: tarjeta de propiedad legible (ambos lados) y datos de contacto del cliente (correo y celular).</li>
                                <li>Confirma la vigencia del SOAT anterior se puede emitir hasta 29 días antes del vencimiento.</li>
                                <li>El SOAT solo se emite con pago confirmado.</li>
                                <li>El SOAT se emite con AXA Colpatria, Previsora, Seguros del Estado o Mundial. Grupo Asistencia define la aseguradora según disponibilidad.</li>
                            </ul>

                            <!-- <div class="buttons-container">
                                <button id="btnContinuarCoti" class="btn btn-green">Continuar</button>
                                <button id="btnNuevaCoti" class="btn btn-black">Realizar nueva cotización</button>
                            </div> -->
                        </div>



                        <div style="margin-top: 25px;">
                            <div class="col-md-6">
                                <label for="">Correo electronico tomador SOAT</label>
                                <input class="form-control" type="text" name="" id="correoTomadorSoat">
                            </div>
                            <div class="col-md-6">
                                <label for="">Celular tomador SOAT</label>
                                <input class="form-control" type="text" name="" id="celularTomadorSoat">
                            </div>

                            <div id="contenedor-subir-archivos" class="col-md-12" style="display: flex; flex-direction: column; margin-top: 25px;">
                                <label>Adjuntar soporte de pago y Docs</label>

                                <button type="button" id="btnUpload" class="btn btn-outline-primary w-100">
                                    Añadir archivos
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

                                <div id="filePreview" class="mt-2"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Parte final del formulario -->
            <div class="containerFinalForm " style="display: none; margin-top: 40px;">
                <!-- <div style="height: 100px;">
                    <div class="col-md-3">
                        <label for="">Correo electronico tomador SOAT</label>
                        <input class="form-control" type="text" name="" id="correoTomadorSoat">
                    </div>
                    <div class="col-md-3">
                        <label for="">Celular tomador SOAT</label>
                        <input class="form-control" type="text" name="" id="celularTomadorSoat">
                    </div>
                    <div class="col-md-5" style="display: flex; flex-direction: column;">
                        <label>Adjuntar soporte de pago y Docs</label>

                        <button type="button" id="btnUpload" class="btn btn-outline-primary w-100">
                            Añadir archivos
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

                        <div id="filePreview" class="mt-2"></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 form-group" id="contenBtnEnviarSolicitud" style="margin-top: 15px;">
                        <button type="button" class="btn btn-primary btn-block" id="btnEnviarSolicitud" style="margin-top: 15px;">Enviar</button>
                    </div>
                </div> -->

                <div class="box-body row" id="section-final" style="display: none;">
                    <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenedor-archivos" style="border: 2px solid #e5e5e5; padding: 15px; display: none; border-radius: 20px;"></div>

                    <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenComentarios" style="margin-top: 0px;">

                        <div class="col-xs-12 col-sm-12 col-md-12 form-group" id="contenBtnEstadoAprobar" style="margin-top: 15px;">
                            <textarea id="txtComentarios" style="width: 100%;" placeholder="Comentarios..."></textarea>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenBtnEstadoAprobar" style="margin-top: 15px;">
                            <button type="button" class="btn btn-primary btn-block" id="btnEstadoAprobar" style="margin-top: 15px;">Aprobar</button>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenBtnEstadoDevolver" style="margin-top: 15px;">
                            <button type="button" class="btn btn-danger btn-block" id="btnEstadoDevolver" style="margin-top: 15px;">Devolver</button>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="contenBtnEnviarSolicitud" style="margin-top: 15px;">
                    <button type="button" class="btn btn-primary btn-block" id="btnEnviarSolicitud" style="margin-top: 15px;">Solicitar Expedición</button>
                </div>
            </div>

            <div id="container-subida-soat" style="display: none;">
                
                
                    <div id="contenedor-subir-soat"></div>
                    <div class="col-md-4" style="display: flex; flex-direction: column; margin-top: 25px;">
                        <button id="btnSubirSoat" type="button" class="btn btn-primary btn-block" style="margin-top: 15px;">Solicitar Expedición</button>
                    </div>
                
            </div>

        </div>
        <!-- <div class="content" style="min-height: 0px">
            <div class="box">
                <div class="box-body row">
                    <div class="col-xs-12 col-sm-5 col-md-5 form-group" id="contenedor-archivos" style="border: 2px solid #e5e5e5; padding: 15px; display: none; border-radius: 20px;"></div>
                </div>
            </div>
        </div> -->
    </div>

</section>

<script src="vistas/modulos/soat/js/cotizar_soat.js?v=<?php echo (rand()); ?>"></script>
<!-- <script src="vistas/modulos/soat/js/retoma_soat.js?v=<?php echo (rand()); ?>"></script> -->

<?php
$eliminarCotizacion = new ControladorCotizaciones();
$eliminarCotizacion->ctrEliminarCotizacion();

?>