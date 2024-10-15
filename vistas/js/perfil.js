$(document).ready(function () {
  //cargartipDoc();
  cargarPerfil();
});

const editarPerfil = () => {
  if (permisos.Modificarlogodepdfdecotizaciondelaagencia !== "x") {
    $("#nombre_perfil").prop("disabled", false);
    $("#tip_doc").prop("disabled", false);
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
    tipos_doc.map((element) =>{
        if(element.tipo == permisos.tipos_documento_id){
            id = element.id;
        }
    })
    return id;
}

function cargartipDoc() {
  const tip_doc_update = document.getElementById("tip_doc");
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

function cargarPerfil() {
  var idUsuario = permisos.id_usuario;
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
      //console.log(respuesta);
      $("#nombre_perfil").val(respuesta["usu_nombre"]);
      $("#apellido_perfil").val(respuesta["usu_apellido"]);
      cargartipDoc();
      $("#documento_perfil").val(respuesta["usu_documento"]);
      $("#direccion_perfil").val(respuesta["direccion"]);
      $("#telefono_perfil").val(respuesta["usu_telefono"]);
      $("#email_perfil").val(respuesta["usu_email"]);

      $("#nombre_perfil").prop("disabled", true);
      $("#tip_doc").prop("disabled", true);
      $("#apellido_perfil").prop("disabled", true);
      $("#documento_perfil").prop("disabled", true);
      $("#telefono_perfil").prop("disabled", true);
      $("#direccion_perfil").prop("disabled", true);
      $("#email_perfil").prop("disabled", true);
      $("#ciudad_perfil").prop("disabled", true);

      //$("#fotoActual").val(respuesta["usu_foto"]);
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
      if (respuesta["usu_foto"] != "") {
        $(".previsualizarEditar").attr("src", respuesta["usu_foto"]);
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
          $("#ciudad_perfil").val(municipio);
          $("#codigoCiudadActual").val(codigo);
        },
      });

      // $("#ciudad2").select2({
      //   theme: "bootstrap dpto1",
      //   language: "es",
      //   width: "100%",
      //   // data: '<?php echo json_encode($ciudadesSelect2); ?>',
      //   ajax: {
      //     url: "ajax/ciudades.ajax.php", // URL del script PHP que devolverá las ciudades
      //     dataType: "json",
      //     delay: 250, // Retardo antes de realizar la búsqueda (milisegundos)
      //     data: function (params) {
      //       return {
      //         q: params.term, // Término de búsqueda ingresado por el usuario
      //       };
      //     },
      //     processResults: function (data) {
      //       return {
      //         results: data, // Resultados obtenidos del servidor
      //       };
      //     },
      //     cache: true, // Habilitar el almacenamiento en caché para reducir las solicitudes al servidor
      //   },
      //   minimumInputLength: 3, // Número mínimo de caracteres para comenzar la búsqueda
      //   allowClear: true, // Mostrar botón para borrar la selección
      //   dropdownAutoWidth: true, // Ancho automático del desplegable
      //   placeholder: "Editar ciudad", // Texto del placeholder del buscador
      // });
    },
  });
}



