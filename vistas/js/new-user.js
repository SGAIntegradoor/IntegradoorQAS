// Variables globales
let id_usuario_edit = "";
let initialSta = {};
let selectedOptionsUnidad = [];

document.addEventListener("DOMContentLoaded", async function () {
  $("#divLoaderFS").show();
  cargarRoll();
  cargarIntermediario();
  cargarAnalistas();
  cargarBancos();
  cargarCargos();

  let user_loaded = {};

  // Cargar parametros iniciales que disparan eventos
  // al iniciar cabe resaltar que solo se disparan si
  // es un usuario nuevo

  $("#origen").on("change", function () {
    // Aquí puedes agregar el código que deseas ejecutar cuando cambie el valor del select "origen"
    let valorOrigen = $(this).val();
    if (valorOrigen == "8") {
      $("#divRecomendador").css("display", "grid");
    } else {
      $("#divRecomendador").css("display", "none");
    }
  });

  $("#valorComision").on("input", function () {
    let valorComision = $(this).val();

    // 1. Reemplaza comas por puntos
    valorComision = valorComision.replace(/,/g, ".");

    // 2. Elimina todo lo que no sea dígito o punto
    valorComision = valorComision.replace(/[^0-9.]/g, "");

    // 3. Permite solo un punto decimal (elimina los extras)
    const parts = valorComision.split(".");
    if (parts.length > 2) {
      valorComision = parts[0] + "." + parts.slice(1).join("");
    }

    $(this).val(valorComision);
  });
  $("#tipoDePersona").val(1).trigger("change");
  $("#tipoDePersona").show();
  $("#noAsistente").prop("checked", true).trigger("change");
  $("#usuarioVin").prop("disabled", false);
  $("#fechaActivacion").prop("disabled", false);

  $("#tipoDePersona").on("change", function () {
    let tipoPersona = $(this).val();
    if (tipoPersona == "2") {
      // Debe borrar las opciones y dejar solo NIT
      $("#tipoDocumento")
        .empty()
        .append('<option value="NIT">NIT</option>')
        .val("NIT")
        .trigger("change");
      $(
        "#nombre_perfil, #apellidos_perfil, #genero_perfil, #fechaNacimiento_perfil"
      ).removeClass("requiredfield");
      $(
        "#razonSocial, #personaDeContacto, #representanteLegal, #fechaNacimientoRepresentante"
      ).addClass("requiredfield");
    } else {
      // Debe borrar las opciones y dejar solo CC y CE
      $("#tipoDocumento")
        .empty()
        .append(
          '<option value="">Seleccione una opción...</option><option value="CC">CC</option><option value="CE">CE</option>'
        )
        .val("")
        .trigger("change");
      $(
        "#nombre_perfil, #apellidos_perfil, #genero_perfil, #fechaNacimiento_perfil"
      ).addClass("requiredfield");
      $(
        "#razonSocial, #personaDeContacto, #representanteLegal, #fechaNacimientoRepresentante"
      ).removeClass("requiredfield");
    }
  });

  const params = new URLSearchParams(window.location.search);

  if (params.has("id")) {
    const id = params.get("id");
    if (id) {
      // Cargar datos del usuario
      id_usuario_edit = id;

      $("#formUser")
        .find("input, select")
        .each(function () {
          if ($(this).hasClass("requiredfield")) {
            $(this).removeClass("requiredfield");
          }
        });

      await loadUser(id)
        .then(() => {})
        .finally(() => {
          $("#divLoaderFS").hide();
        })
        .catch((err) => {
          console.error("Error al cargar el usuario:", err);
        });
    }
    initialSta = setState();
  } else {
    console.log("entre por aqui");
    $("#divLoaderFS").hide();
    $("#imgsContainer").hide();
    $("#diasActivacion").val(0);
    $("#fechaCreaVin").val(todayDateFormatted(new Date(), 0));
    // let limitYear = Number(formattedDate.split("-")[0]) + 30;
    // let newForm = formattedDate.split("-");
    // newForm[0] = limitYear;

    // $("#limiteUso").val(newForm.join("-"))
    initialSta = setState();
  }
});

function todayDateFormatted(date, add = 0) {
  let formattedDate = date.toISOString().split("T")[0];

  let limitYear = Number(formattedDate.split("-")[0]) + add;
  let newForm = formattedDate.split("-");
  newForm[0] = limitYear;

  return newForm.join("-");
}

/*=============================================
Eventos OnChange, OnClick en inputs
=============================================*/

// Evento OnChange crear usuario desde cero

$("#documento").on("input change", function () {
  if (id_usuario_edit == "") {
    if ($(this).val() != "") {
      $("#usuarioVin").val($(this).val());
    } else {
      $("#usuarioVin").val("");
    }
  }
});

// Evento OnChange Mismo Representante

$('input[name="radioMismoRep"]').on("change", function () {
  if ($(this).attr("id") === "siRepresentante") {
    $("#representanteLegal").val($("#personaDeContacto").val());
  } else if ($(this).attr("id") === "noRepresentante") {
    $("#representanteLegal").val("");
  }
});

function detectarCambios(dataOriginal, dataActual) {
  const cambios = {};

  console.log("Data Original", dataOriginal);
  console.log("Data Actual", dataActual);

  Object.keys(dataOriginal).forEach((seccion) => {
    // console.log(seccion);
    cambios[seccion] = {};

    Object.keys(dataOriginal[seccion]).forEach((campo) => {
      // console.log(campo);
      const valorOriginal = dataOriginal[seccion][campo];
      const valorActual = dataActual[seccion][campo];
      if (Array.isArray(valorOriginal)) {
        if (JSON.stringify(valorOriginal) !== JSON.stringify(valorActual)) {
          cambios[seccion][campo] = valorActual;
        }
      } else if (typeof valorOriginal === "object" && valorOriginal !== null) {
        // Comparar objetos internos (como infoAseguradoras.detalle)

        // console.log("Entre aqui");

        cambios[seccion][campo] = {};
        let huboCambioInterno = false;

        Object.keys(valorOriginal).forEach((subCampo) => {
          if (valorOriginal[subCampo] != valorActual[subCampo]) {
            cambios[seccion][campo][subCampo] = valorActual[subCampo];
            huboCambioInterno = true;
          }
        });

        if (!huboCambioInterno) {
          delete cambios[seccion][campo]; // No hubo cambios reales
        }
      } else if (valorOriginal != valorActual) {
        cambios[seccion][campo] = valorActual;
      }
    });

    // Si la sección no tiene cambios, eliminarla
    if (Object.keys(cambios[seccion]).length === 0) {
      delete cambios[seccion];
    }
  });

  return cambios;
}

// Function Check campos requeridos

function checkRequiredFields(check) {
  const errors = [];

  if (check) {
    $("#formUser")
      .find("input, select")
      .each(function () {
        if ($(this).hasClass("requiredfield")) {
          if ($(this).val() == "") {
            //$(this).addClass("requiredfieldError"); para mostrar notificaciones en el campo
            errors.push($(this).attr("id"));
          } else {
            // $(this).removeClass("requiredfieldError");
          }
        }
      });
  }

  return errors;
}

// Evento OnClick btnGuardar Usuario

$(".btnGuardar").on("click", function () {
  if ($("#limiteUso").val() === "") {
    $("#limiteUso").val(todayDateFormatted(new Date(), 30));
  }
  let data = setState();
  let required = false;
  // console.log("State set up OnClick", data);
  // console.log("State from the beginning of loaded content", initialSta);

  const cambios = detectarCambios(initialSta, data);

  // START Validaciones

  if (id_usuario_edit == "") {
    cambios.infoUsuario.usu_password = $("#usuarioVin").val();
    cambios.infoUsuario.numCotizaciones = 0;
    // console.log($("#usuarioVin").val());
    // console.log(data);
    // console.log(initialSta);
    required = true;
  }
  console.log(cambios);

  if (Object.keys(cambios).length === 0) {
    Swal.fire({
      icon: "info",
      title: "Sin cambios",
      text: "No se detectaron cambios en el formulario.",
    });
    return;
  }

  if (checkRequiredFields(required).length > 0) {
    let campos = checkRequiredFields(required).join(", ");
    Swal.fire({
      icon: "error",
      title: "Error",
      text: `Los siguientes campos son requeridos: ${campos}`,
    });
    return;
  }

  // END Validaciones

  // Aquí se puede enviar el objeto data al server, tambien puede ser procesado como se vaya a necesitar.

  $.ajax({
    url: "src/saveUser.php",
    method: "POST",
    dataType: "json",
    contentType: "application/json", // Se debe enviar un JSON si no el scriptt de php no lee el POST ya que el objeto es grande.
    data: JSON.stringify({
      id: id_usuario_edit == "" ? null : id_usuario_edit,
      cambios: cambios, // se serializan los cambios detectados. (Ojo aqui, porque es la base clave para detectar cambios en el form, esto sigue en revision)
    }),
    success: function (respuesta) {
      if (respuesta.success) {
        Swal.fire({
          icon: "success",
          title: "Éxito",
          text: "Usuario guardado correctamente.",
          confirmButtonText: "Aceptar",
        }).then((result) => {
          if (result.isConfirmed) {
            // Redirige a la ruta 'usuarios'
            window.location.href = "usuarios";
          }
        });
      } else if (respuesta.mensaje.includes("Duplicate")) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "El usuario ya se encuentra creado en el sistema, porfavor verifica la información.",
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Revisa la información ingresada e intentalo nuevamente.",
        });
      }
    },
  });
});

$(".btnSalir").on("click", function () {
  window.location.href = "usuarios";
});

/*=============================================
Cargar Rol
=============================================*/

function cargarRoll() {
  const idRoll = permisos.id_rol;

  $.ajax({
    url: "ajax/cargarRoll.php",
    method: "POST",
    data: { idRol: idRoll }, // Enviar idRol en el cuerpo de la solicitud AJAX
    success: function (respuesta) {
      respuesta =
        "<option value='' selected>Seleccione una opción</option>" + respuesta;
      $("#rolUsers").html(respuesta);
    },
  });
}

/*=============================================
Cargar Intermediario
=============================================*/

function cargarIntermediario() {
  $.ajax({
    url: "ajax/cargarIntermediario.php",
    method: "POST",
    success: function (respuesta) {
      respuesta =
        "<option value='' selected>Seleccione una opción</option>" + respuesta;
      $("#intermediarioPerfil").html(respuesta);
    },
  });
}

/*=============================================
Cargar Analistas
=============================================*/

function cargarAnalistas() {
  $.ajax({
    url: "ajax/cargarAnalistas.php",
    method: "POST",
    success: function (respuesta) {
      respuesta =
        "<option value='' selected>Seleccione una opción</option>" + respuesta;
      $("#analistaAsesor").html(respuesta);
    },
  });
}

// Carga las Ciudades disponibles
$("#ciudad").select2({
  theme: "bootstrap ciudad",
  language: "es",
  width: "100%",
});

$("#departamento").select2({
  theme: "bootstrap ciudad",
  language: "es",
  width: "100%",
});

/*=============================================
Cargar Bancos
=============================================*/

function cargarBancos() {
  $.ajax({
    url: "src/getBanks.php",
    method: "POST",
    success: function (respuesta) {
      respuesta =
        "<option value='' selected>Seleccione una opción</option>" + respuesta;
      $("#entidadBancaria").html(respuesta);
    },
  });
}

/*=============================================
Cargar Bancos
=============================================*/

function cargarCargos() {
  $.ajax({
    url: "src/getChairs.php",
    method: "POST",
    success: function (respuesta) {
      respuesta =
        "<option value='' selected>Seleccione una opción</option>" + respuesta;
      $("#cargos").html(respuesta);
    },
  });
}

/*=============================================
Metodo de consultar ciudad por departamento
=============================================*/

// function consultarCiudad(param = "") {
//   var codigoDpto = $("#departamento").val();

//   // Función para poner la primera letra de cada palabra en mayúscula
//   const toTitleCase = (str = "") =>
//     str.toLowerCase().replace(/\b\w/g, (l) => l.toUpperCase());

//   $.ajax({
//     type: "POST",
//     url: "src/consultarCiudadHogar.php",
//     data: { codigoDpto: codigoDpto },
//     cache: false,
//     success: function (data) {
//       const deptoActual = $("#departamento").val();
//       if (deptoActual !== codigoDpto) {
//         // El usuario cambió de departamento antes de que llegara esta respuesta → la ignoramos
//         return;
//       }

//       // Se limpia el select antes de ingresar las ciudades.
//       // $("#ciudad").empty();
//       let ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;

//       let json = JSON.parse(data);
//       if (json.success) {
//         let arrCitys = json.data;
//         if (Array.isArray(arrCitys)) {
//           arrCitys.sort((a, b) => a.codigo - b.codigo);
//           arrCitys.forEach(({ codigo, ciudad }) => {
//             const ciudadFormateada = toTitleCase(ciudad);
//             ciudadesVeh += `<option value="${codigo}">${ciudadFormateada}</option>`;
//           });
//           $("#ciudad").append(ciudadesVeh);
//         } else {
//           console.error("El campo data no es un array:", arrCitys);
//         }
//       }

//       if (param != "") {
//         console.log("entre aqui para la ciudad")
//         $("#ciudad").val(param);
//       } else {
//         $("#ciudad").val("");
//       }
//     },
//   });
// }

function consultarCiudad(param = "") {
  // capturamos el departamento solicitado al inicio de la llamada (inmutable dentro de esta ejecución)
  const requestedDepto = $("#departamento").val();

  // Función para poner la primera letra de cada palabra en mayúscula
  const toTitleCase = (str = "") =>
    str.toLowerCase().replace(/\b\w/g, (l) => l.toUpperCase());

  $.ajax({
    type: "POST",
    url: "src/consultarCiudadHogar.php",
    data: { codigoDpto: requestedDepto },
    cache: false,
    success: function (data) {
      // si el usuario cambió de departamento desde que se hizo la petición, ignoramos la respuesta
      if ($("#departamento").val() !== requestedDepto) {
        console.warn(
          "Respuesta de ciudades descartada porque el departamento cambió."
        );
        return;
      }

      // limpiamos el select antes de ingresar las ciudades (no usar append sin limpiar)
      let ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;

      let json;
      try {
        json = JSON.parse(data);
      } catch (err) {
        console.error("Error parseando json de ciudades:", err, data);
        return;
      }

      if (json.success) {
        let arrCitys = json.data;
        if (Array.isArray(arrCitys)) {
          // orden por código (convierte a número si es string)
          arrCitys.sort((a, b) => Number(a.codigo) - Number(b.codigo));
          arrCitys.forEach(({ codigo, ciudad }) => {
            const ciudadFormateada = toTitleCase(ciudad);
            ciudadesVeh += `<option value="${codigo}">${ciudadFormateada}</option>`;
          });

          // reemplazamos el html completo (evita duplicados)
          $("#ciudad").html(ciudadesVeh);
        } else {
          console.error("El campo data no es un array:", arrCitys);
        }
      } else {
        // si la respuesta no fue success, dejamos solo la opción por defecto
        $("#ciudad").html('<option value="">Seleccionar Ciudad</option>');
      }

      // Seleccionar la ciudad si nos pasaron parametro
      if (param) {
        $("#ciudad").val(param);
      } else {
        // si no hay param, quedamos con la opción vacía
        $("#ciudad").val("");
      }
    },
    error: function (xhr, status, err) {
      console.error("Error al consultar ciudades:", err);
    },
  });
}

function formatoFecha(fecha) {
  console.log(fecha);
  let fechaFormateada = new Date(fecha.replace(" ", "T"));
  let year = fechaFormateada.getFullYear();
  let month = String(fechaFormateada.getMonth() + 1).padStart(2, "0");
  let day = String(fechaFormateada.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

/*=============================================
Cargar Objeto para Guardar Información nueva
==============================================*/

// {
//   "info_usuario": {
//       "id_usuario": "1488",
//       "usu_documento": "16757577",
//       "usu_nombre": "Julio César",
//       "usu_apellido": "Payán Sánchez",
//       "usu_fch_nac": "1968-11-17",
//       "usu_direccion": "AVENIDA 9 NORTE 54 N - 04 APTO 309 CONJUNTO RESIDENCIAL PROVENZA",
//       "ciudades_id": "76001",
//       "tipos_documentos_id": "Cedula de Ciudadania",
//       "usu_usuario": "16757577",
//       "usu_password": "$2a$07$asxx54ahjppf45sd87a5audFPAiX1L0Z2UTt3bQUFH3KQddQzuxwi",
//       "usu_genero": "M",
//       "usu_telefono": "(311) 757-5767",
//       "usu_email": "GLOBALINSURANCECOLOMBIA@YAHOO.ES",
//       "usu_cargo": "Freelance",
//       "usu_foto": "",
//       "usu_logo_pdf": null,
//       "usu_ultimo_login": "2025-02-28 10:40:31",
//       "usu_fch_creacion": "2025-02-28 10:39:44",
//       "usu_estado": "1",
//       "id_rol": "19",
//       "id_Intermediario": "3",
//       "numCotizaciones": "0",
//       "cotizacionesTotales": "50",
//       "fechaFin": "2040-12-31 00:00:00",
//       "in_session": null,
//       "tokenPassword": null,
//       "tokenGuest": null,
//       "id_info_entidad_fin": "1",
//       "id_banco": "2d2698df-2870-4004-a536-408edd78611d",
//       "tipo_cuenta": "1",
//       "numero_cuenta": "077998856743",
//       "regimen_renta": "No declarante",
//       "facturador_electronico": "0",
//       "responsable_iva": "0",
//       "participacion_esp": "18"
//   },
//   "info_usuario_canal": {
//       "id_info_canal": "1",
//       "proactividad": "1",
//       "cargo": "19",
//       "director_comercial": "1",
//       "analista_comercial": "1151946527",
//       "origen": "N/A",
//       "nombre_recomendador": "Test Testing",
//       "id_usuario": "1488",
//       "id_director": "1",
//       "nom_completo_director": "Keila Figueira Lopez",
//       "id_documento_director": "1007028818"
//   },
//   "info_aseguradoras_user": {
//       "id_aseguradoras_user": "1",
//       "allianz_aseg": "1",
//       "axa_aseg": "1",
//       "bolivar_aseg": "1",
//       "equidad_aseg": "0",
//       "estado_aseg": "0",
//       "hdi_aseg": "0",
//       "mapfre_aseg": "1",
//       "mundial_aseg": "0",
//       "previsora_aseg": "0",
//       "sbs_aseg": "0",
//       "sura_aseg": "0",
//       "zurich_aseg": "0",
//       "otras_aseg": "",
//       "id_usuario": "1488"
//   }
// }

function setState() {
  let asegs = {};

  let tipoDePersona = $("#tipoDePersona").val();
  let esJuridiaca = tipoDePersona == "2" ? true : false;

  const valorTipoDoc = $("#tipoDocumento").val();
  let tipoDocTexto = "";

  if (valorTipoDoc === "CC") {
    tipoDocTexto = "CC";
  } else if (valorTipoDoc === "CE") {
    tipoDocTexto = "CE";
  } else if (valorTipoDoc === "NIT") {
    tipoDocTexto = "NIT";
  } else {
    tipoDocTexto = ""; // sin selección
  }

  let unidadNegocioRol = [...selectedOptionsUnidad];

  /*** Info Usuario ***/
  const infoUsuario = {
    usu_documento: $("#documento").val(),
    usu_nombre: esJuridiaca
      ? $("#razonSocial").val().split(" ")[0]
      : $("#nombre_perfil").val(),
    usu_apellido: esJuridiaca
      ? $("#razonSocial").val().split(" ")[1]
      : $("#apellidos_perfil").val(),
    usu_fch_nac: $("#fechaNacimiento_perfil").val(),
    usu_direccion: $("#direccion_perfil").val(),
    ciudades_id: $("#ciudad").val(),
    tipos_documentos_id: tipoDocTexto,
    usu_usuario: $("#usuarioVin").val(),
    usu_genero: esJuridiaca ? "J" : $("#genero_perfil").val(),
    usu_telefono: $("#telefono_perfil").val(),
    usu_email: $("#email_perfil").val(),
    // usu_cargo: $("#cargos option:selected").text(),
    usu_fch_creacion: $("#fechaCreaVin").val(),
    usu_estado: $("#estadoUs").val(),
    id_Intermediario: $("#intermediarioPerfil").val(),

    // parte del usuario para validar fechas
    cotizacionesTotales: $("#limiteCots").val(),
    fechaFin: $("#limiteUso").val(),
    // usu_fecha_activacion: $("#fechaActivacion").val() || null,

    id_rol: $("#rolUsers").val(),
    // Rol Usuario
    unidad_negocio_rol: unidadNegocioRol,
    usu_canal: $("#canal").val() || null,
    // Por tipo de documento - Si cambia esto tiene que cambiar el tipo de documento dependiendo lo que venga
    tipo_persona_rol: $("#tipoDePersona").val(),
    // Tambien si cambia, deberia cambiar el tipo de documento solo que en este caso es directo en el otro caso es indirecto va por condicional
    // funciona de manera condicional y depende si esta check el input "siAsistente" en caso de ser asi debe validarse que vengan los 3 inputs llenos con informacion
    tiene_asistente: $("#siAsistente").is(":checked") ? 1 : 0,
    nombre_asistente: $("#nombreAsistente").val() || null,
    telefono_asistente: $("#telefonoAsistente").val() || null,
    email_asistente: $("#emailAsistente").val() || null,
  };

  /*** Info del Canal ***/
  const infoCanal = {
    //campo dependiente del rol del usuario asi no que no es necesario enviarlo por aca
    // rol: $("#rolUsers").val(),
    proactividad: $("#categoriaAsesor").val(),
    cargo: $("#cargos").val(),
    director_comercial: $("#directorComercial").val(),
    analista_comercial: $("#analistaAsesor").val(),
    origen: $("#origen").val(),
    nombre_recomendador: $("#nombreRecomendador").val(),
  };

  /*** Info Aseguradoras ***/
  const clavesAseguradoras = $("#siClaves").is(":checked") ? 1 : 0;

  if (clavesAseguradoras) {
    $(".clavesAseguradoras")
      .find("input")
      .each(function () {
        const id = $(this).attr("id");
        const value = $(this).is(":checked") ? 1 : 0;
        if (this.tagName.toLowerCase() === "input" && id !== "otras_aseg") {
          asegs[id] = value;
        } else if (id === "otras_aseg") {
          asegs["otras_aseg"] = $("#otras_aseg").val();
        }
      });
  } else {
    asegs = {
      allianz_aseg: 0,
      axa_aseg: 0,
      bolivar_aseg: 0,
      equidad_aseg: 0,
      estado_aseg: 0,
      hdi_aseg: 0,
      mapfre_aseg: 0,
      mundial_aseg: 0,
      previsora_aseg: 0,
      sbs_aseg: 0,
      sura_aseg: 0,
      zurich_aseg: 0,
      otras_aseg: "",
    };
  }

  /*** Info Financiera ***/
  const infoFinanciera = {
    id_banco: $("#entidadBancaria").val(),
    tipo_cuenta: $("#tipoCuenta").val(),
    numero_cuenta: $("#noCuenta").val(),
    regimen_renta: $("#regimenRenta").val(),
    responsable_iva: $("#siIVA").is(":checked") ? 1 : 0,
    facturador_electronico: $("#siFacturado").is(":checked") ? 1 : 0,
    participacion_esp: $("#participacionEsp").val().replace("%", "").trim(),
  };

  /*** Objeto final agrupado por secciones ***/
  const data_user = {
    infoUsuario,
    infoCanal,
    infoAseguradoras: asegs,
    infoFinanciera,
  };

  return data_user;
}

/*=============================================
Cargar Usuario
=============================================*/

const loadMadedDeals = async (id_user) => {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "https://grupoasistencia.com/API/Users/getFirstDealMade",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ id_freelance: id_user }),
      success: function (respuesta) {
        const parsedResponse = JSON.parse(respuesta);
        const [year, month, day] =
          parsedResponse.data.data.fecha_exp_poliza.split("-");
        const dateActivacion = new Date(year, month - 1, day);

        console.log(parsedResponse.data.data.fecha_exp_poliza);

        $("#fechaActivacion").val(parsedResponse.data.data.fecha_exp_poliza);

        // resta de fechas y calcula los dias para colocarlos en un input
        const fechaActual = new Date($("#fechaCreaVin").val());
        const diferenciaTiempo = dateActivacion - fechaActual;
        const diferenciaDias = Math.ceil(
          diferenciaTiempo / (1000 * 60 * 60 * 24)
        );
        $("#diasActivacion").val(diferenciaDias);

        resolve();
      },
      error: function (xhr, status, error) {
        reject(error);
      },
    });
  });
};

async function loadUser(id) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "src/loadUser.php",
      method: "POST",
      data: { id: id },
      success: function (respuesta) {
        try {
          // parseamos la respuesta al inicio
          const data = JSON.parse(respuesta);
          data["info_usuario"]["unidad_negocio_rol"] = JSON.parse(
            data["info_usuario"]["unidad_negocio_rol"] || "[]"
          );
          const { info_usuario, info_usuario_canal, info_aseguradoras_user } =
            data;

          // mantener tu lógica original (solo reorganizada para evitar race conditions)
          // cargarCargos();
          // cargarAnalistas();
          // cargarBancos();

          $("#divComentarios").show();
          getComments(id);

          if (
            info_usuario.id_rol == 1 ||
            info_usuario.id_rol == 10 ||
            info_usuario.id_rol == 11 ||
            info_usuario.id_rol == 22 ||
            info_usuario.id_rol == 23
          ) {
            // console.log("Este es el usuario!", data);
            $("#divUnidadNegocio").hide();
            $("#divCanal").hide();
            $("#canal").val(info_usuario.usu_canal);
            $("#divUsuarioSGA").show();
            $("#usuarioSGA").val(info_usuario?.id_rol);
            $(".divAsistente").hide();
            $("#rolUsers").val(info_usuario?.id_rol).trigger("change");
            setTimeout(() => {
              $("#intermediarioPerfil")
                .val(info_usuario.id_Intermediario)
                .trigger("change");
              $("#cargos")
                .val(
                  info_usuario_canal?.cargo != null
                    ? info_usuario_canal?.cargo
                    : ""
                )
                .trigger("change");
            }, 500);
          } else if (info_usuario.id_rol == 12) {
            // console.log("Este es el usuario!", data);
            $("#divUnidadNegocio").css("display", "none");
            $("#divUsuarioSGA").show();
            $("#usuarioSGA").val(info_usuario.id_rol);
            $(".divAsistente").hide();
            // $("#divCanal").show();
            $("#canal").val(1);
            $("#rolUsers").val(info_usuario?.id_rol).trigger("change");
            setTimeout(() => {
              $("#intermediarioPerfil")
                .val(info_usuario.id_Intermediario)
                .trigger("change");
              $("#cargos").val(info_usuario_canal.cargo).trigger("change");
            }, 500);
          } else {
            // $("#divUnidadNegocio").show();
            $("#divCanal").show();
            $("#canal").val(
              (info_usuario.usu_canal == null ||
                info_usuario.usu_canal == "") &&
                info_usuario.id_rol == "19"
                ? "1"
                : info_usuario.usu_canal
            );
            $("#divUsuarioSGA").hide();

            loadMadedDeals(info_usuario.usu_documento).then(() => {
              resolve(); // Resolves the promise after deals are loaded
            });

            // $("#unidadDeNegocio").val(info_usuario.id_rol);
            $("#divUnidadNegocio").hide();
            $("#tipoDePersona").val(info_usuario.tipo_persona_rol);
            if (info_usuario.tipo_persona_rol == "2") {
              $("#tipoDocumento")
                .empty()
                .append('<option value="NIT">NIT</option>')
                .val("NIT");
              $(
                "#razonSocial, #personaDeContacto, #representanteLegal, #fechaNacimientoRepresentante"
              ).addClass("requiredfield");
              $("#razonSocial").val(
                info_usuario.usu_nombre + " " + info_usuario.usu_apellido
              );
              $("#genero_perfil")
                .empty()
                .append('<option value="J">Juridica</option>')
                .val("J")
                .trigger("change");
            }
            $("#rolUsers").val(info_usuario.id_rol).trigger("change");

            setTimeout(() => {
              $("#intermediarioPerfil")
                .val(info_usuario?.id_Intermediario ?? "")
                .trigger("change");

              $("#analistaAsesor")
                .val(info_usuario_canal?.analista_comercial ?? "")
                .trigger("change");

              $("#entidadBancaria")
                .val(info_usuario?.id_banco ?? "")
                .trigger("change");

              $("#tipoCuenta")
                .val(info_usuario?.tipo_cuenta ?? "")
                .trigger("change");
              $("#noCuenta").val(info_usuario?.numero_cuenta ?? "");
              $("#regimenRenta").val(info_usuario?.regimen_renta ?? "");
              $("#cargos")
                .val(info_usuario_canal?.cargo ?? "")
                .trigger("change");

              info_usuario?.facturador_electronico == 1
                ? $("#siFacturado").prop("checked", true).trigger("change")
                : $("#noFacturado").prop("checked", true).trigger("change");

              info_usuario?.responsable_iva == 1
                ? $("#siIVA").prop("checked", true).trigger("change")
                : $("#noIVA").prop("checked", true).trigger("change");

              $("#participacionEsp").val(
                (info_usuario?.participacion_esp ?? "0") + " %"
              );
            }, 400);

            // Fuera del setTimeout también validás:
            $("#categoriaAsesor")
              .val(info_usuario?.categoria_freelance ?? "")
              .trigger("change");

            $("#estadoAsesor")
              .val(info_usuario?.estado_freelance ?? "")
              .trigger("change");

            $("#directorComercial").val(
              info_usuario_canal?.director_comercial ?? ""
            );

            $("#origen").val(info_usuario_canal?.origen ?? "");
            $("#divRecomendador").show();
            $("#nombreRecomendador").val(
              info_usuario_canal?.nombre_recomendador ?? ""
            );

            setTimeout(() => {
              // Guardamos user_loaded y resolvemos la promesa cuando ya cargamos la UI
              user_loaded = data;
              console.log(user_loaded);
              resolve(data);
            }, 1500);

            const unidades = info_usuario.unidad_negocio_rol;
            if (
              unidades.length > 0 &&
              info_usuario.unidad_negocio_rol != null
            ) {
              // const tieneUnidades = JSON.parse(info_usuario.unidad_negocio_rol);
              unidades.map((unidad) => {
                const element = document.getElementById(unidad);

                // Si existe el elemento y es un checkbox
                if (element && element.type === "checkbox") {
                  element.checked = true;
                }
                // Si es el campo "otras_aseg" que es un input text
              });
              
              let selectBox = document.querySelector("#div-options-unidades");
              if (unidades.length === 0) {
                selectBox.innerText = "Selecciona opciones...";
              } else if (selectedOptionsUnidad.length <= 2) {
                selectedOptionsUnidad = unidades;
                selectBox.innerText = unidades.join(", ");
              } else {
                selectBox.innerText =
                  selectedOptionsUnidad.slice(0, 2).join(", ") + ", .....";
              }
            } else {
              console.log("no hice asegs");
            }

            if (info_aseguradoras_user) {
              Object.entries(info_aseguradoras_user).length > 0
                ? $("#siClaves").prop("checked", true).trigger("change")
                : $("#noClaves").prop("checked", true).trigger("change");

              Object.entries(info_aseguradoras_user).forEach(([key, value]) => {
                const element = document.getElementById(key);
                // Si existe el elemento y es un checkbox
                if (element && element.type === "checkbox") {
                  element.checked = value === "1";
                }
                // Si es el campo "otras_aseg" que es un input text
                if (key === "otras_aseg") {
                  const otrasInput = document.getElementById("otras_aseg");
                  if (otrasInput) {
                    otrasInput.value = value == "NULL" ? "" : value;
                  }
                }
              });
            } else {
              console.log("no hice asegs");
            }
          }

          $("#usuarioVin").prop("disabled", true);
          $("#usuarioVin").val(info_usuario.usu_usuario);

          //Insertar fecha de creación
          let fechaForCrea = formatoFecha(info_usuario.usu_fch_creacion);
          $("#fechaCreaVin").val(fechaForCrea);
          $("#fechaCreaVin").prop("disabled", true);

          if ($("#fechaActivacion").val() == "") {
            $("#diasActivacion").val(0);
          }

          let fechaFormLim = formatoFecha(info_usuario.fechaFin);
          $("#limiteCots").val(info_usuario.cotizacionesTotales);
          $("#limiteUso").val(fechaFormLim);

          $("#estadoUs").val(info_usuario.usu_estado);

          let tipoDoc = info_usuario.tipos_documentos_id;
          let tipoPersona = tipoDoc == "NIT" ? "Juridica" : "Natural";
          $("#tipoDocumento")
            .val(tipoDoc == "CC" ? "CC" : tipoDoc == "CE" ? "CE" : tipoDoc)
            .trigger("change");

          if (tipoPersona == "Natural") {
            $("#tipoDePersona").val(1).trigger("change");
            $("#tipoDocumento")
              .val(info_usuario.tipos_documentos_id)
              .trigger("change");
            $("#documento").val(info_usuario.usu_documento);
            $("#nombre_perfil").val(info_usuario.usu_nombre);
            $("#apellidos_perfil").val(info_usuario.usu_apellido);
            $("#fechaNacimiento_perfil").val(info_usuario.usu_fch_nac);
            $("#genero_perfil").val(info_usuario.usu_genero == "M" ? "1" : "2");
          } else if (tipoPersona == "Juridica") {
            $("#tipoDePersona").val(2).trigger("change");
            $("#tipoDocumento").val("NIT").trigger("change");
            $("#documento").val(info_usuario.usu_documento);
            $("#razonSocial").val(
              info_usuario.usu_nombre + " " + info_usuario.usu_apellido
            );
            $("#personaDeContacto").val(
              info_usuario.usu_nombre + " " + info_usuario.usu_apellido
            );
            if (
              info_usuario.usu_nombre + " " + info_usuario.usu_apellido ==
              $("#personaDeContacto").val()
            ) {
              $("#siRepresentante").prop("checked", true).trigger("change");
              $();
            }
          }

          // --- Manejo departamento / ciudad (mejorado) ---
          if (
            info_usuario?.ciudades_id == null ||
            info_usuario?.ciudades_id == ""
          ) {
            // Si no hay ciudad del usuario, limpiamos departamento y ciudad
            $("#departamento").val("").trigger("change");
            // Limpiamos el select de ciudad (evita duplicados)
            $("#ciudad")
              .html('<option value="">Seleccionar Ciudad</option>')
              .val("");
          } else {
            // tomamos los dos primeros caracteres del código de ciudad
            let depto = String(info_usuario.ciudades_id).substring(0, 2);
            console.log(depto);
            if (depto == "11") {
              depto = "25";
            }
            $("#departamento").val(depto).trigger("change");
            // Llamamos a consultarCiudad pasando la ciudades_id para que seleccione la ciudad tras poblar el select
            consultarCiudad(info_usuario?.ciudades_id);
          }

          $("#direccion_perfil").val(info_usuario.usu_direccion);
          $("#telefono_perfil").val(info_usuario.usu_telefono);
          $("#email_perfil").val(info_usuario.usu_email);
        } catch (err) {
          console.error("Error procesando respuesta loadUser:", err);
          reject(err);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al cargar el usuario:", error);
        reject(error); // Rechazar la promesa en caso de error
      },
    });
  });
}

async function editarComision(id) {
  if (!confirm("¿Estás seguro de que deseas eliminar esta comisión?")) {
    return;
  }

  let nuevo_valor_comision = $("#valor_comision_edit").val();
  let nuevas_observaciones = $("#observaciones_edit").val();

  $.ajax({
    url: "src/editComision.php",
    method: "POST",
    data: {
      id_comision: id,
      valor_comision: nuevo_valor_comision,
      observaciones: nuevas_observaciones,
    },
    success: function (respuesta) {
      alert("Comisión modificada correctamente.");
      getComissions(id_usuario_edit); // Recargar la lista de comisiones
    },
    error: function (xhr, status, error) {
      alert("Error al modificar la comisión.");
    },
  });
}

function openModalEditComision(id) {
  $.fn.modal.Constructor.prototype._enforceFocus = function () {};
  $("#divLoaderFS").show();
  $.ajax({
    url: "src/getComisionById.php",
    method: "POST",
    data: { id_comision: id },
    success: function (respuesta) {
      $("#divLoaderFS").hide();
      const data = JSON.parse(respuesta);
      const { valor_comision, observaciones } = data[0];
      $(document).off("focusin.ui-dialog");
      let inputValor, inputObs;
      Swal.fire({
        title: "Parametrización de Comisiones",
        html: `<div class="form-group text-left"></div>
            <label for="valor_comision_edit">Valor Comisión:</label>
            <input type="text" class="form-control" id="valor_comision_edit" value="${valor_comision}">
          <div class="form-group text-left mt-3"></div>
            <label for="observaciones_edit">Observaciones:</label>
            <textarea class="form-control" id="observaciones_edit" rows="3">${observaciones}</textarea>
          `,
        showCancelButton: true,
        confirmButtonText: "Guardar Cambios",
        cancelButtonText: "Cerrar",
        didOpen: () => {
          inputValor = Swal.getPopup().querySelector("#valor_comision_edit");
          inputObs = Swal.getPopup().querySelector("#observaciones_edit");

          inputValor.value = valor_comision;
          inputObs.value = observaciones;

          inputValor.focus();
        },
        preConfirm: () => {
          return {
            nuevo_valor_comision: inputValor.value,
            nuevas_observaciones: inputObs.value,
          };
        },
      }).then((result) => {
        if (result.isConfirmed) {
          const { nuevo_valor_comision, nuevas_observaciones } = result.value;
          $("#divLoaderFS").show();
          $.ajax({
            url: "src/editComission.php",
            method: "POST",
            data: {
              id_comision: id,
              valor_comision: nuevo_valor_comision,
              observaciones: nuevas_observaciones,
            },
            success: function (respuesta) {
              $("#comisionesTableBody").html("");
              $("#divLoaderFS").hide();
              Swal.fire(
                "Éxito",
                "Comisión modificada correctamente.",
                "success"
              );
              getComissions(id_usuario_edit); // Recargar la lista de comisiones
            },
          });
        }
      });
    },
  });
}

async function eliminarComision(id) {
  if (!confirm("¿Estás seguro de que deseas eliminar esta comisión?")) {
    return;
  }
  $("#divLoaderFS").show();
  $.ajax({
    url: "src/eliminarComision.php",
    method: "POST",
    data: { id_comision: id },
    success: function (respuesta) {
      alert("Comisión eliminada correctamente.");
      $("#comisionesTableBody").html("");
      $("#divLoaderFS").hide();
      getComissions(id_usuario_edit); // Recargar la lista de comisiones
    },
    error: function (xhr, status, error) {
      alert("Error al eliminar la comisión.");
      $("#divLoaderFS").hide();
    },
  });
}

/*=============================================
Cargas Comentarios Usuario
=============================================*/

function getComissions(id = null) {
  $("#divLoaderFS").show();
  $("#comisionesTableBody").html(""); // Limpiar la tabla antes de cargar nuevos datos
  $.ajax({
    url: "src/getComissions.php",
    method: "POST",
    data: { id_usuario: id },
    success: function (respuesta) {
      const data = JSON.parse(respuesta);
      if (data.length == 0) {
        $("#comisionesTable tbody").html(
          '<tr><td colspan="7" class="text-center">No hay comisiones configuradas para este usuario</td></tr>'
        );
        return;
      }
      data.forEach((element) => {
        const {
          id_comision,
          observaciones,
          ramo,
          tipo_expedicion,
          tipo_negocio,
          unidad_negocio,
          valor_comision,
        } = element;

        const newRow = $("<tr>");
        newRow.append($("<td>").text(JSON.parse(ramo).join(", ")));
        newRow.append($("<td>").text(JSON.parse(unidad_negocio).join(", ")));
        newRow.append($("<td>").text(JSON.parse(tipo_negocio).join(", ")));
        newRow.append($("<td>").text(JSON.parse(tipo_expedicion).join(", ")));
        newRow.append($("<td>").text(valor_comision));
        newRow.append($("<td>").text(observaciones));
        newRow.append(
          $("<td>").html(
            `<button class="btn btn-danger btn-sm" onclick="eliminarComision(${id_comision})">Eliminar</button>
            <button class="btn btn-secondary btn-sm" onclick="openModalEditComision(${id_comision})">Editar</button>`
            // `<button class="btn btn-danger btn-sm" onclick="eliminarComision(${id_comision})">Eliminar</button>`
          )
        );

        $("#comisionesTableBody").append(newRow);
      });

      // $("#comisionesTableBody").html(respuesta);
    },
    complete: function () {
      $("#divLoaderFS").hide();
    },
  });
}

/*=============================================
Añadir Comentarios
=============================================*/

function addComment() {
  let value = $("#agregarComentario").val();
  let fechaHoy = new Date().toLocaleDateString("es-CO", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false,
  });

  if (!value) return;

  let comentarioNuevo = `---------------------------------------------------------------------------------------------------\n${value}\n${fechaHoy}\n${permisos.usu_nombre} ${permisos.usu_apellido}\n---------------------------------------------------------------------------------------------------\n`;

  // Agregar al textarea sin perder comentarios previos
  let comentariosPrevios = $("#comentarioTA").val();
  $("#comentarioTA").val(
    comentariosPrevios
      ? comentarioNuevo + "\n" + comentariosPrevios
      : comentarioNuevo
  );

  // Guardar el comentario en la base de datos
  $.ajax({
    url: "src/addComment.php",
    method: "POST",
    data: {
      comentario: value,
      id_user: id_usuario_edit,
      nombre_usuario_comentario:
        permisos.usu_nombre + " " + permisos.usu_apellido,
    },
    success: function (respuesta) {
      // console.log(respuesta);
    },
  });

  // Limpiar el input de agregar comentarios
  $("#agregarComentario").val("");
}

$(".btnComentario").on("click", function () {
  addComment();
});

/*=============================================
Traer comentarios
=============================================*/

function getComments(id) {
  // Guardar el comentario en la base de datos
  $.ajax({
    url: "src/getComments.php",
    method: "POST",
    data: {
      id_usuario: id,
    },
    success: function (respuesta) {
      const data = JSON.parse(respuesta);
      let comentarios = "";

      if (data.status == "error") {
        return;
      } else {
        data.forEach((element) => {
          const { comentario, fecha_comentario, nombre_usuario_comentario } =
            element;
          comentarios += `---------------------------------------------------------------------------------------------------\n${comentario}\n${fecha_comentario}\n${nombre_usuario_comentario}\n---------------------------------------------------------------------------------------------------\n\n`;
        });

        $("#comentarioTA").val(comentarios);
      }
      $("#comentarioTA").prop("disabled", true); // Deshabilitar textarea
    },
  });
}

/*=============================================
Eventos y reacciones de cambios en Inputs
=============================================*/

$("#tipoDePersona").change(function () {
  if ($(this).val() == 1) {
    if (
      permisos.id_rol == 12 ||
      permisos.id_rol == 22 ||
      permisos.id_rol == 23 ||
      permisos.id_rol == 11 ||
      permisos.id_rol == 10 ||
      permisos.id_rol == 1
    ) {
      $("#divCanal").css("display", "flex");
    }
    $("#divUnidadNegocio").css("display", "none");
    $(".legal").css("display", "none");
    $(".natural").css("display", "flex");
  } else {
    // $("#divCanal").css("display", "none");
    $("#divUnidadNegocio").css("display", "flex");
    $(".legal").css("display", "flex");
    $(".natural").css("display", "none");
  }
});

const roles = ["1", "10", "11", "12", "22", "23"];

$("#rolUsers").change(function () {
  if (roles.includes($(this).val())) {
    $(".divClavAseg").css("display", "none");
    $("#noClaves").prop("checked", true).trigger("change");
    $(".freelance").css("display", "none");
    $("#divRecomendador").css("display", "none");
    $(".divAsistente").css("display", "none");
    $("#divComisiones").css("display", "flex");
    $("#divCargos").css("display", "flex");
  } else {
    $(".divClavAseg").css("display", "block");
    $(".divAsistente").css("display", "block");
    // $("#siClaves").prop("checked", true).trigger("change");
    $(".freelance").css("display", "grid");
    $("#divRecomendador").css("display", "none");
    $("#divComisiones").css("display", "none");
    $("#divCargos").css("display", "none");
  }
});

$("#departamento").change(function () {
  consultarCiudad();
});

$("input[name='radioAsistente']").on("change", function () {
  if ($("#siAsistente").is(":checked")) {
    $(".asistente").css("display", "flex");
  } else if ($("#noAsistente").is(":checked")) {
    $(".asistente").css("display", "none");
  }
});

$("input[name='tieneClave']").on("change", function () {
  if ($("#siClaves").is(":checked")) {
    $(".clavesAseguradoras").css("display", "flex");
  } else if ($("#noClaves").is(":checked")) {
    $(".clavesAseguradoras").css("display", "none");
  }
});

/*=============================================
Configuración del modal
=============================================*/

function openModalComisiones(id = null) {
  // console.log(permisos.id_rol);
  if (
    permisos.id_rol != 22 &&
    permisos.id_rol != 23 &&
    permisos.id_rol != 10 &&
    permisos.id_rol != 1 &&
    permisos.id_rol != 11 &&
    permisos.id_rol != 12 &&
    permisos.id_usuario != id
  ) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No tienes permisos para acceder a esta opción.",
    });
    return;
  }

  $("#myModal2").dialog({
    title: "Parametrización de Comisiones",

    autoOpen: false,
    resizable: false, // Desactiva el redimensionamiento
    draggable: false, // Opcional, si deseas permitir que se pueda mover
    modal: true,
    dialogClass: "custom-dialog2",

    buttons: {
      Cerrar: function () {
        $(this).dialog("close");
      },
      Guardar: function () {
        // const form = $("#myModal2 form")[0];

        // // Validar el formulario
        // if (!form.checkValidity()) {
        //   // Si hay campos inválidos, mostrará los mensajes de error nativos del navegador
        //   form.reportValidity();
        //   return; // Detener la ejecución si hay errores
        // }
        $(this).dialog("close");
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
        "btnGuardarComision"
      );

      getComissions(id);
    },
    close: function () {
      $("#comisionesTableBody").empty();
      $("body").css("overflow", "auto");
      $("body").removeClass("modal-open"); // Quita la clase para restaurar el scroll
    },
  });

  // Abrir el diálogo
  $("#myModal2").dialog("open");
}

/*=============================================
Añadir Comision a tabla de comisiones
=============================================*/

async function addComision() {
  const ramoSelect = selectedOptions;
  const unidadNegocioSelect =
    $("#unidadNegocioSelect").val() == ""
      ? null
      : $("#unidadNegocioSelect option:selected").text();
  const tipoNegocioSelect =
    $("#tipoNegocioSelect").val() == ""
      ? null
      : $("#tipoNegocioSelect option:selected").text();
  const tipoExpedicionSelect =
    $("#tipoExpedicionSelect").val() == ""
      ? null
      : $("#tipoExpedicionSelect option:selected").text();
  const valorComision =
    $("#valorComision").val() == "" ? null : $("#valorComision").val();
  const obersavaciones =
    $("#observaciones").val() == "" ? null : $("#observaciones").val();

  if (
    checkFieldsComision(
      ramoSelect,
      valorComision,
      unidadNegocioSelect,
      tipoNegocioSelect,
      tipoExpedicionSelect
    )
  ) {
    // Crear una nueva fila para la tabla
    const newRow = $("<tr>");
    newRow.append($("<td>").text(ramoSelect));
    newRow.append($("<td>").text(unidadNegocioSelect));
    newRow.append($("<td>").text(tipoNegocioSelect));
    newRow.append($("<td>").text(tipoExpedicionSelect));
    newRow.append($("<td>").text(valorComision));
    newRow.append($("<td>").text(obersavaciones));

    let tbody = document.querySelector("#comisionesTable tbody");

    // Si solo hay una fila y tiene una única celda, se asume que es un mensaje de "sin datos"
    if (tbody.rows.length === 1 && tbody.rows[0].cells.length === 1) {
      tbody.deleteRow(0); // Borra la fila vacía
    }

    // Agregar la nueva fila a la tabla y guardar la comisión
    const saveComissionResult = await saveComission(
      ramoSelect,
      [unidadNegocioSelect],
      [tipoNegocioSelect],
      [tipoExpedicionSelect],
      valorComision,
      obersavaciones
    );

    newRow.append(
      $("<td>").html(
        `<button class="btn btn-danger btn-sm eliminarComision" onclick="eliminarComision(${saveComissionResult})">Eliminar</button>
         <button class="btn btn-secondary btn-sm eliminarComision" onclick="openModalEditComision(${saveComissionResult})">Editar</button>
        `
      )
    );
    $("#comisionesTable tbody").append(newRow);

    // Limpiar los campos del modal
    selectedOptions.length = 0; // Limpiar el array de opciones seleccionadas
    document.querySelector(".select-box").innerText = "Selecciona opciones...";
    $(".custom-select").find("input[type='checkbox']").prop("checked", false);
    $("#unidadNegocioSelect").val("");
    $("#tipoNegocioSelect").val("");
    $("#tipoExpedicionSelect").val("");
    $("#valorComision").val("");
    $("#observaciones").val("");
  }
}

function checkFieldsComision(
  ramoSelect,
  valorComision,
  unidadNegocioSelect,
  tipoNegocioSelect,
  tipoExpedicionSelect
) {
  // Verificar si los campos están vacíos o no seleccionados
  if (
    !ramoSelect ||
    !valorComision ||
    !unidadNegocioSelect ||
    !tipoNegocioSelect ||
    !tipoExpedicionSelect
  ) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Por favor, complete todos los campos obligatorios.",
      customClass: "swal2-custom222",
    }).then(() => {
      document.querySelector(".swal2-custom222").style.zIndex = "10000";
    });
    return false; // Algún campo está vacío o no seleccionado
  }

  if (valorComision.includes(",")) {
    valorComision = valorComision.replace(",", ".");
  }

  return true; // Todos los campos están completos
}

function saveComission(
  ramo,
  unidadNegocio,
  tipoNegocio,
  tipoExpedicion,
  valorComision,
  observaciones
) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "src/addComission.php",
      method: "POST",
      dataType: "json",
      data: {
        ramo: ramo,
        unidadNegocio: unidadNegocio,
        tipoNegocio: tipoNegocio,
        tipoExpedicion: tipoExpedicion,
        valorComision: valorComision,
        id_usuario: id_usuario_edit,
        id_super_usuario: permisos.id_usuario,
        observaciones: observaciones,
      },
      success: function (response) {
        resolve(response.id_inserted);
      },
      error: function (err) {
        reject(err);
      },
    });
  });
}

/*=============================================
Select2 del modal de comisiones
=============================================*/

function toggleOptions() {
  const optionsContainer = document.querySelector(".options-container");
  optionsContainer.style.display =
    optionsContainer.style.display === "block" ? "none" : "block";
}

let selectedOptions = [];

function updateSelectText(e = null) {
  const allCheckboxes = document.querySelectorAll(".options-container input");
  const checkedCheckboxesGlobal = document.querySelectorAll(
    ".options-container input:checked"
  );
  const todosCheckbox = document.querySelector(
    ".options-container input[value='Todos']"
  );

  if (e?.target.value == "Todos" && !e?.target.checked) {
    // console.log("checkeo todos desde select");
    // Marcar todas las opciones si "Todos" está seleccionado
    allCheckboxes.forEach((input) => (input.checked = false));
  }
  if (e?.target.value == "Todos" && e?.target.checked) {
    allCheckboxes.forEach((input) => (input.checked = true));
    const checkedCheckboxes = document.querySelectorAll(
      ".options-container input:checked"
    );
    // console.log(checkedCheckboxes);
    checkedCheckboxes.forEach((inpt) => {
      if (inpt.value !== "Todos" && !selectedOptions.includes(inpt.value)) {
        selectedOptions.push(inpt.value);
      }
    });
  }

  // Revisar qué opciones están seleccionadas (sin incluir "Todos")
  // allCheckboxes.forEach((input) => {
  let input = e?.target;
  if (input.checked && input.value !== "Todos") {
    // console.log("disparo evento de otro cualquiera menos todos");
    // Agregar solo si no existe
    if (!selectedOptions.includes(input.value)) {
      selectedOptions.push(input.value);
    }
  } else if (!input.checked && input.value !== "Todos") {
    // Eliminar si existe
    const index = selectedOptions.indexOf("Todos");
    if (index > -1) {
      selectedOptions.splice(index, 1);
      checkedCheckboxesGlobal.forEach((inpt) => {
        // console.log(inpt.value, selectedOptions);
        if (inpt.value !== "Todos" && !selectedOptions.includes(inpt.value)) {
          // console.log(inpt.value);
          selectedOptions.push(inpt.value);
        }
      });
    } else {
      const index2 = selectedOptions.indexOf(input.value);
      if (index2 > -1) {
        selectedOptions.splice(index2, 1);
        checkedCheckboxesGlobal.forEach((inpt) => {
          // console.log(inpt.value, selectedOptions);
          if (!selectedOptions.includes(inpt.value)) {
            // console.log(inpt.value);
            selectedOptions.push(inpt.value);
          }
        });
      }
    }
  } else if (input.value == "Todos" && !input.checked) {
    // console.log("desmarcando todos");
    // Si "Todos" está desmarcado, eliminar "Todos" del array
    allCheckboxes.forEach((input) => (input.checked = false));
    const index = selectedOptions.indexOf("Todos");
    if (index > -1) {
      selectedOptions.splice(index, 1);
    }
  }
  // });

  // Si todas las opciones individuales están seleccionadas, marcar "Todos"
  // console.log(selectedOptions.length, allCheckboxes.length);
  if (selectedOptions.length === allCheckboxes.length - 1) {
    selectedOptions.length = 0; // Limpiar y solo mostrar "Todos"
    selectedOptions.push("Todos");
    todosCheckbox.checked = true; // Asegurarse de que "Todos" esté marcado
  } else {
    todosCheckbox.checked = false;
  }

  // Actualizar el texto en el select
  document.querySelector(".select-box").innerText =
    selectedOptions.length > 0
      ? selectedOptions.join(", ")
      : "Selecciona opciones...";
}

// Cerrar el menú si se hace clic fuera de él
document.addEventListener("click", function (event) {
  const select = document.querySelector(".custom-select");
  if (!select.contains(event.target)) {
    document.querySelector(".options-container").style.display = "none";
  }
});

function toggleOptionsUnidad() {
  const optionsContainerUnidad = document.querySelector(
    ".options-container-unidad"
  );
  optionsContainerUnidad.style.display =
    optionsContainerUnidad.style.display === "block" ? "none" : "block";
}

function updateSelectTextUnidad(e = null) {
  const allCheckboxesUnidad = document.querySelectorAll(
    ".options-container-unidad input"
  );
  const checkedCheckboxesGlobalUnidad = document.querySelectorAll(
    ".options-container-unidad input:checked"
  );

  let input = e?.target;
  if (input.checked && input.value !== "Todos") {
    // console.log("disparo evento de otro cualquiera menos todos");
    // Agregar solo si no existe
    if (!selectedOptionsUnidad.includes(input.value)) {
      selectedOptionsUnidad.push(input.value);
    }
  } else if (!input.checked && input.value !== "Todos") {
    // Eliminar si existe
    const index = selectedOptionsUnidad.indexOf("Todos");
    if (index > -1) {
      selectedOptionsUnidad.splice(index, 1);
      checkedCheckboxesGlobalUnidad.forEach((inpt) => {
        if (
          inpt.value !== "Todos" &&
          !selectedOptionsUnidad.includes(inpt.value)
        ) {
          selectedOptionsUnidad.push(inpt.value);
        }
      });
    } else {
      const index2 = selectedOptionsUnidad.indexOf(input.value);
      if (index2 > -1) {
        selectedOptionsUnidad.splice(index2, 1);
        checkedCheckboxesGlobalUnidad.forEach((inpt) => {
          if (!selectedOptionsUnidad.includes(inpt.value)) {
            selectedOptionsUnidad.push(inpt.value);
          }
        });
      }
    }
  }

  const selectBox = document.querySelector("#div-options-unidades");

  if (selectedOptionsUnidad.length === 0) {
    selectBox.innerText = "Selecciona opciones...";
  } else if (selectedOptionsUnidad.length <= 2) {
    selectBox.innerText = selectedOptionsUnidad.join(", ");
  } else {
    selectBox.innerText =
      selectedOptionsUnidad.slice(0, 2).join(", ") + ", .....";
  }

  document.addEventListener("click", function (event) {
    const select = document.querySelector(".custom-select-unidad");
    if (!select.contains(event.target)) {
      document.querySelector(".options-container-unidad").style.display =
        "none";
    }
  });
}

document.addEventListener("click", function (event) {
  const select = document.querySelector(".custom-select");
  if (!select.contains(event.target)) {
    document.querySelector(".options-container").style.display = "none";
  }
});
