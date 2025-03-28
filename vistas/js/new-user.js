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
  //   $("#siClaves").prop("checked", true).trigger("change");
  $("#usuarioVin").prop("disabled", false);
  $("#fechaCreaVin").prop("disabled", false);
  $("#fechaVinculacion").prop("disabled", false);
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
    $("#comentarioTA").val(comentariosPrevios ? comentariosPrevios + "\n\n" + comentarioNuevo : comentarioNuevo);
  
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

const roles = ["1", "10", "11", "12"];

$("#rolUsers").change(function () {
  if (roles.includes($(this).val())) {
    $(".divClavAseg").css("display", "none");
    $("#noClaves").prop("checked", true).trigger("change");
    $(".freelance").css("display", "none");
  } else {
    $(".divClavAseg").css("display", "block");
    $("#siClaves").prop("checked", true).trigger("change");
    $(".freelance").css("display", "flex");
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
