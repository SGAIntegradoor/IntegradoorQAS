let getParams = (param) => {
    var urlPage = new URL(window.location.href); // Instancia la URL Actual
    var options = urlPage.searchParams.getAll(param); //Buscar todos los parametros
    return options;
};

var getIdCotiSoat = getParams("idCotizacionSoat")[0];

if (getParams("idCotizacionSoat").length > 0) {
    editarCotizacionSoat(getParams("idCotizacionSoat")[0]);
    cargarArchivosCotizacion(getParams("idCotizacionSoat")[0]);
}

$("document").ready(function () {
    var idCotizacionSoat = $("#idCotizacionSoat").val();

    $("#lblDataTrip2Top").css("display", "none");
    $(".box").css("border-top", "0px");
    document.getElementById("headerAsegurado").style.display = "block";
    document.getElementById("contenSuperiorPlaca").style.display = "none";
    document.getElementById("resumenVehiculo").style.display = "block";
    document.getElementById("contenBtnCotizar").style.display = "block";
    menosAseg();
    masAseg();
    document.getElementById("contenBtnConsultarPlaca").style.display = "none";
    $("#contenSuperiorPlaca").css("display", "block");
    $("#txtConocesLaPlacaSi").prop("disabled", true);
    $("#txtConocesLaPlacaNo").prop("disabled", true);
    $("#placaVeh").prop("disabled", true);
    $("btnConsultarPlaca2").remove();
    $("#btnContinuarCoti").remove();
    $("#btnNuevaCoti").remove();
    $("#btnEnviarSolicitud").remove();
    $(".containerResumenCoti").show();
    $(".containerFinalForm").show();
    $("#correoTomadorSoat").prop("disabled", true);
    $("#celularTomadorSoat").prop("disabled", true);
    $("#radioConComision").prop("disabled", true);
    $("#radioSinComision").prop("disabled", true);
    $("#btnUpload").prop("disabled", true);
    $("#contenedor-archivos").show();

});

function editarCotizacionSoat(idCotizacionSoat) {

    var datos = new FormData();
    datos.append("idCotizacionSoat", idCotizacionSoat);

    $.ajax({
        url: "ajax/cotizaciones.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {

            const totalSoat = Number(response.total_soat);
            const valorComision = Number(response.valor_comision);

            $("#valorSoat").html("$ " + totalSoat.toLocaleString("es-CO"));
            $("#totalPagarSoat").html("$ " + (totalSoat + valorComision).toLocaleString("es-CO"));
            $("#placaVeh").val(response.placa);
            $("#txtClaseVeh").val(response.clase);
            $("#txtMarcaVeh").val(response.marca);
            $("#txtModeloVeh").val(response.modelo);
            $("#txtLinea").val(response.linea);
            $("#txtServicio").val(response.servicio);
            $("#txtCilindraje").val(response.cilindraje);
            $("#txtPasajeros").val(response.pasajeros);
            $("#txtMotor").val(response.motor);
            $("#txtChasis").val(response.chasis);
            $("#correoTomadorSoat").val(response.correo);
            $("#celularTomadorSoat").val(response.celular);
            if (response.opcion == "Sin comision") {
                $("#radioSinComision").prop("checked", true);

            } else {
                $("#radioConComision").prop("checked", true);
            }

        },

        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX:");
            console.error("Estado:", textStatus);
            console.error("Error:", errorThrown);
            console.error("Respuesta del servidor:", jqXHR.responseText);
        }
    });
};

async function cargarArchivosCotizacion(idCotizacion) {
    const contenedor = document.getElementById("contenedor-archivos");
    contenedor.innerHTML = "Cargando archivos...";

    try {
        const response = await fetch('vistas/modulos/soat/getArchivos.php?id=' + idCotizacion);
        const archivos = await response.json();

        if (archivos.length === 0) {
            contenedor.innerHTML = "<p>No hay archivos para esta cotización.</p>";
            return;
        }

        // Limpiamos y generamos la lista
        contenedor.innerHTML = '<ul class="list-group">';
        archivos.forEach(file => {
            const nombreLimpio = file.nombre.split('-').slice(2).join('-');

            contenedor.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center" style="display:flex; justify-content: space-between; align-items: center;">
                    ${nombreLimpio}
                    <a href="http://${file.url}" download="${nombreLimpio}" class="btn btn-sm btn-primary" style="margin-left: 15px">
                        Descargar
                    </a>
                </li>`;
        });
        contenedor.innerHTML += '</ul>';

    } catch (error) {
        console.error("Error al obtener archivos:", error);
        contenedor.innerHTML = "Error al cargar la lista.";
    }
}