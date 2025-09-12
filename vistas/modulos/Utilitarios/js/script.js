$(document).ready(function() {
  $("#plataformas").click(function() {
    Swal.fire({
      icon: "info",
      title: "INFO",
      text: "Los vehículos de plataformas se cotizan con el producto. Solicita cotización manual a tu Analista Comercial asignado.",
      showConfirmButton: true,
      allowOutsideClick: true,
      allowEscapeKey: true,
    }).then(() => {
      // window.location.reload();
    });
  });
});
