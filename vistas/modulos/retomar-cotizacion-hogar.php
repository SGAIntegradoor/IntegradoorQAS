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

    Cotización # <?php echo $_GET['idCotizacionHogar'] ?>
    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Hogar</li>

    </ol>

  </section>
  <section class="content">
    <div class="box" id="major-container-retoma">
      <div class="content">
        <div class="box">
          <?php
          require_once "vistas/components/formCotizacion/view.php";
          ?>
        </div>
        <div class="box">
          <?php
          require_once "vistas/components/formCotizacion/formDataHogar.php";
          ?>
          <?php
          require_once "vistas/components/formCotizacion/formValoresHogarAllianz.php";
          ?>
          <?php
          require_once "vistas/components/formCotizacion/formValoresHogar.php";
          ?>
          <?php
          require_once "vistas/components/formCotizacion/alertasHogar.php";
          ?>
        </div>
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

<script src="vistas\js\cotizaciones_hogar.js?v=<?php echo (rand()); ?>" defer></script>
<script src="vistas/components/formCotizacion/js/adminCotizacionesHogar.js?v=<?php echo (rand()); ?>"></script>
<script src="vistas/components/formCotizacion/js/functions.js?v=<?php echo (rand()); ?>"></script>
