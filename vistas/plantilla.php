<?php


// if (isset($_SESSION)) {
//   echo '<script>console.log(' . json_encode($_SESSION) . ')</script>';
// }

// session_start();
?>

<!DOCTYPE html>
<html>

<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">

  <title>Multicotizador de Seguros</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="icon" href="vistas/img/plantilla/icono-blanco.png">

  <!--=====================================
  PLUGINS DE CSS
  ======================================-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">

  <title>Multicotizador de Seguros</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="icon" href="vistas/img/plantilla/icono-blanco.png">

  <!--=====================================
  PLUGINS DE CSS
  ======================================-->

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="vistas/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="vistas/bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="vistas/dist/css/AdminLTE.css?v=<?php echo (rand()); ?>">
  <link rel="stylesheet" href="vistas/dist/css/styles_cotizador.css?v=<?php echo (rand()); ?>">

  <!-- AdminLTE Skins -->
  <link rel="stylesheet" href="vistas/dist/css/skins/_all-skins.min.css">

  <!-- Integrador Skins -->
  <link rel="stylesheet" href="vistas/dist/css/styles_integrador.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- DataTables -->
  <!-- <link rel="stylesheet" href="vistas/bower_components/datatables.net/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="vistas/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="vistas/bower_components/datatables.net-bs/css/responsive.bootstrap.min.css"> -->
  <!--  <link rel="stylesheet" href="vistas/plugins/datatables/datatables.min.css"> -->

  <!-- JQuery UI -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <link rel="stylesheet" href="libraries\JQueryUI\jquery-ui.theme.css">
  <link rel="stylesheet" href="libraries\JQueryUI\jquery-ui.css">
  <script type="text/javascript" src="libraries\JQueryUI\jquery-ui.js"></script>
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css"> -->
  <!-- <script type="text/javascript" src="libraries\JQueryUI\external\jquery\jquery.js"></script> -->
  <!-- JQuery UI -->
  <!-- <link rel="stylesheet" href="libraries/DataTables/datatables.min.css"> 
 <script src="vistas/plugins/datatables/datatables.min.js"></script>-->

  <!--<link rel="stylesheet" href="libraries/DataTables/datatables.min.css"> 
 <script src="libraries/DataTables/datatables.min.js"></script>-->
  <link rel="stylesheet" href="libraries/DataTables/datatables1.min.css">
  <script src="libraries/DataTables/datatables1.min.js"></script>
  <!--<script src="vistas/plugins/datatables/datatables.min.js"></script>-->

  <!-- <link rel="stylesheet" href="vistas/bower_components/datatables.net-bs/css/responsive.bootstrap.min.css"> -->

  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="vistas/plugins/iCheck/all.css">

  <!-- Daterange picker -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap-daterangepicker/daterangepicker.css">

  <!-- Morris chart -->
  <link rel="stylesheet" href="vistas/bower_components/morris.js/morris.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="vistas/bower_components/select2/dist/css/select2.min.css">
  <!-- Select2 Bootstrap v0.1.0-beta.10 -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap/dist/css/select2-bootstrap.min.css">

  <!-- Bootstrap Datepicker -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">


  <!--=====================================
  PLUGINS DE JAVASCRIPT
  ======================================-->

  <!-- jQuery 3 -->
  <!-- <script type="text/javascript" src="libraries\JQueryUI\external\jquery\jquery.js"></script> -->


  <!-- Bootstrap 3.3.7 -->
  <script src="vistas/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.dropdown-toggle').dropdown();
    });
  </script>



  <!-- FastClick -->
  <script src="vistas/bower_components/fastclick/lib/fastclick.js"></script>

  <!-- AdminLTE App -->
  <script src="vistas/dist/js/adminlte.min.js"></script>

  <!--DataTables-->
  <!--<script src="vistas/plugins/datatables/datatables.min.js"></script>-->
  <!-- <script src="libraries/DataTables/datatables.min.css"></script> -->
  <!-- <script src="vistas/bower_components/datatables.net-bs/js/responsive.bootstrap.min.js"></script> 
 <script src="vistas/bower_components/datatables.net-bs/js/dataTables.responsive.min.js"></script> 
 <script src="vistas/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
 <script src="vistas/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>-->
  <!--<link rel="stylesheet" href="libraries/DataTables/datatables.min.js"> -->

  <!-- SweetAlert 2 -->
  <!-- <script src="vistas/plugins/sweetalert2/sweetalert2.all.js"></script> -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- By default SweetAlert2 doesn't support IE. To enable IE 11 support, include Promise polyfill:-->
  <!-- <script src="vistas/plugins/sweetalert2/core-2.4.1.js"></script> -->

  <!-- iCheck 1.0.1 -->
  <script src="vistas/plugins/iCheck/icheck.min.js"></script>

  <!-- InputMask -->
  <script src="vistas/plugins/input-mask/jquery.inputmask.js"></script>
  <script src="vistas/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="vistas/plugins/input-mask/jquery.inputmask.extensions.js"></script>

  <!-- jQuery Number -->
  <script src="vistas/plugins/jqueryNumber/jquerynumber.min.js"></script>

  <!-- jQuery Numeric -->
  <script src="vistas/bower_components/jquery-numeric/jquery.numeric.js"></script>

  <!-- jQuery Redirect "POST" -->
  <script src="vistas/bower_components/jquery-redirect/jquery.redirect.js"></script>

  <!-- Ventana Centrada -->
  <script src="vistas/bower_components/ventana-centrada/VentanaCentrada.js"></script>

  <!-- daterangepicker http://www.daterangepicker.com/-->
  <script src="vistas/bower_components/moment/min/moment.min.js"></script>
  <script src="vistas/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Typeahead v4.0.2 "bootstrap" https://cdnjs.com/libraries/bootstrap-3-typeahead  -->
  <script src="vistas/bower_components/bootstrap-typeahead/bootstrap-typeahead.js"></script>

  <!-- Morris.js charts http://morrisjs.github.io/morris.js/-->
  <script src="vistas/bower_components/raphael/raphael.min.js"></script>
  <script src="vistas/bower_components/morris.js/morris.min.js"></script>

  <!-- ChartJS http://www.chartjs.org/-->
  <!-- <script src="vistas/bower_components/Chart.js/Chart.js"></script> -->

  <!-- Select2 -->
  <script src="vistas/bower_components/select2/dist/js/select2.min.js"></script>
  <script src="vistas/bower_components/select2/dist/js/i18n/es.js"></script>

  <!-- Bootstrap Datepicker -->
  <!-- <script src="vistas/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> -->
  <!-- <script src="vistas/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.es.min.js" charset="UTF-8"></script> -->


  <!--=====================================
CUERPO DOCUMENTO
======================================-->

<body class="hold-transition skin-blue sidebar-collapse sidebar-mini login-page">



  <?php
  if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {

    echo '<input type="hidden" id="fechaLimi" value="' . $_SESSION["fechaLimi"] . '">';
    $permisos = $_SESSION["permisos"];

    $aseguradoras = array(
      "Allianz" => array("A" => $permisos["Allianz_A"], "C" => $permisos["Allianz_C"]),
      "AXA" => array("A" => $permisos["AXA_A"], "C" => $permisos["AXA_C"]),
      "Bolivar" => array("A" => $permisos["Bolivar_A"], "C" => $permisos["Bolivar_C"]),
      "Equidad" => array("A" => $permisos["Equidad_A"], "C" => $permisos["Equidad_C"]),
      "Estado" => array("A" => $permisos["Estado_A"], "C" => $permisos["Estado_C"]),
      "HDI" => array("A" => $permisos["HDI_A"], "C" => $permisos["HDI_C"]),
      "Liberty" => array("A" => $permisos["Liberty_A"], "C" => $permisos["Liberty_C"]),
      "Mapfre" => array("A" => $permisos["Mapfre_A"], "C" => $permisos["Mapfre_C"]),
      "Previsora" => array("A" => $permisos["Previsora_A"], "C" => $permisos["Previsora_C"]),
      "SBS" => array("A" => $permisos["SBS_A"], "C" => $permisos["SBS_C"]),
      "Solidaria" => array("A" => $permisos["Solidaria_A"], "C" => $permisos["Solidaria_C"]),
      "Zurich" => array("A" => $permisos["Zurich_A"], "C" => $permisos["Zurich_C"])
    );

    $aseguradoras_motos = array(
      "Allianz" => array("A" => $permisos["Allianz_A_motos"], "C" => $permisos["Allianz_C_motos"]),
      "AXA" => array("A" => $permisos["AXA_A_motos"], "C" => $permisos["AXA_C_motos"]),
      "SBS" => array("A" => $permisos["SBS_A_motos"], "C" => $permisos["SBS_C_motos"]),
      "Liberty" => array("A" => $permisos["Liberty_A_motos"], "C" => $permisos["Liberty_C_motos"]),
      // "Bolivar" => array("A" => $permisos["Bolivar_A_motos"], "C" => $permisos["Bolivar_C_motos"]),
      // "Equidad" => array("A" => $permisos["Equidad_A_motos"], "C" => $permisos["Equidad_C_motos"]),
      // "Estado" => array("A" => $permisos["Estado_A_motos"], "C" => $permisos["Estado_C_motos"]),
      // "HDI" => array("A" => $permisos["HDI_A_motos"], "C" => $permisos["HDI_C_motos"]),
      // "Mapfre" => array("A" => $permisos["Mapfre_A_motos"], "C" => $permisos["Mapfre_C_motos"]),
      // "Previsora" => array("A" => $permisos["Previsora_A_motos"], "C" => $permisos["Previsora_C_motos"]),
      // "Solidaria" => array("A" => $permisos["Solidaria_A_motos"], "C" => $permisos["Solidaria_C_motos"]),
      // "Zurich" => array("A" => $permisos["Zurich_A_motos"], "C" => $permisos["Zurich_C_motos"])
    );

    $aseguradoras_pesados = array(
      //"Allianz" => array("A" => $permisos["Allianz_A_pesados"], "C" => $permisos["Allianz_C_pesados"]),
      "AXA" => array("A" => $permisos["AXA_A_pesados"], "C" => $permisos["AXA_C_pesados"]),
      // "Bolivar" => array("A" => $permisos["Bolivar_A_pesados"], "C" => $permisos["Bolivar_C_pesados"]),
      //"Equidad" => array("A" => $permisos["Equidad_A_pesados"], "C" => $permisos["Equidad_C_pesados"]),
      // "Estado" => array("A" => $permisos["Estado_A_pesados"], "C" => $permisos["Estado_C_pesados"]),
      //"HDI" => array("A" => $permisos["HDI_A_pesados"], "C" => $permisos["HDI_C_pesados"]),
      "Liberty" => array("A" => $permisos["Liberty_A_pesados"], "C" => $permisos["Liberty_C_pesados"]),
      //"Mapfre" => array("A" => $permisos["Mapfre_A_pesados"], "C" => $permisos["Mapfre_C_pesados"]),
      //"Previsora" => array("A" => $permisos["Previsora_A_pesados"], "C" => $permisos["Previsora_C_pesados"]),
      //"SBS" => array("A" => $permisos["SBS_A_pesados"], "C" => $permisos["SBS_C_pesados"]),
      //"Solidaria" => array("A" => $permisos["Solidaria_A_pesados"], "C" => $permisos["Solidaria_C_pesados"]),
      //"Zurich" => array("A" => $permisos["Zurich_A_pesados"], "C" => $permisos["Zurich_C_pesados"]),
      "Mundial" => array("A" => $permisos["Mundial_A_pesados"], "C" => $permisos["Mundial_C_pesados"])
    );


  ?>
    <script>
      var permisosPlantilla = '<?php echo addslashes(json_encode($permisos)); ?>';
      let permisos = JSON.parse(permisosPlantilla);
      var aseguradorasCredenciales = '<?php echo json_encode($aseguradoras); ?>';
      var aseguradorasCredencialesMotos = '<?php echo json_encode($aseguradoras_motos); ?>';
      var aseguradorasCredencialesPesados = '<?php echo json_encode($aseguradoras_pesados); ?>';
      // console.log(aseguradorasCredencialesPesados)
    </script>
    <?php


    echo '<div class="wrapper">';

    /*=============================================
    CABEZOTE
    =============================================*/

    include "modulos/cabezote.php";

    /*=============================================
    MENU
    =============================================*/

    include "modulos/menu.php";




    if ($_SESSION["permisos"]["Whatsapp"] == "x") {

      echo '<a href="https://web.whatsapp.com/send?phone=+573153539141" target="_blank" class="btn-wasap" style="float: unset; position: fixed; z-index: 999; bottom: 4%; left: 2%;">
<img src="vistas/img/logos/wasap.png" width="50" height="50" alt="" >
</a>';
    }
    ?>

  <?php
    /*=============================================
    CONTENIDO
    =============================================*/


    if (isset($_GET["ruta"])) {
      if (
        $_GET["ruta"] == "inicio" ||
        $_GET["ruta"] == "usuarios" ||
        $_GET["ruta"] == "adminCoti" ||
        $_GET["ruta"] == "politicas" ||
        $_GET["ruta"] == "planes" ||
        $_GET["ruta"] == "clientes" ||
        $_GET["ruta"] == "cotizaciones" ||
        $_GET["ruta"] == "editar-cotizacion" ||
        $_GET["ruta"] == "editar-cotizacion-autogestion" ||
        $_GET["ruta"] == "editar-cotizacionpesados" ||
        $_GET["ruta"] == "livianoMasivas" ||
        /*:::::::::::::::::::::::::::::::::::::::::::::::::::
        Nuevas rutas
        :::::::::::::::::::::::::::::::::::::::::::::::::::*/
        $_GET["ruta"] == "cotizar" ||
        $_GET["ruta"] == "pesados" ||
        $_GET["ruta"] == "motos" ||
        $_GET["ruta"] == "autogestion" ||
        $_GET["ruta"] == "salir" ||
        $_GET["ruta"] == "modificacion-productos" ||
        $_GET["ruta"] == "ayuda-ventas" ||
        $_GET["ruta"] == "perfilintermediario" ||
        $_GET["ruta"] == "intermediario" ||
        $_GET["ruta"] == "Productos" ||
        $_GET["ruta"] == "invitar" ||
        $_GET["ruta"] == "exequias" ||
        $_GET["ruta"] == "assistcard" ||
        $_GET["ruta"] == "soat" ||
        $_GET["ruta"] == "configuracion-pdf"
      ) {
        if ($_GET['ruta'] == 'modificacion-productos') {
          $_GET['ruta'] = 'ModificacionProductos/ModificacionProductosView';
        }
        if ($_GET['ruta'] == 'ayuda-ventas') {
          $_GET['ruta'] = 'AyudaVentas/AyudaVentasView';
        }
        include "modulos/" . $_GET["ruta"] . ".php";
      } else {

        include "modulos/404.php";
      }
    } else {

      include "modulos/inicio.php";
    }

    /*=============================================
    FOOTER
    =============================================*/

    include "modulos/footer.php";

    echo '</div>';
  } else {
    if (isset($_GET['codigo']) && $_GET['codigo'] != '') {
      $_SESSION["codigo"] = $_GET['codigo'];
      include "modulos/cambio-password.php";
    }
    // else if(isset($_GET["ruta"])){
    else if (isset($_GET["ruta"]) && $_GET['ruta'] == 'change') {
      include "modulos/change.php";
    } else if (isset($_GET["ruta"]) && $_GET['ruta'] == 'invitacion') {
      include "modulos/invitacion.php";
    }
    // }
    else {
      include "modulos/login.php";
    }
  }

  ?>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="vistas/js/authChange.js"></script>
  <script src="vistas/js/invitacion.js?v=<?php echo (rand()); ?>"></script>
  <!--<script src="vistas/js/pesados.js?v=<?php echo (rand()); ?>"></script>-->
  <script src="vistas/js/plantilla.js?v=<?php echo (rand()); ?>"></script>
  <script src="vistas/js/count.js?v=<?php echo (rand()); ?>"></script>
  <script src="vistas/js/clientes.js?v=<?php echo (rand()); ?>"></script>
  <script src="vistas/js/fasecolda.js?v=<?php echo (rand()); ?>"></script>
  <script src="vistas/js/cotizaciones.js?v=<?php echo (rand()); ?>"></script>
  <script src="vistas/js/validacionPermisos.js?v=<?php echo (rand()); ?>"></script>

</body>

</html>