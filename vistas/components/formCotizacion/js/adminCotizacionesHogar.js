
function menosViewCot () {
  console.log("clicked")
    $("#masAdminCoti").toggle();
    $("#menosAdminCoti").toggle();
    $("#containerTable").toggle();
}

function masViewCot () {
  console.log("clicked")
    $("#masAdminCoti").toggle();
    $("#menosAdminCoti").toggle();
    $("#containerTable").toggle();
}

$(document).ready(function () { 
    $("#menosAdminCoti").click(function () {
      menosViewCot();
    });

    $("#masAdminCoti").click(function () {
        masViewCot();
    })

    if(getParams("idCotizacionHogar").length > 0){
      $("#lblCotAseg").html("DATOS DEL ASEGURADO")
    }

})

$(".tablas-hogar").DataTable({
    layout: {
      topStart: "buttons",
      topCenter: {
        search: {
          placeholder: "Buscar...",
        },
      },
      topEnd: {
        pageLength: {
          menu: [10, 25, 50, 100],
        },
      },
      bottomEnd: {
        paging: {
          numbers: 3,
        },
      },
    },
    // columnDefs: [
    //     { targets: [3], visible: false } // Oculta las columnas 10 y 11 (ajusta según el índice de tus columnas ocultas)
    //   ],
    buttons: [
        {
          extend: "excelHtml5",
          className: "btn-excel",
          text: '<img src="vistas/img/excelIco.png" />', // Agrega un texto descriptivo
          titleAttr: "Exportar a Excel", // Agrega un tooltip
      },
    ],
    responsive: true,
    order: [
      [0, "desc"],
      [1, "desc"],
    ],
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sInfoPostFix: "",
      sSearch: "Buscar:",
      sUrl: "",
      sInfoThousands: ",",
      sLoadingRecords: "Cargando...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
      },
      oAria: {
        sSortAscending:
          ": Activar para ordenar la columna de manera ascendente",
        sSortDescending:
          ": Activar para ordenar la columna de manera descendente",
      },
    },
  });

/* Bloque logica editar Estado de cotizacion (Hogar) */

$(".tablas-hogar").on("click", ".btnEditarEstadoHogar", function () {
  var idCotizacionHogar = $(this).attr("idCotizacionHogar");
  Swal.fire({
    title: "Cambio de estado",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Cotizada",
    cancelButtonText: "Pendiente",
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
  }).then((result) => {
    if (result.isConfirmed) {
      enviarCambioEstado("Cotizada", idCotizacionHogar);
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      enviarCambioEstado("Pendiente", idCotizacionHogar);
    }
  });
});

function enviarCambioEstado(estado, idcotizacion) {
  $.ajax({
    url: "src/cambiarEstadoHogar.php",
    method: "POST",
    data: {
      estado: estado,
      idcotizacionHogar: idcotizacion,
    },
    success: function(response) {
      console.log("Estado actualizado:", response.message);
      Swal.fire("Éxito", "El estado se actualizó correctamente.", "success");
    },
    error: function(xhr, status, error) {
      console.error("Error al actualizar el estado:", error);
      Swal.fire("Error", "Hubo un problema al actualizar el estado.", "error");
    }
  });
  location.reload();
}