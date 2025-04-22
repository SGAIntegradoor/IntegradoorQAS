<?php

if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
  header('Location: login');
  exit();
}

// Actualiza el tiempo de la última actividad
$_SESSION['lastActivity'] = time();

$showPopup = false;
if (isset($_SESSION['showPopup']) && $_SESSION['showPopup'] === true) {
  session_start();
  $showPopup = true;
  unset($_SESSION['showPopup']); // Evita que el popup aparezca nuevamente después de refrescar
}

?>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    let linkControl = true
    let showPopUp = <?= json_encode($showPopup) ?>; // Asegura que el valor se pase correctamente como booleano
    let linkURL = `<div id="linkReg">
                            <a href="https://forms.gle/pAGxjtjKrkCv49PL6" target="_blank">REGÍSTRATE DANDO CLIC AQUÍ</a>
                        </div>`
    if (showPopUp) {
      Swal.fire({
        html: `
                    <div style='display: flex; align-items: center; justify-content: center; flex-direction: column;'>
                        <img id="modalHome" src='vistas/img/modals/img/home/homeModal23.png'/>
                        ${linkControl ? linkURL: ""}
                    </div>
                `,
        showConfirmButton: true,
        confirmButtonText: 'Continuar',
        customClass: {
          popup: "popup_control",
          confirmButton: 'popup-confirm-button24',
        },
        timer: 50000,
        timerProgressBar: true,
      });
    }
  });
</script>



<style>
  #linkReg a {
    text-decoration: underline;
    /* Subrayado */
    color: #0e1f87;
    font-weight: bold;
    font-size: 24px;
    /* Mantiene el color original del texto */
  }

  #linkReg a:visited {
    color: #ea0b2a;
    /* Mantiene el color después de hacer clic */
  }

  #linkReg a:hover {
    text-decoration: underline;
    /* Mantiene el subrayado al pasar el mouse */
  }

  #linkReg a:active {
    color: #ea0b2a;
    /* Mantiene el color al hacer clic */
  }
</style>


<div class="content-wrapper" style="height: 100vh;">

  <section class="content-header" style="padding-left: 36px">

    <h1>

      Inicio

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

    </ol>


  </section>

  <?php require_once "./vistas/components/ai-chat/view/index.php"?>

  <?php
  echo '<script>
            console.log(' . json_encode($_SESSION) . ');
        </script>';

  ?>


  <script src="vistas\js\inicio.js" defer></script>