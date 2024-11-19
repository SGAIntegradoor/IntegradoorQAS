<style>
  /* Asegura que los elementos estén en una sola fila */
  .top {
    display: flex;
    justify-content: space-between;
    /* Distribuye el espacio entre los elementos */
    align-items: center;
    /* Alinea los elementos verticalmente */
    width: 100%;
    /* Asegura que ocupe el espacio completo */
  }

  /* Agrupa el botón de Excel y el selector en una sola sección */
  .top .dt-buttons {
    display: flex;
    align-items: center;
    /* Alinea verticalmente el contenido del botón de Excel */
    margin-right: 10px;
    /* Espacio a la derecha */
  }

  /* Asegura que el campo de búsqueda esté al final */
  .top .dataTables_filter {
    margin-left: auto;
    /* Empuja el buscador al final */
  }

  /* Espaciado entre el selector y el botón */
  .top .dataTables_length {
    margin-right: 10px;
    /* Añadir espacio entre el selector y el botón de Excel */
  }

  .btnConsultar, .btnCancelar {
    margin-top: 26px;
    max-width: 300px;
   }

  .select2-container .select2-selection--single {
    height: 30px;
    /* Ajusta este valor según el tamaño deseado */
    line-height: 30px;
    /* Alinea el texto verticalmente */
  }

  .select2-container .select2-selection__rendered {
    line-height: 30px;
    /* Asegúrate de que el texto esté centrado */
  }

  .select2-container .select2-selection--single .select2-selection__arrow {
    height: 30px;
    /* Ajusta la flecha al mismo alto */
  }
</style>


<div class="container-fluid" id="containerTable">
  <button type="button" class="btn btn-default pull-right" style="margin-right: 10px" id="daterange-btnOportunidades">
    <span>
      <i class="fa fa-calendar"></i>
      <?php
      require_once 'controladores/oportunidades.controlador.php';

      global $total;
      global $totalCot;
      global $totalPrimasPoliza;

      $total = 0;
      $totalCot = 0;

      if (isset($_GET["fechaInicialOportunidades"])) {
        echo $_GET["fechaInicialOportunidades"] . " - " . $_GET["fechaFinalOportunidades"];
      } else {
        echo 'Rango de fecha';
      }
      ?>
    </span>
    <i class="fa fa-caret-down"></i>
  </button>

  <div class="box-body table-scroll">
    <div id="custom-header-container">
      <div id="custom-buttons-container">
        <!-- Aquí aparecerán los botones, como Exportar a Excel -->
      </div>
      <div id="custom-search-container">
        <!-- Aquí aparecerá el buscador -->
      </div>
    </div>
    <table class="table table-bordered table-striped tablas-oportunidades" width="100%">
      <thead>
        <tr>
          <th style="font-weight: bold; text-align: center;">Accion</th>
          <th style="font-weight: bold; text-align: center;">No. oport</th>
          <th style="font-weight: bold; text-align: center;">N° cotizacion</th>
          <th style="font-weight: bold; text-align: center;">Valor cotizacion</th>
          <th style="font-weight: bold; text-align: center;">Mes oportunidad</th>
          <th style="font-weight: bold; text-align: center;">Asesor freelance</th>
          <th style="font-weight: bold; text-align: center;">Ramo</th>
          <th style="font-weight: bold; text-align: center;">Placa</th>
          <th style="font-weight: bold; text-align: center;">¿Tiene oneroso?</th>
          <th style="font-weight: bold; text-align: center;">Aseguradora</th>
          <th style="font-weight: bold; text-align: center;">Analista/comercial</th>
          <th style="font-weight: bold; text-align: center;">Estado</th>
          <th style="font-weight: bold; text-align: center;"># Poliza</th>
          <th style="font-weight: bold; text-align: center;">Asegurado</th>
          <th style="font-weight: bold; text-align: center;">Prima sin iva</th>
          <th style="font-weight: bold; text-align: center;">Asist/Otros</th>
          <th style="font-weight: bold; text-align: center;">Gastos</th>
          <th style="font-weight: bold; text-align: center;">IVA</th>
          <th style="font-weight: bold; text-align: center;">Fecha expedición</th>
          <th style="font-weight: bold; text-align: center;">Mes expedición</th>
          <th style="font-weight: bold; text-align: center;">Forma de pago</th>
          <th style="font-weight: bold; text-align: center;">Financiera</th>
          <th style="font-weight: bold; text-align: center;">Carpeta</th>
          <th style="font-weight: bold; text-align: center;">Observaciones</th>
          <th style="font-weight: bold; text-align: center;">Id_Oferta</th>
          <th style="font-weight: bold; text-align: center;">Fecha Creacion</th>
        </tr>
      </thead>
      <tbody>
        <?php

        if (isset($_GET["fechaInicialOportunidades"])) {
          $fechaInicialOportunidades = $_GET["fechaInicialOportunidades"];
          $fechaFinalOportunidades = $_GET["fechaFinalOportunidades"];
          // $respuesta = ControladorOportunidades::ctrMostrarOportunidades($fechaFinalOportunidades, $fechaInicialOportunidades);
          $respuesta = ControladorOportunidades::ctrMostrarOportunidades($fechaFinalOportunidades, $fechaInicialOportunidades);
        } else if (isset($_GET["mesExpedicion"]) || isset($_GET["estado"]) || isset($_GET["nombreAsesor"]) || isset($_GET["analistaGA"]) || isset($_GET["aseguradoraOpo"]) || isset($_GET["ramo"]) || isset($_GET["onerosoOp"]) || isset($_GET["formaDePago"]) || isset($_GET["financiera"])) {
          $respuesta = ControladorOportunidades::ctrMostrarOportunidadesFilters($_GET);

        } else {
          $fechaActual = new DateTime();

          // Obtener la fecha de inicio de los últimos 30 días
          $inicioMes = clone $fechaActual;
          $inicioMes->modify('-30 days');
          $inicioMes = $inicioMes->format('Y-m-d');

          // Obtener la fecha de fin (la fecha actual)
          $fechaActual->modify('+1 day');
          $fechaActual = $fechaActual->format('Y-m-d');

          $respuesta = ControladorOportunidades::ctrMostrarOportunidades($fechaActual, $inicioMes);
        }


        if (!empty($respuesta)) {
          foreach ($respuesta as $key => $value) {
            echo '<tr>
            <td class="text-center"><div class="btn-group"><button class="btn btn-primary btnEditarOportunidad"><i class="fa-sharp fa-solid fa-pen"></i></button></div></td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['id_oportunidad']) ? $value['id_oportunidad'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['id_cotizacion']) ? $value['id_cotizacion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['valor_cotizacion']) ? $value['valor_cotizacion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['mes_oportunidad']) ? $value['mes_oportunidad'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['asesor_freelance']) ? $value['asesor_freelance'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['ramo']) ? $value['ramo'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['placa']) ? $value['placa'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['oneroso']) ? $value['oneroso'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['aseguradora']) ? $value['aseguradora'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['analista_comercial']) ? $value['analista_comercial'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['estado']) ? $value['estado'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['no_poliza']) ? $value['no_poliza'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['asegurado']) ? $value['asegurado'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['prima_sin_iva']) ? $value['prima_sin_iva'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['asist_otros']) ? $value['asist_otros'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['gastos']) ? $value['gastos'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['iva']) ? $value['iva'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['fecha_expedicion']) ? $value['fecha_expedicion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['mes_expedicion']) ? $value['mes_expedicion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['forma_pago']) ? $value['forma_pago'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['financiera']) ? $value['financiera'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['carpeta']) ? $value['carpeta'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['observaciones']) ? $value['observaciones'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['id_oferta']) ? $value['id_oferta'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['fecha_creacion']) ? $value['fecha_creacion'] : '') . '</td>
            </tr>';
            $total += isset($value['valor_cotizacion']) ? $value['valor_cotizacion'] : 0;
            $totalPrimasPoliza += $value['prima_sin_iva'] == "" || $value['prima_sin_iva'] == NULL ? 0 : $value['prima_sin_iva'];
            $totalCot += 1;
          }
        } 
        ?>

      </tbody>

    </table>


  </div>

  <?php

  echo '<div style="margin-left: 10px; margin-bottom: 10px"> 
    <b>
    Primas netas de póliza: $ ' . number_format($totalPrimasPoliza, 0, ',', '.') . '<br>
        Valores totales cotizaciones: $ ' . number_format($total, 0, ',', '.') . '<br>
        Unidades: ' . number_format($totalCot, 0, ',', '.') . '
    </b>
</div>';


  ?>

</div>

<!-- <link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\admincotizaciones.css"> -->
<!-- <script src="vistas\modulos\AssistCardCot\js\adminCotizacionesAssistCard.js?v=<?php echo (rand()); ?>" defer></script> -->
<!-- <script src="vistas\js\cotizaciones_assistcard.js?v=<?php echo (rand()); ?>" defer></script> -->
<!-- use version 0.20.3 -->
<script src="vistas\modulos\Oportunidades\js\functions.js?v=<?php echo (rand()); ?>"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>