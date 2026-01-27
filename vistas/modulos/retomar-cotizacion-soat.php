<?php

$idCotizacion = $_GET['idCotizacionSoat'];

$stmt = Conexion::conectar()->prepare("
SELECT
  *
FROM
	cotizaciones_soat ch
WHERE ch.id = :idCotizacion;
");
$stmt->bindParam(":idCotizacion", $idCotizacion, PDO::PARAM_INT);
$stmt->execute();

$cotizacionesFinesa = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

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
    flex: 0 1 calc(15% - 0px);
    /* 48% es solo un ejemplo, ajusta según tus necesidades */
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 0 7px rgba(0, 0, 0, 0.3);
    /* padding: 3.5% 7%; */
    padding: 10px 32px;
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
    margin-bottom: 5.5%;
  }

  .card-exequias .card-text {
    font-size: 13px;
    margin-bottom: 14px;
  }

  /* Estilo para la card especial sin sombra en el borde */
  .special-card {
    box-shadow: none;
    /* Esto elimina la sombra */
  }

  /* .card-exequias .card-text {
    margin: 0 auto;
  min-height: 100px;
  border-radius: 20px;
  text-align: center;
  margin-bottom: 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
} */

  /* .card-exequias .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    height: 100%;
} */

  .miIframe {
    width: 100%;
    max-width: none;
    height: 900px;
    transition: width 0.5s;
  }

  .general-container-aseg {
    padding-right: 25px;
    padding-left: 25px;
    margin-right: auto;
    margin-left: auto;
  }

  .general-container-datos {
    padding-right: 25px;
    padding-left: 25px;
    margin-right: auto;
    margin-left: auto;
  }

  .box {
    border: 0 !important;
  }

  .wrapper {
    overflow: hidden;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">

    <h1 style="margin-bottom: 0%;">

      Solicitud de cotización # <?php echo $_GET['idCotizacionSoat'] ?>
    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">SOAT</li>

    </ol>

  </section>
  <div id="politicasSoatContent" style="display:none;">
                <div class="row card-container">
                    <!-- TITULO PLANES -->
                    <div class="content">
                        <!-- 
                        <div id="politicasSoatContent" style="display:none;">
                            <div id="soatSection1" class="soat-section">...</div>
                            <div id="soatSection2" class="soat-section" style="display:none;">...</div>
                        </div> -->

                        
                        <div id="soatSection1" class="soat-section section-1" style="margin-top: 2.5rem; display: flex; justify-content: center;">
                            <div class="row">

                                <!-- PROCESO TRÁMITE SOAT -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="card-exequias" style="border-radius: 0px; box-shadow: none; padding: 2rem 0 0 3rem;">
                                        <div class="card-body">

                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                PROCESO TRÁMITE SOAT
                                            </h7><br><br>

                                            <ul class="card-text" style="padding-left: 0px; list-style-position: inside; list-style-type: none;">
                                                <li>
                                                    <b>1️⃣</b> Ingresa la <b>placa del vehículo</b> en la plataforma.
                                                </li>
                                                <li>
                                                    <b>2️⃣</b> Verifica la info y selecciona el tipo de servicio de trámite:
                                                    <b>con comisión / sin comisión</b>.
                                                </li>
                                                <li>
                                                    <b>3️⃣</b> Confirma con el cliente el <b>valor final a pagar</b>.
                                                </li>
                                                <li>
                                                    <b>4️⃣</b> Para continuar con la expedición, registra el <b>email y celular del tomador</b>
                                                    y adjunta:
                                                    <b>Tarjeta de Propiedad</b>, factura de compra para vehículos 0 km y
                                                    <b>soporte de pago</b>, cuyo valor debe coincidir exactamente con la cotización.
                                                </li>
                                                <li>
                                                    <b>5️⃣</b> Creada la solicitud, pasa a <b>aprobación</b>, la cual se realiza después de verificar
                                                    documentos y pago en cuentas.
                                                    <br>
                                                    <b>Nota:</b> Si se detectan <b>inconsistencias</b>, la solicitud será devuelta para corrección.
                                                </li>
                                                <li>
                                                    <b>6️⃣</b> <b>Emisión</b>, siempre a nombre del propietario.
                                                </li>
                                                <li>
                                                    <b>7️⃣</b> <b>Póliza SOAT:</b> Será enviada por email automáticamente por la aseguradora.
                                                    También podrá descargarse desde Integrador.
                                                </li>
                                            </ul>

                                            <p class="card-text" style="margin-top: 10px;">
                                                <b>Nota importante:</b> Para vehículos <b>0 km</b>, comunícate previamente con el área SOAT al
                                                <a href="https://wa.link/ozcean" target="_blank"><b><u>301 323 2210</u></b></a>.
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                <!-- VALOR DEL SERVICIO DE TRÁMITE -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="card-exequias" style="border-radius: 0px; box-shadow: none; padding: 2rem 3rem 0 0;">
                                        <div class="card-body">

                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                VALOR DEL SERVICIO DE TRÁMITE
                                            </h7><br><br>

                                            <p class="card-text">
                                                La emisión tiene un costo, que cubre la validación del vehículo,
                                                revisión RUNT, gestión del pago y expedición.
                                                El valor base de este servicio es de <b>$20.000</b>, y aplica así:
                                            </p>

                                            <p class="card-text"><b>Opción 1 - sin comisión:</b> El aliado define si cobra o no su margen.</p>
                                            <p class="card-text"><b style="padding-left: 3rem;">Vr. Carro o moto:</b> $20.000</p>

                                            <p class="card-text" style="margin-top: 10px;">
                                                <b>Opción 2 - con comisión:</b> El aliado recibe <b>$20.000</b> por SOAT emitido,
                                                y el cliente paga el trámite directamente a nuestras cuentas.
                                            </p>
                                            <p class="card-text"><b style="padding-left: 3rem;">Vr. Carro o moto:</b> $40.000</p>

                                            <p class="card-text" style="margin-top: 10px;">
                                                <b>Notas:</b>
                                                Las comisiones se liquidan cada <b>5 SOAT ($100.000)</b>.
                                                Vehículos con novedades en RUNT pueden tener costo adicional.
                                            </p>

                                            <p class="card-text">
                                                Consulta las tarifas oficiales del SOAT descargando el tarifario
                                                <b><u><a href="https://Grupoasistencia.com/pdfSoat/TarifarioSOAT-2025-ll.pdf" target="_blank">AQUÍ</a></u></b>.
                                            </p>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>



                        <div id="soatSection2" class="soat-section section-2" style="display:none;">
                            <div class="row">

                                <!-- CÓMO REALIZAR LOS PAGOS / DEVOLUCIONES / CANAL -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="card-exequias" style="border-radius: 0px; box-shadow: none; padding: 2rem 0 0 3rem;">
                                        <div class="card-body">

                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                ¿CÓMO REALIZAR LOS PAGOS?
                                            </h7><br><br>

                                            <p class="card-text">
                                                A nombre de <b>Finansera SAS</b><br>
                                                NIT: <b>901505888-1</b>
                                            </p>

                                            <p class="card-text">
                                                <b>Bancolombia:</b> Cuenta de ahorros No. <b>265-000079-22</b><br>
                                                o Llave 🔑 <b>0090574357</b> (certificado
                                                <b><u><a href="#" target="_blank">AQUÍ</a></u></b>)
                                            </p>

                                            <p class="card-text">
                                                <b>Nota:</b> Pagos desde otros bancos pueden tardar más en verse reflejados.
                                            </p>



                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                DEVOLUCIONES
                                            </h7><br><br>

                                            <p class="card-text">
                                                Si no es posible expedir, se devuelve el <b>100% del dinero</b>.
                                            </p>

                                            <p class="card-text">
                                                Para procesar la devolución se debe adjuntar certificación bancaria
                                                o carta de autorización con los datos de la cuenta destino.
                                            </p>



                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                CANAL DE ATENCIÓN
                                            </h7><br><br>

                                            <p class="card-text">
                                                📱 Whatsapp SOAT: <b>301 323 2210</b> – línea exclusiva para mensajes (no llamadas).
                                            </p>

                                            <p class="card-text">
                                                Todas las solicitudes y seguimientos se atienden por este medio.
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                <!-- TIEMPOS / HORARIO / MATERIAL COMERCIAL -->
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="card-exequias" style="border-radius: 0px; box-shadow: none; padding: 2rem 3rem 0 0;">
                                        <div class="card-body">

                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                TIEMPOS DE RESPUESTA
                                            </h7><br><br>

                                            <p class="card-text">
                                                Vehículos sin novedades RUNT: <b>1 a 3 horas hábiles</b>.
                                            </p>

                                            <p class="card-text">
                                                Con novedades RUNT: <b>hasta 1 día hábil</b>.
                                            </p>

                                            <p class="card-text">
                                                Vehículos 0 km: <b>1 día hábil</b>.
                                            </p>

                                            <p class="card-text">
                                                ⏱️ El tiempo empieza desde la confirmación del pago.
                                            </p>



                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                HORARIO DE ATENCIÓN
                                            </h7><br><br>

                                            <p class="card-text">
                                                Lunes a viernes<br>
                                                8:00 a.m. – 12:30 p.m.<br>
                                                1:30 p.m. – 4:30 p.m.
                                            </p>



                                            <h7 class="card-title" style="font-weight: bold; font-size: 15px">
                                                MATERIAL COMERCIAL
                                            </h7><br><br>

                                            <p class="card-text">
                                                El SOAT es una excelente puerta de entrada para nuevos clientes.
                                                Grupo Asistencia envía <b>5 piezas publicitarias semanales</b>
                                                para apoyo comercial.
                                            </p>

                                            <p class="card-text">
                                                Para recibirlas:
                                            </p>

                                            <ul class="card-text" style="padding-left: 20px; list-style-position: inside; list-style-type: none;">
                                                <li><b>1️⃣</b> Ingresa al canal de Whatsapp SOAT
                                                    <b><u><a href="https://www.whatsapp.com/channel/0029VbB0Rsc5K3zMSG6mYP2s" target="_blank">AQUÍ</a></u></b>.
                                                </li>
                                                <li><b>2️⃣</b> Haz clic en <b>Seguir canal</b>.</li>
                                                <li><b>3️⃣</b> Activa la campana 🔔.</li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>


                </div>
            </div>
  <section class="content">
    <div class="box">
      <?php
      require_once "vistas/modulos/soat/form/formulario_soat.php";
      ?>
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
          }

        });
      });

    }

    // Llamada inicial y en redimensionamiento de la ventana
    ajustarAlturaTarjetas();
    window.addEventListener('resize', ajustarAlturaTarjetas);
  });
</script>
<link rel="stylesheet" href="vistas\components\formCotizacion\css\styles.css">

<script src="vistas/modulos/soat/js/retoma_soat.js?v=<?php echo (rand()); ?>" defer></script>
<!-- <script src="vistas/modulos/soat/js/cotizaciones_soat.js?v=<?php echo (rand()); ?>" defer></script>
<script src="vistas/components/formCotizacion/js/adminCotizacionesSoat.js?v=<?php echo (rand()); ?>"></script>
<script src="vistas/modulos/soat/js/cotizar_soat.js?v=<?php echo (rand()); ?>"></script> -->