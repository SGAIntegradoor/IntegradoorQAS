$(function () {
    $("#myModal").dialog({
      autoOpen: false, // Evita que el modal se abra automáticamente
      modal: true,     // Hace que el fondo sea oscuro (modal)
      width: 400,      // Ancho del modal
      buttons: {       // Agrega botones personalizados
        "Cerrar": function() {
          $(this).dialog("close");
        }
      }
    });
  
    $("#openModal").click(function() {
      console.log("abriendo")
      $("#myModal").dialog("open");
    });
  });