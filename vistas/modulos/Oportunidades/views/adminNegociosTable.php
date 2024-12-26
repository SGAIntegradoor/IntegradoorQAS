<style>
  /* Asegura que los elementos estén en una sola fila */

  .swal2-custom-zindex {
    z-index: 9999 !important;
    /* Mayor que el modal */
  }

  /* Hacer que el encabezado del modal sea fijo */
  .ui-dialog .ui-dialog-titlebar {
    position: sticky !important;
    /* Mantenerlo fijo en la parte superior */
    top: 0 !important;
    /* Asegurarse de que esté pegado a la parte superior */
    z-index: 1 !important;
    /* Mantenerlo sobre el contenido desplazable */
    background: #88d600 !important;
    /* Fondo del encabezado */
    color: white !important;
    /* Color del texto */
    padding: 10px !important;
    /* Espaciado interno */
    border-bottom: 1px solid #ddd !important;
    /* Línea divisoria */
  }

  .ui-dialog .ui-dialog-titlebar-close {
    position: absolute;
    right: .3em;
    top: 50%;
    width: 37px;
    margin: -13px 0 0 0;
    padding: 1px;
    height: 20px;
}

  /* Contenedor principal del modal */
  .ui-dialog {
    max-height: 100vh !important;
    /* Altura máxima del modal */
    overflow: hidden !important;
    /* Ocultar scroll externo */
    padding: 0 !important;
    /* Eliminar padding innecesario */
  }

  /* Hacer desplazable solo el contenido debajo del encabezado */
  .ui-dialog .ui-dialog-content {
    max-height: calc(100vh - 220px) !important;
    /* Restar el alto del encabezado */
    overflow-y: auto !important;
    /* Habilitar scroll vertical */
    padding: 15px;
    /* Opcional: Espaciado interno */
  }

  /* Contenedor de los botones */
  .ui-dialog .ui-dialog-buttonpane {
    position: sticky !important;
    /* Fijo en la parte inferior del área desplazable */
    bottom: 0 !important;
    /* Pegado al fondo */
    z-index: 1 !important;
    /* Mantenerlo sobre el contenido */
    background: white !important;
    /* Fondo de los botones */
    /* Línea divisoria */
    padding: 10px !important;
    /* Espaciado interno */
  }

  /* Estilo del scrollbar en el contenido desplazable */
  .ui-dialog-content::-webkit-scrollbar {
    width: 5px !important;
    /* Ancho del scroll vertical */
  }

  .ui-dialog-content::-webkit-scrollbar-track {
    background: #f1f1f1 !important;
    /* Fondo claro del track */
    border-radius: 5px !important;
    /* Bordes redondeados */
  }

  .ui-dialog-content::-webkit-scrollbar-thumb {
    background: #888 !important;
    /* Color del thumb */
    border-radius: 5px !important;
    /* Bordes redondeados */
  }

  .ui-dialog-content::-webkit-scrollbar-thumb:hover {
    background: #555 !important;
    /* Color más oscuro al hacer hover */
  }


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

  .btnConsultar,
  .btnCancelar {
    margin-top: 26px;
  }

  .select2-container .select2-selection--single {
    height: 34px !important;
    /* Ajusta este valor según el tamaño deseado */
    line-height: 30px;
    /* Alinea el texto verticalmente */
  }


  /* Elimina completamente la barra de título del modal */
  .custom-dialog2 .ui-dialog-titlebar {
    display: block;
  }

  /* Personaliza el cuadro del modal */
  .custom-dialog2 {
    background-color: white;
    /* Fondo blanco */
    /* Borde gris claro */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    /* Sombra suave */
    /* padding: 20px; */
    /* Espaciado interno */
    /* border-radius: 8px; */
    /* Bordes redondeados opcionales */
  }

  /* Botones del modal en la parte inferior */
  .custom-dialog2 .ui-dialog-buttonpane {
    /* Centra los botones */
    background: none;
    /* Sin color de fondo en el contenedor de botones */
    border-top: none;
    /* Sin borde superior */
  }

  .custom-dialog2 .ui-dialog-buttonpane .ui-dialog-buttonset {
    float: none !important;
    text-align: center !important;
  }

  .custom-dialog2 .ui-dialog-buttonset button {
    margin: 5px;
    padding: 10px 20px;
    background-color: #007BFF;
    /* Azul para botón aceptar */
    color: white;
    border: none;
    cursor: pointer;
  }

  .custom-dialog2 .ui-dialog-titlebar-close {
    font-size: 20px;
    color: white;
    border: none;
    background: #88d600;
  }

  #closeButtonModal {
    /* padding: 0 !important; */
    margin: 0 !important;
    font-weight: 100;
    border: 1px solid white;
    padding: 0 5px 0 5px !important;
    border-radius: 4px;
  }
  .custom-dialog2 .ui-widget-header {
    border: 0;
  }

  .custom-dialog2 .ui-resizable-handle {
    /* position: absolute; */
    /* font-size: 0.1px; */
    /* display: block; */
    -ms-touch-action: none;
    touch-action: none;
  }

  .ui-dialog {
    width: 100% !important;
    /* Ocupar todo el ancho disponible */
    max-width: 850px;
    /* Ancho máximo del modal */
    position: fixed !important;
    /* Fijar el modal en relación al viewport */
    top: 50%;
    /* Centrar verticalmente */
    left: 50%;
    /* Centrar horizontalmente */
    transform: translate(-50%, -50%);
    /* Ajustar la posición para centrar exactamente */
    margin: 0;
    /* Elimina márgenes adicionales */
    z-index: 9999;
    /* Asegura que el modal esté por encima de otros elementos */
    max-height: 90vh;
    /* Limita la altura al 90% del viewport */
    overflow-y: auto;
    /* Habilita el scroll vertical si el contenido es extenso */
  }

  .custom-dialog2 .ui-dialog-content {
    max-height: calc(80vh - 150px);
    /* Ajusta el contenido según el viewport */
    overflow-y: auto;
    /* Habilita el scroll dentro del contenido */
    padding: 0 !important;
    /* Elimina el padding del contenido */
  }

  .ui-dialog-titlebar {
    padding: 0;
    /* Elimina el padding del encabezado */
  }

  .custom-dialog2 .ui-dialog .col-lg-12 {
    padding: 0;
    /* Elimina márgenes adicionales del contenido */
  }

  .custom-dialog2 {
    width: 100%;
    /* Se adapta al contenedor */
    box-sizing: border-box;
    /* Incluye padding y borde en el ancho total */
  }

  #btnGuardar {
    background-color: #88d600;
    min-width: 100px;
  }

  #btnCerrar {
    background-color: black;
    min-width: 100px;
    margin-right: 36px;
  }

  .custom-dialog2 .ui-dialog-title {
    color: white;
    font-weight: 600;
    font-size: 13px;
    padding-left: 15px;
  }


  .custom-dialog2 .ui-dialog-titlebar {
    padding: 1.0em 1em;
    position: relative;
  }

  .custom-dialog2 .ui-corner-all,
  .ui-corner-bottom,
  .ui-corner-right,
  .ui-corner-br {
    border-bottom-right-radius: 0px;
  }

  .custom-dialog2 .ui-corner-all,
  .ui-corner-bottom,
  .ui-corner-left,
  .ui-corner-bl {
    border-bottom-left-radius: 0px;
  }

  .ui-widget-content {
    border: 0;
  }

  #myModal2 {
    padding: 11px 58px 0px 58px !important;
  }

  .ui-widget.ui-widget-content {
    border: 0;
  }

  .ui-dialog {
    padding: 0;
  }

  .no-resize {
    /* resize: vertical; Permite cambiar solo la altura, no el ancho */
    /* Si deseas deshabilitar completamente el redimensionamiento, usa: */
    resize: none;
    margin: 0 !important;
  }

  .full-width-textarea {
    width: 100%;
    /* Ocupa el 100% del ancho disponible */
    box-sizing: border-box;
    /* Incluye el padding y el borde en el cálculo del ancho */
  }



  #select2-txtEstadoOportunidadModal-results {
    max-height: 84px !important;
    overflow-y: auto;
  }


  /* Ajusta estos estilos según tus necesidades */
</style>


<div class="container-fluid" id="containerTable">
  <div style="margin-bottom: 20px">
    <button class="btn btn-primary" style="margin-left: 10px" onclick="abrirDialogoCrear()">Agregar Oportunidad</button>
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
  </div>

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
          <th style="font-weight: bold; text-align: center;">N° cot aseguradora</th>
          <th style="font-weight: bold; text-align: center;">Valor cotizacion</th>
          <th style="font-weight: bold; text-align: center;">Mes oportunidad</th>
          <th style="font-weight: bold;">Asesor freelance</th>
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
          <th style="font-weight: bold; text-align: center;">Valor Total</th>
          <th style="font-weight: bold; text-align: center;">Fecha expedición</th>
          <th style="font-weight: bold; text-align: center;">Mes expedición</th>
          <th style="font-weight: bold; text-align: center;">Forma de pago</th>
          <th style="font-weight: bold; text-align: center;">Financiera</th>
          <th style="font-weight: bold; text-align: center;">Carpeta</th>
          <th style="font-weight: bold; text-align: center;">Id_Oferta</th>
          <th style="font-weight: bold; text-align: center;">Fecha Creacion</th>
          <th style="font-weight: bold; text-align: center;">Fecha Actualizacion</th>
        </tr>
      </thead>
      <tbody>
        <?php

        if (isset($_GET["fechaInicialOportunidades"])) {
          $fechaInicialOportunidades = $_GET["fechaInicialOportunidades"];
          $fechaFinalOportunidades = $_GET["fechaFinalOportunidades"];
          // $respuesta = ControladorOportunidades::ctrMostrarOportunidades($fechaFinalOportunidades, $fechaInicialOportunidades);
          $respuesta = ControladorOportunidades::ctrMostrarOportunidades($fechaFinalOportunidades, $fechaInicialOportunidades);
        } else if (isset($_GET["mesExpedicion"]) || isset($_GET["estado"]) || isset($_GET["nombreAsesor"]) || isset($_GET["analistaGA"]) || isset($_GET["aseguradoraOpo"]) || isset($_GET["ramo"]) || isset($_GET["onerosoOp"]) || isset($_GET["formaDePago"]) || isset($_GET["financiera"]) || isset($_GET["carpeta"])) {
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
            <td class="text-center" style="text-align: center !important;"><div class="btn-group"><button class="btn btn-primary btnEditarOportunidad" onclick="editarOportunidad(' . $value['id_oportunidad'] . ')"><i class="fa-sharp fa-solid fa-pen"></i></button></div></td>
            <td class="" style="font-size: 14px;">' . (!empty($value['id_oportunidad']) ? $value['id_oportunidad'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['id_cotizacion']) ? $value['id_cotizacion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['id_cot_aseguradora']) ? $value['id_cot_aseguradora'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['valor_cotizacion']) ? '$ ' . number_format($value['valor_cotizacion'], 0, ',', '.') : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['mes_oportunidad']) ? $value['mes_oportunidad'] : '') . '</td>
            <td class="" style="font-size: 14px; width: 200px">' . (!empty($value['asesor_freelance']) ? $value['asesor_freelance'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['ramo']) ? $value['ramo'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['placa']) ? $value['placa'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['oneroso']) ? $value['oneroso'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['aseguradora']) ? $value['aseguradora'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['analista_comercial']) ? $value['analista_comercial'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['estado']) ? $value['estado'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['no_poliza']) ? $value['no_poliza'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['asegurado']) ? $value['asegurado'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['prima_sin_iva']) ? '$ ' . number_format($value['prima_sin_iva'], 0, ',', '.') : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['asist_otros']) ? '$ ' . number_format($value['asist_otros'], 0, ',', '.') : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['gastos']) ? '$ ' . number_format($value['gastos'], 0, ',', '.') : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['iva']) ? '$ ' . number_format($value['iva'], 0, ',', '.') : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['valor_total']) ? '$ ' . number_format($value['valor_total'], 0, ',', '.') : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['fecha_expedicion']) && $value['fecha_expedicion'] != "0000-00-00" ? $value['fecha_expedicion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['mes_expedicion']) ? $value['mes_expedicion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['forma_pago']) ? $value['forma_pago'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['financiera']) ? $value['financiera'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['carpeta']) ? $value['carpeta'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['id_oferta']) ? $value['id_oferta'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['fecha_creacion']) ? $value['fecha_creacion'] : '') . '</td>
            <td class="text-center" style="font-size: 14px;">' . (!empty($value['fecha_actualizacion']) ? $value['fecha_actualizacion'] : '') . '</td>
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

<div id="myModal2" style="display: none;">
  <div class="col-lg-12" id="realModal">
    <form action="POST">

      <div class="row" style="margin-bottom: 10px; margin-top: 30px;">
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtnoCotizacionModal">No. cotización</label>
          <input type="text" class="form-control" name="txtnoCotizacionModal" id="txtnoCotizacionModal" placeholder="">
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtValorCotizacionModal">Valor cotización</label>
          <input type="text" class="form-control" name="txtValorCotizacionModal" id="txtValorCotizacionModal" placeholder="" required>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtMesOportunidadModal">Mes oportunidad</label>
          <select type="text" class="form-control mes-expedicion" name="txtMesOportunidadModal" id="txtMesOportunidadModal" placeholder="">
            <option value="" selected>
            </option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select>
        </div>
      </div>
      <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtAsesorOportunidadModal">Asesor Freelance</label>
          <select class="form-control" name="txtAsesorOportunidadModal" id="txtAsesorOportunidadModal" required>
            <option value="" selected></option>
          </select>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtRamoModal">Ramo</label>
          <select class="form-control" name="txtRamoModal" id="txtRamoModal" required>
            <option value="" selected></option>
            <option value="1">Automoviles</option>
            <option value="2">Pesados</option>
            <option value="3">Motos</option>
            <option value="4">RCE autos</option>
            <option value="5">Exequial</option>
            <option value="6">Salud</option>
            <option value="7">Pyme</option>
            <option value="8">Vida</option>
            <option value="9">Vida deudor</option>
            <option value="10">Hogar</option>
            <option value="11">Hogar deudor</option>
            <option value="12">Asistencia en viajes</option>
          </select>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtPlacaOportunidadModal">Placa</label>
          <input type="text" class="form-control" name="txtPlacaOportunidadModal" id="txtPlacaOportunidadModal" placeholder="" required>
          <p id="errorMensaje" style="display: none; color: tomato">Formato placa invalido</p>
        </div>
      </div>
      <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtOnerosoOportunidadModal">Tiene oneroso</label>
          <select type="text" class="form-control oneroso-op" name="txtOnerosoOportunidadModal" id="txtOnerosoOportunidadModal" placeholder="" required>
            <option value="" selected></option>
            <option value="1">
              Si
            </option>
            <option value="2">
              No
          </select>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtAseguradoraOportunidadModal">Aseguradora</label>
          <select type="text" class="form-control asegurado-opo" name="txtAseguradoraOportunidadModal" id="txtAseguradoraOportunidadModal" placeholder="Aseguradora" required>
            <option value="" selected>
            </option>
            <option value="1">Allianz</option>
            <option value="2">Axa Colpatria</option>
            <option value="3">Bolivar</option>
            <option value="4">Equidad</option>
            <option value="5">Estado</option>
            <option value="6">HDI (Antes Liberty)</option>
            <option value="6">Mapfre</option>
            <option value="7">Previsora</option>
            <option value="8">SBS Seguros</option>
            <option value="9">Solidaria</option>
            <option value="10">Zurich</option>
            <option value="11">Mundial</option>
            <option value="12">AssistCard</option>
            <option value="12">AssistOne</option>
            <option value="12">Universal</option>
            <option value="12">Continental</option>
            <option value="12">Olivos</option>
          </select>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtAnalistaGAModal">Analista/asesor GA</label>
          <select type="text" class="form-control" name="txtAnalistaGAModal" id="txtAnalistaGAModal" placeholder="" required>
            <option value=""></option>
          </select>
        </div>
      </div>
      <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtEstadoOportunidadModal">Estado</label>
          <select type="text" class="form-control" name="txtEstadoOportunidadModal" id="txtEstadoOportunidadModal" required>
            <option value="" selected></option>
            <option value="1">Pdte orden inspección</option>
            <option value="2">Pdte inspección</option>
            <option value="3">Pdt emisión</option>
            <option value="4">Emitida</option>
            <option value="5">Perdido</option>
          </select>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 form-group">
          <label for="txtAseguradoModal">Asegurado</label>
          <input type="text" class="form-control" name="" id="txtAseguradoModal" placeholder="">
        </div>
        <div style="display:none" id="firstHide">
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtNoPolizaOportunidadModal"># póliza</label>
            <input type="text" class="form-control" name="" id="txtNoPolizaOportunidadModal" placeholder="">
          </div>
        </div>
      </div>
      <div id="secondHide" style="display: none">
        <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtPrimaSinIvaModal">Prima (sin iva)</label>
            <input type="text" class="form-control" name="txtPrimaSinIvaModal" id="txtPrimaSinIvaModal" placeholder="">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtGastosOportunidadModal">Gastos</label>
            <input type="text" class="form-control" name="txtGastosOportunidadModal" id="txtGastosOportunidadModal" placeholder="">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtAsistOtrosOportunidadModal">Asist/Otros</label>
            <input type="text" class="form-control" name="txtAsistOtrosOportunidadModal" id="txtAsistOtrosOportunidadModal" placeholder="">
          </div>
        </div>
        <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtIvaOportunidadModal">IVA</label>
            <input type="text" class="form-control" name="txtIvaOportunidadModal" id="txtIvaOportunidadModal">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtValorTotalModal">Valor Total</label>
            <input type="text" class="form-control" name="txtValorTotalModal" id="txtValorTotalModal">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtFechaExpedicionOportunidadModal">Fecha de expedición</label>
            <input type="date" class="form-control" name="txtFechaExpedicionOportunidadModal" id="txtFechaExpedicionOportunidadModal">
          </div>
        </div>
        <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtMesExpedicionOportunidadModal">Mes expedición</label>
            <select type="text" class="form-control mes-expedicion" name="txtMesExpedicionOportunidadModal" id="txtMesExpedicionOportunidadModal" placeholder="">
              <option value="" selected>
              </option>
              <option value="1">Enero</option>
              <option value="2">Febrero</option>
              <option value="3">Marzo</option>
              <option value="4">Abril</option>
              <option value="5">Mayo</option>
              <option value="6">Junio</option>
              <option value="7">Julio</option>
              <option value="8">Agosto</option>
              <option value="9">Septiembre</option>
              <option value="10">Octubre</option>
              <option value="11">Noviembre</option>
              <option value="12">Diciembre</option>
            </select>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4 form-group">
            <label for="txtFormaDePagoOportunidadModal">Forma de pago</label>
            <select type="text" class="form-control mes-expedicion" name="txtFormaDePagoOportunidadModal" id="txtFormaDePagoOportunidadModal" placeholder="">
              <option value="" selected>
              </option>
              <option value="1">
                Financiada
              </option>
              <option value="2">
                Contado
              </option>
              <option value="2">
                Pdte.
              </option>

            </select>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4 form-group" id="financieraDiv" style="display:none">
            <label for="txtFinancieraOportunidadModal">Financiera</label>
            <select type="text" class="form-control financiera" name="txtFinancieraOportunidadModal" id="txtFinancieraOportunidadModal" placeholder="">
              <option value="" selected>
              </option>
              <option value="2">Finesa</option>
              <option value="3">Liberty</option>
              <option value="4">Bolivar</option>
            </select>
          </div>
        </div>
        <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
          <div class="col-xs-12 col-sm-6 col-md-4 form-group" style="display:flex; flex-direction: column">
            <label for="checkCarpetaModal">Carpeta</label>
            <input type="checkbox" name="checkCarpetaModal" id="checkCarpetaModal" style="width: 20px; height:20px">
          </div>
        </div>
      </div>
      <div class="col-12">
        <label for="txtObservacionesOportunidadModal">Observaciones</label>
        <textarea class="form-control form-group no-resize full-width-textarea" rows="3" id="txtObservacionesOportunidadModal"></textarea>

      </div>
    </form>
  </div>
</div>


<!-- <link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\admincotizaciones.css"> -->
<!-- <script src="vistas\modulos\AssistCardCot\js\adminCotizacionesAssistCard.js?v=<?php echo (rand()); ?>" defer></script> -->
<!-- <script src="vistas\js\cotizaciones_assistcard.js?v=<?php echo (rand()); ?>" defer></script> -->
<!-- use version 0.20.3 -->
<script src="vistas\modulos\Oportunidades\js\functions.js?v=<?php echo (rand()); ?>"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>