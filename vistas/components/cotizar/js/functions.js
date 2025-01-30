let counters = {
  RCE: 0,
  Full: 0,
  Premium: 0,
  Basicas: 0,
  Clasicas: 0,
  Todas: 0,
};

function getOffertsByFilter(filter, callback) {
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
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
    },
  });
}

// Manejar la carga inicial de las ofertas al cargar el documento
// document.addEventListener("DOMContentLoaded", function () {
//   getOffertsByFilter("Todas", function (response) {
//     // Asegúrate de que la respuesta es válida
//     try {
//       let offers = response;

//       offers.forEach((offer) => {
//         // Verifica que la categoría también sea un JSON válido
//         let categoria = JSON.parse(offer.Categoria);

//         // Incrementa los contadores según la categoría
//         categoria.forEach((cat) => {
//           if (cat === "RCE") {
//             counters.RCE++;
//           } else if (cat === "Full") {
//             counters.Full++;
//           } else if (cat === "Premium") {
//             counters.Premium++;
//           } else if (cat === "Basicas") {
//             counters.Basicas++;
//           } else if (cat === "Clasicas") {
//             counters.Clasicas++;
//           }
//           counters.Todas++;
//         });
//       });
//       Object.entries(counters).forEach(([key, value]) => {
//         if (key === "Todas") {
//           $("#" + key).html(value - 2);
//         } else {
//           $("#" + key).html(value);
//         }
//       });
//     } catch (e) {
//       console.error("Error al procesar la respuesta:", e);
//     }
//   });
// });

// Manejar los filtros cuando se haga clic en ellos

let filters = document.querySelectorAll(".filter");

filters.forEach((filter) => {
  filter.addEventListener("click", function () {
    filters.forEach((filter) => filter.classList.remove("activeTab"));
    this.classList.add("activeTab");
    getOffertsByFilter(filter.getAttribute("name"), function (response) {
      // Actualiza la vista con las ofertas filtradas, si es necesario.
      $("#cardCotizacion").html("");
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

      offers.forEach((offer) => {
        // Verifica que la categoría también sea un JSON válido
        let categoria = JSON.parse(offer.Categoria);

        // Incrementa los contadores según la categoría
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
          counters.Todas++;
        });
      });
      Object.entries(counters).forEach(([key, value]) => {
        if (key === "Todas") {
          $("#" + key).html(value - 2);
        } else {
          $("#" + key).html(value);
        }
      });
    } catch (e) {
      console.error("Error al procesar la respuesta:", e);
    }
  });
}
