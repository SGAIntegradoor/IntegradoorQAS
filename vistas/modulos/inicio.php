
<?php 

if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
  header('Location: login');
  exit();
}

// Actualiza el tiempo de la última actividad
$_SESSION['lastActivity'] = time();

$showPopup = false;
if (isset($_SESSION['showPopup']) && $_SESSION['showPopup'] === true) {
  $showPopup = true;
  $_SESSION['showPopup'] = false;
}
?> 


<div class="content-wrapper" >

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
// if ($_SESSION['permisos']['id_rol'] == '19') {
//   echo '<script>
//       Swal.fire({
//           title: "Módulo Habilitado",
//           text: "Ya está habilitado el módulo para cotizar pesados.",
//           icon: "success",
//       }).then(function() {
//           // Redirige si es necesario
//       });
//   </script>';

//   return;
//   // Detén la ejecución del script actual
// }


?>
<script>
        $(document).ready(function () {
            // Mostrar el popup si está habilitado
            <?php if ($showPopup): ?>
              swal.fire({
                       html: `
                           <div style='display: flex; align-items: center; justify-content: center;'>
                            <img id="modalHome" src='vistas/img/modals/img/home/homeModal.png'/>
                           </div>
                       `,
                       showConfirmButton: true,
                       confirmButtonText: 'Continuar',
                       customClass: {
                           popup: "popup_control",
                           confirmButton: 'popup-confirm-button24',
                       },
                       timer: 20000,
                       timerProgressBar: true,
                   })
            <?php endif; ?>
        });
    </script>

<script src="vistas\js\inicio.js" defer></script>

