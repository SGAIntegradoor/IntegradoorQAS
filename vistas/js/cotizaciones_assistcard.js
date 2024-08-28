$(document).ready(function () {
  // const getOffertsQuotation = async () => {
  //   // $.ajax({
  //   //     url: "####",
  //   //     method: "POST",
  //   //     data: datos,
  //   //     cache: false,
  //   //     contentType: false,
  //   //     processData: false,
  //   //     dataType: "json",
  //   //     success: function (respuesta) {



  //   //     }
  //   // })

  //   const promiseCot = await fetch('')


  // };

  $(".tablas-assistcard").on("click", ".btnEditarCotizacionAssistCard", function () {
    var idCotizacion = $(this).attr("idCotizacion");

    window.location =
      "index.php?ruta=retomar-cotizacion-assistcard&idCotizacion=" + idCotizacion;

    // $.redirect("editar-cotizacion", { idCotizacion: idCotizacion }, "POST");
  });

});
