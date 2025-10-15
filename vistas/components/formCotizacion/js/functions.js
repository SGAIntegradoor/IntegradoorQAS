let aseguradorasHogar = [
  { aseguradora: "Allianz", enabled: true },
  { aseguradora: "SBS", enabled: false },
];

let idCotizacionHogar = 1;

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  $(".tooltip-icon-contenidos").tooltip({
    html: true,
    title:
      "<div>" +
      "Indique el valor de los contenidos básicos de la vivienda como muebles, enseres, equipos eléctricos, electrónicos, equipos a gas, ubicados dentro de la vivienda." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-anio-construccion").tooltip({
    html: true,
    title:
      "<div>" +
      "Año en que se construyó la vivienda, debe estar entre 0 y 35 años." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-vlvivienda").tooltip({
    html: true,
    title:
      "<div>" +
      "Indique el valor comercial de la vivienda. Límite máximo $3.000 millones. Riesgos cuyo valor de la vivienda sea superior requiere autorización." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-vlvivienda-SBS").tooltip({
    html: true,
    title:
      "<div>" +
      "Indique el valor comercial de la vivienda. Límite máximo $4.000 millones. Riesgos cuyo valor de la vivienda sea superior requiere autorización." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-hurto").tooltip({
    html: true,
    title:
      "<div>" +
      "Valor de los contenidos básicos a cubrir contra el riesgo de hurto simple y calificado. Tiene un valor mínimo el cual debe ser mayor o igual al 80% del valor de los contenidos. Si no desea incluir esta cobertura registre 0." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-asist-mascotas").tooltip({
    html: true,
    title:
      "<div>" +
      "Amparo adicional que incluye servicios para la mascota asegurada. Se puede asegurar un máximo de dos (2) mascotas por póliza. Se otorga esta asistencia exclusivamente para perros y/o gatos que al momento de su ingreso a la póliza tengan más de 3 meses y no más de 12 años de edad. Algunos de los servicios incluidos son: orientación médica veterinaria, control médico a domicilio, gastos médicos en caso de accidente, auxilio para esterilización, auxilio para servicio de cremación por enfermedad o accidente, entre otros." +
      "</div>",
    placement: "top",
    width: "280px",
  });

  $(".tooltip-todo-riesgo").tooltip({
    html: true,
    title:
      "<div>" +
      "Valor de los contenidos que corresponden a artículos que pueden utilizarse fuera de la vivienda como equipos portátiles, joyas y bicicletas. El valor debe ser inferior al 25% del valor de los contenidos. En caso de emisión de la póliza deberá relacionarlos individualmente. Si no desea incluir esta cobertura registre 0." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });
  $(".tooltip-todo-riesgo-SBS").tooltip({
    html: true,
    title:
      "<div>" +
      "El valor límite por artículo para Todo Riesgo es: $28.166.667. No puede ser superior al 40% del valor total de Total contenidos sustracción + valor asegurado sustracción de Equipo eléctrico y electrónico." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-tipo-construccion").tooltip({
    html: true,
    title:
      "<div>" +
      "- <b>Concreto reforzado:</b> Material similar a la piedra que contiene cemento, arena, grava y estructura de acero.<br/>" +
      "- <b>Mampostería:</b> Sistema de construcción, en su mayoría estructural, de alta tradición, que consiste en sobreponer materiales para   la construcción de muros, pueden utilizarse materiales como piedras, chapas de concreto o bloque de concreto prefabricado, ladrillos y rocas regulares o no regulares.<br/>" +
      "- <b>Acero:</b> Vivienda construida principalmente con este material<br/>" +
      "- <b>Otro:</b> Vivienda construida principalmente con este material." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-tipo-asegurado").tooltip({
    html: true,
    title:
      "<div>" +
      "- <b>Propietario que habita:</b> Puede asegurar el inmueble y los contenidos de la propiedad.<br/>" +
      "- <b>Propietario que arrienda:</b> Puede asegurar el inmueble únicamente.<br/>" +
      "- <b>Deudor:</b> Puede asegurar el inmueble únicamente.<br/>" +
      "- <b>Arrendatario que habita:</b> Puede asegurar los contenidos que posee en el inmueble." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-area").tooltip({
    html: true,
    title:
      "<div>" +
      "Especifique el área total de la vivienda en metros cuadrados" +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-contnorsus").tooltip({
    html: true,
    title:
      "<div>" +
      "El valor de los contenidos normales no puede ser superior al valor de los Muebles y enseres. Valor límite por artículo (normal o especial): $28.166.667." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });
  $(".tooltip-contespesus").tooltip({
    html: true,
    title:
      "<div>" +
      "El valor total de sólo joyas no puede exceder: $60.000.000. El valor de los Contenidos de Sustracción Especiales no puede ser superior al valor de contenidos especiales." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });
  $(".tooltip-totalcontsus").tooltip({
    html: true,
    title:
      "<div>" +
      "Este valor debe ser menor o igual que el Total Contenidos por Cobertura de Incendio y Aliadas. <br/>" +
      "Deducible:10 % de la pérdida mínimo 30 SMDLV" +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-totalcont").tooltip({
    html: true,
    title:
      "<div>" +
      "No puede superar el 60% del valor de la vivienda. <br/>" +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-valorasegdañosEE").tooltip({
    html: true,
    title:
      "<div>" +
      "Equipo eléctrico y electrónico Daños - Deducible: 10 de la pérdida mínimo 30 SMDLV." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-totalcontsushur").tooltip({
    html: true,
    title:
      "<div>" +
      "El valor de Hurto de Equipo EE no puede exceder el valor de Daños de Equipo EE." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-contEnseres").tooltip({
    html: true,
    title:
      "<div>" +
      "Propios de una vivienda familiar (ejemplo: sala, comedor, ropa, utensilios de cocina)" +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-contEE").tooltip({
    html: true,
    title:
      "<div>" +
      "Todos los aparatos eléctricos y electrónicos que estén conectados o listos para ser conectados dentro de la residencia amparada. No puede ser superior al 80% del valor de los contenidos." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $(".tooltip-contEspeciales").tooltip({
    html: true,
    title:
      "<div>" +
      "Objetos de valor como joyas, platería fina, vajillas y objetos de cristal y porcelana, cuadros, tapices, tapetes, alfombras, obras de arte. No puede ser superior al 2% del valor de los contenidos." +
      "</div>",
    placement: "bottom",
    width: "280px",
  });

  $("#tipoAseg").on("change", function () {
    if ($(this).val() == "2") {
      resetInputsValores();
      $("#contenidos").prop("checked", true);
      $("#inputEYC").prop("disabled", true);
      $("#estructura").prop("disabled", true);
      $("#valorVivienda").prop("disabled", true);
      $("#valorVivienda").val("");
      $("#valorViviendaAllianz").prop("disabled", true);
      $("#valorViviendaAllianz").val("");
      $("#preguntaMascotas").css("display", "flex");
    } else if ($(this).val() == "3") {
      resetInputsValores();
      $("#contenidos").prop("checked", false);
      $("#inputEYC").prop("disabled", false);
      $("#estructura").prop("disabled", false);
      $("#valorVivienda").prop("disabled", false);
      $("#valorViviendaAllianz").prop("disabled", false);
      $("#preguntaMascotas").css("display", "flex");
      $("#valorVivienda").val("0");
    } else if ($(this).val() == "1" || $(this).val() == "4") {
      resetInputsValores();
      $("#contenidos").prop("disabled", true);
      $("#inputEYC").prop("disabled", true);
      $("#estructura").prop("checked", true);
      $("#valorVivienda").prop("disabled", false);
      $("#valorViviendaAllianz").prop("disabled", false);
      $("#valorViviendaAllianz").val("");
      $("#preguntaMascotas").css("display", "flex");
      $("#containerValores, #containerValoresAllianz")
        .find("input, select")
        .each(function () {
          if (
            $(this).attr("id") == "valorVivienda" ||
            $(this).attr("id") == "valorViviendaAllianz" ||
            $(this).attr("id") == "dirInmuebleAllianz" ||
            $(this).attr("id") == "dirInmueble"
          ) {
            return;
          } else if (
            $(this).attr("id") == "siSBS" ||
            $(this).attr("id") == "noSBS"
          ) {
            $(this).prop("disabled", false);
          } else {
            $(this).prop("disabled", true);
          }
        });
    } else {
      resetInputsValores();
    }
  });

  let valorVivInputs = [
    "#valorVivienda",
    "#valorViviendaAllianz",
    "#dirInmueble",
    "#dirInmuebleAllianz",
  ];

  $('input[name="tipoCoberturaRadio"]').on("change", function () {
    let seleccionado = $('input[name="tipoCoberturaRadio"]:checked').attr("id"); // Obtiene el ID del

    if (seleccionado == "inputEYC") {
      valorVivInputs.forEach((element) => {
        $(element).prop("disabled", false);
      });
      $(".valores").prop("disabled", false);
    } else if (seleccionado == "estructura") {
      valorVivInputs.forEach((element) => {
        $(element).prop("disabled", false);
      });
      $(".valores").prop("disabled", true);
    } else {
      valorVivInputs.forEach((element) => {
        if (element == "#dirInmueble" || element == "#dirInmuebleAllianz") {
          return;
        } else {
          $(element).prop("disabled", true);
        }
      });
      $(".valores").prop("disabled", false);
    }
  });

  $('input[name="sbsRadio"]').on("change", function () {
    let seleccionado = $(this).attr("id");

    let sbs = aseguradorasHogar.find(
      (element) => element.aseguradora === "SBS"
    );
    if (sbs) sbs.enabled = seleccionado === "siSBS";

    $("#formValores").toggle(seleccionado === "siSBS");
    $("#valorVivienda").val(
      seleccionado === "siSBS" ? $("#valorViviendaAllianz").val() : ""
    );
    $("#btnCotizarSBS").toggle(seleccionado === "noSBS");
  });

  let element = ".inputNumber";
  $(element).numeric();

  valorVivInputs.forEach((element) => {
    $(element).on("change", function () {
      let value = $(this).val();
      if (value > 3000000000) {
        Swal.fire({
          icon: "error",
          title: "¡Atención!",
          text: "El valor de la vivienda no puede ser mayor a $3.000 millones",
        }).then(() => {
          $(this).focus();
          $(this).val("");
        });
      }
    });
  });

  $("#valorViviendaAllianz").on("change", function () {
    let value = $(this).val();
    if (value > 3000000000) {
      Swal.fire({
        icon: "error",
        title: "¡Atención!",
        text: "El valor de la vivienda no puede ser mayor a $3.000 millones",
      }).then(() => {
        $(this).focus();
        $(this).val("");
      });
    }
  });

  $("#tipoVivienda").on("change", function () {
    if ($(this).val() === "1") {
      $("#noPiso, #noPisosEdi")
        .next(".select2-container")
        .find(".select2-selection")
        .css("border", "1px solid #ccc");
      $("#noPiso, #noPisosEdi").addClass("validateDataHogar");
    } else {
      $("#noPiso, #noPisosEdi")
        .next(".select2-container")
        .find(".select2-selection")
        .css("border", "1px solid #ccc");
      $("#noPiso, #noPisosEdi").removeClass("validateDataHogar"); // Opcional, para limpiar si no es "2"
    }
  });

  $(element).on("keypress", function (event) {
    let charCode = event.which ? event.which : event.keyCode;

    if (charCode < 48 || charCode > 57) {
      event.preventDefault();
    }
  });

  $(element).on("input", function () {
    $(this).val(
      $(this)
        .val()
        .replace(/[^0-9]/g, "")
    );
  });

  $(element).on("blur", function () {
    let value = $(this).val();

    if (value !== "") {
      // Verificar si ya tiene formato con puntos
      if (!/^\d{1,3}(\.\d{3})*$/.test(value)) {
        let formattedValue = new Intl.NumberFormat("es-CO", {
          useGrouping: true,
          minimumFractionDigits: 0,
        }).format(parseInt(value.replace(/\./g, ""), 10)); // Quitar puntos antes de formatear

        $(this).val(formattedValue);
      }
    }
  });

  $("#contenidos").on("change", function () {
    if ($(this).is(":checked")) {
      $("#valorEnseres").prop("disabled", false);
      $("#valorVivienda").prop("disabled", true);
      $("#valorEquipoElectrico").prop("disabled", false);
      $("#valorCEspeciales").prop("disabled", false);
      $("#valorHurto").prop("disabled", false);
      $("#valorTodoRiesgo").prop("disabled", false);
      $("#asistMascotas").prop("disabled", false);
    }
  });

  $("#valorHurto, #valorHurtoAllianz").on("change", function () {
    if ($(this).attr("id") == "valorHurtoAllianz") {
      if (
        $("#valorContenidosAllianz").val() == "0" ||
        $("#valorContenidosAllianz").val() == ""
      ) {
        $("#siGato").prop("disabled", true);
        $("#siPerro").prop("disabled", true);
        $("#no").prop("disabled", true);
        Swal.fire({
          icon: "error",
          title: "¡Atención!",
          text: "Debe ingresar el valor de los contenidos",
        }).then(() => {
          $(this).val("");
          $("#valorContenidosAllianz").focus();
        });
      } else {
        $("#siGato").prop("disabled", false);
        $("#siPerro").prop("disabled", false);
        $("#no").prop("disabled", false);
        return;
      }
    } else {
      if (
        $("#contentNormalesSUS").val() == "0" ||
        $("#contentNormalesSUS").val() == ""
      ) {
        Swal.fire({
          icon: "error",
          title: "¡Atención!",
          text: "Debe ingresar el valor de los contenidos",
        }).then(() => {
          $(this).val("");
          $("#contentNormalesSUS").focus();
        });
      } else {
        return;
      }
    }
  });

  function validateTodoRiesgo(input, type) {
    let valorTRiesgo = parseInt($(`#${input}`).val().replace(/\./g, ""), 10);
    let valorContenidos = parseInt(
      $("#valorContenidosAllianz").val().replace(/\./g, ""),
      10
    );
    if (input == "valorTodoRiesgo") {
      valorContenidos = parseInt(
        $("#totalContenidos").val().replace(/\./g, ""),
        10
      );
    }
    if (type) {
      if ($(`#${input}`).attr("id") == "valorTodoRiesgoAllianz") {
        let valorHurtoAllianz = $("#valorHurtoAllianz").val();
        if (valorHurtoAllianz <= 0 || valorHurtoAllianz === "") {
          Swal.fire({
            icon: "error",
            title: "¡Atención!",
            text: "Debe ingresar valor de hurto",
          }).then(() => {
            $("#valorHurtoAllianz").focus();
          });
          $("#siGato").prop("disabled", true);
          $("#siPerro").prop("disabled", true);
          $("#no").prop("disabled", true);
        } else {
          $(`#${input}`).css("border", "1px solid #ccc");
          $("#siGato").prop("disabled", false);
          $("#siPerro").prop("disabled", false);
          $("#no").prop("disabled", false);
        }
      } else {
        let valorHurto = $("#valorHurto").val();
        if (valorHurto <= 0 || valorHurto === "") {
          Swal.fire({
            icon: "error",
            title: "¡Atención!",
            text: "Debe ingresar valor de hurto",
          }).then(() => {
            $("#valorHurto").focus();
            $(`#${input}`).val("");
          });
        } else {
          $(`#${input}`).css("border", "1px solid #ccc");
        }
      }
    } else {
      if (
        valorTRiesgo > valorContenidos ||
        valorTRiesgo > valorContenidos * 0.25
      ) {
        Swal.fire({
          icon: "error",
          title: "¡Atención!",
          text: "El Valor hurto debe ser inferior al 25% del valor de los contenidos.",
        }).then(() => {
          $(`#${input}`).css("border", "1px solid red");
          $(`#${input}`).focus();
          $(`#${input}`).val("");
        });
      } else {
        $(`#${input}`).css("border", "1px solid #ccc");
      }
    }
  }

  $("#valorTodoRiesgoAllianz").on("change", function () {
    let valor = parseInt($(this).val().replace(/\./g, ""), 10) || 0;
    if (valor === 0 || valor === "0") {
      validateTodoRiesgo($(this).attr("id"), true);
    } else if (valor > 0) {
      validateTodoRiesgo($(this).attr("id"), false);
    } else {
      $("#siGato").prop("disabled", false);
      $("#siPerro").prop("disabled", false);
      $("#no").prop("disabled", false);
    }
  });

  function resetInputsValores() {
    const fields = [
      "#valorVivienda",
      "#valorViviendaAllianz",
      "#valorHurtoAllianz",
      "#valorTodoRiesgoAllianz",
      "#valorContenidosAllianz",
      "#valorEnseres",
      "#valorEquipoElectrico",
      "#valorCEspeciales",
      "#contenidos",
      "#hurto",
      "#valorTodoRiesgo",
      "#asistMascotas",
      "#inputEYC",
      "#estructura",
      "#valorAsegSusEE",
      "#valorHurto",
      "#totalContNormales",
      "#contEspeciales",
      "#siGato",
      "#siPerro",
      "#no",
      "#preguntaMascotas",
      "#siSBS",
      "#noSBS",
    ];

    fields.forEach((element) => {
      if (element == "#siSBS" || element == "#noSBS") {
        $(`${element}`).prop("checked", false);
        $(`${element}`).prop("disabled", false);
      }
      $(`${element}`).val("");
      $(`${element}`).prop("disabled", false);
    });
  }

  function resetFieldsAsegurado() {
    //$("#noDocumento").val("");
    $("#correo").val("");
    $("#nombre").val("");
    $("#apellidos").val("");
    $(".digito").val("");
    $("#nacionalidad").val("").trigger("change");
    $("#pNacimiento").val("").trigger("change");
    $(".razon").val("");
    $(".celular").val("");
    $(".tipoDocumento")
      .next(".select2-container")
      .find(".select2-selection")
      .css("border", "1px solid #ccc");
    $("#noDocumento").css("border", "1px solid #ccc");
    $("#nombre").css("border", "1px solid #ccc");
    $("#apellidos").css("border", "1px solid #ccc");
    $(".correo").css("border", "1px solid #ccc");
    $(".celular").css("border", "1px solid #ccc");
    $(".digito").css("border", "1px solid #ccc");
    $(".razon").css("border", "1px solid #ccc");
    $(`#nacionalidad1`)
      .next(".select2-container")
      .find(".select2-selection")
      .css("border", "1px solid #ccc");
    $("#pNacimiento1")
      .next(".select2-container")
      .find(".select2-selection")
      .css("border", "1px solid #ccc");
  }

  function changesInputs(type) {
    switch (type) {
      case "CC":
        $("#nombreCompleto").css("display", "block");
        $("#digito").css("display", "none");
        $("#razon").css("display", "none");
        $("#nacionalidad").css("display", "none");
        $("#pNacimiento").css("display", "none");
        break;
      case "NIT":
        $("#nombreCompleto").css("display", "none");
        $("#digito").css("display", "block");
        $("#razon").css("display", "block");
        $("#nacionalidad").css("display", "none");
        $("#pNacimiento").css("display", "none");
        break;
      case "CE":
        $("#nombreCompleto").css("display", "block");
        $("#digito").css("display", "none");
        $("#razon").css("display", "none");
        $("#nacionalidad").css("display", "block");
        $("#pNacimiento").css("display", "block");
        break;
      default:
        break;
    }
  }

  $("#tipoDocumento").on("change", function () {
    let val = $(`#tipoDocumento option:selected`).text();
    resetFieldsAsegurado();
    changesInputs(val);
  });

  $("#noDocumento").change(function () {
    consultarAsegurado();
  });

  $("#tipoVivienda").on("change", function () {
    if ($(this).val() == "2" || $(this).val() == "3") {
      $("#noPiso").prop("disabled", true);
      $("#noPisosEdi").prop("disabled", true);
    } else {
      $("#noPiso").prop("disabled", false);
      $("#noPisosEdi").prop("disabled", false);
    }
  });

  $("#valorHurtoAllianz").on("change", function () {
    let valorHurto = parseInt($(this).val().replace(/\./g, ""), 10);
    let valorContenidos = parseInt(
      $("#valorContenidosAllianz").val().replace(/\./g, ""),
      10
    );
    if (valorHurto > valorContenidos || valorHurto < valorContenidos * 0.8) {
      Swal.fire({
        icon: "error",
        title: "¡Atención!",
        text: "El valor de hurto debe estar entre el 80% y el 100% del valor de los contenidos",
      }).then(() => {
        $("#valorTodoRiesgoAllianz").val("");
        $(this).css("border", "1px solid red");
        $(this).focus();
        $(this).val("");
      });
    } else {
      $(this).css("border", "1px solid #ccc");
      $("#valorTodoRiesgoAllianz").val("");
      $("#valorTodoRiesgoAllianz").prop("disabled", false);
    }
  });

  let ciudadesZonaDeRiesgo = [
    "05001",
    "05154",
    "05495",
    "05790",
    "05854",
    "17001",
    "23417",
    "23675",
    "66001",
    "70124",
    "70265",
    "70678",
  ];

  $("#ciudadInmueble").on("change", function () {
    let val = $(this).val();
    let zonaRiesgo = $("#zonaRiesgo");
    let loader = $("#loaderZonaRiesgo"); // Referencia al loader
    let loaderSubZona = $("#loaderSubZona");
    let subZonaBog = $("#subZoneBog");
    subZonaBog.hide();
    if (ciudadesZonaDeRiesgo.includes(val)) {
      $("#btnDataHogarSiguiente").prop("disabled", true);
      zonaRiesgo.empty(); // Limpiar opciones anteriores
      loader.show(); // Mostrar el spinner dentro del select
      zonaRiesgo.prop("disabled", false);

      fetch("https://grupoasistencia.com/motor_webservice/zonaRiesgoHogarena", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ ciudad: val }),
      })
        .then((response) => response.json())
        .then((data) => {
          loader.hide(); // Ocultar el spinner
          $("#btnDataHogarSiguiente").prop("disabled", false);
          if (
            !data.ObtenerZonaRiesgoCiudadResult ||
            !data.ObtenerZonaRiesgoCiudadResult.InformacionZonaType
          ) {
            zonaRiesgo.append(
              "<option disabled selected>No hay datos</option>"
            );
            console.warn("No se encontraron datos de zona de riesgo.");
            return;
          }

          let { InformacionZonaType } = data.ObtenerZonaRiesgoCiudadResult;
          if (!Array.isArray(InformacionZonaType)) {
            console.error(
              "InformacionZonaType no es un array:",
              InformacionZonaType
            );
            return;
          }

          zonaRiesgo.addClass("validateDataHogar");
          zonaRiesgo.append(
            '<option value="0" selected>Seleccione una opción</option>'
          );
          InformacionZonaType.forEach((element) => {
            zonaRiesgo.append(
              `<option value="${element.CodZona}">${element.DescripcionZona}</option>`
            );
          });
        })
        .catch((error) => {
          loader.hide(); // Ocultar el spinner en caso de error
          zonaRiesgo.empty();
          zonaRiesgo.append(
            "<option disabled selected>Error al cargar</option>"
          );
          console.error("Error en la petición:", error);
        });
    } else {
      if (val == "11001") {
        $("#subZoneBog").show();
        $("#btnDataHogarSiguiente").prop("disabled", true);
        $("#subZona").empty(); // Limpiar opciones anteriores
        loaderSubZona.show(); // Mostrar el spinner dentro del select

        fetch("https://grupoasistencia.com/motor_webservice/subZonaSBS", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ ciudad: val }),
        })
          .then((response) => response.json())
          .then((data) => {
            loaderSubZona.hide(); // Ocultar el spinner
            $("#btnDataHogarSiguiente").prop("disabled", false);
            if (
              !data.ObtenerSubZonaCiudadResult ||
              !data.ObtenerSubZonaCiudadResult.InformacionZonaType
            ) {
              $("#subZonaBog").append(
                "<option disabled selected>No hay datos</option>"
              );
              console.warn("No se encontraron datos de zona de riesgo.");
              return;
            }

            let { InformacionZonaType } = data.ObtenerSubZonaCiudadResult;
            if (!Array.isArray(InformacionZonaType)) {
              console.error(
                "InformacionZonaType no es un array:",
                InformacionZonaType
              );
              return;
            }

            $("#subZona").addClass("validateDataHogar");
            $("#subZona").append(
              '<option value="0" selected>Seleccione una opción</option>'
            );
            InformacionZonaType.forEach((element) => {
              $("#subZona").append(
                `<option value="${element.CodZona}">${element.DescripcionZona}</option>`
              );
            });
          })
          .catch((error) => {
            loaderSubZona.hide(); // Ocultar el spinner en caso de error
            $("#subZona").empty();
            $("#subZona").append(
              "<option disabled selected>Error al cargar</option>"
            );
            console.error("Error en la petición:", error);
          });
        loaderSubZona.show();
      } else {
        $("#subZoneBog").hide();
        $("#subZona").removeClass("validateDataHogar");
      }

      zonaRiesgo.empty(); // Limpiar opciones anteriores
      zonaRiesgo.removeClass("validateDataHogar");
      zonaRiesgo.prop("disabled", true);
      zonaRiesgo.append(
        '<option value="0" selected>Sin zonas de riesgo</option>'
      );

      $("#subZona").hide();
      $("#subZona")
        .next(".select2-container")
        .find(".select2-selection")
        .css("border", "1px solid #ccc");
    }
  });

  function consultarAsegurado() {
    var tipoDocumentoID = document.getElementById("tipoDocumento").value;
    var numDocumentoID = document.getElementById("noDocumento");
    $.ajax({
      type: "POST",
      url: "src/consultarAsegurado.php",
      dataType: "json",
      data: {
        tipoDocumento: tipoDocumentoID,
        numDocumento: numDocumentoID.value,
      },
      success: function (data) {
        var estado = data.estado;
        let documentCli = data.cli_num_documento;
        if (estado && data.id_tipo_documento == 2) {
          $("#idCliente").val(data.id_cliente);
          $("#tipoDocumento").val(" ").trigger("change");
          $(".razon").val(data.cli_nombre + " " + data.cli_apellidos);
          $(".digito").val(data.digitoVerificacion); // Último dígito
          numDocumentoID.value = documentCli;
        } else if (estado) {
          $("#idCliente").val(data.id_cliente);
          $("#tipoDocumento")
            .val(
              data.id_tipo_documento == 1
                ? "C"
                : data.id_tipo_documento == 2
                ? " "
                : "X"
            )
            .trigger("change");
          $("#noDocumento").val(data.cli_num_documento);
          $("#nombre").val(data.cli_nombre);
          $("#apellidos").val(data.cli_apellidos);
          $(".correo").val(data.cli_email);
          $(".celular").val(data.cli_telefono);
          // Adjuntar correo y número
        } else {
          // console.log("por aqui")
          $("#idCliente").val("");
          //$("#tipoDocumento").val("0").trigger("change");
          $("#noDocumento").val(numDocumentoID.value);
          $("#nombre").val("");
          $("#apellidos").val("");
          $(".correo").val("");
          $(".celular").val("");
          $(".razon").val("");
          $(".digito").val("");
          //console.log(data.mensaje);
        }
      },
    });
  }

  $("#noDocumento").numeric();

  let params = urlPage.searchParams.getAll("idCotizacion");
  if (params.length <= 0) {
    $("#tipoDocumento").change(function () {
      $("#noDocumento").val("");
      if ($(this).val() == " ") {
        $("#noDocumento").attr("maxlength", "9");
      } else {
        $("#noDocumento").attr("maxlength", "10");
      }
    });
  }
});

let mascotaSeleccionada = "";

$('input[name="mascotasRadio"]').on("change", function () {
  let seleccionado = $('input[name="mascotasRadio"]:checked').attr("id");
  if (seleccionado == "siGato") {
    mascotaSeleccionada = "GA";
  } else if (seleccionado == "siPerro") {
    mascotaSeleccionada = "PE";
  } else if (seleccionado == "no") {
    mascotaSeleccionada = "NO";
  }
});

$("#valorVivienda").on("change", function () {
  // let valorVivienda = parseInt($(this).val().replace(/\./g, ""), 10);
  // $("#totalCoberturaBasicas").val(valorVivienda);
  actualizarTotalCoberturaBasica();
});

// Validaciones de campos SBS BEGIN

$("#valorEnseres, #valorEquipoElectrico, #valorCEspeciales").each(function () {
  $(this).data("prevValue", 0); // Inicializa los valores previos en 0
});

$("#valorEnseres, #valorEquipoElectrico, #valorCEspeciales").on(
  "change",
  function () {
    actualizarTotalContenidos();
  }
);

$("#valorEquipoElectrico").on("change", function () {
  let valorEE = parseInt($(this).val().replace(/\-/g, ""), 10) || 0;
  $("#valorAsegSUSEE").val(valorEE.toLocaleString("es-ES"));
});

$("#contEspecialesSUS, #contentNormalesSUS").on("change", function () {
  actualizarTotalContSUS();
});

function actualizarTotalContenidos() {
  // // debugger
  let valorEnseres =
    parseInt($("#valorEnseres").val().replace(/\./g, ""), 10) || 0;
  let valorEquipoElectrico =
    parseInt($("#valorEquipoElectrico").val().replace(/\./g, ""), 10) || 0;
  let valorContenidosEsp =
    parseInt($("#valorCEspeciales").val().replace(/\./g, ""), 10) || 0;

  let totalContent = valorEnseres + valorEquipoElectrico + valorContenidosEsp;

  $("#totalContenidos").val(totalContent.toLocaleString("es-ES"));

  let espeContents = parseInt($("#valorCEspeciales").val(), 10) || 0;
  let _op = valorEnseres.toLocaleString("es-ES");
  if (_op == "NaN" || _op == "undefined" || _op == "") {
    _op = 0;
  }
  let _op2 = espeContents.toLocaleString("es-ES");

  $("#contentNormalesSUS").val(_op).trigger("change");
  $("#contEspecialesSUS").val(_op2).trigger("change");
  actualizarTotalCoberturaBasica();
}

$("#valorEnseres").on("change", function () {
  let prevValue = $(this).data("prevValue") || 0;
  let newValue = parseInt($(this).val().replace(/\./g, ""), 10) || 0;

  // let totalContent =
  //   parseInt($("#totalContenidos").val().replace(/\./g, ""), 10) || 0;
  // totalContent = totalContent - prevValue + newValue;

  // $("#totalContenidos").val(totalContent.toLocaleString("es-ES"));
  $(this).data("prevValue", newValue);
});

$("#valorEquipoElectrico").on("change", function () {
  let prevValue = $(this).data("prevValue") || 0;
  let newValue = parseInt($(this).val().replace(/\./g, ""), 10) || 0;
  let valorEnseres =
    parseInt($("#valorEnseres").val().replace(/\./g, ""), 10) || 0;
  let porcentaje80 = (newValue + valorEnseres) * 0.8;

  if (newValue > porcentaje80) {
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "El valor de Equipo Eléctrico debe estar entre el 80% y el 100% del valor de los contenidos",
    }).then(() => {
      $(this).css("border", "1px solid red");
      $(this).focus();
      $(this).val("");
      actualizarTotalContenidos(); // Restablece el total si se vacía el campo
    });
  } else {
    // let totalContent =
    //   parseInt($("#totalContenidos").val().replace(/\./g, ""), 10) || 0;
    // totalContent = totalContent - prevValue + newValue;

    // $("#totalContenidos").val(totalContent.toLocaleString("es-ES"));
    $("#valorAseguradoD").val(newValue.toLocaleString("es-ES"));
    $(this).data("prevValue", newValue);
    $(this).css("border", "1px solid #ccc");
  }
});

$("#valorCEspeciales").on("change", function () {
  let newValue = parseInt($(this).val().replace(/\./g, ""), 10) || 0;
  let valorEnseres =
    parseInt($("#valorEnseres").val().replace(/\./g, ""), 10) || 0;
  let valorEquipoElectrico =
    parseInt($("#valorEquipoElectrico").val().replace(/\./g, ""), 10) || 0;

  let porcentaje2 = (newValue + valorEnseres + valorEquipoElectrico) * 0.02;

  if (newValue > porcentaje2) {
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "Valor contenidos especiales no puede ser superior al 2% del valor de los contenidos",
    }).then(() => {
      $(this).css("border", "1px solid red");
      $(this).focus();
      $(this).val("");
      actualizarTotalContenidos(); // Restablece el total si se vacía el campo
    });
  } else {
    $(this).data("prevValue", newValue);
    $(this).css("border", "1px solid #ccc");
  }
});

function actualizarTotalCoberturaBasica() {
  // // debugger
  let totalContenidos =
    parseInt($("#totalContenidos").val().replace(/\./g, ""), 10) || 0;
  let valorVivienda =
    parseInt($("#valorVivienda").val().replace(/\./g, ""), 10) || 0;

  let totalCoberturaBasica = totalContenidos + valorVivienda;
  $("#totalCoberturaBasica").val(totalCoberturaBasica.toLocaleString("es-ES"));
}

$("#contentNormalesSUS").on("change", function () {
  let valorContNormSUS = parseInt($(this).val().replace(/\./g, ""), 10) || 0;
  let valorContNormEnseres =
    parseInt($("#valorEnseres").val().replace(/\./g, ""), 10) || 0;
  // if (valorContNormEnseres == 0) {
  //   Swal.fire({
  //     icon: "error",
  //     title: "¡Atención!",
  //     text: "Debe ingresar el valor de Contenidos (muebles y enseres)",
  //   }).then(() => {
  //     $("#valorEnseres").focus();
  //     $("#valorEnseres").css("border", "1px solid red");
  //     $(this).val("");
  //     actualizarTotalContSUS(); // Restablece el total si se vacía el campo
  //   });
  // } else {
  if (valorContNormSUS > valorContNormEnseres) {
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "El valor de Contenidos Normales por sustracción no puede ser mayor al valor Contenidos de Enseres",
    }).then(() => {
      $(this).css("border", "1px solid red");
      $(this).focus();
      $(this).val("");
      actualizarTotalContSUS();
    });
  } else {
    $(this).css("border", "1px solid #ccc");
  }
  // }
});

$("#contEspecialesSUS").on("change", function () {
  let contEspecialesSUS = parseInt($(this).val().replace(/\./g, ""), 10) || 0;
  let valorCEspeciales =
    parseInt($("#valorCEspeciales").val().replace(/\./g, ""), 10) || 0;
  // if (valorCEspeciales == 0) {
  //   Swal.fire({
  //     icon: "error",
  //     title: "¡Atención!",
  //     text: "Debe ingresar el valor de Contenidos (Especiales)",
  //   }).then(() => {
  //     $("#valorCEspeciales").css("border", "1px solid red");
  //     $("#valorCEspeciales").focus();
  //     $(this).val("");
  //     actualizarTotalContSUS(); // Restablece el total si se vacía el campo
  //   });
  // } else {
  if (contEspecialesSUS > valorCEspeciales) {
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "El valor de Contenidos Especiales por sustracción no puede ser mayor al valor Contenidos de Especiales",
    }).then(() => {
      $(this).css("border", "1px solid red");
      $(this).focus();
      $(this).val("");
      actualizarTotalContSUS();
    });
  } else {
    $(this).css("border", "1px solid #ccc");
  }
});

$("#valorAsegSUSEE").on("change", function () {
  let valorContEESUS = parseInt($(this).val().replace(/\./g, ""), 10) || 0;
  let valorEquipoElectrico =
    parseInt($("#valorEquipoElectrico").val().replace(/\./g, ""), 10) || 0;
  if (valorEquipoElectrico == 0) {
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "Debe ingresar el valor de Contenidos (Electronicos y Electricos)",
    }).then(() => {
      $(this).val("");
      $("#valorEquipoElectrico").focus();
      $("#valorEquipoElectrico").css("border", "1px solid red");
      actualizarTotalContSUS();
    });
  } else {
    if (valorContEESUS > valorEquipoElectrico) {
      Swal.fire({
        icon: "error",
        title: "¡Atención!",
        text: "El valor de Contenidos Electronicos o Electricos por sustracción no puede ser mayor al valor de Contenidos (Electronicos y Electricos)",
      }).then(() => {
        $(this).css("border", "1px solid red");
        $(this).focus();
        $(this).val("");
        actualizarTotalContSUS();
      });
    } else {
      $(this).css("border", "1px solid #ccc");
    }
  }
});

// 166075000

$("#valorTodoRiesgo").on("change", function () {
  let valorTRiesgo = parseInt($(this).val().replace(/\./g, ""), 10) || 0;

  if (valorTRiesgo > 166075000) {
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "El valor de Todo Riesgo no puede ser mayor a $166.075.000",
    }).then(() => {
      $(this).css("border", "1px solid red");
      $(this).focus();
      $(this).val("");
    });
  } else {
    $(this).css("border", "1px solid #ccc");
  }
});

function actualizarTotalContSUS() {
  let contEspecialesSUS =
    parseInt($("#contEspecialesSUS").val().replace(/\./g, ""), 10) || 0;
  let contentNormalesSUS =
    parseInt($("#contentNormalesSUS").val().replace(/\./g, ""), 10) || 0;

  let totalContHurtoSus = contEspecialesSUS + contentNormalesSUS;
  $("#totalContHurtoSus").val(totalContHurtoSus.toLocaleString("es-ES"));
}

function setBlankInputs() {
  $("#containerValores")
    .find("input")
    .each(function () {
      let val = $(this).val();
      if (val == "") {
        $(this).val("0");
      }
    });
}

// Validaciones de campos SBS END

function appendSectionAlerts() {
  $("#resumenCotizaciones").toggle();
}

function saveQuotation() {
  dataCotizacion.idCliente = $("#idCliente").val();
  dataCotizacion.zona_riesgo =
    $("#zonaRiesgo option:selected").text() !== ""
      ? "Sin zonas de riesgo"
      : $("#zonaRiesgo option:selected").text();
  dataCotizacion.departamento = $("#deptoInmueble option:selected").text();
  dataCotizacion.ciudad = $(".ciudadInmueble option:selected").text();
  dataCotizacion.idUsuario = permisos.id_usuario;
  $.ajax({
    type: "POST",
    url: "src/saveQuotationHogar.php",
    dataType: "json",
    data: dataCotizacion,
    cache: false,
    success: function (data) {
      console.log("Guardado");
      idCotizacionHogar = data.last_id;
    },
    catch: function (error) {
      console.log(error);
      console.log("Error");
    },
  });
}

// Guarda la alerta en la BD para mostrar la tabla una vez se realice la pagina de retoma

function saveAlert(data) {
  // debugger;
  let dataCotizacion = {
    cotizacion: idCotizacionHogar,
    aseguradora: data.aseguradora,
    mensajes: data.message,
    ofertas: data.data.length ?? 0,
    cotizo: data.success ? 1 : 0,
  };

  $.ajax({
    type: "POST",
    url: "src/guardarAlerta.php",
    dataType: "json",
    data: dataCotizacion,
    cache: false,
    success: function (data) {
      console.log("Guardado");
    },
    catch: function (error) {
      console.log(error);
      console.log("Error");
    },
  });
}

function saveOffert(data) {
  // debugger;
  let ofertas = [];

  data.data.map((element, i) => {
    if (data.aseguradora == "Allianz") {
      let cob_rce_prop_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 2
      )
        ? "800.000.000"
        : "No ampara";
      let cob_inc_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 1
      )
        ? "Deducible: 2% min 2 SMMLV"
        : "No ampara";
      let cob_terr_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 5
      )
        ? "Deducible: 2% min 2 SMMLV"
        : "No ampara";
      let cob_asist_jur_alz = "Si ampara";
      let cob_asist_dom_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 3
      )
        ? "Si ampara"
        : "No ampara";
      let cob_hamccp_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 8
      )
        ? "Si ampara"
        : "No ampara";
      let cob_danos_agua_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 7
      )
        ? "Si ampara"
        : "No ampara";
      let cob_eve_nat_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 4
      )
        ? "Si ampara"
        : "No ampara";
      let cob_rce_fam_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 9
      )
        ? "Si ampara"
        : "No ampara";
      let cob_eve_elec_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 10
      )
        ? "Si ampara"
        : "No ampara";
      let cob_hur_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 11
      )
        ? "Mínimo 80% del valor de contenidos. Deducible 1 SMMLV"
        : "No ampara";
      let cob_tr_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 12
      )
        ? "Máximo 25% del valor de contenidos. Deducible 1 SMMLV"
        : "No ampara";
      let cob_asis_mas_alz = element.coberturas.find(
        (coverage) => coverage.id_amparo == 13
      )
        ? "Si ampara"
        : "No ampara";

      let oferta = {
        id_cotizacion: idCotizacionHogar,
        aseguradora: data.aseguradora,
        valor_prima: element.valorPrima.replace(/[^0-9]/g, ""),
        producto: element.producto,
        id_cot_aseguradora: data.numeroCotizacion,
        cob_inc_alz: cob_inc_alz,
        cob_terr_alz: cob_terr_alz,
        cob_rce_prop_alz: cob_rce_prop_alz,
        cob_asist_jur_alz: cob_asist_jur_alz,
        cob_asist_dom_alz: cob_asist_dom_alz,
        cob_hamccp_alz: cob_hamccp_alz,
        cob_danos_agua_alz: cob_danos_agua_alz,
        cob_eve_nat_alz: cob_eve_nat_alz,
        cob_rce_fam_alz: cob_rce_fam_alz,
        cob_eve_elec_alz: cob_eve_elec_alz,
        cob_hur_alz: cob_hur_alz,
        cob_tr_alz: cob_tr_alz,
        cob_asis_mas_alz: cob_asis_mas_alz,
      };
      ofertas.push(oferta);
    } else if (data.aseguradora == "SBS") {
      let oferta = {
        id_cotizacion: idCotizacionHogar,
        aseguradora: data.aseguradora,
        producto: element.producto,
        valor_prima: element.valorPrima.split(".")[0],
        id_cot_aseguradora: data.numeroCotizacion,
        cob_terr_ev_nat_sbs: "Deducible: 2 % de la pérdida mínimo 60 SMDLV",
        cob_hur_con_no_ele_sbs: "Deducible: 10 % de la pérdida mínimo 30 SMDLV",
        cob_hur_con_ele_sbs: "Deducible: 10 % de la pérdida mínimo 20 SMDLV",
        cob_tr_sbs: "Deducible: 5% de la pérdida mínimo 20 SMDLV",
        cob_acci_pers_sbs: "No ampara",
        cob_resp_civil_sbs: "Hasta $195.000.000. Deducible 5 SMDLV",
        cob_asist_dom_sbs: "Si ampara",
        cob_prod_plus_sbs: "No ampara",
        pdf_sbs: element.PDF,
      };
      ofertas.push(oferta);
    }
  });

  for (let i = 0; i < ofertas.length; i++) {
    $.ajax({
      type: "POST",
      url: "src/saveOffert.php",
      dataType: "json",
      data: ofertas[i],
      cache: false,
      success: function (data) {
        console.log("Guardado");
      },
      catch: function (error) {
        console.log(error);
        console.log("Error");
      },
    });
  }
}

function disableInputsData(container) {
  $(container)
    .find("input, select")
    .each(function () {
      $(this).prop("disabled", true);
    });
}

function disableButton(button) {
  $(button).prop("disabled", true);
}

function cotizar(body) {
  setBlankInputs();
  if (!validarMascotasSeleccionado()) {
    return;
  } else {
    disableButton("#btnCotizarSBS");
    disableButton("#btnCotizar");
    appendSectionAlerts();
    toggleContainerValoresAllianz();
    toggleContainerValoresSBS();
    makeATable();
    saveQuotation();
    toggleContainerCards();

    if ($("input[name='sbsRadio']:checked").length === 0) {
      $("#noSBS").prop("checked", true);
    }

    let promisesHogar = [];

    let requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: "",
    };
    // debugger;
    aseguradorasHogar.forEach((element) => {
      if (!element.enabled) return; // Si la aseguradora está deshabilitada, saltarla

      let url = "";
      let requestBody = null;

      if (element.aseguradora === "Allianz") {
        disableInputsData("#containerValoresAllianz");
        requestBody = body.allianz;
        url = `https://grupoasistencia.com/backend_node/WSAllianz/QuotationAllianzHogar`;
      } else if (element.aseguradora === "SBS") {
        disableInputsData("#containerValores");
        requestBody = body.sbs;
        url = `https://www.grupoasistencia.com/motor_webservice/Hogarena`;
      }

      if (url && requestBody) {
        requestOptions.body = JSON.stringify(requestBody);
        let promise = fetch(url, requestOptions)
          .then(async (response) => {
            let result = await response.json();
            if (!response.ok) {
              saveRequest(
                requestBody,
                "Error en la petición al WebService, por favor intente de nuevo"
              );
              $(`#${element.aseguradora}-check`).html(
                `<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 5px;"></i>`
              );
              $(`#${element.aseguradora}-offerts`).html("0");
              $(`#${element.aseguradora}-observations`).html(
                "Error en la petición al WebService, por favor intente de nuevo"
              );
              let resp = {
                aseguradora: element.aseguradora,
                message:
                  "Error en la petición al WebService, por favor intente de nuevo",
                data: [],
                success: false,
              };
              saveAlert(resp);
              throw new Error("Error en la petición al WebService");
            }
            return result; // Convierte la respuesta a JSON igualmentep
          })
          .then((result) => {
            if (result.status != "200") {
              // debugger;
              console.log(element.aseguradora);
              let errorsConcat = result?.error?.errors?.map(
                (error) => error?.message
              );

              let errorMessage =
                errorsConcat.length > 1
                  ? errorsConcat.join(",")
                  : errorsConcat[0];
              saveRequest(requestBody, result);
              $(`#${element.aseguradora}-check`).html(
                `<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 5px;"></i>`
              );
              $(`#${element.aseguradora}-offerts`).html("0");
              $(`#${element.aseguradora}-observations`).html(
                "Cotización Fallida: " + errorMessage
              );
              saveAlert(result);
            } else {
              saveRequest(requestBody, result);
              $(`#${element.aseguradora}-check`).html(
                `<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>`
              );
              $(`#${element.aseguradora}-offerts`).html(
                `${element.aseguradora == "SBS" ? 1 : result?.data?.length}`
              );
              $(`#${element.aseguradora}-observations`).html(
                `Cotización exitosa`
              );
              saveOffert(result);
              saveAlert(result);
              makeCards(result);
            }
          });
        promisesHogar.push(promise);
      }
    });

    // Esperar que todas las promesas terminen
    Promise.all(promisesHogar)
      .then(() => {
        Swal.fire({
          icon: "success",
          title: "¡Cotización exitosa!",
          text: "Proceso de cotización finalizado",
          didOpen: () => {
            document.querySelector(".swal2-container").style.zIndex = "1059";
          },
        });
      })
      .catch((error) => console.log(error));
  }
}

function validarMascotasSeleccionado() {
  let radios = $("input[name='mascotasRadio']");
  let tipoAsegurado = $("#tipoAsegurado").val();
  let seleccionado = radios.is(":checked");

  if (!seleccionado && (tipoAsegurado == "2" || tipoAsegurado == "3")) {
    // Aplicar borde rojo a cada radio input
    radios.css({
      outline: "2px solid red",
      "border-radius": "50%",
    });
    Swal.fire({
      icon: "warning",
      title: "¡Atención!",
      text: "Debe seleccionar al menos una opción en el grupo de asistencia para mascotas.",
    });
    window.location.href = "#preguntaMascotas";
  } else {
    // Restaurar estilos si ya se seleccionó una opción
    seleccionado = true;
    radios.css("outline", "none");
  }

  return seleccionado;
}

function makeCards(data, type = null) {
  let cardCotizacion = "";
  if (type == 2) {
    data.forEach((element) => {
      if (element.aseguradora == "Allianz") {
        cardCotizacion = `
        <div class="col-cards-12">
        <div class="card-ofertas">
            <div class="row card-body">
                <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo" style="padding-top: 50px;">
                    <center>
                        <img src="vistas/img/logos/allianz.png" />
                    </center>
                    <div class='col-12' style='margin-top:2%;'>
                       ${
                         permisos.Vernumerodecotizacionencadaaseguradora == "x"
                           ? `<center>
                           <label class='entidad'>N° Cot: <span style='color:black'>${element.id_cot_aseguradora}</span></label>
                         </center>`
                           : ""
                       }
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit" style="padding-top: 50px;">
                    <h5 class="entidad" style="font-size: 15px"><b>${
                      element.aseguradora
                    } - ${element.producto}</b></h5>
                    <h5 class="precio" style="">Desde ${parseInt(
                      element.valor_prima
                    ).toLocaleString("es-ES")} COP</h5>
                    <p class="title-precio" style="font-weight: bold;">IVA incluido</p>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-8" style="padding-top: 30px; padding-bottom: 30px;">
                    <div class="informativeTable">
                        <div style="width: 274px;" class="tab1Table">
                            <ul style="padding-left: 25px; ">
                              <li>Incendio - ${element.cob_inc_alz}</li>
                              <li>Terremoto - ${element.cob_terr_alz}</li>
                              <li>RCE Propiedad - ${
                                element.cob_rce_prop_alz
                              }</li>
                              <li>Asistencia Jurídica - Si ampara</li>
                              <li>Asist. Domiliciaria - ${
                                element.cob_asist_jur_alz
                              }</li>
                              <li>HAMCCP - AMIT - ${element.cob_hamccp_alz}</li>
                              <li>Daños por agua - ${
                                element.cob_danos_agua_alz
                              }</li>
                          </ul>
                        </div>
                        <div style="vertical-align: top; width: 345px;" class="tab2Table">
                            <ul style="padding-left: 25px;">
                              <li>Eventos de la naturaleza - ${
                                element.cob_eve_nat_alz
                              }</li>
                              <li>RCE Familiar - ${element.cob_rce_fam_alz}</li>
                              <li>Eventos Eléctrico - ${
                                element.cob_eve_elec_alz
                              }</li>
                              <li>Hurto - ${element.cob_hur_alz}</li>
                              <li>Todo Riesgo - ${element.cob_tr_alz}</li>
                              <li>Asistencia Mascotas - ${
                                element.cob_asis_mas_alz
                              }</li>
                            </ul>
                          </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2">
                    </div>
                </div>
            </div>
        </div>`;
        $("#cardsContainer").append(cardCotizacion);
      } else if (element.aseguradora == "SBS") {
        // Verifica si el checkbox de "Estructura" está marcado
        if (!$("#estructura").is(":checked")) {
          cardCotizacion = `
          <div class="col-cards-12">
          <div class="card-ofertas">
              <div class="row card-body">
                  <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo" style="padding-top: 50px;">
                      <center>
                          <img src="vistas/img/logos/SBS.png" />
                      </center>
                      <div class='col-12' style='margin-top:2%;'>
                         ${
                           permisos.Vernumerodecotizacionencadaaseguradora ==
                           "x"
                             ? `<center>
                             <label class='entidad'>N° Cot: <span style='color:black'>${element.id_cot_aseguradora}</span></label>
                           </center>`
                             : ""
                         }
                      </div>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit" style="padding-top: 50px;">
                      <h5 class="entidad" style="font-size: 15px"><b>${
                        element.aseguradora
                      } - ${element.producto}</b></h5>
                      <h5 class="precio" style="">Desde ${parseInt(
                        element.valor_prima
                      ).toLocaleString("es-ES")} COP</h5>
                      <p class="title-precio" style="font-weight: bold;">IVA incluido</p>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-8" style="padding-top: 30px; padding-bottom: 30px;">
                      <div class="informativeTable">
                          <div>
                              <ul style="padding-left: 25px; ">
                                <li>Terremoto y otros eventos de la naturaleza - ${
                                  element.cob_terr_ev_nat_sbs
                                }</li>
                                <li>Hurto contenido no electrico - ${
                                  element.cob_hur_con_no_ele_sbs
                                }</li>
                                <li>Hurto contenido electrico - ${
                                  element.cob_hur_con_ele_sbs
                                }</li>
                                <li>Todo riesgo - ${element.cob_tr_sbs}</li>
                                <li>Accidentes personales - ${
                                  element.cob_acci_pers_sbs
                                }</li>
                                <li>Responsabilidad Civil - ${
                                  element.cob_resp_civil_sbs
                                }</li>
                                <li>Asist. domiciliaria - ${
                                  element.cob_asist_dom_sbs
                                }</li>
                                <li>Productos plus - ${
                                  element.cob_prod_plus_sbs
                                }</li>
                              </ul>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
                            ${
                              permisos.Verpdfindividuales == "x"
                                ? `
                              <button type="button" class="btn btn-info" onclick='verPdfOfertaHogar("${element.pdf_sbs}")'>
                                              <div id="">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
                                          </button>
                              `
                                : ""
                            }
                                          
                                      </div>
                          </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-2">
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-2">
                      </div>
                  </div>
              </div>
          </div>`;
          $("#cardsContainer").append(cardCotizacion);
        } else {
          cardCotizacion = `
          <div class="col-cards-12">
          <div class="card-ofertas">
              <div class="row card-body">
                  <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo" style="padding-top: 50px;">
                      <center>
                          <img src="vistas/img/logos/SBS.png" />
                      </center>
                      <div class='col-12' style='margin-top:2%;'>
                         ${
                           permisos.Vernumerodecotizacionencadaaseguradora ==
                           "x"
                             ? `<center>
                             <label class='entidad'>N° Cot: <span style='color:black'>${element.id_cot_aseguradora}</span></label>
                           </center>`
                             : ""
                         }
                      </div>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit" style="padding-top: 50px;">
                      <h5 class="entidad" style="font-size: 15px"><b>${
                        element.aseguradora
                      } - ${element.producto}</b></h5>
                      <h5 class="precio" style="">Desde ${parseInt(
                        element.valor_prima
                      ).toLocaleString("es-ES")} COP</h5>
                      <p class="title-precio" style="font-weight: bold;">IVA incluido</p>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-8" style="padding-top: 30px; padding-bottom: 30px;">
                      <div class="informativeTable">
                          <div>
                              <ul style="padding-left: 25px; ">
                                <li>AMIT - 10% de la pérdida Mínimo 90 SMDLV</li>
                                <li>Terremoto, erupcion, maremoto - 2 % de la pérdida mínimo 60 SMDLV</li>
                                <li>Asist. domiciliaria - Si ampara</li>
                                <li>Daños por Agua - Si ampara</li>
                                <li>Accidentes personales - No ampara</li>
                                <li>Productos plus - No ampara</li>
                                <li>Reemplazo Llaves Hogar - Si ampara</li>
                              </ul>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
                            ${
                              permisos.Verpdfindividuales == "x"
                                ? `
                              <button type="button" class="btn btn-info" onclick='verPdfOfertaHogar("${element.pdf_sbs}")'>
                                              <div id="">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
                                          </button>
                              `
                                : ""
                            }
                                          
                                      </div>
                          </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-2">
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-2">
                      </div>
                  </div>
              </div>
          </div>`;
        }
        $("#cardsContainer").append(cardCotizacion);
      }
    });
  } else if (data.aseguradora == "Allianz") {
    data.data.forEach((element) => {
      let incendios = element.coberturas.find(
        (coverage) => coverage.id_amparo === 1
      );
      let rcePro = element.coberturas.find(
        (coverage) => coverage.id_amparo === 2
      );
      let asistDomiciliaria = element.coberturas.find(
        (coverage) => coverage.id_amparo === 3
      );
      let otrosEventosNaturales = element.coberturas.find(
        (coverage) => coverage.id_amparo === 4
      );
      let terremoto = element.coberturas.find(
        (coverage) => coverage.id_amparo === 5
      );
      let otroDaniosRotura = element.coberturas.find(
        (coverage) => coverage.id_amparo === 6
      );
      let daniosPorAgua = element.coberturas.find(
        (coverage) => coverage.id_amparo === 7
      );
      let hamcppAMIT = element.coberturas.find(
        (coverage) => coverage.id_amparo === 8
      );
      let rceFam = element.coberturas.find(
        (coverage) => coverage.id_amparo === 9
      );
      let eventosElectricos = element.coberturas.find(
        (coverage) => coverage.id_amparo === 10
      );
      let hurto = element.coberturas.find(
        (coverage) => coverage.id_amparo === 11
      );
      let todoRiesgo = element.coberturas.find(
        (coverage) => coverage.id_amparo === 12
      );
      let asistMascotas = element.coberturas.find(
        (coverage) => coverage.id_amparo === 13
      );

      cardCotizacion = `
      <div class="col-cards-12">
      <div class="card-ofertas">
          <div class="row card-body">
              <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo" style="padding-top: 50px;">
                  <center>
                      <img src="vistas/img/logos/allianz.png" />
                  </center>
                  <div class='col-12' style='margin-top:2%;'>
                     ${
                       permisos.Vernumerodecotizacionencadaaseguradora == "x"
                         ? `<center>
                         <label class='entidad'>N° Cot: <span style='color:black'>${data.numeroCotizacion}</span></label>
                       </center>`
                         : ""
                     }
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit" style="padding-top: 50px;">
                  <h5 class="entidad" style="font-size: 15px"><b>${
                    data.aseguradora
                  } - ${element.producto}</b></h5>
                  <h5 class="precio" style="">Desde ${element.valorPrima}</h5>
                  <p class="title-precio" style="font-weight: bold;">IVA incluido</p>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-8" style="padding-top: 30px; padding-bottom: 30px;">
                  <div class="informativeTable">
                      <div style="width: 274px;" class="tab1Table">
                          <ul style="padding-left: 25px; ">
                            <li>Incendio - ${
                              incendios ? "Sin deducible" : "No ampara"
                            }</li>
                            <li>Terremoto - ${
                              terremoto
                                ? "Deducible: 2% min 2 SMMLV"
                                : "No ampara"
                            }</li>
                            <li>RCE Propiedad - ${
                              rcePro ? "$ 800.000.000" : "No ampara"
                            }</li>
                            <li>Asistencia Jurídica - Si ampara</li>
                            <li>Asist. Domiliciaria - ${
                              asistDomiciliaria ? "Si ampara" : "No ampara"
                            }</li>
                            <li>HAMCCP - AMIT - ${
                              hamcppAMIT ? "Si ampara" : "No ampara"
                            }</li>
                            <li>Daños por agua - ${
                              daniosPorAgua ? "Si ampara" : "No ampara"
                            }</li>
                          </ul>
                          </div>
                          <div style="vertical-align: top; width: 345px;" class="tab2Table">
                          <ul style="padding-left: 25px;">
                            <li>Eventos de la naturaleza - ${
                              otrosEventosNaturales ? "Si ampara" : "No ampara"
                            }</li>
                            <li>RCE Familiar - ${
                              rceFam ? "Si ampara" : "No ampara"
                            }</li>
                            <li>Eventos Eléctrico - ${
                              eventosElectricos
                                ? "Deducible 1 SMMLV"
                                : "No ampara"
                            }</li>
                            <li>Hurto - ${
                              hurto
                                ? "Mínimo 80% del valor de los contenidos"
                                : "No ampara"
                            }</li>
                            <li>Todo Riesgo - ${
                              todoRiesgo
                                ? "Maximo 25% del valor de los contenidos"
                                : "No ampara"
                            }</li>
                            <li>Asistencia Mascotas - ${
                              asistMascotas ? "Si ampara" : "No ampara"
                            }</li>
                          </ul>
                        </div>
                      </div>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-2">
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-2">
                  </div>
              </div>
          </div>
      </div>`;
      $("#cardsContainer").append(cardCotizacion);
    });
  } else if (data.aseguradora == "SBS") {
    data.data.forEach((element) => {
      if (!$("#estructura").is(":checked")) {
        cardCotizacion = `
            <div class="col-cards-12">
                      <div class="card-ofertas">
                          <div class="row card-body">
                              <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo" style="padding-top: 50px;">
                                  <center>
                                      <img src="vistas/img/logos/sbs.png" />
                                  </center>
                                  <div class='col-12' style='margin-top:2%;'>
                                    ${
                                      permisos.Vernumerodecotizacionencadaaseguradora ==
                                      "x"
                                        ? `<center>
                                        <label class='entidad'>N° Cot: <span style='color:black'>${data.numeroCotizacion}</span></label>
                                      </center>`
                                        : ""
                                    }
                                  </div>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit" style="padding-top: 50px;">
                                  <h5 class="entidad" style="font-size: 15px"><b>${
                                    data.aseguradora
                                  } - ${element.producto}</b></h5>
                                  <h5 class="precio">Desde $ ${parseInt(
                                    element.valorPrima
                                  ).toLocaleString("es-ES")} COP</h5>
                                  <p class="title-precio" style="font-weight: bold;">IVA incluido</p>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-8" style="padding-top: 30px; padding-bottom: 30px;">
                                  <div class="informativeTable">
                                      <div>
                                          <ul style="padding-left: 25px; ">
                                              <li>Terremoto y otros eventos de la naturaleza - ${
                                                element.cob_terr_ev_nat_sbs
                                              }</li>
                                              <li>Hurto contenido no electrico - ${
                                                element.cob_hur_con_no_ele_sbs
                                              }</li>
                                              <li>Hurto contenido electrico - ${
                                                element.cob_hur_con_ele_sbs
                                              }</li>
                                              <li>Todo riesgo - ${
                                                element.cob_tr_sbs
                                              }</li>
                                              <li>Accidentes personales - ${
                                                element.cob_acci_pers_sbs
                                              }</li>
                                              <li>Responsabilidad Civil - ${
                                                element.cob_resp_civil_sbs
                                              }</li>
                                              <li>Asist. domiciliaria - ${
                                                element.cob_asist_dom_sbs
                                              }</li>
                                              <li>Productos plus - ${
                                                element.cob_pro
                                              }</li>
                                          </ul>
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
                                          <button type="button" class="btn btn-info" onclick='verPdfOfertaHogar("${
                                            element.PDF
                                          }")'>
                                              <div id="">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2">
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2">
                              </div>
                          </div>
                      </div>
                  </div>`;
        $("#cardsContainer").append(cardCotizacion);
      } else {
        cardCotizacion = `
            <div class="col-cards-12">
                      <div class="card-ofertas">
                          <div class="row card-body">
                              <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo" style="padding-top: 50px;">
                                  <center>
                                      <img src="vistas/img/logos/sbs.png" />
                                  </center>
                                  <div class='col-12' style='margin-top:2%;'>
                                    ${
                                      permisos.Vernumerodecotizacionencadaaseguradora ==
                                      "x"
                                        ? `<center>
                                        <label class='entidad'>N° Cot: <span style='color:black'>${data.numeroCotizacion}</span></label>
                                      </center>`
                                        : ""
                                    }
                                  </div>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit" style="padding-top: 50px;">
                                  <h5 class="entidad" style="font-size: 15px"><b>${
                                    data.aseguradora
                                  } - ${element.producto}</b></h5>
                                  <h5 class="precio">Desde $ ${parseInt(
                                    element.valorPrima
                                  ).toLocaleString("es-ES")} COP</h5>
                                  <p class="title-precio" style="font-weight: bold;">IVA incluido</p>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-8" style="padding-top: 30px; padding-bottom: 30px;">
                                  <div class="informativeTable">
                                      <div>
                                          <ul style="padding-left: 25px; ">
                                              <li>AMIT - 10% de la pérdida Mínimo 90 SMDLV</li>
                                              <li>Terremoto, erupcion, maremoto - 2 % de la pérdida mínimo 60 SMDLV</li>
                                              <li>Asist. domiciliaria - Si ampara</li>
                                              <li>Daños por Agua - Si ampara</li>
                                              <li>Accidentes personales - No ampara</li>
                                              <li>Productos plus - No ampara</li>
                                              <li>Reemplazo Llaves Hogar - Si ampara</li>
                                          </ul>
                                      </div>
                                      <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
                                          <button type="button" class="btn btn-info" onclick='verPdfOfertaHogar("${
                                            element.PDF
                                          }")'>
                                              <div id="">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2">
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2">
                              </div>
                          </div>
                      </div>
                  </div>`;
        $("#cardsContainer").append(cardCotizacion);
      }
    });
  }
}

function verPdfOfertaHogar(b64) {
  if (permisos.Verpdfindividuales == "x") {
    let pdfWindow = window.open("");
    pdfWindow.document.write(
      `<iframe width='100%' height='100%' src='data:application/pdf;base64,${b64}'></iframe>`
    );
  } else {
    Swal.fire({
      icon: "warning",
      title: "¡Atención!",
      text: "No tiene permisos para ver los PDF individuales",
    });
  }
}

function validateErrors(form) {
  let errorFields = [];
  let camposAsegCC = ["#nombre", "#apellidos"];
  let camposAsegCE = [
    "#nombre",
    "#apellidos",
    "#nacionalidad1",
    "#pNacimiento1",
  ];
  let camposAsegNIT = [".razon", ".digito"];

  switch (form) {
    case "datosAsegurado":
      if ($("#tipoDocumento").val() == "0") {
        errorFields.push({
          descripcion: "Error debe ingresar los valores en los campos",
          codigo: 1001,
        });
        $("#tipoDocumento")
          .next(".select2-container")
          .find(".select2-selection")
          .css("border", "1px solid red");
      } else {
        $("#tipoDocumento")
          .next(".select2-container")
          .find(".select2-selection")
          .css("border", "1px solid #ccc");
      }

      if (
        $("#noDocumento").val() == "" ||
        $("#noDocumento").val().length <= 3
      ) {
        errorFields.push({
          descripcion: "Error debe ingresar los valores en los campos",
          codigo: 1001,
        });
        $(".numeroDocumento").css("border", "1px solid red");
      } else {
        $(".numeroDocumento").css("border", "1px solid #ccc");
      }

      let campos =
        $("#tipoDocumento").val() == "C"
          ? camposAsegCC
          : $("#tipoDocumento").val() == "X"
          ? camposAsegCE
          : camposAsegNIT;

      campos.forEach((element) => {
        if ($(`${element}`).val() == "" || $(`${element}`).val() == "0") {
          console.log(element);
          errorFields.push({
            descripcion: `Error debe ingresar en ${element}`,
            codigo: 1001,
          });
          if ($(`${element}`).is("select")) {
            $(`${element}`)
              .next(".select2-container")
              .find(".select2-selection")
              .css("border", "1px solid red");
          } else {
            $(`${element}`).css("border", "1px solid red");
          }
        }
      });
      break;
    case "datosInmueble":
      $(".validateDataHogar").each(function () {
        let selector = $(this);
        let tagName = this.tagName.toLowerCase(); // Obtiene el tipo de elemento (input, select, etc.)

        if (!selector.prop("disabled")) {
          if (tagName === "select") {
            if (selector.val() === "" || selector.val() === "0") {
              // Validación única
              errorFields.push({
                descripcion: `Error debe ingresar en ${selector.attr("id")}`,
                codigo: 1001,
              });
              // Si el select usa Select2, aplicamos el estilo al contenedor correcto
              selector
                .next(".select2-container")
                .find(".select2-selection")
                .css("border", "1px solid red");
            } else {
              selector
                .next(".select2-container")
                .find(".select2-selection")
                .css("border", "1px solid #ccc");
            }
          } else {
            if (selector.val().trim() === "") {
              // trim() para evitar espacios vacíos
              errorFields.push({
                descripcion: `Error debe ingresar en ${selector.attr("id")}`,
                codigo: 1001,
              });
              selector.css("border", "1px solid red");
            } else {
              selector.css("border", "1px solid #ccc");
            }
          }
          if ($("#tipoAseg").val() === "3") {
            let tipoCobertura = $(
              'input[name="tipoCoberturaRadio"]:checked'
            ).attr("id");

            // Verificar si realmente se seleccionó algo
            if (!tipoCobertura) {
              // Marcar error
              $('input[name="tipoCoberturaRadio"]').css(
                "outline",
                "2px solid red"
              );
              errorFields.push({
                descripcion: "Error: debe seleccionar un tipo de cobertura",
                codigo: 1001,
              });
            } else {
              // Remover el error
              $('input[name="tipoCoberturaRadio"]').css("outline", "none");
            }
          }
        } else {
          selector.css("border", "1px solid #ccc");
        }
      });
      break;
    case "cotizar":
      let tipoCotizacion = $('input[name="tipoCoberturaRadio"]:checked').attr(
        "id"
      );
      if (tipoCotizacion == "contenidos") {
        $(".contentsAllianz").each(function () {
          let selector = $(this);
          if (selector.attr("id") == "valorViviendaAllianz") {
            if (selector.prop("disabled")) {
              selector.val("0");
              return;
            }
            selector.css("border", "1px solid #ccc");
          } else {
            if (
              selector.val() == "" &&
              selector.attr("id") != "valorTodoRiesgoAllianz"
            ) {
              $(this).css("border", "1px solid red");
              errorFields.push({
                descripcion: `Error debe ingresar en ${selector.attr("id")}`,
                codigo: 1001,
              });
            } else {
              $(this).css("border", "1px solid #ccc");
            }
          }
        });
      } else if (tipoCotizacion == "estructura") {
        $(".contentsAllianz").each(function () {
          let selector = $(this);
          if (selector.attr("id") == "valorViviendaAllianz") {
            if (selector.val() == "" || selector.val() == "0") {
              $(this).css("border", "1px solid red");
              errorFields.push({
                descripcion: `Error debe ingresar en ${selector.attr("id")}`,
                codigo: 1001,
              });
            }
          }
        });
      } else {
        $(".contentsAllianz").each(function () {
          let selector = $(this);
          let isError = false; // Variable para saber si hay error en este campo

          if (
            selector.attr("id") == "valorViviendaAllianz" &&
            (selector.val() == "" || selector.val() == "0")
          ) {
            isError = true;
            errorFields.push({
              descripcion: `Error debe ingresar en ${selector.attr("id")}`,
              codigo: 1001,
            });
          }

          if (
            selector.attr("id") == "valorHurtoAllianz" &&
            selector.val() == ""
          ) {
            isError = true;
            errorFields.push({
              descripcion: `Error debe ingresar en ${selector.attr("id")}`,
              codigo: 1001,
            });
          }

          if (selector.attr("id") == "valorContenidosAllianz") {
            if (selector.val() == "") {
              isError = true;
              errorFields.push({
                descripcion: `Error debe ingresar en ${selector.attr("id")}`,
                codigo: 1001,
              });
            } else if (
              selector.val() !== "" &&
              $("#valorHurtoAllianz").val() > selector.val() * 0.8
            ) {
              isError = true;
              errorFields.push({
                descripcion: `Error el valor de hurto no puede ser mayor al valor de los contenidos`,
                codigo: 1002,
              });
            }
          }

          // if (
          //   selector.attr("id") == "valorTodoRiesgoAllianz" &&
          //   (selector.val() == "" || selector.val() == "0")
          // ) {
          //   isError = true;
          //   errorFields.push({
          //     descripcion: `Error debe ingresar en ${selector.attr("id")}`,
          //     codigo: 1001,
          //   });
          // }

          // Aplicar el borde rojo si hay error, de lo contrario, borde normal
          if (isError) {
            selector.css("border", "1px solid red");
          } else {
            selector.css("border", "1px solid #ccc");
          }
        });
      }
      break;
    default:
      break;
  }

  return { errors: errorFields.length <= 0, data: errorFields };
}

function toggleContainerData() {
  $("#masCotHogar").toggle();
  $("#menosCotHogar").toggle();
  $("#containerInfoAseg").toggle();
}

function toggleContainerDataHogar() {
  $("#masDataHogar").toggle();
  $("#menosDataHogar").toggle();
  $("#containerDatos").toggle();
}

function toggleContainerValores() {
  $("#masValoresHogar").toggle();
  $("#menosValoresHogar").toggle();
  $("#containerValores").toggle();
}

function toggleContainerValoresAllianz() {
  $("#masValoresHogarAllianz").toggle();
  $("#menosValoresHogarAllianz").toggle();
  $("#containerValoresAllianz").toggle();
}

function toggleContainerValoresSBS() {
  $("#masValoresHogar").toggle();
  $("#menosValoresHogar").toggle();
  $("#containerValores").toggle();
}

function toggleContainerCards() {
  $("#parrillaCards").toggle();
}

function openValAllianz() {
  $("#formValoresAllianz").toggle();
}

function openDataFormHogar() {
  $("#formHogar").toggle();
}

// function toggleContainerValoresHogar() {
//   $("#formValores").toggle();
// }

function hideCards() {
  $(".card-container").toggle();
}

function changeTittleHeader(title, container) {
  $(container).text(title);
}

function saveRequest(body, response) {
  $.ajax({
    type: "POST",
    url: "src/guardarRR.php",
    dataType: "json",
    data: {
      request: body,
      response: response,
      aseguradora: response.aseguradora, //response.aseguradora deberia traer el nombre de la aseguradora.
      cotizacion: idCotizacionHogar, //se debe traer la variable del contexto global idCotizacionHogar una vez sea generada, esta se generara cuando den en cotizar y no haya ningun error deberia traer el id de la cotizacion.
    },
    cache: false,
    success: function (data) {
      console.log("Guardado");
    },
    catch: function (error) {
      console.log(error);
      console.log("Error");
    },
  });
}

function saveClient() {
  let tipoDocumento = $("#tipoDocumento").val();
  let documento = $("#noDocumento").val();
  let nombre = $("#nombre").val();
  let apellidos = $("#apellidos").val();
  let nacionalidad = $("#nacionalidad").val();
  let fechaNacimiento = $("#pNacimiento").val();
  let razonSocial = $(".razon").val() != "" ? $(".razon").val() : null;
  let celular = $(".celular").val();
  let correo = $(".correo").val();
  let digito = $(".digito").val();

  $.ajax({
    type: "POST",
    url: "src/saveClient.php",
    dataType: "json",
    data: {
      tipoDocumento: tipoDocumento,
      documento: documento,
      nombre: nombre,
      apellidos: apellidos,
      nacionalidad: nacionalidad,
      fechaNacimiento: fechaNacimiento,
      celular: celular,
      correo: correo,
      digito: digito,
    },
    cache: false,
    success: function (data) {
      console.log("Guardado");
    },
    catch: function (error) {
      console.log(error);
      console.log("Error");
    },
  });
}

$("#btnHogarSiguiente").click(function (event) {
  let { errors, data } = validateErrors("datosAsegurado");
  if (errors) {
    $("#btnHogarSiguiente").prop("disabled", true);
    $("#containerDataTable").hide();
    $("#containerTable").hide();
    $(".mainDataContainer").addClass("paddingTop");
    // disableInputsData("#containerInfoAseg");
    toggleContainerData();
    changeTittleHeader("Datos del Asegurado", "#lblCotAseg");
    //deactivateFields();
    hideCards();
    openDataFormHogar();
  } else {
    event.preventDefault();
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "Debe completar los campos necesarios para continuar",
    });
  }
});

$("#btnDataHogarSiguiente").click(function (event) {
  let { errors, data } = validateErrors("datosInmueble");
  if (errors) {
    $("#btnDataHogarSiguiente").prop("disabled", true);
    // disableInputsData("#containerDatos");
    toggleContainerDataHogar();
    openValAllianz();
  } else {
    event.preventDefault();
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "Debe completar los campos necesarios para continuar",
    });
  }
});

let rawCompiled = {};
let rawAllianz = {};
let rawSBS = {};

let dataCotizacion = {};

$("#btnCotizarSBS, #btnCotizar").click(function () {
  let { errors, data } = validateErrors("cotizar");
  if (errors) {
    // $("html, body").animate({ scrollTop: 0 }, "slow", function () {
    //   setTimeout(() => {
    //     openModalVidaDeudor();
    //   }, 1000);
    // });
    // Obtener valores de los inputs una sola vez
    let tipoDocumento = $("#tipoDocumento").val();
    let tipoVivienda = $("#tipoVivienda").val();
    const tipoDeConstruccion = $("#tipoConstruccion").val();
    let tipoAsegValue =
      parseInt(($("#tipoAseg").val() || 0).replace(/\./g, ""), 10) || 0;
    let valorContenido =
      parseInt(
        ($("#valorContenidosAllianz").val() || "0")
          .toString()
          .replace(/\./g, ""),
        10
      ) || 0;
    let valorHurto =
      parseInt(
        ($("#valorHurtoAllianz").val() || "0").toString().replace(/\./g, ""),
        10
      ) || 0;
    let valorTodoRiesgo =
      parseInt(
        ($("#valorTodoRiesgoAllianz").val() || "0")
          .toString()
          .replace(/\./g, ""),
        10
      ) || 0;
    let valorVivienda =
      parseInt(
        ($("#valorViviendaAllianz").val() || "0").toString().replace(/\./g, ""),
        10
      ) || 0;
    let areaTotal =
      parseInt(($("#areaTotal").val() || 0).replace(/\./g, ""), 10) || 0;
    let anoConstruccion = parseInt($("#anioConstruccion").val(), 10) || 0;
    let direccionCompletaAllianz = $("#dirInmuebleAllianz").val();
    let asegurarMascota = "";
    if (
      (tipoAsegValue == "3" || tipoAsegValue == "2") &&
      valorHurto != 0 &&
      valorHurto != "0"
    ) {
      asegurarMascota = mascotaSeleccionada;
    }
    let codLocalidad = parseInt($("#ciudadInmueble").val(), 10) || 0;
    // Construir objeto con los valores obtenidos
    rawAllianz = {
      tipoDocumento: tipoDocumento,
      documento: $("#noDocumento").val(),
      categoriaDeRiesgo: tipoAsegValue,
      codLocalidad: codLocalidad,
      direccion: direccionCompletaAllianz,
      resto: "",
      valorEdificio: valorVivienda,
      valorContenido: valorContenido,
      valorHurto: valorHurto,
      valorTodoRiesgo: valorTodoRiesgo,
      asegurarMascota: asegurarMascota == "" ? "NO" : asegurarMascota,
      anoConstruccion: anoConstruccion,
      numeroTotalDePisos:
        tipoVivienda == "2" || tipoVivienda == "3" ? 1 : $("#noPisosEdi").val(),
      pisoUbicacionApto:
        tipoVivienda == "2" || tipoVivienda == "3" ? 1 : $("#noPiso").val(),
      numeroSotanos: 0,
      areaTotal: areaTotal,
      tipoDeConstruccion: tipoDeConstruccion,
      tipoDeVivienda: tipoVivienda,
      correo: $("#correo").val(),
      celular: $("#celular").val(),
      departamento: $("#deptoInmueble option:selected").text(),
      zonaConstruccion: $("#zonaConstruccion").val(),
      tieneCredito: $('input[name="creditoHipotecarioRadio"]:checked').val(),
      tipoCobertura: $('input[name="tipoCoberturaRadio"]:checked').attr("id"),
      estrato: $("#estrato").val(),
      id_usuario: $("#idUsuario").val(),
      correoAnalista: $("#correoAnalista").val(),
      usu_cel: $("#usu_cel").val(),
      usu_email: $("#usu_email").val(),
    };

    // Condicionales para agregar campos adicionales según el tipo de documento
    if (tipoDocumento === "C" || tipoDocumento === "X") {
      rawAllianz.nombreCompleto =
        $("#nombre").val() + " " + $("#apellidos").val();
      if (tipoDocumento === "X") {
        rawAllianz.nacionalidad = $("#nacionalidad1").val();
        rawAllianz.pNacimiento = $("#pNacimiento1").val();
      }
    } else {
      rawAllianz.nombreCompleto = $(".razon").val();
      rawAllianz.documento = $("#noDocumento").val() + "" + $(".digito").val();
    }

    dataCotizacion = rawAllianz;

    dataCotizacion.val_viv = valorVivienda;
    dataCotizacion.val_cn = valorContenido;
    dataCotizacion.val_hur = valorHurto;
    dataCotizacion.val_tr = valorTodoRiesgo;
    dataCotizacion.aseg_masc = asegurarMascota;

    rawCompiled.allianz = rawAllianz;

    if (aseguradorasHogar[1].enabled) {
      if (tipoDeConstruccion != "1") {
        let apellidosSBS = $("#apellidos").val().split(" ");
        let primerApellido = apellidosSBS[0] || "";
        let segundoApellido = apellidosSBS[1] || "";
        let valorContentSBS =
          parseInt(
            ($("#valorEnseres").val() || "0").toString().replace(/\./g, ""),
            10
          ) || 0;
        let ValorEquipoEE =
          parseInt(
            ($("#valorEquipoElectrico").val() || "0")
              .toString()
              .replace(/\./g, ""),
            10
          ) || 0;
        let valorContenidoEspecial =
          parseInt(
            ($("#valorCEspeciales").val() || "0").toString().replace(/\./g, ""),
            10
          ) || 0;
        let valorContenidoNormalSUS =
          parseInt(
            ($("#contentNormalesSUS").val() || "0")
              .toString()
              .replace(/\./g, ""),
            10
          ) || 0;

        let valorContenidoEspecialSUS =
          parseInt(
            ($("#contEspecialesSUS").val() || "0")
              .toString()
              .replace(/\./g, ""),
            10
          ) || 0;

        let valorEquipoEESUS =
          parseInt(
            ($("#valorAsegSUSEE").val() || "0").toString().replace(/\./g, ""),
            10
          ) || 0;

        let valorTodoRiesgo =
          parseInt(
            ($("#valorTodoRiesgo").val() || "0").toString().replace(/\./g, ""),
            10
          ) || 0;

        let direccionCompletaSBS = $("#dirInmueble").val();
        let tipoConstruccion = $("#tipoConstruccion").val();

        if (
          tipoConstruccion == 2 ||
          tipoConstruccion == 3 ||
          tipoConstruccion == 4
        ) {
          tipoConstruccion = 1;
        }

        rawSBS = {
          factorComision: "A80",
          tipoDocumento:
            tipoDocumento == "C" ? 1 : tipoDocumento == "X" ? 3 : 2,
          nacionalidad: $("#nacionalidad1").val() || 45,
          paisNacimiento: $("#pNacimiento1").val() || 45,
          numeroDocumento: $("#noDocumento").val(),
          nombreAsegurado: $("#nombre").val(),
          apellido1Asegurado: primerApellido,
          apellido2Asegurado: segundoApellido,
          telefonoFijo: "",
          celular: $("#celular").val() || "",
          correo: $("#correo").val() || "",
          direccionContacto: direccionCompletaSBS,
          digitoVerificacion: $(".digito").val() || 0,
          direccion: direccionCompletaSBS,
          zonaDeRiesgo: $("#zonaRiesgo").val() || 0,
          subZona: $("#subZona").val() || 0,
          categoriaDeRiesgo: tipoAsegValue,
          tipoDeVivienda: tipoVivienda,
          tipoDeConstruccion: tipoConstruccion,
          codLocalidad: codLocalidad,
          numeroTotalDePisos:
            tipoVivienda == "2" || tipoVivienda == "3"
              ? 1
              : $("#noPisosEdi").val(),
          anoConstruccion: anoConstruccion,
          zonaConstruccion: $("#zonaConstruccion").val(),
          resto: "",
          valorEdificio: valorVivienda,
          valorContenido: valorContentSBS, // Valor de los enseres, #valorEnseres
          valorEquipoEE: ValorEquipoEE, // Valor de los equipos electricos, #valorEquipoElectrico
          valorContenidoEspecial: valorContenidoEspecial, // Valor de los contenidos especiales, #valorCEspeciales
          valorContenidoNormalSUS: valorContenidoNormalSUS, // Valor de los contenidos normales, #contentNormalesSUS
          valorContenidoEspecialSUS: valorContenidoEspecialSUS, // Valor de los contenidos especiales, #contEspecialesSUS
          valorEquipoEESUS: valorEquipoEESUS, // Valor de los equipos electricos, #valorAsegSUSEE
          valorTodoRiesgo: valorTodoRiesgo, // Valor de los contenidos normales, #valorTodoRiesgo
          deducibleGeneral: 2,
          deducibleTerremoto: 44,
          sublimiteTerremoto: 1,
          accidentesPersonales: 0,
          responsabilidadCivil: 4,
          deducibleResponsabilidadCivil: 1,
          asistenciaDomiciliaria: true,
          estrato: $("#estrato").val(),
          id_usuario: $_SESSION['idUsuario'],
          correoAnalista: $("#correoAnalista").val(),
          usu_cel: $("#usu_cel").val(),
          usu_email: $("#usu_email").val(),
        };

        let valorContNormEnseres =
          parseInt(
            ($("#valorEnseres").val() || "0").toString().replace(/\./g, ""),
            10
          ) || 0;

        let valorContentTotalNorm =
          parseInt(
            ($("#totalContenidos").val() || "0").toString().replace(/\./g, ""),
            10
          ) || 0;

        let valorTotalCoberturaBasica =
          parseInt(
            ($("#totalCoberturaBasica").val() || "0")
              .toString()
              .replace(/\./g, ""),
            10
          ) || 0;
        let valorTotalContenidosSUS =
          parseInt(
            ($("#totalContHurtoSus").val() || "0")
              .toString()
              .replace(/\./g, ""),
            10
          ) || 0;

        let valorAseguradoD =
          parseInt(
            ($("#valorAseguradoD").val() || "0").toString().replace(/\./g, ""),
            10
          ) || 0;

        dataCotizacion.sub_zona = $("#subZona option:selected").text();
        dataCotizacion.val_viv_sbs = valorVivienda;
        dataCotizacion.val_cnen_sbs = valorContNormEnseres;
        dataCotizacion.val_cnelec_sbs = ValorEquipoEE;
        dataCotizacion.val_cnens_sbs = valorContenidoEspecial;
        dataCotizacion.tot_cnn_sbs = valorContentTotalNorm;
        dataCotizacion.tot_cobertura_basica_sbs = valorTotalCoberturaBasica;
        dataCotizacion.val_cnesp_sus_sbs = valorContenidoEspecialSUS;
        dataCotizacion.val_cnnor_sus_sbs = valorContenidoNormalSUS;
        dataCotizacion.tot_cn_sus_sbs = valorTotalContenidosSUS;
        dataCotizacion.val_asegee_danos_sbs = valorAseguradoD;
        dataCotizacion.val_asegee_sus_sbs = valorEquipoEESUS;
        dataCotizacion.val_tr_sbs = valorTodoRiesgo;

        rawCompiled.sbs = rawSBS;
      } else {
        aseguradorasHogar[1].enabled = false;
        rawCompiled.sbs = {};
        $(`#SBS-check`).html(
          `<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i>`
        );
        $(`#SBS-offerts`).html("0");
        $(`#SBS-observations`).html(
          "Para cotizar tipo de construcción <b>Otro</b> solicita cotización manual a tu Analista Comercial asignado donde deberás enviar vía correo electrónico las fotos correspondientes con las especificaciones para revisar y aprobar si es el caso"
        );
      }
    }

    // cotizar(rawCompiled);
    setBlankInputs();
    if (!validarMascotasSeleccionado()) {
      return;
    } else {
      console.log(rawCompiled.allianz);
      saveQuotation().then((response) => {
        if (response.success) {
          
        }
      });
      // throw new Error("Detener ejecución para pruebas");  
      // Luego de salvar la cotizacion en: cotizaciones_hogar, enviar el correo -> mostrar alerta de exito o error -> volver a la pantalla principal de hogar.
    }
    debugger;
  } else {
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: "Debe completar los campos necesarios para continuar",
    });
  }
});

function makeATable() {
  aseguradorasHogar.forEach((element) => {
    if (element.enabled) {
      $("#tablaResCot").append(
        `<tr style="vertical-align: center; text-align: center; font-size: 13px;" id="${element.aseguradora}-row">
            <td id="${element.aseguradora}-name" style="font-weight: bold; font-size: 15px;">${element.aseguradora}</td>
            <td id="${element.aseguradora}-check">
               <img src="vistas/img/plantilla/loader-update.gif" alt="Allianz" style="width: 22px; height: 22px;">
            </td>
            <td style="font-size: 15px;" id="${element.aseguradora}-offerts">         
            </td>
            <td style="font-size: 15px;" id="${element.aseguradora}-observations">   
            </td>
        </tr>`
      );
    } else {
      $("#tablaResCot").append(
        `<tr style="vertical-align: center; text-align: center; font-size: 13px;" id="${element.aseguradora}-row">
            <td id="${element.aseguradora}-name" style="font-weight: bold; font-size: 15px;">${element.aseguradora}</td>
            <td id="${element.aseguradora}-check">
               <i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i>
            </td>
            <td style="font-size: 15px;" id="${element.aseguradora}-offerts">0</td>
            <td style="font-size: 15px;" id="${element.aseguradora}-observations">No cotizada por asesor</td>
        </tr>`
      );
    }
  });
}

/***********************************************************************
 **********************  FORM HOGAR FUNCTIONS  *************************
 **********************************************************************/

$("#masCotHogar, #menosCotHogar").click(function () {
  toggleContainerData();
});

$("#masDataHogar, #menosDataHogar").click(function () {
  toggleContainerDataHogar();
});

$("#masValoresHogar, #menosValoresHogar").click(function () {
  toggleContainerValores();
});

$("#masValoresHogarAllianz, #menosValoresHogarAllianz").click(function () {
  toggleContainerValoresAllianz();
});

$("#dirInmueble").on("click", function () {
  abrirDialogoCrear();
});

function abrirDialogoCrear() {
  // Configurar el diálogo
  $("#myModalHogar").dialog({
    title:
      "A continuación escriba la dirección la dirección del inmueble, segun el formato solicitado",
    closeOnEscape: false,
    autoOpen: false,
    resizable: false, // Desactiva el redimensionamiento
    draggable: false, // Opcional, si deseas permitir que se pueda mover
    modal: true,
    width: "auto",
    // position: { my: "center center", at: "center top+100", of: window },
    // position: { my: "left top", at: "left+65 top+50", of: window } ,
    dialogClass: "",

    open: function () {
      $("body").addClass("modal-open"); // Añade la clase para bloquear el scroll de la página
      $("body").css("overflow", "hidden");

      $("body").addClass("modal-open").css("overflow", "hidden");

      // Obtener dimensiones de la ventana
      let windowWidth = $(window).width();
      let windowHeight = $(window).height();

      // Calcular posiciones dinámicas
      let posX = windowWidth >= 1280 ? "center+25" : "center"; // Centrado en pantallas grandes, desplazado en chicas
      let posY = windowHeight > 800 ? "center" : "top+300"; // Ajuste según altura de la pantalla

      // Ajustar la posición
      $(this).dialog("option", "position", {
        my: "center center",
        at: `${posX} ${posY}`,
        of: window,
      });
    },
    close: function () {
      if ($("#dirInmueble") != "") {
        $("#dirInmueble").css("border", "1px solid #ccc");
      } else {
        $("#dirInmueble").css("border", "1px solid red");
      }
      $("#dirInmueble").blur();
      $("body").removeClass("modal-open").css("overflow", "auto");
    },
  });
  // Abrir el diálogo
  $("#myModalHogar").dialog("open");
}

// openModalVidaDeudor()

function openModalVidaDeudor() {
  $("#myModalHogarVidaDeudor").dialog({
    closeOnEscape: false,
    autoOpen: false,
    resizable: false, // Desactiva el redimensionamiento
    draggable: false, // Opcional, si deseas permitir que se pueda mover
    modal: true,
    width: "63%",
    // position: { my: "center center", at: "center top+100", of: window },
    // position: { my: "left top", at: "left+65 top+50", of: window } ,
    dialogClass: "modalVidaDeudor",

    open: function () {
      $("body").addClass("modal-open"); // Añade la clase para bloquear el scroll de la página

      // Obtener dimensiones de la ventana
      let windowWidth = $(window).width();
      let windowHeight = $(window).height();

      // Calcular posiciones dinámicas
      let posX = windowWidth >= 1280 ? "center+25" : "center+19"; // Centrado en pantallas grandes, desplazado en chicas
      let posY = windowHeight > 800 ? "center" : "top+350"; // Ajuste según altura de la pantalla

      // Ajustar la posición
      $(this).dialog("option", "position", {
        my: "center center",
        at: `${posX} ${posY}`,
        of: window,
      });
    },
    close: function () {
      $("body").removeClass("modal-open").css("overflow", "auto");
    },
  });
  // Abrir el diálogo
  $("#myModalHogarVidaDeudor").dialog("open");
}

function openFormQuestions() {
  $("#btnSiVidaDeudor").prop("disabled", "true");
  $("#myModalHogarVidaDeudor").dialog({
    height: $(window).height() * 0.8,
  });
  $("#formQuestionVidaDeudor").css("display", "block");
}
function showCircularProg(cotType) {
  Swal.fire({
    title: `${cotType}`,
    html: `
        <div style="display: flex; justify-content: center; align-items: center; overflow: hidden;">
          <div class="loader"></div>
        </div>
      `,
    didOpen: () => {
      document.querySelector(".swal2-container").style.zIndex = "1200";
    },
    backdrop: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    customClass: {
      popup: "popupLoader",
      container: "backdropLoader",
    },
  });
}

async function enviarCorreoVidaDeudor() {
  // Capturar los valores manualmente desde los inputs
  const valorDeuda = document.getElementById("valorDeuda").value.trim();
  const diaNacimiento = document.getElementById(
    "dianacimientoVidaDeudor"
  ).value;
  const mesNacimiento = document.getElementById(
    "mesnacimientoVidaDeudor"
  ).value;
  const anioNacimiento = document.getElementById(
    "anionacimientoVidaDeudor"
  ).value;
  const peso = document.getElementById("pesoDeudor").value.trim();
  const altura = document.getElementById("alturaDeudor").value.trim();
  const condicionSalud = document.getElementById("condicionSal").value.trim();
  const tieneCondicion = document.querySelector(
    "input[name='infoCheck']:checked"
  )?.value;

  // Validar que los campos obligatorios no estén vacíos
  if (!valorDeuda)
    return mostrarError("valorDeuda", "Este campo es obligatorio");
  if (!diaNacimiento || !mesNacimiento || !anioNacimiento)
    return mostrarError(
      "dianacimientoVidaDeudor",
      "Selecciona una fecha válida"
    );
  if (!peso || isNaN(peso) || peso <= 0)
    return mostrarError("pesoDeudor", "Ingresa un peso válido");
  if (!altura || isNaN(altura) || altura <= 0)
    return mostrarError("alturaDeudor", "Ingresa una altura válida");
  if (!condicionSalud)
    return mostrarError("condicionSal", "Este campo es obligatorio");
  if (!tieneCondicion)
    return mostrarError("creditoHipotecarioRadio", "Selecciona una opción");

  // Si todo está bien, remover errores previos
  limpiarErrores();

  let nombreCli = $("#nombre").val() ?? null;
  let apellidoCli = $("#apellidos").val() ?? null;
  let noDocumentoCli = $("#noDocumento").val() ?? null;
  let razonSocialCli = $(".razon").val() ?? null;
  let correoCli = $(".correo").val() ?? null;
  let celularCli = $(".celular").val() ?? null;

  // Construir objeto con los datos
  const data = {
    asesor: permisos.usu_nombre + " " + permisos.usu_apellido,
    documentoAsesor: permisos.usu_documento,
    correoAsesor: permisos.usu_email,
    telefono: permisos.usu_telefono,
    clienteNombre: nombreCli || razonSocialCli,
    clienteApellido: apellidoCli || null,
    clienteDocumento: noDocumentoCli,
    clienteCorreo: correoCli,
    clienteCelular: celularCli,
    valorDeuda,
    fechaNacimiento: `${anioNacimiento}-${mesNacimiento}-${diaNacimiento}`,
    peso,
    altura,
    condicionSalud,
    tieneCondicion,
  };

  try {
    showCircularProg("Enviando Email...");
    const response = await fetch("ajax/sendMailDeudor.ajax.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    if (response.ok) {
      Swal.close();
      closeModal("#myModalHogarVidaDeudor"); // Cierra el modal si el envío fue exitoso
    } else {
      alert("Error al enviar el correo");
    }
  } catch (error) {
    console.error("Error en la solicitud:", error);
    alert("Hubo un problema al enviar el correo");
  }
}

// Función para mostrar errores en los inputs
function mostrarError(idCampo, mensaje) {
  const campo = document.getElementById(idCampo);
  let error = document.createElement("p");
  error.classList.add("error-msg");
  error.style.color = "red";
  error.textContent = mensaje;

  // Eliminar error previo si existe
  let errorPrevio = campo.parentNode.querySelector(".error-msg");
  if (errorPrevio) {
    errorPrevio.remove();
  }

  campo.parentNode.appendChild(error);
  campo.focus(); // Hacer que el campo tome el foco
}

// Función para limpiar errores previos
function limpiarErrores() {
  document.querySelectorAll(".error-msg").forEach((el) => el.remove());
}

$(
  "#dianacimientoVidaDeudor, #mesnacimientoVidaDeudor, #anionacimientoVidaDeudor"
).select2({
  theme: "bootstrap",
  language: "es",
  width: "100%",
  dropdownParent: $("#myModalHogarVidaDeudor"),
});

let address = ["", "", "", " ", "", "#", "", "", "-", "", "", "", "", ""];

function clearInfoModalAddress(erase) {
  // // // debugger;
  let inputAddress = $("#dirInmueble").val();
  if (inputAddress != "") {
    return;
  }
  if (erase) {
    for (let i = 0; i < 15; i++) {
      if (i == 6 || i == 9) {
        continue;
      }
      $(`#${i}m`).val("");
    }
    $(`#15m`).val("");
    address = ["", "", "", " ", "", "#", "", "", "-", "", "", "", "", ""];
  } else {
    return;
  }
}

function closeModalAddress(erase = false) {
  $("#myModalHogar").dialog("close");
  clearInfoModalAddress(erase);
}

function closeModal(selector) {
  $(`${selector}`).dialog("close");
}

$("#myModalHogar")
  .find("input, select")
  .on("change", function () {
    saveAddress($(this));
  });

function saveAddress(input) {
  let id = input[0].id;
  let index;

  if (id.length <= 2) {
    index = id[0];
  } else {
    index = id[0] + id[1];
  }

  let typeField = input[0].tagName;
  console.log(typeField);

  if (typeField === "SELECT") {
    let option = $(`#${index}m option:selected`).val();
    if (option == 0) {
      address[index - 1] = "";
    } else {
      // Agregar coma solo si ya hay contenido en la dirección
      if (index == 12) {
        address[index - 1] = ` ${$(`#${index}m`).val()}`;
      } else {
        address[index - 1] = $(`#${index}m`).val();
      }
    }
  } else {
    address[index - 1] = $(`#${index}m`).val();
  }

  let format = address.map((element, index) => {
    if (element == "") {
      return "";
    } else if (
      index == 0 ||
      index == 2 ||
      index == 3 ||
      index == 4 ||
      index == 5 ||
      index == 11 ||
      index == 12
    ) {
      return element + " ";
    } else {
      return element;
    }
  });

  $("#15m").val(format.join(""));
}

function saveToFrontAddress() {
  let errorFields = [];
  $("#divFields")
    .find("select, input")
    .each(function () {
      if ($(this).prop("required") && $(this).val() == "") {
        let selector = $(this);
        if (selector.val() == "") {
          errorFields.push(selector[0].id);
        }
      }
    });

  if (errorFields.length > 0) {
    $("#divFields").find("select, input").css("border", "1px solid #ccc");
    let message =
      "Debe completar los campos necesarios para continuar, de lo contrario no se podrá guardar la dirección";
    errorFields.forEach((element, index) => {
      $(`#${element}`).css("border", "1px solid red");
    });
    Swal.fire({
      icon: "error",
      title: "¡Atención!",
      text: message,
    });
  }

  $("#dirInmueble").val($("#15m").val());

  if (errorFields.length <= 0) {
    $("#divFields").find("select, input").css("border", "1px solid #ccc");
    closeModalAddress(true);
  }
}

// FUNCION PARA CARGAR LA CIUDAD DE CIRCULACIÓN
function consultarCiudadHogar() {
  var codigoDpto = document.getElementById("deptoInmueble").value;
  $.ajax({
    type: "POST",
    url: "src/consultarCiudadHogar.php",
    data: { codigoDpto: codigoDpto },
    cache: false,
    dataType: "json",
    success: function (response) {
      //
      let ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;
      try {
        let json = response;
        const { data } = json
        console.log(data)
        data.sort((a, b) => a.codigo - b.codigo);
        data.forEach(({ codigo, ciudad }) => {
          ciudadesVeh += `<option value="${codigo}">${ciudad}</option>`;
        });

        document.getElementById("ciudadInmueble").innerHTML = ciudadesVeh;
      } catch (error) {
        console.error("Error al procesar JSON:", error);
      }
    },
  });
}

// Carga los Departamentos disponibles
$("#deptoInmueble").select2({
  theme: "bootstrap dpto",
  language: "es",
  width: "100%",
});

// Carga las Ciudades disponibles
$(
  "#ciudadInmueble, #zonaRiesgo, #noPiso, #noPisosEdi, #anioConstruccion, #estrato, #tipoDocumento, #nacionalidad1, #pNacimiento1, #subZona"
).select2({
  theme: "bootstrap ciudad",
  language: "es",
  width: "100%",
});

$("#deptoInmueble").on("change", function () {
  const params = new URLSearchParams(window.location.search);
  if (!params.has("idCotizacionHogar")) {
    consultarCiudadHogar();
  }
});
