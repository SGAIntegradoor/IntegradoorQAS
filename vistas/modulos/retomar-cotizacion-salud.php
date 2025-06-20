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
    padding: 10px 30px;
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

  .select2-container--bootstrap .select2-results>.select2-results__options {
    max-height: 90px !important;
    overflow-y: auto;
  }

  .miIframe {
    width: 100%;
    max-width: none;
    height: 1200px;
    transition: width 0.5s;
  }

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

  @media (max-width: 495px) {
    .dt-info {
      width: 300px !important;
      text-align: left;
    }
  }

  .container-salud {
    padding-right: 25px;
    padding-left: 25px;
    margin-right: auto;
    margin-left: auto;
  }
</style>

<?php

if (!isset($_GET['idCotizacionSalud'])) {

  echo '<script>

  window.location = "inicio";
  
  </script>';
}


?>
<script>

</script>

<div class="content-wrapper">
  <section class="content-header">

    <h1 style="margin-bottom: 0%;">

      Cotización # <?php echo $_GET['idCotizacionSalud'] ?>

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Salud</li>

    </ol>

  </section>
  <section class="content">
    <div class="box">
      <?php include_once './vistas/modulos/SaludCot/vistas/cotizadorSalud.php'; ?>
      <div class="container-fluid" id="">
        <div class="col-lg-12">
          <div class="row row-aseg">
            <div class="col-xs-12 col-sm-6 col-md-3">
              <label for="">PARRILLA DE COTIZACIONES</label>
            </div>
          </div>
        </div>
        <div class="container-fluid" id="Cards">
          <div class="row">
            <div class="col-xs-12">
              <p><strong>Nota: </strong>Esta propuesta tiene una vigencia limitada</p>
            </div>
          </div>
          <div class="row" id="row_contenedor_general_salud2"></div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<link rel="stylesheet" href="vistas\modulos\SaludCot\css\cotizadorSalud.css">
<script src="vistas/js/cotizaciones_salud.js?v=<?php echo (rand()); ?>" defer></script>
<script src="vistas\modulos\SaludCot\js\adminCotizacionesSalud.js?v=<?php echo (rand()); ?>" defer></script>