/*=============================================
CARGA LA FECHA DE NACIMIENTO
=============================================*/
$("#nuevoDiaNacCliente, #nuevoMesNacCliente, #nuevoAnioNacCliente").select2({
  // theme: "bootstrap",
  language: "es",
  width: "100%",
});

/*=============================================
CARGA LA FECHA DE NACIMIENTO EN EDITAR
=============================================*/
$("#editarDiaNacCliente, #editarMesNacCliente, #editarAnioNacCliente").select2({
  // theme: "bootstrap",
  language: "es",
  width: "100%",
});

/*=============================================
ACTIVAR CLIENTE
=============================================*/

$(".tablas").on("click", ".btnActivarCliente", function () {
  var idCliente = $(this).attr("idCliente");
  var estadoCliente = $(this).attr("estadoCliente");

  var datos = new FormData();
  datos.append("activarId", idCliente);
  datos.append("activarCliente", estadoCliente);

  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function (respuesta) {
      if (window.matchMedia("(max-width:767px)").matches) {
        swal({
          title: "El cliente ha sido actualizado",
          type: "success",
          confirmButtonText: "¡Cerrar!",
        }).then(function (result) {
          if (result.value) {
            window.location = "clientes";
          }
        });
      }
    },
  });

  if (estadoCliente == 0) {
    $(this).removeClass("btn-success");
    $(this).addClass("btn-danger");
    $(this).html("Inactivo");
    $(this).attr("estadoCliente", 1);
  } else {
    $(this).addClass("btn-success");
    $(this).removeClass("btn-danger");
    $(this).html("Activo");
    $(this).attr("estadoCliente", 0);
  }
});


/*=============================================
EDITAR CLIENTE
=============================================*/

$(".tablas").on("click", ".btnEditarCliente", function () {
  var idCliente = $(this).attr("idCliente");
  console.log(idCliente);
  var datos = new FormData();
  datos.append("idCliente", idCliente);
  
  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#idCliente").val(respuesta["id_cliente"]);
      $("#codCliente").val(respuesta["cli_codigo"]);
      $("#editarTipoDocIdCliente").val(respuesta["id_tipo_documento"]);
      $("#editarNumDocIdCliente").val(respuesta["cli_num_documento"]);
      $("#editarNombreCliente").val(respuesta["cli_nombre"]);
      $("#editarApellidoCliente").val(respuesta["cli_apellidos"]);

      var fecha = respuesta["cli_fch_nacimiento"].split("-");
      var nombreMes = obtenerNombreMes(fecha[1]);
      $("#editarDiaNacCliente").append(
        "<option value='" + fecha[2] + "' selected>" + fecha[2] + "</option>"
      );
      $("#editarMesNacCliente").append(
        "<option value='" +
          fecha[1] +
          "' selected>" +
          nombreMes[0].toUpperCase() +
          nombreMes.slice(1) +
          "</option>"
      );
      $("#editarAnioNacCliente").append(
        "<option value='" + fecha[0] + "' selected>" + fecha[0] + "</option>"
      );

      $("#editarGeneroCliente").val(respuesta["cli_genero"]);
      $("#editarEstCivilCliente").val(respuesta["id_estado_civil"]);
      $("#editarTelefonoCliente").val(respuesta["cli_telefono"]);
      $("#editarEmailCliente").val(respuesta["cli_email"]);
      $("#idEstado").val(respuesta["cli_estado"]);
    },
  });
});

/*=============================================
BUSCAR CLIENTE
=============================================*/

/*=============================================
ELIMINAR CLIENTE
=============================================*/

$(".tablas").on("click", ".btnEliminarCliente", function () {
  var idCliente = $(this).attr("idCliente");

  swal({
    title: "¿Está seguro de borrar el cliente?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar cliente!",
  }).then(function (result) {
    if (result.value) {
      window.location = "index.php?ruta=clientes&idCliente=" + idCliente;
    }
  });
});

/*=============================================
REVISAR SI EL CLIENTE YA ESTÁ REGISTRADO
=============================================*/

$("#nuevoNumDocIdCliente").change(function () {
  $(".alert").remove();

  var documentoId = $(this).val();
  var id_Inter = $("#id_inter").val();

  var datos = new FormData();
  datos.append("validarDocumentoId", documentoId);
  datos.append("intermediario", id_Inter);

  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#alertNumDocIdCliente")
          .parent()
          .after(
            '<div class="alert alert-warning text-center" role="alert">! El numero de documento ingresado ya Existe !</div>'
          );
        $(".alert")
          .delay(6000)
          .fadeTo(500, 0)
          .slideUp(500, function () {
            $(this).remove();
          });
        $("#nuevoNumDocIdCliente").val("");
      }
    },
  });
});

/*=============================================
CARGA LOS CLIENTES
=============================================*/
$('#miTabla').DataTable({
  processing: true,
  serverSide: true,
  ajax: {
      url: "controladores/clientes.controlador.php",
      type: "GET"
  },
  pageLength: 10,
  columns: [
      { data: "Numero" },
      { data: "Tipo" },
      { data: "Documento" },
      { data: "Nombre" },
      { data: "F_Nacimiento" },
      { data: "Gen" },
      { data: "Estado_Civil" },
      { data: "Telefono" },
      { data: "Email" }
  ],
  layout: {
    topStart: "buttons",
    topCenter: {
      search: {
        placeholder: "Buscar...",
      },
    },
    topEnd: {
      pageLength: {
        menu: [10, 25, 100],
      },
    },
    bottomEnd: {
      paging: {
        numbers: 3,
      },
    },
  },
  buttons: [
    {
      extend: "excelHtml5",
      className: "btn-excel",
      text: '<img src="vistas/img/excelIco.png" />', // Agrega un texto descriptivo
      titleAttr: "Exportar a Excel", // Agrega un tooltip
    },
  ],
  responsive: true,
  order: [
    [0, "asc"],
    [1, "asc"],
  ],
  language: {
    sProcessing: "Procesando...",
    sLengthMenu: "Mostrar _MENU_ registros",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    sInfoPostFix: "",
    sSearch: "Buscar:",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending:
        ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  },
});



/*=============================================
CARGA LOS TIPOS DE DOCUMENTO DE IDENTIDAD
=============================================*/

// $('#nuevoTipoDocIdCliente').click(function () {

// 	var tipoDocId = document.getElementById("nuevoTipoDocIdCliente").value;

// 	var datos = new FormData();
//     datos.append("tipoDocId", tipoDocId);

//     $.ajax({

//       url:"ajax/clientes.ajax.php",
//       method: "POST",
//       data: datos,
//       cache: false,
//       contentType: false,
//       processData: false,
//       dataType:"json",
//       success:function(respuesta){

//       	   $("#nuevoTipoDocIdCliente").val(respuesta["nuevoTipoDocIdCliente"]);
// 	  }

//   	});

// });


