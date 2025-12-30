<link rel="stylesheet" href="vistas/modulos/soat/css/styles.css">
<section class="content">

    <label style="padding: 10px;" id="lblDataTrip2Top">Ingresa información para cotizar</label>
    <div class="box">
        <div class="box-body">

            <div id="formularioResumen">

                <!-- FORMULARIO RESUMEN ASEGURADO -->
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
                                        <button class="btn btn-primary btn-block" id="btnConsultarPlaca">Cotizar</button>
                                    </div>

                                </div>

                                
                                <div id="rowBoton" class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <div id="loaderPlaca"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="datosAseguradoNIT" style="display:none">
                                <div class="col-lg-12 form-resumAseg">
                                    <label style="font-style: underline; text-decoration: underline; padding-bottom: 15px">Datos Representante Legal</label>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="tipoDocumento">
                                            <!-- <input type="hidden" class="form-control" id="intermediario" value="<php echo $_SESSION["intermediario"]; ?>"> -->
                                            <input type="hidden" class="form-control" id="cotRestanv" value="<?php echo $_SESSION["cotRestantes"]; ?>">
                                            <label for="tipoDocumentoIDRepresentante">Tipo de Documento</label>
                                            <select class="form-control" id="tipoDocumentoIDRepresentante" name="tipoDocumentoIDRepresentante" required>
                                                <option value="" disabled selected>Selecciona el tipo de documento</option>
                                                <option value="1">Cedula de ciudadania</option>
                                                <option value="3">Cédula de extranjería</option>
                                                <option value="4">Tarjeta de identidad</option>
                                                <option value="5">Pasaporte</option>
                                                <option value="6">Carné diplomático</option>
                                                <option value="7">Sociedad extranjera sin NIT en Colombia</option>
                                                <option value="8">Fideicomiso</option>
                                                <option value="9">Registro civil de nacimiento</option>
                                            </select>
                                            <div id="alertaTipoDocumento" class="alert alert-danger mt-2" style="display: none;">
                                                Debes seleccionar un tipo de documento.
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="numDocumento">
                                            <label for="numDocumentoIDRepresentante">No. Documento</label>
                                            <input type="text" maxlength="10" class="form-control" id="numDocumentoIDRepresentante" name="numDocumentoIDRepresentante" required placeholder="Número de Documento">
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="nombreCompleto">
                                            <label for="txtNombresRepresentante">Nombre Completo</label>
                                            <div id="divNombreRepresentante" class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-6 form-group nomAseg">
                                                    <input type="text" class="form-control" name="nombres" id="txtNombresRepresentante" placeholder="Nombres">
                                                </div>
                                                <div id="divApellidosRepresentante" class="col-xs-12 col-sm-6 col-md-6 form-group apeAseg">
                                                    <input type="text" class="form-control" name="apellidos" id="txtApellidosRepresentante" placeholder="Apellidos">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="fechaNacimiento">
                                            <label name="lblFechaNacimientoRepresentante">Fecha de Nacimiento</label>
                                            <div id="fechaCompletaRepresentante" class="row">
                                                <div class="col-xs-4 col-sm-4 col-md-4 conten-dia">
                                                    <select class="form-control fecha-nacimiento" name="dianacimientoRepresentante" id="dianacimientoRepresentante">
                                                        <option value="">Dia</option>
                                                        <?php
                                                        for ($i = 1; $i <= 31; $i++) {
                                                            if (strlen($i) == 1) { ?>
                                                                <option value="<?php echo "0" . $i ?>"><?php echo "0" . $i ?></option><?php
                                                                                                                                    } else { ?>
                                                                <option value="<?php echo $i ?>"><?php echo $i ?></option><?php
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            ?>
                                                    </select>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 conten-mes">
                                                    <select class="form-control fecha-nacimiento" name="mesnacimientoRepresentante" id="mesnacimientoRepresentante">
                                                        <option value="" selected>Mes</option>
                                                        <option value="01">Enero</option>
                                                        <option value="02">Febrero</option>
                                                        <option value="03">Marzo</option>
                                                        <option value="04">Abril</option>
                                                        <option value="05">Mayo</option>
                                                        <option value="06">Junio</option>
                                                        <option value="07">Julio</option>
                                                        <option value="08">Agosto</option>
                                                        <option value="09">Septiembre</option>
                                                        <option value="10">Octubre</option>
                                                        <option value="11">Noviembre</option>
                                                        <option value="12">Diciembre</option>
                                                    </select>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 conten-anio">
                                                    <select class="form-control fecha-nacimiento" name="anionacimientoRepresentante" id="anionacimientoRepresentante">
                                                        <option value="">Año</option>
                                                        <?php
                                                        for ($j = 1920; $j <= 2025; $j++) {
                                                        ?>
                                                            <option value="<?php echo $j ?>"><?php echo $j ?></option><?php
                                                                                                                    }
                                                                                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="genero">
                                            <label for="generoRepresentante">Genero</label>
                                            <select class="form-control" name="generoRepresentante" id="generoRepresentante" required>
                                                <option value="" selected>Género</option>
                                                <option value="1">Masculino</option>
                                                <option value="2">Femenino</option>
                                            </select>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="estadoCivil">
                                            <label for="estadoCivilRepresentante">Estado Civil</label>
                                            <select class="form-control" id="estadoCivilRepresentante" name="" required>
                                                <option value="" selected>Estado Civil</option>
                                                <option value="1">Soltero (a)</option>
                                                <option value="2">Casado (a)</option>
                                                <option value="3">Viudo (a)</option>
                                                <option value="4">Divorciado (a)</option>
                                                <option value="5">Unión Libre</option>
                                                <option value="6">Separado (a)</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="correo">
                                            <label for="txtCorreoRepresentante">Correo</label>
                                            <input class="form-control" type="text" id="txtCorreoRepresentante" name="" placeholder="Correo">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="celular">
                                            <label for="txtCelularRepresentante">Celular</label>
                                            <input class="form-control" type="text" id="txtCelularRepresentante" name="" placeholder="Celular">
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="contenBtnConsultarPlaca">
                                            <button class="btn btn-primary btn-block" id="btnConsultarPlaca2">Siguiente</button>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                            <div id="loaderPlaca2"></div>
                                        </div>
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
                            <div class="row row-veh" style="margin-bottom: 3rem;">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="">DATOS DEL VEHICULO</label>
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

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" style="padding-left: 0px;">
                                            <label for="txtCilindraje">Cilindraje</label>
                                            <input type="text" class="form-control classReferenciaVeh" id="txtCilindraje" placeholder="" disabled>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" style="padding-right: 0px;">
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
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <div id="loaderPlacaTwo"></div>
                                    </div>
                                </div>

                                <div class="row">
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
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

            </div>

            <!-- RESUMEN COTIZACIÓN Y NOTAS IMPORTANTES -->
            <div class="containerResumenCoti" style="margin-top:40px; display:none;">
                <div class="row">

                    <!-- Resumen Cotización -->
                    <div class="col-md-6" style="padding-right: 5rem;">
                        <div class="summary-box">
                            <h4 class="summary-title">RESUMEN COTIZACIÓN:</h4>

                            <div class="clearfix" style="margin-bottom:15px;">
                                <span class="pull-left"><strong>Valor SOAT</strong></span>
                                <span id="valorSoat" class="pull-right price">$ -</span>
                            </div>

                            <div class="radio">
                                <label>
                                    <input id="radioConComision" type="radio" name="servicio" checked>
                                    Servicio trámite - Con Comisión:
                                    <span class="pull-right">$ 45.000</span>
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input id="radioSinComision" type="radio" name="servicio">
                                    Servicio trámite - Sin Comisión:
                                    <span class="pull-right">$ 20.000</span>
                                </label>
                            </div>

                            <hr>

                            <div class="clearfix">
                                <span class="pull-left"><strong>Total a pagar:</strong></span>
                                <span id="totalPagarSoat" class="pull-right total">$ 587.700</span>
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

                            <div class="buttons-container">
                                <button class="btn btn-green">Continuar</button>
                                <button id="btnNuevaCoti" class="btn btn-black">Realizar nueva cotización</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Parte final del formulario -->
            <div class="containerFinalForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Correo electronico tomador SOAT</label>
                        <input class="form-control" type="text" name="" id="">
                    </div>
                    <div class="col-md-3">
                        <label for="">Celular tomador SOAT</label>
                        <input class="form-control" type="text" name="" id="">
                    </div>
                    <div class="col-md-5">
                        <label for="">Adjuntar soporte de pago y Docs(TP, factura u otros)</label>
                        <input class="form-control" type="file" name="" id="">
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>



<script src="vistas/modulos/soat/js/cotizar_soat.js?v=<?php echo (rand()); ?>"></script>
<script src="vistas/js/functionsViews.js?v=<?php echo (rand()); ?>"></script>


<?php

$eliminarCotizacion = new ControladorCotizaciones();
$eliminarCotizacion->ctrEliminarCotizacion();

?>