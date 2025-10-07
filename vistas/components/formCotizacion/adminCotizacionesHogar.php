<div class="container-fluid mainDataContainer" id="containerDataTable" style="padding-top: 30px; margin-top: 0px;">
  <div class="col-lg-12">
    <div class="row row-aseg" style="margin-bottom: 0px;">
      <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 10px;">
        <label id="lblDataTrip2">Administración de cotizaciones Seguro de Hogar</label>
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
          <th style="font-weight: bold; text-align: center;">FechaCot</th>
          <th style="font-weight: bold; text-align: center;">Tipo Documento</th>
          <th style="font-weight: bold; text-align: center;">No. Documento</th>
          <th style="font-weight: bold; text-align: center;">Nombre Tomador</th>
          <th style="font-weight: bold; text-align: center;">Dirección</th>
          <th style="font-weight: bold; text-align: center;">Tipo de Vivienda</th>
          <th style="font-weight: bold; text-align: center;">Año de Construcción</th>
          <th style="font-weight: bold; text-align: center;">Credito Hip.</th>
          <th style="font-weight: bold; text-align: center;">Asesor</th>
          <th style="font-weight: bold; text-align: center;">Estado</th>
          <th style="font-weight: bold; text-align: center;">Acción</th>
          <!-- <th style="font-weight: bold; text-align: center;">Acciones</th> -->

        </tr>

      </thead>

      <tbody>
        <?php

        if (isset($_GET["fechaInicialCotizaciones"])) {

          $fechaInicialCotizaciones = $_GET["fechaInicialCotizaciones"];
          $fechaFinalCotizaciones = $_GET["fechaFinalCotizaciones"];
          $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesHogar($fechaFinalCotizaciones, $fechaInicialCotizaciones);
        } else {
          $fechaActual = new DateTime();

          // Obtener la fecha de inicio de los últimos 30 días
          $inicioMes = clone $fechaActual;
          $inicioMes->modify('-30 days');
          $inicioMes = $inicioMes->format('Y-m-d');

          // Obtener la fecha de fin (la fecha actual)
          $fechaActual->modify('+1 day');
          $fechaActual = $fechaActual->format('Y-m-d');

          $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesHogar($fechaActual, $inicioMes);
        }


        $tipoDocumento = [1 => "Cédula de ciudadanía", 4 => "Cédula de extranjería", 2 => "Tarjeta de identidad", 3 => "Registro civil", 5 => "DNI"];
        $tipoVivienda = [1 => "Apartamento", 2 => "Casa", 3 => "Casa en condominio"];

        foreach ($respuesta as $key => $value) {
          //   <td class="text-center" style="font-size: 14px">' . date('Y/m/d', strtotime($value['fch_nacimiento'])) . '</td>
          echo '<tr>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['id_hogar'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_cotizacion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $tipoDocumento[(int)$value['id_tipo_documento']] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['cli_num_documento'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['cli_nombre'] . ' ' . $value['cli_apellidos'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['direccion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $tipoVivienda[(int)$value['tipo_vivienda']] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['anio_construccion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['credito'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['usu_nombre'] . ' ' . $value['usu_apellido'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['estado'] . '</td>
                    <td class="text-center"><div class="btn-group"><button class="btn btn-primary btnEditarEstadoHogar" idCotizacionHogar="' . $value["id_hogar"] . '"><li class="fa fa-pencil"></li></button>';
                    // <td class="text-center">
                    //     <div class="btn-group">
                    //         <button class="btn btn-primary btnEditarCotizacionHogar" idCotizacionHogar="' . $value["id_hogar"] . '">Seleccionar</button>';

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
<script src="vistas\js\cotizaciones_hogar.js?v=<?php echo (rand()); ?>" defer></script>
<script src="vistas\components\formCotizacion\js\adminCotizacionesHogar.js?v=<?php echo (rand()); ?>" defer></script>
<!-- use version 0.20.3 -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>