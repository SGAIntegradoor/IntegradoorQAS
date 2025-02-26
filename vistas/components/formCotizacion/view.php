<div class="container-fluid mainDataContainer">
    <div class="col-lg-12">
        <div class="row row-aseg">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <!-- titulo del form para tipo de persona -->
                <label id="lblCotAseg">Ingresa información para cotizar</label>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                <div id="masCotHogar">
                    <p id="masCot">Ver más <i class="fa fa-plus-square-o"></i></p>
                </div>
                <div id="menosCotHogar">
                    <p id="menosCot">Ver menos <i class="fa fa-minus-square-o"></i></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display: none;">
    <input type="text" name="idCliente" id="idCliente" />
</div>
<div class="general-container-aseg" id="containerInfoAseg">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 0px; padding-bottom: 20px;"></div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label for="tipoDocumento">Tipo de Documento</label>
                <select id="tipoDocumento" class="form-control tipoDocumento">
                    <option value="0">Seleccione tipo de documento</option>
                    <option value="C">CC</option>
                    <option value=" ">NIT</option>
                    <option value="X">CE</option>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label for="noDocumento">No. Documento</label>
                <input id="noDocumento" class="form-control numeroDocumento" type="number"></input>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6" id="nombreCompleto">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <div class="nombreCompleto">
                    <input id="nombre" class="form-control nombre format-text" placeholder="Nombre"></input>
                    <input id="apellidos" class="form-control apellido format-text" placeholder="Apellido"></input>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2" id="digito" style="display: none;">
            <div class="form-group">
                <label for="digito">Digito de Verificación</label>
                <input class="form-control digito" placeholder="Digito de Verificación"></input>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4" id="razon" style="display: none;">
            <div class="form-group">
                <label for="razon">Razón Social</label>
                <input class="form-control razon" placeholder="Razón Social"></input>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 20px; padding-bottom: 20px;"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3" id="nacionalidad" style="display: none;">
            <div class="form-group">
                <label for="nacionalidad1">Nacionalidad</label>
                <select class="form-control nacionalidad" id="nacionalidad1">
                    <option value="" selected>
                        Seleccione una opción
                    </option>
                    <option value="1">
                        Colombia
                    </option>
                    <option value="2">
                        Canada
                    </option>
                    <option value="3">
                        Estados Unidos
                    </option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3" id="pNacimiento" style="display: none;">
            <div class="form-group">
                <label for="pNacimiento">Pais de Nacimiento</label>
                <select id="pNacimiento1" class="form-control pNacimiento">
                    <option value="" selected>
                        Seleccione una opción
                    </option>
                    <option value="1">
                        Colombia
                    </option>
                    <option value="2">
                        Canada
                    </option>
                    <option value="3">
                        Estados Unidos
                    </option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3" id="correo">
            <div class="form-group">
                <label for="correo">Correo</label>
                <input class="form-control correo" type="text" placeholder="Correo"></input>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3" id="celular">
            <div class="form-group">
                <label for="celular">Celular</label>
                <input class="form-control celular" type="text" placeholder="Celular"></input>
            </div>
        </div>

        <?php

        if (!isset($_GET["idCotizacionHogar"])) {

            echo '
            <div class="col-xs-12 col-sm-6 col-md-3" id="btnSiguiente">
                    <button class="btn btn-primary btn-block btn-cot" id="btnHogarSiguiente">Siguiente</button>
            </div>';
        }
        ?>
        <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 20px; padding-bottom: 20px;"></div>

    </div>
    <link rel="stylesheet" href="vistas\components\formCotizacion\css\styles.css">
    <!-- <script src="vistas\components\formCotizacion\js\functions.js"></script> -->