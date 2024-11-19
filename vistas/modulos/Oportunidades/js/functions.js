function loadAnalistas() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "ajax/analistas.ajax.php",
      type: "POST",
      success: function (data) {
        $("#analistaGA").append(data);
        resolve(); // Resolviendo la promesa una vez que los datos se han añadido
      },
      error: function (error) {
        reject(error); // En caso de error, rechazar la promesa
      },
    });
  });
}

function loadFreelance() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "ajax/freelances.ajax.php",
      type: "POST",
      success: function (data) {
        $("#nombreAsesor").append(data);
        resolve(); // Resolviendo la promesa una vez que los datos se han añadido
      },
      error: function (error) {
        reject(error); // En caso de error, rechazar la promesa
      },
    });
  });
}

function reset() {
  window.location.href = "negocios";
}

$(document).ready(function () {
  Promise.all([loadAnalistas(), loadFreelance()])
    .then(() => {
      aplicarCriterios(); // Llama a aplicarCriterios una vez que ambos AJAX han completado
    })
    .catch((error) => {
      console.error("Error al cargar datos:", error);
    });
});

let getParams = () => {
  let url = new URL(window.location.href);
  return Object.fromEntries(url.searchParams.entries());
};

function aplicarCriterios() {
  const criterios = [
    "mesExpedicion",
    "estado",
    "analistaGA",
    "nombreAsesor",
    "formaDePago",
    "financiera",
    "aseguradoraOpo",
    "ramo",
    "onerosoOp",
  ];

  let params = getParams();
  for (let [key, value] of Object.entries(params)) {
    if (criterios.includes(key)) {
      $(`#${key} option`).each(function () {
        if ($(this).text().trim() === value.trim()) {
          $(`#${key}`).val($(this).val()).trigger("change");
          return false; // Detener la iteración
        }
      });
    }
  }
}

let url = `index.php?ruta=negocios`;

function searchInfo() {
  let mesExpedicion =
    $("#mesExpedicion").val() !== ""
      ? $("#mesExpedicion option:selected").text()
      : "";
  let estado =
    $("#estado").val() !== "" ? $("#estado option:selected").text() : "";
  let nombreAsesor =
    $("#nombreAsesor").val() !== ""
      ? $("#nombreAsesor option:selected").text()
      : "";
  let analistaGA =
    $("#analistaGA").val() !== ""
      ? $("#analistaGA option:selected").text()
      : "";
  let aseguradoraOpo =
    $("#aseguradoraOpo").val() !== ""
      ? $("#aseguradoraOpo option:selected").text()
      : "";
  let ramo = $("#ramo").val() !== "" ? $("#ramo option:selected").text() : "";
  let onerosoOp =
    $("#onerosoOp").val() !== "" ? $("#onerosoOp option:selected").text() : "";
  let formaDePago =
    $("#formaDePago").val() !== ""
      ? $("#formaDePago option:selected").text()
      : "";
  let financiera =
    $("#financiera").val() !== ""
      ? $("#financiera option:selected").text()
      : "";

  if (mesExpedicion !== "") {
    url += `&mesExpedicion=${mesExpedicion}`;
  }

  if (estado !== "") {
    url += `&estado=${estado}`;
  }

  if (nombreAsesor !== "") {
    url += `&nombreAsesor=${nombreAsesor}`;
  }

  if (analistaGA !== "") {
    url += `&analistaGA=${analistaGA}`;
  }

  if (aseguradoraOpo !== "") {
    url += `&aseguradoraOpo=${aseguradoraOpo}`;
  }

  if (ramo !== "") {
    url += `&ramo=${ramo}`;
  }

  if (onerosoOp !== "") {
    url += `&onerosoOp=${onerosoOp.trim()}`;
  }

  if (formaDePago !== "") {
    url += `&formaDePago=${formaDePago.trim()}`;
  }
  if (financiera !== "") {
    url += `&financiera=${financiera.trim()}`;
  }

  window.location.href = url;
}

let control = false; // Variable global

function menosCotizaciones() {
  $("#filtersSearch").toggle();
  $("#menosCotizacion").toggle();
  $("#masCotizacion").toggle();

  if (control) {
    $(".row-filters").css("margin-bottom", "0px");
    $(".row-filters").css("border-bottom-left-radius", "0px");
    $(".row-filters").css("border-bottom-right-radius", "0px");
  } else {
    $(".row-filters").css("margin-bottom", "0px");
    $(".row-filters").css("border-bottom-left-radius", "10px");
    $(".row-filters").css("border-bottom-right-radius", "10px");
  }

  control = !control; // Alterna el valor de control
}

$(document).ready(function () {
  $("#masCots, #menosCots").click(function () {
    menosCotizaciones();
  });
});

$(".tablas-oportunidades").on("click", ".btnEditarOportunidad", function () {
  var idCotizacionAssistCard = $(this).attr("id_oportunidad");
});

$(
  "#nombreAsesor, #estado, #mesExpedicion, #nombreAsesor, #analistaGA, #aseguradoraOpo, #ramo, #onerosoOp, #formaDePago, #financiera"
).select2({
  theme: "bootstrap selecting",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Seleccione una opción",
});

$("#daterange-btnOportunidades").daterangepicker(
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
    $("#daterange-btnOportunidades span").html(
      startDate.format("MMMM D, YYYY") + " - " + endDate.format("MMMM D, YYYY")
    );
    var fechaInicialNegocios = startDate.format("YYYY-MM-DD");
    var fechaFinalNegocios = endDate.format("YYYY-MM-DD");
    var capturarRango = $("#daterange-btnOportunidades span").html();
    localStorage.setItem("capturarRango2", capturarRango);
    var selectedOption = $("#daterange-btnOportunidades").data(
      "daterangepicker"
    ).chosenLabel;
    localStorage.setItem("Selected2", selectedOption);
    window.location =
      "index.php?ruta=negocios&" +
      "fechaInicialOportunidades=" +
      fechaInicialNegocios +
      "&fechaFinalOportunidades=" +
      fechaFinalNegocios;
  }
);

let selected = localStorage.getItem("Selected2");
switch (selected) {
  case "Hoy":
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setStartDate(moment());
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Ayer":
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "days"));
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "days"));
    break;
  case "Últimos 7 días":
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setStartDate(moment().subtract(7, "days"));
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Últimos 30 días":
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setStartDate(moment().subtract(30, "days"));
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Este mes":
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setStartDate(moment().startOf("month"));
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setEndDate(moment().endOf("month"));
    break;
  case "Último mes":
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "month").startOf("month"));
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "month").endOf("month"));
    break;
  case "Últimos 3 meses":
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setStartDate(moment().subtract(3, "month").startOf("month"));
    $("#daterange-btnOportunidades")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  default:
    break;
}

$("#daterange-btnOportunidades").on(
  "cancel.daterangepicker",

  function (ev, picker) {
    localStorage.removeItem("capturarRango2");

    localStorage.clear();

    window.location = "negocios";
  }
);

$(".daterangepicker.opensleft").on("click", ".liCotizaciones", function () {
  var textoHoy = $(this).attr("data-range-key");

  if (textoHoy == "Hoy") {
    var d = new Date();

    var dia = d.getDate();

    var mes = d.getMonth() + 1;

    var año = d.getFullYear();

    dia = ("0" + dia).slice(-2);

    mes = ("0" + mes).slice(-2);

    var fechaInicialNegocios = año + "-" + mes + "-" + dia;

    var fechaFinalNegocios = año + "-" + mes + "-" + dia;

    var fechaInicialNegocios1 = fechaInicialNegocios.format("YYYY-MM-DD");

    var fechaFinalNegocios1 = fechaFinalNegocios.format("YYYY-MM-DD");

    localStorage.setItem("capturarRango", "Hoy");

    window.location =
      "index.php?ruta=assistcard&" +
      "fechaInicialOportunidades=" +
      fechaInicialNegocios1 +
      "&fechaFinalOportunidades=" +
      fechaFinalNegocios1;
  }
});

$(".tablas-oportunidades").DataTable({
  scrollX: true, // Activa el scroll horizontal
  responsive: false, // Desactiva la funcionalidad responsive que crea el acordeón
  dom: '<"top"Blf>rt<"bottom"ip>',
  // dom: '<"top"Blf>rt<"bottom"ip>',
  buttons: [
    {
      extend: "excelHtml5",
      className: "btn-excel",
      text: '<img src="vistas/img/excelIco.png" />',
      titleAttr: "Exportar a Excel",
    },
  ],
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
    sSearch: "Buscar:",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  },
});
