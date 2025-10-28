<style>
    .tooltip-inner {
        max-width: 400px !important;
        /* Aumenta el ancho máximo */
        width: 350px !important;
        /* Fija el ancho */
        text-align: justify;
        /* Justifica el texto */
        white-space: normal !important;
        /* Permite saltos de línea */
    }
</style>

<div id="formValores" style="display: none;">
    <div class="container-fluid">
        <div class="">
            <div class="row row-aseg">
                <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 10px;">
                    <!-- titulo del form para tipo de persona -->
                    <label id="lblValoresCot">Datos Aseguradora SBS</label>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div id="masValoresHogar">
                        <p id="masValCot">Ver más <i class="fa fa-plus-square-o" aria-hidden="true"></i></p>
                    </div>
                    <div id="menosValoresHogar">
                        <p id="menosValCot">Ver menos <i class="fa fa-minus-square-o" aria-hidden="true"></i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;">
        <input type="text" name="idCliente" id="idCliente">
    </div>
    <div class="general-container-aseg" id="containerValores">
        <div class="row" style="margin-top: 15px;">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="dirInmueble">Dirección del inmueble</label>
                    <input id="dirInmueble" class="form-control dirInmueble" type="text">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorVivienda">Valor de la vivienda</label>
                        <!-- <i class="fa fa-solid fa-circle-info tooltip-vlvivienda-SBS" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-vlvivienda-SBS" data-placement="bottom"></i> -->
                    </div>
                    <input id="valorVivienda" class="form-control valorVivienda inputNumber" type="text">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorEnseres">Val. muebles y enseres(No Electrico)</label>
                        <i class="fa fa-solid fa-circle-info tooltip-contEnseres" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-contEnseres" data-placement="bottom"></i>
                    </div>
                    <input id="valorEnseres" class="form-control valorEnseres inputNumber valores" type="text">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorEquipoElectrico">Valor Equipo Eléctrico y/o Electrónico</label>
                        <i class="fa fa-solid fa-circle-info tooltip-contEE" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-contEE" data-placement="bottom"></i>
                    </div>
                    <input id="valorEquipoElectrico" class="form-control valorEquipoElectrico inputNumber valores" type="text">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorCEspeciales">Valor contenidos especiales</label>
                        <i class="fa fa-solid fa-circle-info tooltip-contEspeciales" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-contEspeciales" data-placement="bottom"></i>
                    </div>
                    <input id="valorCEspeciales" class="form-control valorCEspeciales inputNumber valores" type="text">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="totalContenidos">Total contenidos</label>
                        <i class="fa fa-solid fa-circle-info tooltip-totalcont" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-totalcont" data-placement="bottom"></i>
                    </div>
                    <input id="totalContenidos" class="form-control totalContenidos inputNumber" type="text" disabled>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="totalCoberturaBasica">Total cobertura básica</label>
                    <input id="totalCoberturaBasica" class="form-control totalCoberturaBasica inputNumber" type="text" disabled>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 30px; margin-bottom: 45px;">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <u><b>COBERTURA OPCIONAL SUSTRACCION</b></u>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="contentNormalesSUS">Contenidos normales</label>
                        <i class="fa fa-solid fa-circle-info tooltip-contnorsus" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-contnorsus" data-placement="bottom"></i>
                    </div>
                    <input id="contentNormalesSUS" class="form-control contentNormalesSUS inputNumber" type="text" disabled>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="contEspecialesSUS">Contenidos especiales</label>
                        <i class="fa fa-solid fa-circle-info tooltip-contespesus" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-contespesus" data-placement="bottom"></i>
                    </div>
                    <input id="contEspecialesSUS" class="form-control contEspeciales inputNumber" type="text" disabled>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="totalContHurtoSus">Total contenidos sustracción</label>
                        <i class="fa fa-solid fa-circle-info tooltip-totalcontsus" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-totalcontsus" data-placement="bottom"></i>
                    </div>
                    <input id="totalContHurtoSus" class="form-control totalContHurtoSus inputNumber" type="text" disabled>
                </div>
            </div>

        </div>
        <div class="row" style="margin-top: 30px; margin-bottom: 45px;">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <u><b>EQUIPO ELÉCTRICO Y/O ELECTRÓNICO</b></u>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3"></div>
            <div class="col-xs-12 col-sm-6 col-md-3"></div>
            <div class="col-xs-12 col-sm-6 col-md-3">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorAseguradoD">Valor asegurado daños</label>
                    </div>
                    <input id="valorAseguradoD" class="form-control valorAseguradoD inputNumber" type="text" disabled>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <p id="valorAseguSus" style="margin: 0px; margin-bottom: 5px; font-weight: bold; width: 390px;">Valor asegurado sustracción equipo eléctrico y/o electrónico
                        <i class="fa fa-solid fa-circle-info tooltip-totalcontsushur" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-totalcontsushur" data-placement="bottom"></i>
                    </p>
                    <input id="valorAsegSUSEE" class="form-control valorAsegSUSEE inputNumber" type="text" disabled>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 30px; margin-bottom: 45px;">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <u><b>TODO RIESGO</b></u>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3"></div>
            <div class="col-xs-12 col-sm-6 col-md-3"></div>
            <div class="col-xs-12 col-sm-6 col-md-3">
            </div>
        </div>
        <div class="row" style="margin-bottom: 35px;">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorTodoRiesgo">Valor todo riesgo</label>
                        <i class="fa fa-solid fa-circle-info tooltip-todo-riesgo-SBS" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-todo-riesgo-SBS" data-placement="bottom"></i>
                    </div>
                    <input id="valorTodoRiesgo" class="form-control valorTodoRiesgo inputNumber valores" type="text">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <button class="btn btn-primary btn-block btn-cot" id="btnCotizar">Cotizar</button>
            </div>
        </div>
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
                        <option value="Autopista">Autopista</option>
                        <option value="Avenida">Avenida</option>
                        <option value="Avenida Calle">Avenida Calle</option>
                        <option value="Avenida Carrera">Avenida Carrera</option>
                        <option value="Bulevar">Bulevar</option>
                        <option value="Calle">Calle</option>
                        <option value="Carrera">Carrera</option>
                        <option value="Carretera">Carretera</option>
                        <option value="Circular">Circular</option>
                        <option value="Circunvalar">Circunvalar</option>
                        <option value="Cuentas Corridas">Cuentas Corridas</option>
                        <option value="Diagonal">Diagonal</option>
                        <option value="Kilometro">Kilometro</option>
                        <option value="Pasaje">Pasaje</option>
                        <option value="Paseo">Paseo</option>
                        <option value="Peatonal">Peatonal</option>
                        <option value="Transversal">Transversal</option>
                        <option value="Troncal">Troncal</option>
                        <option value="Variante">Variante</option>
                        <option value="Vereda">Vereda</option>
                        <option value="Via">Vía</option>
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
                    <select type="text" class="form-control" name="" id="4m" placeholder="" style="width: 100px;">
                        <option selected value=""></option>
                        <option value="BIS">BIS</option>
                    </select>
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
                        <option value="Administración">Administración</option>
                        <option value="Agrupación">Agrupación</option>
                        <option value="Altillo">Altillo</option>
                        <option value="Apartamento">Apartamento</option>
                        <option value="Barrio">Barrio</option>
                        <option value="Bloque">Bloque</option>
                        <option value="Bodega">Bodega</option>
                        <option value="Casa">Casa</option>
                        <option value="Célula">Célula</option>
                        <option value="Centro Comercial">Centro Comercial</option>
                        <option value="Ciudadela">Ciudadela</option>
                        <option value="Conjunto Residencial">Conjunto Residencial</option>
                        <option value="Consultorio">Consultorio</option>
                        <option value="Deposito">Deposito</option>
                        <option value="Deposito Sótano">Deposito Sótano</option>
                        <option value="Edificio">Edificio</option>
                        <option value="Entrada">Entrada</option>
                        <option value="Esquina">Esquina</option>
                        <option value="Estación">Estación</option>
                        <option value="Etapa">Etapa</option>
                        <option value="Exterior">Exterior</option>
                        <option value="Finca">Finca</option>
                        <option value="Garaje">Garaje</option>
                        <option value="Garaje Sótano">Garaje Sótano</option>
                        <option value="Interior">Interior</option>
                        <option value="Kilometro">Kilometro</option>
                        <option value="Local">Local</option>
                        <option value="Lote">Lote</option>
                        <option value="Manzana">Manzana</option>
                        <option value="Mezzanine">Mezzanine</option>
                        <option value="Módulo">Módulo</option>
                        <option value="Oficina">Oficina</option>
                        <option value="Parque">Parque</option>
                        <option value="Parqueadero">Parqueadero</option>
                        <option value="Pent-House">Pent-House</option>
                        <option value="Piso">Piso</option>
                        <option value="Planta">Planta</option>
                        <option value="Porteria">Porteria</option>
                        <option value="Predio">Predio</option>
                        <option value="Puesto">Puesto</option>
                        <option value="Round Point">Round Point</option>
                        <option value="Sector">Sector</option>
                        <option value="Semisótano">Semisótano</option>
                        <option value="Sotano">Sótano</option>
                        <option value="Suite">Suite</option>
                        <option value="Supermanzana">Supermanzana</option>
                        <option value="Terraza">Terraza</option>
                        <option value="Torre">Torre</option>
                        <option value="Unidad">Unidad</option>
                        <option value="Unidad Residencial">Unidad Residencial</option>
                        <option value="Urbanización">Urbanización</option>
                        <option value="Zona">Zona</option>
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