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
              2: "Axa Colpatria",
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
            };

            $("#txtAseguradora").val(aseguradoras[respuesta["aseguradora_poliza"]]);

            let ramoOptions = {
              1: "Autos",
              2: "Motos",
              3: "Pesados",
              4: "Hogar",
              5: "Vida",
              6: "Asistencia en viajes",
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
            $("#txtPrimaSinIVA").val(respuesta["prima_neta_poliza"]);
            $("#txtValorTotal").val(respuesta["valor_total_poliza"]);

            let formasDePago = {
              1: "Contado",
              2: "Financiado",
            };

            $("#txtFormaDePago").val(formasDePago[respuesta["forma_pago_poliza"]]);

            if (respuesta["pagos_realizados"]) {
              let pagos = Number(respuesta["pagos_realizados"]);
              let valorTotal = Number(respuesta["valor_total_poliza"]);
              valorTotal > pagos
                ? $("#txtEstadoCartera").val("Pendiente")
                : $("#txtEstadoCartera").val("Al Día");
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
