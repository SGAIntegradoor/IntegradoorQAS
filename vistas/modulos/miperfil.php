<?php

if ($_SESSION["permisos"]["PerfilAgencia"] != "x") {

  echo '<script>

    window.location = "inicio";

  </script>';

  return;
}

?>

<style>
  input[type="checkbox"] {
    content: "";
    width: 26px;
    height: 26px;
    border: 2px solid #ccc;
    background: #ddd;
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
    #boxes-wrapper input, select {
      height: 40px;
      border: 2px solid #DBDBDB;
      padding: 8px;
    }

    #boxes-wrapper input:disabled, select:disabled {
      background-color: #E6E6E6;
    }

    #btnGuardar {
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

          <div style="padding-left: 60px; margin-top: 40px; display:flex; flex-direction: row; gap: 50px;">
            <div style="display: flex; flex-direction:column; ">
              <div>
                <p>Imagen de perfil de usuario</p>
              </div>
              <div style="display: flex; flex-direction:row; align-items: flex-end; gap: 20px;">
                <!-- <img src="vistas/img/views/user.png" alt="" width="100"> -->
                <?php
                echo '<img class="profile-pic previsualizarEditar" src="' . $_SESSION['foto'] . '" width="' . (strpos($_SESSION['foto'], "user.png") !== false ? '150' : '100') . '" style="border-radius: 50%;">';
                ?>
                <label class="btn btn-primary">
                  <input type="file" name="imgUser" id="imgUser" style="display:none;" />
                  Subir archivo
                </label>
                <p style="color: gray; margin-bottom: 0px; padding-bottom: 0px; font-size: 17px;">Max. 2MB</p>
              </div>
            </div>
            <div style="display: flex; flex-direction:column">
              <div>
                <p>Logo asesor o agencia (Si eres un asesor productivo y tienes autorización, sube tu logo para el
                  PDF comparativo)
                </p>
              </div>
              <div style="display: flex; flex-direction:row; align-items: flex-end; gap: 20px">
                <!-- <img src="vistas/img/views/user.png" alt="" width="100"> -->
                <?php
                echo '<img class="profile-pic previsualizarEditar" src="' . $_SESSION['foto'] . '" width="' . (strpos($_SESSION['foto'], "user.png") !== false ? '150' : '100') . '" style="border-radius: 50%;">';
                ?>
                
                <label class="btn btn-primary">
                  <input type="file" name="ImgInter" id="imgUser" style="display:none;" />
                  Subir archivo
                </label>
                <p style="color: gray; margin-bottom: 0px; padding-bottom: 0px; font-size: 17px;">Max. 2MB</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12" style="margin-top: 50px; padding-right: 60px; padding-left: 50px;">
          <div clas="row" style="margin-bottom: 30px;">
            <u><b style="font-size: 16px;">Información del usuario</b></u>
          </div>
          <div id="boxes-wrapper" style="margin-left: 10px">
            <div class="" style="margin-bottom: 15px; display:flex; flex-direction: row; gap: 40px; align-content:flex-start">
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="tipoDocumento"><b>Tipo de documento</b></label>
                <select disabled type="text" name="tipoDocumento" id="tipoDocumento"> </select>
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="documento"><b>Documento</b></label>
                <input disabled type="text" name="documento" id="documento">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="nombres"><b>Nombres</b></label>
                <input disabled type="text" name="nombres" id="nombres">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="apellidos"><b>Apellidos</b></label>
                <input disabled type="text" name="apellidos" id="apellidos">
              </div>
            </div>
            <div class="" style="margin-bottom: 15px; display:flex; flex-direction: row; gap: 40px; align-content:flex-start">
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="fechaNacimiento"><b>Fecha de nacimiento</b></label>
                <input disabled type="text" name="fechaNacimiento" id="fechaNacimiento">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="genero"><b>Genero</b></label>
                <input disabled type="text" name="genero" id="genero">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="celular"><b>Celular</b></label>
                <input disabled type="text" name="celular" id="celular">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="correoElectronico"><b>Correo Electronico</b></label>
                <input disabled type="text" name="correoElectronico" id="correoElectronico">
              </div>
            </div>
            <div class="" style="display:flex; flex-direction: row; gap: 40px; align-content:flex-start">
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="direccion"><b>Dirección</b></label>
                <input disabled type="text" name="direccion" id="direccion">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="departamento"><b>Departamento</b></label>
                <input disabled type="text" name="departamento" id="departamento">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <label for="ciudad"><b>Ciudad</b></label>
                <input disabled type="text" name="ciudad" id="ciudad">
              </div>
              <div class="col-md-3" style="display:flex; flex-direction:column; padding-left: 0px !important;">
                <div style="margin-top: 25px"></div>
                <button id="btnGuardar">Guardar</button>
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