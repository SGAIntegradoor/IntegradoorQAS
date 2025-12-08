<?php
require_once "config/dbconfig.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$sqlRamos = "SELECT * from ramos";
$enlace = mysqli_connect("$DB_host", "$DB_user", "$DB_pass", "$DB_name");
$resultadoRamos = mysqli_query($enlace, $sqlRamos);
if (!$resultadoRamos) {
  die("Error en la consulta: " . mysqli_error($enlace));
}

$ramos = mysqli_fetch_all($resultadoRamos, MYSQLI_ASSOC);

if (isset($_GET["id"])) {

  echo '<script>
    var idUserURL = ' . $_GET['id'] . ';
  </script>';

  $sql = "SELECT usu_foto, usu_logo_pdf FROM usuarios WHERE id_usuario = " . $_GET['id'];

  $resultado = mysqli_query($enlace, $sql);
  $usuario = mysqli_fetch_assoc($resultado);
}


?>
<script>
  console.log(permisos)
</script>

<link rel="stylesheet" href="./vistas/modulos/Styles/New-User/styles.css">

<style>
  input[type="checkbox"] {
    content: "";
    width: 26px;
    height: 26px;
    border: 2px solid #ccc;
    background: #ddd;
  }

  .containerImg img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
  }

  .contentnav {
    display: table;
    justify-content: space-around;
    margin: auto;
  }

  .divBoton {
    display: flex;
    justify-content: end;
  }

  .nav-tabs>.classli>.classa {
    border: 1px solid lightgray;
    color: black;
  }

  .nav-tabs>.classli.active>.classa,
  .nav-tabs>.classli.active>.classa:focus,
  .nav-tabs>.classli.active>.classa:hover {
    color: #fff;
    cursor: default;
    background-color: #88d600;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
  }

  /* Sweep To Bottom */
  .botonSel {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: perspective(1px) translateZ(0);
    transform: perspective(1px) translateZ(0);
    box-shadow: 0 0 1px rgba(0, 0, 0, 0);
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }

  .botonSel:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #88d600;
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
    -webkit-transform-origin: 50% 0;
    transform-origin: 50% 0;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }

  .botonSel:hover,
  .botonSel:focus,
  .botonSel:active {
    color: white;
  }

  .botonSel:hover:before,
  .botonSel:focus:before,
  .botonSel:active:before {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
  }

  .separador {
    margin-left: 15px;
  }

  #loader-skeleton .skeleton {
    background: linear-gradient(90deg, #e0e0e0 25%, #f8f8f8 50%, #e0e0e0 75%);
    background-size: 200% 100%;
    opacity: 0.4;
    /* La opacidad no afecta a #message */
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    /* Fondo en un nivel más bajo */
    border-radius: 10px;
    /* Bordes redondeados si quieres */
    box-shadow: inset 0 0 15px 0 rgba(0, 0, 0, 0.1);
    /* Sombra difusa en los bordes */
  }

  #loader-skeleton #message {
    display: flex;
    height: 100%;
    text-align: center;
    color: #000000;
    position: relative;
    z-index: 2;
    font-size: 1.5em;
    margin: 0;
    justify-content: center;
    align-items: center;
  }

  #labelImgUser {
    background-color: #989797;
  }

  #labelPDF {
    background-color: #989797;
  }


  @keyframes shimmer {
    0% {
      background-position: -200% 0;
    }

    100% {
      background-position: 200% 0;
    }
  }
</style>

<div class="content-wrapper" style="margin-left: 50px;">
  <section class="content-header">


    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Perfil</li>

    </ol>

  </section>

  <style>
    #boxes-wrapper input,
    select {
      height: 40px;
      border: 2px solid #DBDBDB;
      padding: 8px;
    }

    #boxes-wrapper input:disabled,
    select:disabled {
      background-color: #E6E6E6;
    }

    #btnGuardar {
      background-color: #88d600;
      /* Color de fondo normal */
      border: 0;
      margin-top: 94px;
      margin-right: 80px;
      border-radius: 5px;
      width: 140px;
      color: white;
      height: 40px;
      transition: background-color 0.3s ease;
      /* Transición suave */
    }

    #btnGuardar:hover {
      background-color: #5f9800;
      /* Color de fondo cuando pasas el cursor (verde más oscuro) */
    }

    #selFotoPerfil {
      background-color: #88d600;
      /* Color de fondo normal */
      border: 0;
      margin-right: 80px;
      border-radius: 5px;
      width: 140px;
      color: white;
      height: 40px;
      transition: background-color 0.3s ease;
      /* Transición suave */
    }

    #selFotoPerfil:hover {
      background-color: #5f9800;
      /* Color de fondo cuando pasas el cursor (verde más oscuro) */
    }

    .file-upload-btn {
      background-color: #88d600;
      border: 0;
      border-radius: 5px;
      width: 140px;
      color: white;
      height: 40px;
      text-align: center;
      line-height: 40px;
      /* Para centrar el texto verticalmente */
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: inline-block;
    }

    #selFotoAgencia {
      background-color: #88d600;
      /* Color de fondo normal */
      border: 0;
      margin-right: 80px;
      border-radius: 5px;
      width: 140px;
      color: white;
      height: 40px;
      transition: background-color 0.3s ease;
      /* Transición suave */
    }

    #selFotoAgencia:hover {
      background-color: #5f9800;
      /* Color de fondo cuando pasas el cursor (verde más oscuro) */
    }
  </style>

  <section class="container-fluid">
    <div style="display: none" id="divLoaderFS">
      <div style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.4); z-index: 9999;">
        <div style="display: flex; align-items: center; justify-content: center;">
          <img src="vistas/img/loader/integradoor.gif" alt="Loading..." style="opacity: 0.7;" />
        </div>
      </div>
    </div>

    <div class="box">
      <div class="box-header with-border ">
        <div style="display: flex; flex-direction: column">

          <div id="imgsContainer" style="padding-left: 40px; margin-top: 40px; display: flex; flex-direction: row; gap: 50px;">
            <div style="display: flex; flex-direction: column;">
              <div>
                <p>Imagen de perfil de usuario</p>
              </div>
              <div class="containerImg" style="display: flex; flex-direction: row; align-items: flex-end; gap: 20px;">
                <?php
                echo '<img class="profile-pic previsualizarEditar" id="previewImg" src="' . (($usuario['usu_foto'] !== "" && $usuario['usu_foto'] != null) ? $usuario['usu_foto'] : 'vistas/img/views/user.png') . '" width="100" style="border-radius: 50%; min-width: 100px; width: 100px; height: 100px">';
                ?>
                <div style="display: flex; flex-direction: column">
                  <p id="fileNameUser" style="color: gray; margin: 0; padding: 0; font-size: 14px;">No se ha seleccionado ningún archivo</p>
                  <label class="btn btn-primary" id="labelImgUser">
                    <input type="file" name="imgUser" id="imgUser" style="display:none;" accept="image/*" />
                    Seleccionar archivo
                  </label>
                </div>
                <p style="color: gray; margin-bottom: 0px; padding-bottom: 0px; font-size: 17px;">Max. 2MB</p>
              </div>
            </div>
            <div style="display: flex; flex-direction: column;">
              <div>
                <div>
                  <div style="display: flex; gap: 10px">
                    <p>Logo asesor o agencia </p>
                    <?php echo isset($_SESSION["permisos"]["permisos_pdf"]) && $_SESSION["permisos"]["permisos_pdf"] === "x" ? '<p id="validate" style="font-weight: bold; color: Green"> [ Opción Habilitada ]</p>' :  '<p id="validate" style="font-weight: bold; color: red"> [ Opción No Habilitada ]</p>' ?>
                    <p></p>
                  </div>
                </div>
              </div>
              <div class="containerImg" style="display: flex; flex-direction: row; align-items: flex-end; gap: 20px;">
                <?php
                echo '<img class="profile-pic previsualizarEditar2" id="previewImgPDF" src="' . ($usuario['usu_logo_pdf'] == "" || empty($usuario['usu_logo_pdf']) ? 'vistas\img\usuarios\Tu Logo Aquí.png' : $usuario['usu_logo_pdf']) . '" width="100" style="border-radius: 50%; min-width: 100px; width: 100px; height: 100px">';
                ?>
                <?php

                if (isset($_SESSION["permisos"]["permisos_pdf"]) && $_SESSION["permisos"]["permisos_pdf"] === "x") {
                  echo '<div style="display: flex; flex-direction: column">
                  <p id="fileNamePDF" style="color: gray; margin: 0; padding: 0; font-size: 14px;">No se ha seleccionado ningún archivo</p>
                  <label class="btn btn-primary" id="labelPDF">
                    <input type="file" name="imgLogo" id="imgLogo" style="display:none;" accept="image/*" />
                    Seleccionar archivo
                  </label>
                </div>';
                } else {
                  echo '<div style="display: flex; flex-direction: column">
                  <p id="fileNamePDF" style="color: gray; margin: 0; padding: 0; font-size: 14px;">No se ha seleccionado ningún archivo</p>
                  <label class="btn btn-primary" id="labelPDF" disabled>
                    <input type="file" name="imgLogo" id="imgLogo" style="display:none;" accept="image/*" disabled/>
                    Seleccionar archivo
                  </label>
                </div>';
                }
                ?>

                <p style="color: gray; margin-bottom: 0px; padding-bottom: 0px; font-size: 17px;">Max. 2MB</p>
              </div>
            </div>
            <button id="btnGuardar">Guardar cambios</button>
          </div>
        </div>
        <div class="col-md-12" style="margin-top: 50px; padding-right: 60px; padding-left: 40px;" id="formUser">
          <div clas="row" style="margin-bottom: 30px; margin-left: 10px;">
            <u><b style="font-size: 16px;">Información del usuario</b></u>
          </div>
          <div id="boxes-wrapper" style="display:flex; margin-left: 10px; flex-direction: column; flex-wrap: wrap">
            <!-- Loader Overlay -->
            <div id="loader-skeleton" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: none; z-index: 2; flex-direction: column; justify-content: center; align-items: center;">
              <div class="skeleton" style="width: 100%; height: 100%; animation: shimmer 1.5s infinite;">
                <!-- Aquí podrías incluir una animación o mensaje "Cargando..." -->
              </div>
              <p id="message">Cargando Información de usuario...</p>
            </div>
            <div id="boxesInfoPerfil">
              <style>
                .form-container {
                  display: grid;
                  grid-template-columns: repeat(4, 1fr);
                  /* 4 columnas */
                  gap: 20px;
                }

                .form-group {
                  display: flex;
                  flex-direction: column;
                }

                /* Asegura que el facturador electrónico se alinee correctamente */
                .facturador-electronico {
                  grid-column: 1 / 2;
                  /* Ocupará la primera columna */
                }

                .radio-group {
                  display: flex;
                  gap: 10px;
                }

                textarea {
                  resize: none;
                  padding: 20px 15px 15px 15px;
                }

                .comentarioTA {
                  width: 100%;
                  /* Ocupará todo el ancho disponible */
                  height: 120px;
                  /* Altura fija */
                  resize: none;
                  /* Deshabilita la redimensión y elimina el icono */
                  border: 1px solid #ccc;
                  padding: 8px;
                  font-size: 14px;
                }

                .btnComentario {
                  background-color: #88d600;
                  border: 0;
                  margin-top: 15px;
                  margin-right: 80px;
                  border-radius: 5px;
                  width: 140px;
                  color: white;
                  height: 40px;
                  transition: background-color 0.3s ease;
                }

                .btnGuardar {
                  background-color: #88d600;
                  border: 0;
                  margin-top: 15px;
                  margin-right: 15px;
                  border-radius: 5px;
                  width: 90px;
                  color: white;
                  height: 40px;
                  transition: background-color 0.3s ease;
                }

                .btnSalir {
                  background-color: #c9c9c9;
                  border: 0;
                  margin-top: 15px;
                  margin-right: 80px;
                  border-radius: 5px;
                  width: 80px;
                  color: white;
                  height: 40px;
                  transition: background-color 0.3s ease;
                }

                .btnGuardar:hover {
                  background-color: rgb(118, 187, 0);
                }

                .btnComisiones {
                  background-color: #88d600;
                  border: 0;
                  margin-top: 25px;
                  border-radius: 5px;
                  width: 90%;
                  color: white;
                  height: 40px;
                  transition: background-color 0.3s ease;
                  align-items: flex-end;
                }

                .btnComisiones:hover {
                  background-color: rgb(118, 187, 0);
                }

                .btnSalir {
                  background-color: #c9c9c9;
                  border: 0;
                  margin-top: 15px;
                  margin-right: 80px;
                  border-radius: 5px;
                  width: 80px;
                  color: white;
                  height: 40px;
                  transition: background-color 0.3s ease;
                }

                .btnSalir:hover {
                  background-color: rgba(199, 199, 199, 0.7);
                }
              </style>

              <div class="form-container">
                <div class="form-group" style="display: none;" id="divUsuarioSGA">
                  <label for="usuarioSGA"><b>Tipo Usuario SGA</b></label>
                  <select name="usuarioSGA" id="usuarioSGA" disabled>
                    <option value="">Seleccione una opción...</option>
                    <option value="22">Super Administrador</option>
                    <option value="12">Analista SGA</option>
                    <option value="1">Administrador SGA</option>
                    <option value="11">Asesor SGA</option>
                    <option value="23">Contabilidad SGA</option>
                  </select>
                </div>
                <div class="form-group" style="display: none;" id="divUnidadNegocio">
                  <label for="unidadDeNegocio"><b>Unidad de negocio</b></label>
                  <select name="unidadDeNegocio" id="unidadDeNegocio">
                    <option value="">Seleccione una opción...</option>
                    <option value="19">Asesor Freelance</option>
                    <option value="asesor10">Asesor 10</option>
                    <option value="negocioDirecto">Negocio Directo</option>
                    <option value="asesorGanador">Asesor Ganador</option>
                  </select>
                </div>

                <div class="form-group" style="display: none;" id="divCanal" class="requiredfield">
                  <label for="canal"><b>Canal</b></label>
                  <select name="canal" id="canal">
                    <option value="">Seleccione una opción...</option>
                    <option value="1">Canal Freelance</option>
                    <option value="2">Canal Directo</option>
                  </select>
                </div>

                <div class="form-group" id="divTipoDePersona">
                  <label for="tipoDePersona"><b>Tipo de persona</b></label>
                  <select name="tipoDePersona" id="tipoDePersona" class="requiredfield">
                    <!-- <option value="">Seleccione una opción</option> -->
                    <option value="">Seleccione una opción...</option>
                    <option value="1">Natural</option>
                    <option value="2">Jurídica</option>
                  </select>
                </div>

                <div class="form-group" id="divTipoDocumento">
                  <label for="tipoDocumento"><b>Tipo de documento</b></label>
                  <select name="tipoDocumento" id="tipoDocumento" class="requiredfield">
                    <option value="">Seleccione una opción...</option>
                    <option value="CC">CC</option>
                    <option value="CE">CE</option>
                  </select>
                </div>

                <div class="form-group" id="divDocumento">
                  <label for="documento"><b>Documento</b></label>
                  <input type="number" name="documento" id="documento" class="requiredfield">
                </div>

                <div class="form-group legal" id="divRazonSocial">
                  <label for="razonSocial"><b>Razón Social</b></label>
                  <input type="text" name="razonSocial" id="razonSocial">
                </div>

                <div class="form-group legal" id="divPersonaContacto">
                  <label for="personaDeContacto"><b>Persona de Contacto</b></label>
                  <input type="text" name="personaDeContacto" id="personaDeContacto">
                </div>

                <!-- <div class="form-group legal" id="divMismoRepLegal">
                  <label for="representanteLegal"><b>¿Es el mismo representante legal?</b></label>
                  <div class="radio-group">
                    <div>
                      <input type="radio" name="radioRepresentante" id="siRepresentante">
                      <label for="siRepresentante">Sí</label>
                    </div>
                    <div>
                      <input type="radio" name="radioRepresentante" id="noRepresentante">
                      <label for="noRepresentante">No</label>
                    </div>
                  </div>
                </div> -->
                <div class="form-group legal">
                  <label for="representanteLegal"><b>¿Es el mismo representante legal?</b></label>
                  <div class="" style="display: flex; flex-direction: row; gap: 30px;">
                    <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                      <input type="radio" name="radioMismoRep" id="siRepresentante" style="margin: 0px">
                      <label for="siRepresentante" style="margin-bottom: 0px">Sí</label>
                    </div>
                    <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                      <input type="radio" name="radioMismoRep" id="noRepresentante" style="margin: 0px">
                      <label for="noRepresentante" style="margin-bottom: 0px">No</label>
                    </div>
                  </div>
                </div>

                <div class="form-group legal" id="divRepresentanteLegal">
                  <label for="representanteLegal"><b>Representante Legal</b></label>
                  <input type="text" name="representanteLegal" id="representanteLegal">
                </div>

                <div class="form-group legal" id="divFechaNacimientoRep">
                  <label for="fechaNacimientoRepresentante"><b>Fecha de nacimiento representante</b></label>
                  <input type="date" name="fechaNacimientoRepresentante" id="fechaNacimientoRepresentante">
                </div>


                <!-- Campos Persona Natural Inicio -->

                <div class="form-group natural" id="divNombre">
                  <label for="nombre"><b>Nombres</b></label>
                  <input type="text" name="nombre" id="nombre_perfil" class="requiredfield">
                </div>

                <div class="form-group natural" id="divApellidos">
                  <label for="apellidos"><b>Apellidos</b></label>
                  <input type="text" name="apellidos" id="apellidos_perfil" class="requiredfield">
                </div>

                <div class="form-group natural" id="divGenero">
                  <label for="genero"><b>Género</b></label>
                  <select type="text" name="genero" id="genero_perfil" class="requiredfield">
                    <option value="">Seleccione una opción</option>
                    <option value="1">Masculino</option>
                    <option value="2">Femenino</option>
                  </select>
                </div>

                <div class="form-group natural" id="divFechaNacimientoPN">
                  <label for="fechaNacimiento"><b>Fecha de nacimiento</b></label>
                  <input type="date" name="fechaNacimiento_perfil" id="fechaNacimiento_perfil" class="requiredfield">
                </div>

                <div class="form-group" id="divDepto">
                  <label for="departamento"><b>Departamento</b></label>
                  <select id="departamento" class="requiredfield">
                    <option value="">Seleccione una opción...</option>
                    <option value="91">Amazonas</option>
                    <option value="05">Antioquia</option>
                    <option value="81">Arauca</option>
                    <option value="08">Atlántico</option>

                    <option value="13">Bolívar</option>
                    <option value="15">Boyacá</option>
                    <option value="17">Caldas</option>
                    <option value="18">Caquetá</option>

                    <option value="85">Casanare</option>
                    <option value="19">Cauca</option>
                    <option value="20">Cesar</option>
                    <option value="27">Chocó</option>
                    <option value="23">Córdoba</option>

                    <option value="25">Cundinamarca</option>
                    <option value="94">Guainía</option>
                    <option value="44">La Guajira</option>
                    <option value="95">Guaviare</option>
                    <option value="41">Huila</option>

                    <option value="47">Magdalena</option>
                    <option value="50">Meta</option>
                    <option value="52">Nariño</option>
                    <option value="54">Norte de Santander</option>
                    <option value="86">Putumayo</option>

                    <option value="63">Quindío</option>
                    <option value="66">Risaralda</option>
                    <option value="88">San Andrés, Providencia y Santa Catalina</option>
                    <option value="68">Santander</option>
                    <option value="70">Sucre</option>

                    <option value="73">Tolima</option>
                    <option value="76">Valle del Cauca</option>
                    <option value="97">Vaupés</option>
                    <option value="99">Vichada</option>
                  </select>
                </div>

                <div class="form-group" id="divCiudad">
                  <label for="ciudad"><b>Ciudad</b></label>
                  <select name="ciudad" id="ciudad" class="requiredfield">
                    <option value="">Seleccione una opción...</option>
                  </select>
                </div>

                <div class="form-group" id="divDireccion">
                  <label for="direccion"><b>Dirección</b></label>
                  <input type="text" name="direccion_perfil" id="direccion_perfil" class="requiredfield">
                </div>

                <div class="form-group" id="divTelefono">
                  <label for="celular"><b>Celular</b></label>
                  <input type="text" name="telefono_perfil" id="telefono_perfil" class="requiredfield">
                </div>

                <div class="form-group" id="divEmailPN">
                  <label for="correoElectronico"><b>Correo Electrónico</b></label>
                  <input type="text" name="email_perfil" id="email_perfil" class="requiredfield">
                </div>

                <!-- Campos Persona Natural Fin -->



                <div class="form-group divAsistente">
                  <label for="tieneAsistente"><b>¿Tiene asistente?</b></label>
                  <div class="" style="display: flex; flex-direction: row; gap: 30px;">
                    <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                      <input type="radio" name="radioAsistente" id="siAsistente" style="margin: 0px">
                      <label for="siAsistente" style="margin-bottom: 0px">Sí</label>
                    </div>
                    <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                      <input type="radio" name="radioAsistente" id="noAsistente" style="margin: 0px">
                      <label for="noAsistente" style="margin-bottom: 0px">No</label>
                    </div>
                  </div>
                </div>

                <div class="form-group asistente">
                  <label for="nombreAsistente"><b>Nombre Asistente</b></label>
                  <input type="text" name="nombreAsistente" id="nombreAsistente_perfil">
                </div>

                <div class="form-group asistente">
                  <label for="nombreAsistente"><b>Celular Asistente</b></label>
                  <input type="text" name="nombreAsistente" id="nombreAsistente_perfil">
                </div>

                <div class="form-group asistente">
                  <label for="analista"><b>Correo electronico asistente</b></label>
                  <input type="text" name="analista_perfil" id="analista_perfil">
                </div>
              </div>

              <div class="" style="margin-top: 30px; margin-bottom: 30px;">
                <u><b style="font-size: 16px;">Información del canal</b></u>
              </div>

              <div class="form-container">
                <div class="form-group" id="divRolUsers">
                  <label for="rolUsers"><b>Rol</b></label>
                  <select name="rolUsers" id="rolUsers" class="requiredfield">
                    <option value="" selected>Seleccione una opción...</option>
                  </select>
                </div>

                <div class="form-group" id="divIntermediarioPerfil">
                  <label for="intermediarioPerfil"><b>Intermediario</b></label>
                  <select name="intermediarioPerfil" id="intermediarioPerfil" class="requiredfield">
                    <option value="" selected>Seleccione una opción...</option>
                  </select>
                </div>

                <div class="form-group freelance" id="divAsesorCat">
                  <label for="categoriaAsesor"><b>Categoria de asesor</b></label>
                  <select name="categoriaAsesor" id="categoriaAsesor">
                    <option value="">Seleccione una opción...</option>
                    <option value="1">Productividad Baja</option>
                    <option value="2">Productividad Media</option>
                    <option value="3">Productividad Alta</option>
                  </select>
                </div>

                <div class="form-group" id="divCargos">
                  <label for="cargos"><b>Cargo</b></label>
                  <select name="cargos" id="cargos">
                    <option value="">Seleccione una opción...</option>
                  </select>
                </div>

                <div class="form-group" id="divComisiones" style="display: none; align-items: flex-end;">
                  <?php
                  $id = isset($_GET['id']) ? $_GET['id'] : 'null';

                  $puedeVer = ($_SESSION["permisos"]["id_rol"] == 22 ||
                    $_SESSION["permisos"]["id_rol"] == 23 ||
                    $_SESSION["permisos"]["id_usuario"] == $id);

                  $disabled = $puedeVer ? "" : "disabled";
                  $css = $puedeVer ? "" : "background-color: gray; opacity: 0.8; cursor: not-allowed;";

                  // Botón
                  echo "<button class='btnComisiones' onclick='openModalComisiones(\"$id\")' style=\"$css\" $disabled>Configurar Comisiones</button>";
                  ?>

                </div>

                <!-- <div class="form-group">
                  <label for="categoriaAsesor"><b>Categoria de asesor</b></label>
                  <select name="categoriaAsesor" id="categoriaAsesor">
                    <option value="1">Asesor Freelance</option>
                    <option value="2">Asesor 10</option>
                    <option value="3">Asesor Ganador</option>
                  </select>
                </div> -->

                <div class="form-group freelance" id="divDirectorComercial">
                  <label for="directorComercial"><b>Director Comercial</b></label>
                  <select name="directorComercial" id="directorComercial">
                    <option value="">Seleccione una opción...</option>
                    <option value="1007028818">Keila Figueira López</option>
                  </select>
                </div>

                <div class="form-group freelance" id="divAnalistaAsesor">
                  <label for="analistaAsesor"><b>Analista/Asesor</b></label>
                  <select name="analistaAsesor" id="analistaAsesor">
                    <option value="">Seleccione una opción...</option>
                  </select>
                </div>

                <div class="form-group freelance" id="divOrigen">
                  <label for="origen"><b>Origen</b></label>
                  <select name="origen" id="origen">
                    <!-- Mail, Facebook, Instagram, Whatsapp, Charla, Computrabajo, Campaña, Recomendado. -->
                    <option value="">Seleccione una opción...</option>
                    <option value="1">Mail</option>
                    <option value="2">Facebook</option>
                    <option value="3">Instagram</option>
                    <option value="4">Whatsapp</option>
                    <option value="5">Charla</option>
                    <option value="6">Computrabajo</option>
                    <option value="7">Campaña</option>
                    <option value="8">Recomendado</option>
                  </select>
                </div>

                <!-- <div class="form-group">
                    <label for="representanteLegal"><b>¿Es el mismo representante legal?</b></label>
                    <div class="radio-group">
                      <div>
                        <input type="radio" name="radioRepresentante" id="siRepresentante">
                        <label for="siRepresentante">Sí</label>
                      </div>
                      <div>
                        <input type="radio" name="radioRepresentante" id="noRepresentante">
                        <label for="noRepresentante">No</label>
                      </div>
                    </div>
                  </div> -->

                <div class="form-group freelance" id="divRecomendador">
                  <label for="nombreRecomendador"><b>Nombre del recomendador</b></label>
                  <input type="text" name="nombreRecomendador" id="nombreRecomendador">
                </div>
              </div>

              <!-- <div class="form-group">
                  <label for="nombre"><b>Nombre</b></label>
                  <input type="text" name="nombre" id="nombre_perfil">
                </div>

                <div class="form-group">
                  <label for="apellidos"><b>Apellidos</b></label>
                  <input type="text" name="apellidos" id="apellidos_perfil">
                </div>

                <div class="form-group">
                  <label for="genero"><b>Género</b></label>
                  <input type="text" name="genero" id="genero_perfil">
                </div> -->
              <div class="divClavAseg">
                <div style="display: flex; flex-direction: row; margin-top: 20px; margin-bottom: 20px;gap: 70px;">
                  <p style="margin-top: 10px; font-size: 16px;"><b>¿Tiene clave con aseguradoras?</b></p>
                  <div class="" style="display: flex; flex-direction: row; gap: 15px;">
                    <div style="display: flex; flex-direction: row; gap: 15px; justify-content: center; align-items: center;">
                      <input type="radio" name="tieneClave" id="siClaves" style="margin: 0px">
                      <label for="siFacturador" style="margin-bottom: 0px">Sí</label>
                    </div>
                    <div style="display: flex; flex-direction: row; gap: 15px; justify-content: center; align-items: center;">
                      <input type="radio" name="tieneClave" id="noClaves" style="margin: 0px" checked>
                      <label for="noFacturador" style="margin-bottom: 0px">No</label>
                    </div>
                  </div>
                </div>
                <div style="display: none; flex-direction: column;" class="clavesAseguradoras">
                  <div style="display: flex; flex-direction: row; gap: 10px; justify-content: center;">
                    <div class="form-group" style="width:120px;  display: flex; flex-direction: row; gap: 35px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="allianz_aseg" style="margin: 0;"><b style="font-size: 15px">Allianz</b></label>
                      <input type="checkbox" name="allianz_aseg" id="allianz_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:150px;  display: flex; flex-direction: row; gap: 20px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="axa_aseg" style="margin: 0;"><b style="font-size: 15px">AXA Colpatria</b></label>
                      <input type="checkbox" name="axa_aseg" id="axa_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:120px;  display: flex; flex-direction: row; gap: 20px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="bolivar_aseg" style="margin: 0;"><b style="font-size: 15px">Bolivar</b></label>
                      <input type="checkbox" name="bolivar_aseg" id="bolivar_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:130px;  display: flex; flex-direction: row; gap: 20px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="equidad_aseg" style="margin: 0;"><b style="font-size: 15px">Equidad</b></label>
                      <input type="checkbox" name="equidad_aseg" id="equidad_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:130px;  display: flex; flex-direction: row; gap: 25px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="estado_aseg" style="margin: 0;"><b style="font-size: 15px">Estado</b></label>
                      <input type="checkbox" name="estado_aseg" id="estado_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:130px;  display: flex; flex-direction: row; gap: 25px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="hdi_aseg" style="margin: 0;"><b style="font-size: 15px">HDI</b></label>
                      <input type="checkbox" name="hdi_aseg" id="hdi_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:130px;  display: flex; flex-direction: row; gap: 25px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="mapfre_aseg" style="margin: 0;"><b style="font-size: 15px">Mapfre</b></label>
                      <input type="checkbox" name="mapfre_aseg" id="mapfre_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:100px;  display: flex; flex-direction: row; gap: 25px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="mundial_aseg" style="margin: 0;"><b style="font-size: 15px">Mundial</b></label>
                      <input type="checkbox" name="mundial_aseg" id="mundial_aseg" style="margin: 0;">
                    </div>
                  </div>
                  <div style="display: flex; flex-direction: row; gap: 0; margin-top: 15px; justify-content: center;">
                    <div class="form-group" style="width:130px;  display: flex; flex-direction: row; gap: 17px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="previsora_aseg" style="margin: 0;"><b style="font-size: 15px">Previsora</b></label>
                      <input type="checkbox" name="previsora_aseg" id="previsora_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:160px; display: flex; flex-direction: row; gap: 84px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="sbs_aseg" style="margin: 0;"><b style="font-size: 15px">SBS</b></label>
                      <input type="checkbox" name="sbs_aseg" id="sbs_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:130px; display: flex; flex-direction: row; gap: 37px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="sura_aseg" style="margin: 0;"><b style="font-size: 15px">Sura</b></label>
                      <input type="checkbox" name="sura_aseg" id="sura_aseg" style="margin: 0;">
                    </div>
                    <div class="form-group" style="width:140px; display: flex; flex-direction: row; gap: 32px; margin: 0; align-items: center; justify-content: flex-start;">
                      <label for="zurich_aseg" style="margin: 0;"><b style="font-size: 15px">Zurich</b></label>
                      <input type="checkbox" name="zurich_aseg" id="zurich_aseg" style="margin: 0;">
                    </div>
                    <div>
                      <label style="font-size: 15px; margin-right: 31px;">Otras</label>
                      <input type="text" name="otras_aseg" id="otras_aseg" style="width: 450px;">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="freelance" style="margin-bottom: 30px; margin-top: 30px;">
              <u><b style="font-size: 16px;">Información del financiera</b></u>
            </div>

            <div class="form-container freelance">
              <div class="form-group">
                <label for="entidadBancaria"><b>Entidad Bancaria</b></label>
                <select name="entidadBancaria" id="entidadBancaria">
                  <option value="" selected>Seleccione una opción...</option>
                </select>
              </div>

              <div class="form-group">
                <label for="tipoCuenta"><b>Tipo de cuenta</b></label>
                <select name="tipoCuenta" id="tipoCuenta">
                  <option value="" selected>Seleccione una opción...</option>
                  <option value="1">Ahorros</option>
                  <option value="2">Corriente</option>
                </select>
              </div>

              <div class="form-group">
                <label for="noCuenta"><b>Número de cuenta</b></label>
                <input type="text" name="noCuenta" id="noCuenta" />
              </div>

              <div class="form-group" id="divRegimenRenta">
                <label for="regimenRenta"><b>Regimen Renta</b></label>
                <select name="regimenRenta" id="regimenRenta">
                  <option value="">Seleccione una opción...</option>
                  <option value="1">Declarante</option>
                  <option value="2">No declarante</option>
                  <option value="3">Regimen simple</option>
                  <option value="4">Regimen especial</option>
                </select>
              </div>
              <div class="form-group">
                <label for="representanteLegal"><b>Facturador electronico</b></label>
                <div class="" style="display: flex; flex-direction: row; gap: 30px;">
                  <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                    <input type="radio" name="radioFacturador" id="siFacturado" style="margin: 0px">
                    <label for="siFacturado" style="margin-bottom: 0px">Sí</label>
                  </div>
                  <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                    <input type="radio" name="radioFacturador" id="noFacturado" style="margin: 0px">
                    <label for="noFacturado" style="margin-bottom: 0px">No</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="representanteLegal"><b>Responsable de IVA</b></label>
                <div class="" style="display: flex; flex-direction: row; gap: 30px;">
                  <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                    <input type="radio" name="radioIVA" id="siIVA" style="margin: 0px">
                    <label for="siIVA" style="margin-bottom: 0px">Sí</label>
                  </div>
                  <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; align-items: center;">
                    <input type="radio" name="radioIVA" id="noIVA" style="margin: 0px">
                    <label for="noIVA" style="margin-bottom: 0px">No</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="participacionEsp"><b>Participación Especial</b></label>
                <input type="text" name="participacionEsp" id="participacionEsp" />
              </div>
            </div>
            <section style="display: none;" id="divComentarios">
              <div clas="row" style="margin-bottom: 30px; margin-top: 30px;">
                <u><b style="font-size: 16px;">Información adicional</b></u>
              </div>
              <div style="display: flex; flex-direction: row; gap: 40px;" id="divComentarios">
                <div class="form-group" style="width: 50%;">
                  <label for="agregarComentario"><b>Agregar comentarios:</b></label>
                  <input type="text" name="agregarComentario" id="agregarComentario">
                  <button class="btnComentario">Agregar</button>
                </div>
                <div class="form-group" style="width: 50%; margin-top: 25px; resize: none;">
                  <textarea name="comentarioTA" id="comentarioTA" rows="15" cols="50" disabled></textarea>
                </div>
              </div>
            </section>

            <div class="form-container" style="margin-top: 50px;">
              <div class="form-group">
                <label for="usuarioVin"><b>Usuario:</b></label>
                <input type="text" name="usuarioVin" id="usuarioVin" disabled class="requiredfield">
              </div>

              <div class="form-group">
                <label for="fechaCreaVin"><b>Fecha de creación/vinculación:</b></label>
                <input type="date" name="fechaCreaVin" id="fechaCreaVin" class="requiredfield">
              </div>

              <div class="form-group">
                <label for="fechaActivacion"><b>Fecha activación:</b></label>
                <input type="date" name="fechaActivacion" id="fechaActivacion" disabled>
              </div>

              <div class="form-group">
                <label for="diasActivacion"><b>Dias de activación</b></label>
                <input type="text" name="diasActivacion" id="diasActivacion" disabled>
              </div>
            </div>
            <div class="form-container" style="margin-top: 20px;">
              <div class="form-group">
                <label for="limiteCots"><b>Limite cotizaciones:</b></label>
                <input type="text" name="limiteCots" id="limiteCots" class="requiredfield">
              </div>

              <div class="form-group">
                <label for="limiteUso"><b>Limite de uso:</b></label>
                <input type="date" name="limiteUso" id="limiteUso" class="requiredfield">
              </div>

              <div class="form-group">
                <label for="estadoUs"><b>Estado:</b></label>
                <select type="text" name="estadoUs" id="estadoUs" class="requiredfield">
                  <option value="" selected>Seleccione una opción...</option>
                  <option value="1">Vinculado</option>
                  <option value="2">Activado</option>
                  <option value="3">Inactivo</option>
                  <option value="4">Bloqueado</option>
                  <option value="5">Reactivado</option>
                </select>
              </div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 60px; margin-top: 60px;">
              <button class="btnSalir">Salir</button>
              <button class="btnGuardar">Guardar</button>
            </div>


          </div>



        </div>
      </div>

    </div>

    <div id="myModal2" style="display: none;">
      <div class="col-lg-12" id="realModal">
        <div style="margin-bottom: 0px; margin-top: 20px; gap: 5px;">
          <div style="display:flex; flex-direction: row; margin-bottom: 10px;margin-top: 18px; gap:40px">
            <!-- <div class="form-group">
              <label for="ramoSelect">Ramo:</label>
              <select class="" name="ramoSelect" id="ramoSelect" multiple="multiple" placeholder="" style="width: 150px;" required>
                <option value="">Seleccione...</option>
                <option value="1">Todos</option>
                <option value="2">Automoviles</option>
                <option value="3">Motos</option>
                <option value="4">Pesados</option>
                <option value="5">Hogar</option>
                <option value="6">Vida</option>
              </select>
            </div> -->
            <div class="custom-select form-group">
              <label for="ramoSelector">Ramo:</label>
              <div>
                <div class="select-box" onclick="toggleOptions()">
                  Selecciona opciones...
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 16 16" fill="none">
                    <path d="M3 5l5 5 5-5" stroke="black" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
                <div class="options-container">
                  <div class="option">
                    <input id="todosCheck" type="checkbox" value="Todos" onchange="updateSelectText(event)"> Todos
                  </div>
                  <?php

                  foreach ($ramos as $ramo) {
                    echo '
                          <div class="option">
                            <input type="checkbox" value="' . htmlspecialchars($ramo["ramo"]) . '" onchange="updateSelectText(event)"> ' . htmlspecialchars($ramo["ramo"]) . '
                          </div>
                        ';
                  }

                  ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="unidadNegocioSelect">Unidad de negocio:</label>
              <select name="unidadNegocioSelect" id="unidadNegocioSelect" placeholder="" style="width: 150px;" required>
                <option value="">Seleccione...</option>
                <option value="1">Negocio Directo</option>
                <option value="2">Asesor 10</option>
                <option value="3">Asesor Ganador</option>
                <option value="4">Asesor Freelance</option>
              </select>
            </div>
            <div class="form-group">
              <label for="tipoNegocioSelect">Tipo de negocio:</label>
              <select name="tipoNegocioSelect" id="tipoNegocioSelect" placeholder="" style="width: 150px;" required>
                <option value="">Seleccione...</option>
                <option value="1">Individual</option>
                <option value="2">Colectivo</option>
              </select>
            </div>
            <div class="form-group">
              <label for="tipoExpedicionSelect">Tipo expedición</label>
              <select name="tipoExpedicionSelect" id="tipoExpedicionSelect" placeholder="" style="width: 150px;" required>
                <option value="">Seleccione...</option>
                <option value="1">Todos</option>
                <option value="2">Nueva</option>
                <option value="3">Renovación</option>
                <option value="4">Inclusión</option>
              </select>
            </div>
            <div style="display: flex; align-items: center;">
              <input type="text" name="valorComision" id="valorComision" style="border: 0; border-bottom: 1px solid  #c9c9c9;" placeholder="Valor comisión %" required>
            </div>

          </div>
          <div style="display:flex; flex-direction: row; margin-bottom: 10px;margin-top: 100px; gap:85px; width: 100%;">
            <input type="text" name="observaciones" id="observaciones" placeholder="Observaciones" style="border: 0; border-bottom: 1px solid  #c9c9c9; width:50%" ;>
            <div class="form-group">

            </div>
            <div class="form-group">

            </div>
            <button class="guardarComision" onclick="addComision()" style="flex: 1/2;">Adicionar</button>
          </div>
          <div class="row" style="margin-bottom: 10px;margin-top: 18px;">
          </div>
          <div style="margin-bottom: 10px;margin-top: 50px; text-align: start;">
            <table border="1" style="width: 100%; margin-right: 10px;" id="comisionesTable">
              <thead>
                <tr>
                  <th>Ramo</th>
                  <th>Unidad de negocio</th>
                  <th>Tipo de negocio</th>
                  <th>Tipo expedición</th>
                  <th>Valor comisión %</th>
                  <th>Observaciones</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="comisionesTableBody">
                <!-- Aquí se agregarán las filas dinámicamente -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
</div>

</section>
</div>









<!-- script -->

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="vistas/js/perfil.js" defer></script>
<script src="vistas/js/new-user.js" defer></script>