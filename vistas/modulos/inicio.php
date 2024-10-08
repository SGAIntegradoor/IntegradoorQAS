
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

<div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        let modalShown = localStorage.getItem('initModal');

        // Solo mostrar el modal si no se ha mostrado antes
        if (!modalShown || modalShown === 'false') {
            swal.fire({
                html: `
                    <div style='display: flex; align-items: center; justify-content: center; padding: 20px;'>
                    <img src='vistas/img/modals/img/home/modalHome.jpg' style="max-width: 350px"/>
                    </div>
                `,
  
                showConfirmButton: true,
                confirmButtonText: 'Continuar',
                customClass: {
                    popup: 'popup-login',
                    title: 'custom-swal-titlePesados',
                    confirmButton: 'custom-swal-confirm-button24',
                    actions: 'custom-swal-actions-pesados',
                    icon: 'swal2-icon_monto',
                },
                timer: 20000,
                timerProgressBar: true,
            }).then(() => {
                // Después de cerrar el modal, establecer initModal en true para no mostrarlo de nuevo
                localStorage.setItem('initModal', true);
            });
        }
    });
  </script>
</div>