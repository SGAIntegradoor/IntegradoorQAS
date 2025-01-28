window.addEventListener("beforeunload", () => {
  navigator.sendBeacon(
    "controladores/session.php",
    JSON.stringify({ logout: true })
  );
});

$(document).ready(function () {
  // Intervalo de verificación: cada 10 segundos
  setInterval(function () {
      $.ajax({
          url: "controladores/session.php", // Ruta al archivo de verificación
          method: "GET",
          success: function (response) {
              if (response.trim() === "expired") {
                  // Si la sesión ha expirado, redirigir al login
                  Swal.fire({
                      title: "Sesión Expirada",
                      text: "Tu sesión ha expirado. Por favor, inicia sesión nuevamente.",
                      icon: "warning",
                      confirmButtonText: "OK",
                  }).then(function (result) {
                    if (result.isConfirmed) {
                      window.location = "login";
                    } else if (result.isDismissed) {
                      if (result.dismiss === "cancel") {
                        window.location = "login";
                      } else if (result.dismiss === "backdrop") {
                        window.location = "login";
                      }
                    }
                  });
              }
          },
          error: function (xhr, status, error) {
              console.error("Error en la verificación de sesión:", error);
          }
      });
  }, 10000); // 10 segundos
});

/*=============================================
SideBar Menu
=============================================*/

$(".sidebar-menu").tree();

/*=============================================
 //iCheck for checkbox and radio inputs
=============================================*/

$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
  checkboxClass: "icheckbox_minimal-blue",
  radioClass: "iradio_minimal-blue",
});

/*=============================================
 //input Mask
=============================================*/

//Datemask dd/mm/yyyy
$("#datemask").inputmask("dd/mm/yyyy", { placeholder: "dd/mm/yyyy" });
//Datemask2 mm/dd/yyyy
$("#datemask2").inputmask("mm/dd/yyyy", { placeholder: "mm/dd/yyyy" });
//Money Euro
$("[data-mask]").inputmask();

/*=============================================
CORRECCIÓN BOTONERAS OCULTAS BACKEND	
=============================================*/

if (window.matchMedia("(max-width:767px)").matches) {
  $("body").removeClass("sidebar-collapse");
} else {
  $("body").addClass("sidebar-collapse");
}

// function checkSession() {
//   // Obtener la ruta actual
//   let rutaActual = window.location.href.split("/");
//   let paginaActual = rutaActual[rutaActual.length - 1]; // Obtiene la última parte de la ruta, por ejemplo, 'login'

//   // Verificar si no estamos en la página de login
//   if (paginaActual !== "login") {
//     let lastUserLogged = localStorage.getItem("lastUserLogged");
//     const formData = new FormData();
//     formData.append("lastUserLogged", lastUserLogged);
//     fetch("config/checkSession.php", {
//       method: "POST",
//       body: formData,
//     })
//       .then((response) => response.json())
//       .then((data) => {
//         if (!data.sessionActive) {
//           // Si la sesión ha expirado, eliminar el item de localStorage
//           // Redirigir al usuario a la página de inicio de sesión
//           //localStorage.removeItem("lastUserLogged");
//           window.location.href = "login";
//         } else {
//           return;
//           //console.log("me ejecute cada 10 segs", data.sessionActive);
//         }
//       })
//       .catch((error) => console.error("Error:", error));
//   }
// }

// // Verificar la sesión cada 10 segundos
// setInterval(checkSession, 63000);
