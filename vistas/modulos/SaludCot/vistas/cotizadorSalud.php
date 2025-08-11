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
                <select id="" class="form-control tipoDocumento"></select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2">
            <div class="form-group">
                <label for="">No. Documento</label>
                <input id="" class="form-control numeroDocumento" type="number"></input>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="form-group">
                <label for="fechaSalida">Nombre Completo</label>
                <div class="nombreCompleto">
                    <input id="" class="form-control nombre format-text" placeholder="Nombre"></input>
                    <input id="" class="form-control apellido format-text" placeholder="Apellido"></input>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
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
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-2 cantAsegurados">
            <div class="form-group">
                <label for="numAsegurados">Cantidad de asegurados</label>
                <select id="numAsegurados" class="form-control"></select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
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
    </div>

    <div class="row ">
        <div class="col-xs-12 col-sm-6 col-md-6 rowAseg">
            <label id="lblDatosAse">Datos Asegurado.</label>
        </div>
    </div>

    <div class="row asegurado" id="aseguradoTemplate" data-asegurado-id="1">
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
                            for ($j = 1920; $j <= 2024; $j++) {
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
<div class="container-fluid" id="containerCardsSalud">
    <div class="col-lg-12">
        <div class="row row-aseg">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <label for="">PARRILLA DE COTIZACIONES</label>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="Cards">
        <div class="row">
            <div class="col-xs-12">
                <p><strong>Nota: </strong>Esta propuesta tiene una vigencia limitada</p>
            </div>
        </div>
        <div class="row" id="row_contenedor_general_salud"></div>
    </div>
</div>

<link rel="stylesheet" href="vistas\modulos\SaludCot\css\cotizadorSalud.css">
<script src="vistas\modulos\SaludCot\js\eventCotizarSalud.js?v=<?php echo (rand()); ?>"></script>
<!-- <script src="vistas\modulos\SaludCot\js\adminCotizacionesSalud.js?v=<?php echo (rand()); ?>"></script> -->