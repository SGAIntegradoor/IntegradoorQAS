$(".tablas-hogar").on("click", ".btnEditarCotizacionHogar", function () {
  var idCotizacionHogar = $(this).attr("idCotizacionHogar");
  window.location =
    "index.php?ruta=retomar-cotizacion-hogar&idCotizacionHogar=" +
    idCotizacionHogar;
});

let getParams = (param) => {
  var urlPage = new URL(window.location.href); // Instancia la URL Actual
  var options = urlPage.searchParams.getAll(param); //Buscar todos los parametros
  return options;
};

function changeTitlePage() {
  var newTittle = "DATOS DEL ASEGURADO";
  $("#lblCotAseg").text(newTittle);
}
if (getParams("idCotizacionHogar").length > 0) {
  editarCotizacionHogar(getParams("idCotizacionHogar")[0]);
  $("#btnCotizarSBS, #btnDataHogarSiguiente, #btnCotizar").hide();
  changeTitlePage();
  $("#formValores").show();
  $("#lblCotAseg").html("DATOS DEL ASEGURADO");
  openDataFormHogar();
  openValAllianz();
} else if (getParams("fechaInicialCotizaciones").length > 0) {
  menosViewCot();
}

$("#daterange-btnCotizacionesHogar").daterangepicker(
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
    $("#daterange-btnCotizacionesHogar span").html(
      startDate.format("MMMM D, YYYY") + " - " + endDate.format("MMMM D, YYYY")
    );
    var fechaInicialCotizaciones = startDate.format("YYYY-MM-DD");
    var fechaFinalCotizaciones = endDate.format("YYYY-MM-DD");
    var capturarRango = $("#daterange-btnCotizacionesHogar span").html();
    localStorage.setItem("capturarRango2", capturarRango);
    var selectedOption = $("#daterange-btnCotizacionesHogar").data(
      "daterangepicker"
    ).chosenLabel;
    localStorage.setItem("Selected2", selectedOption);
    window.location =
      "index.php?ruta=hogar&fechaInicialCotizaciones=" +
      fechaInicialCotizaciones +
      "&fechaFinalCotizaciones=" +
      fechaFinalCotizaciones;
  }
);

let selected = localStorage.getItem("Selected2");
switch (selected) {
  case "Hoy":
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setStartDate(moment());
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Ayer":
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "days"));
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "days"));
    break;
  case "Últimos 7 días":
    Ay;
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setStartDate(moment().subtract(7, "days"));
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Últimos 30 días":
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setStartDate(moment().subtract(30, "days"));
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  case "Este mes":
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setStartDate(moment().startOf("month"));
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setEndDate(moment().endOf("month"));
    break;
  case "Último mes":
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setStartDate(moment().subtract(1, "month").startOf("month"));
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setEndDate(moment().subtract(1, "month").endOf("month"));
    break;
  case "Últimos 3 meses":
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setStartDate(moment().subtract(3, "month").startOf("month"));
    $("#daterange-btnCotizacionesHogar")
      .data("daterangepicker")
      .setEndDate(moment());
    break;
  default:
    break;
}

$("#daterange-btnCotizacionesHogar").on(
  "cancel.daterangepicker",

  function (ev, picker) {
    localStorage.removeItem("capturarRango2");

    localStorage.clear();

    window.location = "hogar";
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
      "index.php?ruta=hogar&" +
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

function editarCotizacionHogar(id) {
  idCotizacionHogar = id; // Almacena el Id en la variable global de idCotización
  //console.log(id);
  var datos = new FormData();

  datos.append("idCotizacionHogar", idCotizacionHogar);

  $.ajax({
    url: "ajax/cotizaciones.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (response) {
      console.log(response);

      // $(".contentsAllianz").find("input, select, radio").each(function () {
      //   console.log(this); // 'this' es el elemento actual
      // });
      $(".general-container-aseg")
        .find("input, select, radio")
        .prop("disabled", true);

      let {
        anio_construccion,
        area_total,
        ciudad,
        cli_apellidos,
        cli_nombre,
        cli_email,
        cli_num_documento,
        id_tipo_documento,
        cli_telefono,
        direccion,
        departamento,
        zona_riesgo,
        tipo_vivienda,
        tipo_construccion,
        tipo_asegurado,
        tipo_cobertura,
        no_piso,
        no_total_pisos,
        credito,
        zona_construccion,
        tot_cn_sus_sbs,
        tot_cnn_sbs,
        tot_cobertura_basica_sbs,
        val_asegee_danos_sbs,
        val_asegee_sus_sbs,
        val_cn,
        val_cnelec_sbs,
        val_cnen_sbs,
        val_cnesp_sus_sbs,
        val_cnnor_sus_sbs,
        val_hur,
        val_tr,
        aseg_mascota,
        val_tr_sbs,
        val_viv,
        val_viv_sbs,
      } = response;

      const fields = [
        "nombre",
        "apellido",
        "tipoDocumento",
        "celular",
        "numeroDocumento",
        "correo",
      ];

      let id_tipo_documentoV =
        id_tipo_documento == "1" ? "C" : id_tipo_documento == "2" ? " " : "X";

      // DATOS DEL ASEGURADO

      fields.forEach((field) => {
        $(`.${field}`).prop("disabled", true);
      });

      $(".tipoDocumento").val(id_tipo_documentoV).trigger("change");
      $(".nombre").val(cli_nombre);
      $(".apellido").val(cli_apellidos);
      $(".numeroDocumento").val(cli_num_documento);
      $(".celular").val(cli_telefono);
      $(".correo").val(cli_email);

      // DATOS DEL BIEN ASEGURADO

      $(".dirInmueble").val(direccion);
      $("#deptoInmueble").append(new Option(departamento, "1000"));
      $("#deptoInmueble").val("1000").trigger("change");
      $(".ciudadInmueble").append(new Option(ciudad, "1000"));
      $(".ciudadInmueble").val("1000").trigger("change");
      $(".zonaRiesgo").append(new Option(zona_riesgo, "1000"));
      $(".zonaRiesgo").val("1000").trigger("change");
      $(".tipoVivienda").val(tipo_vivienda).trigger("change");
      $(".noPiso").val(no_piso).trigger("change");
      $(".noPisosEdi").val(no_total_pisos).trigger("change");
      $(".tipoConstruccion").val(tipo_construccion).trigger("change");
      $(".anioConstruccion").val(anio_construccion).trigger("change");
      $(".areaTotal").val(area_total).trigger("change");
      $(".zonaConstruccion").val(zona_construccion).trigger("change");
      $(".tipoAseg").val(tipo_asegurado).trigger("change");
      $(`#${tipo_cobertura}`).prop("checked", true).trigger("click");
      $(`#${credito}Credito`).prop("checked", true).trigger("click");

      // campos Allianz
      $("#valorViviendaAllianz").val(val_viv || 0);
      $("#valorContenidosAllianz").val(val_cn);
      $("#valorHurtoAllianz").val(val_hur);
      $("#valorTodoRiesgoAllianz").val(val_tr);

      $(".contentsAllianz").prop("disabled", true);

      $("#btnAllianzCot").css("display", "none");

      if (aseg_mascota == "GA") {
        $("#siGato").prop("checked", true);
      } else if (aseg_mascota == "PE") {
        $("#siPerro").prop("checked", true);
      } else {
        $("#no").prop("checked", true);
      }

      $(".inputsAllianz").prop("disabled", true);

      // campos SBS

      if (val_viv_sbs != null) {
        $("#valorVivienda").val(val_viv_sbs || 0);
        $("#siSBS").prop("checked", true).trigger("change");
      } else {
        $("#noSBS").prop("checked", true).trigger("change");
      }

      $("#valorEnseres").val(val_cnen_sbs == null ? 0 : val_cnen_sbs);
      $("#valorEquipoElectrico").val(
        val_cnelec_sbs == null ? 0 : val_cnelec_sbs
      );
      $("#valorCEspeciales").val(
        val_cnesp_sus_sbs == null ? 0 : val_cnesp_sus_sbs
      );
      $("#totalContenidos").val(tot_cnn_sbs == null ? 0 : tot_cnn_sbs);
      $("#totalCoberturaBasica").val(
        tot_cobertura_basica_sbs == null ? 0 : tot_cobertura_basica_sbs
      );
      $("#contentNormalesSUS").val(
        val_cnnor_sus_sbs == null ? 0 : val_cnnor_sus_sbs
      );
      $("#contEspecialesSUS").val(
        val_cnesp_sus_sbs == null ? 0 : val_cnesp_sus_sbs
      );
      $("#totalContHurtoSus").val(tot_cn_sus_sbs == null ? 0 : tot_cn_sus_sbs);
      $("#valorAseguradoD").val(
        val_asegee_danos_sbs == null ? 0 : val_asegee_danos_sbs
      );
      $("#valorAsegSUSEE").val(
        val_asegee_sus_sbs == null ? 0 : val_asegee_sus_sbs
      );
      $("#valorTodoRiesgo").val(val_tr_sbs == null ? 0 : val_tr_sbs);

      $(".inputNumber").prop("disabled", true);

      // Construye la tabla de ofertas (Resumen de cotizaciones)

      let alerts = {
        alertasHogar: true,
        cotizacion: idCotizacionHogar,
      };

      $.ajax({
        url: "ajax/alerta_aseguradora.ajax.php",
        method: "POST",
        data: JSON.stringify(alerts),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
          makeATable()
          response.forEach((alert) => {
            alert.cotizo == 1
              ? $(`#${alert.aseguradora}-check`).html(
                  `<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>`
                )
              : $(`#${alert.aseguradora}-check`).html(
                  `<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 5px;"></i>`
                );
            $(`#${alert.aseguradora}-offerts`).html(alert.num_ofertas);
            $(`#${alert.aseguradora}-observations`).html(alert.mensajes);
          });
        },
      });

      // Construye las cards de cotizaciónes
      let form = new FormData();
      form.append("idCotizacionHogarOfferts", idCotizacionHogar);

      $.ajax({
        url: "ajax/cotizaciones.ajax.php",
        method: "POST",
        data: form,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
          console.log(response);
          $("#parrillaCards").show();
          $("#resumenCotizaciones").show();

          makeCards(response, 2);
        },
      });
    },

    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud AJAX:");
      console.error("Estado:", textStatus);
      console.error("Error:", errorThrown);
      console.error("Respuesta del servidor:", jqXHR.responseText);
    },
  });
}
