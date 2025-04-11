// Variables globales
let id_usuario_edit = "";

document.addEventListener("DOMContentLoaded", function () {
  cargarRoll();
  cargarIntermediario();

  // Cargar parametros iniciales que disparan eventos
  // al iniciar cabe resaltar que solo se disparan si
  // es un usuario nuevo

  $("#tipoDePersona").val(1).trigger("change");
  $("#noAsistente").prop("checked", true).trigger("change");
  $("#usuarioVin").prop("disabled", false);
  $("#fechaCreaVin").prop("disabled", false);
  $("#fechaVinculacion").prop("disabled", false);

  const params = new URLSearchParams(window.location.search);

  if (params.has("id")) {
    const id = params.get("id");
    if (id) {
      // Cargar datos del usuario
      id_usuario_edit = id;
      loadUser(id);
    }
  }
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

function consultarCiudad() {
  var codigoDpto = $("#departamento").val();
  $.ajax({
    type: "POST",
    url: "src/consultarCiudadHogar.php",
    data: { codigoDpto: codigoDpto },
    cache: false,
    success: function (data) {
      let ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;
      try {
        let json = JSON.parse(data);
        json.sort((a, b) => a.codigo - b.codigo);

        json.forEach(({ codigo, ciudad }) => {
          ciudadesVeh += `<option value="${codigo}">${ciudad}</option>`;
        });

        $("#ciudad").append(ciudadesVeh);
      } catch (error) {
        console.error("Error al procesar JSON:", error);
      }
    },
  });
}

function formatoFecha(fecha) {
  let fechaFormateada = new Date(fecha.replace(" ", "T"));
  let year = fechaFormateada.getFullYear();
  let month = String(fechaFormateada.getMonth() + 1).padStart(2, "0");
  let day = String(fechaFormateada.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

/*=============================================
Cargar Usuario
=============================================*/

function loadUser(id) {
  $.ajax({
    url: "src/loadUser.php",
    method: "POST",
    data: { id: id },
    success: function (respuesta) {
      const data = JSON.parse(respuesta);
      console.log(data);
      getComments(id);
      if (
        data.id_rol == 1 ||
        data.id_rol == 10 ||
        data.id_rol == 11 ||
        data.id_rol == 22 ||
        data.id_rol == 23
      ) {
        $("#divUnidadNegocio").hide();
        $("#divCanal").hide();
        $("#divUsuarioSGA").show();
        $("#usuarioSGA").val(data.id_rol);
        $(".divAsistente").hide();
        $("#rolUsers").val(data.id_rol).trigger("change");
        setTimeout(() => {
          $("#intermediarioPerfil")
            .val(data.id_Intermediario)
            .trigger("change");
          $("#cargos").val(data.usu_cargo).trigger("change");
        }, 500);
      } else if (data.id_rol == 12) {
        $("#divUnidadNegocio").hide();
        $("#divUsuarioSGA").hide();
        $("#divCanal").show();
        $("#canal").val(1);
      } else {
        cargarAnalistas();
        cargarBancos();
        cargarCargos();
        $("#divUnidadNegocio").show();
        $("#divCanal").hide();
        $("#divUsuarioSGA").hide();
        $("#unidadNegocio").val(data.id_rol);
        $("#rolUsers").val(data.id_rol).trigger("change");
        setTimeout(() => {
          $("#intermediarioPerfil")
            .val(data.id_Intermediario)
            .trigger("change");
          // $("#cargos").val(data.usu_cargo).trigger("change");
        }, 500);

        $("#usuarioVin").prop("disabled", true);
        $("#usuarioVin").val(data.usu_usuario);

        //Insertar fecha de creación
        let fechaForCrea = formatoFecha(data.usu_fch_creacion);

        $("#fechaCreaVin").val(fechaForCrea);
        $("#fechaCreaVin").prop("disabled", true);

        if ($("#fechaVinculacion").val() == "") {
          $("#diasActivacion").val(0);
        }

        let fechaFormLim = formatoFecha(data.fechaFin);

        $("#limiteCots").val(data.cotizacionesTotales);
        $("#limiteUso").val(fechaFormLim);

       
      }

      let tipoDoc = data.tipos_documentos_id;

      $("#tipoDocumento").val(tipoDoc == "Cedula de Ciudadania" ? "CC" : "NIT");
      $("#documento").val(data.usu_documento);
      $("#nombre_perfil").val(data.usu_nombre);
      $("#apellidos_perfil").val(data.usu_apellido);
      $("#genero_perfil").val(data.usu_genero == "M" ? "1" : "2");
      $("#fechaNacimiento_perfil").val(data.usu_fch_nac);
      let depto = data.ciudades_id.split("")[0] + data.ciudades_id.split("")[1];
      $("#departamento").val(depto).trigger("change");
      console.log(data.ciudades_id);
      setTimeout(() => {
        $("#ciudad").val(data.ciudades_id).trigger("change");
      }, 200);
      $("#direccion_perfil").val(data.usu_direccion);
      $("#telefono_perfil").val(data.usu_telefono);
      $("#email_perfil").val(data.usu_email);
    },
  });
}

/*=============================================
Cargas Comentarios Usuario
=============================================*/

function getComissions(id = null) {
  $.ajax({
    url: "src/getComissions.php",
    method: "POST",
    data: { id_usuario: id },
    success: function (respuesta) {
      const data = JSON.parse(respuesta);
      console.log(data);
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
            `<button class="btn btn-danger btn-sm" onclick="eliminarComision(${id_comision})">Eliminar</button>`
          )
        );

        $("#comisionesTableBody").append(newRow);
      });

      // $("#comisionesTableBody").html(respuesta);
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
      console.log(respuesta);
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

      data.forEach((element) => {
        const { comentario, fecha_comentario, nombre_usuario_comentario } =
          element;
        comentarios += `---------------------------------------------------------------------------------------------------\n${comentario}\n${fecha_comentario}\n${nombre_usuario_comentario}\n---------------------------------------------------------------------------------------------------\n\n`;
      });

      $("#comentarioTA").val(comentarios);
      $("#comentarioTA").prop("disabled", true); // Deshabilitar textarea
    },
  });
}

/*=============================================
Eventos y reacciones de cambios en Inputs
=============================================*/

$("#tipoDePersona").change(function () {
  if ($(this).val() == 1) {
    $("#divCanal").css("display", "flex");
    $("#divUnidadNegocio").css("display", "none");
    $(".legal").css("display", "none");
    $(".natural").css("display", "flex");
  } else {
    $("#divCanal").css("display", "none");
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
    $(".divAsistente").css("display", "none");
    $("#divComisiones").css("display", "flex");
  } else {
    $(".divClavAseg").css("display", "block");
    $(".divAsistente").css("display", "block");
    $("#siClaves").prop("checked", true).trigger("change");
    $(".freelance").css("display", "grid");
    $("#divComisiones").css("display", "none");
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
        const form = $("#myModal2 form")[0];

        // Validar el formulario
        if (!form.checkValidity()) {
          // Si hay campos inválidos, mostrará los mensajes de error nativos del navegador
          form.reportValidity();
          return; // Detener la ejecución si hay errores
        }
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
      $(".ui-dialog-buttonpane button:contains('Guardar')").attr(
        "class",
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

function addComision() {
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
    newRow.append(
      $("<td>").html(
        '<button class="btn btn-danger btn-sm eliminarComision">Eliminar</button>'
      )
    );

    let tbody = document.querySelector("#comisionesTable tbody");

    // Si solo hay una fila y tiene una única celda, se asume que es un mensaje de "sin datos"
    if (tbody.rows.length === 1 && tbody.rows[0].cells.length === 1) {
      tbody.deleteRow(0); // Borra la fila vacía
    }

    // Agregar la nueva fila a la tabla y guardar la comisión
    $("#comisionesTable tbody").append(newRow);
    saveComission(
      ramoSelect,
      [unidadNegocioSelect],
      [tipoNegocioSelect],
      [tipoExpedicionSelect],
      valorComision,
      obersavaciones
    );

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
      id_usuario: permisos.id_usuario,
      id_super_usuario: permisos.id_usuario,
      observaciones: observaciones,
    },
    success: function () {
      console.log("Se aguardo la comision para el usuario");
    },
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
  debugger;
  const allCheckboxes = document.querySelectorAll(".options-container input");
  const checkedCheckboxesGlobal = document.querySelectorAll(
    ".options-container input:checked"
  );
  const todosCheckbox = document.querySelector(
    ".options-container input[value='Todos']"
  );

  if (e?.target.value == "Todos" && !e?.target.checked) {
    console.log("checkeo todos desde select");
    // Marcar todas las opciones si "Todos" está seleccionado
    allCheckboxes.forEach((input) => (input.checked = false));
  }
  if (e?.target.value == "Todos" && e?.target.checked) {
    allCheckboxes.forEach((input) => (input.checked = true));
    const checkedCheckboxes = document.querySelectorAll(
      ".options-container input:checked"
    );
    console.log(checkedCheckboxes);
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
    console.log("disparo evento de otro cualquiera menos todos");
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
        console.log(inpt.value, selectedOptions);
        if (inpt.value !== "Todos" && !selectedOptions.includes(inpt.value)) {
          console.log(inpt.value);
          selectedOptions.push(inpt.value);
        }
      });
    } else {
      const index2 = selectedOptions.indexOf(input.value);
      if (index2 > -1) {
        selectedOptions.splice(index2, 1);
        checkedCheckboxesGlobal.forEach((inpt) => {
          console.log(inpt.value, selectedOptions);
          if (!selectedOptions.includes(inpt.value)) {
            console.log(inpt.value);
            selectedOptions.push(inpt.value);
          }
        });
      }
    }
  } else if (input.value == "Todos" && !input.checked) {
    console.log("desmarcando todos");
    // Si "Todos" está desmarcado, eliminar "Todos" del array
    allCheckboxes.forEach((input) => (input.checked = false));
    const index = selectedOptions.indexOf("Todos");
    if (index > -1) {
      selectedOptions.splice(index, 1);
    }
  }
  // });

  // Si todas las opciones individuales están seleccionadas, marcar "Todos"
  console.log(selectedOptions.length, allCheckboxes.length);
  if (selectedOptions.length === allCheckboxes.length - 1) {
    console.log("la longitud del selected es igual a la de los checkboxes -1");
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
