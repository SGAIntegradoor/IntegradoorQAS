<div class="container-fluid mainDataContainer" id="containerDataTable" style="padding-top: 30px; margin-top: 0px;">
  <div class="col-lg-12">
    <div class="row row-aseg" style="margin-bottom: 0px;">
      <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 10px;">
        <label id="lblDataTrip2">Administración de solicitudes SOAT</label>
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
<div class="container-fluid" id="containerTable" style="margin-top: 20px;">
  <div class="box-body">
    <button type="button" class="btn btn-default pull-right" id="daterange-btnCotizacionesHogar">
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


    <table class="table table-bordered table-striped dt-responsive tablas-hogar" width="100%">

      <thead>

        <tr>

          <th style="font-weight: bold; text-align: center;">N°</th>
          <th style="font-weight: bold; text-align: center;">FechaSoli</th>
          <th style="font-weight: bold; text-align: center;">Placa</th>
          <th style="font-weight: bold; text-align: center;">Clase</th>
          <th style="font-weight: bold; text-align: center;">Referencia</th>
          <th style="font-weight: bold; text-align: center;">Correo</th>
          <th style="font-weight: bold; text-align: center;">Celular</th>
          <th style="font-weight: bold; text-align: center;">Opción</th>
          <th style="font-weight: bold; text-align: center;">Analista</th>
          <th style="font-weight: bold; text-align: center;">Asesor</th>
          
          <th style="font-weight: bold; text-align: center;">Estado</th>

        </tr>

      </thead>

      <tbody>
        <?php

        if (isset($_GET["fechaInicialCotizaciones"])) {

          $fechaInicialCotizaciones = $_GET["fechaInicialCotizaciones"];
          $fechaFinalCotizaciones = $_GET["fechaFinalCotizaciones"];
          $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesSoat($fechaFinalCotizaciones, $fechaInicialCotizaciones);
        } else {
          $fechaActual = new DateTime();

          // Obtener la fecha de inicio de los últimos 30 días
          $inicioMes = clone $fechaActual;
          $inicioMes->modify('-30 days');
          $inicioMes = $inicioMes->format('Y-m-d');

          // Obtener la fecha de fin (la fecha actual)
          $fechaActual->modify('+1 day');
          $fechaActual = $fechaActual->format('Y-m-d');

          $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesSoat($fechaActual, $inicioMes);
        }


        // $tipoDocumento = [1 => "Cédula de ciudadanía", 2 => "NIT", 3 => "Cédula de extranjería", 4 => "NA"];
        // $tipoVivienda = [1 => "Apartamento", 2 => "Casa", 3 => "Casa en condominio"];
        $disabled = '';
        if ($_SESSION['permisos']['idRol'] == 19) $disabled = 'disabled';

        foreach ($respuesta as $key => $value) {

          echo '<tr>
                    <td class="text-center" style="font-size: 14px; text-align: center; vertical-align: middle;">
                      <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem">
                      <span style="margin-top: 5px;">' . $value['id_cotizacion'] . '</span>
                      <a href="index.php?ruta=retomar-cotizacion-soat&idCotizacionSoat=' . $value["id_cotizacion"] . '" title="Ver detalle"><img src="vistas/img/iconosResources/carpeta.png" style="width: 18px; height: auto;" alt="Mi Icono" width="50" height="50"></a>
                      </div>
                    </td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_creacion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['placa'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['clase'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['referencia'] .'</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['correo'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['celular'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['opcion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['nombre_analista'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['asesor'] . '</td>';
                    
          if ($value["estado"] == 'Soat Expedido') {
            echo '<td><button ' . $disabled . ' style="background: #88d600; color: white;" class="btn btn-xs btnActivar' ./* btnEditarEstadoSoat*/ '" idCotizacionSoat="' . $value["id_cotizacion"] . '" estadoUsuario="Pendiente">' . $value["estado"] . '</button></td>';
          } else if($value["estado"] == 'Solicitud devuelta') {
            echo '<td><button ' . $disabled . ' style="background: #b64444; color: white;" class="btn btn-xs btnActivar' ./* btnEditarEstadoSoat*/ '" idCotizacionSoat="' . $value["id_cotizacion"] . '" estadoUsuario="Pendiente">' . $value["estado"] . '</button></td>';
          } else {
            echo '<td><button ' . $disabled . ' style="background: #000000; color: white;" class="btn btn-xs btnActivar' ./* btnEditarEstadoSoat*/ '" idCotizacionSoat="' . $value["id_cotizacion"] . '" estadoUsuario="Cotizada">' . $value["estado"] . '</button></td>';
          }
          echo '</tr>';
        }

        ?>

      </tbody>

    </table>


  </div>
</div>

<link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\admincotizaciones.css">
<script src="vistas\modulos\soat\js\cotizaciones_soat.js?v=<?php echo (rand()); ?>" defer></script>
<script src="vistas\modulos\soat\js\adminCotizacionesSoat.js?v=<?php echo (rand()); ?>" defer></script>
<!-- use version 0.20.3 -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>