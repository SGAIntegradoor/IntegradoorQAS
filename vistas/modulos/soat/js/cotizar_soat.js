function guardarEstado(datos) {
  $.ajax({
    type: "POST",
    url: "src/soat/saveQuotationSoat.php",
    data: datos,
    success: function (data) {
      console.log("saveQuotationSoat ejecutado correctamente", data);
      idCotizacionSoat = data.lastId;
    },
    error: function (error) {
      console.log("Error al guardar cotizacion SOAT: ", error);
    }
  });
};

function enviarEmail(mensaje, idCotiSoatE) {
  showLoader();
  $.ajax({
    type: "POST",
    url: "https://grupoasistencia.com/WS-laravel-email-shetts/api/emails/enviar-correo-soat",
    dataType: "text",
    data: {
      idCotizacion: idCotiSoatE,
      asesor: permisos.usu_nombre + ' ' + permisos.usu_apellido,
      url: 'https://integradoor.com/Pruebas/index.php?ruta=retomar-cotizacion-soat&idCotizacionSoat=' + idCotiSoatE,
      placa: $("#placaVeh").val(),
      clase: $("#txtClaseVeh").val(),
      referencia: $("#txtMarcaVeh").val() + " " + $("#txtLinea").val(),
      prima: $("#valorSoat").text().replace(/\./g, "").replace("$ ", ""),
      totalPagar: $("#totalPagarSoat").text().replace(/\./g, "").replace("$ ", ""),
      correoTomador: $("#correoTomadorSoat").val(),
      celularTomador: $("#celularTomadorSoat").val(),
      opcionPago: $("#radioConComision").is(":checked") ? "Con comision" : "Sin comision",
      comentario: $("#txtComentarios").val(),
      cuerpoCorreo: mensaje,
    },
    cache: false,
    success: function (data) {
      hideLoader();
      console.log("Correo Enviado");
      swal
        .fire({
          icon: "success",
          title: "Solicitud #" + idCotizacionSoat + " actualizada exitosamente",
          showConfirmButton: true,
          confirmButtonText: "Ok",
          allowOutsideClick: false,
          allowEscapeKey: false,
        })
        .then((result) => {
          if (result.isConfirmed) {
            window.location.href = "soat";
          }
        });
    },
    error: function (xhr, status, error) {
      hideLoader();
      swal
        .fire({
          icon: "success",
          title: "Solicitud #" + idCotizacionSoat + " actualizada exitosamente",
          showConfirmButton: true,
          confirmButtonText: "Ok",
          allowOutsideClick: false,
          allowEscapeKey: false,
        })
        .then((result) => {
          if (result.isConfirmed) {
            window.location.href = "soat";
          }
        });
      console.log(error);
      console.log("Error");
      // Pendiente crear registros cuando fallen el servicio de correos
    },
  });
};

var idCotizacionSoat = 0;
var msg = "";

$(document).ready(function () {

  $("#claseVehSoat").select2({
    theme: "bootstrap ciudad",
    language: "es",
    width: "100%",
  });

  var valorSoatGlobal = 0;
  const urlCompleta = window.location.href;

  const partes = urlCompleta.split("/");

  if (partes.includes("dev") || partes.includes("DEV")) {
    env = "dev";
  } else if (
    partes.includes("QAS") ||
    partes.includes("qas") ||
    partes.includes("Pruebas")
  ) {
    env = "qas";
  } else if (partes.includes("app") || partes.includes("App")) {
    env = "";
  }

  var permisos = JSON.parse(permisosPlantilla);

  // Elimina los espacios de la placa
  $("#placaVeh").keyup(function () {
    var numeroInput = document.getElementById("placaVeh").value;
    var placaSinEspacios = numeroInput.replace(/\s/g, "");
    document.getElementById("placaVeh").value = placaSinEspacios;
  });

  // Convierte la Placa ingresada en Mayusculas
  $("#placaVeh").keyup(function () {
    var numPlaca = document.getElementById("placaVeh").value;
    mayuscPlaca = numPlaca.toUpperCase();
    $("#placaVeh").val(mayuscPlaca);
  });

  // Evita Espacios en blanco en el numero de Placa
  $("#placaVeh").on("keypress", function (e) {
    if (e.which == 32) return false;
  });

  // Obtener el campo de entrada por su ID
  var placaInput = document.getElementById("placaVeh");

  // Agregar un evento de escucha para el evento "input"
  placaInput.addEventListener("input", function () {
    // Obtener el valor actual del campo de entrada
    var valor = placaInput.value;

    // Filtrar caracteres especiales y dejar solo letras y números
    var valorFiltrado = valor.replace(/[^a-zA-Z0-9]/g, "");

    // Actualizar el valor del campo de entrada con el valor filtrado
    placaInput.value = valorFiltrado;
  });

  var ceroKilometros = document.getElementById("txtEsCeroKmSi");

  // Función para filtrar caracteres especiales
  function filtrarCaracteresEspeciales(input) {
    var valor = input.value;
    var valorFiltrado = valor.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ ]/g, ""); // Permitir letras, espacios, "ñ" y vocales con tilde
    input.value = valorFiltrado;
  }

  // Si conoce la Placa muestra el campo Placa y oculta el campo CeroKM.
  $("#txtConocesLaPlacaSi").click(function () {
    document.getElementById("contenPlaca").style.display = "block";
    document.getElementById("contenCeroKM").style.display = "none";
    document.getElementById("placaVeh").value = "";
    $("#txtEsCeroKmSi").prop("checked", false);
    $("#txtEsCeroKmNo").prop("checked", true);
  });

  // Si no conoce la Placa oculta el campo Placa y muestra el campo CeroKM.
  $("#txtConocesLaPlacaNo").click(function () {
    Swal.fire({
      icon: "info",
      title: "",
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      html: `
        Para la emisión de vehículos 0 Km, comunícate con el área SOAT al número 
        <a href="https://api.whatsapp.com/send?phone=573013232210" target="_blank" style="color:#3085d6; text-decoration:underline;">
          301 323 2210
        </a>.
        La expedición tiene un tiempo de respuesta de 1 día hábil.
      `,
      showConfirmButton: true,
      confirmButtonText: "Nueva cotización",
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload(); // recarga la ubicación actual
      }
    });
  });

  // Validamos que si el vehiculo No es Cero KM, debe tener Placa
  $("#txtEsCeroKmNo").click(function () {
    var conoceslaPlaca = document.getElementById("txtConocesLaPlacaNo").checked;
    var esCeroKmNo = document.getElementById("txtEsCeroKmNo").checked;

    if (conoceslaPlaca == true && esCeroKmNo == true) {
      Swal.fire({
        icon: "error",
        title: "!Si el vehiculo no es 0 km, debe tener placa!",
        text: "Si el vehiculo tiene placa, no es 0 km",
        showConfirmButton: true,
      });
      $("#txtEsCeroKmNo").prop("checked", false);
    }
  });

  document.addEventListener("DOMContentLoaded", function () {
    var formulario = document.getElementById("formResumAseg"); // Reemplaza 'formulario' con el ID de tu formulario

    formulario.addEventListener("submit", function (event) {
      if (1 === 2) {
        event.preventDefault(); // Evita que el formulario se envíe
      }
    });
  });

  $("#btnConsultarPlaca").click(function () {
    if ($("#placaVeh").val() == "" || $("#placaVeh").val()== null) {
      swal
      .fire({
        icon: "error",
        title: "Ingresa la placa",
        showConfirmButton: true,
        confirmButtonText: "Ok",
        allowOutsideClick: false,
        allowEscapeKey: false,
      });
      return;
    }
    $("#btnConsultarPlaca").prop("disabled", true);
    $("#containerDataTable").hide();
    $(".card-container").hide();
    $("#containerDataTable").remove();
    $("#containerTable").remove();
    $("html, body").animate({ scrollTop: 0 }, 600);
    consulPlaca();
  });
  // let intermediario = document.getElementById("idIntermediario").value;
});

// Maximiza el formulario Datos Asegurado
function masAsegSoat() {
  document.getElementById("DatosAsegurado").style.display = "block";
  document.getElementById("DatosVehiculo").style.display = "block";
  document.getElementById("menosAsegurado").style.display = "block";
  document.getElementById("masAsegurado").style.display = "none";
  masVeh();
}
// Minimiza el formulario Datos Asegurado
function menosAsegSoat() {
  document.getElementById("DatosAsegurado").style.display = "none";
  document.getElementById("DatosVehiculo").style.display = "none";
  document.getElementById("menosAsegurado").style.display = "none";
  document.getElementById("masAsegurado").style.display = "block";
  menosVeh();
}

// Maximizar el formulario Datos Vehiculo
function masVehSoat() {
  document.getElementById("DatosVehiculo").style.display = "block";
  document.getElementById("menosVehiculo").style.display = "block";
  document.getElementById("masVehiculo").style.display = "none";
}
// Minimiza el formulario Datos Vehiculo
function menosVehSoat() {
  document.getElementById("DatosVehiculo").style.display = "none";
  document.getElementById("menosVehiculo").style.display = "none";
  document.getElementById("masVehiculo").style.display = "block";
}

// Maximizar el formulario Datos Vehiculo
function masExp() {
  document.getElementById("containerExpedicion").style.display = "block";
  document.getElementById("menosExp").style.display = "block";
  document.getElementById("masExp").style.display = "none";
}
// Minimiza el formulario Datos Vehiculo
function menosExp() {
  document.getElementById("containerExpedicion").style.display = "none";
  document.getElementById("menosExp").style.display = "none";
  document.getElementById("masExp").style.display = "block";
}
// Maximiza el Formulario Agregar Oferta
function masAgr() {
  document.getElementById("DatosAgregarOferta").style.display = "block";
  document.getElementById("menosAgrOferta").style.display = "block";
  document.getElementById("masAgrOferta").style.display = "none";
}
// Minimiza el Formulario Agregar Oferta
function menosAgr() {
  document.getElementById("DatosAgregarOferta").style.display = "none";
  document.getElementById("menosAgrOferta").style.display = "none";
  document.getElementById("masAgrOferta").style.display = "block";
}
// Permite consultar la informacion del vehiculo por medio de la Placa (Seguros del Estado)
function consulPlaca(query = "1") {
  var numplaca = document.getElementById("placaVeh").value;

  $("#title-resumen-coti").html("RESUMEN COTIZACIÓN SOAT " + numplaca);

  if (numplaca == "WWW404") {
    document.getElementById("formularioVehiculo").style.display = "block";
    $("#loaderPlaca").html("");
  } else {
    var valnumplaca = numplaca.toUpperCase(); // Convierte la Placa en Mayusculas
    let typeQuery = query != "2" ? numplaca != "" : numplaca != "";

    if (typeQuery) {
      $("btnConsultarPlaca2").remove();

      $("#loaderPlaca").html(
        '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Consultando Placa...</strong>'
      );

      $("#loaderPlaca2").html(
        '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Consultando Placa...</strong>'
      );

      $("#lblDataTrip2Top").css("display", "none");
      $(".box").css("border-top", "0px");

      //INICIO DE CABECERA PARA INGRESAR INFORMACION DEL METODO
      var myHeaders = new Headers();
      myHeaders.append("Content-Type", "application/json");

      var raw = JSON.stringify({
        placa: valnumplaca,
        // intermediario: intermediario,
      });

      var requestOptions = {
        mode: "cors",
        method: "POST",
        headers: myHeaders,
        body: raw,
        redirect: "follow",
      };

      // Llama la informacion del Vehiculo por medio de la Placa
      fetch(
        "https://grupoasistencia.com/motor_webservice/consulta_veh_soat",
        requestOptions
      )
        .then(function (response) {
          if (!response.ok) {
            throw Error(response.statusText);
          }
          return response.json();
        })
        .then(function (myJson) {
          // agregar clases homologadas del vehiuculo
          var clasesVehSoat = `<option value="">Seleccionar</option>`;

          if (myJson.homologaciones.HomologacionClaseMarcaLineaServicio.clase.length <= 1) {
            document.getElementById("claseVehSoat").innerHTML = `<option value="">${myJson.homologaciones.HomologacionClaseMarcaLineaServicio.clase.txtDesc}</option>`;
          } else {
            data.forEach(function (valor, i) {
              var valorNombre = valor.Nombre.split("-");
              var nombreMinusc = valorNombre[0].toLowerCase();
              var ciudad = nombreMinusc.replace(/^(.)|\s(.)/g, function ($1) {
                return $1.toUpperCase();
              });

              ciudadesVeh += `<option value="${valor.Codigo}">${ciudad}</option>`;
            });
          }
          document.getElementById("claseVehSoat").innerHTML = ciudadesVeh;
          var codigoLinea = myJson.ConsultarInfoVehiculoRuntDocResult.linea;
          var modeloVehiculo = myJson.ConsultarInfoVehiculoRuntDocResult.aaaa_modelo;
          var codigoClase = myJson.ConsultarInfoVehiculoRuntDocResult.claseVehiculo;
          var idClase = myJson.ConsultarInfoVehiculoRuntDocResult.codClaseSise;
          var codigoMarca = myJson.ConsultarInfoVehiculoRuntDocResult.marca;

          var servicio = myJson.ConsultarInfoVehiculoRuntDocResult.tipoServicio;
          var cilindraje = myJson.ConsultarInfoVehiculoRuntDocResult.cnt_cc;
          var pasajeros = myJson.ConsultarInfoVehiculoRuntDocResult.cnt_ocupantes;
          var motor = myJson.ConsultarInfoVehiculoRuntDocResult.noMotor;
          var chasis = myJson.ConsultarInfoVehiculoRuntDocResult.noChasis;
          var capacidad = myJson.ConsultarInfoVehiculoRuntDocResult.cnt_toneladas;

          var nroDocPropietario = myJson.ConsultarInfoVehiculoRuntDocResult.Propietarios.Propietario.noDocumento;

          const fechaInicioVigencia = new Date();
          const fechaFinVigencia = new Date(fechaInicioVigencia);
          fechaFinVigencia.setFullYear(fechaFinVigencia.getFullYear() + 1);
          //   $("#LimiteRC").val(limiteRCESTADO);

          $("#txtClaseVeh").val(codigoClase);
          $("#txtMarcaVeh").val(codigoMarca);
          $("#txtModeloVeh").val(modeloVehiculo);
          $("#txtLinea").val(codigoLinea);
          $("#txtServicio").val(servicio);
          $("#txtCilindraje").val(cilindraje);
          $("#txtPasajeros").val(pasajeros);
          $("#txtMotor").val(motor);
          $("#txtChasis").val(chasis);

          $("#loaderPlacaTwo").html('<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Cotizando SOAT...</strong>'
          );

          $.ajax({
            type: "POST",
            url: "https://grupoasistencia.com/motor_webservice/calcular_pol_soat",
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            processData: false,
            data: JSON.stringify({
              Placa: valnumplaca,
              Clase: idClase,
              Cilindraje: cilindraje,
              Capacidad: capacidad,
              Modelo: modeloVehiculo,
              Pasajeros: pasajeros,
              FechaInicioVigencia: "?",
              FechaFinVigencia: "?",
              NumeroDocumento: nroDocPropietario,
            }),
            success: function (data) {

              // document.getElementById("formularioVehiculo").style.display = "none";
              document.getElementById("headerAsegurado").style.display = "block";
              document.getElementById("contenSuperiorPlaca").style.display = "none";
              document.getElementById("resumenVehiculo").style.display = "block";
              // document.getElementById("contenBtnCotizar").style.display = "block";
              $("#loaderPlaca").hide();
              $("#loaderPlaca2").html("");
              menosAseg();
              document.getElementById("contenBtnConsultarPlaca").style.display = "none";
              $("#contenSuperiorPlaca").css("display", "block");
              $("#txtConocesLaPlacaSi").prop("disabled", true);
              $("#txtConocesLaPlacaNo").prop("disabled", true);
              $("#placaVeh").prop("disabled", true);
              let fechaStr = data.CalcularPolizaResult.FechaInicioVigencia; // "19/12/2026"
              let fecha = new Date(fechaStr.split('/').reverse().join('-'));
              fecha.setDate(fecha.getDate() - 0);
              let fechaFinal = fecha.toLocaleDateString('es-ES');
              let fechaVencimiento = data.CalcularPolizaResult.FechaInicioVigencia;

              // Peticion para guardar la cotización (formato Form Data)
              let datos = {
                  Accion: "Guardar",
                  Placa: valnumplaca,
                  Clase: codigoClase,
                  Modelo: modeloVehiculo,
                  Marca: codigoMarca,
                  Linea: codigoLinea,
                  Cilindraje: cilindraje,
                  Pasajeros: pasajeros,
                  Motor: motor,
                  Chasis: chasis,
                  Servicio: servicio,
                  Referencia: codigoMarca + " " + codigoLinea,
                  FechaVencimiento: fechaVencimiento.split(' ')[0],
                  NumeroDocumento: nroDocPropietario,
                  Prima: data.CalcularPolizaResult.ValorPrima,
                  Contribucion: data.CalcularPolizaResult.ValorContribucion,
                  Runt: data.CalcularPolizaResult.ValorTasaRUNT,
                  totalSoat: data.CalcularPolizaResult.ValorTotalPagar,
                  IdUsuario: permisos.id_usuario,
                };

              guardarEstado(datos);  

              let valorAPagarSoat = Number(data.CalcularPolizaResult.ValorTotalPagar);
              let totalPagarSoat = valorAPagarSoat + 45000;
              valorSoatGlobal = valorAPagarSoat;
              $("#fechaCoti").text(new Date().toLocaleDateString());
              $("#txtFechaVencimiento").val(fechaVencimiento.split(' ')[0]);
              $("#PrimaSoat").text("$ " + Number(data.CalcularPolizaResult.ValorPrima).toLocaleString("es-CO"));
              $("#contriFosyga").text("$ " + Number(data.CalcularPolizaResult.ValorContribucion).toLocaleString("es-CO"));
              $("#tasaRunt").text("$ " + Number(data.CalcularPolizaResult.ValorTasaRUNT).toLocaleString("es-CO"));
              $("#valorSoat").text("$ " + Number(data.CalcularPolizaResult.ValorTotalPoliza).toLocaleString("es-CO"));
              $("#valorSoat").text("$ " + valorAPagarSoat.toLocaleString("es-CO"));
              if (data.CalcularPolizaResult.ValorPrima == 0 || data.CalcularPolizaResult.ValorPrima == "0") {
                Swal.fire({
                  icon: "info",
                  title: "El vehículo presenta inconsistencias en la información registrada en el RUNT.",
                  text: "Para continuar, ingresa correo electrónico y número de celular del tomador, y adjunta la tarjeta de propiedad si deseas solicitar una cotización manual para validar la tarifa y el servicio de trámite.",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                });
                $("#lblTotalPagar").text("Inconsistencia en el RUNT");
                $("#totalPagarSoat").text("Continue adjuntando TP");
              } else {
                $("#totalPagarSoat").text("$ " + totalPagarSoat.toLocaleString("es-CO"));
              }
              $("#loaderPlacaTwo").html("");
              // $(".containerResumenCoti").show();
            },
            error: function (error) {
              console.log("Error al cotizar SOAT: ", error);
              $("#loaderPlacaTwo").html("");
            },
          });
          return;
        })
        .catch(function (error) {
          console.log("Error al consultar la placa: ", error);
        });
    } else {
      Swal.fire({
        icon: "error",
        title: "Completa toda la información del formulario",
        text: "Para avanzar debes completa la informacion del formulario",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
      });
    }
  }
}

$("#radioSinComision").click(function () {
  sinComision = valorSoatGlobal + 20000;
  $("#totalPagarSoat").text("$ " + sinComision.toLocaleString("es-CO"));
});

$("#radioConComision").click(function () {
  conComision = valorSoatGlobal + 45000;
  $("#totalPagarSoat").text("$ " + conComision.toLocaleString("es-CO"));
});

$("#btnNuevaCoti").click(function () {
  window.location.reload();
});

$("#btnContinuarCoti").click(function (e) {
  if (!$("#radioSinComision").prop("checked") && !$("#radioConComision").prop("checked")) {
    e.preventDefault();
    Swal.fire({
      icon: "error",
      title: "Datos faltantes",
      text: "Por favor selecciona el valor del tramite",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
    });
    return;
  }
  menosVeh();
  $(".containerResumenCoti").show();
  $(".containerFinalForm").show();
  $("#servicioTramite").text("$ " + Number($("#radioConComision").is(":checked") ? 45000 : 20000).toLocaleString("es-CO"));
  $("#radioConComision").prop("disabled", true);
  $("#radioSinComision").prop("disabled", true);
  $("#btnContinuarCoti").prop("disabled", true);

  // Peticion para actualizar valores la cotización (formato Form Data)
  datos = {
      Accion: "Actualizar-valores-soat",
      IdCotizacionSoat: idCotizacionSoat,
      Estado: "Soat Cotizado",
      Opcion: $("#radioConComision").is(":checked") ? "Con comision" : "Sin comision",
      Comision: $("#radioConComision").is(":checked") ? 45000 : 20000,
      TotalSoat: $("#totalPagarSoat").text().replace(/\./g, "").replace("$ ", ""),
      IdUsuario: permisos.id_usuario,
    };
    guardarEstado(datos);
});

$("#btnEnviarSolicitud").click(function () {
  if (getParams("idCotizacionSoat").length > 0) {
    idCotizacionSoat = getParams("idCotizacionSoat")[0];
  }

  $("#btnEnviarSolicitud").prop("disabled", true);

  const campos = ["#correoTomadorSoat", "#celularTomadorSoat"];
  let errores = 0;

  campos.forEach(id => {
    const el = $(id);
    if (el.val() === "") {
      el.css("border", "1px solid red");
      errores++;
    } else {
      el.css("border", ""); // Limpia el borde si el usuario ya lo corrigió
    }
  });

  if (errores > 0 || files.length === 0) {
    Swal.fire({
      icon: "error",
      title: "Faltan datos por completar",
      text: "Por favor completa el correo, celular del tomador SOAT o documentos adjuntos",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
    });
    $("#btnEnviarSolicitud").prop("disabled", false);
    return;
  }

  // Enviar Archivos Adjuntos
  enviarArchivos();

  // Peticion para actualizar datos la cotización (formato Form Data)
  datos = {
      Accion: "Actualizar-datos-soat",
      IdCotizacionSoat: idCotizacionSoat,
      Estado: "Solicitud enviada",
      Correo: $("#correoTomadorSoat").val(),
      Celular: $("#celularTomadorSoat").val(),
    };

  guardarEstado(datos);
  msg = "Solicitud enviada";
  enviarEmail(msg, idCotizacionSoat);

});

const MAX_FILES = 3;
const MAX_SIZE = 1 * 1024 * 1024;

const btn = document.getElementById("btnUpload");
const input = document.getElementById("fileInput");
const preview = document.getElementById("filePreview");

var files = [];

btn.onclick = () => {
  if (files.length < MAX_FILES) {
    input.click();
  }
};

input.onchange = () => {
  const selected = Array.from(input.files);

  for (const file of selected) {

    if (files.length >= MAX_FILES) {
      alert("Máximo 3 archivos.");
      break;
    }

    if (file.size > MAX_SIZE) {
      alert(`"${file.name}" supera 1 MB`);
      continue;
    }

    const exists = files.some(
      f => f.name === file.name && f.size === file.size
    );

    if (!exists) {
      files.push(file);
    }
  }

  render();
  input.value = "";
};

function render() {
  preview.innerHTML = "";

  files.forEach((file, index) => {
    const div = document.createElement("div");
    div.className = "file-item";

    div.innerHTML = `
        <span class="nombre-archivo-contenedor" style="color: #337ab7; font-weight: bold;">
            <span class="texto-ellipsis">${idCotizacionSoat}-${file.name}</span>
            <strong style="font-size: 10.5px; color: black; white-space: nowrap;">
                (${(file.size / 1000).toFixed(2)} K)
            </strong>
        </span>
        <span class="remove-btn" onclick="removeFile(${index})">✕</span>
    `;

    preview.appendChild(div);
  });

  // bloquear cuando llegue al límite
  btn.disabled = files.length >= MAX_FILES;
}

function removeFile(index) {
  files.splice(index, 1);
  render();
}

function enviarArchivos() {
  // Verificamos que existan archivos para evitar peticiones vacías
  if (!files || files.length === 0) {
    console.warn("No hay archivos para subir");
    return;
  }

  console.log("Iniciando subida para cotización:", idCotizacionSoat);
  const formData = new FormData();

  // Agregamos el ID de la cotización
  formData.append("cotizacion", idCotizacionSoat);

  // Agregamos los archivos
  files.forEach((file, index) => {
    const nuevoNombre = `${idCotizacionSoat}-${index}-${file.name}`;
    // Importante: 'archivos[]' permite que PHP lo reciba como un array
    formData.append("archivos[]", file, nuevoNombre);
  });

  // --- DEBUG: Ver el contenido real antes de enviar ---
  console.log("Contenido del FormData:");
  for (let [key, value] of formData.entries()) {
    console.log(`${key}:`, value);
  }

  fetch("vistas/modulos/soat/uploadSoat.php", {
    method: "POST",
    body: formData // El navegador añade automáticamente el Header multipart/form-data
  })
    .then(res => {
      if (!res.ok) throw new Error("Error en la respuesta del servidor");
      return res.json();
    })
    .then(data => {
      if (data.ok) {
        console.log("Archivos subidos con éxito", data);
      } else {
        console.error("Error del servidor:", data.error);
      }
    })
    .catch(err => {
      console.error("Error en la petición fetch:", err);
      alert("Ocurrió un error al conectar con el servidor");
    });
}

function showLoader() {
  $("#loading-screen").css({
    display: "flex",
    justifyContent: "center",
    alignItems: "center"
  });
  $("#loading-screen").fadeIn(200);

  }

  function hideLoader() {
    $("#loading-screen").fadeOut(200);
  }

  async function copiarCardComoImagen() {
    const elemento = document.getElementsByClassName('summary-box');
    
    try {
        // 1. Convertir el div a canvas
        const canvas = await html2canvas(elemento[0], {
            backgroundColor: "#ffffff", // Asegura que el fondo sea blanco y no transparente
            scale: 2 // Mejora la calidad de la imagen
        });

        // 2. Convertir el canvas a un Blob (imagen PNG)
        canvas.toBlob(async (blob) => {
            try {
                // 3. Crear el item para el portapapeles
                const data = [new ClipboardItem({ "image/png": blob })];
                
                // 4. Escribir en el portapapeles
                await navigator.clipboard.write(data);
                
                // alert("¡Imagen copiada al portapapeles! Ya puedes pegarla en WhatsApp o correos.");
                $("#btnCopiarImagen").css("width", "25%");
                $("#btnCopiarImagen span").text("¡Copiado!");
                setTimeout(() => {
                  $("#btnCopiarImagen").css("width", "10%");
                  $("#btnCopiarImagen span").text("");
                }, 2000);
            } catch (err) {
                console.error("Error al copiar:", err);
                alert("Hubo un error al copiar la imagen.");
            }
        }, "image/png");

    } catch (error) {
        console.error("Error al generar la imagen:", error);
    }
}