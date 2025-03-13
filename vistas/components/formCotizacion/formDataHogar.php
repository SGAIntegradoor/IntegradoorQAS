

<div id="formHogar" style="display: none;">
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="row row-aseg" style="margin-bottom: 0px;">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <!-- titulo del form para tipo de persona -->
                    <label id="lblDataCot">DATOS DEL BIEN ASEGURADO</label>
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
        <div class="row" id="">
            <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 20px;"></div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="dirInmueble">Dirección del inmueble</label>
                    <input id="dirInmueble" class="form-control dirInmueble validateDataHogar" type="text">
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

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group" style="position: relative;">
                    <label for="zonaRiesgo">Zona de riesgo</label>
                    <select id="zonaRiesgo" class="form-control zonaRiesgo"></select>
                    <span id="loaderZonaRiesgo" class="loader-spinner">
                    Cargando... <i class="fas fa-spinner fa-spin" style="color: #88D600;"></i>
                    </span>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3" style="display: none;" id="subZoneBog" >
                <div class="form-group">
                    <label for="subZona">Barrio Localidad (Sub Zona)</label>
                    <select id="subZona" class="form-control subZona"></select>
                    <span id="loaderSubZona" class="loader-spinner-subZona">
                    Cargando... <i class="fas fa-spinner fa-spin" style="color: #88D600;"></i>
                    </span>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 20px; padding-bottom: 20px;"></div>

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
                        <label for="tipoConstruccion">Tipo de construccion</label>
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

            <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 20px; padding-bottom: 20px;"></div>

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
                        for ($i = 1980; $i <= 2025; $i++) {
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
                    <input id="areaTotal" class="form-control areaTotal validateDataHogar" type="number">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="zonaConstruccion">Zona de construcción</label>
                    <select id="zonaConstruccion" class="form-control zonaConstruccion validateDataHogar">
                        <option value="0">Seleccione una opción</option>
                        <option value="1">Urbana</option>
                        <option value="2">Rural</option>
                        <option value="3">Rural con perímetro urbano</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="creditoHipotecario" style="font-size: 12.5px; margin-bottom: 12px;">¿La vivienda tiene credito hipotecario vigente?</label>
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

            <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 20px; padding-bottom: 20px;"></div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="tipoAseg">Tipo de asegurado</label>
                        <i class="fa fa-solid fa-circle-info tooltip-tipo-asegurado" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-tipo-asegurado" data-placement="bottom"></i>
                    </div>
                    <select id="tipoAseg" class="form-control tipoAseg validateDataHogar">
                        <option value="0">Seleccione una opción</option>
                        <option value="1">Propietario que arrienda</option>
                        <option value="2">Inquilino</option>
                        <option value="3">Propietario que habita</option>
                        <option value="4">Deudor</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-5">
                <div class="form-group">
                    <label for=" inputEYC">Tipo de cobertura</label>
                    <div style="display: flex; flex-direction: row; gap: 15px; padding-left: 5px; padding-top: 10px; align-items: flex-start;">
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="inputEYC" name="tipoCoberturaRadio" value="estYCont">
                            <p style="margin:0; font-weight: bold ">Estructura y Contenidos</p>
                        </div>
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="estructura" name="tipoCoberturaRadio" value="est">
                            <p style="margin:0; font-weight: bold">Estructura</p>
                        </div>
                        <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                            <input type="radio" id="contenidos" name="tipoCoberturaRadio" value="cont">
                            <p style="margin:0; font-weight: bold">Contenidos</p>
                        </div>
                    </div>
                </div>
            </div>

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
</div>

<div id="myModalHogar" style="display: none;">
    <div class="header-modal-hogar">
        A continuación escriba la dirección la dirección del inmueble, segun el formato solicitado
    </div>
    <div class="col-lg-12" id="realModalHogar">
        <div class="row" style="margin-bottom: 0px; margin-top: 30px; gap: 5px;" id="divFields">
            <div><b>Sección principal</b></div>
            <div class="row" style="margin-bottom: 20px;margin-top: 18px;">
                <div class="col-xs-12 col-sm-6 col-md-12 form-group" style="display: flex; flex-direction: row; gap: 20px;">
                    <select type="text" class="form-control" name="" id="1m" placeholder="" required style="width: 120px; height: 30px;">
                        <option selected="selected" value=""> Seleccione ...</option>
                        <option value="AU">Autopista</option>
                        <option value="AV">Avenida</option>
                        <option value="AC">Avenida Calle</option>
                        <option value="AK">Avenida Carrera</option>
                        <option value="BL">Bulevar</option>
                        <option value="CL">Calle</option>
                        <option value="KR">Carrera</option>
                        <option value="CT">Carretera</option>
                        <option value="CQ">Circular</option>
                        <option value="CV">Circunvalar</option>
                        <option value="CC">Cuentas Corridas</option>
                        <option value="DG">Diagonal</option>
                        <option value="KM">Kilometro</option>
                        <option value="PJ">Pasaje</option>
                        <option value="PJ">Paseo</option>
                        <option value="PT">Peatonal</option>
                        <option value="TV">Transversal</option>
                        <option value="TC">Troncal</option>
                        <option value="VT">Variante</option>
                        <option value="VDA">Vereda</option>
                        <option value="VI">Vía</option>
                    </select>
                    <input type="text" class="form-control inputNumber" name="" id="2m" placeholder="" required style="width: 50px;">
                    <select type="text" class="form-control" name="" id="3m" placeholder="" style="width: 70px;">
                        <option selected="selected" value=""></option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                        <option value="H">H</option>
                        <option value="I">I</option>
                        <option value="J">J</option>
                        <option value="K">K</option>
                        <option value="L">L</option>
                        <option value="M">M</option>
                        <option value="N">N</option>
                        <option value="O">O</option>
                        <option value="P">P</option>
                        <option value="Q">Q</option>
                        <option value="R">R</option>
                        <option value="S">S</option>
                        <option value="T">T</option>
                        <option value="U">U</option>
                        <option value="V">V</option>
                        <option value="W">W</option>
                        <option value="X">X</option>
                        <option value="Y">Y</option>
                        <option value="Z">Z</option>
                        <option value="AA">AA</option>
                        <option value="BB">BB</option>
                        <option value="CC">CC</option>
                        <option value="DD">DD</option>
                        <option value="EE">EE</option>
                        <option value="FF">FF</option>
                        <option value="GG">GG</option>
                        <option value="HH">HH</option>
                        <option value="II">II</option>
                        <option value="JJ">JJ</option>
                        <option value="KK">KK</option>
                        <option value="LL">LL</option>
                        <option value="MM">MM</option>
                        <option value="NN">NN</option>
                        <option value="OO">OO</option>
                        <option value="PP">PP</option>
                        <option value="QQ">QQ</option>
                        <option value="RR">RR</option>
                        <option value="SS">SS</option>
                        <option value="TT">TT</option>
                        <option value="UU">UU</option>
                        <option value="VV">VV</option>
                        <option value="WW">WW</option>
                        <option value="XX">XX</option>
                        <option value="YY">YY</option>
                        <option value="ZZ">ZZ</option>
                    </select>
                    <input type="text" class="form-control" name="" id="4m" placeholder="" style="width: 100px;">
                    <select type="text" class="form-control" name="" id="5m" placeholder="" style="width: 120px;">
                        <option selected="selected" value=""> Seleccione ...</option>
                        <option value="ESTE">Este</option>
                        <option value="NORTE">Norte</option>
                        <option value="OESTE">Oeste</option>
                        <option value="SUR">Sur</option>
                    </select>
                    <input type="text" class="form-control" name="" id="6m" placeholder="" style="width: 33px;" value="#" disabled style="padding: 0;">
                    <input type="text" class="form-control inputNumber" name="" id="7m" placeholder="" required style="width: 90px;">
                    <select type="text" class="form-control" name="" id="8m" placeholder="" style="width: 70px;">
                        <option selected="selected" value=""></option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                        <option value="H">H</option>
                        <option value="I">I</option>
                        <option value="J">J</option>
                        <option value="K">K</option>
                        <option value="L">L</option>
                        <option value="M">M</option>
                        <option value="N">N</option>
                        <option value="O">O</option>
                        <option value="P">P</option>
                        <option value="Q">Q</option>
                        <option value="R">R</option>
                        <option value="S">S</option>
                        <option value="T">T</option>
                        <option value="U">U</option>
                        <option value="V">V</option>
                        <option value="W">W</option>
                        <option value="X">X</option>
                        <option value="Y">Y</option>
                        <option value="Z">Z</option>
                        <option value="AA">AA</option>
                        <option value="BB">BB</option>
                        <option value="CC">CC</option>
                        <option value="DD">DD</option>
                        <option value="EE">EE</option>
                        <option value="FF">FF</option>
                        <option value="GG">GG</option>
                        <option value="HH">HH</option>
                        <option value="II">II</option>
                        <option value="JJ">JJ</option>
                        <option value="KK">KK</option>
                        <option value="LL">LL</option>
                        <option value="MM">MM</option>
                        <option value="NN">NN</option>
                        <option value="OO">OO</option>
                        <option value="PP">PP</option>
                        <option value="QQ">QQ</option>
                        <option value="RR">RR</option>
                        <option value="SS">SS</option>
                        <option value="TT">TT</option>
                        <option value="UU">UU</option>
                        <option value="VV">VV</option>
                        <option value="WW">WW</option>
                        <option value="XX">XX</option>
                        <option value="YY">YY</option>
                        <option value="ZZ">ZZ</option>
                    </select>
                    <input type="text" class="form-control" name="" id="9m" placeholder="" style="width: 33px;" value="-" disabled style="padding: 0;">
                    <input type="text" class="form-control inputNumber" name="" id="10m" placeholder="" required style="width: 90px;">
                    <select type="text" class="form-control" name="" id="11m" placeholder="" style="width: 120px;">
                        <option selected="selected" value=""> Seleccione ...</option>
                        <option value="ESTE">Este</option>
                        <option value="NORTE">Norte</option>
                        <option value="OESTE">Oeste</option>
                        <option value="SUR">Sur</option>
                    </select>
                </div>
            </div>
            <div><b>Complemento</b></div>
            <div class="row" style="margin-bottom: 30px;margin-top: 18px;">
                <div class="col-xs-12 col-sm-6 col-md-12 form-group" style="display: flex; flex-direction: row; gap: 20px;">
                    <select type="text" class="form-control" name="" id="12m" placeholder="" required style="width: 140px;">
                        <option selected="selected" value=""> Seleccione ...</option>
                        <option value="AD">Administración</option>
                        <option value="AG">Agrupación</option>
                        <option value="AL">Altillo</option>
                        <option value="AP">Apartamento</option>
                        <option value="BR">Barrio</option>
                        <option value="BQ">Bloque</option>
                        <option value="BG">Bodega</option>
                        <option value="CS">Casa</option>
                        <option value="CU">Célula</option>
                        <option value="CU">Centro Comercial</option>
                        <option value="CD">Ciudadela</option>
                        <option value="CO">Conjunto Residencial</option>
                        <option value="CN">Consultorio</option>
                        <option value="DP">Deposito</option>
                        <option value="DS">Deposito Sótano</option>
                        <option value="ED">Edificio</option>
                        <option value="EN">Entrada</option>
                        <option value="EQ">Esquina</option>
                        <option value="ES">Estación</option>
                        <option value="ERT">Etapa</option>
                        <option value="EX">Exterior</option>
                        <option value="FI">Finca</option>
                        <option value="GA">Garaje</option>
                        <option value="GS">Garaje Sótano</option>
                        <option value="IN">Interior</option>
                        <option value="KM">Kilometro</option>
                        <option value="KM">Local</option>
                        <option value="LC">Local</option>
                        <option value="LT">Lote</option>
                        <option value="MZ">Manzana</option>
                        <option value="LM">Mezzanine</option>
                        <option value="MN">Mezzanine</option>
                        <option value="MD">Módulo</option>
                        <option value="OF">Oficina</option>
                        <option value="PQ">Parque</option>
                        <option value="PA">Parqueadero</option>
                        <option value="PN">Pent-House</option>
                        <option value="PI">Piso</option>
                        <option value="PL">Planta</option>
                        <option value="PR">Porteria</option>
                        <option value="PD">Predio</option>
                        <option value="PU">Puesto</option>
                        <option value="RP">Round Point</option>
                        <option value="SC">Sector</option>
                        <option value="SS">Semisótano</option>
                        <option value="SO">Sótano</option>
                        <option value="ST">Suite</option>
                        <option value="SM">Supermanzana</option>
                        <option value="TZ">Terraza</option>
                        <option value="TO">Torre</option>
                        <option value="UN">Unidad</option>
                        <option value="UL">Unidad Residencial</option>
                        <option value="UR">Urbanización</option>
                        <option value="ZN">Zona</option>
                    </select>
                    <input type="text" class="form-control" name="" id="13m" required placeholder="" style="width: 195px;">
                    <input type="text" class="form-control" name="" id="14m" placeholder="" style="width: 195px;">
                </div>
            </div>
            <div><b>Dirección</b></div>
            <div class="row" style="margin-top: 18px;">
                <div class="col-xs-12 col-sm-6 col-md-12 form-group" style="display: flex; flex-direction: row; justify-content: space-between; gap: 20px;">
                    <input type="text" class="form-control" name="" id="15m" placeholder="" style="width: 570px;" disabled>
                    <div style="display: flex; flex-direction: row; gap: 30px;">
                        <button class="btn btn-primary btn-modal" onclick="closeModalAddress(true)" id="btnHogarSiguiente" style="width: 150px; background: black">Cancelar</button>
                        <button class="btn btn-primary btn-modal" onclick="saveToFrontAddress()" id="btnHogarSiguiente" style="width: 200px">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>