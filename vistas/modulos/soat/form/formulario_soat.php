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
                                            <!-- <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenCeroKM">
                                                <label>Vehiculo 0 KM?</label>
                                                <div class="conten-ceroKM">
                                                    <label for="Si">Si</label>
                                                    <input type="radio" name="ceroKM" id="txtEsCeroKmSi" value="Si" required>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label for="No">No</label>
                                                    <input type="radio" name="ceroKM" id="txtEsCeroKmNo" value="No" checked>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>


                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="tipoDocumento">
                                        <input type="hidden" class="form-control" id="intermediario" value="<?php //echo $_SESSION["intermediario"]; ?>">
                                        <input type="hidden" class="form-control" id="cotRestanv" value="<?php //echo $_SESSION["cotRestantes"]; 
                                                                                                                ?>
                                        <label for="tipoDocumentoID">Tipo de Documento</label>
                                        <select class="form-control" id="tipoDocumentoID" required>
                                            <option value="" disabled selected>Selecciona el tipo de documento</option>
                                            <option value="1">Cedula de ciudadania</option>
                                            <option value="2">NIT</option>
                                            <option value="3">Cédula de extranjería</option>
                                            <option value="5">Pasaporte</option>
                                        </select>
                                        <div id="alertaTipoDocumento" class="alert alert-danger mt-2" style="display: none;">
                                            Debes seleccionar un tipo de documento.
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="numDocumento">
                                        <label for="numDocumentoID">No. Documento</label>
                                        <input type="text" maxlength="10" class="form-control" id="numDocumentoID" required placeholder="Número de Documento">
                                    </div> -->

                                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="nombreCompleto" style="display: none;">
                                        <label for="txtNombres">Nombre Completo</label>
                                        <div id="divNombre" class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 nomAseg">
                                                <input type="text" class="form-control" name="nombres" id="txtNombres" placeholder="Nombres">
                                            </div>
                                            <div id="divApellidos" class="col-xs-12 col-sm-6 col-md-6 form-group apeAseg">
                                                <input type="text" class="form-control" name="apellidos" id="txtApellidos" placeholder="Apellidos">
                                            </div>
                                        </div>
                                        <div id="digitoVerificacion" class="row" style="display: none">
                                            <div id="divDigitoVerif" class="col-xs-12 col-sm-6 col-md-12 nomAseg">
                                                <input type="text" class="form-control" id="txtDigitoVerif" placeholder="Dígito de Verificación" max="1" maxlength="1">
                                            </div>
                                        </div>
                                    </div>

                                     <div class="col-xs-12 col-sm-6 col-md-2 form-group" id="contenBtnConsultarPlaca">
                                        <button class="btn btn-primary btn-block" id="btnConsultarPlaca">Cotizar</button>
                                    </div>

                                </div>

                                <div class="row">
                                    <div id="divRazonSocial" style="display: none">
                                        <div id="razonSocial" class="col-xs-12 col-sm-6 col-md-3 form-group nomAseg">
                                            <label name="lblRazonSocial">Razón Social</label>
                                            <input type="text" class="form-control" id="txtRazonSocial" placeholder="Razón Social" required>
                                        </div>
                                    </div>
                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="fechaNacimiento">
                                        <label name="lblFechaNacimiento">Fecha de Nacimiento</label>
                                        <div id="fechaCompleta" class="row">
                                            <div class="col-xs-4 col-sm-4 col-md-4 conten-dia">
                                                <select class="form-control fecha-nacimiento" name="dianacimiento" id="dianacimiento">
                                                    <option value="">Dia</option>
                                                   
                                                </select>
                                            </div>
                                            <div class="col-xs-4 col-sm-4 col-md-4 conten-mes">
                                                <select class="form-control fecha-nacimiento" name="mesnacimiento" id="mesnacimiento">
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
                                                <select class="form-control fecha-nacimiento" name="anionacimiento" id="anionacimiento">
                                                    <option value="">Año</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divGenero">
                                        <label for="genero">Genero</label>
                                        <select class="form-control" id="genero" required>
                                            <option value="" selected>Género</option>
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                    </div> -->

                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divEstadoCivil">
                                        <label for="estadoCivil">Estado Civil</label>
                                        <select class="form-control" id="estadoCivil" required>
                                            <option value="" selected>Estado Civil</option>
                                            <option value="1">Soltero (a)</option>
                                            <option value="2">Casado (a)</option>
                                            <option value="3">Viudo (a)</option>
                                            <option value="4">Divorciado (a)</option>
                                            <option value="5">Unión Libre</option>
                                            <option value="6">Separado (a)</option>
                                        </select>
                                    </div> -->
                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="correo">
                                        <label for="txtCorreo">Correo</label>
                                        <input type="text" class="form-control" id="txtCorreo" placeholder="Correo">
                                    </div> -->
                                </div>
                                <div id="rowBoton" class="row">
                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="celular">
                                        <label for="txtCelular">Celular</label>
                                        <input type="text" class="form-control" id="txtCelular" placeholder="Celular">
                                    </div> -->

                                   

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

                <!-- FORMULARIO VEHICULO MANUAL -->

                <div id="formularioVehiculo">
                    <div class="col-lg-12" id="headerFormVeh">
                        <div class="row row-formVehManual">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <label for="">CONSULTA MANUAL DEL VEHICULO POR FASECOLDA</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 form-consulVeh">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                <label for="clase">Código Fasecolda</label>
                                <input type="text" maxlength="10" class="form-control" id="fasecoldabuscadormanual" placeholder="Número de fasecolda">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                <label for="clase">Modelo Vehículo</label>
                                <input type="text" maxlength="10" class="form-control" id="modelobuscadormanual" placeholder="Modelo Vehículo">
                            </div>

                            <div style="padding-top: 25px !important;" class="col-xs-12 col-sm-6 col-md-2 form-group">
                                <button class="btn btn-primary btn-block" id="btnConsultarVehmanualbuscador">Consultar
                                    Vehículo</button>
                            </div>
                        </div>
                    </div>
                    <form method="Post" id="formVehManual">
                        <div class="col-lg-12" id="headerFormVeh">
                            <div class="row row-formVehManual">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="">CONSULTA MANUAL DEL VEHICULO POR CARACTERISTICAS</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 form-consulVeh">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                            <label for="clase">Clase Vehículo</label>
                                            <select class="form-control" name="clase" id="clase" required="">
                                                <option value="">Seleccione una opción</option>
                                                <option value="AUTOMOVIL">AUTOMOVIL</option>
                                                <option value="CAMPERO">CAMPERO</option>
                                                <option value="CAMIONETA PASAJ.">CAMIONETA PASAJERO</option>
                                                <option value="PICKUP DOBLE CAB">PICKUP DOBLE CAB</option>
                                                <option value="PICKUP SENCILLA">PICKUP SENCILLA</option>
                                            </select>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                            <label for="Marca">Marca Vehículo</label>
                                            <select class="form-control" name="Marca" id="Marca" required></select>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                            <label for="linea">Modelo Vehículo</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <div id="loadingModelo"></div>
                                                </div>
                                                <select class="form-control" name="edad" id="edad" required></select>
                                            </div>
                                        </div>


                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                            <label for="linea">Linea Vehículo</label>
                                            <select class="form-control" name="linea" id="linea" required></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                            <div id="referenciados"></div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                            <div id="referenciatres"></div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3">
                                            <div id="loaderVehiculo"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-2 form-group btnConsultarVeh">
                                            <button class="btn btn-primary btn-block" id="btnConsultarVehmanual">Consultar
                                                Vehículo</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- FORMULARIO RESUMEN VEHICULO -->
                <form method="Post" id="formResumVeh">


                    <div id="resumenVehiculo">

                        <div class="col-lg-12" id="headerVehiculo">
                            <div class="row row-veh">
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
                                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                                        <label for="txtPlacaVeh">Placa</label>
                                        <input type="text" class="form-control" id="txtPlacaVeh" placeholder="" disabled>
                                    </div> -->

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
                                                    <!-- <button class="btn btn-primary btn-block" id="btnCotizar">Cotizar Ofertas</button> -->
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

                <!--- RESUMEN DE COTIZACIONES -->
                <div id="contenParrilla" style="display: none;">
                    <div class="col-lg-12 form-parrilla">
                        <div class="row row-parrilla">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <label for="">RESUMEN DE COTIZACIONES</label>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 text-right">
                                <div id="masResOferta">
                                    <p id="masResumen" onclick="masRE();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                                </div>
                                <div id="menosResOferta">
                                    <p id="menosResumen" onclick="menosRE();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                                </div>
                            </div>
                        </div>

                        <!-- *//* Mostrar alertas *//* -->
                        <div id="resumenCotizaciones">
                            <div class="col-lg-12" style="display: block;">
                                <div class="card-ofertas">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-padding" id="tablaResumenCot">
                                            <thead>
                                                <tr>
                                                    <th class="thTable" scope="col" style="color: #88d600; margin-right: 5px;">Aseguradora</th>
                                                    <th class="thTable" scope="col" style="color: #88d600; margin-right: 5px;">Cotizo?</th>
                                                    <th class="thTable" scope="col" style="color: #88d600;; margin-right: 5px;">Productos
                                                        cotizados</th>
                                                    <th class="thTable" scope="col" style="color: #88d600;; margin-right: 5px;">Observaciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        <p class="text-justify"><strong>¿Por qué algunas compañías no cotizan? R/. 0.</strong>Tiene póliza
                                            vigente con esa compañía. <strong>1.</strong> Aseguradora
                                            caída, en mantenimiento o en actualización. <strong>2.</strong> RUNT, Cexper, Sistema Fasecolda
                                            caído. <strong>3.</strong> Fallas Portal
                                            Integradoor. <strong>4.</strong> Vehículo fuera de políticas por marca, línea o modelo.
                                            <strong>5.</strong> Ciudad bloqueada. <strong>6.</strong> Error en
                                            validación datos del asegurado. <strong>7.</strong> Valor asegurado no autorizado para cotizar
                                            vía webservice. <strong>8.</strong> Vehículo
                                            salvamento. <strong>9.</strong> Motos, Pesados, Públicos no se cotizan por este módulo.
                                            <strong>10.</strong> Personas Jurídicas se cotizan
                                            manualmente. <strong>11.</strong> Algunas aseguradoras no cotizan 0 km vía webservice.
                                            <strong>12.</strong> Vehículo bloqueado por cotización
                                            vigente con otro asesor (ej. Solidaria). <strong>13.</strong> Mal uso del usuario registrando
                                            espacios o caracteres en placas,
                                            nombres, apellidos o documentos de identidad
                                        </p>
                                    </div>
                                    <div class="row button-recotizar" style="display: none; margin:5px">
                                        <div class="col-md-6"></div>
                                        <div class="col-xs-12 col-sm-12 col-md-3 form-group">
                                            <button class="btn btn-primary btn-block" id="btnReCotizarFallidas">Recotizar Ofertas
                                                Fallidas</button>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-3 form-group">
                                            <button class="btn btn-primary btn-block" style="background-color: black;" id="btnCotizarFinesa">Calcular Financiación</button>
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                </div>
                            </div>


                            <div id="mensajePrevisora" style="font-size: 13px;">
                                <p class="aviso-container col-lg-12">
                                <p><b>Notas Importantes: </b></p>

                                <ul style="padding-right: 25px !important; text-align: justify;">
                                    <li>
                                        <p><b>Seguros Mundial:</b> En pérdidas totales por daños y hurto puedes escoger 3 opciones: cobertura al 100% sin deducible, y deducible del 20% o del 40% de la perdida.
                                            Para daños parciales aplica un deducible del 10% (mínimo 1 SMMLV), y se maneja la modalidad de ARREGLO DIRECTO, la cual consiste en que el asegurado es quien se encarga de tramitar y hacer seguimiento a la reparación del vehículo en su taller de confianza y la compañía se encarga de autorizar el pago del reclamo.
                                            Inicialmente se anticipa el 70% del valor de la reparación y el 30% restante cuando el vehículo quede reparado y se presenten las respectivas facturas. Vehículos de hasta 5 años pueden usar repuestos originales; si son mayores, se usan repuestos homologados.</p>
                                        <p>Incluye servicios de conductor elegido, grúa por avería y accidente y no cubre vehículo de reemplazo.</p>
                                    </li>
                                    <li>
                                        <p>
                                            <b>Seguros del Estado:</b> Los vehículos KIA de la línea PICANTO se encuentran fuera de políticas. Si se genera cotización con esta Aseguradora, omitir dicha oferta. Con respecto a la línea SPORTAGE está sólo está excluida en el Valle del Cauca. Igualmente con esta compañía, la clase de vehículo PICK UP solo se asegura como vehículo público.
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            Si a tu cliente le interesa Previsora, Allianz o HDI, ten en cuenta que ciertas líneas de vehículos requieren la instalación del dispositivo Cazador al tomar su seguro. Para Previsora tiene un costo adicional a la póliza y para Allianz y HDI es totalmente gratis. Por favor confirma con tu área comercial.
                                        </p>
                                    </li>
                                    <li>
                                        <p style="font-weight: bold;">Política de valor asegurado livianos:</p>
                                        <p>Menos de 200 millones, se asegura de acuerdo a políticas de cada aseguradora. Entre 200 a 250 millones, se puede asegurar con autorización del Director Comercial de Grupo Asistencia. Entre 250 a 300 millones, se puede asegurar solo bajo autorización de Gerencia de Grupo Asistencia, de acuerdo al nivel de productividad del Asesor.</p>
                                        <p><b>Nota:</b> Tener en cuenta que aunque el cotizador genere ofertas, no todos los vehículos son asegurables. Se podrán hacer excepciones de valor asegurado superior cuando el asesor sea productivo, tenga más de 6 meses de antigüedad con Grupo Asistencia, no tenga altos indices de siniestralidad en su cartera, y si el cliente tiene vinculación con otros productos de la aseguradora. El valor de las primas de las cotizaciones puede variar al momento de emitir en los casos autorizados de manera excepcional.
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- PARRILLA DE COTIZACIONES -->
                    <div id="parrillaCotizaciones">

                        <div class="col-lg-12 form-coti">
                            <div class="row row-parrilla">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="">PARRILLA DE COTIZACIONES</label>
                                </div>
                            </div>
                        </div>

                        <div id="filtersSection" class="col-lg-12" style="display: none;">
                            <?php require "vistas/components/cotizar/catfilters.php" ?>
                        </div>

                        <div id="cardCotizacion">
                        </div>
                        <div class="divCards" id="divCards"></div>
                        <div id="cardAgregarCotizacion">
                        </div>

                        <div id="contenCotizacionPDFLivianos">

                        </div>

                    </div>

                </div>

                <div id="dialog"></div>

                <!-- CAMPOS OCULTOS PARA OPTENER LA INFORMACION-->
                <div style="display: none;">
                    <label>Aseguradoras</label>
                    <input type="hidden" name="aseguradoras" id="aseguradoras" value='<?php echo json_encode($aseguradoras); ?>'>
                    <label>Intermediario</label>
                    <input type="hidden" name="idIntermediario" id="idIntermediario" value="<?php echo $idIntermediario; ?>">
                    <label>Rol Asesor</label>
                    <input type="hidden" name="rolAsesor" id="rolAsesor" value="<?php echo $rolAsesor; ?>">
                    <label>Id Asegurado</label>
                    <input type="hidden" name="idCliente" id="idCliente">
                    <label>Celular Asegurado</label>
                    <input type="text" name="celularAseg" id="celularAseg" value="">
                    <label>Email Asegurado</label>
                    <input type="text" name="emailAseg" id="emailAseg" value="">
                    <label>Direccion Asegurado</label>
                    <input type="text" name="direccionAseg" id="direccionAseg" value="CALLE 70 7T2-16">
                    <label>ClaseVehiculo</label>
                    <input type="text" name="CodigoClase" id="CodigoClase">
                    <label>MarcaVehiculo</label>
                    <input type="text" name="CodigoMarca" id="CodigoMarca">
                    <label>LineaVehiculo</label>
                    <input type="text" name="CodigoLinea" id="CodigoLinea">
                    <label>LimiteRCESTADO</label>
                    <input type="text" name="LimiteRC" id="LimiteRC" value="6">
                    <label>CoberturaEstado</label>
                    <input type="text" name="CoberturaEstado" id="CoberturaEstado" value="1">
                    <label>ValorAccesorios</label>
                    <input type="text" name="ValorAccesorios" id="ValorAccesorios" value="0">
                    <label>CodigoVerificacion</label>
                    <input type="text" name="CodigoVerificacion" id="CodigoVerificacion" value="0">
                    <label>AniosSiniestro</label>
                    <input type="text" name="AniosSiniestro" id="AniosSiniestro" value="0">
                    <label>AniosAsegurados</label>
                    <input type="text" name="AniosAsegurados" id="AniosAsegurados" value="0">
                    <label>NivelEducativo</label>
                    <input type="text" name="NivelEducativo" id="NivelEducativo" value="4">
                    <label>Estrato</label>
                    <input type="text" name="Estrato" id="Estrato" value="3">
                    <label>TokenPrevisora</label>
                    <input type="text" name="previsoraToken" id="previsoraToken">

                    <!--ESTADO-->
                    <input type="text" class="form-control" id="cre_est_usuario" value="<?php echo $cre_est_usuario; ?>">
                    <input type="text" class="form-control" id="cre_equ_contrasena" value="<?php echo $cre_equ_contrasena; ?>">
                    <input type="text" class="form-control" id="Cre_Est_Entity_Id" value="<?php echo $Cre_Est_Entity_Id; ?>">
                    <input type="text" class="form-control" id="cre_est_zona" value="<?php echo $cre_est_zona; ?>">


                    <!--ZURICH-->
                    <!-- <input type="text" class="form-control" id="cre_zur_nomUsu" value="</?php echo $_SESSION["cre_zur_nomUsu"]; ?>">
            <input type="text" class="form-control" id="cre_zur_passwd" value="</?php echo $_SESSION["cre_zur_passwd"]; ?>">
            <input type="text" class="form-control" id="cre_zur_intermediaryEmail" value="</?php echo $_SESSION["cre_zur_intermediaryEmail"]; ?>">
            <input type="text" class="form-control" id="cre_zur_Cookie" value="</?php echo $_SESSION["cre_zur_Cookie"]; ?>">
            <input type="text" class="form-control" id="cre_zur_token" value="</?php echo $_SESSION["cre_zur_token"]; ?>">
            <input type="text" class="form-control" id="cre_zur_fecha_token" value="</?php echo $_SESSION["cre_zur_fecha_token"]; ?>"> -->

                    <!--SOLIDARIA-->
                    <input type="text" class="form-control" id="cre_sol_cod_sucursal" value="<?php echo $cre_sol_cod_sucursal; ?>">
                    <input type="text" class="form-control" id="cre_sol_cod_per" value="<?php echo $cre_sol_cod_per; ?>">
                    <input type="text" class="form-control" id="cre_sol_cod_tipo_agente" value="<?php echo $cre_sol_cod_tipo_agente; ?>">
                    <input type="text" class="form-control" id="cre_sol_cod_agente" value="<?php echo $cre_sol_cod_agente; ?>">
                    <input type="text" class="form-control" id="cre_sol_cod_pto_vta" value="<?php echo $cre_sol_cod_pto_vta; ?>">
                    <input type="text" class="form-control" id="cre_sol_grant_type" value="<?php echo $cre_sol_grant_type; ?>">
                    <input type="text" class="form-control" id="cre_sol_Cookie_token" value="<?php echo $cre_sol_Cookie_token; ?>">
                    <input type="text" class="form-control" id="cre_sol_token" value="<?php echo $cre_sol_token; ?>">
                    <input type="text" class="form-control" id="cre_sol_fecha_token" value="<?php echo $cre_sol_fecha_token; ?>">

                    <!--PREVISORA-->
                    <input type="text" class="form-control" id="cre_pre_BusinessId" value="<?php echo $cre_pre_bussinessId ?>">
                    <input type="text" class="form-control" id="cre_pre_SourceCode" value="<?php echo $cre_pre_source_code ?>">
                    <input type="text" class="form-control" id="cre_pre_AgentCode" value="<?php echo $cre_pre_key ?>">
                    <input type="text" class="form-control" id="cre_pre_Username" value="<?php echo $cre_pre_usu ?>">
                    <input type="text" class="form-control" id="cre_pre_Password" value="<?php echo $cre_pre_pass ?>">

                    <!--MAPFRE-->
                    <!-- <input type="text" class="form-control" id="cre_map_codCliente" value="</?php echo $_SESSION["cre_map_codCliente"]; ?>">
            <input type="text" class="form-control" id="cre_map_codigoOficinaAsociado" value="</?php echo $_SESSION["cre_map_codigoOficinaAsociado"]; ?>">
            <input type="text" class="form-control" id="cre_map_codigoIntermediario" value="</?php echo $_SESSION["cre_map_codigoIntermediario"]; ?>">
            <input type="text" class="form-control" id="cre_map_username" value="</?php echo $_SESSION["cre_map_username"]; ?>">
            <input type="text" class="form-control" id="cre_map_password" value="</?php echo $_SESSION["cre_map_password"]; ?>">
            <input type="text" class="form-control" id="cre_map_codigonivel3GA" value="</?php echo $_SESSION["cre_map_codigonivel3GA"]; ?>"> -->

                    <!--SBS-->
                    <input type="text" class="form-control" id="cre_sbs_usuario" value="<?php echo $cre_sbs_usuario; ?>">
                    <input type="text" class="form-control" id="cre_sbs_contrasena" value="<?php echo $cre_sbs_contrasena; ?>">

                    <!--ALLIANZ-->
                    <input type="text" class="form-control" id="cre_alli_sslcertfile" value="<?php echo $cre_alli_sslcertfile; ?>">
                    <input type="text" class="form-control" id="cre_alli_sslkeyfile" value="<?php echo $cre_alli_sslkeyfile; ?>">
                    <input type="text" class="form-control" id="cre_alli_passphrase" value="<?php echo $cre_alli_passphrase; ?>">
                    <input type="text" class="form-control" id="cre_alli_partnerid" value="<?php echo $cre_alli_partnerid; ?>">
                    <input type="text" class="form-control" id="cre_alli_agentid" value="<?php echo $cre_alli_agentid; ?>">
                    <input type="text" class="form-control" id="cre_alli_partnercode" value="<?php echo $cre_alli_partnercode; ?>">
                    <input type="text" class="form-control" id="cre_alli_agentcode" value="<?php echo $cre_alli_agentcode; ?>">

                    <!--AXA-->
                    <input type="text" class="form-control" id="cre_axa_sslcertfile" value="<?php echo $cre_axa_sslcertfile; ?>">
                    <input type="text" class="form-control" id="cre_axa_sslkeyfile" value="<?php echo $cre_axa_sslkeyfile; ?>">
                    <input type="text" class="form-control" id="cre_axa_passphrase" value="<?php echo $cre_axa_passphrase; ?>">
                    <input type="text" class="form-control" id="cre_axa_codigoDistribuidor" value="<?php echo $cre_axa_codigoDistribuidor; ?>">
                    <input type="text" class="form-control" id="cre_axa_idTipoDistribuidor" value="<?php echo $cre_axa_idTipoDistribuidor; ?>">
                    <input type="text" class="form-control" id="cre_axa_codigoDivipola" value="<?php echo $cre_axa_codigoDivipola; ?>">
                    <input type="text" class="form-control" id="cre_axa_canal" value="<?php echo $cre_axa_canal; ?>">
                    <input type="text" class="form-control" id="cre_axa_validacionEventos" value="<?php echo $cre_axa_validacionEventos; ?>">
                    <input type="text" class="form-control" id="url_axa" value="<?php echo $url_axa; ?>">
                    <input type="text" class="form-control" id="cre_axa_livianos_productos" value="<?php echo $cre_axa_livianos_productos; ?>">

                    <!--Bolivar-->
                    <input type="text" class="form-control" id="cre_bol_api_key" value="<?php echo $cre_bol_api_key; ?>">
                    <input type="text" class="form-control" id="cre_bol_claveAsesor" value="<?php echo $cre_bol_claveAsesor; ?>">

                </div>

            </div>

        </div>

        <!-- MODAL FASECOLDA -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Buscar vehículo por fasecolda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label class="col-form-label">Fasecolda:</label>
                                <input type="text" class="form-control" id="txtFasecolda_modal">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Modelo:</label>
                                <input type="text" class="form-control" id="txtModeloVeh_modal">
                            </div>
                            <div class="divsButtonsModals">
                                <button type="button" class="btn btn-primary buttonsModal" id="btn-cerrar-fasecolda">Cerrar</button>
                                <button type="button" class="btn btn-primary buttonsModal" id="btn-consultar-fasecolda">Consultar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog2">
          <div class="modal-content2">
            <div class="modal-header2">
              <h5 class="modal-title2" id="staticBackdropLabel2">POLÍTICA DE VALOR ASEGURADO LIVIANOS</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body2">
              <form>
                <table>
                  <thead>
                    <tr>
                      <th>Valor Asegurado</th>
                      <th>Condiciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Menos de 200 millones</td>
                      <td>De acuerdo a políticas de cada aseguradora</td>
                    </tr>
                    <tr>
                      <td>200 a 250 millones</td>
                      <td>Requieren autorización del Director Comercial de Grupo Asistencia</td>
                    </tr>
                    <tr>
                      <td>250 a 300 millones</td>
                      <td>Requieren autorización de Gerencia de Grupo Asistencia de acuerdo al nivel de productividad del Asesor</td>
                    </tr>
                  </tbody>
                </table>

                

                <div class="divsButtonsModals">
                  <button type="button" class="btn btn-primary buttonsModal" id="btn-cerrar-fasecolda">Cerrar</button>
                  <button type="button" class="btn btn-primary buttonsModal" id="btn-consultar-fasecolda">Consultar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div> -->
        <!-- END MODAL FASECOLDA -->

</section>



<script src="vistas/modulos/soat/js/cotizar_soat.js?v=<?php echo (rand()); ?>"></script>
<script src="vistas/js/functionsViews.js?v=<?php echo (rand()); ?>"></script>


<?php

$eliminarCotizacion = new ControladorCotizaciones();
$eliminarCotizacion->ctrEliminarCotizacion();

?>