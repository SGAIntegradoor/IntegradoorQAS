<?php 
$idIntermediario = $_SESSION['permisos']['id_Intermediario'];

$stmt = Conexion::conectar()->prepare("
SELECT 
    u.id_usuario,
    CONCAT(u.usu_nombre,' ', u.usu_apellido) AS nombre_usuario, 
    u.usu_email, u.usu_telefono,
    af.nombre_analista,
    uu.usu_email as correo_analista
FROM usuarios u
INNER JOIN analistas_freelances af ON af.id_usuario = u.usu_documento
INNER JOIN usuarios uu ON uu.usu_documento = af.id_analista
WHERE u.id_usuario = :idUsuario
");
$stmt->bindParam(":idUsuario", $_SESSION['idUsuario'], PDO::PARAM_INT);
$stmt->execute();

$infoUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
$correoAnalista = $infoUsuario[0]['correo_analista'];
$usu_email = $infoUsuario[0]['usu_email'];
$usu_cel = $infoUsuario[0]['usu_telefono'];
$nombre_analista = $infoUsuario[0]['nombre_analista'];

?>

<style>
    .flex-wrap-form {
        display: flex;
        flex-wrap: wrap;
        gap: 0px;
    }

    .flex-wrap-form>[class*="col-"] {
        display: block;
    }

    .form-control {
        margin-bottom: 30px;
    }
    
</style>

<div id="formHogar" style="display: none;">
    <div class="container-fluid">
        <div class="">
            <div class="row row-aseg" style="margin-bottom: 0px;">
                <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 10px;">
                    <!-- titulo del form para tipo de persona -->
                    <label id="lblDataCot">Datos del Bien Asegurado</label>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div id="masDataHogar">
                        <p id="masHogarCot">Ver más <i class="fa fa-plus-square-o" aria-hidden="true"></i></p>
                    </div>
                    <div id="menosDataHogar">
                        <p id="menosHogarCot">Ver menos <i class="fa fa-minus-square-o" aria-hidden="true"></i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;">
        <input type="text" name="idCliente" id="idCliente">
    </div>
    <div class="general-container-aseg" id="containerDatos">
        <div class="row flex-wrap-form" id="">
            <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 20px;"></div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="dirInmuebleAllianz">Dirección del inmueble</label>
                    <i class="fa fa-solid fa-circle-info tooltip-direccion" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-direccion" data-placement="bottom"></i>
                    <input id="dirInmuebleAllianz" class="form-control dirInmueble" type="text">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="dirRestoInmuebleAllianz">Resto de la dirección</label>
                    <i class="fa fa-solid fa-circle-info tooltip-resto-direccion" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-resto-direccion" data-placement="bottom"></i>
                    <input id="dirRestoInmuebleAllianz" class="form-control dirInmueble" type="text">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="deptoInmueble">Departamento</label>
                    <select class="form-control validateDataHogar" id="deptoInmueble" required>
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

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="ciudadInmueble">Ciudad</label>
                    <select id="ciudadInmueble" class="form-control ciudadInmueble validateDataHogar">
                    </select>
                </div>
            </div>

            <!-- <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group" style="position: relative;">
                    <label for="zonaRiesgo">Zona de riesgo</label>
                    <select id="zonaRiesgo" class="form-control zonaRiesgo"></select>
                    <span id="loaderZonaRiesgo" class="loader-spinner">
                        Cargando... <i class="fas fa-spinner fa-spin" style="color: #88D600;"></i>
                    </span>
                </div>
            </div> -->

            <div class="col-xs-12 col-sm-6 col-md-3" style="display: none;" id="subZoneBog">
                <div class="form-group">
                    <label for="subZona">Barrio Localidad (Sub Zona)</label>
                    <select id="subZona" class="form-control subZona"></select>
                    <span id="loaderSubZona" class="loader-spinner-subZona">
                        Cargando... <i class="fas fa-spinner fa-spin" style="color: #88D600;"></i>
                    </span>
                </div>
            </div>


            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="tipoVivienda">Tipo de vivienda</label>
                    <select id="tipoVivienda" class="form-control tipoVivienda">
                        <option value="0">Seleccione una opción</option>
                        <option value="1">Apartamento</option>
                        <option value="2">Casa</option>
                        <option value="3">Casa en condominio</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="estrato">Estrato</label>
                    <select id="estrato" class="form-control">
                        <option value="0">Seleccione una opción</option>
                        <!-- <option value="2">2</option> -->
                        <option value="1">No tenga esa información</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>

                    <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $_SESSION['idUsuario']; ?>">
                    <input type="hidden" name="correoAnalista" id="correoAnalista" value="<?php echo $correoAnalista; ?>">
                    <input type="hidden" name="usu_cel" id="usu_cel" value="<?php echo $usu_cel; ?>">
                    <input type="hidden" name="usu_email" id="usu_email" value="<?php echo $usu_email; ?>">      
                    <input type="hidden" name="nombre_analista" id="nombre_analista" value="<?php echo $nombre_analista; ?>">      
                </div>
            </div>


            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="noPiso">No. de piso del apartamento</label>
                    <select id="noPiso" class="form-control noPiso">
                        <option value="0">Seleccione una opción</option>
                        <?php
                        for ($i = 1; $i <= 50; $i++) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <!-- colocar numero de pisos hasta el 50 y con select2 -->
                <div class="form-group">
                    <label for="noPisosEdi">No. pisos total del edificio</label>
                    <select id="noPisosEdi" class="form-control noPisosEdi ">
                        <option value="0">Seleccione una opción</option>
                        <?php
                        for ($i = 1; $i <= 50; $i++) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="tipoConstruccion">Tipo de construcción</label>
                        <i class="fa fa-solid fa-circle-info tooltip-tipo-construccion" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-tipo-construccion" data-placement="bottom"></i>
                    </div>
                    <select id="tipoConstruccion" class="form-control tipoConstruccion validateDataHogar">
                        <option value="0">Seleccione una opción</option>
                        <option value="3">Concreto reforzado</option>
                        <option value="2">Mamposteria</option>
                        <option value="4">Acero</option>
                        <option value="1">Otro</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="nroSotanos">Número de sótanos</label>
                    </div>
                    <input id="nroSotanos" class="form-control nroSotanos validateDataHogar" type="number" min="0" step="1">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <!-- colocar desde el 1985 hasta el 2025 y con select2 -->
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorEnseres">Año de construcción</label>
                        <i class="fa fa-solid fa-circle-info tooltip-anio-construccion" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-anio-construccion" data-placement="bottom"></i>
                    </div>
                    <select id="anioConstruccion" class="form-control anioConstruccion validateDataHogar">
                        <option value="0">Seleccione una opción</option>
                        <?php
                        for ($i = 2025; $i >= 1990; $i--) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="areaTotal">Área total vivienda (M²)</label>
                        <i class="fa fa-solid fa-circle-info tooltip-area" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-area" data-placement="bottom"></i>
                    </div>
                    <input id="areaTotal" class="form-control areaTotal validateDataHogar" type="number" min="0" step="1">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="zonaConstruccion">Zona de la vivienda</label>
                    <select id="zonaConstruccion" class="form-control zonaConstruccion validateDataHogar">
                        <option value="0">Seleccione una opción</option>
                        <option value="1">Urbana</option>
                        <option value="2">Rural</option>
                        <!-- <option value="3">Rural con perímetro urbano</option> -->
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="creditoHipotecario" style="font-size: 12.5px;">¿La vivienda tiene crédito hipotecario vigente?</label>
                    </div>
                    <div style="display: flex; flex-direction: row; gap: 40px;">
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="siCredito" name="creditoHipotecarioRadio" value="si">
                            <p style="margin:0; font-weight: bold ">Si</p>
                        </div>
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="noCredito" name="creditoHipotecarioRadio" value="no" checked>
                            <p style="margin:0; font-weight: bold">No</p>
                        </div>
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="noseCredito" name="creditoHipotecarioRadio" value="nose">
                            <p style="margin:0; font-weight: bold">No se</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="tipoAseg">¿Que desea asegurar?</label>
                        <!-- <i class="fa fa-solid fa-circle-info tooltip-tipo-asegurado" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-tipo-asegurado" data-placement="bottom"></i> -->
                    </div>
                    <select id="tipoAseg" class="form-control tipoAseg validateDataHogar">
                        <option value="0">Seleccione una opción</option>
                        <option value="1">Solo la estructura</option>
                        <option value="2">Solo los contenidos</option>
                        <option value="3">Estructura y sus contenidos</option>
                        <option value="4">Deudor</option>
                    </select>
                </div>
            </div>

            <!-- <div class="col-xs-12 col-sm-6 col-md-3 vidaDeudorQ" style="display: none;">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="vidaDeudorQ">¿Desea agregar seguro de vida deudor?</label> -->
                        <!-- <i class="fa fa-solid fa-circle-info tooltip-tipo-asegurado" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-tipo-asegurado" data-placement="bottom"></i> -->
                    <!-- </div>
                    <select id="vidaDeudorQ" class="form-control vidaDeudorQ validateDataHogar"style="display: none;" disabled>
                        <option value="0">Seleccione una opción</option>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div> -->

            <!-- <div class="col-xs-12 col-sm-6 col-md-5">
                <div class="form-group">
                    <label for=" inputEYC">Tipo de cobertura</label>
                    <div style="display: flex; flex-direction: row; gap: 15px; padding-left: 5px; padding-top: 10px; align-items: flex-start;">
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="inputEYC" name="tipoCoberturaRadio" value="estYCont" class="inputsAllianz">
                            <p style="margin:0; font-weight: bold ">Estructura y Contenidos</p>
                        </div>
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="estructura" name="tipoCoberturaRadio" value="est" class="inputsAllianz">
                            <p style="margin:0; font-weight: bold">Estructura</p>
                        </div>
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="contenidos" name="tipoCoberturaRadio" value="cont" class="inputsAllianz">
                            <p style="margin:0; font-weight: bold">Contenidos</p>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-xs-12 col-sm-6 col-md-3" id="btnSiguiente">
                <button class="btn btn-primary btn-block btn-cot" id="btnDataHogarSiguiente">Siguiente</button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="spinner-container" id="spinener-cot-salud">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="vistas\components\formCotizacion\css\styles.css">
</div>


