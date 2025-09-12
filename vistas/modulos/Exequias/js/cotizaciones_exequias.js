let getParams = (param) => {
  var urlPage = new URL(window.location.href); // Instancia la URL Actual
  var options = urlPage.searchParams.getAll(param); //Buscar todos los parametros
  return options;
};

function changeTitlePage() {
  var newTittle = "Datos del Viaje";
  $("#lblDataTrip").text(newTittle);
}
if (getParams("idCotizacionAssistCard").length > 0) {
  editarCotizacionAssistcard(getParams("idCotizacionAssistCard")[0]);
  changeTitlePage();
} else if (getParams("fechaInicialCotizaciones").length > 0) {
  menosCotizaciones();
}

$("#daterange-btnCotizacionesExequias").daterangepicker(
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
    $("#daterange-btnCotizacionesExequias span").html(
      startDate.format("MMMM D, YYYY") + " - " + endDate.format("MMMM D, YYYY")
    );
    var fechaInicialCotizaciones = startDate.format("YYYY-MM-DD");
    var fechaFinalCotizaciones = endDate.format("YYYY-MM-DD");
    var capturarRango = $("#daterange-btnCotizacionesExequias span").html();
    localStorage.setItem("capturarRango2", capturarRango);
    var selectedOption = $("#daterange-btnCotizacionesExequias").data(
      "daterangepicker"
    ).chosenLabel;
    localStorage.setItem("Selected2", selectedOption);
    window.location =
      "index.php?ruta=exequias&fechaInicialCotizaciones=" +
      fechaInicialCotizaciones +
      "&fechaFinalCotizaciones=" +
      fechaFinalCotizaciones;
  }
);

let selected = localStorage.getItem("Selected2");
switch (selected) {
  case "Hoy":
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setStartDate(moment());
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Ayer":
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "days"));
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "days"));
    break;
  case "Últimos 7 días":
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setStartDate(moment().subtract(7, "days"));
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Últimos 30 días":
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setStartDate(moment().subtract(30, "days"));
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Este mes":
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setStartDate(moment().startOf("month"));
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setEndDate(moment().endOf("month"));
    break;
  case "Último mes":
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "month").startOf("month"));
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "month").endOf("month"));
    break;
  case "Últimos 3 meses":
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setStartDate(moment().subtract(3, "month").startOf("month"));
    $("#daterange-btnCotizacionesExequias")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  default:
    break;
}

$("#daterange-btnCotizacionesExequias").on(
  "cancel.daterangepicker",

  function (ev, picker) {
    localStorage.removeItem("capturarRango2");

    localStorage.clear();

    window.location = "exequias";
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

    var fechaInicialCotizaciones = año + "-" + mes + "-" + dia;

    var fechaFinalCotizaciones = año + "-" + mes + "-" + dia;

    var fechaInicialCotizaciones1 =
      fechaInicialCotizaciones.format("YYYY-MM-DD");

    var fechaFinalCotizaciones1 = fechaFinalCotizaciones.format("YYYY-MM-DD");

    localStorage.setItem("capturarRango", "Hoy");

    window.location =
      "index.php?ruta=exequias&" +
      "fechaInicialCotizaciones=" +
      fechaInicialCotizaciones1 +
      "&fechaFinalCotizaciones=" +
      fechaFinalCotizaciones1;
  }
});

// Carga la fecha de Nacimiento
$("#dianacimientoResumen, #mesnacimientoResumen, #anionacimientoResumen").each(
  function () {
    $(this).select2({
      theme: "bootstrap fecnacimiento",
      language: "es",
      width: "100%",
      // Otras configuraciones específicas si las necesitas
    });
    $(this).on("select2:open", function (e) {
      var $select2 = $(this).data("select2");
      $select2.dropdown.$dropdownContainer.addClass("select2-container--above");
    });
  }
);

$("#menosCotizacion, #masCotizacion").click(function () {
  toggleContainerData();
});

$("#menosParrilla, #masParrilla").click(function () {
  toggleContainerCards();
});
