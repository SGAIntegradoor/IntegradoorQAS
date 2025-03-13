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
        <div class="col-lg-12">
            <div class="row row-aseg">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <!-- titulo del form para tipo de persona -->
                    <label id="lblValoresCot">DATOS ASEGURADORA SBS</label>
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
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorVivienda">Valor de la vivienda</label>
                        <i class="fa fa-solid fa-circle-info tooltip-vlvivienda" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-vlvivienda" data-placement="bottom"></i>
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
                    <input id="contentNormalesSUS" class="form-control contentNormalesSUS inputNumber valores" type="text">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="contEspecialesSUS">Contenidos especiales</label>
                        <i class="fa fa-solid fa-circle-info tooltip-contespesus" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-contespesus" data-placement="bottom"></i>
                    </div>
                    <input id="contEspecialesSUS" class="form-control contEspeciales inputNumber valores" type="text">
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
                        <i class="fa fa-solid fa-circle-info tooltip-valorasegdañosEE" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-valorasegdañosEE" data-placement="bottom"></i>
                    </div>
                    <input id="valorAseguradoD" class="form-control valorAseguradoD inputNumber" type="text" disabled>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <p id="valorAseguSus" style="margin: 0px; margin-bottom: 5px; font-weight: bold; width: 390px;">Valor asegurado sustracción equipo eléctrico y/o electrónico
                        <i class="fa fa-solid fa-circle-info tooltip-totalcontsushur" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-totalcontsushur" data-placement="bottom"></i>
                    </p>
                    <input id="valorAsegSUSEE" class="form-control valorAsegSUSEE inputNumber valores" type="text">
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
                        <i class="fa fa-solid fa-circle-info tooltip-todo-riesgo" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-todo-riesgo" data-placement="bottom"></i>
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