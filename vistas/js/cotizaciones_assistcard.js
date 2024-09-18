$(".tablas-assistcard").on(
  "click",
  ".btnEditarCotizacionAssistCard",
  function () {
    var idCotizacionAssistCard = $(this).attr("idCotizacionAssistCard");

    window.location =
      "index.php?ruta=retomar-cotizacion-assistcard&idCotizacionAssistCard=" +
      idCotizacionAssistCard;
  }
);

let getParams = (param) => { 
  var urlPage = new URL(window.location.href); // Instancia la URL Actual
  var options = urlPage.searchParams.getAll(param); //Buscar todos los parametros
  return options;
}


function changeTitlePage() {
  var newTittle = "Datos del Viaje";
  $("#lblDataTrip").text(newTittle);
}
if (getParams("idCotizacionAssistCard").length > 0) {
  editarCotizacionAssistcard(getParams("idCotizacionAssistCard")[0]);
  changeTitlePage();
} else if(getParams("fechaInicialCotizaciones").length > 0){
  menosCotizaciones();
}

console.log()

$("#daterange-btnCotizacionesAssistCard").daterangepicker(
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
    $("#daterange-btnCotizacionesAssistCard span").html(
      startDate.format("MMMM D, YYYY") +
        " - " +
        endDate.format("MMMM D, YYYY")
    );
    var fechaInicialCotizaciones = startDate.format("YYYY-MM-DD");
    var fechaFinalCotizaciones = endDate.format("YYYY-MM-DD");
    var capturarRango = $("#daterange-btnCotizacionesAssistCard span").html();
    localStorage.setItem("capturarRango2", capturarRango);
    var selectedOption = $("#daterange-btnCotizacionesAssistCard").data(
      "daterangepicker"
    ).chosenLabel;
    localStorage.setItem("Selected2", selectedOption);
    window.location =
      "index.php?ruta=assistcard&fechaInicialCotizaciones=" +
      fechaInicialCotizaciones +
      "&fechaFinalCotizaciones=" +
      fechaFinalCotizaciones;
  }
);

let selected = localStorage.getItem("Selected2");
  switch (selected) {
    case "Hoy":
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setStartDate(moment());
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    case "Ayer":
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setStartDate(moment().subtract(1, "days"));
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setEndDate(moment().subtract(1, "days"));
      break;
    case "Últimos 7 días":
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setStartDate(moment().subtract(7, "days"));
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    case "Últimos 30 días":
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setStartDate(moment().subtract(30, "days"));
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    case "Este mes":
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setStartDate(moment().startOf("month"));
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setEndDate(moment().endOf("month"));
      break;
    case "Último mes":
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setStartDate(moment().subtract(1, "month").startOf("month"));
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setEndDate(moment().subtract(1, "month").endOf("month"));
      break;
    case "Últimos 3 meses":
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setStartDate(moment().subtract(3, "month").startOf("month"));
      $("#daterange-btnCotizacionesAssistCard")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    default:
      break;
  }

  $("#daterange-btnCotizacionesAssistCard").on(
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
        "index.php?ruta=assistcard&" +
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

function editarCotizacionAssistcard(id) {
  idCotizacionAssistCard = id; // Almacena el Id en la variable global de idCotización
  console.log(id)
  var datos = new FormData();

  datos.append("idCotizacionAssistCard", idCotizacionAssistCard);

  /*=============================================			
  
    INFORMACION DEL ASEGURADO Y DEL VEHICULO
  
    =============================================*/

  $.ajax({
    url: "ajax/cotizaciones.ajax.php",

    method: "POST",

    data: datos,

    cache: false,

    contentType: false,

    processData: false,

    dataType: "json",

    success: function (respuesta) {
      /* FORMULARIO INFORMACIÓN DEL ASEGURADO */
      // Esta funcion se encuentra en eventCotizarAssistCard.js se usa desde alla para no crearla nuevamente.
      cargarEstilos("vistas/modulos/AssistCardCot/css/cards.css");

      $("#nombreProspectoResumen").val(respuesta["nom_prospecto"]);

      const fecha = respuesta["fch_nacimiento"].split("-");

      $("#anionacimientoResumen").append(
        "<option value='" + fecha[0] + "' selected>" + fecha[0] + "</option>"
      );
      $("#mesnacimientoResumen").append(
        "<option value='" + fecha[1] + "' selected>" + fecha[1] + "</option>"
      );
      $("#dianacimientoResumen").append(
        "<option value='" + fecha[2] + "' selected>" + fecha[2] + "</option>"
      );

      //  $("#lugarOrigenResumen").val(respuesta["lugar_destino"]);

      $("#lugarOrigenResumen").append(
        "<option value='" +
          respuesta["lugar_origen"] +
          "' selected>" +
          respuesta["lugar_origen"] +
          "</option>"
      );

      $("#lugarDestinoResumen").append(
        "<option value='" +
          respuesta["lugar_destino"] +
          "' selected>" +
          respuesta["lugar_destino"] +
          "</option>"
      );

      $("#fechaSalidaResumen").val(respuesta["fch_salida"]);
      $("#fechaRegresoResumen").val(respuesta["fch_regreso"]);

      $("#motivoViajeResumen").append(
        "<option value='" +
          respuesta["modalidad_cot"] +
          "' selected>" +
          respuesta["modalidad_cot"] +
          "</option>"
      );

      $("#numeroDiasResumen").val(respuesta["num_dias"]);

      var fechaNacimientoStr = fecha[2] + "/" + fecha[1] + "/" + fecha[0];

      var partesFecha = fechaNacimientoStr.split("/");
      var fechaNacimiento = new Date(
        partesFecha[2],
        partesFecha[1] - 1,
        partesFecha[0]
      );

      // Obtener la fecha actual
      var fechaActual = new Date();

      // Calcular la diferencia en milisegundos entre las dos fechas
      var diferencia = fechaActual.getTime() - fechaNacimiento.getTime();

      // Calcular la edad en años
      var edadPrincipalParaVerDetalles = Math.floor(
        diferencia / (1000 * 60 * 60 * 24 * 365.25)
      );

      /*=============================================			
   
         // CONSULTA LAS OFERTAS DE LA COTIZACION
   
         =============================================*/

      var datos2 = new FormData();

      datos2.append("ofertasCotizacion", idCotizacionAssistCard);

      const calcCobertura = (cobertura, modalidad) => {
        if (modalidad == "Vacacional") {
          if (cobertura == "35" && modalidad != "Estudiantil") {
            return cobertura + ".000";
          } else if (cobertura == "60" && modalidad != "Estudiantil") {
            return cobertura + ".000";
          } else if (cobertura == "150" && modalidad != "Estudiantil") {
            return cobertura + ".000";
          } else if (cobertura == "250" && modalidad != "Estudiantil") {
            return cobertura + ".000";
          }
        } else if (modalidad == "Empresarial") {
          if (cobertura == "60" && modalidad != "Estudiantil") {
            return cobertura + ".000";
          } else if (cobertura == "150" && modalidad != "Estudiantil") {
            return cobertura + ".000";
          } else if (cobertura == "250" && modalidad != "Estudiantil") {
            return cobertura + ".000";
          }
        } 
      };
      $.ajax({
        url: "ajax/cotizaciones.ajax.php",

        method: "POST",

        data: datos2,

        cache: false,

        contentType: false,

        processData: false,

        dataType: "json",

        success: async function (respuesta) {
          $("#containerCardsResum").css("display", "block");
          var html_cards = "";
          if (respuesta.length > 0) {
            respuesta.forEach(function (oferta, i) {
              const cobertura = oferta.producto.split(" ").at(1);
              html_cards += ` 
                                <div class='card-ofertas'>
                                  <div class='row card-body'>
                                      <div class="col-xs-12 col-sm-6 col-md-2 align-horizontal ">
                                          <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                      </div>
    
                                      <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                          <span class="tittleCard">
                                              Assist Card - ${changeNameProduct(
                                                oferta.codigo,
                                                oferta.producto
                                              )} ${
                oferta.tipo_modalidad == "Estudiantil"
                  ? regionConvert(oferta.codigo_tarifa)
                  : ""
              }
                                          </span><br> 
                                          <span class="tittleCard">
                                          ${
                                            oferta.tipo_modalidad ==
                                            "Estudiantil"
                                              ? "ESTUDIANTIL"
                                              : oferta.tipo_modalidad ==
                                                "Empresarial"
                                              ? "CORPORATIVO"
                                              : oferta.tipo_modalidad ==
                                                "Vacacional"
                                              ? "VACACIONAL"
                                              : oferta.tipo_modalidad
                                          }
                                          </span><br> 
                                          <span class="tittlePrice">
                                              Desde USD $${oferta.precio}
                                          </span><br> 
                                      </div>
    
                                      <div class="col-xs-12 col-sm-6 col-md-3 textCards">                
                                          <ul>
                                              <li>Cobertura USD ${calcCobertura(
                                                cobertura,
                                                oferta.tipo_modalidad
                                              )} </li>
                                              <li>Cobertura de accidentes</li>
                                              <li>Cobertura por enfermedades no preexistente</li>
                                              <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                              <li>Traslado ejecutivo por reemplazo de funcionario asistido</li>
                                          </ul>
                                      </div>
    
                                      <div class="col-xs-12 col-sm-6 col-md-3 textCards">
                                          <ul>
                                              <li>Odontología de urgencia</li>
                                              <li>Repatriación Sanitaria derivada de una atención médica</li>
                                              <li>Repatriación funeraria</li>
                                              <li>Seguro de equipaje ante demora y pérdida</li>
                                              <li>Cobertura salvoconducto ante perdida de pasaporte</li>
                                          </ul>
                                      </div>
    
                                      <div class="col-xs-12 col-sm-6 col-md-2 colPdf">
                                            <span> Muchas más <br>
                                            </span>
                                            <span> coberturas  
                                            <span class="bigEmoji">👇🏼</span>  
                                            </span>
                                              <button class="btn btn-info btn-block btn-pdf" id="">
                                                  <span class="span_titulo_item">
                                                      <a target="_blank" class="btnText" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${
                                                        oferta.pais
                                                      }&producto=${
                oferta.codigo
              }&tarifa=${
                oferta.codigo_tarifa
              }&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${
                oferta.cantidad_dias == 365 ? `True` : `False`
              }'>Ver detalles</a>
                                                  </span>
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          `;
            });
          } else {
            $("#loaderOferta").html("");

            swal
              .fire({
                type: "warning",

                title: "¡ UPS, Lo Sentimos !",

                text: "¡ No hay ofertas disponibles para esta cotización !",

                showConfirmButton: true,

                confirmButtonText: "Cerrar",
              })
              .then((result) => {
                if (result.isConfirmed) {
                  window.location.href = "assistcard";
                } else {
                  window.location.href = "assistcard";
                }
              });
          }
          $("#row_contenedor_general").html(html_cards);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la solicitud AJAX:");
          console.error("Estado:", textStatus);
          console.error("Error:", errorThrown);
          console.error("Respuesta del servidor:", jqXHR.responseText);
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
