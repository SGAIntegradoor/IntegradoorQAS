let aseguradorasHogar = [
  { aseguradora: "Allianz", enabled: true },
  { aseguradora: "SBS", enabled: false },
];

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
      $("#containerValores, #containerValoresAllianz")
        .find("input, select")
        .each(function () {
          if (
            $(this).attr("id") == "valorVivienda" ||
            $(this).attr("id") == "valorViviendaAllianz"
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

  let valorVivInputs = ["#valorVivienda", "#valorViviendaAllianz"];

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
        $(element).prop("disabled", true);
      });
      $(".valores").prop("disabled", false);
    }

    console.log("Seleccionado: " + seleccionado);
  });

  $('input[name="sbsRadio"]').on("change", function () {
    let seleccionado = $('input[name="sbsRadio"]:checked').attr("id");

    if (seleccionado == "siSBS") {
      $("#formValores").css("display", "block");
      $("#btnCotizarSBS").css("display", "none");
    } else if (seleccionado == "noSBS") {
      $("#formValores").css("display", "none");
      $("#btnCotizarSBS").css("display", "block");
    }

    console.log("Seleccionado: " + seleccionado);
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
        $("#totalContenidos").val() == "0" ||
        $("#totalContenidos").val() == ""
      ) {
        Swal.fire({
          icon: "error",
          title: "¡Atención!",
          text: "Debe ingresar el valor de los contenidos",
        }).then(() => {
          $(this).val("");
          $("#totalContenidos").focus();
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
        console.log(valorTRiesgo, "Valor Todo Riesgo");
        console.log(valorContenidos, "Valor Contenidos");
        console.log(valorContenidos * 0.25, "25% Contenidos");
        Swal.fire({
          icon: "error",
          title: "¡Atención!",
          text: "El valor de Todo Riesgo no puede ser mayor al valor de los contenidos o al 25% del total de los contenidos",
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

  // $("#valorTodoRiesgo, #valorTodoRiesgoAllianz").on("change", function () {
  //   if ($(this).val() === "0" || $(this).val() === 0) {
  //     validateTodoRiesgo($(this).attr("id"), true);
  //   } else if ($(this).val() > "0" || $(this).val() > 0) {
  //     validateTodoRiesgo($(this).attr("id"), false);
  //   } else {
  //     $("#siGato").prop("disabled", false);
  //     $("#siPerro").prop("disabled", false);
  //     $("#no").prop("disabled", false);
  //   }
  // });

  $("#valorTodoRiesgo, #valorTodoRiesgoAllianz").on("change", function () {
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

  // $("#valorTodoRiesgo, #valorTodoRiesgoAllianz").on("blur", function () {
  //   if ($(this).val() === "" || $(this).val() === "0") {
  //     validateTodoRiesgo($(this).attr("id"), true);
  //   } else {
  //     validateTodoRiesgo($(this).attr("id"), true);
  //   }
  // });

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
      if (element == "#preguntaMascotas") {
        $(`${element}`).css("display", "none");
      }
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
      console.log(valorHurto, "Valor Hurto");
      console.log(valorContenidos, "Valor Contenidos");
      Swal.fire({
        icon: "error",
        title: "¡Atención!",
        text: "El valor de hurto no puede ser mayor al valor de los contenidos o menor al 80% de los contenidos",
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
          $("#tipoDocumento").val(data.id_tipo_documento).trigger("change");
          $(".razon")
            .find()
            .val(data.cli_nombre + " " + data.cli_apellidos);
          $(".digito").val(data.digitoVerificacion); // Último dígito
          numDocumentoID.value = documentCli;
        } else if (estado) {
          console.log(data);
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

function appendSectionAlerts(){
  $("#resumenCotizaciones").toggle();
}

function guardarCotizacion() {}

function cotizar(body) {
  appendSectionAlerts();
  let promisesHogar = [];
  let requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  };

  aseguradorasHogar.forEach((element) => {
    if (element.aseguradora == "Allianz" && element.enabled) {
      promisesHogar.push(
        fetch(
          `https://grupoasistencia.com/backend_node/WSAllianz/QuotationAllianzHogar`,
          requestOptions
        )
          .then((response) => {
            if (!response?.ok) throw Error("Error en la petición");
            return response.json();
          })
          .then((offerts) => {
            $(`#${element.aseguradora}-check`).html(
              `'<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>';`
            );
            $(`#${element.aseguradora}-offerts`).html(
              `${offerts?.data?.data?.paquetes.length}`
            );
            $(`#${element.aseguradora}-observations`).html(
              `Cotización exitosa`
            );
          })
      );
    } else if (element.aseguradora == "SBS" && element.enabled) {
      promisesHogar.push(
        fetch(
          `https://grupoasistencia.com/backend_node/WSSBS/QuotationSBSHogar`,
          requestOptions
        )
      );
    }
  });

  Promise.all(promisesHogar)
    .then(() => {
      Swal.fire({
        icon: "success",
        title: "¡Cotización exitosa!",
        text: "Proceso de cotización finalizado",
      });
    })
    .catch((error) => console.log(error));
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
            if (selector.val() == "") {
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

          if (
            selector.attr("id") == "valorTodoRiesgoAllianz" &&
            (selector.val() == "" || selector.val() == "0")
          ) {
            isError = true;
            errorFields.push({
              descripcion: `Error debe ingresar en ${selector.attr("id")}`,
              codigo: 1001,
            });
          }

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

  console.log(errorFields);

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

$("#btnHogarSiguiente").click(function (event) {
  let { errors, data } = validateErrors("datosAsegurado");
  if (errors) {
    $("#btnHogarSiguiente").prop("disabled", true);
    toggleContainerData();
    changeTittleHeader("DATOS DEL ASEGURADO", "#lblCotAseg");
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

$("#btnCotizarSBS").click(function () {
  let { errors, data } = validateErrors("cotizar");
  if (errors) {
    toggleContainerValoresAllianz();
    // Obtener valores de los inputs una sola vez
    let tipoDocumento = $("#tipoDocumento").val();
    let tipoVivienda = $("#tipoVivienda").val();
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
    let direccionCompleta = $("#dirInmueble").val().split(",");
    let direccion = direccionCompleta[0].trim() || "";
    let restoDireccion = direccionCompleta[1].trim() || "";
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
    let raw = {
      tipoDocumento: tipoDocumento,
      documento: $("#noDocumento").val(),
      categoriaDeRiesgo: tipoAsegValue,
      codLocalidad: codLocalidad,
      direccion: direccion,
      resto: restoDireccion,
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
      tipoDeConstruccion: $("#tipoConstruccion").val(),
      tipoDeVivienda: tipoVivienda,
      correo: $("#correo").val(),
      celular: $("#celular").val(),
      departamento: $("#deptoInmueble option:selected").text(),
      zonaConstruccion: $("#zonaConstruccion").val(),
      tieneCredito: $('input[name="creditoHipotecarioRadio"]:checked').val(),
      tipoCobertura: $('input[name="tipoCoberturaRadio"]:checked').val(),
    };

    // Condicionales para agregar campos adicionales según el tipo de documento
    if (tipoDocumento === "C" || tipoDocumento === "X") {
      raw.nombreCompleto = $("#nombre").val() + " " + $("#apellidos").val();
      if (tipoDocumento === "X") {
        raw.nacionalidad = $("#nacionalidad1").val();
        raw.pNacimiento = $("#pNacimiento1").val();
      }
    } else {
      raw.nombreCompleto = $(".razon").val();
      raw.documento = $("#noDocumento").val() + "" + $(".digito").val();
    }

    // Validar si se debe incluir asegurarMascota

    console.log(raw);
    makeATable();
    cotizar(raw);
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

function clearInfoModalAddress(erase) {
  let inputAddress = $("#15m").val();
  if (inputAddress != "") {
    return;
  } else {
    if (erase) {
      for (let i = 0; i < 15; i++) {
        if (i == 6 || i == 9) {
          continue;
        }
        $(`#${i}m`).val("");
      }
      $(`#15m`).val("");
    } else {
      return;
    }
  }
}

function closeModalAddress(erase = false) {
  $("#myModalHogar").dialog("close");
  clearInfoModalAddress(erase);
}

$("#myModalHogar")
  .find("input, select")
  .on("change", function () {
    saveAddress($(this));
  });

let address = ["", "", "", " ", "", "#", "", "", "-", "", "", "", "", ""];

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
        if (address[index - 1] !== "") {
          address[index - 1] += $(`#${index}m`).val();
        } else {
          address[index - 1] = `, ${$(`#${index}m`).val()}`;
        }
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
    closeModalAddress();
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
    success: function (data) {
      // console.log(data);
      let ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;
      try {
        let json = JSON.parse(data);
        json.sort((a, b) => a.codigo - b.codigo);

        json.forEach(({ codigo, ciudad }) => {
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
  "#ciudadInmueble, #zonaRiesgo, #zonaConstruccion, #tipoVivienda, #noPiso, #noPisosEdi, #tipoConstruccion, #anioConstruccion, #estrato, #tipoAseg, #tipoDocumento, #nacionalidad1, #pNacimiento1"
).select2({
  theme: "bootstrap ciudad",
  language: "es",
  width: "100%",
});

$("#deptoInmueble").on("change", function () {
  consultarCiudadHogar();
});
