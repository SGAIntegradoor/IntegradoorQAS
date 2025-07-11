/*=============================================
CARGANDO DATOS DE INICIO
=============================================*/
(() => {
  cargarIntermediario();
  cargarRoll();
})();

let getParams = () => {
  let url = new URL(window.location.href);
  return Object.fromEntries(url.searchParams.entries());
};

let url = `index.php?ruta=usuarios`;

function searchInfo() {
  let fechaVinculacionFiltro =
    $("#fechaVinculacionFiltro").val() !== ""
      ? $("#fechaVinculacionFiltro").val()
      : "";
  let fechaDesvinculacionFiltro =
    $("#fechaDesvinculacionFiltro").val() !== "" ? $("#fechaDesvinculacionFiltro").val() : "";
  let identificacionFiltro =
    $("#identificacionFiltro").val() !== ""
      ? $("#identificacionFiltro").val()
      : "";
  let nombreFiltro =
    $("#nombreFiltro").val() !== ""
      ? $("#nombreFiltro").val()
      : "";
  let celularFiltro =
    $("#celularFiltro").val() !== ""
      ? $("#celularFiltro").val()
      : "";
  let emailFiltro = $("#emailFiltro").val() !== "" ? $("#emailFiltro").val() : "";
  let ciudadFiltro =
    $("#ciudadFiltro").val() !== ""
      ? $("#ciudadFiltro option:selected").text()
      : "";
  if (fechaVinculacionFiltro !== "") {
    url += `&fechaVinculacionFiltro=${fechaVinculacionFiltro}`;
  }

  if (fechaDesvinculacionFiltro !== "") {
    url += `&fechaDesvinculacionFiltro=${fechaDesvinculacionFiltro}`;
  }

  if (identificacionFiltro !== "") {
    url += `&identificacionFiltro=${identificacionFiltro}`;
  }

  if (nombreFiltro !== "") {
    url += `&nombreFiltro=${nombreFiltro}`;
  }

  if (celularFiltro !== "") {
    url += `&celularFiltro=${celularFiltro}`;
  }

  if (emailFiltro !== "") {
    url += `&emailFiltro=${emailFiltro}`;
  }

  if (ciudadFiltro !== "") {
    url += `&ciudadFiltro=${ciudadFiltro.trim()}`;
  }

  window.location.href = url;
}

let params = getParams();

if(Object.keys(params).length > 0){
  
  aplicarCriterios();
}

function aplicarCriterios() {
  const criterios = [
    "fechaVinculacionFiltro",
    "fechaDesvinculacionFiltro",
    "identificacionFiltro",
    "nombreFiltro",
    "celularFiltro",
    "emailFiltro",
    "ciudadFiltro",
  ];

  for (let [key, value] of Object.entries(params)) {
    if (criterios.includes(key)) {
      if($(`#${key}`).is("select")){
        $(`#${key}`).val(value).trigger("change");
      }else {
        console.log(key)
        $(`#${key}`).val(value);
      }
    }
  }
}

$("#creaTemporal").on("click", function () {
  let ISO = new Date().toISOString();
  // Eliminar los caracteres que no deseas
  let randomizer = ISO.replace(/[-T:Z.]/g, "");

  let random = Math.random();
  let resultRand = Math.round(random);

  let newName = `Invitado`;
  let newUserTemp = newName;

  let actualDate = new Date();

  // Sumar 7 días a la fecha actual
  actualDate.setDate(actualDate.getDate() + 7);

  // Obtener los componentes de la fecha
  let year = actualDate.getFullYear();
  let month = ("0" + (actualDate.getMonth() + 1)).slice(-2); // Se suma 1 porque los meses van de 0 a 11
  let day = ("0" + actualDate.getDate()).slice(-2);

  // Formatear la fecha en el formato YYYYmmdd
  let formattedDate = year + "-" + month + "-" + day + " 23:59:59";

  $.ajax({
    url: "ajax/crearUsuarioTemporal.php",
    method: "POST",
    data: {
      newName: newName,
      newLastName: "SGA",
      newDocIdUser: `${randomizer}`,
      newUserTemp: newUserTemp,
      newPassword: `Temporal123*`,
      newGender: resultRand == 1 ? "M" : "F",
      newRol: 20,
      newPhone: "3000000001",
      newEmail: "tecnologia@grupoasistencia.com",
      newCharge: "Guest",
      maxCotizaciones: 20,
      cotizacionesTotales: 20,
      intermediario: 3,
      lifeTime: formattedDate,
      bornDate: formattedDate,
      address: "Calle 11 # 11-11",
      city: 30000,
      typeDoc: null,
      picture:
        resultRand == 1
          ? "vistas/img/usuarios/guestUser/257.jpg"
          : "vistas/img/usuarios/guestUser/258.jpg",
    },
    success: function (respuesta) {
      let parsedVar = JSON.parse(respuesta);
      if (parsedVar.responseSuccess) {
        swal
          .fire({
            icon: "success",
            title: "¡El usuario temporal ha sido creado correctamente!",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
          })
          .then(function (result) {
            if (result.value) {
              window.location = "usuarios";
            }
          });
      } else {
        swal
          .fire({
            icon: "error",
            title:
              "El usuario temporal no ha sido creado, valida porfavor con el administrador del sistema",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
          })
          .then(function (result) {
            if (result.value) {
              window.location = "usuarios";
            }
          });
      }
    },
    error: function (xhr, status, error) {
      console.log(error);
    },
  });
});

function cargarIntermediario() {
  const $idInter = document.getElementById("idIntermediario");
  const $idInter2 = document.getElementById("idIntermediario2");

  $.ajax({
    url: "ajax/cargarIntermediario.php",
    method: "POST",
    success: function (respuesta) {
      respuesta = "<option disabled selected></option>" + respuesta;

      $idInter.innerHTML = respuesta;
      $idInter2.innerHTML = respuesta;

      //Carga los Intermediarios disponibles para agregar
      $("#idIntermediario").select2({
        theme: "bootstrap",
        language: "es",
        width: "100%",
        // placeholder: "Intermediario", // Esto configura el placeholder
        // dropdownParent: $("#modalAgregarUsuario")
        dropdownParent: $("#idIntermediario").parent(),
      });

      //Carga los Intermediarios disponibles para editar
      $("#idIntermediario2").select2({
        theme: "bootstrap",
        language: "es",
        width: "100%",
        // placeholder: "Intermediario", // Esto configura el placeholder
        // dropdownParent: $("#modalAgregarUsuario")
        dropdownParent: $("#idIntermediario2").parent(),
      });
    },
  });
}

/*=============================================
CARGAR ROLL
=============================================*/

function cargarRoll() {
  const $idRoll = document.getElementById("idRoll");
  const $idRoll1 = document.getElementById("editarRol");
  const idRol = $("#idRolAdmin").val();

  $.ajax({
    url: "ajax/cargarRoll.php",
    method: "POST",
    data: { idRol: idRol }, // Enviar idRol en el cuerpo de la solicitud AJAX
    success: function (respuesta) {
      respuesta = "<option disabled selected></option>" + respuesta;
      $idRoll.innerHTML = respuesta;
      $idRoll1.innerHTML = respuesta;

      // Carga los Intermediarios disponibles para agregar
      $("#idRoll").select2({
        theme: "bootstrap rol",
        language: "es",
        width: "100%",
        placeholder: "Rol*", // Esto configura el placeholder
        // dropdownParent: $("#modalAgregarUsuario")
        dropdownParent: $("#idRoll").parent(),
      });

      // Carga los Intermediarios disponibles para editar
      $("#editarRol").select2({
        theme: "bootstrap rol",
        language: "es",
        width: "100%",
        placeholder: "Rol", // Esto configura el placeholder
        // dropdownParent: $("#modalAgregarUsuario")
        dropdownParent: $("#editarRol").parent(),
      });
    },
  });
}

/*=============================================
CARGAR Foto
=============================================*/
$(".nuevaFoto").change(function () {
  var imagen = this.files[0];

  /*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
    $(".nuevaFoto").val("");

    swal({
      title: "Error al subir la imagen",
      text: "¡La imagen debe estar en formato JPG o PNG!",
      type: "error",
      confirmButtonText: "¡Cerrar!",
    });
  } else if (imagen["size"] > 2000000) {
    $(".nuevaFoto").val("");

    swal({
      title: "Error al subir la imagen",
      text: "¡La imagen no debe pesar más de 2MB!",
      type: "error",
      confirmButtonText: "¡Cerrar!",
    });
  } else {
    var datosImagen = new FileReader();
    datosImagen.readAsDataURL(imagen);

    $(datosImagen).on("load", function (event) {
      var rutaImagen = event.target.result;

      $(".previsualizar").attr("src", rutaImagen);
    });
  }
});

/*=============================================
AGREGAR USUARIO SELECT2 Y CONFIGURACIONES
=============================================*/

// Modal editar Conviete la letras iniciales del Nombre y el Apellido deL Cliente en Mayusculas
$("#editarNombre").keyup(function () {
  var cliNombres = document.getElementById("editarNombre").value.toLowerCase();
  $("#editarNombre").val(
    cliNombres.replace(/^(.)|\s(.)/g, function ($1) {
      return $1.toUpperCase();
    })
  );
});

$("#editarApellido").keyup(function () {
  var cliApellido = document
    .getElementById("editarApellido")
    .value.toLowerCase();
  $("#editarApellido").val(
    cliApellido.replace(/^(.)|\s(.)/g, function ($1) {
      return $1.toUpperCase();
    })
  );
});

// Modal agregar Conviete la letras iniciales del Nombre y el Apellido deL Cliente en Mayusculas
$("#nuevoNombre").keyup(function () {
  var cliNombres = document.getElementById("nuevoNombre").value.toLowerCase();
  $("#nuevoNombre").val(
    cliNombres.replace(/^(.)|\s(.)/g, function ($1) {
      return $1.toUpperCase();
    })
  );
});

$("#nuevoApellido").keyup(function () {
  var cliApellido = document
    .getElementById("nuevoApellido")
    .value.toLowerCase();
  $("#nuevoApellido").val(
    cliApellido.replace(/^(.)|\s(.)/g, function ($1) {
      return $1.toUpperCase();
    })
  );
});

// Carga los Documentos disponibles para agregar
$("#agregarTipoDocumento").select2({
  theme: "bootstrap doc",
  language: "es",
  width: "100%",
  placeholder: "Tipo Documento*", // Esto configura el placeholder
  // dropdownParent: $("#modalAgregarUsuario")
  dropdownParent: $("#agregarTipoDocumento").parent(),
});

// Carga los Generos disponibles para agregar
$("#nuevoGenero").select2({
  theme: "bootstrap gen",
  language: "es",
  width: "100%",
  placeholder: "Genero*", // Esto configura el placeholder
  // dropdownParent: $("#modalAgregarUsuario")
  dropdownParent: $("#nuevoGenero").parent(),
});

// Carga los Departamentos disponibles para editar
$("#DptoCirculacion").select2({
  theme: "bootstrap dpto1",
  language: "es",
  width: "100%",
  // dropdownParent: $("#modalAgregarUsuario")
  dropdownParent: $("#DptoCirculacion").parent(),
});

$("#DptoCirculacion").change(function () {
  consultarCiudad();
});

// Carga las Ciudades disponibles para editar
$("#ciudadCirculacion").select2({
  theme: "bootstrap ciudad1",
  language: "es",
  width: "100%",
  // dropdownParent: $("#modalAgregarUsuario")
  dropdownParent: $("#ciudadCirculacion").parent(),
});

// Carga los Departamentos disponibles para agregar
$("#ingDptoCirculacion").select2({
  theme: "bootstrap dpto1",
  language: "es",
  width: "100%",
  placeholder: "Departamento*",
  dropdownParent: $("#ingDptoCirculacion").parent(),
  // dropdownParent: $("#modalAgregarUsuario")
});

$("#ingDptoCirculacion").change(function () {
  consultarCiudadAgregar();
});

// Carga las Ciudades disponibles para agregar
$("#ingciudadCirculacion").select2({
  theme: "bootstrap ciudad1",
  language: "es",
  width: "100%",
  placeholder: "Ciudad*", // Texto del placeholder del buscador
  // dropdownParent: $("#modalAgregarUsuario")
  dropdownParent: $("#ingciudadCirculacion").parent(),
});

// FUNCION PARA CARGAR LA CIUDAD DE CIRCULACIÓN
function consultarCiudad() {
  var codigoDpto = document.getElementById("DptoCirculacion").value;

  $.ajax({
    type: "POST",
    url: "src/consultarCiudad.php",
    dataType: "json",
    data: { data: codigoDpto },
    cache: false,
    success: function (data) {
      // console.log(data);
      var ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;

      data.forEach(function (valor, i) {
        var valorNombre = valor.Nombre.split("-");
        var nombreMinusc = valorNombre[0].toLowerCase();
        var ciudad = nombreMinusc.replace(/^(.)|\s(.)/g, function ($1) {
          return $1.toUpperCase();
        });

        ciudadesVeh += `<option value="${valor.Codigo}">${ciudad}</option>`;
      });
      document.getElementById("ciudadCirculacion").innerHTML = ciudadesVeh;
      // document.getElementById("ingciudadCirculacion").innerHTML = ciudadesVeh;
    },
  });

  //}
}

// Control Variables
let changeAnalist = false;
let notAssigned = false;
let initialValueAnalista = "";

function cargarAnalistas(rol) {
  let analistas = $("#analista");
  let analistasCrear = $("#nuevoAnalista");

  if(permisos.Editarusuario != "null"){
    analistas.prop("disabled", false);
  }

  return new Promise((resolve, reject) => {
    if (rol == 19 || rol == "19") {
      $.ajax({
        type: "POST",
        url: "src/consultarAnalistas.php",
        cache: false,
        success: function (data) {
          let response = JSON.parse(data);
          let analistasList = `<option value="1">No Aplica</option>`;

          response.map((element) => {
            analistasList += `<option value="${element.id_analista}">${element.nombre_analista}</option>`; // Corregido el atributo 'value'
          });
          analistas.html(analistasList);
          analistasCrear.html(analistasList);
          resolve(); // Notifica que terminó de cargar
        },
        error: function (error) {
          reject(error); // Notifica si hubo un error
        },
      });
    } else {
      let analistasList = `<option value="1" selected>No Aplica</option>`;
      analistas.html(analistasList);
      analistasCrear.html(analistasList);
      analistas.prop("disabled", true);
      analistasCrear.prop("disabled", true);
      resolve(); // No hay nada que cargar, pero se resuelve la promesa
    }
  });
}

function cargarAnalistaFreelance(id, rol) {
  let analistas = $("#analista");
  var datos = new FormData();
  datos.append("idUsuario", id);
  if (rol == 19 || rol == "19") {
    $.ajax({
      type: "POST",
      url: "src/consultarAnalistaFreelance.php",
      data: datos,
      processData: false, // Desactiva el procesamiento automático de datos
      contentType: false, // Desactiva el tipo de contenido predeterminado
      cache: false,
      success: function (data) {
        let response = JSON.parse(data);
        if(response.codeStatus == 0){
          notAssigned = true;
          cargarAnalistas(19);
          analistas.val(1);
        } else {
          console.log(response);
          analistas.val(response[0].id_analista);
          //analistas.prop("disabled", true);
        }
      },
    });
  } else {
    return;
  }
}

// FUNCION PARA CARGAR LA CIUDAD DE CIRCULACIÓN
function consultarCiudadAgregar() {
  var codigoDpto = document.getElementById("ingDptoCirculacion").value;
  
  $.ajax({
    type: "POST",
    url: "src/consultarCiudad.php",
    dataType: "json",
    data: { data: codigoDpto },
    cache: false,
    success: function (data) {
      // console.log(data);
      var ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;

      data.forEach(function (valor, i) {
        var valorNombre = valor.Nombre.split("-");
        var nombreMinusc = valorNombre[0].toLowerCase();
        var ciudad = nombreMinusc.replace(/^(.)|\s(.)/g, function ($1) {
          return $1.toUpperCase();
        });

        ciudadesVeh += `<option value="${valor.Codigo}">${ciudad}</option>`;
      });
      // document.getElementById("ciudadCirculacion").innerHTML = ciudadesVeh;
      document.getElementById("ingciudadCirculacion").innerHTML = ciudadesVeh;
    },
  });
  
  //}
}

$(".btnAgregarUsuario").on("click", function () {
  cargarAnalistas(19);
})

const btnSubmit = $("#btnSubmitUser");

document.addEventListener('DOMContentLoaded', () => {
  // Verificar si el botón está deshabilitado
  if (btnSubmit.prop('disabled')) {
    btnSubmit.on('click', (event) => {
          event.preventDefault();
          Swal.fire({
            icon: "warning",
            title: "Función Inhabilitada",
            text: "No tiene permiso para usar esta función, comunicate con el administrador para ver como obtenerla",
            showConfirmButton: true,
            confirmButtonText: "Aceptar",
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.reload();
            } else if (result.isDismissed) {
              window.location.reload();
            }
          });
      });
  }
});

$(".tablas-user-admin").on("click", ".btnEditarUsuarioAdmin", function () {
  let idUsuario = $(this).attr("idUsuario");
  window.location = `user?id=${idUsuario}`;
}
);

$(".tablas").on("click", ".btnEditarUsuario", function (e) {
  var idUsuario = $(this).attr("idUsuario");

  e.preventDefault();
  var datos = new FormData();
  datos.append("idUsuario", idUsuario);
  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#idUsuEdit").val(respuesta["id_usuario"]);
      $("#editarNombre").val(respuesta["usu_nombre"]);
      $("#editarApellido").val(respuesta["usu_apellido"]);
      $("#editarDocIdUser").val(respuesta["usu_documento"]);
      $("#editarUsuario").val(respuesta["usu_usuario"]);
      $("#passwordActual").val(respuesta["usu_password"]);
      $("#editarGenero").val(respuesta["usu_genero"]);
      $("#editarTelefono").val(respuesta["usu_telefono"]);
      $("#editarEmail").val(respuesta["usu_email"]);
      $("#editarCargo").val(respuesta["usu_cargo"]);
      $("#fotoActual").val(respuesta["usu_foto"]);
      $("#editarRol").val(respuesta["id_rol"]);
      $("#idIntermediario2").val(respuesta["id_Intermediario"]);
      $("#analista").val(respuesta["analista_comercial"]);
      //$("#maxiCot").val(respuesta["numCotizaciones"]);
      $("#cotizacionesTotales").val(respuesta["cotizacionesTotales"]);
      $("#fechaLimEdi").val(respuesta["fechaFin"]);
      $("#fechNacimiento").val(respuesta["usu_fch_nac"]);
      $("#editarDireccion").val(respuesta["usu_direccion"]);
      $("#editarTipoDocumento").val(respuesta["tipos_documentos_id"]);
      $("#editarTipoDocumento").trigger("change");
      $("#editarGenero").trigger("change");
      $("#idIntermediario2").trigger("change");
      $("#editarRol").trigger("change");

      cargarAnalistas(respuesta["id_rol"])
        .then(() => {
          cargarAnalistaFreelance(
            respuesta["usu_documento"],
            respuesta["id_rol"]
          );
          initialValueAnalista = $("#analista").val();
        })
        .catch((error) => {
          console.error("Error al cargar analistas:", error);
        });

      // Convertir la fecha ISO 8601 a un objeto Date

      function formatearFechaISO8601(fechaISO8601) {
        // Convertir la fecha ISO 8601 a un objeto Date
        var fecha = new Date(fechaISO8601);

        // Obtener los componentes de la fecha
        var dia = fecha.getDate();
        var mes = fecha.getMonth() + 1; // Los meses van de 0 a 11, sumamos 1 para obtener el mes correcto
        var anio = fecha.getFullYear();

        // Formatear la fecha en formato "yyyy-mm-dd"
        var fechaFormateada =
          anio +
          "-" +
          (mes < 10 ? "0" + mes : mes) +
          "-" +
          (dia < 10 ? "0" + dia : dia);

        return fechaFormateada;
      }

      // Supongamos que tienes la fecha en formato ISO 8601 en la variable 'fechaISO8601'
      var fechaISO8601 = respuesta["usu_fch_creacion"];

      // Formatear la fecha
      var fechaFormateada = formatearFechaISO8601(fechaISO8601);

      // Asignar la fecha formateada al campo de entrada
      $("#fechaUserExist").val(fechaFormateada);

      // Logica foto de usuario
      if (respuesta["usu_foto"] != "") {
        $(".previsualizarEditar").attr("src", respuesta["usu_foto"]);
        $(".previsualizarEditarPDF").attr("src", respuesta["usu_logo_pdf"]);
      } else {
        $(".previsualizarEditar").attr(
          "src",
          "vistas/img/usuarios/default/anonymous.png"
        );
      }

      // Crear una instancia de FormData
      var formData = new FormData();

      // Obtener el código de ciudad
      var codigoCiudad = respuesta["ciudades_id"];
      formData.append("ciudad", codigoCiudad);

      // FUNCION BUSCAR CIUDAD #1

      $.ajax({
        url: "ajax/ciudades.ajax.php",
        method: "POST",
        data: formData, // Agrega el nombre del campo "ciudad"
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
          // Supongamos que tienes el nombre del departamento en una variable llamada 'departamento'
          var municipio = respuesta.Nombre; // Nombre del departamento obtenido desde la respuesta
          var codigo = respuesta.Codigo;

          // Obtener el valor del select a partir del nombre del municipio
          // var valorDepartamento = obtenerValorDepartamento(departamento);

          $("#ciudadActual").val(municipio);
          $("#codigoCiudadActual").val(codigo);
        },
      });

      $("#ciudad2").select2({
        theme: "bootstrap dpto1",
        language: "es",
        width: "100%",
        // data: '<?php echo json_encode($ciudadesSelect2); ?>',
        ajax: {
          url: "ajax/ciudades.ajax.php", // URL del script PHP que devolverá las ciudades
          dataType: "json",
          delay: 250, // Retardo antes de realizar la búsqueda (milisegundos)
          data: function (params) {
            return {
              q: params.term, // Término de búsqueda ingresado por el usuario
            };
          },
          processResults: function (data) {
            return {
              results: data, // Resultados obtenidos del servidor
            };
          },
          cache: true, // Habilitar el almacenamiento en caché para reducir las solicitudes al servidor
        },
        // dropdownParent: $("#modalEditarUsuario"), // Establecer el contenedor del desplegable
        dropdownParent: $("#ciudad2").parent(),
        minimumInputLength: 3, // Número mínimo de caracteres para comenzar la búsqueda
        allowClear: true, // Mostrar botón para borrar la selección
        dropdownAutoWidth: true, // Ancho automático del desplegable
        placeholder: "Editar ciudad", // Texto del placeholder del buscador
      });
    },
  });
});


// Revisa y esta atento al cambio de analista para validar si es el mismo o es otro y asi mismo tomar la decision al guardar.

$("#analista").on("change", function () {
  if($(this).val() === initialValueAnalista){
    changeAnalist = false;
    notAssigned = false;
  } else {
    changeAnalist = true;
  }
});

/*===============================================
 ====== Crear Usuario Temporal O Invitado =======
 ===============================================*/

/*=============================================
EDITAR USUARIO SELECT2 Y CONFIGURACIONES
=============================================*/

// Carga los Documentos disponibles para agregar
$("#editarTipoDocumento").select2({
  theme: "bootstrap doc",
  language: "es",
  width: "100%",
  // placeholder: "Tipo Documento*", // Esto configura el placeholder
  dropdownParent: $("#editarTipoDocumento").parent(),
  // dropdownParent: $("#modalAgregarUsuario")
});

// Carga los Generos disponibles para agregar
$("#editarGenero").select2({
  theme: "bootstrap gen",
  language: "es",
  width: "100%",
  // placeholder: "Genero*", // Esto configura el placeholder
  dropdownParent: $("#editarGenero").parent(),
  // dropdownParent: $("#modalAgregarUsuario")
});

/*=============================================
ACTIVAR USUARIO
=============================================*/
$(".tablas").on("click", ".btnActivar", function () {
  var idUsuario = $(this).attr("idUsuario");
  var estadoUsuario = $(this).attr("estadoUsuario");
  let userIdOwner = permisos.id_usuario;
  var datos = new FormData();
  datos.append("activarId", idUsuario);
  datos.append("activarUsuario", estadoUsuario);
  datos.append("usuarioId", userIdOwner);

  // console.log(estadoUsuario, userIdOwner, idUsuario);

  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function (respuesta) {
      if (window.matchMedia("(max-width:767px)").matches) {
        swal({
          title: "El usuario ha sido actualizado",
          type: "success",
          confirmButtonText: "¡Cerrar!",
        }).then(function (result) {
          if (result.value) {
            window.location = "usuarios";
          }
        });
      }
    },
  });

  if (estadoUsuario == 0) {
    $(this).removeClass("btn-success");
    $(this).addClass("btn-danger");
    $(this).html("Bloqueado");
    $(this).attr("estadoUsuario", 1);
  } else {
    $(this).addClass("btn-success");
    $(this).removeClass("btn-danger");
    $(this).html("Activo");
    $(this).attr("estadoUsuario", 0);
  }
});

/*=============================================
ELIMINAR USUARIO
=============================================*/
$(".tablas").on("click", ".btnEliminarUsuario", function () {
  var idUsuario = $(this).attr("idUsuario");
  var fotoUsuario = $(this).attr("fotoUsuario");
  var usuario = $(this).attr("usuario");

  swal({
    title: "¿Está seguro de borrar el usuario?",
    text: "¡Si no lo está puede cancelar la accíón!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar usuario!",
  }).then(function (result) {
    if (result.value) {
      window.location =
        "index.php?ruta=usuarios&idUsuario=" +
        idUsuario +
        "&usuario=" +
        usuario +
        "&fotoUsuario=" +
        fotoUsuario;
    }
  });
});

/*=======================================================
REVISA SI EL N° IDENTIDAD DEL USUARIO YA ESTÁ REGISTRADO
=======================================================*/

$("#nuevoDocIdUser").change(function () {
  $(".alert").remove();

  var docIdUser = $(this).val();

  var datos = new FormData();
  datos.append("validarDocIdUser", docIdUser);

  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#alertUsuarioExist")
          .parent()
          .after(
            '<div class="alert alert-warning">El Numero de Identidad ya se encuentra registrado</div>'
          );
        $(".alert")
          .delay(6000)
          .fadeTo(500, 0)
          .slideUp(500, function () {
            $(this).remove();
          });
        $("#nuevoDocIdUser").val("");
      }
    },
  });
});

/*=======================================
REVISAR SI EL USUARIO YA ESTÁ REGISTRADO
=======================================*/

$("#nuevoUsuario").change(function () {
  $(".alert").remove();

  var usuario = $(this).val();

  var datos = new FormData();
  datos.append("validarUsuario", usuario);

  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#alertUsuarioExist")
          .parent()
          .after(
            '<div class="alert alert-warning">El Usuario ya se encuentra registrado</div>'
          );
        $(".alert")
          .delay(6000)
          .fadeTo(500, 0)
          .slideUp(500, function () {
            $(this).remove();
          });
        $("#nuevoUsuario").val("");
      }
    },
  });
});

/*=======================================
AGREGA EL N° IDENTIDAD COMO CONTRASEÑA
=======================================*/

$("#nuevoDocIdUser").keyup(function () {
  var docIdentidad = $("#nuevoDocIdUser").val();
  $("#nuevoPassword").val(docIdentidad);
});

/*==============================================
AGREGA EL N° IDENTIDAD COMO CONTRASEÑA EN EDITAR
==============================================*/

$("#editarDocIdUser").keyup(function () {
  var docIdentidad = $("#editarDocIdUser").val();
  $("#editarPassword").val(docIdentidad);
});

// VALIDACION PARA NUMERO DE CELULAR MODAL AGREGAR
document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("userForm")
    .addEventListener("submit", function (event) {
      // Aquí realizas la validación del número de celular
      var placaInput = document.getElementById("AgregMovil");
      var telefono = placaInput.value.trim(); // Eliminar espacios en blanco al principio y al final

      // Expresión regular para validar un número de celular con al menos 10 dígitos
      var formatoValido = /^(?:\(\d{3}\)\s*|\d{3}-?)\d{3}-?\d{4}$/.test(
        telefono
      );

      if (!formatoValido) {
        var mensajeError = document.getElementById("mensajeErrorCelular");
        mensajeError.style.display = "block";
        mensajeError.textContent =
          "Número de celular incompleto, verificar información";
        event.preventDefault(); // Evita que se envíe el formulario si la validación falla
      }
    });
});

// VALIDACION PARA NUMERO DE CELULAR MODAL EDITAR

document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("userEditForm")
    .addEventListener("submit", function (event) {
      // Aquí realizas la validación del número de celular
      var placaInput = document.getElementById("editarTelefono");
      var telefono = placaInput.value.trim(); // Eliminar espacios en blanco al principio y al final

      // Expresión regular para validar un número de celular con al menos 10 dígitos
      var formatoValido = /^(?:\(\d{3}\)\s*|\d{3}-?)\d{3}-?\d{4}$/.test(
        telefono
      );

      if (!formatoValido) {
        var mensajeError = document.getElementById("mensajeErrorCelularEdit");
        mensajeError.style.display = "block";
        mensajeError.textContent =
          "Número de celular incompleto, verificar información";
        event.preventDefault(); // Evita que se envíe el formulario si la validación falla
      }
    });
});
