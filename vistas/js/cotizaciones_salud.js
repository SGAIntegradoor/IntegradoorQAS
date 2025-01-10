$(".tablas-salud").on("click", ".btnEditarCotizacionSalud", function () {
  var idCotizacionSalud = $(this).attr("idCotizacionSalud");

  window.location =
    "index.php?ruta=retomar-cotizacion-salud&idCotizacionSalud=" +
    idCotizacionSalud;
});

console.log(permisos);

let getParams = (param) => {
  var urlPage = new URL(window.location.href); // Instancia la URL Actual
  var options = urlPage.searchParams.getAll(param); //Buscar todos los parametros
  return options;
};

function changeTitlePage() {
  var newTittle = "Datos de la cotización";
  $("#lblDataTrip").text(newTittle);
}
if (getParams("idCotizacionSalud").length > 0) {
  console.log(getParams("idCotizacionSalud")[0]);
  editarCotizacionSalud(getParams("idCotizacionSalud")[0]);
  changeTitlePage();
} else if (getParams("fechaInicialCotizaciones").length > 0) {
  menosCotizaciones();
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
    var fechaInicialCotizaciones = startDate.format("YYYY-MM-DD");
    var fechaFinalCotizaciones = endDate.format("YYYY-MM-DD");
    var capturarRango = $("#daterange-btnCotizacionesSalud span").html();
    localStorage.setItem("capturarRango2", capturarRango);
    var selectedOption = $("#daterange-btnCotizacionesSalud").data(
      "daterangepicker"
    ).chosenLabel;
    localStorage.setItem("Selected2", selectedOption);
    window.location =
      "index.php?ruta=salud&fechaInicialCotizaciones=" +
      fechaInicialCotizaciones +
      "&fechaFinalCotizaciones=" +
      fechaFinalCotizaciones;
  }
);

let selected = localStorage.getItem("Selected2");
switch (selected) {
  case "Hoy":
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setStartDate(moment());
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Ayer":
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "days"));
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "days"));
    break;
  case "Últimos 7 días":
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setStartDate(moment().subtract(7, "days"));
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Últimos 30 días":
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setStartDate(moment().subtract(30, "days"));
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Este mes":
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setStartDate(moment().startOf("month"));
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setEndDate(moment().endOf("month"));
    break;
  case "Último mes":
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "month").startOf("month"));
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "month").endOf("month"));
    break;
  case "Últimos 3 meses":
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setStartDate(moment().subtract(3, "month").startOf("month"));
    $("#daterange-btnCotizacionesSalud")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  default:
    break;
}

$("#daterange-btnCotizacionesSalud").on(
  "cancel.daterangepicker",

  function (ev, picker) {
    localStorage.removeItem("capturarRango2");

    localStorage.clear();

    window.location = "assistcard";
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
      "index.php?ruta=salud&" +
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

function disableInputs(context, disabled) {
  $(context)
    .find("input, select, textarea")
    .each(function () {
      $(this).prop("disabled", disabled);
    });
}

function editarCotizacionSalud(id) {
  idCotizacionSalud = id; // Almacena el Id en la variable global de idCotización
  //console.log(id);
  var datos = new FormData();

  datos.append("idCotizacionSalud", idCotizacionSalud);

  $.ajax({
    url: "ajax/cotizaciones.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);

      const { nombre, apellido, tipoDocumento, cedula } =
        respuesta.requestData.tomador;

      const { asegurados } = respuesta.requestData;

      $("#tipoDocumento").val("0" + tipoDocumento);
      $("#numeroDocumento").val(cedula);
      $("#nombre").val(nombre);
      $("#apellido").val(apellido);

      if (respuesta.asegurados.length > 1) {
        $("#grupoFamiliar").prop("checked", true).trigger("click");
      } else {
        $("#individual").prop("checked", false).trigger("click");
      }

      if ($("#grupoFamiliar").is(":checked")) {
        $(".cantAsegurados").show();
        $("#numAsegurados").val(respuesta.asegurados.length);
        generateAseguradosFields();
        $("#lblTomador").text("¿El tomador también será asegurado?");
      }

      if (respuesta.asegurados[0].numeroDocumento == cedula) {
        $("#si").prop("checked", true);
        $("#lblDatosAse").text("Tomador Asegurado");
      }

      $("#numAsegurados").prop("disabled", true);
      $("#tipoDocumento").prop("disabled", true);
      $("#numeroDocumento").prop("disabled", true);
      $("#nombre").prop("disabled", true);
      $("#apellido").prop("disabled", true);
      $("#grupoFamiliar").prop("disabled", true);
      $("#individual").prop("disabled", true);
      $("#si").prop("disabled", true);
      $("#no").prop("disabled", true);

      // let objAsegurados = [];
      // let asegs = [];

      $(".asegurado").each(function (index) {

          $(this).find(".nombre").val(asegurados[index].nombre);
          $(this).find(".apellido").val(asegurados[index].apellido);
          $(this).find(".tipoDocumento").val(asegurados[index].tipoDocumento);
          $(this).find(".numeroDocumento").val(asegurados[index].numeroDocumento);
          $(this).find(".genero").val(asegurados[index].genero);
          // $(this).find(".fechaNacimiento").val(asegurados[index].fechaNacimiento);

          disableInputs(this, true);

          let dia = asegurados[index].fechaNacimiento.dia.toString();
          let mes = asegurados[index].fechaNacimiento.mes.toString();
          let anio = asegurados[index].fechaNacimiento.anio.toString(); 

          console.log(dia, mes, anio);

          let monthFormatted = mes.padStart(2, "0");

          $(this)
            .find(".conten-dia")
            .find(`#dianacimiento${index == 0 ? "" : "_" + (index + 1 )}`) // Selecciona el <select>
            .val(dia.length < 2 ? "0"+dia : dia) // Cambia el valor del select
            .trigger("change"); // Actualiza el select2

          $(this)
            .find(".conten-mes")
            .find(`#mesnacimiento${index == 0 ? "" : "_" + (index + 1)}`) // Selecciona el <select>
            .val(monthFormatted) // Cambia el valor del select
            .trigger("change"); // Actualiza el select2

          $(this)
            .find(".conten-anio")
            .find(`#anionacimiento${index == 0 ? "" : "_" + (index + 1)}`) // Selecciona el <select>
            .val(anio) // Cambia el valor del select
            .trigger("change"); // Actualiza el select2
        });


      makeCards(respuesta, 2);

    },

    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud AJAX:");
      console.error("Estado:", textStatus);
      console.error("Error:", errorThrown);
      console.error("Respuesta del servidor:", jqXHR.responseText);
    },
  });
}
