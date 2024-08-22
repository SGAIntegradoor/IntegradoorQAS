<div class="container-fluid mainDataContainer" id="containerDataTable">
    <div class="col-lg-12">
        <div class="row row-aseg">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label id="lblDataTrip">Administracion de cotizaciones asistencia en viajes</label>        
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div id="masAdminCoti" >
                     <p id="masCots">Ver más <i class="fa fa-plus-square-o"></i></p>
                </div>
                <div id="menosAdminCoti">
                     <p id="menosCots">Ver menos <i class="fa fa-minus-square-o"></i></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid"  id="containerTable">
    <button type="button" class="btn btn-default pull-right" id="daterange-btnCotizaciones">       
        <span>
        <i class="fa fa-calendar"></i> 
        <?php
            if(isset($_GET["fechaInicialCotizaciones"])){
            echo $_GET["fechaInicialCotizaciones"]." - ".$_GET["fechaFinalCotizaciones"];
            }else{
            echo 'Rango de fecha';
            }
        ?>
        </span>
        <i class="fa fa-caret-down"></i>
    </button>
    <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas-assistcard" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="font-weight: bold; text-align: center;">N°</th>
           <th style="font-weight: bold; text-align: center;">FechaCot</th>
           <th style="font-weight: bold; text-align: center;">Nombre Prospecto</th>
           <th style="font-weight: bold; text-align: center; display: none;">Fecha Nacimiento</th>
           <th style="font-weight: bold; text-align: center;">Lugar Origen</th>
           <th style="font-weight: bold; text-align: center;">Lugar Destino</th>
           <th style="font-weight: bold; text-align: center;">Numero Pasajeros</th>
           <th style="font-weight: bold; text-align: center;">Fecha Salida</th>
           <th style="font-weight: bold; text-align: center;">Fecha Regreso</th>
           <th style="font-weight: bold; text-align: center;">Motivo</th>
           <th style="font-weight: bold; text-align: center;">Asesor</th>
           <th style="font-weight: bold; text-align: center;">Acciones</th>

         </tr> 

        </thead>

        <tbody>
        <?php

          if(isset($_GET["fechaInicialCotizaciones"])){

            $fechaInicialCotizaciones = $_GET["fechaInicialCotizaciones"];
            $fechaFinalCotizaciones = $_GET["fechaFinalCotizaciones"];
            $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesAssistCard( $fechaFinalCotizaciones, $fechaInicialCotizaciones);
          }else{
            $fechaActual = new DateTime();
              
            // Obtener la fecha de inicio de los últimos 30 días
            $inicioMes = clone $fechaActual;
            $inicioMes->modify('-30 days');
            $inicioMes = $inicioMes->format('Y-m-d');
              
            // Obtener la fecha de fin (la fecha actual)
            $fechaActual->modify('+1 day');
            $fechaActual = $fechaActual->format('Y-m-d');
              
            $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesAssistCard($fechaActual, $inicioMes);
          }

          

          foreach ($respuesta as $key => $value) {
            //   <td class="text-center" style="font-size: 14px">' . date('Y/m/d', strtotime($value['fch_nacimiento'])) . '</td>
            echo '<tr>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['id_cotizacion'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fecha_cot'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['nom_prospecto'] . '</td>
                    <td class="text-center" style="display: none">' . $value['fch_nacimiento'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['lugar_origen'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['lugar_destino'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['numero_pasajeros'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fch_salida'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fch_regreso'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['modalidad_cot'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['usu_nombre'] . ' ' . $value['usu_apellido'] . '</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-primary btnEditarCotizacion" idCotizacion="' . $value["id_cotizacion"] . '">Seleccionar</button>';
        
            if ($_SESSION["rol"] == 1) {
                echo '<button class="btn btn-danger btnEliminarCotizacion" style="display: none !important;" idCotizacion="' . $value["id_cotizacion"] . '"><i class="fa fa-times"></i></button>';
            }
        
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
<script src="vistas\modulos\AssistCardCot\js\adminCotizacionesAssistCard.js?v=<?php echo (rand()); ?>" defer></script>
<!-- use version 0.20.3 -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>
