<div class="container-fluid mainDataContainer" id="containerDataTable" style="padding-top: 30px; margin-top: 0px;">
  <div class="col-lg-12">
    <div class="row row-aseg" style="margin-bottom: 0px;">
      <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 10px;">
        <label id="lblDataTrip2">Administración de solicitudes Seguro de Hogar</label>
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
          <th style="font-weight: bold; text-align: center;">Tipo Documento</th>
          <th style="font-weight: bold; text-align: center;">No. Documento</th>
          <th style="font-weight: bold; text-align: center;">Nombre Asegurado</th>
          <th style="font-weight: bold; text-align: center;">Tipo de vivienda</th>
          <th style="font-weight: bold; text-align: center;">Categoria a cotizar</th>
          <th style="font-weight: bold; text-align: center;">Zona Vivienda</th>
          <th style="font-weight: bold; text-align: center;">Analista</th>
          <th style="font-weight: bold; text-align: center;">Asesor</th>
          <th style="font-weight: bold; text-align: center;">Dirección</th>
          <th style="font-weight: bold; text-align: center;">Ciudad</th>
          <th style="font-weight: bold; text-align: center;">Departamento</th>
          <th style="font-weight: bold; text-align: center;">Zona Riego</th>
          <th style="font-weight: bold; text-align: center;">Piso</th>
          <th style="font-weight: bold; text-align: center;">Total Piso</th>
          <th style="font-weight: bold; text-align: center;">Año Construcción</th>
          <th style="font-weight: bold; text-align: center;">Area Total</th>
          <th style="font-weight: bold; text-align: center;">Credito</th>
          <th style="font-weight: bold; text-align: center;">Tipo Cobertura</th>
          <th style="font-weight: bold; text-align: center;">Valor Vivienda</th>
          <th style="font-weight: bold; text-align: center;">Valor Contenidos</th>
          <th style="font-weight: bold; text-align: center;">Valor Hurto</th>
          <th style="font-weight: bold; text-align: center;">Valor Todo Riego</th>
          <th style="font-weight: bold; text-align: center;">Mascota</th>
          <th style="font-weight: bold; text-align: center;">Estado</th>

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


        $tipoDocumento = [1 => "Cédula de ciudadanía", 2 => "NIT", 3 => "Cédula de extranjería", 4 => "NA"];
        $tipoVivienda = [1 => "Apartamento", 2 => "Casa", 3 => "Casa en condominio"];
        $disabled = '';
        if ($_SESSION['permisos']['idRol'] == 19) $disabled = 'disabled';

        foreach ($respuesta as $key => $value) {

          if (isset($value['id_tipo_documento'])) {
            $documentoId = (int)$value['id_tipo_documento'];
          } else {
            $documentoId = 4;
          }

          if ($value['tipo_asegurado'] == 1) {
            $tipoAsegurado = 'Solo la estructura';
          } elseif ($value['tipo_asegurado'] == 2) {
            $tipoAsegurado = 'Solo los contenidos';
          } elseif ($value['tipo_asegurado'] == 3) {
            $tipoAsegurado = 'Estructura y sus contenidos';
          } else {
            $tipoAsegurado = 'Deudor';
          }

           if ($value['zona_construccion'] == 1) {
            $zonaVivienda = 'Urbana';
          } elseif ($value['zona_construccion'] == 2) {
            $zonaVivienda = 'Rural';
          } elseif ($value['zona_construccion'] == 3) {
            $zonaVivienda = 'Rural con perímetro urbano';
          } else {
            $zonaVivienda = 'No definido';
          }
          //   <td class="text-center" style="font-size: 14px">' . date('Y/m/d', strtotime($value['fch_nacimiento'])) . '</td>
          echo '<tr>
                    <td class="text-center" style="font-size: 14px; text-align: center; vertical-align: middle;">
                      <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem">
                      <span style="margin-top: 5px;">' . $value['id_hogar'] . '</span>
                      <a href="index.php?ruta=retomar-cotizacion-hogar&idCotizacionHogar=' . $value["id_hogar"] . '" title="Ver detalle"><img src="vistas/img/iconosResources/carpeta.png" style="width: 18px; height: auto;" alt="Mi Icono" width="50" height="50"></a>
                      </div>
                    </td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_cotizacion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $tipoDocumento[(int)$documentoId] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['cli_num_documento'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['cli_nombre'] . ' ' . $value['cli_apellidos'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $tipoVivienda[(int)$value['tipo_vivienda']] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $tipoAsegurado . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $zonaVivienda . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['nombre_analista'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['usu_nombre'] . ' ' . $value['usu_apellido'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['direccion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['ciudad'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['departamento'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['zona_riesgo'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['no_piso'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['no_total_pisos'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['anio_construccion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['area_total'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['credito'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['tipo_cobertura'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['val_viv'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['val_cn'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['val_hur'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['val_tr'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['aseg_mascota'] . '</td>';
                    
          if ($value["estado"] == 'Pendiente') {
            echo '<td><button ' . $disabled . ' style="background: #000000; color: white;" class="btn btn-xs btnActivar btnEditarEstadoHogar" idCotizacionHogar="' . $value["id_hogar"] . '" estadoUsuario="Pendiente">Pendiente</button></td>';
          } else {
            echo '<td><button ' . $disabled . ' style="background: #88d600; color: white;" class="btn btn-xs btnActivar btnEditarEstadoHogar" idCotizacionHogar="' . $value["id_hogar"] . '" estadoUsuario="Cotizada">Cotizada</button></td>';
          }
          // <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['estado'] . '</td>
          // <td class="text-center"><div class="btn-group"><button class="btn btn-primary btnEditarEstadoHogar" idCotizacionHogar="' . $value["id_hogar"] . '"><li class="fa fa-pencil"></li></button>
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