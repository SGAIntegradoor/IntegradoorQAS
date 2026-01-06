

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
    max-width: 400px; /* Ajusta el valor según tus necesidades */
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
    width: 100%; /* Ajusta el ancho según tus necesidades */
    height: 100%; /* Ajusta la altura según tus necesidades */
    overflow: hidden; /* Oculta el contenido que se desborde del contenedor */
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
    flex: 0 1 calc(15% - 0px); /* 48% es solo un ejemplo, ajusta según tus necesidades */
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
    }

    .card-exequias-logo {
    flex: 0 1 calc(15% - 0px); /* 48% es solo un ejemplo, ajusta según tus necesidades */
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

    input:invalid + .error-message,
    select:invalid + .error-message {
        display: block;
    }

    .row1 {
        display: flex;

    }

    .card-text{

        text-align: justify;

    }

    .card-container {
    display: flex;
    flex-wrap: wrap;
    /* justify-content: space-between; */
    }

    .card-exequias .card-title{

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

    /* Agregado para darle estilo a la DataTable */

    .btn-excel {
    display: flex !important;
    border: 0px !important;
    height: 32px;
    align-items: center;
}

.dt-search {
    display: flex !important;
    align-items: center;
    justify-content: flex-end;
}

.paging_full_numbers {
    display: flex !important;
    justify-content: flex-end;
}

.dt-length {
    display: flex;
}

.dt-start {
    width: 60px !important;
}

.dt-info {
    width: 600px !important;
}

.dt-column-title {
    font-size: 14px !important;
}

.select2-container--bootstrap 
.select2-results > 
.select2-results__options {
    max-height: 90px !important;
    overflow-y: auto;
}

</style>

<div class="content-wrapper">
    <section class="content-header">

        <h1 style="margin-bottom: 0%;">

        Solicitud de Cotización SOAT

        </h1>

        <ol class="breadcrumb">

            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

            <li class="active">SOAT</li>

        </ol>

    </section>
    <section class="content">
        <div class="box">
            <?php include_once './vistas/modulos/soat/adminCotizacionesSoat.php'; ?>
            <div class="row card-container">
                <!-- TITULO PLANES -->
                <div class="content">
                    
                    <!-- //LOGO Y DESCRIPCIÓN// -->
                        <!-- Primera tarjeta con el logo -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="card-exequias special-card">
                                <div class="card-body">
                                    <h4 class="card-title;" style="font-weight: bold;">CONVENIO SOAT</h4>
                                    <img src="vistas/img/plantilla/logo_soat.jpg" class="img-fluid mx-auto" style="max-width: 66%;">
                                    <p class="card-text; margin-top: 10px" style="font-size: 16px;"><strong>Solicita cotización</strong> <b><a href="https://docs.google.com/forms/d/e/1FAIpQLSfS5iEUGxHMzRwDBkRYN48v-Q3mTECfWVuM9flSCOoYiOMN6A/viewform" target="_blank"><u> AQUÍ</u></a></b></p>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda tarjeta con título y párrafo -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="card-exequias">
                                <div class="card-body">
                                    <h4 class="card-title" style="font-weight: bold;">PROCESO TRÁMITE SOAT</h4>
                                    <ul class="card-text" style="padding-left: 0px; list-style-type: decimal; list-style-position: inside;">
                                        <li>Diligenciar el formulario completamente adjuntando imagen de tarjeta de propiedad (por favor nombrar el documento con la placa del vehículo).</li>
                                        <li>Esperar cotización y confirmación (se verifica que vehículo no tenga errores en el RUNT).</li>
                                        <li>Realizar pago según cotización y enviar soporte al Whatsapp SOAT <a href="https://wa.link/ozcean" target="_blank"><b><u>3013232210</u></b></a>.</li>
                                        <li>Esperar confirmación de recepción del pago en cuentas bancarias.</li>
                                        <li>Emitir SOAT (siempre a nombre del propietario según TP)</li>
                                        <li>Recibir SOAT en correo electrónico del tomador.</li>
                                    </ul>
                                    <p class="card-text"><b>Notas: 1.</b> Se puede emitir Autos y Motos, pero actualmente no tenemos habilitada la expedición de Motos 0 km. <b>2.</b> Vehículos con errores en el RUNT se podrán emitir bajo autorización y con un cobro de servicio de trámite mayor.</p>
                                    <p class="card-text">Para conocer los valores del SOAT por tarifa, descarga el tarifario <b><u><a href="https://Grupoasistencia.com/pdfSoat/TarifarioSOAT-2025-ll.pdf" target="_blank">AQUÍ</a></u></b></p>
                                </div>
                            </div>
                        </div>

                        <!-- Tercera tarjeta con título y párrafo -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="card-exequias">
                                <div class="card-body">
                                    <h4 class="card-title" style="font-weight: bold;">VALOR COBRO SERVICIO DE TRÁMITE</h4>
                                    <p class="card-text">Opción 1 sin comisión: El aliado cobra al cliente el valor adicional que desee. En ese caso el valor de cobro por servicio de trámite es el siguiente:</p>
                                    <ul class="card-text" style="padding-left: 11%; padding-right: 15%; list-style-position: inside;">
                                        <li>Moto: <b>$30.000</b></li>
                                        <li>Otras tarifas: <b>$20.000</b></li>
                                    </ul>
                                    <p class="card-text">Opción 2 con comisión: Aliado recibe comisión de $20.000 por cada SOAT que se emita para sus clientes. El valor de cobro por servicio de trámite es:</p>
                                    <ul class="card-text" style="padding-left: 11%; padding-right: 15%; list-style-position: inside;">
                                        <li>Moto: <b>$50.000</b></li>
                                        <li>SOAT menor a $700.000: <b>$35.000</b></li>
                                        <li>SOAT mayor o igual a $700.000: <b>$45.000</b></li>
                                    </ul>
                                    <p class="card-text"><b>Notas: 1.</b>  Los $20.000 de comisión se liquidan y cobran a través del área SOAT de Grupo Asistencia, y se pagan una vez se logren 5 SOAT, es decir, cada $100.000.
                                    <b>2.</b> Actualmente no tenemos habilitada la expedición de Motos (0 km). <b>3.</b> 
                                    Vehículos con errores en el RUNT se podrán emitir bajo autorización y con un cobro de servicio de trámite mayor.
                                    </p>
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
                                    <h4 class="card-title" style="font-weight: bold;">TIEMPO DE RESPUESTA</h4>
                                    <h6 style="margin-left: 0; text-align: left;">Cotizaciones:</h6>
                                    <ul class="card-text" style="padding-left: 0px; list-style-position: inside;">
                                        <li>De <b>30</b> a <b>45</b> minutos hábiles una vez registrado correctamente el formulario</li>
                                    </ul>
                                    <h6 style="margin-left: 0; text-align: left;">Expediciones:</h6>
                                    <ul class="card-text" style="padding-left: 0px; list-style-position: inside;">
                                        <li>Vehículos sin problemas en el RUNT: de <b>2</b> hasta <b>5</b> horas hábiles.</li>
                                        <li>Vehículos con problemas en el RUNT: de <b>5</b> horas hasta <b>1</b> día hábil.</li>
                                        <li>Vehículos 0 km: <b>1</b> día hábil.</li>
                                    </ul>
                                    <p class="card-text">Nota: El tiempo de respuesta empieza a correr desde que se recibe el pago del SOAT en las cuentas bancarias de Grupo Asistencia</p>
                                    <h4 class="card-title" style="font-weight: bold;">HORARIO DE SERVICIO</h4>
                                    <ul class="card-text" style="padding-left: 0px; list-style-position: inside;">
                                        <li>Lunes a Viernes de 8:00 am 12:30 pm y de 1:30 pm a 4:00 pm</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- quinta tarjeta con título y párrafo -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="card-exequias">
                                <div class="card-body">
                                    <h4 class="card-title" style="font-weight: bold;">DONDE PAGAR SERVICIO DE TRÁMITE</h4>
                                    <p class="card-text">A nombre de Seguros Grupo Asistencia Asistencia Ltda. BIC, NIT # 900.600.470-8 en las cuentas:</p>
                                    <ul class="card-text" style="padding-left: 0px; list-style-position: inside;">
                                        <li>Banco de Bogotá CTA Corriente No. 486457310. Descarga certificado bancario <b><u><a href="https://Grupoasistencia.com/pdfExequias/CERTIFICACIONBANCOBOGOTASOATSGA.pdf" target="_blank">AQUÍ</a></u></b></li>
                                        <li>Bancolombia CTA Ahorros No. 26500002769. Descarga certificado bancario <b><u><a href="https://Grupoasistencia.com/pdfExequias/CERTIFICADOBANCARIOBANCOLOMBIASOATSGA.pdf" target="_blank">AQUÍ</a></u></b></li>
                                    </ul>
                                    <p class="card-text">Nota: Si el pago se realiza desde una entidad financiera diferente, se debe esperar hasta que el dinero ingrese a la cuenta.</p>
                                    <h4 class="card-title" style="font-weight: bold;">DEVOLUCIONES DE DINERO</h4>
                                    <p class="card-text">No garantizamos la expedición de todos los SOAT solicitados. En caso de requerirse devoluciones de dineros pagados por Aliados o Clientes, se requiere adjuntar Certificación Bancaria o Carta de Autorización con información del número, tipo y banco de la cuenta donde se debe realizar la devolución.</p>
                                    <p class="card-text">Nota: Devolvemos el 100% del dinero cuando no se logre la expedición.</p>                                
                                </div>
                            </div>
                        </div>

                        <!-- sexta tarjeta con título y párrafo -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="card-exequias">
                                <div class="card-body text-center">
                                    <h4 class="card-title" style="font-weight: bold;">INFORMACIÓN DE CONTACTO</h4>
                                    <p class="card-text">La linea <b><u>3013232210</u></b> es exclusiva para mensajes de Whatsapp y no para llamadas. Cualquier inquietud, solicitud y/o requerimiento sera atendido por este medio.</p>
                                    <h4 class="card-title" style="font-weight: bold;">PUBLICIDAD</h4>
                                    <p class="card-text">El SOAT es una gran oportunidad para ampliar tu base de clientes y fidelizar a tus clientes actuales. Por eso Grupo Asistencia no sólo pone a tu disposición nuestro servicio de trámites de expedición de SOAT, sino que también vamos a enviarte 5 nuevas piezas publicitarias semanalmente para que puedas compartir en tus redes sociales.</p>
                                    <p class="card-text">Si quieres recibir este material, sigue los siguientes pasos:</p>
                                    <ul class="card-text" style="padding-left: 0px;">
                                        <li style="list-style-type: none;">
                                            <u style="font-weight: bold; color: black; text-decoration: none;">1.</u>  Ingresa al Canal de Whatsapp del área SOAT, haciendo clic <b><u><a href="https://www.whatsapp.com/channel/0029VbB0Rsc5K3zMSG6mYP2s" target="_blank">AQUÍ</a></u></b>.
                                        </li>
                                        <li style="list-style-type: none;">
                                            <u style="font-weight: bold; color: black; text-decoration: none;">2.</u> Haz clic en el botón Seguir Canal.
                                        </li>
                                         <li style="list-style-type: none;">
                                            <u style="font-weight: bold; color: black; text-decoration: none;">3.</u> Presiona la campana 🔔 para recibir notificaciones. 
                                        </li>
                                    </ul>
                                    <p class="card-text">Una vez que estés en el canal vas a recibir publicidad e información importante sobre el SOAT. También puedes guardar en Whatsapp el siguiente número de contacto directo del área SOAT,  <a href="https://wa.link/ozcean" target="_blank"><b><u> 3013232210</u></b></a>.</p>
                                </div>
                            </div>
                        </div>

                </div>


            </div>
            

                <!-- //FORMULARIO VIAJES -->
            <!-- <div class="content-link" style="margin-top: -5px;" data-evaluar="si">
                <p style="font-size: 19px;"><strong>Solicita una cotización en el siguiente formulario ingresando</strong> <b style="font-size: 17px;"><a href="https://docs.google.com/forms/d/e/1FAIpQLSfS5iEUGxHMzRwDBkRYN48v-Q3mTECfWVuM9flSCOoYiOMN6A/viewform" target="_blank"><u>AQUÍ</u></a></b></p>
            </div> -->

            <?php include_once './vistas/modulos/soat/form/formulario_soat.php'; ?>

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
                tarjeta.style.height = '200px';
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

