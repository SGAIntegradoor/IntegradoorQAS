/*=============================================
SideBar Menu
=============================================*/

 $('.sidebar-menu').tree();

/*=============================================
 //iCheck for checkbox and radio inputs
=============================================*/

$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
  checkboxClass: 'icheckbox_minimal-blue',
  radioClass   : 'iradio_minimal-blue'
});

/*=============================================
 //input Mask
=============================================*/

//Datemask dd/mm/yyyy
$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
//Datemask2 mm/dd/yyyy
$('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
//Money Euro
$('[data-mask]').inputmask();

/*=============================================
CORRECCIÓN BOTONERAS OCULTAS BACKEND	
=============================================*/

if(window.matchMedia("(max-width:767px)").matches){
	
	$("body").removeClass('sidebar-collapse');

}else{

	$("body").addClass('sidebar-collapse');
}


function checkSession() {
  // Obtener la ruta actual
  let rutaActual = window.location.href.split("/");
  let paginaActual = rutaActual[rutaActual.length - 1]; // Obtiene la última parte de la ruta, por ejemplo, 'login'

  // Verificar si no estamos en la página de login
  if (paginaActual !== "login") {
      fetch('config/checkSession.php')
          .then(response => response.json())
          .then(data => {
              if (!data.sessionActive) {
                  // Si la sesión ha expirado, eliminar el item de localStorage
                  localStorage.removeItem('initModal');
                  // Redirigir al usuario a la página de inicio de sesión
                  window.location.href = 'login';
              } else {
                  console.log("me ejecute cada 10 segs", data.sessionActive);
              }
          })
          .catch(error => console.error('Error:', error));
  }
}

// Verificar la sesión cada 10 segundos
setInterval(checkSession, 900000);