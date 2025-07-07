// metodo para cargar las ciudades cuando se selecciona un departamento
queryCiudades();

$(".tablas-salud").on("click", ".btnEditarCotizacionSalud", function () {
  var idCotizacionSalud = $(this).attr("idCotizacionSalud");

  window.location =
    "index.php?ruta=retomar-cotizacion-salud&idCotizacionSalud=" +
    idCotizacionSalud;
});

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
  toggleContainerData();
  idCotizacionSalud = id; // Almacena el Id en la variable global de idCotización
  var datos = new FormData();

  $("#loaderFilters").html(
    `<div style="display:flex; align-items: center; justify-content: center; margin-bottom: 90px; margin-top: 90px; gap: 10px"><img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong style="font-size: 19px"> Cargando...</strong></div>`
  );

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
      console.log(respuesta.asegurados);
      const { cedula, nombre, apellido, tipoDocumento } =
        respuesta.requestData.tomador;

      if (respuesta.asegurados[0].ciudad) {
        $("#siCiudadB").prop("checked", true);
      }

      const { asegurados } = respuesta.requestData;

      // Verifica si algún asegurado es asociado a Coomeva
      let algunoAsociado = false;
      for (let i = 0; i < asegurados.length; i++) {
        if (asegurados[i].asociado == 1) {
          algunoAsociado = true;
          break;
        }
      }
      if (algunoAsociado) {
        $("#siAsociadoC").prop("checked", true);
      } else {
        $("#noAsociadoC").prop("checked", true);
      }

      const fields = ["nombre", "apellido", "tipoDocumento", "cedula"];

      fields.forEach((field) => {
        if (field === "cedula") {
          field = "numeroDocumento";
        }
        $(`.${field}`).prop("disabled", true);
      });

      $("#tomadorContainerData")
        .find(".tipoDocumento")
        .val("0" + tipoDocumento);
      $("#tomadorContainerData").find(".numeroDocumento").val(cedula);
      $("#tomadorContainerData").find(".nombre").val(nombre);
      $("#tomadorContainerData").find(".apellido").val(apellido);

      if (respuesta.asegurados.length > 1) {
        $("#grupoFamiliar").prop("checked", true).trigger("click");
      } else {
        $("#individual").prop("checked", true).trigger("click");
      }

      // verifica si hay mas de un asegurado, si es asi le da un check a el radio de grupo familiar
      if ($("#grupoFamiliar").is(":checked")) {
        $(".cantAsegurados").show();
        $("#numAsegurados").val(respuesta.asegurados.length);
        generateAseguradosFields();
        $("#lblTomador").text("¿El tomador también será asegurado?");
      }
      // verifica si el tomador es asegurado, si es asi le da un check a el radio de si
      if (respuesta.asegurados[0].nombre == nombre) {
        $("#si").prop("checked", true);
        $("#lblDatosAse").text("Tomador Asegurado");
      }

      // $(".preguntasForm").hide();

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

      /* BLOQUE AGREGADO Y FUNCIONAL PARA RECUPERAR LA INFO DE LA COTIZACION JAVIER-DEV */
      $("#nombre").val(asegurados[0].nombre);
      $("#apellido").val(asegurados[0].apellido);
      $("#genero").val(asegurados[0].genero);
      $("#select2-dianacimiento-container").text(
        asegurados[0].fechaNacimiento.dia
      );
      $("#select2-mesnacimiento-container").text(
        asegurados[0].fechaNacimiento.mes
      );
      $("#select2-anionacimiento-container").text(
        asegurados[0].fechaNacimiento.anio
      );

      $("#departamento_1").val(asegurados[0].id_departamento).trigger("change");
      $("#ciudad_1").val(asegurados[0].id_ciudad);
      $("#ciudad_1").val(asegurados[0].id_ciudad);
      $("#asociadoSi_1").prop("disabled", true);
      $("#asociadoNo_1").prop("disabled", true);

      if (asegurados[0].asociado == 1) {
        $("#asociadoSi_1").prop("checked", true);
      } else {
        $("#asociadoNo_1").prop("checked", true);
      }

      for (let i = 1; i < asegurados.length; i++) {
        // Deshabilita los inputs de los asegurados
        $("#nombre_" + (i + 1)).prop("disabled", true);
        $("#apellido_" + (i + 1)).prop("disabled", true);
        $("#departamento_" + (i + 1)).prop("disabled", true);
        $("#ciudad_" + (i + 1)).prop("disabled", true);
        $("#dianacimiento_" + (i + 1)).prop("disabled", true);
        $("#mesnacimiento_" + (i + 1)).prop("disabled", true);
        $("#anionacimiento_" + (i + 1)).prop("disabled", true);
        $("#genero_" + (i + 1)).prop("disabled", true);
        $("#asociadoSi_" + (i + 1)).prop("disabled", true);
        $("#asociadoNo_" + (i + 1)).prop("disabled", true);

        if (asegurados[i].asociado == 1) {
          $("#asociadoSi_" + (i + 1)).prop("checked", true);
        } else {
          $("#asociadoNo_" + (i + 1)).prop("checked", true);
        }

        // Asigna los valores de los asegurados a los inputs correspondientes
        $("#nombre_" + (i + 1)).val(asegurados[i].nombre);
        $("#apellido_" + (i + 1)).val(asegurados[i].apellido);
        $("#genero_" + (i + 1)).val(asegurados[i].genero);
        $("#select2-dianacimiento_" + (i + 1) + "-container").text(
          asegurados[i].fechaNacimiento.dia
        );
        $("#select2-mesnacimiento_" + (i + 1) + "-container").text(
          asegurados[i].fechaNacimiento.mes
        );
        $("#select2-anionacimiento_" + (i + 1) + "-container").text(
          asegurados[i].fechaNacimiento.anio
        );

        $("#departamento_" + (i + 1))
          .val(asegurados[i].id_departamento)
          .trigger("change");

        $("#ciudad_" + (i + 1)).val(asegurados[i].id_ciudad);
      }

      $(".aseguradosContainer").each(function (index) {
        $(this).find(".nombre").val(asegurados[index].nombre);
        $(this).find(".apellido").val(asegurados[index].apellido);
        // $(this).find(".tipoDocumento").val(asegurados[index].tipoDocumento);
        // $(this).find(".numeroDocumento").val(asegurados[index].numeroDocumento);
        $(this).find(".genero").val(asegurados[index].genero);
        $(this)
          .find(".departamento")
          .val(asegurados[index].departamento)
          .trigger("change");
        $(this).find(".ciudad").val(asegurados[index].ciudad).trigger("change");
        // $(this).find(".fechaNacimiento").val(asegurados[index].fechaNacimiento);

        disableInputs(this, true);

        let dia = asegurados[index].fechaNacimiento.dia.toString();
        let mes = asegurados[index].fechaNacimiento.mes.toString();
        let anio = asegurados[index].fechaNacimiento.anio.toString();

        let monthFormatted = mes.padStart(2, "0");

        $(this)
          .find(".conten-dia")
          .find(`#dianacimiento${index == 0 ? "" : "_" + (index + 1)}`) // Selecciona el <select>
          .val(dia.length < 2 ? "0" + dia : dia) // Cambia el valor del select
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
      
      hideShowCamposCiudad();
      hideShowAsociadoCoomeva();
      makeCards(respuesta, 2);

      $("#loaderFilters").hide();
    },

    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud AJAX:");
      console.error("Estado:", textStatus);
      console.error("Error:", errorThrown);
      console.error("Respuesta del servidor:", jqXHR.responseText);
    },
  });

  setTimeout(function () {
    $(".container-salud")
      .find("input, select, textarea")
      .prop("disabled", true);
  }, 3000);
}

$(document).on("change", ".departamentoSelect", function () {
  const selectId = $(this).attr("id"); // e.g. departamento_1
  const index = selectId.split("_")[1]; // e.g. 1
  const selectedDepartamento = $(this).val(); // valor del departamento seleccionado
  const ciudadSelect = $(`#ciudad_${index}`); // select relacionado
  let ciudadesData = [];

  // Recuperar el array de ciudades directamente
  try {
    ciudadesData = JSON.parse(localStorage.getItem("ciudades")) || [];
  } catch (e) {
    ciudadesData = [];
  }

  // Filtrar ciudades que pertenecen al departamento
  const ciudadesFiltradas = ciudadesData.filter(
    (ciudad) => ciudad.cod_departamento == Number(selectedDepartamento)
  );

  ciudadesFiltradas.map((ciudad) => {
    ciudad.ciudad = formatInput(ciudad.ciudad);
  });

  // Limpiar el select de ciudad antes de llenarlo
  ciudadSelect.empty();

  if (ciudadesFiltradas.length > 0) {
    ciudadSelect.append(`<option value="">Seleccione una ciudad</option>`);
    ciudadesFiltradas.forEach((ciudad) => {
      ciudadSelect.append(
        `<option value="${ciudad.codigo}">${ciudad.ciudad}</option>`
      );
    });
  } else {
    ciudadSelect.append(`<option value="">No hay ciudades</option>`);
  }
});

function queryCiudades() {
  // Cargar las ciudades al cargar la página Javier Pendiente. hacer que se llamen todas las ciudades modify
  $.ajax({
    type: "POST",
    url: "src/consultarCiudadHogar.php",
    data: { codigoDpto: 0 },
    cache: false,
    success: function (data) {
      // Si la respuesta es un string, conviértela a objeto
      let response = typeof data === "string" ? JSON.parse(data) : data;
      // Guardar solo el array de ciudades en localStorage
      if (response.data && Array.isArray(response.data)) {
        localStorage.setItem("ciudades", JSON.stringify(response.data));
        console.log("Ciudades guardadas en localStorage como array");
      } else {
        localStorage.setItem("ciudades", "[]");
        console.warn("No se encontraron ciudades en la respuesta");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
    },
  });
}
