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

<div id="formValoresAllianz" style="display: none; margin-top:20px">
    <div class="container-fluid">
        <div class="">
            <div class="row row-aseg" style="margin-bottom: 0px;">
                <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 10px;">
                    <!-- titulo del form para tipo de persona -->
                    <label id="lblValoresCotAllianz">Datos Aseguradora Allianz</label>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div id="masValoresHogarAllianz">
                        <p id="masValCotAllianz">Ver más <i class="fa fa-plus-square-o" aria-hidden="true"></i></p>
                    </div>
                    <div id="menosValoresHogarAllianz">
                        <p id="menosValCotAllianz">Ver menos <i class="fa fa-minus-square-o" aria-hidden="true"></i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;">
        <input type="text" name="idCliente" id="idCliente">
    </div>
    <div class="general-container-aseg" id="containerValoresAllianz" style="padding-bottom: 20px;">
        <div class="row" style="margin-top: 15px;" id="valoresAllianz">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="dirInmuebleAllianz">Dirección del inmueble</label>
                    <input id="dirInmuebleAllianz" class="form-control dirInmueble" type="text">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="sotanosAllianz">Número de sotanos</label>
                    <input id="sotanosAllianz" class="form-control sotanos" type="num" min="0">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">

                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorViviendaAllianz">Valor de la vivienda</label>
                        <i class="fa fa-solid fa-circle-info tooltip-vlvivienda" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-vlvivienda" data-placement="bottom"></i>
                    </div>
                    <input id="valorViviendaAllianz" class="form-control valorVivienda inputNumber validate contentsAllianz" type="text">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorContenidosAllianz">Valor Contenidos</label>
                        <i class="fa fa-solid fa-circle-info tooltip-icon-contenidos" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-icon-contenidos" data-placement="bottom"></i>
                    </div>
                    <input id="valorContenidosAllianz" class="form-control valorContenidosAllianz inputNumber valores validate contentsAllianz" type="text">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorHurtoAllianz">Valor Hurto</label>
                        <i class="fa fa-solid fa-circle-info tooltip-hurto" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-hurto" data-placement="bottom"></i>
                    </div>
                    <input id="valorHurtoAllianz" class="form-control valorHurtoAllianz inputNumber valores validate contentsAllianz" type="text">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                        <label for="valorTodoRiesgoAllianz">Valor todo riesgo</label>
                        <i class="fa fa-solid fa-circle-info tooltip-todo-riesgo" style="margin-top: 4px; margin-top: 2px; font-size: 18px;" data-toggle="tooltip-todo-riesgo" data-placement="bottom"></i>
                    </div>
                    <input id="valorTodoRiesgoAllianz" class="form-control valorTodoRiesgoAllianz inputNumber valores validate contentsAllianz" type="text">
                </div>
            </div>
        </div>

        <div id="preguntaMascotas" style="display: none; margin-top: 30px; margin-bottom: 30px;">
            <div class="col-xs-12 col-sm-6 col-md-3" style="display: flex; flex-direction: row; gap: 5px; align-items: center; padding-left: 0px; ">
                <label for="mascotaAllianz" style="margin-bottom: 0px">¿Desea incluir Asistencia Mascotas?</label>
                <i class="fa fa-solid fa-circle-info tooltip-asist-mascotas" style="margin-top: 2px; font-size: 18px;" data-toggle="tooltip-asist-mascotas" data-placement="top"></i>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3" id="mascotaAllianz" style="display: flex; flex-direction: row; gap: 15px; padding-left: 5px; align-items: center;">
                <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                    <input type="radio" id="siGato" name="mascotasRadio" class="inputsAllianz">
                    <p style="margin:0; font-weight: bold;">Si, gato</p>
                </div>
                <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                    <input type="radio" id="siPerro" name="mascotasRadio" class="inputsAllianz">
                    <p style="margin:0; font-weight: bold;">Si, perro</p>
                </div>
                <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                    <input type="radio" id="no" name="mascotasRadio" class="inputsAllianz">
                    <p style="margin:0; font-weight: bold;">No</p>
                </div>
            </div>

        </div>

        <div id="preguntaSBS" style="display: flex; margin-top: 50px; margin-bottom: 15px; font-weight: bold; font-size: 15px; align-items: center;">
            <div class="col-xs-12 col-sm-6 col-md-5" style="padding-left: 0px;">
                <label for="preguntaSBSCotizar" style="margin-bottom: 0px;">¿Deseas incluir cotización del seguro de hogar con SBS Seguros?</label>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3" id="preguntaSBSCotizar" style="display: flex; flex-direction: row; gap: 15px; padding-left: 5px; align-items: center;">
                <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                    <input type="radio" id="siSBS" name="sbsRadio" class="inputsAllianz">
                    <p style="margin:0;">Si</p>
                </div>
                <div style="display: flex; flex-direction: row; gap: 5px; align-items: flex-start;">
                    <input type="radio" id="noSBS" name="sbsRadio" class="inputsAllianz">
                    <p style="margin:0;">No</p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3" id="btnAllianzCot">
                <button type="button" class="btn btn-primary" style="width: 70%;" id="btnCotizarSBS">Cotizar</button>
            </div>
        </div>


    </div>

</div>
</div>