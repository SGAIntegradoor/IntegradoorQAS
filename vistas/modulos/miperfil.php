<?php

// if ($_SESSION["permisos"]["PerfilAgencia"] != "x") {

//   echo '<script>

//     window.location = "inicio";

//   </script>';

//   return;
// }

error_reporting(E_ALL);
ini_set('display_errors', 0);


?>
<script>
  console.log(permisos)
</script>


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

    <h1>

      Perfil de usuario

    </h1>

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
                echo '<img class="profile-pic previsualizarEditar" id="previewImg" src="' . $_SESSION['foto'] . '" width="100" style="border-radius: 50%; min-width: 100px; width: 100px; height: 100px">';
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
                    <?php echo $_SESSION["permisos"]["permisos_pdf"] === "x" ? '<p id="validate" style="font-weight: bold; color: Green"> [ Opción Habilitada ]</p>' :  '<p id="validate" style="font-weight: bold; color: red"> [ Opción No Habilitada ]</p>' ?>
                    <p></p>
                  </div>
                </div>
              </div>
              <div class="containerImg" style="display: flex; flex-direction: row; align-items: flex-end; gap: 20px;">
                <?php
                echo '<img class="profile-pic previsualizarEditar2" id="previewImgPDF" src="' . ($_SESSION['imgPDF'] == "" || empty($_SESSION['imgPDF']) ? 'vistas\img\usuarios\Tu Logo Aquí.png' : $_SESSION['imgPDF']) . '" width="100" style="border-radius: 50%; min-width: 100px; width: 100px; height: 100px">';
                ?>
                <?php 
                
                if($_SESSION["permisos"]["permisos_pdf"] == "x"){
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
        <div class="col-md-12" style="margin-top: 50px; padding-right: 60px; padding-left: 40px;">
          <div clas="row" style="margin-bottom: 30px;">
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
              <div class="" style="margin-bottom: 15px; display:flex; flex-direction: row; gap: 40px; align-content:flex-start">
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="tipoDocumento"><b>Tipo de documento</b></label>
                  <select disabled type="text" name="tipodocumento_perfil" id="tipodocumento_perfil"> </select>
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="documento"><b>Documento</b></label>
                  <input disabled type="text" name="documento" id="documento_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="nombres"><b>Nombres</b></label>
                  <input disabled type="text" name="nombres" id="nombres_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="apellidos"><b>Apellidos</b></label>
                  <input disabled type="text" name="apellidos" id="apellidos_perfil">
                </div>
              </div>
              <div class="" style="margin-bottom: 15px; display:flex; flex-direction: row; gap: 40px; align-content:flex-start;">
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="fechaNacimiento"><b>Fecha de nacimiento</b></label>
                  <input disabled type="text" name="fechaNacimiento_perfil" id="fechaNacimiento_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="genero"><b>Genero</b></label>
                  <input disabled type="text" name="genero" id="genero_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="celular"><b>Celular</b></label>
                  <input disabled type="text" name="telefono_perfil" id="telefono_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="correoElectronico"><b>Correo Electronico</b></label>
                  <input disabled type="text" name="email_perfil" id="email_perfil">
                </div>
              </div>
              <div class="" style="display:flex; flex-direction: row; gap: 40px; align-content:flex-start">
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="direccion"><b>Dirección</b></label>
                  <input disabled type="text" name="direccion_perfil" id="direccion_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="departamento"><b>Departamento</b></label>
                  <input disabled type="text" name="departamento" id="departamento_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="ciudad"><b>Ciudad</b></label>
                  <input disabled type="text" name="ciudad" id="ciudad_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="intermediario"><b>Intermediario</b></label>
                  <input disabled type="text" name="intermediario" id="intermediario">
                </div>
              </div>
              <div class="" style="display:flex; flex-direction: row; gap: 40px; align-content:flex-start; margin-top: 15px">
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                  <label for="analista"><b>Analista</b></label>
                  <input disabled type="text" name="analista_perfil" id="analista_perfil">
                </div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;"></div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;"></div>
                <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;"></div>
              </div>
            </div>
          </div>
          <div class="row" style="margin-bottom: 22px;">
            <div class="" style="display: flex; flex-direction: row; gap: 5px; justify-content: center; align-items: center;">
              <!-- <button class="btn btn-primary" onclick="activarCamposEditables()" style="color: black; margin-bottom:40px"><strong>Editar</strong></button> -->
              <!-- <button class="btn btn-primary" style="color: white ; margin-bottom:30px" disabled><strong>Actualizar</strong></button>
                <button class="btn btn-primary" onclick="editarPerfil()" style="color: white ; margin-bottom:30px" disabled><strong>Editar Perfil</strong></button> -->
            </div>
          </div>
        </div>
      </div>
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