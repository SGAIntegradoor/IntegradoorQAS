function menosCotizaciones () {
  
    $("#masAdminCoti").toggle();
    $("#menosAdminCoti").toggle();
    $("#containerTable").toggle();
}

function masViewCot () {
    $("#masAdminCoti").toggle();
    $("#menosAdminCoti").toggle();
    $("#containerTable").toggle();
}

$(document).ready(function () { 
    $("#masAdminCoti, #menosAdminCoti").click(function () {
        menosCotizaciones();
    });

    $("#masAdminCoti, #menosAdminCoti").click(function () {
        masViewCot();
    })

    if(getParams("idCotizacionHogar").length > 0){
      $("#lblCotAseg").html("DATOS DEL ASEGURADO")
    }

})

$(".tablas-salud").DataTable({
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