let getParams = () => {
  let url = new URL(window.location.href);
  return Object.fromEntries(url.searchParams.entries());
};

$("#nombreAsesor, #clase, #canal, #moduloCotizacion, #analistaGA").select2({
  theme: "bootstrap selecting",
  allowClear: true,
  language: {
    emptyTable: "No se encontraron registros",
  },
  width: "100%",
  placeholder: "Seleccione una opción",
});

let url = `index.php?ruta=adminCoti`;

function reset() {
  window.location.href = "adminCoti";
}

function searchInfo() {
  let moduloCotizacion =
    $("#moduloCotizacion").val() !== ""
      ? $("#moduloCotizacion option:selected").text()
      : "";
  let clase =
    $("#clase").val() !== "" ? $("#clase option:selected").text() : "";
  let canal =
    $("#canal").val() !== "" ? $("#canal option:selected").text() : "";
  let analistaGA =
    $("#analistaGA").val() !== ""
      ? $("#analistaGA option:selected").text()
      : "";
  let nombreAsesor =
    $("#nombreAsesor").val() !== ""
      ? $("#nombreAsesor option:selected").text()
      : "";

  if (moduloCotizacion !== "") {
    url += `&moduloCotizacion=${moduloCotizacion}`;
  }

  if (clase !== "") {
    url += `&clase=${clase}`;
  }

  if (canal !== "") {
    url += `&canal=${canal}`;
  }

  if (analistaGA !== "") {
    url += `&analistaGA=${analistaGA}`;
  }

  if (nombreAsesor !== "") {
    url += `&nombreAsesor=${nombreAsesor}`;
  }

  window.location.href = url;
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

$(window).on("load", function () {
  let control = false;

  $("#masCots, #menosCots").click(function () {
    menosCotizaciones();
  });

  Promise.all([loadAnalistas(), loadFreelance()/*, loadClaseVehiculos()*/])
    .then(() => {
      aplicarCriterios(); // Llama a aplicarCriterios una vez que ambos AJAX han completado
    })
    .catch((error) => {
      console.error("Error al cargar datos:", error);
    })
    .finally(() => {
      // Oculta el loader al finalizar todas las cargas AJAX
      $("#loader-overlay").fadeOut();
      $(".tablas-cotizaciones tbody").fadeIn();
    });
});

function loadAnalistas() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "ajax/analistas.ajax.php",
      type: "POST",
      success: function (data) {
        let dat = JSON.parse(data);
        $("#analistaGA").append(dat.options).trigger("change");
        resolve(); // Resolviendo la promesa una vez que los datos se han añadido
      },
      error: function (error) {
        reject(error); // En caso de error, rechazar la promesa
      },
    });
  });
}

function loadFreelance() {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "ajax/freelances.ajax.php",
      type: "POST",
      success: function (data) {
        $("#nombreAsesor").append(data).trigger("change");
        resolve(); // Resolviendo la promesa una vez que los datos se han añadido
      },
      error: function (error) {
        reject(error); // En caso de error, rechazar la promesa
      },
    });
  });
}

// function loadClaseVehiculos() {
//   return new Promise((resolve, reject) => {
//     $.ajax({
//       url: "ajax/clasesVehiculo.ajax.php",
//       type: "POST",
//       success: function (data) {
//         let dat = JSON.parse(data);
//         $("#clase").append(dat.options).trigger("change");
//         resolve(); // Resolviendo la promesa una vez que los datos se han añadido
//       },
//       error: function (error) {
//         reject(error); // En caso de error, rechazar la promesa
//       },
//     });
//   });
// }

function aplicarCriterios() {
  const criterios = [
    "moduloCotizacion",
    "canal",
    "clase",
    "analistaGA",
    "nombreAsesor",
  ];

  let params = getParams();
  for (let [key, value] of Object.entries(params)) {
    if (criterios.includes(key)) {
      $(`#${key} option`).each(function () {
        if ($(this).text().trim() === value.trim()) {
          $(`#${key}`).val($(this).val()).trigger("change");
          return false; // Detener la iteración
        }
      });
    }
  }
}
