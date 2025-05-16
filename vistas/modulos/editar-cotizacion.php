<?php

require_once "config/retrieveQuotation.php";
// if ($_SESSION["rol"] != 1 && $_SESSION["rol"] != 2) {

//   echo '<script>

//     window.location = "inicio";

//   </script>';

//   return;
//}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('display_errors', 1);

$idIntermediario = $_SESSION['permisos']['id_Intermediario'];
$idCotizacion = $_GET['idCotizacion'];

$response = retrieveQuotation($idCotizacion);

// echo '<script>
//   console.log(' . json_encode($response) . '); </script>';

?>

<head>
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
</head>
<style>
  .divCards {
    display: flex;
    align-content: center;
    align-items: center;
    justify-content: center;
    padding: 0px 50px 50px 50px;
  }

  .botones-agregar-manual {
    margin-top: 20px;
  }

  .cotizacionAdded {
    height: 150px !important;
    /* Alto deseado */
  }

  .checkbox-adjust {
    margin-left: -28px;
  }

  .table-padding {
    padding: 25px;
    /* Puedes ajustar el valor según tus preferencias */
  }

  /* Agregar relleno general al contenedor padre */
  .card-ofertas {
    padding: 20px;
    /* Puedes ajustar el valor según tus preferencias */
  }

  .thTable {
    text-align: center;
    /* Puedes ajustar el valor según tus preferencias */
  }

  div:where(.swal2-container) button:where(.swal2-styled).swal2-cancel {
    font-size: 14px !important;
  }

  div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
    font-size: 14px !important;
  }

  .custom-swal-popup-warning {
    width: 300px !important;
    /* Ajusta el ancho según sea necesario */
    height: 250px !important;
    /* Ajusta el alto según sea necesario */
  }

  .custom-swal-popup-success {
    width: 300px !important;
    /* Ajusta el ancho según sea necesario */
    height: 250px !important;
    /* Ajusta el alto según sea necesario */
  }

  /* Elimina completamente la barra de título del modal */
  .custom-dialog .ui-dialog-titlebar {
    display: none;
  }

  /* Personaliza el cuadro del modal */
  .custom-dialog {
    background-color: white;
    /* Fondo blanco */
    border: 1px solid #ccc;
    /* Borde gris claro */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    /* Sombra suave */
    padding: 20px;
    /* Espaciado interno */
    border-radius: 8px;
    /* Bordes redondeados opcionales */
  }

  /* Botones del modal en la parte inferior */
  .custom-dialog .ui-dialog-buttonpane {
    /* Centra los botones */
    background: none;
    /* Sin color de fondo en el contenedor de botones */
    border-top: none;
    /* Sin borde superior */
  }

  .custom-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
    float: none !important;
    text-align: center !important;
  }

  .custom-dialog .ui-dialog-buttonset button {
    margin: 5px;
    padding: 10px 20px;
    background-color: #007BFF;
    /* Azul para botón aceptar */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
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



  .custom-dialog .ui-dialog-buttonset button:nth-child(2):hover {
    background-color: #5a6268;
    /* Gris más oscuro al pasar el ratón */
  }

  .no-resize {
    /* resize: vertical; Permite cambiar solo la altura, no el ancho */
    /* Si deseas deshabilitar completamente el redimensionamiento, usa: */
    resize: none;
  }

  .full-width-textarea {
    width: 100%;
    /* Ocupa el 100% del ancho disponible */
    box-sizing: border-box;
    /* Incluye el padding y el borde en el cálculo del ancho */
  }

  /* Ajusta estos estilos según tus necesidades */


  /* Estilo para pantallas más pequeñas (menos de 495px) */
  @media (max-width: 495px) {
    .table-responsive {
      overflow-x: auto;
    }
  }
</style>

<div class="content-wrapper">

  <section class="content-header">

    <?php


    ?>
    <input type="hidden" id="idofertaguardarmanual" value="<?php echo  $idCotizacion; ?>">
    <h1>

      Cotización # <?php echo $idCotizacion ?>

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Cotización</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-body">

        <div id="formularioResumen">

          <!-- FORMULARIO RESUMEN ASEGURADO -->
          <div method="Post" id="formResumAseg">
            <div id="resumenAsegurado">
              <div class="col-lg-12" id="headerAsegurado">
                <div class="row row-aseg">
                  <div class="col-xs-12 col-sm-6 col-md-3">
                    <label for="">DATOS DEL ASEGURADO</label>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3">
                    <div id="masAsegurado">
                      <p id="masA" onclick="masAseg();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                    </div>
                    <div id="menosAsegurado">
                      <p id="menosA" onclick="menosAseg();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                    </div>
                  </div>
                </div>
              </div>

              <div id="DatosAsegurado">
                <div class="col-lg-12 form-resumAseg">
                  <div class="row">

                    <div class="col-xs-12 col-sm-6 col-md-3" id="contenSuperiorPlaca">
                      <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="conocesPlaca">
                          <label>Conoces la Placa?</label>
                          <div class="conten-conocesPlaca">
                            <label for="Si">Si</label>
                            <input type="radio" name="conocesPlaca" id="txtConocesLaPlacaSi" value="Si" checked disabled>&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="No">No</label>
                            <input type="radio" name="conocesPlaca" id="txtConocesLaPlacaNo" value="No" required disabled>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenPlaca">
                          <label for="placaVeh">Placa</label>
                          <input type="text" minlength="6" maxlength="6" class="form-control" id="placaVeh" required placeholder="Placa" disabled>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="contenCeroKM">
                          <label>Vehiculo 0 KM?</label>
                          <div class="conten-ceroKM">
                            <label for="Si">Si</label>
                            <input type="radio" name="ceroKM" id="txtEsCeroKmSi" value="Si" required disabled>&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="No">No</label>
                            <input type="radio" name="ceroKM" id="txtEsCeroKmNo" value="No" checked disabled>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="tipoDocumento">
                      <input type="hidden" class="form-control" id="intermediario" value="<?php echo $_SESSION["intermediario"]; ?>">
                      <!-- <input type="hidden" class="form-control" id="cotRestanv" value="<? //?php echo $_SESSION["cotRestantes"]; 
                                                                                            ?>"> -->
                      <label for="tipoDocumentoID">Tipo de Documento</label>
                      <select class="form-control" id="tipoDocumentoID" required disabled>
                        <option value="" disabled selected>Selecciona el tipo de documento</option>
                        <option value="1">Cedula de ciudadania</option>
                        <option value="2">NIT</option>
                        <option value="3">Cédula de extranjería</option>
                        <option value="4">Tarjeta de identidad</option>
                        <option value="5">Pasaporte</option>
                        <option value="6">Carné diplomático</option>
                        <option value="7">Sociedad extranjera sin NIT en Colombia</option>
                        <option value="8">Fideicomiso</option>
                        <option value="9">Registro civil de nacimiento</option>
                      </select>
                      <div id="alertaTipoDocumento" class="alert alert-danger mt-2" style="display: none;">
                        Debes seleccionar un tipo de documento.
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="numDocumento">
                      <label for="numDocumentoID">No. Documento</label>
                      <input type="text" maxlength="10" class="form-control" id="numDocumentoID" required placeholder="Número de Documento" required disabled>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="nombreCompleto">
                      <label for="txtNombres">Nombre Completo</label>
                      <div id="divNombre" class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 nomAseg">
                          <input type="text" class="form-control" name="nombres" id="txtNombres" placeholder="Nombres" disabled>
                        </div>
                        <div id="divApellidos" class="col-xs-12 col-sm-6 col-md-6 form-group apeAseg">
                          <input type="text" class="form-control" name="apellidos" id="txtApellidos" placeholder="Apellidos" disabled>
                        </div>
                      </div>
                      <div id="digitoVerificacion" class="row" style="display: none">
                        <div id="divDigitoVerif" class="col-xs-12 col-sm-6 col-md-12 nomAseg">
                          <input type="text" class="form-control" id="txtDigitoVerif" placeholder="Dígito de Verificación" max="1" maxlength="1" disabled>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div id="divRazonSocial" style="display: none">
                      <div id="razonSocial" class="col-xs-12 col-sm-6 col-md-3 form-group nomAseg">
                        <label name="lblRazonSocial">Razón Social</label>
                        <input type="text" class="form-control" id="txtRazonSocial" placeholder="Razón Social" required disabled>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="fechaNacimiento">
                      <label name="lblFechaNacimiento">Fecha de Nacimiento</label>
                      <div id="fechaCompleta" class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 conten-dia">
                          <select class="form-control fecha-nacimiento" name="dianacimiento" id="dianacimiento" required disabled>
                            <option value="">Dia</option>
                            <?php
                            for ($i = 1; $i <= 31; $i++) {
                              if (strlen($i) == 1) { ?>
                                <option value="<?php echo "0" . $i ?>"><?php echo "0" . $i ?></option><?php
                                                                                                    } else { ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option><?php
                                                                                                    }
                                                                                                  }
                                                                                          ?>
                          </select>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 conten-mes">
                          <select class="form-control fecha-nacimiento" name="mesnacimiento" id="mesnacimiento" required disabled>
                            <option value="" selected>Mes</option>
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                          </select>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 conten-anio">
                          <select class="form-control fecha-nacimiento" name="anionacimiento" id="anionacimiento" required disabled>
                            <option value="">Año</option>
                            <?php
                            for ($j = 1920; $j <= 2025; $j++) {
                            ?>
                              <option value="<?php echo $j ?>"><?php echo $j ?></option><?php
                                                                                      }
                                                                                        ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divGenero">
                      <label for="genero">Genero</label>
                      <select class="form-control" id="genero" required disabled>
                        <option value="" selected>Género</option>
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                      </select>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divEstadoCivil">
                      <label for="estadoCivil">Estado Civil</label>
                      <select class="form-control" id="estadoCivil" required disabled>
                        <option value="" selected>Estado Civil</option>
                        <option value="1">Soltero (a)</option>
                        <option value="2">Casado (a)</option>
                        <option value="3">Viudo (a)</option>
                        <option value="4">Divorciado (a)</option>
                        <option value="5">Unión Libre</option>
                        <option value="6">Separado (a)</option>
                      </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="correo">
                      <label for="txtCorreo">Correo</label>
                      <input type="text" class="form-control" id="txtCorreo" placeholder="Correo" disabled>
                    </div>
                  </div>
                  <div id="rowBoton" class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="celular">
                      <label for="txtCelular">Celular</label>
                      <input type="text" class="form-control" id="txtCelular" placeholder="Celular" disabled>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="contenBtnConsultarPlaca">
                      <button class="btn btn-primary btn-block" id="btnConsultarPlaca">Siguiente</button>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <div id="loaderPlaca"></div>
                    </div>
                  </div>
                </div>

                <div id="datosAseguradoNIT" style="display:none">
                  <div class="col-lg-12 form-resumAseg">
                    <label style="font-style: underline; text-decoration: underline; padding-bottom: 15px">Datos Representante Legal</label>
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="tipoDocumento">
                        <input type="hidden" class="form-control" id="intermediario" value="<?php echo $_SESSION["intermediario"]; ?>">
                        <input type="hidden" class="form-control" id="cotRestanv" value="<?php echo $_SESSION["cotRestantes"]; ?>">
                        <label for="tipoDocumentoIDRepresentante">Tipo de Documento</label>
                        <select class="form-control" id="tipoDocumentoIDRepresentante" name="tipoDocumentoIDRepresentante" required disabled>
                          <option value="" disabled selected>Selecciona el tipo de documento</option>
                          <option value="1">Cedula de ciudadania</option>
                          <option value="3">Cédula de extranjería</option>
                          <option value="4">Tarjeta de identidad</option>
                          <option value="5">Pasaporte</option>
                          <option value="6">Carné diplomático</option>
                          <option value="7">Sociedad extranjera sin NIT en Colombia</option>
                          <option value="8">Fideicomiso</option>
                          <option value="9">Registro civil de nacimiento</option>
                        </select>
                        <div id="alertaTipoDocumento" class="alert alert-danger mt-2" style="display: none;">
                          Debes seleccionar un tipo de documento.
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="numDocumento">
                        <label for="numDocumentoIDRepresentante">No. Documento</label>
                        <input type="text" maxlength="10" class="form-control" id="numDocumentoIDRepresentante" name="numDocumentoIDRepresentante" required placeholder="Número de Documento" disabled>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-6 form-group" id="nombreCompleto">
                        <label for="txtNombresRepresentante">Nombre Completo</label>
                        <div id="divNombreRepresentante" class="row">
                          <div class="col-xs-12 col-sm-6 col-md-6 form-group nomAseg">
                            <input type="text" class="form-control" name="nombres" id="txtNombresRepresentante" placeholder="Nombres" disabled>
                          </div>
                          <div id="divApellidosRepresentante" class="col-xs-12 col-sm-6 col-md-6 form-group apeAseg">
                            <input type="text" class="form-control" name="apellidos" id="txtApellidosRepresentante" placeholder="Apellidos" disabled>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="fechaNacimiento">
                        <label name="lblFechaNacimientoRepresentante">Fecha de Nacimiento</label>
                        <div id="fechaCompletaRepresentante" class="row">
                          <div class="col-xs-4 col-sm-4 col-md-4 conten-dia">
                            <select class="form-control fecha-nacimiento" name="dianacimientoRepresentante" id="dianacimientoRepresentante" disabled>
                              <option value="">Dia</option>
                              <?php
                              for ($i = 1; $i <= 31; $i++) {
                                if (strlen($i) == 1) { ?>
                                  <option value="<?php echo "0" . $i ?>"><?php echo "0" . $i ?></option><?php
                                                                                                      } else { ?>
                                  <option value="<?php echo $i ?>"><?php echo $i ?></option><?php
                                                                                                      }
                                                                                                    }
                                                                                            ?>
                            </select>
                          </div>
                          <div class="col-xs-4 col-sm-4 col-md-4 conten-mes">
                            <select class="form-control fecha-nacimiento" name="mesnacimientoRepresentante" id="mesnacimientoRepresentante" disabled>
                              <option value="" selected>Mes</option>
                              <option value="01">Enero</option>
                              <option value="02">Febrero</option>
                              <option value="03">Marzo</option>
                              <option value="04">Abril</option>
                              <option value="05">Mayo</option>
                              <option value="06">Junio</option>
                              <option value="07">Julio</option>
                              <option value="08">Agosto</option>
                              <option value="09">Septiembre</option>
                              <option value="10">Octubre</option>
                              <option value="11">Noviembre</option>
                              <option value="12">Diciembre</option>
                            </select>
                          </div>
                          <div class="col-xs-4 col-sm-4 col-md-4 conten-anio">
                            <select class="form-control fecha-nacimiento" name="anionacimientoRepresentante" id="anionacimientoRepresentante" disabled>
                              <option value="">Año</option>
                              <?php
                              for ($j = 1920; $j <= 2025; $j++) {
                              ?>
                                <option value="<?php echo $j ?>"><?php echo $j ?></option><?php
                                                                                        }
                                                                                          ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="genero">
                        <label for="generoRepresentante">Genero</label>
                        <select class="form-control" name="generoRepresentante" id="generoRepresentante" required disabled>
                          <option value="" selected>Género</option>
                          <option value="1">Masculino</option>
                          <option value="2">Femenino</option>
                        </select>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="estadoCivil">
                        <label for="estadoCivilRepresentante">Estado Civil</label>
                        <select class="form-control" id="estadoCivilRepresentante" name="" required disabled>
                          <option value="" selected>Estado Civil</option>
                          <option value="1">Soltero (a)</option>
                          <option value="2">Casado (a)</option>
                          <option value="3">Viudo (a)</option>
                          <option value="4">Divorciado (a)</option>
                          <option value="5">Unión Libre</option>
                          <option value="6">Separado (a)</option>
                        </select>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="correo">
                        <label for="txtCorreoRepresentante">Correo</label>
                        <input class="form-control" type="text" id="txtCorreoRepresentante" name="" placeholder="Correo" disabled>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="celular">
                        <label for="txtCelularRepresentante">Celular</label>
                        <input class="form-control" type="text" id="txtCelularRepresentante" name="" placeholder="Celular" disabled>
                      </div>

                      <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="contenBtnConsultarPlaca">
                        <button class="btn btn-primary btn-block" id="btnConsultarPlaca2">Siguiente</button>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <div id="loaderPlaca2"></div> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </form>

          <!-- FORMULARIO RESUMEN VEHICULO -->
          <form method="Post" id="formResumVeh">
            <div id="resumenVehiculo">
              <div class="col-lg-12" id="headerVehiculo">
                <div class="row row-veh">
                  <div class="col-xs-12 col-sm-6 col-md-3">
                    <label for="">DATOS DEL VEHICULO</label>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3">
                    <div id="masVehiculo">
                      <p id="masVeh" onclick="masVeh();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                    </div>
                    <div id="menosVehiculo">
                      <p id="menosVeh" onclick="menosVeh();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                    </div>
                  </div>
                </div>
              </div>

              <div id="DatosVehiculo">
                <div class="col-lg-12 form-resumVeh">
                  <div class="row">
                    <div class="">
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="txtPlacaVeh">Placa</label>
                        <input type="text" class="form-control" id="txtPlacaVeh" placeholder="" disabled>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="txtClaseVeh">Clase</label>
                        <input type="text" class="form-control" id="txtClaseVeh" placeholder="" disabled>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="txtMarcaVeh">Marca</label>
                        <input type="text" class="form-control classMarcaVeh" id="txtMarcaVeh" placeholder="" disabled>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="txtModeloVeh">Modelo</label>
                        <input type="text" class="form-control" id="txtModeloVeh" placeholder="" disabled>
                      </div>
                    </div>

                    <div class="">
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="txtReferenciaVeh">Línea</label>
                        <input type="text" class="form-control classReferenciaVeh" id="txtReferenciaVeh" placeholder="" disabled>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="txtFasecolda">Fasecolda</label>
                        <input type="text" class="form-control" id="txtFasecolda" placeholder="" required>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="txtValorFasecolda">Valor Asegurado</label>
                        <input type="text" class="form-control" id="txtValorFasecolda" placeholder="" required>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divTipoUso">
                        <label for="txtTipoUsoVehiculo">Tipo de Uso</label>
                        <select class="form-control" id="txtTipoUsoVehiculo" required>
                          <option value=""></option>
                          <option value="Particular" selected>Particular</option>
                          <option value="Trabajo">Trabajo</option>
                        </select>
                      </div>
                    </div>

                    <div class="">
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divTipoTransporte" style="display: none;">
                        <label for="txtTipoTransporteVehiculo">Tipo de transporte</label>
                        <select class="form-control" id="txtTipoTransporteVehiculo" required>
                          <option value=""></option>
                          <option value="Taxi">Taxi</option>
                          <option value="Bus">Bus</option>
                          <option value="MicroBus">MicroBus</option>
                          <option value="Buseta">Buseta</option>
                          <option value="ServicioEsp">Servicio Especial</option>
                          <option value="PlacaBlanca">Placa Blanca</option>
                        </select>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divTipoServicio">
                        <label for="txtTipoServicio">Tipo de Servicio</label>
                        <select class="form-control" id="txtTipoServicio" required>
                          <option value=""></option>
                          <option value="14" selected>Particular</option>
                          <option value="11">Publico Municipal</option>
                          <option value="12">Publico Intermunicipal</option>
                        </select>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-3 form-group" id="divNumeroPasajeros" style="display: none;">
                        <label for="txtNumeroPasajeros">Numero de Pasajeros</label>
                        <select name="txtNumeroPasajeros" id="txtNumeroPasajeros" class="form-control">
                          <option value="">Seleccione una opción...</option>
                          <option value="hasta19">Hasta 19 Pasajeros</option>
                          <option value="masDe19">Mas de 19 Pasajeros</option>
                        </select>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="DptoCirculacion">Departamento de Circulación</label>
                        <select class="form-control" id="DptoCirculacion" required>
                          <option value=""></option>
                          <option value="1">Amazonas</option>
                          <option value="2">Antioquia</option>
                          <option value="3">Arauca</option>
                          <option value="4">Atlántico</option>
                          <option value="5">Barranquilla</option>

                          <option value="6">Bogotá</option>
                          <option value="7">Bolívar</option>
                          <option value="8">Boyacá</option>
                          <option value="9">Caldas</option>
                          <option value="10">Caquetá</option>

                          <option value="11">Casanare</option>
                          <option value="12">Cauca</option>
                          <option value="13">Cesar</option>
                          <option value="14">Chocó</option>
                          <option value="15">Córdoba</option>

                          <option value="16">Cundinamarca</option>
                          <option value="17">Guainía</option>
                          <option value="18">La Guajira</option>
                          <option value="19">Guaviare</option>
                          <option value="20">Huila</option>

                          <option value="21">Magdalena</option>
                          <option value="22">Meta</option>
                          <option value="23">Nariño</option>
                          <option value="24">Norte de Santander</option>
                          <option value="25">Putumayo</option>

                          <option value="26">Quindío</option>
                          <option value="27">Risaralda</option>
                          <option value="28">San Andrés</option>
                          <option value="29">Santander</option>
                          <option value="30">Sucre</option>

                          <option value="31">Tolima</option>
                          <option value="32">Valle del Cauca</option>
                          <option value="33">Vaupés</option>
                          <option value="34">Vichada</option>
                        </select>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                        <label for="ciudadCirculacion">Ciudad de Circulación</label>
                        <select class="form-control" id="ciudadCirculacion" required></select>
                        <div id="listaCiudades"></div>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="row" id="contentOnerosoCheckBox">
                          <div class="col-xs-5 col-sm-5 col-md-5 form-group">
                            <label>Es Oneroso?</label>
                            <div class="conten-oneroso">
                              <label for="Si">Si</label>
                              <input type="radio" name="oneroso" id="esOnerosoSi" value="Si">&nbsp;&nbsp;&nbsp;&nbsp;
                              <label for="No">No</label>
                              <input type="radio" name="oneroso" id="esOnerosoNo" value="No" required>
                            </div>
                          </div>
                          <div class="col-xs-7 col-sm-7 col-md-7 form-group" id="contenBenefOneroso">
                            <label for="benefOneroso">Beneficiario</label>
                            <input type="text" class="form-control" id="benefOneroso">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div id="DatosVehiculoPesados">
                <div class="col-lg-12 form-resumVeh">
                  <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="txtPlacaVeh">Placa</label>
                      <input type="text" class="form-control" id="txtPlacaVehPesado" placeholder="" disabled>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="txtModeloVeh">Modelo</label>
                      <input type="text" class="form-control" id="txtModeloVehPesado" placeholder="" disabled>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="clasepesados">Clase Vehiculo</label>
                      <input type="text" class="form-control" id="clasepesados" placeholder="" disabled>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="txtMarcaVeh">Marca</label>
                      <input type="text" class="form-control classMarcaVeh" id="txtMarcaVehPesado" placeholder="" disabled>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="txtReferenciaVeh">Línea</label>
                      <input type="text" class="form-control classReferenciaVeh" id="txtReferenciaVehPesado" placeholder="" disabled>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="txtFasecolda">Fasecolda</label>
                      <input type="text" class="form-control" id="txtFasecoldaPesado" placeholder="" required>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="txtValorFasecolda">Valor Asegurado</label>
                      <input type="text" class="form-control" id="txtValorFasecoldaPesado" placeholder="" required>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 form-group" style="display: none;">
                      <label for="txtTipoUsoVehiculo">Tipo de Uso</label>
                      <select class="form-control" id="txtTipoUsoVehiculoPesado" required>
                        <option value=""></option>
                        <option value="Particular" selected>Particular</option>
                        <option value="Trabajo">Trabajo</option>
                      </select>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="txtTipoServicio">Tipo de Servicio</label>
                      <select class="form-control" id="txtTipoServicioPesado" required>
                        <option value=""></option>
                        <option value="14" selected>Particular</option>
                        <option value="11">Publico Municipal</option>
                        <option value="12">Publico Intermunicipal</option>
                      </select>
                    </div>
                  </div>

                  <div class="row">


                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="DptoCirculacion">Departamento de Circulación</label>
                      <select class="form-control" id="DptoCirculacionPesado" required>
                        <option value=""></option>
                        <option value="1">Amazonas</option>
                        <option value="2">Antioquia</option>
                        <option value="3">Arauca</option>
                        <option value="4">Atlántico</option>
                        <option value="5">Barranquilla</option>

                        <option value="6">Bogotá</option>
                        <option value="7">Bolívar</option>
                        <option value="8">Boyacá</option>
                        <option value="9">Caldas</option>
                        <option value="10">Caquetá</option>

                        <option value="11">Casanare</option>
                        <option value="12">Cauca</option>
                        <option value="13">Cesar</option>
                        <option value="14">Chocó</option>
                        <option value="15">Córdoba</option>

                        <option value="16">Cundinamarca</option>
                        <option value="17">Guainía</option>
                        <option value="18">La Guajira</option>
                        <option value="19">Guaviare</option>
                        <option value="20">Huila</option>

                        <option value="21">Magdalena</option>
                        <option value="22">Meta</option>
                        <option value="23">Nariño</option>
                        <option value="24">Norte de Santander</option>
                        <option value="25">Putumayo</option>

                        <option value="26">Quindío</option>
                        <option value="27">Risaralda</option>
                        <option value="28">San Andrés</option>
                        <option value="29">Santander</option>
                        <option value="30">Sucre</option>

                        <option value="31">Tolima</option>
                        <option value="32">Valle del Cauca</option>
                        <option value="33">Vaupés</option>
                        <option value="34">Vichada</option>
                      </select>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="ciudadCirculacion">Ciudad de Circulación</label>
                      <select class="form-control" id="ciudadCirculacionPesado" required></select>
                      <div id="listaCiudades"></div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                      <label for="mundialseguros">Tipo Vehiculo Mundial</label>
                      <select class="form-control" id="mundialseguros" required>
                        <option value=""></option>
                        <option value="1">Tractocamión</option>
                        <option value="2">Camión</option>
                        <option value="3">Semipesados</option>
                        <option value="4">Volquetas</option>
                        <option value="5">Trailers</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>

            </div>

            <div id="contenBtnCotizar">
              <div class="col-lg-12 conten-cotizar">
                <div class="row">
                  <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                    <button class="btn btn-primary btn-block" id="btnCotizar">Cotizar Ofertas</button>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                    <div id="loaderOferta"></div>
                  </div>
                </div>
              </div>
            </div>
          </form>

        </div>

        <!-- CAMPOS OCULTOS PARA OPTENER LA INFORMACION-->
        <div style="display: none;">
          <label>Intermediario</label>
          <input type="hidden" name="idIntermediario" id="idIntermediario" value="<?php echo $idIntermediario; ?>">
          <label>Mundial</label>
          <input type="hidden" name="mundial" id="mundial">
          <label>Id Asegurado</label>
          <input type="hidden" name="idCliente" id="idCliente">
          <label>Celular Asegurado</label>
          <input type="text" name="celularAseg" id="celularAseg" value="3122464876">
          <label>Email Asegurado</label>
          <input type="text" name="emailAseg" id="emailAseg" value="tecnologia@grupoasistencia.com">
          <label>Direccion Asegurado</label>
          <input type="text" name="direccionAseg" id="direccionAseg" value="CALLE 70 7T2-16">
          <label>ClaseVehiculo</label>
          <input type="text" name="CodigoClase" id="CodigoClase">
          <label>MarcaVehiculo</label>
          <input type="text" name="CodigoMarca" id="CodigoMarca">
          <label>LineaVehiculo</label>
          <input type="text" name="CodigoLinea" id="CodigoLinea">
          <label>LimiteRCESTADO</label>
          <input type="text" name="LimiteRC" id="LimiteRC" value="6">
          <label>CoberturaEstado</label>
          <input type="text" name="CoberturaEstado" id="CoberturaEstado" value="1">
          <label>ValorAccesorios</label>
          <input type="text" name="ValorAccesorios" id="ValorAccesorios" value="0">
          <label>CodigoVerificacion</label>
          <input type="text" name="CodigoVerificacion" id="CodigoVerificacion" value="0">
          <label>AniosSiniestro</label>
          <input type="text" name="AniosSiniestro" id="AniosSiniestro" value="0">
          <label>AniosAsegurados</label>
          <input type="text" name="AniosAsegurados" id="AniosAsegurados" value="0">
          <label>NivelEducativo</label>
          <input type="text" name="NivelEducativo" id="NivelEducativo" value="4">
          <label>Estrato</label>
          <input type="text" name="Estrato" id="Estrato" value="3">
        </div>

        <!-- SECCION BOTON DE RECOTIZAR Y AGREGAR OFERTA -->
        <div id="contenRecotizarYAgregar">
          <div class="col-lg-12 recotizarYAgregar">
            <div class="row">

              <div class="col-xs-12 col-sm-6 col-md-3 form-group">
              </div>
              <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                <div id="loaderRecotOferta"></div>
              </div>
              <!-- <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <button class="btn btn-primary btn-block" id="btnRecotizar">Recotizar Parrilla</button>
                  </div> -->

              <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                <button class="btn btn-success btn-block" id="btnMostrarFormCotManual">Agregar Cotización Manual</button>
              </div>
            </div>
          </div>
        </div>


        <!-- FORMULARIO MODIFICADO PARA AGREGAR OFERTAS -->
        <!--
        <form method="POST" id="agregarOferta" enctype="multipart/form-data">
          <div id="formularioCotizacionManual">
            <div class="col-lg-12 agregar-oferta">
              <div class="row row-agregar">
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <label for="">AGREGAR COTIZACIÓN</label>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div id="masAgrOferta">
                    <p id="masAgr" onclick="masAgr();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                  </div>
                  <div id="menosAgrOferta">
                    <p id="menosAgr" onclick="menosAgr();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                  </div>
                </div>
              </div>
            </div>

            <div id="DatosAgregarOferta">

              <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 form-group">
                  <label for="aseguradora">Aseguradora</label>
                  <select class="form-control" id="aseguradora" required>
                    <option value=""></option>
                    <option class="clsAseguradora" value="Seguros del Estado">Seguros del Estado</option>
                    <option class="clsAseguradora" value="Seguros Bolivar">Seguros Bolivar</option>
                    <option class="clsAseguradora" value="Axa Colpatria">Axa Colpatria</option>
                    <option class="clsAseguradora" value="HDI Seguros">HDI Seguros</option>
                    <option class="clsAseguradora" value="SBS Seguros">SBS Seguros</option>
                    <option class="clsAseguradora" value="Seguros Sura">Seguros Sura</option>
                    <option class="clsAseguradora" value="Zurich Seguros">Zurich Seguros</option>
                    <option class="clsAseguradora" value="Allianz Seguros">Allianz Seguros</option>
                    <option class="clsAseguradora" value="Liberty Seguros">Liberty Seguros</option>
                    <option class="clsAseguradora" value="Seguros Mapfre">Seguros Mapfre</option>
                    <option class="clsAseguradora" value="Equidad Seguros">Equidad Seguros</option>
                    <option class="clsAseguradora" value="Previsora">Previsora Seguros</option>
                    <option class="clsAseguradora" value="Aseguradora Solidaria">Aseguradora Solidaria</option>
                  </select>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4 form-group">
                  <label for="producto">Producto</label>
                  <select class="form-control" id="producto" required></select>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4 form-group">
                  <label for="valorRC">Valor RC</label>
                  <select class="form-control" id="valorRC" required></select>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="numCotizacion">Numero Cotización</label>
                  <input type="text" class="form-control" id="numCotizacion" required>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="valorTotal">Valor Prima Total <span style="font-size: 12px;">(con IVA)</span></label>
                  <input type="text" class="form-control" id="valorTotal" required>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="valorPerdidaTotal">Cubrimiento Perdidas Total </label>
                  <input type="text" class="form-control" id="valorPerdidaTotal" maxlength="10" required disabled>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="valorPerdidaParcial">Cubrimiento Perdidas Parcial</label>
                  <input type="text" class="form-control" id="valorPerdidaParcial" required disabled>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="conductorElegido">Conductor Elegido</label>
                  <input type="text" class="form-control" id="conductorElegido" required disabled>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="servicioGrua">Servicio de Grua</label>
                  <input type="text" class="form-control" id="servicioGrua" required disabled>
                </div>
                <?php

                if ($_SESSION['permisos']['Agregarpdfdecotizacionmanual'] == "x") {


                  echo '<div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="exampleFormControlFile1">PDF de cotización</label>
                      <input type="file" class="form-control-file" id="pdfCotizacion">
                    </div>';
                } else {
                }
                ?>

              </div>

              <div class="row botones-agregar-manual">
                <div class="col-md-offset-6 col-md-3 col-xs-12 col-sm-6 form-group">
                  <button class="btn btn-danger btn-block" id="btnCancelar">Cancelar</button>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 btnAgregar form-group">
                  <button class="btn btn-primary btn-block" id="btnAgregarCotizacion">Agregar Cotización</button>
                </div>
              </div>
            </div>
          </div>
        </form>-->

        <!-- FORMULARIO AGREGAR OFERTA MANUAL -->
        <!--
        <form method="Post" id="agregarOferta2">

        </form>-->


        <div class="col-md-12" id="agregarOferta">
          <div id="formularioCotizacionManual">
            <div class="col-lg-12 agregar-oferta">
              <div class="row row-agregar">
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <label for="">AGREGAR COTIZACIÓN -</label>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div id="masAgrOferta">
                    <p id="masAgr" onclick="masAgr();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                  </div>
                  <div id="menosAgrOferta">
                    <p id="menosAgr" onclick="menosAgr();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                  </div>
                </div>
              </div>
            </div>

            <div id="DatosAgregarOferta">

              <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 form-group">
                  <label for="aseguradora">Aseguradora</label>
                  <select class="form-control" id="aseguradora" required>
                    <option value=""></option>
                    <option class="clsAseguradora" value="Seguros del Estado">Seguros del Estado</option>
                    <option class="clsAseguradora" value="Seguros Bolivar">Seguros Bolivar</option>
                    <option class="clsAseguradora" value="Axa Colpatria">Axa Colpatria</option>
                    <option class="clsAseguradora" value="HDI Seguros">HDI Seguros</option>
                    <option class="clsAseguradora" value="SBS Seguros">SBS Seguros</option>
                    <option class="clsAseguradora" value="Seguros Sura">Seguros Sura</option>
                    <option class="clsAseguradora" value="Zurich Seguros">Zurich Seguros</option>
                    <option class="clsAseguradora" value="Allianz Seguros">Allianz Seguros</option>
                    <option class="clsAseguradora" value="HDI (Antes Liberty)">HDI (Antes Liberty)</option>
                    <option class="clsAseguradora" value="Seguros Mapfre">Seguros Mapfre</option>
                    <option class="clsAseguradora" value="Equidad Seguros">Equidad Seguros</option>
                    <option class="clsAseguradora" value="Previsora Seguros">Previsora Seguros</option>
                    <option class="clsAseguradora" value="Aseguradora Solidaria">Aseguradora Solidaria</option>
                  </select>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4 form-group">
                  <label for="producto">Producto</label>
                  <select class="form-control" id="producto" required></select>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4 form-group">
                  <label for="valorRC">Valor RC</label>
                  <select class="form-control" id="valorRC" required></select>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="numCotizacion">Numero Cotización</label>
                  <input type="text" class="form-control" id="numCotizacion" required>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="valorTotal">Valor Prima Total <span style="font-size: 12px;">(con IVA)</span></label>
                  <input type="text" class="form-control" id="valorTotal" required>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="valorPerdidaTotal">Cubrimiento Perdidas Total </label>
                  <input type="text" class="form-control" id="valorPerdidaTotal" maxlength="10" required disabled>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="valorPerdidaParcial">Cubrimiento Perdidas Parcial</label>
                  <input type="text" class="form-control" id="valorPerdidaParcial" required disabled>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="conductorElegido">Conductor Elegido</label>
                  <input type="text" class="form-control" id="conductorElegido" required disabled>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="servicioGrua">Servicio de Grua</label>
                  <input type="text" class="form-control" id="servicioGrua" required disabled>
                </div>
                <?php

                if ($_SESSION['permisos']['Agregarpdfdecotizacionmanual'] == "x") {

                  /*
                  echo '<div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="exampleFormControlFile1">PDF de cotización</label>
                      <input type="file" class="form-control-file" id="pdfCotizacion">
                    </div>';*/
                } else {
                }
                ?>

              </div>

              <div class="row botones-agregar-manual">
                <div class="col-md-offset-6 col-md-3 col-xs-12 col-sm-6 form-group">
                  <button class="btn btn-danger btn-block" id="btnCancelar">Cancelar</button>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <button class="btn btn-primary btn-block" id="btnAgregarCotizacionManual">Agregar Cotización</button>
                </div>
              </div>
            </div>
          </div>
        </div>



        <!-- PARRILLA DE COTIZACIONES -->
        <div id="contenParrilla">
          <div class="col-lg-12 form-parrilla">
            <div class="row row-parrilla">
              <div class="col-xs-12 col-sm-6 col-md-3">
                <label for="">RESUMEN DE COTIZACIONES</label>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-3">
              </div>
              <div class="col-xs-12 col-sm-6 col-md-3">
              </div>
              <div class="col-xs-12 col-sm-6 col-md-3 text-right">
                <div id="masResOferta">
                  <p id="masResumen" onclick="masRE();">Ver mas <i class="fa fa-plus-square-o"></i></p>
                </div>
                <div id="menosResOferta">
                  <p id="menosResumen" onclick="menosRE();">Ver menos <i class="fa fa-minus-square-o"></i></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Mostrar alertas -->
          <div id="resumenCotizaciones">
            <div class="col-lg-12" style="display: block;">
              <div class="card-ofertas">
                <div class="table-responsive">
                  <table class="table table-bordered table-padding" id="tablaResumenCot">
                    <thead>
                      <tr>
                        <th class="thTable" scope="col" style="color: #88d600; margin-right: 5px;">Aseguradora</th>
                        <th class="thTable" scope="col" style="color: #88d600; margin-right: 5px;">Cotizo?</th>
                        <th class="thTable" scope="col" style="color: #88d600;; margin-right: 5px;">Productos cotizados</th>
                        <th class="thTable" scope="col" style="color: #88d600;; margin-right: 5px;">Observaciones</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
                <?php if ($response['cot_clase'] == "AUTOMOVIL" || $response['cot_clase'] == "AUTOMOVILES" || $response['cot_clase'] == "UTILITARIOS DEPORTIVOS" || $response['cot_clase'] == "CAMPEROS" || $response['cot_clase'] == "PICK UPS") {

                  echo '<div style="font-size: 13px">
                          <p class="text-justify"><strong>¿Por qué algunas compañías no cotizan? R/. 0.</strong>Tiene póliza vigente con esa compañía. <strong>1.</strong> Aseguradora
                            caída, en mantenimiento o en actualización. <strong>2.</strong> RUNT, Cexper, Sistema Fasecolda caído. <strong>3.</strong> Fallas Portal
                            Integradoor. <strong>4.</strong> Vehículo fuera de políticas por marca, línea o modelo. <strong>5.</strong> Ciudad bloqueada. <strong>6.</strong> Error en
                            validación datos del asegurado. <strong>7.</strong> Valor asegurado no autorizado para cotizar vía webservice. <strong>8.</strong> Vehículo
                            salvamento. <strong>9.</strong> Motos, Pesados, Públicos no se cotizan por este módulo. <strong>10.</strong> Personas Jurídicas se cotizan
                            manualmente. <strong>11.</strong> Algunas aseguradoras no cotizan 0 km vía webservice. <strong>12.</strong> Vehículo bloqueado por cotización
                            vigente con otro asesor (ej. Solidaria). <strong>13.</strong> Mal uso del usuario registrando espacios o caracteres en placas,
                            nombres, apellidos o documentos de identidad
                          </p>
                        </div>';
                }
                ?>
              </div>
              <?php
              // var_dump($response);
              $tipoUsoVeh = ["Taxi", "Bus", "MicroBus", "Buseta", "ServicioEsp", "PlacaBlanca"];
              if (($response['cot_clase'] == "AUTOMOVIL" || $response['cot_clase'] == "AUTOMOVILES" || $response['cot_clase'] == "UTILITARIOS DEPORTIVOS" || $response['cot_clase'] == "CAMPEROS" || $response['cot_clase'] == "PICK UPS") && !in_array($response['cot_tip_uso'], $tipoUsoVeh)) {
                if ($idIntermediario != 78 && $idIntermediario != 4) {
                  echo '<div class="aviso-container col-lg-12" style="font-size: 13px">
                    <p><b>Notas Importantes: </b></p>
                        <ul>
                            <li>
                              <p><b>Seguros Mundial:</b> En pérdidas totales por daños y hurto puedes escoger 3 opciones: cobertura al 100% sin deducible, y deducible del 20% o del 40% de la perdida. 
                              Para daños parciales aplica un deducible del 10% (mínimo 1 SMMLV), y se maneja la modalidad de ARREGLO DIRECTO, la cual consiste en que el asegurado es quien se encarga de tramitar y hacer seguimiento a la reparación del vehículo en su taller de confianza y la compañía se encarga de autorizar el pago del reclamo. Inicialmente se anticipa el 70% del valor de la reparación y el 30% restante cuando el vehículo quede reparado y se presenten las respectivas facturas. Vehículos de hasta 5 años pueden usar repuestos originales; si son mayores, se usan repuestos homologados.</p>

                              <p>Incluye servicios de conductor elegido, grúa por avería y accidente y no cubre vehículo de reemplazo.</p>
                              </li>
                              <li>
                                <p><b>Seguros del Estado:</b> Los vehículos KIA de la línea PICANTO se encuentran fuera de políticas. Si se genera cotización con esta Aseguradora, omitir dicha oferta. Con respecto a la línea SPORTAGE está sólo está excluida en el Valle del Cauca. Igualmente con esta compañía, la clase de vehículo PICK UP solo se asegura como vehículo público.</p>        
                              </li>
                            <li>
                                <p>
                                    Si a tu cliente le interesa Previsora, Allianz o HDI, ten en cuenta que ciertas líneas de vehículos requieren la instalación del dispositivo Cazador al tomar su seguro. Para Previsora tiene un costo adicional a la póliza y para Allianz y HDI es totalmente gratis. Por favor confirma con tu área comercial.
                                </p>
                            </li>
                            <li>
                                <p style="font-weight: bold;">Política de valor asegurado livianos:</p>
                                <p>Menos de 200 millones, se asegura de acuerdo a políticas de cada aseguradora. Entre 200 a 250 millones, se puede asegurar con autorización del Director Comercial de Grupo Asistencia. Entre 250 a 300 millones, se puede asegurar solo bajo autorización de Gerencia de Grupo Asistencia, de acuerdo al nivel de productividad del Asesor.</p>
                                <p><b>Nota:</b> Tener en cuenta que aunque el cotizador genere ofertas, no todos los vehículos son asegurables. Se podrán hacer excepciones de valor asegurado superior cuando el asesor sea productivo, tenga más de 6 meses de antigüedad con Grupo Asistencia, no tenga altos indices de siniestralidad en su cartera, y si el cliente tiene vinculación con otros productos de la aseguradora. El valor de las primas de las cotizaciones puede variar al momento de emitir en los casos autorizados de manera excepcional.
                            </li>
                      </ul>
                    </div>';
                }
              }
              ?>
              <?php if ($response['cot_clase'] == "MOTOCICLETA" && !in_array($response['cot_tip_uso'], $tipoUsoVeh)) {
                if ($idIntermediario != 78 && $idIntermediario != 4) {
                  echo '<div class="aviso-container col-lg-12" style="font-size: 13px">
                      <p>
                        <strong>Condiciones Generales:</strong><br>
                        • Para motos con valores asegurados menores a $7 millones de pesos solo se presentan las condiciones que genere el cotizador web.<br>
                        • El equipo del Canal Asesores Freelance solo cotiza manualmente motos con valores asegurados mayores a $7 millones.<br>
                        • Para motocicletas el valor asegurado máximo es $50 millones. Motos por encima de ese valor deben ser autorizadas por el Gerente General, quien podrá hacer excepciones de valor asegurado superior cuando  el asesor sea productivo, tenga más de 6 meses de antigüedad con Grupo Asistencia, no tenga altos indices de siniestralidad en su cartera, y si el cliente tiene vinculación con otros productos de la aseguradora.<br>
                        • Motos con valor de prima total menor de $1 millón de pesos solo se permite pago de contado.<br><br>
                        <b>Condiciones de Financiación:</b><br>
                        • Se puede financiar motos con valor de prima total mayor a $1 millón de pesos.<br>
                        • Se pueden financiar hasta en 11 cuotas, motos con beneficiarios onerosos de modelos de 2022 en adelante, después de que la prima con IVA supere $1 millón de pesos.<br>
                        • Las cuotas máximas de financiación dependen del valor de prima total, de acuerdo a los siguientes rangos: entre $1 y $1,4 millones máx. 7 cuotas; mayor a 1,4 y menor a $2 millones máx 9 cuotas; y para motos con valor de prima total mayor a $2 millones se pueden financiar hasta en 11 cuotas.<br>
                      </p>
                    </div>';
                }
              }
              ?>
              <?php if (
                ($response['cot_clase'] == "CARROTANQUE" || $response['cot_clase'] == "REMOLQUE" ||
                  $response['cot_clase'] == "VOLQUETA" || $response['cot_clase'] == "FURGONETA" || $response['cot_clase'] == "GRUA"
                  || $response['cot_clase'] == "REMOLCADOR" || $response['cot_clase'] == "FURGON" || $response['cot_clase'] == "CHASIS"
                  || $response['cot_clase'] == "BUS" || $response['cot_clase'] == "CAMION") && !in_array($response['cot_tip_uso'], $tipoUsoVeh)
              ) {
                if ($idIntermediario != 78 && $idIntermediario != 4) {
                  echo '<div class="aviso-container col-lg-12" style="font-size: 13px">
                  <ul>
                    <li>
                      <p>El precio de la póliza con Seguros Mundial tiene incluido un descuento del 5%, solo aplica para pago de contado o financiación con Finesa.</p></li>
                    <li>
                      <p> HDI Seguros se reserva el derecho de aceptar marcas, clases, tipos y modelos de vehículos que no se encuentren expresamente incluidos en el listado de exclusiones. No se autoriza la vinculación de vehículos que hayan presentado siniestros en la última vigencia. No se autoriza la cotización de vehículos vinculados a pólizas individuales o colectivas vigentes en HDI. No se aseguran remolques o carrocerías sin el cabezote o chasis.
                      </p>
                    </li>                
                  </ul>                 
                      <p style="font-weight: bold;">Política de valor asegurado pesados:</p>
                      <p>El Valor asegurado máximo de acuerdo a la aseguradora es el siguiente: Seguros Mundial 700 millones. AXA Colpatria 400 millones. Liberty 310 millones. Previsora 700 millones. HDI 700 millones y 200 millones para remolques</p>
                      <p>
                        <strong>Nota:</strong> Tener en cuenta que aunque el cotizador genere ofertas, no todos los vehículos son asegurables. Se podrán hacer excepciones de valor asegurado superior cuando el asesor sea productivo, tenga más de 6 meses de antigüedad con Grupo Asistencia, no tenga altos indices de siniestralidad en su cartera, y si el cliente tiene vinculación con otros productos de la aseguradora. El valor de las primas de las cotizaciones puede variar al momento de emitir en los casos autorizados de manera excepcional.
                      </p>
                      </div>';
                }
              }
              ?>
            </div>
          </div>
        </div>

        <div class="col-lg-12 form-parrilla">
          <div class="row row-parrilla">
            <div class="col-xs-12 col-sm-6 col-md-3">
              <label for="">PARRILLA DE COTIZACIONES</label>
            </div>
          </div>
        </div>

        <div class="col-lg-12">
          <?php require "vistas/components/cotizar/catfilters.php" ?>
        </div>

        <div id="cardCotizacion">
          <div class="divCards" id="divCards"></div>
        </div>

        <div id="cardAgregarCotizacion">
        </div>

        <div id="contenCotizacionPDF">

          <div class="col-xs-12" style="width: 100%;">
            <div class="row align-items-center">
              <div class="col-xs-4">
                <label for="checkboxAsesorEditar">¿Deseas agregar tus datos como asesor en la cotización?</label>
                <input class="form-check-input" type="checkbox" id="checkboxAsesorEditar" style="margin-left: 10px;" checked>
              </div>
              <div class="col-xs-4">
                <button type="button" class="btn btn-danger" id="btnParrillaPDF">
                  <span class="fa fa-file-text"></span> Generar PDF de Cotización
                </button>
              </div>
            </div>
          </div>

          <div id="myModal">
            <div class="col-lg-12">
              <div class="row" style="margin-bottom: 32px; margin-top: 32px;">
                <div class="col-xs-12 col-sm-6 col-md-9 form-group">
                  <p style="font-weight: bold; font-size: 23px; margin-top:20px">Completa información de la oportunidad</p>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                  <label for="noCotizacion">No. cotización</label>
                  <input type="text" class="form-control" id="noCotizacion" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="txtAsesorOportunidad">Asesor</label>
                  <input type="text" class="form-control" id="txtAsesorOportunidad" placeholder="" disabled>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="txtAseguradoraOportunidad">Aseguradora</label>
                  <input type="text" class="form-control" id="txtAseguradoraOportunidad" placeholder="" disabled>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="txtPlacaOportunidad">Placa</label>
                  <input type="text" class="form-control" id="txtPlacaOportunidad" placeholder="" disabled>
                </div>
              </div>
              <div class="row" style="margin-bottom: 32px;">
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="txtAsesorOnerosoOportunidad">¿Tiene oneroso?</label>
                  <select type="text" class="form-control" id="txtAsesorOnerosoOportunidad">
                    <option value="1">Si</option>
                    <option value="2">No</option>
                  </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="txtAnalistaOportunidad">Analista/Asesor GA</label>
                  <select type="text" class="form-control" id="txtAnalistaOportunidad" required>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                  <label for="txtEstadoOportunidad">Estado</label>
                  <select type="text" class="form-control" id="txtEstadoOportunidad">
                    <option value="1">Pdte orden inspección</option>
                    <option value="2">Pdte inspección</option>
                    <option value="3">Pdt emisión</option>
                    <option value="4">Emitida</option>
                    <option value="5">Perdido</option>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <label for="txtObservacionesOportunidades">Observaciones</label>
                <textarea class="form-control form-group no-resize full-width-textarea" rows="5" id="txtObservacionesOportunidades"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

</section>

</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="vistas/js/modals.js?v=<?php echo (rand()); ?>" defer></script>
<script>

</script>