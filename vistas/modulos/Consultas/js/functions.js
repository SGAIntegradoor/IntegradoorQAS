$(document).ready(function () {
  $(".tablas-negocios-user").DataTable({
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

  // $(".btnVerPoliza").on("click", function () {
  //   var idPoliza = $(this).attr("idPoliza");
  //   abrirDialogoCrear(idPoliza);
  // });
});

function abrirDialogoCrear(id = null) {
  // Configurar el diálogo
  $("#myModal2").dialog({
    title: "Consulta de Póliza",
    autoOpen: false,
    resizable: false, // Desactiva el redimensionamiento
    draggable: false, // Opcional, si deseas permitir que se pueda mover
    modal: true,
    width: 850,
    height: 700, // ← altura fija del contenido del modal
    maxHeight: $(window).height() * 0.9,
    dialogClass: "custom-dialog2",
    buttons: {
      Cerrar: function () {
        $(this).dialog("close");
      },
    },
    create: function () {
      $(".ui-dialog-titlebar-close").html('<p id="closeButtonModal">x</p>');
    },
    open: function () {
      $("body").addClass("modal-open"); // Añade la clase para bloquear el scroll de la página
      $("body").css("overflow", "hidden");
      $(".ui-dialog-buttonpane button:contains('Cerrar')").attr(
        "id",
        "btnCerrar"
      );
      // Cargar datos si se proporciona un ID
      var dataEdit = new FormData();
      dataEdit.append("id_poliza", id);
      $.ajax({
        url: "ajax/cargarPoliza.ajax.php",
        method: "POST",
        data: dataEdit,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
          if (respuesta) {
            // Rellenar los campos del formulario con los datos recibidos
            $("#txtNoPoliza").val(respuesta["no_poliza"]);

            const aseguradoras = {
              1: "Allianz",
              2: "AXA Colpatria",
              3: "Bolivar",
              4: "Equidad",
              5: "Estado",
              6: "HDI Seguros",
              7: "Mapfre",
              8: "Mundial",
              9: "Previsora",
              10: "Qualitas",
              11: "SBS Seguros",
              12: "Solidaria",
              13: "Zurich",
              14: "Assist Card",
              15: "Universal",
              16: "Assist 1",
              17: "Los Olivos",
              18: "Sura",
              19: "Cesce",
              20: "Colmena",
              21: "Coomeva",
              22: "Palig",
            };

            $("#txtAseguradora").val(
              aseguradoras[respuesta["aseguradora_poliza"]]
            );

            const ramoOptions = {
              2: "Hogar",
              4: "Salud",
              5: "Vida",
              6: "Asistencia E/V",
              7: "Motos",
              8: "Pesados",
              9: "Vida deudor",
              10: "Arrendamiento",
              12: "AP Estudiantil",
              13: "AP",
              1: "Autos Livianos",
              14: "Autos Pasajeros",
              15: "Autos Colectivo",
              16: "Bicicleta",
              17: "Credito",
              18: "Cumplimiento",
              19: "Equipo Maquinaria",
              20: "Exequias",
              21: "Hogar Deudor",
              22: "Manejo",
              23: "PYME",
              24: "RCE Autos Livianos",
              25: "RCE Motos",
              26: "RCE Pesados",
              27: "RCE Pasajeros",
              28: "RCC Colectivos",
              29: "RCE Colectivos",
              30: "RC Cumplimiento",
              31: "RC Hidrocarburos",
              32: "RC Medica Profesional",
              33: "",
            };

            $("#txtRamo").val(ramoOptions[respuesta["ramo_poliza"]]);
            $("#txtNombreAsegurado").val(
              respuesta["nombre_completo_asegurado"]
            );
            $("#txtDocumentoAsegurado").val(
              respuesta["numero_documento_asegurado"]
            );
            $("#txtPlaca").val(respuesta["placa_veh_poliza"]);
            $("#txtFechaExpedicion").val(respuesta["fecha_exp_poliza"]);
            $("#txtFechaInicioVigencia").val(
              respuesta["fecha_inicio_vig_poliza"]
            );
            $("#txtFechaFinVigencia").val(respuesta["fecha_fin_vig_poliza"]);
            $("#txtPrimaSinIVA").val(
              Number(respuesta["prima_neta_poliza"]).toLocaleString("es-CO", {
                style: "currency",
                currency: "COP",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
              })
            );

            $("#txtValorTotal").val(
              Number(respuesta["valor_total_poliza"]).toLocaleString("es-CO", {
                style: "currency",
                currency: "COP",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
              })
            );

            let formasDePago = {
              2: "Contado",
              1: "Financiado",
            };

            $("#txtFormaDePago").val(
              formasDePago[respuesta["forma_pago_poliza"]]
            );

            if (respuesta["pagos_realizados"] >= 0) {
              console.log("pagos");
              let pagos = Number(respuesta["pagos_realizados"]);
              let valorTotal = Number(respuesta["valor_total_poliza"]);
              valorTotal > pagos
                ? $("#txtEstadoCartera").val("Pendiente")
                : $("#txtEstadoCartera").val("Al Día");
            } else {
              console.log("No pages");
            }
          } else {
            console.log("No se recibieron datos");
          }
        },
        error: function () {
          console.log("Error al obtener los datos");
        },
      });
    },
    close: function () {
      // cleanFields();
      $("body").css("overflow", "auto");
      $("body").removeClass("modal-open"); // Quita la clase para restaurar el scroll
    },
  });
  // Abrir el diálogo
  $("#myModal2").dialog("open");
}

$("#daterange-btnCotizacionesSalud").daterangepicker(
  {
    ranges: {
      Hoy: [moment(), moment()],
      Ayer: [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "Últimos 7 días": [moment().subtract(7, "days"), moment()],
      "Últimos 30 días": [moment().subtract(30, "days"), moment()],
      "Este mes": [moment().startOf("month"), moment()],
      "Último mes": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
      "Últimos 3 meses": [
        moment().subtract(3, "month").startOf("month"),
        moment(),
      ],
    },
  },
  function (startDate, endDate) {
    $("#daterange-btnCotizacionesSalud span").html(
      startDate.format("MMMM D, YYYY") + " - " + endDate.format("MMMM D, YYYY")
    );
    var fechaInicial = startDate.format("YYYY-MM-DD");
    var fechaFinal = endDate.format("YYYY-MM-DD");
    var capturarRango = $("#daterange-btnCotizacionesSalud span").html();
    localStorage.setItem("capturarRango2", capturarRango);
    var selectedOption = $("#daterange-btnCotizacionesSalud").data(
      "daterangepicker"
    ).chosenLabel;
    localStorage.setItem("Selected2", selectedOption);
    window.location =
      "index.php?ruta=user-negocios&fechaInicialCreacion=" +
      fechaInicial +
      "&fechaFinalCreacion=" +
      fechaFinal;
  }
);