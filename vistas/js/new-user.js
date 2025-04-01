document.addEventListener("DOMContentLoaded", function () {
  cargarRoll();
  cargarIntermediario();
  cargarAnalistas();
  cargarBancos();

  // Cargar parametros iniciales que disparan eventos
  // al iniciar cabe resaltar que solo se disparan si
  // es un usuario nuevo

  $("#tipoDePersona").val(1).trigger("change");
  $("#noAsistente").prop("checked", true).trigger("change");
  $("#usuarioVin").prop("disabled", false);
  $("#fechaCreaVin").prop("disabled", false);
  $("#fechaVinculacion").prop("disabled", false);

  openModalComisiones();
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
      console.log(respuesta);
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

/*=============================================
Añadir Comentarios
=============================================*/

function addComment() {
  let value = $("#agregarComentario").val();
  let fechaHoy = new Date().toLocaleDateString("es-CO", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  });

  if (!value) return;

  let comentarioNuevo = `---------------------------------------------------------------------------------------------------\n${value}\n${fechaHoy}\n${permisos.usu_nombre} ${permisos.usu_apellido}\n---------------------------------------------------------------------------------------------------`;

  // Agregar al textarea sin perder comentarios previos
  let comentariosPrevios = $("#comentarioTA").val();
  $("#comentarioTA").val(
    comentariosPrevios
      ? comentariosPrevios + "\n\n" + comentarioNuevo
      : comentarioNuevo
  );

  // Limpiar el input de agregar comentarios
  $("#agregarComentario").val("");
}

$(".btnComentario").on("click", function () {
  addComment();
});

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
    cargarCargos();
    $(".divClavAseg").css("display", "none");
    $("#noClaves").prop("checked", true).trigger("change");
    $(".freelance").css("display", "none");
    $(".divAsistente").css("display", "none");
    $("#divComisiones").css("display", "flex");
  } else {
    $(".divClavAseg").css("display", "block");
    $(".divAsistente").css("display", "block");
    $("#siClaves").prop("checked", true).trigger("change");
    $(".freelance").css("display", "flex");
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

function openModalComisiones() {
  $("#myModal2").dialog({
    title: "Agregar/editar oportunidad",

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
    },
    close: function () {
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
  const ramoSelect =
    $("#ramoSelect").val() == ""
      ? null
      : $("#ramoSelect option:selected").text();
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
      [ramoSelect],
      [unidadNegocioSelect],
      [tipoNegocioSelect],
      [tipoExpedicionSelect],
      valorComision,
      obersavaciones
    );

    // Limpiar los campos del modal
    $("#ramoSelect").val("");
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

function updateSelectText(e = null) {
  const allCheckboxes = document.querySelectorAll(".options-container input");
//   const checkedCheckboxes = document.querySelectorAll(
//     ".options-container input:checked"
//   );
  const todosCheckbox = document.querySelector(
    ".options-container input[value='Todos']"
  );
  const selectedOptions = [];

  if (e?.target.value == "Todos" && !e?.target.checked) {
    console.log("checkeo todos desde select");
    // Marcar todas las opciones si "Todos" está seleccionado
    allCheckboxes.forEach((input) => (input.checked = false));
  } 
  if (e?.target.value == "Todos" && e?.target.checked) {
    allCheckboxes.forEach((input) => (input.checked = true));
  }
  
  // Revisar qué opciones están seleccionadas (sin incluir "Todos")
  allCheckboxes.forEach((input) => {
    if (input.checked && input.value !== "Todos") {
      console.log("disparo evento de otro cualquiera menos todos");
      selectedOptions.push(input.value);
    }
  });

  // Si todas las opciones individuales están seleccionadas, marcar "Todos"
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
