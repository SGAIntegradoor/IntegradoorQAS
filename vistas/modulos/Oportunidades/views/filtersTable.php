    <link rel="stylesheet" href="vistas/modulos/Oportunidades/css/styles.css">
    <div class="container-fluid mainDataContainer">
        <div class="col-lg-12">
            <div class="row row-filters">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <label id="lblDataTrip">Consulta Avanzada</label>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div id="masCotizacion">
                        <p id="masCots">Ver más <i class="fa fa-plus-square-o"></i></p>
                    </div>
                    <div id="menosCotizacion">
                        <p id="menosCots">Ver menos <i class="fa fa-minus-square-o"></i></p>
                    </div>
                </div>
            </div>
            <div id="filtersSearch">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="mesExpedicion">Mes Expedición:</label>
                            <select type="text" class="form-control mes-expedicion" name="mesExpedicion" id="mesExpedicion" placeholder="Mes expedición">
                                <option value="">
                                </option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                    </div>

                    <!-- 

                    Estados:

                    1. Pdte orden inspección
                    2. Pdte inspección
                    3. Pdte emisión
                    4. emitida
                    5. perdido
                     
                    -->
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <label for="estado">Estado:</label>
                        <select class="form-control estado" name="estado" id="estado">
                            <option value="" selected></option>
                            <option value="1">Pdte orden inspección</option>
                            <option value="2">Pdte inspección</option>
                            <option value="3">Pdt emisión</option>
                            <option value="4">Emitida</option>
                            <option value="5">Cambio de Intermediario</option>
                            <option value="6">Perdido</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="nombreAsesor">Nombre Asesor:</label>
                            <select type="text" class="form-control nombre-asesor" name="nombreAsesor" id="nombreAsesor" placeholder="Nombre Asesor">
                                <option value=""></option>
                            </select> <!-- Cierra correctamente aquí -->
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="analistaGA">Analista/Asesor GA:</label>
                            <select id="analistaGA" class="form-control analista-ga">
                                <option value="">
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="aseguradoraOpo">Aseguradora:</label>
                            <select type="text" class="form-control asegurado-opo" name="aseguradoraOpo" id="aseguradoraOpo" placeholder="Aseguradora">
                                <option value="" selected>
                                </option>
                                <option value="1">Allianz</option>
                                <option value="2">AXA Colpatria</option>
                                <option value="3">Seguros Bolivar</option>
                                <option value="4">Equidad</option>
                                <option value="5">Seguros del Estado</option>
                                <option value="6">HDI (Antes Liberty)</option>
                                <option value="7">Mapfre</option>
                                <option value="8">Previsora Seguros</option>
                                <option value="9">SBS Seguros</option>
                                <option value="10">Solidaria</option>
                                <option value="11">Zurich</option>
                                <option value="12">Mundial</option>
                                <option value="13">AssistCard</option>
                                <option value="14">AssistOne</option>
                                <option value="15">Universal</option>
                                <option value="16">Continental</option>
                                <option value="17">Olivos</option>
                                <option value="18">Sura</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <label for="ramo">Ramo:</label>
                        <select class="form-control ramo" name="ramo" id="ramo">
                            <option value="" selected></option>
                            <option value="1">Automoviles</option>
                            <option value="2">Pesados</option>
                            <option value="3">Motos</option>
                            <option value="4">RCE autos</option>
                            <option value="5">Exequial</option>
                            <option value="6">Salud</option>
                            <option value="7">Pyme</option>
                            <option value="8">Vida</option>
                            <option value="9">Vida deudor</option>
                            <option value="10">Hogar</option>
                            <option value="11">Hogar deudor</option>
                            <option value="12">Asistencia en viajes</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="onerosoOp">Oneroso:</label>
                            <select type="text" class="form-control oneroso-op" name="onerosoOp" id="onerosoOp" placeholder="Oneroso">
                                <option value="" selected></option>
                                <option value="1">
                                    Si
                                </option>
                                <option value="2">
                                    No
                            </select> <!-- Cierra correctamente aquí -->
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="formaDePago">Forma de pago:</label>
                            <select id="formaDePago" class="form-control forma-pago">
                                <option value="" selected>
                                </option>
                                <option value="1">
                                    Financiada
                                </option>
                                <option value="2">
                                    Contado
                                </option>
                                <option value="3">
                                    Pdte.
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="financiera">Financiera:</label>
                            <select type="text" class="form-control financiera" name="financiera" id="financiera" placeholder="Financiera">
                                <option value="" selected>
                                </option>
                                <option value="1">Finesa</option>
                                <option value="3">CrediMapfre</option>
                                <option value="2">Liberty</option>
                                <option value="4">Bolivar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="carpeta">Carpeta:</label>
                            <select type="text" class="form-control carpeta" name="carpeta" id="carpeta" placeholder="Carpeta">
                                <option value="" selected>
                                </option>
                                <option value="1">Si</option>
                                <option value="2">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <button class="btn btn-primary btn-block btnConsultar" onclick="searchInfo()">Consultar</button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <button class="btn btn-primary btn-block btnCancelar" onclick="reset()">Limpiar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\cotizador.css">
    <link rel="stylesheet" href="vistas\modulos\Oportunidades\css\styles.css">