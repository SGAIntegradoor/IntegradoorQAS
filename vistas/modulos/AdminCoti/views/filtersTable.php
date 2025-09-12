<link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\cotizador.css">
<link rel="stylesheet" href="vistas\modulos\Oportunidades\css\styles.css">
<style>
    .btnConsultar,
    .btnCancelar {
        margin-top: 25px;
    }
</style>
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
                        <label for="moduloCotizacion">Módulo de cotización:</label>
                        <select type="text" class="form-control" name="moduloCotizacion" id="moduloCotizacion" placeholder="Modulo de cotización">
                            <option value=""></option>
                            <option value="1">Livianos Familiares</option>
                            <option value="5">Livianos Utilitarios</option>
                            <option value="2">Pesados</option>
                            <option value="3">Motos</option>
                            <option value="4">Transporte Pasajeros</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                    <label for="clase">Clase de Vehículo:</label>
                    <select class="form-control" name="clase" id="clase"> <!-- id="clase" -->
                        <option value=""></option>
                        <option value="AUTOMOVIL">AUTOMÓVIL</option>
                        <option value="BUS / BUSETA / MICROBUS">BUS / BUSETA / MICROBUS</option>
                        <option value="CAMION">CAMIÓN</option>
                        <option value="CAMIONETA PASAJEROS">CAMIONETA PASAJEROS</option>
                        <option value="CAMIONETA REPARTICION">CAMIONETA REPARTICIÓN</option>
                        <option value="CAMPERO">CAMPERO</option>
                        <option value="CARROTANQUE">CARROTANQUE</option>
                        <option value="CHASIS">CHASIS</option>
                        <option value="CUATRIMOTO">CUATRIMOTO</option>
                        <option value="FURGON">FURGÓN</option>
                        <option value="MOTOCARRO">MOTOCARRO</option>
                        <option value="MOTOCICLETA">MOTOCICLETA</option>
                        <option value="PESADO">PESADO (SIN CLASIFICAR)</option>
                        <option value="PICKUP">PICKUP</option>
                        <option value="REMOLCADOR">REMOLCADOR</option>
                        <option value="REMOLQUE">REMOLQUE</option>
                        <option value="TAXI">TAXI</option>
                        <option value="TRAILER">TRAILER</option>
                        <option value="SUV">SUV</option>
                        <option value="VAN">VAN</option>
                        <option value="VOLQUETA">VOLQUETA</option>
                        <option value="DESCONOCIDO">DESCONOCIDO</option>
                    </select>
                </div>

                <?php if ($_SESSION["rol"] == 22 || $_SESSION["rol"] == 6 || $_SESSION["rol"] == 10) { ?>

                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <label for="canal">Canal:</label>
                        <select class="form-control" name="canal" id="canal">
                            <option value="" selected></option>
                            <option value="1">Directo</option>
                            <option value="2">Freelance</option>
                        </select>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                        <div class="form-group">
                            <label for="analistaGA">Analista/Asesor GA:</label>
                            <select id="analistaGA" class="form-control">
                                <option value="">
                                </option>
                            </select>
                        </div>

                    </div>

            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                    <div class="form-group">
                        <label for="nombreAsesor">Asesor freelance:</label>
                        <select type="text" class="form-control" name="nombreAsesor" id="nombreAsesor" placeholder="Nombre Asesor">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <div class="col-xs-12 col-sm-6 col-md-3" style="padding-left: 25px; padding-right: 25px">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 15px; padding-right: 5px">
                        <div class="form-group">
                            <button class="btn btn-primary btn-block btnConsultar" onclick="searchInfo()">Consultar</button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 15px; padding-right: 5px">
                        <div class="form-group">
                            <button class="btn btn-primary btn-block btnCancelar" onclick="reset()">Limpiar</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>