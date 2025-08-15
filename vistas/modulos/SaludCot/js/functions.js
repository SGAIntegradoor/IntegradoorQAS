var idCotiNew;
var totalOfertas = 0;
var premiumOfertas = 0;
var basicasOfertas = 0;
var countFiltrado = 0;

function usarID() {
  if (idCotiNew) {
    console.log("Usando el ID:", idCotiNew);
  } else {
    console.log("ID aún no está definido");
  }
}

var datosFiltro = new FormData();
$(".filter").on("click", function () {
  countFiltrado++;
  $("#divPadreFiltros .activeTab").removeClass("activeTab");
  $("#divPadreFiltros").css("pointer-events", "none");
  paramFiltro = $(this).attr("name");
  $(this).addClass("filter activeTab");
  idCotiUrl = getParams("idCotizacionSalud")[0] ? getParams("idCotizacionSalud")[0] : idCotiNew;
  datosFiltro.append("idCotizacionSalud", idCotiUrl);
  datosFiltro.append("filtro", paramFiltro);

  $("#row_contenedor_general_salud2").html("");
  $("#row_contenedor_general_salud").html("");
  $("#loaderFilters").show();
  $("#loaderFilters2").show();

  $.ajax({
    url: "ajax/cotizaciones.ajax.php",
    method: "POST",
    data: datosFiltro,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (data) {
      $("#loaderFilters").hide();
      $("#loaderFilters2").hide();
      makeCards(data, 2);
    },
    error: function (xhr, status, error) {
      errores = errores + 1;
      console.log("Error status:", status);
      console.log("Error:", error);
      console.log("Response:", xhr.responseText);
    },
  }).always(function () {
      setTimeout(() => {
        $("#divPadreFiltros").css("pointer-events", "auto");
      }, 2000);
    });
});

