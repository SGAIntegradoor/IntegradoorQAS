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

  // Mostrar el loader
  $("#loader").show();

  $.ajax({
    url: "./vistas/modulos/Productividad/services/consulta_productividad.php",
    type: "POST",
    data: {
      anio,
      mes,
      asesor,
      analista,
      ramo
    },
    success: function (response) {
      const datos = JSON.parse(response);
      renderTable(datos);

      // Ocultar el loader
      $("#loader").hide();
    },
    error: function (error) {
      console.error("Error al consultar los datos:", error);

      // Ocultar el loader en caso de error
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

  const tbody = $(".tabla-productividad tbody");
  tbody.empty();

  // Mostrar las fechas de búsqueda para cada mes
  const fechasBusqueda = data.fechasBusqueda;

  // Extraemos y ordenamos las fechas para pasarlas a la función actualizarTitulosMeses
  const mesesOrdenados = Object.keys(fechasBusqueda).sort((a, b) => {
    return a > b ? -1 : 1; // Orden de más antiguo (mes3) a más reciente (mes1)
  });

  // Creamos un objeto para los meses en formato Date
  const meses = {
    mes1: new Date(fechasBusqueda[mesesOrdenados[0]].inicio), // Mes más reciente (mes1)
    mes2: new Date(fechasBusqueda[mesesOrdenados[1]].inicio), // Mes pasado (mes2)
    mes3: new Date(fechasBusqueda[mesesOrdenados[2]].inicio)  // Mes más antiguo (mes3)
  };

  // Llamamos a la función para actualizar los títulos de los meses
  actualizarTitulosMeses(meses);

  // Variables para los totales acumulados
  let totalCotizaciones = 0;
  let totalNegocios = 0;

  // Ahora procesamos los asesores
  data.asesores.forEach(item => {
    // Ordenamos los meses en orden cronológico (de mes3 a mes1)
    const mesesOrdenados = Object.keys(item.meses).sort((a, b) => {
      return a > b ? -1 : 1; // Orden de más antiguo (mes3) a más reciente (mes1)
    });

    let cotizacionesAsesor = 0;
    let negociosAsesor = 0;

    // Creamos una fila para cada asesor
    const row = `
      <tr>
        <td>${item.asesor}</td>
        <td>${item.fecha_ingreso}</td>
        <td>${item.estado_usuario}</td>
        <td>${item.analista}</td>

        <!-- Mostrar cotizaciones, negocios y efectividad por mes -->
        ${mesesOrdenados.map(mes => {
          const cotizaciones = item.meses[mes].cotizaciones !== undefined ? item.meses[mes].cotizaciones : 0;
          const negocios = item.meses[mes].negocios !== undefined ? item.meses[mes].negocios : 0;
          const efectividad = calcularEfectividad(cotizaciones, negocios);

          // Acumulamos los valores para los totales
          cotizacionesAsesor += cotizaciones;
          negociosAsesor += negocios;

          return `
            <td>${cotizaciones}</td>
            <td>${negocios}</td>
            <td><strong>${efectividad.toFixed(2)}%</strong></td>
          `;
        }).join('')}

        <!-- Mostrar los totales por asesor (después de los 3 meses) -->
        <td>${cotizacionesAsesor}</td>
        <td>${negociosAsesor}</td>
        <td><strong>${calcularEfectividad(cotizacionesAsesor, negociosAsesor).toFixed(2)}%</strong></td>
      </tr>
    `;

    tbody.append(row);

    // Acumulamos los totales generales
    totalCotizaciones += cotizacionesAsesor;
    totalNegocios += negociosAsesor;
  });


  // Inicializamos la tabla
  initTable();

  // Totales por cada mes (en orden: mes1, mes2, mes3)
let totalesPorMes = {
  mes1: { cotizaciones: 0, negocios: 0 },
  mes2: { cotizaciones: 0, negocios: 0 },
  mes3: { cotizaciones: 0, negocios: 0 }
};

// Acumulamos por cada asesor
data.asesores.forEach(item => {
  Object.keys(item.meses).forEach(mes => {
    totalesPorMes[mes].cotizaciones += item.meses[mes].cotizaciones || 0;
    totalesPorMes[mes].negocios += item.meses[mes].negocios || 0;
  });
});

// Mostrar en el DOM
$("#cotMes1").text(totalesPorMes.mes1.cotizaciones);
$("#negMes1").text(totalesPorMes.mes1.negocios);

$("#cotMes2").text(totalesPorMes.mes2.cotizaciones);
$("#negMes2").text(totalesPorMes.mes2.negocios);

$("#cotMes3").text(totalesPorMes.mes3.cotizaciones);
$("#negMes3").text(totalesPorMes.mes3.negocios);

// Calcular efectividad promedio
let efectividad1 = calcularEfectividad(totalesPorMes.mes1.cotizaciones, totalesPorMes.mes1.negocios);
let efectividad2 = calcularEfectividad(totalesPorMes.mes2.cotizaciones, totalesPorMes.mes2.negocios);
let efectividad3 = calcularEfectividad(totalesPorMes.mes3.cotizaciones, totalesPorMes.mes3.negocios);

let promedio = (efectividad1 + efectividad2 + efectividad3) / 3;
$("#promEfectividad").text(promedio.toFixed(2) + "%");

}

function actualizarTitulosMeses(meses) {
  // Si no se pasan los meses como parámetro, usamos la fecha actual
  if (!meses) {
      const fechaActual = new Date();
      const mesActual = fechaActual.getMonth(); // mes actual (0-11)
      
      // Calculamos los meses según la fecha actual
      meses = {
          mes1: new Date(fechaActual.setMonth(mesActual - 2)), // Dos meses atrás
          mes2: new Date(fechaActual.setMonth(mesActual - 1)), // Un mes atrás
          mes3: new Date(fechaActual.setMonth(mesActual))      // Mes actual
      };
  }

  // Obtenemos los nombres de los meses
  const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
  
  // Actualizamos los títulos de los meses en la tabla usando los ids
  const mes3Nombre = nombresMeses[meses.mes3.getMonth()]; // Mes actual
  const mes2Nombre = nombresMeses[meses.mes2.getMonth()]; // Mes pasado
  const mes1Nombre = nombresMeses[meses.mes1.getMonth()]; // Mes hace 2 meses

  // Actualizamos el encabezado de la tabla
  document.getElementById("tituloMes3").textContent = mes3Nombre.toUpperCase();
  document.getElementById("tituloMes2").textContent = mes2Nombre.toUpperCase();
  document.getElementById("tituloMes1").textContent = mes1Nombre.toUpperCase();
  
  // También actualizamos los subtítulos de las columnas de los meses
 
}


$(
  "#anioExpedicionPro, #mesExpedicionPro, #nombreAsesorPro, #analistaGAPro, #ramoPro"
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
    actualizarTitulosMeses();
    initTable();
});