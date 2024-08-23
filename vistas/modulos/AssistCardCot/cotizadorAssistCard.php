    <div class="container-fluid mainDataContainer">
        <div class="col-lg-12">
            <div class="row row-aseg">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <label id="lblDataTrip">Ingresa Información del Viaje a Cotizar</label>
                    <i id="iconDataTrip" class="fa fa-globe planeCot" aria-hidden="true"></i>
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

    <div class="container-fluid" id="containerDatos">



        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="nombreProspecto">Nombre viajero principal</label>
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="text" class="form-control" name="nombres" id="nombreProspecto" placeholder="Nombre viajero principal" required>
                        </div>
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
                            <select class="form-control fecha-nacimiento" name="anionacimiento" id="anionacimiento" required>
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
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="lugarOrigen">Lugar de origen</label>
                    <select id="lugarOrigen" class="form-control"></select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="lugarDestino">Lugar de destino</label>
                    <select id="lugarDestino" class="form-control"></select>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="fechaSalida">Fecha de salida</label>
                    <input type="date" id="fechaSalida" name="fechaSalida" class="form-control">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="fechaRegreso">Fecha de regreso</label>
                    <input type="date" id="fechaRegreso" name="fechaRegreso" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="motivoViaje">Motivo de viaje</label>
                    <select id="motivoViaje" class="form-control"></select>
                </div>
            </div>



            <div class="col-xs-12 col-sm-6 col-md-3" id="colradioPeople">
                <div class="form-group">
                    <label>¿Para cuántas personas es la cotización?</label><br>
                    <div class="form-check form-check-inline">
                        <span class="radio-container">
                            <input type="radio" id="lugarDestino1" name="lugarDestino" class="form-check-input radioPeople" checked>
                            <label for="lugarDestino1" class="form-check-label">1 Persona</label>
                        </span>
                        <span class="radio-container">
                            <input type="radio" id="lugarDestino2" name="lugarDestino" class="form-check-input" data-toggle="tooltip" data-placement="top" disabled
                                title="La siguiente cotización es para una sola persona. Si se requiere una cotización para un grupo (dos o más personas), debes comunicarte con tu analista comercial asignado.">
                            <label for="lugarDestino2" class="form-check-label">2 o más personas</label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 60px;">
            <div class="col-xs-12 col-sm-6 col-md-3" id="colBtnCotizar">

                <button class="btn btn-primary btn-block btn-cot" id="btnCotizarAsiss">Cotizar</button>

            </div>

            <div class="col-xs-12 text-center">
                <div class="spinner-container" id="spinener-cot">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="containerCards">
        <div class="col-lg-12">
            <div class="row row-aseg">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <label for="">PARRILLA DE COTIZACIONES</label>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3"></div>
                <div class="col-xs-12 col-sm-6 col-md-3"></div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div id="masParrilla">
                        <p id="masPar">Ver más <i class="fa fa-plus-square-o"></i></p>
                    </div>
                    <div id="menosParrilla">
                        <p id="menos">Ver menos <i class="fa fa-minus-square-o"></i></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" id="Cards">
            <div class="row">
                <div class="col-xs-12" style="padding-left: 0px;">
                    <p><strong>Nota: </strong>Esta cotización aplica para una sola persona. Si requieres una cotización para un grupo (2 o más personas), ponte en contacto con el equipo comercial a través de tu analista asignado.</p>
                    <p><strong>Nota: </strong>Los valores cotizados se encuentran en dolares americanos (USD).</p>
                </div>
            </div>
            <div class="row" id="row_contenedor_general"></div>
        </div>
    </div>

    <link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\cotizador.css">
    <script src="vistas\modulos\AssistCardCot\js\eventCotizarAssistCard.js?v=<?php echo (rand()); ?>"></script>