let getParams = (param) => {
    var urlPage = new URL(window.location.href); // Instancia la URL Actual
    var options = urlPage.searchParams.getAll(param); //Buscar todos los parametros
    return options;
};

var getIdCotiSoat = getParams("idCotizacionSoat")[0];

if (getParams("idCotizacionSoat").length > 0) {
    editarCotizacionSoat(getParams("idCotizacionSoat")[0]);
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

            cargarArchivosCotizacion(getParams("idCotizacionSoat")[0], response.estado);

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
                if (permisos.id_rol == 23) {
                    $("#section-final").show();
                } else {
                    $("#section-final").show();
                    $("#contenComentarios").hide();
                    $("#contenedor-archivos").show();
                }
            } else if (response.estado == "Soat Expedido") {
                $("#section-final").show();
                $("#contenComentarios").hide();
                $("#contenedor-archivos").show();
            } else if (response.estado == "Solicitud aprobada" && permisos.id_rol == 22) {
                $("#btnEstadoAprobar").prop("disabled", true);
                $("#btnEstadoDevolver").prop("disabled", true);
                $("#txtComentarios").prop("disabled", true);
                $("#container-subida-soat").show();
                $("#contenedor-subir-soat").show();
                $("#section-final").show();
                
                $("#contenedor-archivos").show();
                const miBloque = document.getElementById("contenedor-subir-archivos");
                const contenedorDestino = document.getElementById("contenedor-subir-soat");
                contenedorDestino.appendChild(miBloque);
                $("#btnUpload").prop("disabled", false);
                $("#contenedor-subir-archivos label").text("Subir SOAT");
            } else if (response.estado == "Solicitud rechazada" && permisos.id_rol == 22) {
                // $("#btnEstadoAprobar").prop("disabled", true);
                $("#btnUpload").prop("disabled", false);
                $("#section-final").show();
                $("#contenComentarios").hide();
                
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

async function cargarArchivosCotizacion(idCotizacion, estadoCotizacion) {
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
        let primerArchivo = true;
        archivos.forEach(file => {

    const nombreBase = file.nombre.split('-').slice(2).join('-');
    const nombreLimpio = primerArchivo
        ? `<b style="color: #7AC943">${nombreBase.toUpperCase()}</b>`
        : nombreBase;

    primerArchivo = false;

    // Botón eliminar solo si está Solicitud rechazada
    const botonEliminar = estadoCotizacion === 'Solicitud rechazada'
        ? `<button class="btn btn-sm btn-danger"
                onclick="eliminarArchivo('${file.nombre}', ${idCotizacion})"
                title="Eliminar archivo"
                style="margin-left:5px; background-color: white;">
                ❌
           </button>`
        : '';

    contenedor.innerHTML += `
        <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
            <span>${nombreLimpio}</span>
            <div>
                <a href="http://${file.url}" target="_blank" class="btn btn-sm btn-primary">
                    Descargar
                </a>
                ${botonEliminar}
            </div>
        </li>
        <hr>
    `;
});
        contenedor.innerHTML += '</ul>';

    } catch (error) {
        console.error("Error al obtener archivos:", error);
        contenedor.innerHTML = "Error al cargar la lista.";
    }
}

async function eliminarArchivo(nombreArchivo, idCotizacion) {
    if (!confirm('¿Seguro que deseas eliminar este archivo?')) return;

    try {
        const response = await fetch('vistas/modulos/soat/eliminarArchivo.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                archivo: nombreArchivo,
                id: idCotizacion
            })
        });

        const result = await response.json();

        if (result.success) {
            alert('Archivo eliminado correctamente');
            cargarArchivosCotizacion(idCotizacion, 'Solicitud rechazada');
        } else {
            alert('Error al eliminar archivo');
        }

    } catch (error) {
        console.error(error);
        alert('Error al eliminar archivo');
    }
}


$("#btnEstadoAprobar").click(function () {
  $.ajax({
    type: "POST",
    url: "src/soat/saveQuotationSoat.php",
    data: {
      Accion: "Actualizar-datos-soat",
      IdCotizacionSoat: getIdCotiSoat,
      Estado: "Solicitud aprobada",
      Correo: $("#correoTomadorSoat").val(),
      Celular: $("#celularTomadorSoat").val(),
    },
    success: function (data) {
      console.log("Datos actualizados correctamente", data);
      idCotizacionSoat = data.lastId;
    },
    error: function (error) {
      console.log("Error al actualizar cotizacion SOAT: ", error);
    }
  });
  $("#btnEstadoAprobar").prop("disabled", true);
  $("#btnEstadoDevolver").prop("disabled", true);
  $("#txtComentarios").prop("disabled", true);
  // $("#contenedor-subir-archivos").remove();
  $("#container-subida-soat").show();
  $("#contenedor-subir-soat").show();

  const miBloque = document.getElementById("contenedor-subir-archivos");
  const contenedorDestino = document.getElementById("contenedor-subir-soat");
  contenedorDestino.appendChild(miBloque);
  $("#btnUpload").prop("disabled", false);
  $("#contenedor-subir-archivos label").text("Subir SOAT");
});

$("#btnSubirSoat").click(function () {
  if (getParams("idCotizacionSoat").length > 0) {
    idCotizacionSoat = getParams("idCotizacionSoat")[0];
  }

  $("#btnSubirSoat").prop("disabled", true);

  const campos = ["#correoTomadorSoat", "#celularTomadorSoat"];
  let errores = 0;

  campos.forEach(id => {
    const el = $(id);
    if (el.val() === "") {
      el.css("border", "1px solid red");
      errores++;
    } else {
      el.css("border", ""); // Limpia el borde si el usuario ya lo corrigió
    }
  });

  if (errores > 0 || files.length === 0) {
    Swal.fire({
      icon: "error",
      title: "Faltan datos por completar",
      text: "Por favor adjunta el SOAT expedido",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
    });
    $("#btnSubirSoat").prop("disabled", false);
    return;
  }

  // Enviar Archivos Adjuntos
  enviarArchivos();

  // Peticion para actualizar datos la cotización (formato Form Data)
  $.ajax({
    type: "POST",
    url: "src/soat/saveQuotationSoat.php",
    data: {
      Accion: "Actualizar-datos-soat",
      IdCotizacionSoat: idCotizacionSoat,
      Estado: "Soat Expedido",
      Correo: $("#correoTomadorSoat").val(),
      Celular: $("#celularTomadorSoat").val(),
    },
    success: function (data) {
      console.log("Datos actualizados correctamente", data);
      idCotizacionSoat = data.lastId;
    },
    error: function (error) {
      console.log("Error al actualizar cotizacion SOAT: ", error);
    }
  });

  $.ajax({
    type: "POST",
    url: "https://grupoasistencia.com/WS-laravel-email-shetts/api/emails/enviar-correo-soat",
    // url: "http://localhost/WS-laravel/api/emails/enviar-correo",
    dataType: "text",
    data: {
      idCotizacion: idCotizacionSoat,
      placa: $("#placaVeh").val(),
      clase: $("#txtClaseVeh").val(),
      referencia: $("#txtMarcaVeh").val() + " " + $("#txtLinea").val(),
      prima: $("#valorSoat").text().replace(/\./g, "").replace("$ ", ""),
      totalPagar: $("#totalPagarSoat").text().replace(/\./g, "").replace("$ ", ""),
      correoTomador: $("#correoTomadorSoat").val(),
      celularTomador: $("#celularTomadorSoat").val(),
      opcionPago: $("#radioConComision").is(":checked") ? "Con comision" : "Sin comision",
    },
    cache: false,
    success: function (data) {
      console.log("Correo Enviado");
      swal
        .fire({
          icon: "success",
          title: "Solicitud de cotización #" + idCotizacionSoat + " enviada exitosamente",
          showConfirmButton: true,
          confirmButtonText: "Ok",
          allowOutsideClick: false,
          allowEscapeKey: false,
        })
        .then((result) => {
          if (result.isConfirmed) {
            window.location.href = "soat";
          }
        });
    },
    error: function (xhr, status, error) {
      console.log(error);
      console.log("Error");
    },
  });
});
