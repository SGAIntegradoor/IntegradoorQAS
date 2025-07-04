let counters = {
  RCE: 0,
  Full: 0,
  Premium: 0,
  Basicas: 0,
  Clasicas: 0,
  Todas: 0,
};

function disableFilters() {
  $(".container-filters").css({
      "pointer-events": "none",  // Evita la interacción
      "opacity": "0.5"           // Reduce la visibilidad para indicar que está deshabilitado
  });
}

function enableFilters() {
  $(".container-filters").css({
      "pointer-events": "auto",
      "opacity": "1"
  });
}

function getOffertsByFilter(filter, callback) {
  // $("#cardCotizacion")
  //   .find("#divCards")
  //   .html(
  //     '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Cargando...</strong>'
  //   );
  let data = {
    idOfertaFilter: filter,
    idOfertaCotizacion: idCotizacion,
  };
  $.ajax({
    url: "ajax/cotizaciones.ajax.php",
    type: "POST",
    data: data,
    success: function (response) {
      // Llama al callback con la respuesta
      callback(JSON.parse(response));
      $("#cardCotizacion").find("#divCards").html("");
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
    },
  });
}

// Manejar los filtros cuando se haga clic en ellos

let filters = document.querySelectorAll(".filter");

filters.forEach((filter) => {
  filter.addEventListener("click", function () {
    filters.forEach((filter) => filter.classList.remove("activeTab"));
    this.classList.add("activeTab");
    getOffertsByFilter(filter.getAttribute("name"), function (response) {
      // Actualiza la vista con las ofertas filtradas, si es necesario.
      // return;
      // $("#cardCotizacion").html("");
      renderCards(response);
    });
  });
});

let typeCotizacion = false;

// Obtener y validar el parámetro 'idCotizacion' de la URL
let queryParams = new URLSearchParams(window.location.search);

if (queryParams.has("idCotizacion")) {
  let paramValue = queryParams.get("idCotizacion");
  if (paramValue !== "" && !isNaN(Number(paramValue))) {
    typeCotizacion = true;
    
    // Manejar la carga inicial de las ofertas al cargar el documento
    document.addEventListener("DOMContentLoaded", function () {
      countOfferts();
    });
  }
} else {
}

function countOfferts() {
  getOffertsByFilter("Todas", function (response) {
    // Asegúrate de que la respuesta es válida
    try {
      let offers = response;
      let todas = offers.length;
      let categoria = [];
      counters = {
        RCE: 0,
        Full: 0,
        Premium: 0,
        Basicas: 0,
        Clasicas: 0,
        Todas: todas,
      };

      offers.forEach((offer) => {
        
        try {
          // Verifica que la categoría también sea un JSON 
          if( offer.Categoria == null || offer.Categoria == "" || offer.Categoria == "null" || offer.Categoria == "undefined") {
            $("#filtersSection").css("display", "none");
            return; 
          } else {
            categoria = JSON.parse(offer.Categoria);
            categoria.forEach((cat) => {
              if (cat === "RCE") {
                counters.RCE++;
              } else if (cat === "Full") {
                counters.Full++;
              } else if (cat === "Premium") {
                counters.Premium++;
              } else if (cat === "Basicas") {
                counters.Basicas++;
              } else if (cat === "Clasicas") {
                counters.Clasicas++;
              }
            });
          }
          // Incrementa los contadores según la categoría
        } catch (e) {
          console.error("Error al procesar la categoría:", e, e.stack);
        }
      });
      if(categoria.length == 0) {
        return;
      } else {
        Object.entries(counters).forEach(([key, value]) => {
          if (key == "Todas") {
            //console.log("entre aqui, existo!!!!");
            $("#" + key).html(todas);
          } else {
            $("#" + key).html(value);
          }
        });
        if ($("#filtersSection").css("display") == "none" ) {
          $("#filtersSection").css("display", "block");
        } 
      }
    } catch (e) {
      console.error("Error al procesar la respuesta:", e, e.stack);
      console.trace();
      alert("Error general: " + e.message + "\nStack: " + e.stack);
    }
  });
}
