
<div class="container-fluid" id="containerTable1">

  <div class="box-body table-scroll">
    <div id="custom-header-container">
      <div id="custom-buttons-container">
        <!-- Aquí aparecerán los botones, como Exportar a Excel -->
      </div>
      <div id="custom-search-container">
        <!-- Aquí aparecerá el buscador -->
      </div>
    </div>

    <div style="overflow-x: auto;">

      <table class="table table-bordered tabla-productividad" style="width: 100%; text-align: center;">
        <thead>
        <tr>
          <th rowspan="2">Asesor</th>
          <th rowspan="2">Fecha de ingreso</th>
          <th rowspan="2">Estado Usuario</th>
          <th rowspan="2">Analista</th>
          <th colspan="3" id="tituloMes1"><strong>ENERO</th>
          <th colspan="3" id="tituloMes2">FEBRERO</th>
          <th colspan="3" id="tituloMes3">MARZO</th>
          <th colspan="3">TOTALES</th>
        </tr>
        <tr>
          <th>Cant. cotizaciones</th>
          <th>Negocios</th>
          <th>% efectividad</th>
          <th>Cant. cotizaciones</th>
          <th>Negocios</th>
          <th>% efectividad</th>
          <th>Cant. cotizaciones</th>
          <th>Negocios</th>
          <th>% efectividad</th>
          <th>Cant. cotizaciones</th>
          <th>Negocios</th>
          <th>% efectividad</th>
        </tr>
        </thead>
        <tbody></tbody> 
      </table>
    </div>
    <div id="resumenMeses" class="mt-4">
    <p><strong>Cant. cotizaciones mes actual:</strong> <span id="cotMes1">0</span></p>
    <p><strong>Cant. negocios mes actual:</strong> <span id="negMes1">0</span></p>

    <p><strong>Cant. cotizaciones mes anterior:</strong> <span id="cotMes2">0</span></p>
    <p><strong>Cant. negocios mes anterior:</strong> <span id="negMes2">0</span></p>

    <p><strong>Cant. cotizaciones hace 2 meses:</strong> <span id="cotMes3">0</span></p>
    <p><strong>Cant. negocios hace 2 meses:</strong> <span id="negMes3">0</span></p>

    <p><strong>% porcentaje promedio:</strong> <span id="promEfectividad">0%</span></p>
    
  </div>

  </div>

</div>


<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>
<script src="vistas\modulos\Productividad\js\productividadFunctions.js?v=<?php echo (rand()); ?>"></script>
<link rel="stylesheet" href="vistas\modulos\AssistCardCot\css\cotizador.css">
<link rel="stylesheet" href="vistas\modulos\Productividad\css\styles.css">