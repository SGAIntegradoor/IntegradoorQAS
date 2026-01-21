function loadAnalistas() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "ajax/analistas.ajax.php",
      type: "POST",
      success: function (data) {
        let dat = JSON.parse(data);

        $("#analistaGA").append(dat.options);
        $("#txtAnalistaGAModal").append(dat.options);
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

function loadAllFreelance() {
  let dat = new FormData();
  dat.append("param", "all");
  $.ajax({
    url: "ajax/freelances.ajax.php",
    type: "POST",
    data: dat,
    cache: false, // Importante para evitar problemas con objetos dinámicos
    contentType: false, // Necesario para enviar `FormData`
    processData: false, // Evita que jQuery intente procesar `FormData`
    success: function (response) {
      $("#txtAsesorOportunidadModal").append(response);
    },
    error: function (error) {},
  });
}

function reset() {
  window.location.href = "negocios";
}

function obtenerFechaActual() {
  const hoy = new Date();
  const año = hoy.getFullYear();
  const mes = String(hoy.getMonth() + 1).padStart(2, "0"); // Los meses van de 0 a 11
  const día = String(hoy.getDate()).padStart(2, "0");

  return `${año}-${mes}-${día}`;
}

function cleanFields() {
  // Limpiar los campos de texto
  $("#txtnoCotizacionModal").val("");
  $("#txtValorCotizacionModal").val("");
  $("#txtPlacaOportunidadModal").val("");
  $("#txtPlacaOportunidadModal").prop("disabled", false);
  $("#txtAseguradoModal").val("");
  $("#txtOtraRazonOportunidadModal").val("");
  $("#txtAnalistaGAModal").val("").trigger("change");;
  $("#txtObservacionesOportunidadModal").val("");
  $("#txtnoCotAseguradoraModal").val("");

  // Restablecer selects al valor por defecto
  $("#txtMesOportunidadModal").val(null).trigger("change"); // Restablece al valor por defecto
  $("#txtCanalModal").val(null).trigger("change"); // Restablece al valor por defecto
  $("#txtRazonPerdidoOportunidadModal").val(null).trigger("change"); // Restablece al valor por defecto
  $("#txtAsesorOportunidadModal").val('').trigger("change"); // Restablece al valor por defecto
  $("#txtRamoModal").val(null).trigger("change"); // Restablece al valor por defecto
  $("#txtOnerosoOportunidadModal").val(null).trigger("change"); // Restablece al valor por defecto
  $("#txtAseguradoraOportunidadModal").val(null).trigger("change"); // Restablece al valor por defecto
  $("#txtEstadoOportunidadModal").val(null).trigger("change"); // Restablece al valor por defecto
}

function obtenerMesActual() {
  const meses = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
  ];

  const mesActual = new Date().getMonth(); // Retorna un valor entre 0 y 11
  return meses[mesActual]; // Devuelve el nombre del mes
}

function selectByText(selector, text) {
  // Encuentra el valor asociado al texto

  let valueToSelect = $(selector + " option")
    .filter(function () {
      return $(this).text().trim() === text;
    })
    .val();

  // Selecciona el valor y activa el evento change
  if (valueToSelect) {
    $(selector).val(valueToSelect).trigger("change");
  } else {
    if ($("txtEstadoOportunidadModal").val() == "Emitida") {
      console.log(
        `No se encontró una opción con el texto: "${text}" en ${selector}`
      );
    } else {
      return;
    }
  }
}

$("#txtRamoModal").on("change", function () {
  let selectedValue = $(this).val();
  if (selectedValue != "1" && selectedValue != "2" && selectedValue != "3" && selectedValue != "13") {
    $("#txtPlacaOportunidadModal").prop("disabled", true);
    $("#txtPlacaOportunidadModal").val("NA");
  } else {
    $("#txtPlacaOportunidadModal").val("");
    $("#txtPlacaOportunidadModal").prop("disabled", false);
  }
});

$("#txtFechaExpedicionOportunidadModal").on("change", function () {
  let selectedValue = $(this).val();
  let mes = selectedValue.split("-");

  if (mes[1].charAt(0) == 0) {
    mes[1] = mes[1].charAt(1);
  }
  $("#txtMesExpedicionOportunidadModal").val(mes[1]).trigger("change");
});

$(".sorting_1").css("text-align", "center");

function abrirDialogoCrear(id = null) {
  cleanFields();
  // Configurar el diálogo
  $("#myModal2").dialog({
    title: "Agregar/editar oportunidad",

    autoOpen: false,
    resizable: false, // Desactiva el redimensionamiento
    draggable: false, // Opcional, si deseas permitir que se pueda mover
    modal: true,
    width: 850,
    dialogClass: "custom-dialog2",

    buttons: {
      Cerrar: function () {
        $(this).dialog("close");
      },
      Guardar: function () {
        const form = $("#myModal2 form")[0];

        // Validar el formulario
        if (!form.checkValidity()) {
          // Si hay campos inválidos, mostrará los mensajes de error nativos del navegador
          form.reportValidity();
          return; // Detener la ejecución si hay errores
        }

        // Se valida previamente que los campos este completos
        // En caso de no estarlos debe dar un error marcando que campo debe ser llenado en el formulario o modal

        let noCotizacion = $("#txtnoCotizacionModal").val();
        let noCotAseguradora = $("#txtnoCotAseguradoraModal").val();
        let valorCotizacion = $("#txtValorCotizacionModal").val();

        valorCotizacion = valorCotizacion.replace(/\.|\$/g, "").trim();

        let mesOportunidad = $(
          "#txtMesOportunidadModal option:selected"
        ).text();
        let canalOportunidad = $("#txtCanalModal option:selected").text();
        let razonPerdidaOportunidad = $(
          "#txtRazonPerdidoOportunidadModal option:selected"
        ).text();
        let asesor_freelance = $(
          "#txtAsesorOportunidadModal option:selected"
        ).text();
        let id_asesor_freelance = $("#txtAsesorOportunidadModal").val();
        let ramo = $("#txtRamoModal option:selected").text();
        let placaModal = $("#txtPlacaOportunidadModal").val();
        let oneroso = $("#txtOnerosoOportunidadModal option:selected")
          .text()
          .trim();
        let aseguradora = $(
          "#txtAseguradoraOportunidadModal option:selected"
        ).text();
        let asesorGa = $("#txtAnalistaGAModal option:selected").text();
        let estado = $("#txtEstadoOportunidadModal option:selected").text();
        let noPoliza = $("#txtNoPolizaOportunidadModal").val();
        let asegurado = $("#txtAseguradoModal").val();
        let otraRazon = $("#txtOtraRazonOportunidadModal").val();
        let primaSinIva = $("#txtPrimaSinIvaModal").val();
        let gastos = $("#txtGastosOportunidadModal").val();
        let asistencias = $("#txtAsistOtrosOportunidadModal").val();
        let iva = $("#txtIvaOportunidadModal").val();
        let valorTotal = $("#txtValorTotalModal").val();
        let fechaExpedicion = $("#txtFechaExpedicionOportunidadModal").val();
        if (fechaExpedicion === "") {
          fechaExpedicion = null; // Enviar NULL si el campo está vacío
        }
        let mesExpedicion = $(
          "#txtMesExpedicionOportunidadModal option:selected"
        ).text();
        let formaPago = $(
          "#txtFormaDePagoOportunidadModal option:selected"
        ).text();
        let financiera = $(
          "#txtFinancieraOportunidadModal option:selected"
        ).text();
        let checkCarpeta = $("#checkCarpetaModal").prop("checked");
        let observaciones = $("#txtObservacionesOportunidadModal").val();
        let fechaCreacion = obtenerFechaActual();

        var data = new FormData();

        let url;

        if (id != null && id != "") {
          url = "ajax/updateOportunidad.ajax.php";
          //id_oportunidad
          data.append("id", id);
          data.append("idCotizacion", noCotizacion);
          data.append("idCotAseguradora", noCotAseguradora);
          data.append("valor_cotizacion", valorCotizacion);
          data.append("idOferta", 0);
          data.append("mesOportunidad", mesOportunidad);
          data.append("canalOportunidad", canalOportunidad);
          data.append("razonPerdidaOportunidad", razonPerdidaOportunidad);
          data.append("asesor_freelance", asesor_freelance);
          data.append("id_user_freelance", id_asesor_freelance);
          data.append("ramo", ramo);
          data.append("placa", placaModal);
          data.append("oneroso", oneroso);
          data.append("aseguradora", aseguradora);
          data.append("analista_comercial", asesorGa);
          data.append("id_analista_comercial", permisos.id_usuario);
          data.append("estado", estado);
          data.append("asegurado", asegurado);
          data.append("otraRazon", otraRazon);
          //numero de poliza
          data.append(
            "noPoliza",
            noPoliza.trim() === "" ? "" : noPoliza.trim()
          );
          data.append("id_asegurado", 0);
          data.append("prima_sin_iva", primaSinIva === "" ? null : primaSinIva);
          data.append("gastos", gastos === "" ? null : gastos);
          data.append("asistencias", asistencias === "" ? null : asistencias);
          data.append("iva", iva === "" ? null : iva);
          data.append("valorTotal", valorTotal === "" ? null : valorTotal);
          data.append("fechaExpedicion", fechaExpedicion);
          data.append(
            "mesExpedicion",
            mesExpedicion === "" ? null : mesExpedicion
          );
          data.append("formaDePago", formaPago === "" ? null : formaPago);
          data.append(
            "financiera",
            financiera.trim() === "" ? "" : financiera.trim()
          );
          data.append(
            "carpeta",
            checkCarpeta ? "Carpeta creada" : "Sin carpeta"
          );
          data.append("observaciones", observaciones);
          data.append("fechaActualizacion", fechaCreacion);
        } else {
          url = "ajax/oportunidades.ajax.php";
          //id_oportunidad
          data.append("idCotizacion", noCotizacion);
          data.append("idCotAseguradora", noCotAseguradora);
          data.append("valor_cotizacion", valorCotizacion);
          data.append("idOferta", 0);
          data.append("mesOportunidad", mesOportunidad);
          data.append("canalOportunidad", canalOportunidad);
          data.append("razonPerdidaOportunidad", razonPerdidaOportunidad);
          data.append("asesor_freelance", asesor_freelance);
          data.append("id_user_freelance", id_asesor_freelance);
          data.append("ramo", ramo);
          data.append("placa", placaModal);
          data.append("oneroso", oneroso);
          data.append("aseguradora", aseguradora);
          data.append("analista_comercial", asesorGa);
          data.append("id_analista_comercial", permisos.id_usuario);
          data.append("estado", estado);
          data.append("asegurado", asegurado);
          data.append("otraRazon", otraRazon);
          //numero de poliza
          data.append(
            "noPoliza",
            noPoliza.trim() === "" ? "" : noPoliza.trim()
          );
          data.append("id_asegurado", 0);
          data.append("prima_sin_iva", primaSinIva === "" ? null : primaSinIva);
          data.append("gastos", gastos === "" ? null : gastos);
          data.append("asistencias", asistencias === "" ? null : asistencias);
          data.append("iva", iva === "" ? null : iva);
          data.append("valorTotal", valorTotal === "" ? null : valorTotal);
          data.append("fechaExpedicion", fechaExpedicion);
          data.append(
            "mesExpedicion",
            mesExpedicion === "" ? null : mesExpedicion
          );
          data.append("formaDePago", formaPago === "" ? null : formaPago);
          data.append("financiera", financiera === "" ? "" : financiera);
          data.append(
            "carpeta",
            checkCarpeta ? "Carpeta creada" : "Sin carpeta"
          );
          data.append("observaciones", observaciones);
          data.append("manual", "manual");
          data.append("fechaCreacion", fechaCreacion);
        }

        // Se ejecuta la peticion por AJAX para llamar a un controlador que se encargara de guardar la data en la base de datos en la tabla "Oportunidades"
        if (errors.length) {
          Swal.fire({
            icon: "error",
            showConfirmButton: true,
            text: `Tienes errores en el formulario, valida nuevamente, error en el campo: ${errors[0]}`,
            confirmButtonText: "Cerrar",
            customClass: {
              container: "swal2-custom-zindex", // Clase personalizada
            },
          }).then((result) => {
            if (result.isConfirmed) {
              return;
            } else if (result.isDismissed) {
              return;
            }
          });
        }
        
        $.ajax({
          url: url,
          method: "POST",
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function (respuesta) {
            if (respuesta.code === 1) {
              $("#myModal2").dialog("close");
              Swal.fire({
                icon: "success",
                text: `Oportunidad # ${
                  id != "" && id != null ? id : respuesta.inserted_id
                } ${
                  id != "" && id != null ? "actualizada" : "creada"
                } con éxito`,
                showConfirmButton: true,
                confirmButtonText: "Ok",
              }).then((result) => {
                if (result.isConfirmed) {
                  $("#myModal2").dialog("close");
                  //$("#myModal2").dialog("close"); // Cerrar el modal
                  window.location.reload(); // Recargar la página (opcional)
                } else if (result.isDismissed) {
                  $("#myModal2").dialog("close"); // Cerrar el modal
                  window.location.reload(); // Recargar la página (opcional)
                }
              });
            } else {
              Swal.fire({
                icon: "error",
                showConfirmButton: true,
                text: `Error al intentar crear la oportunidad, comuníquese con el administrador del sistema`,
                confirmButtonText: "Cerrar",
              }).then((result) => {
                if (result.isConfirmed) {
                  return;
                } else if (result.isDismissed) {
                  return;
                }
              });
            }
          },
          error: function () {
            console.log("Error al obtener los datosj");
          },
        });
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
      $(".ui-dialog-buttonpane button:contains('Guardar')").attr(
        "id",
        "btnGuardar"
      );
      $(".ui-dialog-buttonpane button:contains('Guardar')").attr(
        "type",
        "submit"
      );
      if (id != null) {
        $("#btnGuardar").html("Editar");
        let dataEdit = new FormData();
        dataEdit.append("id_oportunidad_edit", id);
        $.ajax({
          url: "ajax/cargarOportunidad.ajax.php",
          method: "POST",
          data: dataEdit,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function (respuesta) {
            if (
              respuesta[0].id_oportunidad != null ||
              respuesta[0].id_oportunidad != ""
            ) {
              $("#txtnoCotizacionModal").val(respuesta[0].id_cotizacion);
              $("#txtnoCotAseguradoraModal").val(
                respuesta[0].id_cot_aseguradora
              );
              $("#txtValorCotizacionModal").val(
                `$ ${new Intl.NumberFormat("co-CO").format(
                  respuesta[0].valor_cotizacion
                )}`
              );
              selectByText(
                "#txtMesOportunidadModal",
                respuesta[0].mes_oportunidad
              );
              selectByText("#txtCanalModal", respuesta[0].canal_oportunidad);
              selectByText(
                "#txtRazonPerdidoOportunidadModal",
                respuesta[0].razon_negocio_perdido
              );

              $("#txtAsesorOportunidadModal")
                .val(respuesta[0].id_user_freelance)
                .trigger("change");
              selectByText("#txtRamoModal", respuesta[0].ramo);
              $("#txtPlacaOportunidadModal").val(respuesta[0].placa);
              selectByText("#txtOnerosoOportunidadModal", respuesta[0].oneroso);
              selectByText(
                "#txtAseguradoraOportunidadModal",
                respuesta[0].aseguradora
              );
              selectByText(
                "#txtAnalistaGAModal",
                respuesta[0].analista_comercial
              );
              selectByText("#txtEstadoOportunidadModal", respuesta[0].estado);
              $("#txtNoPolizaOportunidadModal").val(respuesta[0].no_poliza);
              $("#txtAseguradoModal").val(respuesta[0].asegurado);
              $("#txtOtraRazonOportunidadModal").val(
                respuesta[0].otra_razon_negocio_perdido
              );
              selectByText(
                "#txtAseguradoraOportunidadModal",
                respuesta[0].aseguradora
              );

              if ($("#txtEstadoOportunidadModal").val() == "6") {
                $("#perdidaHide").show();
                if ($("#txtRazonPerdidoOportunidadModal").val() == "Otro") {
                  $("#divOtraRazon").show();
                }
              }

              $("#txtPrimaSinIvaModal").val(respuesta[0].prima_sin_iva);
              $("#txtAsistOtrosOportunidadModal").val(respuesta[0].asist_otros);
              $("#txtGastosOportunidadModal").val(respuesta[0].gastos);
              $("#txtIvaOportunidadModal").val(respuesta[0].iva);
              $("#txtValorTotalModal").val(respuesta[0].valor_total);
              $("#txtFechaExpedicionOportunidadModal").val(
                respuesta[0].fecha_expedicion
              );
              selectByText(
                "#txtMesExpedicionOportunidadModal",
                respuesta[0].mes_expedicion
              );
              selectByText(
                "#txtFormaDePagoOportunidadModal",
                respuesta[0].forma_pago
              );
              selectByText(
                "#txtFinancieraOportunidadModal",
                respuesta[0].financiera
              );
              $("#checkCarpetaModal").prop(
                "checked",
                respuesta[0].carpeta !== null &&
                  respuesta[0].carpeta == "Carpeta creada"
                  ? true
                  : false
              );
              $("#txtObservacionesOportunidadModal").val(
                respuesta[0].observaciones == null
                  ? ""
                  : respuesta[0].observaciones
              );
            } else {
              Swal.fire({
                icon: "error",
                showConfirmButton: true,
                text: `Error al intentar cargar la oportunidad, comuníquese con el administrador del sistema`,
                confirmButtonText: "Cerrar",
              }).then((result) => {
                if (result.isConfirmed) {
                  $("#myModal2").dialog("close");
                  return;
                } else if (result.isDismissed) {
                  $("#myModal2").dialog("close");
                  return;
                }
              });
            }
          },
          error: function () {
            console.log("Error al obtener los datos");
          },
        });
      }
      // $("#txtAnalistaGAModal").val(asesorGa);
    },
    close: function () {
      cleanFields();
      $("#txtPlacaOportunidadModal").prop("disabled", false);
      $("#txtPlacaOportunidadModal").val("");
      $("body").css("overflow", "auto");
      $("body").removeClass("modal-open"); // Quita la clase para restaurar el scroll
    },
  });

  // Abrir el diálogo
  $("#myModal2").dialog("open");
}

$(document).ready(function () {
  let inputIDCotizacion = [
    "#txtnoCotizacionModal",
    "#txtValorCotizacionModal",
    "#txtPrimaSinIvaModal",
    "#txtGastosOportunidadModal",
    "#txtAsistOtrosOportunidadModal",
    "#txtIvaOportunidadModal",
    "#txtValorTotalModal",
    "#txtNoPolizaOportunidadModal",
  ];

  $("#txtValorCotizacionModal").numeric();
  $("#txtPrimaSinIvaModal").numeric();
  $("#txtGastosOportunidadModal").numeric();
  $("#txtAsistOtrosOportunidadModal").numeric();
  $("#txtIvaOportunidadModal").numeric();

  const parseNumbersToString = (selector) => {
    $(selector).on("input", function () {
      this.value = this.value.replace(
        /[.,;!?@#$%^&¿¡*¨()_+\-=\[\]{}|\\:"'<>,.?/`~]/g,
        ""
      );
    });

    // Previene el ingreso de puntos desde el teclado
    $(selector).on("keydown", function (event) {
      if (event.which === 190 || event.which === 110) {
        event.preventDefault();
      }
    });
  };

  inputIDCotizacion.map((element) => {
    parseNumbersToString(element);
  });

  $("#txtValorCotizacionModal").on("input", function () {
    let valor = $(this).val();
    let valorFormateado = new Intl.NumberFormat("co-CO").format(valor);
    $(this).val(`$ ${valorFormateado}`);
  });

  loadAllFreelance();
  Promise.all([loadAnalistas(), loadFreelance()])
    .then(() => {
      aplicarCriterios(); // Llama a aplicarCriterios una vez que ambos AJAX han completado
    })
    .catch((error) => {
      console.error("Error al cargar datos:", error);
    });
  location  
});

let getParams = () => {
  let url = new URL(window.location.href);
  return Object.fromEntries(url.searchParams.entries());
};

function aplicarCriterios() {
  const criterios = [
    "mesExpedicion",
    "canal",
    "estado",
    "analistaGA",
    "nombreAsesor",
    "formaDePago",
    "financiera",
    "aseguradoraOpo",
    "ramo",
    "anioOp",
    "carpeta",
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

function editarOportunidad(id) {
  cleanFields();
  abrirDialogoCrear(id);
}

function eliminarOportunidad(id_oportunidad, id_oferta) {
  Swal.fire({
    title: `¿Estás seguro de eliminar la oportunidad # ${id_oportunidad}?`,
    text: "Esta acción no se puede deshacer",
    icon: "warning",
    showCancelButton: true,
    cancelButtonColor: "#000000",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "boton-eliminar",
    },
  }).then((result) => {
    if (result.isConfirmed) {
      let data = new FormData();
      data.append("id_oportunidad", id_oportunidad);
      data.append("id_oferta", id_oferta);
      $.ajax({
        url: "ajax/eliminarOportunidad.ajax.php",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
          if (respuesta.statusCode === 1) {
            Swal.fire("Eliminado!", "La oportunidad ha sido eliminada.").then(
              (result) => {
                window.location.reload();
              }
            );
          } else {
            Swal.fire(
              "error",
              "Error!",
              "La oportunidad no pudo ser eliminada."
            ).then((result) => {
              window.location.reload();
            });
          }
        },
      });
    }
  });
}

function searchInfo() {
  let mesExpedicion =
    $("#mesExpedicion").val() !== ""
      ? $("#mesExpedicion option:selected").text()
      : "";
  let canalOportunidad =
    $("#canal").val() !== "" ? $("#canal option:selected").text() : "";
  let razonPerdidaOportunidad =
    $("#txtRazonPerdidoOportunidadModal").val() !== ""
      ? $("#txtRazonPerdidoOportunidadModal option:selected").text()
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
  let anioOp =
    $("#anioOp").val() !== "" ? $("#anioOp option:selected").text() : "";
  let formaDePago =
    $("#formaDePago").val() !== ""
      ? $("#formaDePago option:selected").text()
      : "";
  let financiera =
    $("#financiera").val() !== ""
      ? $("#financiera option:selected").text()
      : "";
  let carpeta =
    $("#carpeta").val() !== "" ? $("#carpeta option:selected").text() : "";

  if (mesExpedicion !== "") {
    url += `&mesExpedicion=${mesExpedicion}`;
  }

  if (canalOportunidad !== "") {
    url += `&canal=${canalOportunidad}`;
  }
  if (razonPerdidaOportunidad !== "") {
    url += `&razonPerdida=${razonPerdidaOportunidad}`;
  }

  if (estado !== "") {
    url += `&estado=${estado}`;
  }

  if (nombreAsesor !== "") {
    url += `&nombreAsesor=${nombreAsesor.replace(/&/g, "%26")}`;
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

  if (anioOp !== "") {
    url += `&anioOp=${anioOp.trim()}`;
  }

  if (formaDePago !== "") {
    url += `&formaDePago=${formaDePago.trim()}`;
  }
  if (financiera !== "") {
    url += `&financiera=${financiera.trim()}`;
  }
  if (carpeta !== "") {
    url += `&carpeta=${carpeta.trim()}`;
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
  "#nombreAsesor, #estado, #canal, #mesExpedicion, #nombreAsesor, #analistaGA, #aseguradoraOpo, #ramo, #anioOp, #formaDePago, #financiera, #carpeta"
).select2({
  theme: "bootstrap selecting",
  allowClear: true,
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Seleccione una opción",
});

$("#txtEstadoOportunidadModal").select2({
  theme: "bootstrap selectingModal",
  allowClear: true,
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Estado",
  dropdownParent: $("#txtEstadoOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtAsesorOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Asesor Freelance",
  dropdownParent: $("#txtAsesorOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtRamoModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Ramo",
  dropdownParent: $("#txtRamoModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtAseguradoraOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Aseguradora",
  dropdownParent: $("#txtAseguradoraOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtOnerosoOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "¿ Tiene oneroso ?",
  dropdownParent: $("#txtOnerosoOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtMesOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Mes Oportunidad",
  dropdownParent: $("#txtMesOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});
// placeholder agregado canal Javier
$("#txtCanalModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Canal",
  dropdownParent: $("#txtCanalModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtAnalistaGAModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Asesor GA",
  dropdownParent: $("#txtAnalistaGAModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtRazonPerdidoOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Razón",
  dropdownParent: $("#txtRazonPerdidoOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});
//fin canal Javier

$("#txtMesExpedicionOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Fecha de expedición",
  dropdownParent: $("#txtMesExpedicionOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});
$("#txtFormaDePagoOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Forma de pago",
  dropdownParent: $("#txtFormaDePagoOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});
$("#txtFinancieraOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Financiera",
  dropdownParent: $("#txtFinancieraOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});
$("#txtMesOportunidadModal").select2({
  allowClear: true,
  theme: "bootstrap selectingModal",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Mes Oportunidad",
  dropdownParent: $("#txtMesOportunidadModal").parent(), // Ubica el dropdown dentro del modal
});

$("#txtCanalModal").on("change", function () {
  if ($(this).val() == 2) {
    $("#divAsesorFreelance").css("display", "block");
    // $("#txtAsesorOportunidadModal").val(null).trigger('change');
    $("#txtAsesorOportunidadModal").prop('disabled', false);
    // $("#txtAsesorOportunidadModal").text('');
    $("#txtAsesorOportunidadModal")[0].required = true;
  } else {
    $("#divAsesorFreelance").css("display", "none");
    // $("#txtAsesorOportunidadModal").val(null).trigger('change');
    $("#txtAsesorOportunidadModal").prop('disabled', true);
    // $("#txtAsesorOportunidadModal").text('');
    $("#txtAsesorOportunidadModal")[0].required = false;
  }
});

$("#txtFormaDePagoOportunidadModal").on("change", function () {
  if ($(this).val() == 1) {
    $("#financieraDiv").css("display", "block");
  } else {
    $("#financieraDiv").css("display", "none");
  }
});

$("#txtRazonPerdidoOportunidadModal").on("change", function () {
  if ($(this).val() == "Otro") {
    $("#divOtraRazon").show();
    $("#txtOtraRazonOportunidadModal")[0].required = true;
  } else {
    $("#txtOtraRazonOportunidadModal").val("");
    $("#divOtraRazon").hide();
    $("#txtOtraRazonOportunidadModal")[0].required = false;
  }
});

$("#txtEstadoOportunidadModal").on("change", function () {
  if ($(this).val() === "4") {
    $("#perdidaHide").hide();
    $("#firstHide").css("display", "block");
    $("#secondHide").css("display", "block");

    $("#txtNoPolizaOportunidadModal")[0].required = true;
    $("#txtOtraRazonOportunidadModal")[0].required = false;
    $("#txtRazonPerdidoOportunidadModal")[0].required = false;
    $("#txtAseguradoModal")[0].required = true;
    $("#txtPrimaSinIvaModal")[0].required = true;
    $("#txtGastosOportunidadModal")[0].required = true;
    $("#txtAsistOtrosOportunidadModal")[0].required = true;
    $("#txtIvaOportunidadModal")[0].required = true;
    $("#txtValorTotalModal")[0].required = true;
    $("#txtFechaExpedicionOportunidadModal")[0].required = true;
    $("#txtMesExpedicionOportunidadModal")[0].required = true;
    $("#txtFormaDePagoOportunidadModal")[0].required = true;
    $("#txtRazonPerdidoOportunidadModal").val("");
    $("#txtOtraRazonOportunidadModal").val("");
  } else if ($(this).val() === "6") {
    console.log('Estado "perdido" seleccionado');
    $("#firstHide").hide();
    $("#perdidaHide").show();
    if ($("#txtRazonPerdidoOportunidadModal").val() == "Otro") {
      $("#divOtraRazon").show();
    }
    $("#divOtraRazon").hide();
    $("#txtRazonPerdidoOportunidadModal")[0].required = true;
  } else {
    $("#txtOtraRazonOportunidadModal").val("");
    $("#txtRazonPerdidoOportunidadModal").val("");
    $("#perdidaHide").hide();
    $("#firstHide").css("display", "none");
    $("#secondHide").css("display", "none");
    $("#financieraDiv").css("display", "none");

    $("#txtOtraRazonOportunidadModal").removeAttr("required");
    $("#txtRazonPerdidoOportunidadModal").removeAttr("required");
    $("#txtNoPolizaOportunidadModal").removeAttr("required");
    $("#txtAseguradoModal").removeAttr("required");
    $("#txtPrimaSinIvaModal").removeAttr("required");
    $("#txtGastosOportunidadModal").removeAttr("required");
    $("#txtAsistOtrosOportunidadModal").removeAttr("required");
    $("#txtIvaOportunidadModal").removeAttr("required");
    $("#txtValorTotalModal").removeAttr("required");
    $("#txtFechaExpedicionOportunidadModal").removeAttr("required");
    $("#txtMesExpedicionOportunidadModal").removeAttr("required");
    $("#txtFormaDePagoOportunidadModal").removeAttr("required");
    $("#txtFinancieraOportunidadModal").removeAttr("required");

    $("#txtNoPolizaOportunidadModal").val("");
    $("#txtPrimaSinIvaModal").val("");
    $("#txtGastosOportunidadModal").val("");
    $("#txtAsistOtrosOportunidadModal").val("");
    $("#txtIvaOportunidadModal").val("");
    $("#txtValorTotalModal").val("");
    $("#txtFechaExpedicionOportunidadModal").val("");
    // $("#txtObservacionesOportunidadModal").val("");

    $("#txtFormaDePagoOportunidadModal").val(null).trigger("change");
    $("#txtFinancieraOportunidadModal").val(null).trigger("change");
    $("#txtMesExpedicionOportunidadModal").val(null).trigger("change");

    $("#checkCarpetaModal").prop("checked", false);
  }
});

function formatNumber(value) {
  // Remueve cualquier carácter que no sea número
  value = value.replace(/\D/g, "");
  // Añade los puntos como separadores de miles
  return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
$("#txtPrimaSinIvaModal").on("change", function () {
  formatNumber(this.value);
});

$(
  "#txtPrimaSinIvaModal, #txtGastosOportunidadModal, #txtAsistOtrosOportunidadModal, #txtIvaOportunidadModal"
).on("change", function () {
  let valor = 0;
  let primaSinIva = $("#txtPrimaSinIvaModal").val();
  let gastos = $("#txtGastosOportunidadModal").val();
  let asistencias = $("#txtAsistOtrosOportunidadModal").val();
  let iva = $("#txtIvaOportunidadModal").val();

  valor =
    Number(primaSinIva) + Number(gastos) + Number(asistencias) + Number(iva);

  $("#txtValorTotalModal").val("$ " + formatNumber(valor));
});

$(
  "#txtPrimaSinIvaModal, #txtGastosOportunidadModal, #txtAsistOtrosOportunidadModal, #txtIvaOportunidadModal"
).on("input", function () {
  let valor = 0;
  let primaSinIva = $("#txtPrimaSinIvaModal").val();
  let gastos = $("#txtGastosOportunidadModal").val();
  let asistencias = $("#txtAsistOtrosOportunidadModal").val();
  let iva = $("#txtIvaOportunidadModal").val();

  valor =
    (Number(primaSinIva) || 0) +
    (Number(gastos) || 0) +
    (Number(asistencias) || 0) +
    (Number(iva) || 0);

  $("#txtValorTotalModal").val("$ " + formatNumber(valor));
});

$(
  "#txtPrimaSinIvaModal, #txtGastosOportunidadModal, #txtAsistOtrosOportunidadModal, #txtIvaOportunidadModal"
).on("paste", function (e) {
  // Detecta el evento de pegar texto
  var pastedData = e.originalEvent.clipboardData.getData("text");

  // Reemplaza los caracteres no numéricos del texto pegado
  var cleanData = pastedData.replace(/[^0-9.]/g, "");

  // Evita que el valor no numérico se pegue
  e.preventDefault();
  // Inserta solo los números o puntos decimales válidos
  document.execCommand("insertText", false, cleanData);
});

let errors = [];

$(document).on("input", "#txtPlacaOportunidadModal", function () {
  var inputValue = $(this).val().toUpperCase(); // Convertir a mayúsculas
  $(this).val(inputValue); // Asignar de nuevo al campo

  // Patrones de validación
  var pattern1 = /^[A-Z]{3}[0-9]{3}$/; // LLLXXX
  var pattern2 = /^[A-Z]{3}[0-9]{2}[A-Z]$/; // LLLXXL
  var pattern3 = /^[A-Z]{1}[0-9]{5}$/; // LXXXXX

  // Validar si el valor cumple con alguno de los patrones
  if (inputValue.length === 6) {
    if (
      pattern1.test(inputValue) ||
      pattern2.test(inputValue) ||
      pattern3.test(inputValue)
    ) {
      $("#errorMensaje").css("display", "none"); // Limpiar el mensaje de error si es válido
      errors = [];
    } else {
      $("#errorMensaje").css("display", "block");
      errors.push("placa");
    }
  } else if (inputValue.length > 6) {
    // Mostrar el mensaje de error solo cuando el input tenga 6 caracteres
    errors.push("placa");
    $("#errorMensaje").css("display", "block");
  }
});

/**
 * Formato de inputs del modal ENDsa
 */

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
  columnDefs: [
    { targets: [25], visible: false, searchable: false }, // Oculta las columnas 10 y 11 (ajusta según el índice de tus columnas ocultas)
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
