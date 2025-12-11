<style>
    #loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        /* Fondo blanco semi-transparente */
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #loader-container img {
        width: 40px;
        /* Ajusta el tamaño de tu gif */
    }
</style>

<div id="loader-overlay" style="display: none;">
    <div id="loader-container">
        <img src="vistas/img/plantilla/loader-update.gif" alt="Cargando..." />
    </div>
</div>

<div class="container-fluid mainDataContainer">
    <div class="col-lg-12">
        <div class="row row-aseg">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label id="lblAseData">Ingrese los datos del tomador</label>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                <div id="masCotizacion">
                    <p id="masCot">Ver más <i class="fa fa-plus-square-o"></i></p>
                </div>
                <div id="menosCotizacion">
                    <p id="menosCot">Ver menos <i class="fa fa-minus-square-o"></i></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-salud" id="containerDatosSalud">
    <div class="row" id="tomadorContainerData">
        <div class="col-xs-12 col-sm-6 col-md-2">
            <div class="form-group">
                <label for="">Tipo de Documento</label>
                <select id="TipoDocumento" class="form-control tipoDocumento"></select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2">
            <div class="form-group">
                <label for="">No. Documento</label>
                <input id="NroDocumento" maxlength="10" class="form-control numeroDocumento" type="text"></input>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label for="fechaSalida">Nombre Completo</label>
                <div class="nombreCompleto">
                    <input id="" class="form-control nombre format-text" placeholder="Nombre"></input>
                    <input id="" class="form-control apellido format-text" placeholder="Apellido"></input>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 text-center">
            <div class="form-group">
                <label>¿Se requiere cotizar individual o grupo familiar?</label><br>
                <div class="form-check form-check-inline">
                    <span class="center-elements">
                        <input type="radio" id="individual" name="tipoCotizacion" class="form-check-input" checked>
                        <label for="individual" class="form-check-label colorGray">Individual</label>
                    </span>
                    <span class="radio-container center-elements">
                        <input type="radio" id="grupoFamiliar" name="tipoCotizacion" class="form-check-input">
                        <label for="grupoFamiliar" class="form-check-label colorGray">Grupo Familiar</label>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2 cantAsegurados p-0">
            <div class="form-group">
                <label for="numAsegurados">Cantidad de asegurados</label>
                <select id="numAsegurados" class="form-control"></select>
            </div>
        </div>
    </div><br>

    <div class="row">

        <div class="col-xs-12 col-sm-6 col-md-3 preguntasForm">
            <div class="form-group">
                <label id="lblTomador">¿El tomador es el mismo asegurado?</label><br>
                <div class="form-check form-check-inline">
                    <span class=" center-elements">
                        <input type="radio" id="si" name="mismoAsegurado" class="form-check-input">
                        <label for="si" class="form-check-label colorGray">Si</label>
                    </span>
                    <span class="radio-container center-elements">
                        <input type="radio" id="no" name="mismoAsegurado" class="form-check-input" checked>
                        <label for="no" class="form-check-label colorGray">No</label>
                    </span>
                </div>
            </div>
        </div>

        <!-- Campo pregunta algun asegurado vive en barranquilla -->
        <div class="col-xs-12 col-sm-6 col-md-3 preguntasForm">
            <div class="form-group">
                <label id="">¿Algún asegurado vive en Barranquilla?</label><br>
                <div class="form-check form-check-inline">
                    <span class=" center-elements">
                        <input type="radio" id="siCiudadB" name="ciudadBarranquilla" class="form-check-input">
                        <label for="siCiudadB" class="form-check-label colorGray">Si</label>
                    </span>
                    <span class="radio-container center-elements">
                        <input type="radio" id="noCiudadB" name="ciudadBarranquilla" class="form-check-input" checked>
                        <label for="noCiudadB" class="form-check-label colorGray">No</label>
                    </span>
                </div>
            </div>
        </div>

        <!-- Campo pregunta algun asegurado es asociado a coomeva -->
        <div class="col-xs-12 col-sm-6 col-md-4 preguntasForm">
            <div class="form-group">
                <label id="">¿Algún asegurado es asociado a la Cooperativa de Coomeva?</label><br>
                <div class="form-check form-check-inline">
                    <span class=" center-elements">
                        <input type="radio" id="siAsociadoC" name="asociadoCoomeva" class="form-check-input">
                        <label for="" class="form-check-label colorGray">Si</label>
                    </span>
                    <span class="radio-container center-elements">
                        <input type="radio" id="noAsociadoC" name="asociadoCoomeva" class="form-check-input" checked>
                        <label for="" class="form-check-label colorGray">No</label>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <div class="row ">
        <div class="col-xs-12 col-sm-6 col-md-6 rowAseg">
            <label id="lblDatosAse">Datos Asegurado.</label>
        </div>
    </div>

    <div class="row asegurado" id="aseguradoTemplate" data-asegurado-id="1">
        <!-- <div id="asegurado1Id">
            <div class="col-xs-12 col-sm-6 col-md-2">
                <div class="form-group">
                    <label for="tipoDocumento">Tipo de Documento</label>
                    <select id="tipoDocumento" class="form-control tipoDocumento"></select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2">
                <div class="form-group">
                    <label for="numeroDocumento">No. Documento</label>
                    <input id="numeroDocumento" class="form-control numeroDocumento" type="number"></input>
                </div>
            </div>
        </div> -->
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label for="fechaSalida">Nombre Completo</label>
                <div class="nombreCompleto">
                    <input id="nombre" class="form-control nombre format-text" placeholder="Nombre"></input>
                    <input id="apellido" class="form-control apellido format-text" placeholder="Apellido"></input>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label for="fechaNaci">Fecha de nacimiento</label>

                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 conten-dia">
                        <select class="form-control fecha-nacimiento" name="dianacimiento" id="dianacimiento" required>
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
                        <select class="form-control fecha-nacimiento" name="mesnacimiento" id="mesnacimiento" required>
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
                        <select class="form-control fecha-nacimiento" name="anionacimiento" id="anionacimiento"
                            required>
                            <option value="">Año</option>
                            <?php
                            for ($j = 1920; $j <= 2026; $j++) {
                            ?>
                                <option value="<?php echo $j ?>"><?php echo $j ?></option><?php
                                                                                        }
                                                                                            ?>
                        </select>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2">
            <div class="form-group">
                <div class="form-group">
                    <label for="genero">Genero</label>
                    <select id="genero" class="form-control genero">
                    </select>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2 departamento" style="display: none;">
            <div class="form-group">
                <label for="departamento_1">Departamento</label>
                <select id="departamento_1" class="form-control departamento departamentoSelect">
                    <option value=""></option>
                    <option value="91">Amazonas</option>
                    <option value="05">Antioquia</option>
                    <option value="81">Arauca</option>
                    <option value="08">Atlántico</option>

                    <option value="13">Bolívar</option>
                    <option value="15">Boyacá</option>
                    <option value="17">Caldas</option>
                    <option value="18">Caquetá</option>

                    <option value="85">Casanare</option>
                    <option value="19">Cauca</option>
                    <option value="20">Cesar</option>
                    <option value="27">Chocó</option>
                    <option value="23">Córdoba</option>

                    <option value="25">Cundinamarca</option>
                    <option value="94">Guainía</option>
                    <option value="44">La Guajira</option>
                    <option value="95">Guaviare</option>
                    <option value="41">Huila</option>

                    <option value="47">Magdalena</option>
                    <option value="50">Meta</option>
                    <option value="52">Nariño</option>
                    <option value="54">Norte de Santander</option>
                    <option value="86">Putumayo</option>

                    <option value="63">Quindío</option>
                    <option value="66">Risaralda</option>
                    <option value="88">San Andrés, Providencia y Santa Catalina</option>
                    <option value="68">Santander</option>
                    <option value="70">Sucre</option>

                    <option value="73">Tolima</option>
                    <option value="76">Valle del Cauca</option>
                    <option value="97">Vaupés</option>
                    <option value="99">Vichada</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-2 ciudad" style="display: none;">
            <div class="form-group">
                <label for="ciudad_1">Ciudad</label>
                <select id="ciudad_1" class="form-control ciudad ciudadSelect"></select>
            </div>
        </div>

        <!-- Campo pregunta algun asegurado es asociado a coomeva -->
        <div class="col-xs-12 col-sm-6 col-md-4 asociadoC" style="display: none;">
            <div class="form-group">
                <label id="">Asociado Cooperativa Coomeva</label><br>
                <div class="form-check form-check-inline">
                    <span class="center-elements">
                        <input type="radio" id="asociadoSi_1" name="aseguradoAsociadoCoomeva_1" class="form-check-input" checked>
                        <label for="" class="form-check-label colorGray">Si</label>
                    </span>
                    <span class="radio-container center-elements">
                        <input type="radio" id="asociadoNo_1" name="aseguradoAsociadoCoomeva_1" class="form-check-input">
                        <label for="" class="form-check-label colorGray">No</label>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <div id="aseguradosContainer"></div>

    <?php

    if (!isset($_GET["idCotizacionSalud"])) {

        echo '<div class="row">
        <div class="col-xs-12 col-sm-6 col-md-2" id="colBtnCotizar">
            <div class="form-group">
                <button class="btn btn-primary btn-block btn-cot" id="btnCotizarAsiss">Cotizar</button>
            </div>
        </div>
    </div>';
    }

    ?>
    <div class="row">
        <div class="col-xs-12 text-center">
            <div class="spinner-container" id="spinener-cot-salud">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Inicio Agregado Javier-Dev -->

<div id="contenParrilla" class="container-fluid" style="display: none;">
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
                <div id="masResOferta" style="display: none;">
                    <p id="masResumen" onclick="masRE();">Ver más <i class="fa fa-plus-square-o"></i></p>
                </div>
                <div id="menosResOferta">
                    <p id="menosResumen" onclick="menosRE();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mostrar alertas -->
<div id="resumenCotizaciones" style="display: none;">
    <div class="col-lg-12" style="display: block;">
        <div class="card-ofertas" style="font-size: 1.42rem;">
            <div class="table-responsive">
                <table class="table table-bordered table-padding" id="tablaResumenCot">
                    <thead>
                        <tr>
                            <th class="thTable" scope="col" style="color: #88d600; margin-right: 5px; text-align: center;">Aseguradora</th>
                            <th class="thTable" scope="col" style="color: #88d600; margin-right: 5px; text-align: center;">Cotizo?</th>
                            <th class="thTable" scope="col" style="color: #88d600;; margin-right: 5px; text-align: center;">Productos cotizados</th>
                            <th class="thTable" scope="col" style="color: #88d600;; margin-right: 5px; text-align: center;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td>Seguros Bolivar</td>
                            <td class="text-center"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i></td>
                            <td class="text-center">2</td>
                            <td>Cotización exitosa!</td>
                        </tr>
                        <tr>
                            <td>Axa Colpatria</td>
                            <td class="text-center"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i></td>
                            <td class="text-center">6</td>
                            <td>Cotización exitosa!</td>
                        </tr>
                        <tr>
                            <td>Coomeva</td>
                            <td class="text-center"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i></td>
                            <td class="text-center">7</td>
                            <td>Cotización exitosa!</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Fin agregado Javier-Dev -->


<div class="container-fluid" id="containerCardsSalud">
    <div class="col-lg-12">
        <div class="row row-aseg">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <label for="">PARRILLA DE COTIZACIONES</label>
            </div>
        </div>
    </div>

    <!-- REVISAR COMO AGREGAR ESTE DIV -->
    <h4 id="h4-filtros" style="color: #514D4D; margin-bottom: 33px; margin-top: 40px; margin-left: 15px;"><b>Filtro por categoria de producto</b></h4>
    <?php if (!isset($_GET['idCotizacionSalud'])) { ?>
        <div id="filtersSection" class="col-lg-12" style="display: flex; justify-content: center;">
            <?php include "vistas/modulos/SaludCot/vistas/filtrosCategoriaSalud.php"; ?>
        </div>
    <?php
    }
    ?>

    <div class="container-fluid" id="Cards">
        <div class="row">
            <div class="col-xs-12">
                <p><strong>Nota: </strong>Esta propuesta tiene una vigencia limitada</p>
            </div>
        </div>
        <div class="row" id="row_contenedor_general_salud"></div>
    </div>
    <div id="loaderFilters">
        <div style="display:flex; align-items: center; justify-content: center; margin-bottom: 90px; margin-top: 90px; gap: 10px"><img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong style="font-size: 19px"> Cargando...</strong></div>
    </div>
</div>

<link rel="stylesheet" href="vistas\modulos\SaludCot\css\cotizadorSalud.css">
<script src="vistas\modulos\SaludCot\js\eventCotizarSalud.js?v=<?php echo (rand()); ?>"></script>
<!-- <script src="vistas\modulos\SaludCot\js\adminCotizacionesSalud.js?v=<?php echo (rand()); ?>"></script> -->