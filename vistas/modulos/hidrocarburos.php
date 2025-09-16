<head>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta2/css/all.css" integrity="sha384-OA4SkQ1hW5kfQF3/OBdzK99bg7sQKT6+yXxq5Iu7QvGrrkrBsX3p5SRy9CrJ0+Gx" crossorigin="anonymous">

</head>
<style>
    input[type="checkbox"] {
        content: "";
        width: 26px;
        height: 26px;
        border: 2px solid #ccc;
        background: #ddd;
    }

    .gray-header {
        color: #808080;
    }

    .divBoton {
        display: flex;
        justify-content: end;
    }

    .separador {
        margin-left: 15px;
    }

    .smaller-input {
        max-width: 200px;
        margin: 0 auto;
    }

    .input-addon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 325px;
        z-index: 1;
    }

    .placeholder {
        position: absolute;
        top: 0;
        left: 0;
        padding: 6px;
        color: #aaa;
        pointer-events: none;
        transition: all 0.2s;
    }

    .input-container {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .input-container .form-control {
        margin-left: 10px;
    }

    .form {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .form input {
        width: 90%;
        height: 80px;
        margin: 0.5rem;
    }

    .form button {
        padding: 0.5em 1em;
        border: none;
        background: rgb(100, 200, 255);
        cursor: pointer;
    }


    .container {
        display: flex;
        justify-content: center;
    }

    .login-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        /* margin-right: 20px; */
    }

    .rounded-container {
        border-radius: 20px;
        background-color: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        padding: 10px;
        /* max-width: 400px; Ajusta el valor según tus necesidades */
        /* width: 390px; */
        /* height: 300px; */
        margin: 0 auto;
        /* margin: 10% 0% 10% 0%; */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .rounded-container-logo {
        border-radius: 20px;
        background-color: white;
        box-shadow: 0 0 0px rgba(0, 0, 0, 0.3);
        padding: 10px;
        max-width: 400px;
        /* Ajusta el valor según tus necesidades */
        margin: 0 auto;
        text-align: center;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .circle-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        /* Ajusta el ancho según tus necesidades */
        height: 100%;
        /* Ajusta la altura según tus necesidades */
        overflow: hidden;
        /* Oculta el contenido que se desborde del contenedor */
    }


    .circle {
        width: 90%;
        height: 90%;
        border-radius: 100%;
        overflow: hidden;
        /* margin-right: 5px; */
    }


    .circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #contenBtnConsultarExequial {
        padding-top: 25px;
    }

    .card-exequias {
        /* flex: 0 1 calc(15% - 0px); 48% es solo un ejemplo, ajusta según tus necesidades */
        border-radius: 20px;
        background-color: white;
        box-shadow: 0 0 7px rgba(0, 0, 0, 0.3);
        /* padding: 3.5% 7%; */
        padding: 25px 30px;
        max-width: 100%;
        margin: 0 auto;
        margin-top: 12px;
        /* min-height: 370px; */
        /* max-height: 370px;  */
        text-align: center;
        margin-bottom: 12px;
        display: flex;
        flex-direction: column;
        /* justify-content: center; */
        align-items: center;
        /* height: 365px; */
    }

    .card-exequias-logo {
        flex: 0 1 calc(15% - 0px);
        /* 48% es solo un ejemplo, ajusta según tus necesidades */
        border-radius: 20px;
        background-color: white;
        box-shadow: 0 0 0px rgba(0, 0, 0, 0.3);
        /* padding: 3.5% 7%; */
        /* padding: 10px 30px; */
        max-width: 100%;
        margin: 0 auto;
        margin-top: 12px;
        /* min-height: 370px; */
        /* max-height: 370px;  */
        text-align: center;
        margin-bottom: 12px;
        display: flex;
        flex-direction: column;
        /* justify-content: center; */
        align-items: center;
    }


    .row-card {

        padding-top: 3%;
        padding-left: 3%;
        padding-right: 3%;
        display: flex;

    }

    .row-card-end {
        padding-bottom: 3%;
        padding-left: 7%;
        padding-right: 7%;
    }

    .error-message {
        display: none;
        color: red;
        font-size: 12px;
        margin-top: 5px;
    }

    input:invalid+.error-message,
    select:invalid+.error-message {
        display: block;
    }

    .row1 {
        display: flex;

    }

    .card-text {

        text-align: justify;

    }

    .card-container {
        display: flex;
        flex-wrap: wrap;
        /* justify-content: space-between; */
    }

    .card-exequias .card-title {

        font-size: 19px;
        /* margin-bottom: 5.5%; */
    }

    .card-exequias .card-text {
        font-size: 12px;
        margin-bottom: 14px;
    }

    /* Estilo para la card especial sin sombra en el borde */
    .special-card {
        box-shadow: none;
        justify-content: normal !important;
    }


    .miIframe {
        width: 100%;
        max-width: none;
        height: 1200px;
        transition: width 0.5s;
    }

    .content-link {
        /* min-height: 250px; */
        padding: 15px;
        margin-right: auto;
        margin-left: auto;
        padding-left: 15px;
        padding-right: 15px;
    }

    .card-exequias.special-card img {
        margin-top: 22px;
        margin-bottom: 26px;
    }

    .img-opcion {
        width: 100%;
        max-width: 850px;
        height: auto;
        display: block;
        margin: 0 auto;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">

        <h1 style="margin-bottom: 0%;">

            Solicitud Cotización RC Hidrocarburos

        </h1>

        <ol class="breadcrumb">

            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

            <li class="active">Hidrocarburos</li>

        </ol>

    </section>
    <section class="content">
        <div class="box">
            <div class="row card-container">
                <!-- TITULO PLANES -->
                <div class="content">

                    <!-- //LOGO Y DESCRIPCIÓN// -->
                    <!-- Primera tarjeta con el logo -->
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="card-exequias special-card">
                            <div class="card-body">
                                <h4 class="card-title;" style="font-weight: bold;"></h4>
                                <img src="vistas/img/plantilla/logo-hidrocarburos.png" class="img-fluid mx-auto" style="max-width: 66%; margin-top: 0px;">
                                <p class="card-text; margin-top: 10px" style="font-size: 12px;">Consulta la presentación del producto RCE Hidrocarburos <b><a href="https://grupoasistencia.com/pdfHidrocarburos/RC HIDROCARBUROS - Integradoor.pdf" target="_blank"><u> AQUÍ</u></a></b></p>
                            </div>
                        </div>
                    </div>

                    <!-- Segunda tarjeta con título y párrafo -->
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="card-exequias">
                            <div class="card-body">
                                <h4 class="card-title" style="font-weight: bold;">¿Por qué ofrecer este Seguro a tus clientes?</h4><br>
                                <ul class="card-text" style="padding-left: 0px; list-style-type: none; list-style-position: inside;">
                                    <li>El <b>Seguro de Responsabilidad Civil Extrcontractual para el transporte de hidrocarburos</b> protege al asegurado por los posibles daños y perjuicios causados a terceros como consecuencia de la acción directa de los hidrocarburos durante su transporte, manejo y distribución.</li>
                                    <br>
                                    <li>La legislación colombiana pretende que todos los agentes del sector de hidrocarburos cuenten con todos los elementos que garanticen unas condiciones mínimas de seguridad. Esto incluye la asegurabilidad, que busca resarcir los posibles daños de ejecutar estas operaciones.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tercera tarjeta con título y párrafo -->
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="card-exequias">
                            <div class="card-body">
                                <h4 class="card-title" style="font-weight: bold;">Normatividad</h4><br>
                                <p class="card-text">Ministerio de Minas y Energía</p>
                                <p class="card-text"><b>Decreto 1073 de 2015 (vigente):</b><br>
                                    Aplica para agentes de la cadena de distribución de combustibles líquidos derivados del petróleo, excepto GLP: refinador, importador, almacenador, distribuidor mayorista, transportador, distribuidor minorista y gran consumidor.</p>
                                <p class="card-text"><b>Decreto 4299 de 2005:</b><br>
                                    Establece requisitos, obligaciones y el régimen sancionatorio, aplicables a los agentes de la cadena de distribución de combustibles líquidos derivados del petróleo, excepto GLP</p>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                    <!-- </div> -->


                    <!-- //INFORMACION SEGUNDA FILA -->
                    <!-- <div class="row card-container"> -->
                    <!-- <div class="content"> -->
                    <!-- <div class="content-header">
                                <h4 style="font-family: 'Arial Arabic', Arial; text-align: left; font-weight: bold; margin-bottom: -12px; margin-top: -8px;">Adicionales Opcionales</h4>
                                <HR>
                            </div> -->
                    <!-- //AFILIADO ADICIONAL -->
                    <!-- cuarta tarjeta con título y párrafo -->
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="card-exequias">
                            <div class="card-body">
                                <h4 class="card-title" style="font-weight: bold;">¿Cuáles son las principales coberturas?</h4><br>
                                <ul class="card-text" style="padding-left: 0px; list-style-position: inside;">
                                    <li>RCE Hidrocarburos - Decreto 4299 del 2005.</li>
                                    <li>Responsabilidad civil contaminación accidental.</li>
                                    <li>Gastos de limpieza y/o biorremediación.</li>
                                    <li>Gastos médicos.</li>
                                </ul>
                                <h4 class="card-title" style="font-weight: bold;">Clausulado</h4>
                                <p>Para conocer más del Seguro <b>RC Hidrocarburos</b> de Allianz ingresa <b><a href="https://grupoasistencia.com/pdfHidrocarburos/Clausulado Responsabilidad-Civil-Extracontractual-Hidrocarburos-16-01-2023.pdf" target="_blank"><u> AQUÍ</u></a></b></p>
                            </div>
                        </div>
                    </div>

                    <!-- quinta tarjeta con título y párrafo -->
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="card-exequias">
                            <div class="card-body">
                                <h4 class="card-title" style="font-weight: bold;">Políticas de Suscripción</h4><br>
                                <p class="card-text">Estas son algunas de las políticas de suscripción, las cuales pueden cambiar en cualquier momento, sin previo aviso:</p>
                                <ul class="card-text" style="padding-left: 0px; list-style-position: inside;">
                                    <li>Edad máxima de antigüedad del vehículo: 20 años</li>
                                    <li>Edad máxima de repotenciación del vehículo: 12 años</li>
                                    <li>Aplica para Carrotanques, Remolque - Trailer (tanque).</li>
                                    <li>No esta permitido cotizar y/o emitir para Volquetas o carrotanques que transporten gas.</li>
                                    <li>Al emitir tener presente la inclusión de los trailer respectivos para que queden incluidos de una vez en la póliza (máximo 3)</li>
                                    <li>Reporte al SICOM, es responsabilidad del cliente, no de la compañía de seguros.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- sexta tarjeta con título y párrafo -->
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="card-exequias">
                            <div class="card-body text-center">
                                <h4 class="card-title" style="font-weight: bold;">Comisión</h4><br>
                                <p class="card-text">La comisión base que nos ofrece Allianz Seguros para nuestra alianza de asesores es del x%. De este porcentaje, tu participación será de acuerdo al nivel de ventas de todos los negocios (sin IVA), sumando todos los ramos, que realices en el mes.</p>
                                <ul class="card-text" style="padding-left: 0px;">
                                    <li style="list-style-type: none;">Primas mensuales inferiores a 8 SMMLV: 70%</li>
                                    <li style="list-style-type: none;">Primas mensuales superiores a 8 SMMLV: 75%</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="content-link" style="margin-top: -5px;" data-evaluar="si">
                <p style="font-size: 14px;"><strong>Existen dos opciones de tarifa para el cliente con diferente aplicación de deducible:</strong></p>
                <ul>
                    <li><b> Opcion 1: </b>Deducible 15% mínimo 3.000.000</li>
                    <li><b>Opcion 2: </b>Deducible 10% mínimo 3.000.000</li>
                </ul>
            </div>
            
            <div>
                <img class="img-opcion" src="vistas/img/plantilla/Opcion1-hidrocarburos.png" alt="opcion 1">
                <img class="img-opcion" src="vistas/img/plantilla/Opcion2-hidrocarburos.png" alt="opcion 2">
            </div>

            <!-- //FORMULARIO -->
            <div class="content-link" style="margin-top: -5px;" data-evaluar="si">
                <p style="font-size: 19px;"><strong>Solicita una cotización en el siguiente formulario ingresando</strong> <b style="font-size: 17px;"><a href="https://forms.gle/bHw1SYtGS9p22TJs7" target="_blank"><u>AQUÍ</u></a></b></p>
            </div>


        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function ajustarAlturaTarjetas() {
            var filas = document.querySelectorAll('.row.card-container'); // Modificado el selector

            filas.forEach(function(fila) {
                var tarjetas = fila.querySelectorAll('.card-exequias');

                var alturaMaxima = 0;

                tarjetas.forEach(function(tarjeta) {
                    tarjeta.style.height = 'auto'; // Restablecer la altura a 'auto' antes de medir
                    var altura = tarjeta.offsetHeight;

                    if (altura > alturaMaxima) {
                        alturaMaxima = altura;
                    }
                });

                tarjetas.forEach(function(tarjeta) {
                    tarjeta.style.height = alturaMaxima + 'px';

                    if (tarjeta.classList.contains('special-card')) {
                        tarjeta.style.display = 'flex';
                        tarjeta.style.flexDirection = 'column';
                        tarjeta.style.alignItems = 'center';
                        tarjeta.style.justifyContent = 'center';


                        if (window.innerWidth <= 768 && tarjeta.classList.contains('special-card')) {
                            tarjeta.style.height = '300px';
                        }
                    }

                });
            });

        }

        // Llamada inicial y en redimensionamiento de la ventana
        ajustarAlturaTarjetas();
        window.addEventListener('resize', ajustarAlturaTarjetas);
    });
</script>