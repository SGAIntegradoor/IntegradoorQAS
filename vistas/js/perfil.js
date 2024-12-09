$(document).ready(function () {
  //cargartipDoc();
  cargarPerfil().then(() => console.log("finalizando")).finally(() => console.log("finalizo"))



  // const labelSelectLogoPDF = $("#labelPDF");
  // var hasProperty = $('#labelPDF').prop('disabled') ? true : false;

  // if(!hasProperty){
  //   $("#imgLogo").prop("disabled", true);
  // }
  
});

/* Start Variables Globales */
let documento = permisos.usu_documento;
let idUsuario = permisos.id_usuario;
//let conUploads = 0;
/* End Variables Globales */

let btnGuardar = document.getElementById("btnGuardar");
const imgUserInput = document.getElementById("imgUser");
const imgLogoInput = document.getElementById("imgLogo");

btnGuardar.addEventListener("click", function () {
  // Subir imagen de perfil del usuario
  if (imgUserInput.files.length > 0) {
    uploadImageToServer(imgUserInput.files[0], "imgUser");
  } else {
    console.log("No se seleccionó una imagen de perfil.");
  }



  // Subir logo del PDF
  if (imgLogoInput.files.length > 0) {
    uploadImageToServer(imgLogoInput.files[0], "imgLogo");
  } else {
    console.log("No se seleccionó un logo para el PDF.");
  }
});

$("#imgUser").on("change", function () {
  const file = this.files[0]; // Obtiene el archivo seleccionado
  if (file) {
    $("#fileNameUser").text(file.name); // Muestra el nombre del archivo

    // Usamos FileReader para leer la imagen seleccionada
    const reader = new FileReader();
    reader.onload = function (e) {
      $("#previewImg").attr("src", e.target.result); // Asigna la imagen cargada como `src` al elemento img
    };
    reader.readAsDataURL(file); // Convierte el archivo en una URL en base64
  } else {
    $("#fileNameUser").text("No se ha seleccionado ningún archivo");
    $("#previewImg").attr("src", "<?php echo $_SESSION['foto']; ?>"); // Restaura la imagen original
  }
});

$("#imgLogo").on("change", function () {
  const file = this.files[0]; // Obtiene el archivo seleccionado
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = new Image();
      img.onload = function () {
        const width = img.width;
        const height = img.height;

        // Validar las dimensiones de la imagen
        // if (width > 1080 || !height > 428) {
        //   Swal.fire({
        //     icon: "warning",
        //     title: "Advertencia",
        //     text: "La imagen esperada tiene que ser de máximo 1080 pixeles de ancho por 428 pixeles de alto",
        //     showConfirmButton: true,
        //     confirmButtonText: "Aceptar",
        //   }).then((result) => {
        //     if (result.isConfirmed) {
        //       console.log("Imagen rechazada por dimensiones");
        //       // Reiniciar el input para que no se envíe
        //       $("#imgLogo").val(""); // Limpia el input
        //       $("#fileNamePDF").text("No se ha seleccionado ningún archivo");
        //       $("#previewImgPDF").attr("src", defaultPhoto); // Restaura la imagen original
        //     }
        //   });
        // } else {
          // Si las dimensiones son válidas, mostramos el nombre y la imagen
          $("#fileNamePDF").text(file.name);
          $("#previewImgPDF").attr("src", e.target.result);
        // }
      };
      img.src = e.target.result; // Asigna la imagen cargada como `src`
    };
    reader.readAsDataURL(file); // Convierte el archivo en una URL en base64
  } else {
    $("#fileNamePDF").text("No se ha seleccionado ningún archivo");
    $("#previewImgPDF").attr("src", defaultPhoto); // Restaura la imagen original
  }
});

// $("#imgLogo").on("change", function () {
//   const file = this.files[0]; // Obtiene el archivo seleccionado
//   if (file) {
//     $("#fileNamePDF").text(file.name); // Muestra el nombre del archivo

//     // Usamos FileReader para leer la imagen seleccionada
//     const reader = new FileReader();
//     reader.onload = function (e) {
//       $("#previewImgPDF").attr("src", e.target.result); // Asigna la imagen cargada como `src` al elemento img
//     };
//     reader.readAsDataURL(file); // Convierte el archivo en una URL en base64
//   } else {
//     $("#fileNamePDF").text("No se ha seleccionado ningún archivo");
//     $("#previewImgPDF").attr("src", "<?php echo $_SESSION['imgPDF']; ?>"); // Restaura la imagen original
//   }
// });

function uploadImageToServer(file, inputId) {
  const formData = new FormData();
  formData.append("file", file);
  formData.append("inputId", inputId);
  formData.append("documento", documento);
  formData.append("idUsuario", idUsuario);

  // Llamada AJAX para enviar el archivo al servidor
  $.ajax({
    url: "src/upload.php",
    method: "POST",
    data: formData, // Agrega el nombre del campo "ciudad"
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      // console.log(respuesta);
      if (respuesta.hasOwnProperty("success")) {
        Swal.fire({
          icon: "success",
          title: "Modificacion correcta",
          text: respuesta["success"],
          showConfirmButton: true,
          confirmButtonText: "Aceptar",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.reload();
          } else if (result.isDismissed) {
            //window.location.reload();
          }
        });
      } else if (respuesta.hasOwnProperty("error")) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: respuesta["error"],
          showConfirmButton: true,
          confirmButtonText: "Aceptar",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.reload();
          } else if (result.isDismissed) {
            //window.location.reload();
          }
        });
      } else if (respuesta.hasOwnProperty("warning")) {
        Swal.fire({
          icon: "warning",
          title: "Advertencia",
          text: respuesta["warning"],
          showConfirmButton: true,
          confirmButtonText: "Aceptar",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.reload();
          } else if (result.isDismissed) {
            //window.location.reload();
          }
        });
      }
    },
  });
}

const editarPerfil = () => {
  if (permisos.Modificarlogodepdfdecotizaciondelaagencia !== "x") {
    $("#nombre_perfil").prop("disabled", false);
    $("#tipoDocumento").prop("disabled", false);
    $("#apellido_perfil").prop("disabled", false);
    $("#documento_perfil").prop("disabled", false);
    $("#telefono_perfil").prop("disabled", false);
    $("#direccion_perfil").prop("disabled", false);
    $("#email_perfil").prop("disabled", false);
    $("#ciudad_perfil").prop("disabled", false);
  } else {
    Swal.fire({
      icon: "error",
      title: "No tienes el permiso para editar tu perfil.",
    });
  }
};

const tipos_doc = [
  { id: "1", tipo: "Cedula de ciudadania" },
  { id: "2", tipo: "Numero de identificación tributaria" },
  { id: "3", tipo: "Cédula de extranjería" },
  { id: "4", tipo: "Tarjeta de identidad" },
  { id: "5", tipo: "Pasaporte" },
  { id: "6", tipo: "Carné diplomático" },
  { id: "7", tipo: "Sociedad extranjera sin NIT en Colombia" },
  { id: "8", tipo: "Fideicomiso" },
  { id: "9", tipo: "Registro civil de nacimiento" },
];

const tipoDocTip = () => {
  let id = "1";
  tipos_doc.map((element) => {
    if (element.tipo == permisos.tipos_documento_id) {
      id = element.id;
    }
  });
  return id;
};

function cargartipDoc() {
  const tip_doc_update = document.getElementById("tipodocumento_perfil");
  //console.log(permisos.tipo_documento)
  $.ajax({
    url: "ajax/tipdoc.php",
    method: "POST",
    success: function (respuesta) {
      tip_doc_update.innerHTML = respuesta;
      tip_doc_update.value = tipoDocTip();
    },
  });
}

async function cargarPerfil() {
  var idUsuario = permisos.id_usuario;
  var datos = new FormData();
  datos.append("idUsuario", idUsuario);
  // $("#boxesInfoPerfil").hide();
  $("#loader-skeleton").fadeIn();
  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#nombres_perfil").val(respuesta["usu_nombre"]);
      $("#apellidos_perfil").val(respuesta["usu_apellido"]);
      cargartipDoc();
      $("#documento_perfil").val(respuesta["usu_documento"]);
      $("#direccion_perfil").val(respuesta["direccion"]);
      $("#telefono_perfil").val(respuesta["usu_telefono"]);
      $("#fechaNacimiento_perfil").val(respuesta["usu_fch_nac"]);
      $("#email_perfil").val(respuesta["usu_email"]);
      $("#genero_perfil").val(
        respuesta["usu_genero"] == "F" ? "Femenino" : "Masculino"
      );

      $("#nombre_perfil").prop("disabled", true);
      $("#tipoDocumento").prop("disabled", true);
      $("#apellido_perfil").prop("disabled", true);
      $("#documento_perfil").prop("disabled", true);
      $("#telefono_perfil").prop("disabled", true);
      $("#direccion_perfil").prop("disabled", true);
      $("#email_perfil").prop("disabled", true);
      $("#ciudad_perfil").prop("disabled", true);
      $("#editarRol").val(respuesta["id_rol"]);

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
      if (respuesta["usu_foto"] != "" && respuesta["usu_foto"] != null) {
        $(".previsualizarEditar").attr("src", respuesta["usu_foto"]);
        $(".previsualizarEditarPDF").attr("src", respuesta["usu_logo_pdf"]);
        $(".user-image").attr("src", respuesta["usu_foto"]);
      } else {
        $(".previsualizarEditar").attr(
          "src",
          "vistas/img/views/user.png"
        );
      }


      // Crear una instancia de FormData
      var formData = new FormData();

      // Obtener el código de ciudad
      var codigoCiudad = respuesta["ciudades_id"];
      formData.append("ciudad", codigoCiudad);

      $.ajax({
        url: "ajax/ciudades.ajax.php",
        method: "POST",
        data: formData, // Agrega el nombre del campo "ciudad"
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
          var municipio = respuesta.Nombre; // Nombre del departamento obtenido desde la respuesta
          var codigo = respuesta.Codigo;

          const departamento = municipio.split("-");

          $("#departamento_perfil").val(departamento[1]);
          $("#ciudad_perfil").val(departamento[0]);

          $("#loader-skeleton").fadeOut();
        },
      });
    }, complete: function (){
      // $("#loading").remove();
     
    },
  });
}
