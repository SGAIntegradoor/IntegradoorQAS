<?php

if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
    header('Location: login');
    exit();
}

// Actualiza el tiempo de la última actividad
$_SESSION['lastActivity'] = time();

$show = true;
$showPopup = false;

if($show && $_SESSION['rol'] != 2){
    if (isset($_SESSION['showPopup']) && $_SESSION['showPopup'] === true) {
        $showPopup = true;
        unset($_SESSION['showPopup']); // Evita que el popup aparezca nuevamente después de refrescar la view
    }
}
?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    let linkControl = true;
    let showPopUp = <?= json_encode($showPopup) ?>; // Asegura que el valor se pase correctamente como booleano
    let linkURL = `<div id="linkReg">
                            <span><b style="font-size:16px">REGISTRATE AQUÍ</b> 👉 📅 <a style="cursor:pointer" href="https://forms.gle/qBTQXz5KEwYortzG8" target="_blank">https://forms.gle/qBTQXz5KEwYortzG8</a></span>
                        </div>`
    if (showPopUp) {
      Swal.fire({
        html:   `
                    <div style='display: flex; align-items: center; justify-content: center; flex-direction: column;'>
                          <img id="modalHome" width="400px" src='vistas/img/modals/img/home/homeModal57.jpeg'/>
                        <!-- <video autoplay controls>
                          <source src="vistas/img/modals/img/home/video_hallowen.mp4" type="video/mp4">
                          Tu navegador no soporta el video.
                        </video> -->

                        ${linkControl ? linkURL: ""}
                    </div>
                `,
        showConfirmButton: true,
        confirmButtonText: 'Continuar',
        customClass: {
          popup: "popup_control",
          confirmButton: 'popup-confirm-button24',
        },
        timer: 5000000,
        timerProgressBar: true,
      });
    }
  });
</script>

<!--<div id="linkReg">
        <a href="https://forms.gle/L5wYZNTaavYu9Chn7" target="_blank">REGÍSTRATE DANDO CLIC AQUÍ</a>
</div> -->


<style>
  #linkReg a {
    text-decoration: underline;
    /* Subrayado */
    color: #016D39;
    font-weight: bold;
    font-size: 18px;
    /* Mantiene el color original del texto */
  }

  #linkReg a:visited {
    color: #016d39;
    /* Mantiene el color después de hacer clic */
  }

  #linkReg a:hover {
    text-decoration: underline;
    /* Mantiene el subrayado al pasar el mouse */
  }

  #linkReg a:active {
    color: #016d39;
    /* Mantiene el color al hacer clic */
  }
</style>


<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Inicio

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

    </ol>


  </section>

  <?php
  echo '<script>
            console.log(' . json_encode($_SESSION) . ');
        </script>';

  ?>
  

  <script src="vistas\js\inicio.js" defer></script>