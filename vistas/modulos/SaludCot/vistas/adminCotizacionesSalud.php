<div class="container-fluid mainDataContainer" id="containerDataTable">
  <div class="col-lg-12">
    <div class="row row-aseg">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <label id="lblDataTrip2">Administración de cotizaciones Seguro de salud</label>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div id="masAdminCoti">
          <p id="masCots">Ver más <i class="fa fa-plus-square-o"></i></p>
        </div>
        <div id="menosAdminCoti">
          <p id="menosCots">Ver menos <i class="fa fa-minus-square-o"></i></p>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid" id="containerTable">
  <button type="button" class="btn btn-default pull-right" id="daterange-btnCotizacionesSalud">
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
  <div class="box-body">

    <table class="table table-bordered table-striped dt-responsive tablas-salud" width="100%">

      <thead>

        <tr>

          <th style="font-weight: bold; text-align: center;">N°</th>
          <th style="font-weight: bold; text-align: center;">FechaCot</th>
          <!-- <th style="font-weight: bold; text-align: center;">Tipo Documento</th>
          <th style="font-weight: bold; text-align: center;">No. Documento</th> -->
          <th style="font-weight: bold; text-align: center;">Tomador / Asegurado 1</th>
          <th style="font-weight: bold; text-align: center;">Fecha de nacimiento</th>
          <th style="font-weight: bold; text-align: center;">Genero</th>
          <th style="font-weight: bold; text-align: center;">Tipo de cotización</th>
          <th style="font-weight: bold; text-align: center;">Cant. asegurados</th>
          <th style="font-weight: bold; text-align: center;">Asesor</th>
          <th style="font-weight: bold; text-align: center;">Acciones</th>

        </tr>

      </thead>

      <tbody>
        <?php

        if (isset($_GET["fechaInicialCotizaciones"])) {

          $fechaInicialCotizaciones = $_GET["fechaInicialCotizaciones"];
          $fechaFinalCotizaciones = $_GET["fechaFinalCotizaciones"];
          $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesSalud($fechaFinalCotizaciones, $fechaInicialCotizaciones);
        } else {
          $fechaActual = new DateTime();

          // Obtener la fecha de inicio de los últimos 30 días
          $inicioMes = clone $fechaActual;
          $inicioMes->modify('-30 days');
          $inicioMes = $inicioMes->format('Y-m-d');

          // Obtener la fecha de fin (la fecha actual)
          $fechaActual->modify('+1 day');
          $fechaActual = $fechaActual->format('Y-m-d');

          $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesSalud($fechaActual, $inicioMes);
          // var_dump($respuesta);
          // die();  
        }

        $tipoDocumento = [1 => "Cédula de ciudadanía", 4 => "Cédula de extranjería", 2 => "Tarjeta de identidad", 3 => "Registro civil", 5 => "DNI"];

        $genero = [1 => "Masculino", 2 => "Femenino"];

        $tipoCotizacion = [1 => "Individual", 2 => "Familiar"];

        // <td class="text-center" style="font-size: 14px; text-align: center;">' . $tipoDocumento[(int)$value['tipo_documento']] . '</td>
        // <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['cedula_asegurado'] . '</td>

        foreach ($respuesta as $key => $value) {
          //   <td class="text-center" style="font-size: 14px">' . date('Y/m/d', strtotime($value['fch_nacimiento'])) . '</td>
          echo '<tr>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['id_cotizacion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_cotizacion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['nom_asegurado'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fch_nac_asegurado'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $genero[$value['genero_asegurado']] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $tipoCotizacion[$value['num_asegurados'] == 1 ? 1 : 2] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['num_asegurados'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['usu_nombre'] . ' ' . $value['usu_apellido'] . '</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-primary btnEditarCotizacionSalud" idCotizacionSalud="' . $value["id_cotizacion"] . '">Seleccionar</button>';

          // if ($_SESSION["rol"] == 1) {
          //     echo '<button class="btn btn-danger btnEliminarCotizacion" style="display: none !important;" idCotizacion="' . $value["id_cotizacion"] . '"><i class="fa fa-times"></i></button>';
          // }

          echo '   </div>
                    </td>
                  </tr>';
        }

        ?>

      </tbody>

    </table>


  </div>
</div>

<link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\admincotizaciones.css">
<script src="vistas\modulos\SaludCot\js\adminCotizacionesSalud.js?v=<?php echo (rand()); ?>" defer></script>
<script src="vistas\js\cotizaciones_salud.js?v=<?php echo (rand()); ?>" defer></script>
<!-- use version 0.20.3 -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>