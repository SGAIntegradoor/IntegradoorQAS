<?php
if ($_SESSION["permisos"]["administracionCotizaciones"] != "x") {

  echo '<script>
    window.location = "inicio";
  </script>';
  return;
}

?>

<style>
  .btnNuevaCot {
    border-radius: 4px;
    background-color: #88D600;
    border: none;
    color: #fff;
    text-align: center;
    font-size: 18px;
    padding: 5px;
    width: 180px;
    transition: all 0.5s;
    cursor: pointer;
    margin: 5px;
    /* box-shadow: 0 10px 20px -8px rgba(0, 0, 0,.7); */
  }

  .btnNuevaCot {
    cursor: pointer;
    display: inline-block;
    position: relative;
    transition: 0.5s;
  }

  .btnNuevaCot:after {
    content: '»';
    position: absolute;
    opacity: 0;
    top: 4px;
    right: -30px;
    transition: 0.5s;
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

  @media (max-width: 495px) {
    .dt-info {
      width: 300px !important;
      text-align: left;
    }
  }

  #tabla-wrapper {
    position: relative;
  }

  #loader-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* #loader-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;
} */

  #loader-container img {
    width: 40px;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">

    <h1>

      Admin. Cotizaciones

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Admin. cotizaciones </li>

    </ol>

  </section>


  <section class="content">

    <div class="box">
      <?php include_once './vistas/modulos/AdminCoti/views/filtersTable.php'; ?>
      <div class="box-header with-border">
        <button class="btnNuevaCot" id="btnRedLivianos" style="font-size: 16px">
          Cotizar Liviano
          <i class="fa fa-car" aria-hidden="true"></i>
        </button>
        </a>

        <a href="pesados">
          <?php
          if ($_SESSION["permisos"]["Cotizarpesadoboton"] == "x") {
            echo '<button class="btnNuevaCot" style="font-size: 16px">
            Cotizar Pesado
            <i class="fa fa-truck" aria-hidden="true"></i>
          </button>';
          }
          ?>
        </a>

        <a href="motos">
          <?php
          if ($_SESSION["permisos"]["Cotizarmotos"] == "x") {
            echo '<button class="btnNuevaCot" id="btnMotosX" style="font-size: 16px">            
            Cotizar Motos
            <i class="fa fa-motorcycle" aria-hidden="true"></i>
          </button>';
          }
          ?>
        </a>

        <a href="transporte-pasajeros">
          <?php
          if ($_SESSION["permisos"]["cotizarpasajeros"] == "x") {
            echo '<button class="btnNuevaCot" style="font-size: 16px">
              Cotizar Autos Pasaj.
              <i class="fa-solid fa-bus" aria-hidden="true"></i>
            </button>';
          }
          ?>
        </a>

        <button type="button" class="btn btn-default pull-right" id="daterange-btnCotizaciones">

          <span>
            <i class="fa fa-calendar"></i>

            <?php

            if (isset($_GET["fechaInicialCotizaciones"])) {

              echo $_GET["fechaInicialCotizaciones"] . " - " . $_GET["fechaFinalCotizaciones"];
            } else {

              echo 'Rango de fecha';
            }

            ?>
          </span>

          <i class="fa fa-caret-down"></i>

        </button>

      </div>

      <div class="box-body">
        <div id="tabla-wrapper" style="position: relative;">
          <div id="loader-overlay">
            <div id="loader-container">
              <img src="vistas/img/plantilla/loader-update.gif" alt="Cargando..." />
            </div>
          </div>

          <table class="table table-bordered table-striped dt-responsive tablas-cotizaciones" width="100%">

            <thead>

              <tr>

                <th style="font-weight: bold; text-align: center;">N°</th>
                <th style="font-weight: bold; text-align: center;">Fecha</th>
                <th style="font-weight: bold; text-align: center;">Documento</th>
                <th style="font-weight: bold; text-align: center;">Cliente</th>
                <th style="font-weight: bold; text-align: center;">Contacto</th>
                <th style="font-weight: bold; text-align: center;">Placa</th>
                <th style="font-weight: bold; text-align: center;">Referencia del Vehículo</th>
                <th style="font-weight: bold; text-align: center;">Clase</th>
                <th style="font-weight: bold; text-align: center;">Módulo</th>
                <th style="font-weight: bold; text-align: center;">Asesor</th>
                <th style="width:110px; font-weight: bold; text-align: center;">Acciones</th>

              </tr>

            </thead>

            <tbody style="display: none;">

              <?php

              if (isset($_GET["fechaInicialCotizaciones"])) {

                $fechaInicialCotizaciones = $_GET["fechaInicialCotizaciones"];
                $fechaFinalCotizaciones = $_GET["fechaFinalCotizaciones"];
                $respuesta = ControladorCotizaciones::ctrRangoFechasCotizaciones($fechaFinalCotizaciones, $fechaInicialCotizaciones);
              } else if (isset($_GET["moduloCotizacion"]) || isset($_GET["canal"]) || isset($_GET["clase"]) || isset($_GET["nombreAsesor"]) || isset($_GET["analistaGA"])) {
                $respuesta = ControladorCotizaciones::ctrMostrarCotizacionesFilters($_GET);
              } else {
                $fechaActual = new DateTime();

                // Obtener la fecha de inicio de los últimos 30 días
                $inicioMes = clone $fechaActual;
                $inicioMes->modify('-30 days');
                $inicioMes = $inicioMes->format('Y-m-d');

                // Obtener la fecha de fin (la fecha actual)
                $fechaActual->modify('+1 day');
                $fechaActual = $fechaActual->format('Y-m-d');

                $respuesta = ControladorCotizaciones::ctrRangoFechasCotizaciones($fechaActual, $inicioMes);
              }


              if ($respuesta) {
                foreach ($respuesta as $key => $value) {

                  echo '<tr>

                  <td class="text-center" style="font-size: 14px">' . $value['id_cotizacion'] . '</td>

                  <td class="text-center" style="font-size: 14px">' . date('Y/m/d', strtotime($value['cot_fch_cotizacion'])) . '</td>

                  <td class="text-right" style="font-size: 14px">' . $value['cli_num_documento'] . '</td>

                  <td class="text-right" style="font-size: 14px">' . $value['cli_nombre'] . ' ' . $value['cli_apellidos'] . '</td>
                  <td class="text-right" style="font-size: 14px">' . $value['cli_telefono'] . '</td>';

                  $placa = $value['cot_placa'] == "KZY000" ? "SIN PLACA" : $value['cot_placa'];
                  echo '<td class="text-center" style="font-size: 14px">' . $placa . '</td>

                  <td class="text-center" style="font-size: 14px">' . $value['cot_marca'] . ' ' . $value['cot_linea'] . '</td>

                  <td class="text-center" style="font-size: 14px">' . $value['cot_clase'] . '</td>

                  <td class="text-center" style="font-size: 14px">' . $value['modulo_cotizacion'] . '</td>

                  <td class="text-center" style="font-size: 14px">' . $value['usu_nombre'] . ' ' . $value['usu_apellido'] . '</td>

                  <td class="text-center">

                    <div class="btn-group">
                    
                      <button class="btn btn-primary btnEditarCotizacion" idCotizacion="' . $value["id_cotizacion"] . '">Seleccionar</button>';

                  if ($_SESSION["rol"] == 1) {

                    echo '<button class="btn btn-danger btnEliminarCotizacion" style="display: none !important;" idCotizacion="' . $value["id_cotizacion"] . '"><i class="fa fa-times"></i></button>';
                  }

                  echo '</div>

                  </td>

                </tr>';
                }
              }
              ?>

            </tbody>

          </table>
        </div>
        <?php

        $eliminarCotizacion = new ControladorCotizaciones();
        $eliminarCotizacion->ctrEliminarCotizacion();

        ?>


      </div>

    </div>

  </section>

</div>

<script src="vistas/modulos/AdminCoti/js/functionsj.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>