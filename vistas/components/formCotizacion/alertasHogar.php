<style>
    .tooltip-inner {
        max-width: 400px !important;
        /* Aumenta el ancho máximo */
        width: 350px !important;
        /* Fija el ancho */
        text-align: justify;
        /* Justifica el texto */
        white-space: normal !important;
        /* Permite saltos de línea */
    }
</style>

<div id="resumenCotizaciones" style="display: none;">
    <div class="container-fluid">
        <div class="">
            <div class="row row-aseg">
                <div class="col-xs-12 col-sm-6 col-md-6" style="padding-left: 10px;">
                    <!-- titulo del form para tipo de persona -->
                    <label id="lblResumenCotizaciones">Resumen de Cotizaciones</label>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="containerResumenCotizaciones">
        <div class="card-ofertas table-alerts" style="padding: 15px">
            <div class="table-responsive" style="margin-top: 15px;">
                <table class="table table-bordered table-padding" id="tablaResCot">
                    <tr style="color: #88d600; font-size: 15px;">
                        <th style="text-align: center;">Aseguradora</th>
                        <th style="text-align: center;">Cotizo?</th>
                        <th style="text-align: center;">Productos cotizados</th>
                        <th style="text-align: center;">Observaciones</th>
                    </tr>
                    <!-- <tr style="vertical-align: center; text-align: center; font-size: 13px;">
                        <td style="font-weight: bold; font-size: 15px;">Allianz</td>
                        <td>
                            <i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px; font-weight: bold; font-size: 15px;"></i>
                        </td>
                        <td style="font-size: 15px;">
                            4
                        </td>
                        <td style="font-size: 15px;">
                            Observacion
                        </td>
                    </tr> -->

                </table>
            </div>
        </div>
    </div>
    <div class="notas-resumen-cot">
        <p>
            <b>Nota:</b> Las siguientes son propuestas de tarifas las cuales estarán sujetas a revisión por parte de las líneas de Hogar de las aseguradoras. En algunos casos se solicitará información, documentos y fotos adicionales, se podrán realizar inspecciones y análisis para suscribir el riesgo.
        </p>
    </div>
</div>
<?php
require_once "vistas/components/formCotizacion/parrillaHogar.php";
?>