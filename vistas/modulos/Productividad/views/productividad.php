    <!-- <link rel="stylesheet" href="vistas/modulos/Oportunidades/css/styles.css"> -->
    
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

            <div id="filtersSearch" class="container-fluid">
                <div class="row">
                    <!-- Año de expedición -->
                    <div class="col-md-3">
                    <div class="form-group">
                        <label for="anioExpedicion">Año Expedición:</label>
                        <select class="form-control" id="anioExpedicionPro" name="anioExpedicion">
                        <option value="">Seleccione año</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        </select>
                    </div>
                    </div>

                    <!-- Mes de expedición -->
                    <div class="col-md-3">
                    <div class="form-group">
                        <label for="mesExpedicion">Mes Expedición:</label>
                        <select class="form-control" id="mesExpedicionPro" name="mesExpedicion">
                        <option value="">Seleccione mes</option>
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

                    <!-- Nombre asesor -->
                    <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombreAsesor">Nombre Asesor:</label>
                        <select class="form-control" id="nombreAsesorPro" name="nombreAsesor">
                        <option value=""></option>
                        </select>
                    </div>
                    </div>

                    <!-- Analista/Asesor GA -->
                    <div class="col-md-3">
                    <div class="form-group">
                        <label for="analistaGA">Analista/Asesor GA:</label>
                        <select class="form-control" id="analistaGAPro" name="analistaGA">
                        <option value=""></option>
                        </select>
                    </div>
                    </div>
                </div>

                <div class="row">
    <!-- Ramo -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="ramo">Ramo:</label>
            <select class="form-control" id="ramoPro" name="ramo">
                <option value="">Seleccione</option>
                <option value="1">Automóviles</option>
                <option value="2">Salud</option>
                <option value="3">Asistencia en viajes</option>
            </select>
        </div>
    </div>

    <!-- Botón consultar -->
    <div class="col-md-3">
        <div class="form-group d-flex align-items-end h-100 btn-search">
            <button class="btn btn-primary btn-block" onclick="searchInfo()">Consultar</button>
        </div>
    </div>

    <!-- Spinner -->
    <div class="col-md-1">
        <div id="loader" class="loader btn-search" style="display: none; text-align: center;">
            <div class="spinner"></div>
        </div>
    </div>
</div>
            </div>

        </div>
        <?php include_once './vistas/modulos/Productividad/views/productividadTable.php'; ?>
    </div>


   
 
   