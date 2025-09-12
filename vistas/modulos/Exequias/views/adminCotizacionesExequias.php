<div class="container-fluid mainDataContainer" id="containerDataTable">
    <div class="col-lg-12">
        <div class="row row-aseg">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label id="lblDataTrip2">Administración de cotizaciones plan exequial</label>        
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
    <button type="button" class="btn btn-default pull-right" id="daterange-btnCotizacionesExequias">       <!-- id = btnCotizacionesAssistCard -->
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
        
       <table class="table table-bordered table-striped dt-responsive tablas-exequias" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="font-weight: bold; text-align: center;">N°</th>
           <th style="font-weight: bold; text-align: center;">FechaCot</th>
           <th style="font-weight: bold; text-align: center;">Nombre Titular</th>
           <th style="font-weight: bold; text-align: center;">Edad Titular</th>
           <th style="font-weight: bold; text-align: center;">Tipo Plan Exequial</th>
           <th style="font-weight: bold; text-align: center;">Asesor</th>

         </tr> 

        </thead>

        <tbody>
        <?php

          if(isset($_GET["fechaInicialCotizaciones"])){

            $fechaInicialCotizaciones = $_GET["fechaInicialCotizaciones"];
            $fechaFinalCotizaciones = $_GET["fechaFinalCotizaciones"];
            $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesExequias( $fechaFinalCotizaciones, $fechaInicialCotizaciones);
          }else{
            $fechaActual = new DateTime();
              
            // Obtener la fecha de inicio de los últimos 30 días
            $inicioMes = clone $fechaActual;
            $inicioMes->modify('-30 days');
            $inicioMes = $inicioMes->format('Y-m-d');
              
            // Obtener la fecha de fin (la fecha actual)
            $fechaActual->modify('+1 day');
            $fechaActual = $fechaActual->format('Y-m-d');
              
            $respuesta = ControladorCotizaciones::ctrRangoFechasCotizacionesExequias($fechaActual, $inicioMes);
          }

          

          foreach ($respuesta as $key => $value) {
            //   <td class="text-center" style="font-size: 14px">' . date('Y/m/d', strtotime($value['fch_nacimiento'])) . '</td>
            echo '<tr>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['id'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['fechaCoti'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['nombreTitular'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['edad'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['tipoPlan'] . '</td>
                    <td class="text-center" style="font-size: 14px; text-align: center;">' . $value['usuario'] . '</td>
                  </tr>';
        }

        ?>
               
        </tbody>

       </table>
      

      </div>
</div>

<link rel="stylesheet" href="vistas\modulos\Exequias\css\admincotizaciones.css">
<script src="vistas\modulos\Exequias\js\adminCotizacionesExequias.js?v=<?php echo (rand()); ?>" defer></script>
<script src="vistas\modulos\Exequias\js\cotizaciones_exequias.js?v=<?php echo (rand()); ?>" defer></script>
<!-- use version 0.20.3 -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js" defer></script>
