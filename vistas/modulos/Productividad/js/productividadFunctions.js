let control = false; 

  function loadAnalistasPro() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "ajax/analistas.ajax.php",
        type: "POST",
        success: function (data) {
          let dat = JSON.parse(data);
  
          $("#analistaGAPro").append('<option value=""></option>' + dat.options);
          resolve(); 
        },
        error: function (error) {
          reject(error);
        },
      });
    });
  }

  function loadAllFrelances() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "ajax/allFreelances.ajax.php",
        type: "POST",
        success: function (data) {
          let dat = JSON.parse(data);
  
          $("#nombreAsesorPro").append('<option value=""></option>' + dat.options);
          resolve(); 
        },
        error: function (error) {
          reject(error);
        },
      });
    });
  }

 function initTable(){
    $(".tabla-productividad").DataTable({
        scrollX: true, 
        responsive: false,
        dom: '<"top"Blf>rt<"bottom"ip>',
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
            sSortDescending: ": Activar para ordenar la columna de manera descendente",
          },
        },
      });
 }

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

function searchInfo() {
  const anio = $("#anioExpedicionPro").val();
  const mes = $("#mesExpedicionPro").val();
  const asesor = $("#nombreAsesorPro").val();
  const analista = $("#analistaGAPro").val();
  const ramo = $("#ramoPro").val();
  const estado = $("#estadoPro").val();

  // Mostrar el loader
  $("#loader").show();

  $.ajax({
    url: "vistas/modulos/Productividad/services/consulta_productividad.php",
    type: "POST",
    data: {
      anio,
      mes,
      asesor,
      analista,
      ramo,
      estado
    },
    success: function (response) {
      try {
        const datos = JSON.parse(response);

        if (Array.isArray(datos.asesores) && datos.asesores.length > 0) {
          renderTable(datos);

          const cantidad = datos.asesores.length;
          const texto = `Se ${cantidad === 1 ? 'encontró' : 'encontraron'} ${cantidad} ${cantidad === 1 ? 'registro' : 'registros'}.`;

          Swal.fire({
            icon: 'success',
            title: 'Consulta exitosa',
            text: texto,
            timer: 3000,
            showConfirmButton: false
          });
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'Sin resultados',
            text: 'No se encontraron asesores con los filtros seleccionados.',
            timer: 3000,
            showConfirmButton: false
          });
        }
      } catch (err) {
        console.error("Error al procesar los datos:", err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al procesar la respuesta del servidor.',
        });
      } finally {
        $("#loader").hide();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);

      Swal.fire({
        icon: 'error',
        title: 'Error de red',
        text: 'No se pudo obtener la información. Intenta nuevamente.',
      });

      $("#loader").hide();
    }
  });
}

function calcularEfectividad(cotizaciones, negocios) {
  if (cotizaciones === 0) {
    return 0; // Si las cotizaciones son 0, la efectividad es 0
  }
  return (negocios / cotizaciones) * 100; // Calculamos la efectividad
}

function renderTable(data) {
  if ($.fn.DataTable.isDataTable(".tabla-productividad")) {
    $(".tabla-productividad").DataTable().clear().destroy();
  }

  $("#contenedorTablaProductividad").empty();
  $("#resumenTablaMeses").empty();

  const fechasBusqueda = data.fechasBusqueda;
  const mesesOrdenados = Object.keys(fechasBusqueda).sort((a, b) => a > b ? -1 : 1);

  const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

  const mesesNombres = mesesOrdenados.map(m => {
    const fecha = new Date(fechasBusqueda[m].inicio);
    return nombresMeses[fecha.getMonth()].toUpperCase();
  });

  // Tabla principal
  const tableHTML = `
    <table class="table table-bordered tabla-productividad" style="width: 100%; text-align: center;">
      <thead>
        <tr>
          <th rowspan="2">Acción</th>
          <th rowspan="2" class="col-nombre">Asesor</th>
          <th rowspan="2">Fecha de ingreso</th>
          <th rowspan="2">Estado Usuario</th>
          <th rowspan="2">Analista</th>
          <th rowspan="2">Estado freelance</th>
          <th rowspan="2">Categoría</th> 
          ${mesesNombres.map((mes, i) => `<th colspan="4" id="tituloMes${i + 1}">${mes}</th>`).join('')}
          <th colspan="4">TOTALES</th>
        </tr>
        <tr>
          ${mesesNombres.map((_, i) => `
            <th class="mes-${i + 1}">Cant. cotizaciones</th>
            <th class="mes-${i + 1}">Negocios</th>
            <th class="mes-${i + 1}">Primas negocios</th>
            <th class="mes-${i + 1}">% efectividad</th>
          `).join('')}
          <th>Cant. cotizaciones</th>
          <th>Negocios</th>
          <th>Primas negocios</th>
          <th>% efectividad</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  `;

  $("#contenedorTablaProductividad").html(tableHTML);
  const tbody = $(".tabla-productividad tbody");

  // Variables para totales por mes
  const resumen = {
    mes1: { cotizaciones: 0, negocios: 0, primas: 0 },
    mes2: { cotizaciones: 0, negocios: 0, primas: 0 },
    mes3: { cotizaciones: 0, negocios: 0, primas: 0 }
  };

  let totalCotizaciones = 0;
  let totalNegocios = 0;
  let totalPrimas = 0;

  data.asesores.forEach(item => {
    const mesesOrdenadosAsesor = Object.keys(item.meses).sort((a, b) => a > b ? -1 : 1);

    let cotizacionesAsesor = 0;
    let negociosAsesor = 0;
    let primasAsesor = 0;

    let fila = `
      <tr>
        <td>
          <div class="btn-group">
            <button 
              class="btn btn-primary btnSeguimientoAsesor"
              onclick="abrirModalSeguimiento(this)"
              data-id="${item.asesor_id}"
              data-nombre="${item.asesor}"
              data-fecha="${item.fecha_ingreso}"
              data-estado="${item.estado_usuario}"
              data-analista="${item.analista}"
              data-categoria="${item.categoria_freelance || ''}"
              data-freelance="${item.estado_freelance || ''}"
            >
              <i class="fa-sharp fa-solid fa-pen"></i>
            </button>
          </div>
        </td>
        <td class="col-nombre">${item.asesor}</td>
        <td>${item.fecha_ingreso}</td>
        <td>${item.estado_usuario}</td>
        <td>${item.analista}</td>
        <td>${item.estado_freelance || ''}</td>
        <td>${item.categoria_freelance || ''}</td> 
    `;

    mesesOrdenadosAsesor.forEach((mes, index) => {
      const cotizaciones = item.meses[mes]?.cotizaciones || 0;
      const negocios = item.meses[mes]?.negocios || 0;
      const primas = item.meses[mes]?.prima_emitida  || 0;

      const efectividad = calcularEfectividad(cotizaciones, negocios);

      cotizacionesAsesor += cotizaciones;
      negociosAsesor += negocios;
      primasAsesor += primas;

      resumen[`mes${index + 1}`].cotizaciones += cotizaciones;
      resumen[`mes${index + 1}`].negocios += negocios;
      resumen[`mes${index + 1}`].primas += primas;

      fila += `
        <td class="mes-${index + 1}">${cotizaciones}</td>
        <td class="mes-${index + 1}">${negocios}</td>
        <td class="mes-${index + 1}">$${primas.toLocaleString()}</td>
        <td class="mes-${index + 1}"><strong>${efectividad.toFixed(2)}%</strong></td>
      `;
    });

    fila += `
      <td>${cotizacionesAsesor}</td>
      <td>${negociosAsesor}</td>
      <td>$${primasAsesor.toLocaleString()}</td>
      <td><strong>${calcularEfectividad(cotizacionesAsesor, negociosAsesor).toFixed(2)}%</strong></td>
      </tr>
    `;

    tbody.append(fila);

    totalCotizaciones += cotizacionesAsesor;
    totalNegocios += negociosAsesor;
    totalPrimas += primasAsesor;
  });

  // Tabla resumen mensual
  const totalResumen = {
    cotizaciones: 0,
    negocios: 0,
    primas: 0,
    efectividad: 0
  };

  let resumenHTML = `
    <table class="table table-bordered tabla-resumen-meses" style="width: auto; margin-top: 20px;">
      <thead>
        <tr>
          <th>Mes</th>
          <th>Cant. cotizaciones</th>
          <th>Negocios</th>
          <th>Primas negocios</th>
          <th>% efectividad</th>
        </tr>
      </thead>
      <tbody>
  `;

  mesesOrdenados.forEach((mesKey, i) => {
    const nombreMes = nombresMeses[new Date(fechasBusqueda[mesKey].inicio).getMonth()];
    const res = resumen[`mes${i + 1}`];
    const efectividad = calcularEfectividad(res.cotizaciones, res.negocios);

    totalResumen.cotizaciones += res.cotizaciones;
    totalResumen.negocios += res.negocios;
    totalResumen.primas += res.primas;

    resumenHTML += `
      <tr>
        <td>${nombreMes}</td>
        <td>${res.cotizaciones}</td>
        <td>${res.negocios}</td>
        <td>$${res.primas.toLocaleString()}</td>
        <td>${efectividad.toFixed(2)}%</td>
      </tr>
    `;
  });

  resumenHTML += `
    <tr style="background-color: #f0f0f0;">
      <td><strong>Total</strong></td>
      <td>${totalResumen.cotizaciones}</td>
      <td>${totalResumen.negocios}</td>
      <td>$${totalResumen.primas.toLocaleString()}</td>
      <td>${calcularEfectividad(totalResumen.cotizaciones, totalResumen.negocios).toFixed(2)}%</td>
    </tr>
    <tr style="background-color: #f0f0f0;">
      <td><strong>Promedio</strong></td>
      <td>${Math.round(totalResumen.cotizaciones / 3)}</td>
      <td>${Math.round(totalResumen.negocios / 3)}</td>
      <td>$${Math.round(totalResumen.primas / 3).toLocaleString()}</td>
      <td>${(calcularEfectividad(totalResumen.cotizaciones, totalResumen.negocios)).toFixed(2)}%</td>
    </tr>
  `;

  resumenHTML += `</tbody></table>`;

  $("#resumenTablaMeses").html(resumenHTML);

  initTable(); // Inicializa DataTable
}

let usuarioActualId = 1234; // ⚠️ reemplaza con el ID real del usuario logueado

function abrirModalSeguimiento(btn) {
  const $btn = $(btn);
  const idAsesor = $btn.data("id");

  // Guardar ID asesor
  $("#idAsesorSeguimiento").val(idAsesor);

  // Llenar datos
  $("#nombreAsesor").val($btn.data("nombre"));
  $("#fechaIngreso").val($btn.data("fecha"));
  $("#estadoUsuario").val($btn.data("estado"));
  $("#analista").val($btn.data("analista"));
  $("#categoria").val($btn.data("categoria"));
  $("#estadoFreelance").val($btn.data("freelance"));
  $("#comentariosAsesor").val("");
  $("#historialComentarios").html("");

  // Cargar comentarios
  $.get("ruta/listar_comentarios.php", { id_asesor: idAsesor }, function (data) {
    const comentarios = JSON.parse(data);
    if (comentarios.length === 0) {
      $("#historialComentarios").html("<p>No hay comentarios aún.</p>");
    } else {
      renderizarComentarios(comentarios);
    }
  });

  $("#modalSeguimiento").dialog("open");
}

function renderizarComentarios(lista) {
  const contenedor = $("#historialComentarios").html("");
  lista.forEach(c => {
    const html = `
      <div style="border: 1px solid #ccc; padding: 8px; margin-bottom: 5px; border-radius: 5px;">
        <small><strong>${c.autor}</strong> - ${c.fecha_creacion}</small>
        <p>${c.comentario}</p>
      </div>
    `;
    contenedor.append(html);
  });
}

$(document).ready(function () {
  $("#modalSeguimiento").dialog({
    autoOpen: false,
    title: "Seguimiento del asesor",
    modal: true,
    resizable: false,
    draggable: true,
    width: 850,
    dialogClass: "custom-dialog2",

    position: { my: "center", at: "center", of: window }, // 👈 Esto centra el modal

    buttons: {
      Cerrar: function () {
        $(this).dialog("close");
      },
    },

    create: function () {
      $(".ui-dialog-titlebar-close").html('<p id="closeButtonModal">x</p>');
    },

    open: function () {
      $("body").css("overflow", "hidden");
      $(".ui-dialog-buttonpane button:contains('Cerrar')").attr("id", "btnCerrar");

      // Reforzamos centrado en caso de que no se respete
      $(this).dialog("option", "position", { my: "center", at: "center", of: window });
    },

    close: function () {
      $("body").css("overflow", "auto");
    },
  });

  // Función para renderizar comentarios
  function renderizarComentarios(lista) {
    const contenedor = $("#historialComentarios").html("");
    lista.forEach(c => {
      const html = `
        <div style="border: 1px solid #ccc; padding: 8px; margin-bottom: 5px; border-radius: 5px;">
          <small><strong>${c.autor}</strong> - ${c.fecha_creacion}</small>
          <p>${c.comentario}</p>
        </div>
      `;
      contenedor.append(html);
    });
  }

  // Función para cargar comentarios
  function cargarComentarios(idAsesor) {
    if (!idAsesor) return;

    $.ajax({
      url: "vistas/modulos/Productividad/services/listar_comentarios.php",
      type: "GET",
      data: { id_asesor: idAsesor },
      success: function (data) {
        try {
          const comentarios = JSON.parse(data);
          renderizarComentarios(comentarios);
        } catch (error) {
          console.error("Error al parsear los comentarios:", error);
          Swal.fire("Error al procesar los comentarios.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al obtener comentarios:", status, error);
        Swal.fire("No se pudieron obtener los comentarios.");
      }
    });
  }

  // Evento para botón "Agregar comentario"
  $("#btnGuardarSeguimiento").on("click", function () {
    debugger;
    const comentario = $("#comentariosAsesor").val().trim();
    const idAsesor = $("#idAsesorSeguimiento").val();
    const idAutor = $("#idUsuarioActual").val();

    if (!comentario || !idAsesor || !idAutor) {
      Swal.fire("Faltan datos. Asegúrate de haber iniciado sesión y escrito un comentario.");
      return;
    }

    $.ajax({
      url: "vistas/modulos/Productividad/services/guardar_comentario.php",
      method: "POST",
      data: {
        id_asesor: idAsesor,
        id_autor: idAutor,
        comentario: comentario
      },
      success: function (response) {
        try {
          const res = JSON.parse(response);
          if (res.success) {
            $("#comentariosAsesor").val(""); // limpiar textarea
            cargarComentarios(idAsesor);     // recargar historial
          } else {
            Swal.fire("Error al guardar el comentario");
          }
        } catch (err) {
          console.error("Error al parsear respuesta del servidor:", err, response);
          Swal.fire("Respuesta inválida del servidor");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
        Swal.fire("Error en la red");
      }
    });
  });

  // Exponer funciones para usarlas fuera del ready
  window.cargarComentarios = cargarComentarios;
});

function abrirModalSeguimiento(btn) {
  const $btn = $(btn);

  $("#nombreAsesor").val($btn.data("nombre"));
  $("#fechaIngreso").val($btn.data("fecha"));
  $("#estadoUsuario").val($btn.data("estado"));
  $("#analista").val($btn.data("analista"));
  $("#categoria").val($btn.data("categoria"));
  $("#estadoFreelance").val($btn.data("freelance"));

  const idAsesor = $btn.data("id");
  $("#idAsesorSeguimiento").val(idAsesor);
  $("#comentariosAsesor").val("");

  cargarComentarios(idAsesor); // Llamar a la función global

  $("#modalSeguimiento").dialog("open");
}

$(
  "#anioExpedicionPro, #mesExpedicionPro, #nombreAsesorPro, #analistaGAPro, #ramoPro, #estadoPro"
).select2({
  theme: "bootstrap selecting",
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Seleccione una opción",
  allowClear: true
});


$(document).ready(function () {
  $("#masCots, #menosCots").click(function () {
    menosCotizaciones();
  });
    loadAnalistasPro();
    loadAllFrelances();
    initTable();
});