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

      Mi Perfil

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Perfil</li>

    </ol>

  </section>
  <section class="container-fluid">
    <div class="box">
      <div class="box-header with-border ">
        <div style="display: flex; flex-direction: row">
          <div style="display:flex; flex-direction:row; justify-content: center; align-items: center; gap: 50px;">
            <div style="display: flex; flex-direction:column; align-items: center; justify-content: center; max-width: 150px; margin-top:30px">
              <p style="font-weight:bold; vertical-align:middle; margin-bottom: 10px;">Logo Perfil</p>
              <?php
              echo '<img class="profile-pic previsualizarEditar" src="' . $_SESSION['foto'] . '" width="' . (strpos($_SESSION['foto'], "anonymous.png") !== false ? '100%' : '50%') . '" style="margin-bottom: 25px; border-radius: 50%;">';
              ?>

              <?php
              if ($_SESSION["permisos"]["Modificarlogodepdfdecotizaciondelaagencia"] == "x") {
                echo '<label class="btn btn-primary"  disabled>
                  <input type="file" name="ImgInter" id="imgUser" style="display:none;"  disabled/>
                  Subir archivo
                </label>';
              }
              ?>
            </div>

            <!-- <input type="text" style="display: none;" id="fotoActual"> -->
            <div style="display: flex; flex-direction:column; align-items: center; justify-content: center;  max-width: 150px; margin-top:30px">
              <p style="font-weight:bold; vertical-align:middle; margin-bottom: 10px;"> Logo PDF</p>
              <?php
              echo '<img class="profile-pic previsualizarEditar" src="' . $_SESSION['foto'] . '" width="' . (strpos($_SESSION['foto'], "anonymous.png") !== false ? '100%' : '50%') . '" style="margin-bottom: 25px; border-radius: 50%;">';
              ?>
              <?php
              if ($_SESSION["permisos"]["Modificarlogodepdfdecotizaciondelaagencia"] == "x") {
                echo '<label class="btn btn-primary">
                  <input type="file" name="ImgInter" id="imgPdf" style="display:none;" />
                  Subir archivo
                </label>';
              }
              ?>
            </div>
            <!-- <input type="text" style="display: none;" id="fotoActual"> -->
          </div>

          <div class="col-md-8" style="margin-top: 50px; margin-left: 50px">
            <div clas="row" style="margin-bottom: 30px;">
              <b style="font-size: 40px">Perfil de usuario</b>
            </div>
            <div class="row" style="margin-bottom: 22px;">
              <div class="col-md-3">
                <label for="">Tipo de documento:</label>
              </div>
              <div class="col-md-3">
                <div style="width: 164px">
                  <select name="tip_doc" id="tip_doc" style="width: 164px" >
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for="">Correo Electrónico:</label>
              </div>
              <div class="col-md-3">
                <input type="email" name="email" id="email_perfil">
              </div>
            </div>
            <div class="row" style="margin-bottom: 22px;">
              <div class="col-md-3">
                <label for="">No. Identificación:</label>
              </div>
              <div class="col-md-3">
                <input type="number" id="documento_perfil">
              </div>
              <div class="col-md-3">
                <label for="">Dirección:</label>
              </div>
              <div class="col-md-3">
                <input type="text" name="direccion" id="direccion_perfil">
              </div>
            </div>
            <div class="row" style="margin-bottom: 22px;">
              <div class="col-md-3">
                <label for="">Nombre:</label>
              </div>
              <div class="col-md-3">
                <input type="text" id="nombre_perfil">
              </div>
              <div class="col-md-3">
                <label for="">Ciudad:</label>
              </div>
              <div class="col-md-3">
                <input type="text" name="ciudad" id="ciudad_perfil">
              </div>
            </div>
            <div class="row" style="margin-bottom:22px;">
              <div class="col-md-3">
                <label for="">Apellido:</label>
              </div>
              <div class="col-md-3">
                <input type="text" id="apellido_perfil">
              </div>
              <div class="col-md-3">
                <label for="">Celular:</label>
              </div>
              <div class="col-md-3">
                <input type="text" id="telefono_perfil">
              </div>
            </div>


            <div class="row" style="margin-bottom: 22px;">
              <div class="" style="display: flex; flex-direction: row; gap: 5px; justify-content: center; align-items: center;">
                <!-- <button class="btn btn-primary" onclick="activarCamposEditables()" style="color: black; margin-bottom:30px"><strong>Editar</strong></button> -->
                <button class="btn btn-primary" style="color: white ; margin-bottom:30px" disabled><strong>Actualizar</strong></button>
                <button class="btn btn-primary" onclick="editarPerfil()" style="color: white ; margin-bottom:30px" disabled><strong>Editar Perfil</strong></button>
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