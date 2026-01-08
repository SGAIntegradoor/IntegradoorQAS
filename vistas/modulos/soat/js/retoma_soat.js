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
    // document.getElementById("contenBtnCotizar").style.display = "block";
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
    $("#btnEnviarSolicitud").hide();
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

            $("#title-resumen-coti").html("RESUMEN COTIZACIÓN SOAT PLACA " + response.placa);
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

            $("#fechaCoti").text(response.fecha_creacion.split(' ')[0]);
            $("#PrimaSoat").text("$ " + Number(response.valor_prima).toLocaleString("es-CO"));
            $("#contriFosyga").text("$ " + Number(response.valor_contribucion).toLocaleString("es-CO"));
            $("#tasaRunt").text("$ " + Number(response.valor_runt).toLocaleString("es-CO"));
            $("#valorSoat").text("$ " + Number(response.total_soat).toLocaleString("es-CO"));
            $("#servicioTramite").text("$ " + Number(response.valor_comision).toLocaleString("es-CO"));

            if (response.opcion == "Sin comision") {
                $("#radioSinComision").prop("checked", true);

            } else {
                $("#radioConComision").prop("checked", true);
            }

            if (response.estado == "Soat Cotizado") {
                $("#btnEnviarSolicitud").show();
                $("#btnUpload").prop("disabled", false);
                $("#celularTomadorSoat").prop("disabled", false);
                $("#correoTomadorSoat").prop("disabled", false);
            } else if (response.estado == "Solicitud enviada") {
                if (permisos.id_rol == 22) {
                    $("#section-final").show();
                } else {
                    $("#section-final").show();
                    $("#contenComentarios").hide();
                    $("#contenedor-archivos").show();
                }
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
                </li> <hr>`;
        });
        contenedor.innerHTML += '</ul>';

    } catch (error) {
        console.error("Error al obtener archivos:", error);
        contenedor.innerHTML = "Error al cargar la lista.";
    }
}

$("#btnEstadoAprobar").click(function () {
    
    $("#btnEstadoAprobar").prop("disabled", true);
    $("#btnEstadoDevolver").prop("disabled", true);
    $("#txtComentarios").prop("disabled", true);
    $("#contenedor-subir-archivos").remove();
    $("#contenedor-subir-soat").show();
});

const MAX_FILESSoat = 3;
const MAX_SIZESoat = 1 * 1024 * 1024;

const btnSoat = document.getElementById("btnUploadSoat");
const inputSoat = document.getElementById("fileInputSoat");
const previewSoat = document.getElementById("filePreviewSoat");

var files = [];

btnSoat.onclick = () => {
  if (files.length < MAX_FILESSoat) {
    inputSoat.click();
  }
};

inputSoat.onchange = () => {
  const selectedSoat = Array.from(input.files);

  for (const fileSoat of selectedSoat) {

    if (files.length >= MAX_FILESSoat) {
      alert("Máximo 3 archivos.");
      break;
    }

    if (fileSoat.size > MAX_SIZESoat) {
      alert(`"${file.name}" supera 1 MB`);
      continue;
    }

    const existsSoat = files.some(
      f => f.name === fileSoat.name && f.size === fileSoat.size
    );

    if (!existsSoat) {
      files.push(fileSoat);
    }
  }

  render();
  inputSoat.value = "";
};

function render() {
  previewSoat.innerHTML = "";

  files.forEach((file, index) => {
    const divSoat = document.createElement("div");
    divSoat.className = "file-item";

    divSoat.innerHTML = `
            <span>${idCotizacionSoat}-${file.name}</span>
            <span class="remove-btn" onclick="removeFile(${index})">✕</span>
        `;

    previewSoat.appendChild(divSoat);
  });

  // bloquear cuando llegue al límite
  btnSoat.disabled = files.length >= MAX_FILESSoat;
}

function removeFile(index) {
  files.splice(index, 1);
  render();
}

function enviarArchivos() {
  // Verificamos que existan archivos para evitar peticiones vacías
  if (!files || files.length === 0) {
    console.warn("No hay archivos para subir");
    return;
  }

  console.log("Iniciando subida para cotización:", idCotizacionSoat);
  const formDataSoat = new FormData();

  // Agregamos el ID de la cotización
  formDataSoat.append("cotizacion", idCotizacionSoat);

  // Agregamos los archivos
  files.forEach((file, index) => {
    const nuevoNombreSoat = `${idCotizacionSoat}-${index}-${file.name}`;
    // Importante: 'archivos[]' permite que PHP lo reciba como un array
    formDataSoat.append("archivos[]", file, nuevoNombreSoat);
  });

  // --- DEBUG: Ver el contenido real antes de enviar ---
  console.log("Contenido del FormData:");
  for (let [key, value] of formDataSoat.entries()) {
    console.log(`${key}:`, value);
  }

  fetch("vistas/modulos/soat/uploadSoat.php", {
    method: "POST",
    body: formDataSoat // El navegador añade automáticamente el Header multipart/form-data
  })
    .then(res => {
      if (!res.ok) throw new Error("Error en la respuesta del servidor");
      return res.json();
    })
    .then(data => {
      if (data.ok) {
        console.log("Archivos subidos con éxito", data);
      } else {
        console.error("Error del servidor:", data.error);
      }
    })
    .catch(err => {
      console.error("Error en la petición fetch:", err);
      alert("Ocurrió un error al conectar con el servidor");
    });
}