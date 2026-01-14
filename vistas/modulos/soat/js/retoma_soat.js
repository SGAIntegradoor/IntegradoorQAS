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

  // Cargar comentarios
  $.get("vistas/modulos/soat/listarComentariosSoat.php", { id_asesor: permisos.id_usuario, id_general: getIdCotiSoat}, function (data) {
    const comentarios = JSON.parse(data);
    if (comentarios.length === 0) {
      $("#historialComentarios").html("<p>No hay comentarios aún.</p>");
    } else {
      renderizarComentarios(comentarios);
    }
  });

  $("#lblDataTrip2Top").css("display", "none");
  $("#contenedor-historial-comentarios").show();
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
  $("#contenedor-archivos").css({ display: "flex"});

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

      $("#title-resumen-coti").html("RESUMEN COTIZACIÓN SOAT " + response.placa);
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
        if (permisos.id_rol != 19) {
          $("#section-final").show();
          $("#contenedor-archivos").css({ display: "flex"});
        } else {
          $("#section-final").show();
          $("#contenComentarios").hide();
          $("#contenedor-archivos").css({ display: "flex"});
        }
      } else if (response.estado == "Soat Expedido") {
        $("#section-final").show();
        $("#contenComentarios").hide();
        $("#contenedor-archivos").css({ display: "flex"});
      } else if (response.estado == "Solicitud aprobada") {
        if (permisos.id_rol != 19) { // configurar condicion para los encargados de soat de sga
          $("#btnEstadoAprobar").prop("disabled", true);
          $("#btnEstadoDevolver").prop("disabled", true);
          $("#txtComentarios").prop("disabled", true);
          $("#container-subida-soat").show();
          $("#contenedor-subir-soat").show();
          $("#section-final").show();

          $("#contenedor-archivos").css({ display: "flex"});
          const miBloque = document.getElementById("contenedor-subir-archivos");
          const miBloquePreview = document.getElementById("contenedor-subir-archivos-preview");
          const contenedorDestino = document.getElementById("contenedor-subir-soat");
          const contenedorDestinoPreview = document.getElementById("destinoPreview");
          contenedorDestino.appendChild(miBloque);
          contenedorDestinoPreview.appendChild(miBloquePreview);
          $("#contenedor-subir-archivos").removeClass().addClass("col-md-2");
          $("#btnUpload").prop("disabled", false);
          $("#contenedor-subir-archivos label").text("Subir SOAT");
        } else {
          $("#section-final").show();
          $("#contenComentarios").hide();
          $("#contenedor-archivos").css({ display: "flex"});
        }
      } else if (response.estado == "Solicitud rechazada") {
        if (permisos.id_rol != 19) {
          $("#section-final").show();
          $("#contenComentarios").hide();
          $("#contenedor-archivos").css({ display: "flex"});
        } else {
          $("#btnUpload").prop("disabled", false);
          $("#section-final").show();
          $("#contenComentarios").hide();
          $("#btnEnviarSolicitud").show();
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

async function cargarArchivosCotizacion(idCotizacion, estadoCotizacion) {
  const contenedor = document.getElementById("contenedor-archivos");
  contenedor.innerHTML = "Cargando archivos...";

  try {
    const response = await fetch(
      "vistas/modulos/soat/getArchivos.php?id=" + idCotizacion
    );
    const archivos = await response.json();
    // console.log(archivos);

    if (archivos.length === 0) {
      contenedor.innerHTML = "<p>No hay archivos para esta cotización.</p>";
      return;
    }

    // Limpiamos y generamos la lista
    contenedor.innerHTML = '<ul class="list-group">';
    let primerArchivo = true;
    archivos.forEach((file) => {
      const nombreBase = file.nombre.split("-").slice(2).join("-");
      const nombreLimpio =
        primerArchivo && estadoCotizacion == "Soat Expedido"
          ? `<b style="color:#7AC943">${nombreBase.toUpperCase()}</b>`
          : nombreBase;

      primerArchivo = false;

      const botonEliminar =
        estadoCotizacion === "Solicitud rechazada" && permisos.id_rol == 19
          ? `<button class="btn btn-sm btn-danger"
                   onclick="eliminarArchivo('${file.nombre}', ${idCotizacion})"
                   title="Eliminar archivo"
                   style="margin-left:5px; background-color:white;">❌</button>`
          : "";

      const preview = generarPreview(file.url, file.nombre);

      contenedor.innerHTML += `
        <li class="list-group-item">
            <div style="display:flex; flex-direction: column; gap:15px; align-items:center;">
                
                ${preview}

                <div style="flex:1">
                    <div>${nombreLimpio}</div>
                    <!-- <div style="margin-top:5px;">
                        <a href="http://${file.url}" target="_blank" class="btn btn-sm btn-primary">
                            Abrir
                        </a>
                        <a href="http://${file.url}" download class="btn btn-sm btn-success">
                            Descargar
                        </a>
                        ${botonEliminar}
                    </div> -->
                    ${botonEliminar}
                </div>
            </div>
        </li>
        <hr>
    `;
    });

    contenedor.innerHTML += "</ul>";
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

  let inpComentarios = $("#txtComentarios").val();
  if (inpComentarios != null || inpComentarios != "") {
    // Guardar comentario
    $.ajax({
      type: "POST",
      url: "src/addComment.php",
      dataType: "text",
      data: {
        id_general: getIdCotiSoat,
        modulo: "Soat",
        comentario: inpComentarios,
        idUsuario: permisos.id_usuario,
        nombre_usuario_comentario: permisos.nombre,

      },
      cache: false,
      success: function (data) {
        console.log("comentario agregado: " + data);
        $.get("vistas/modulos/soat/listarComentariosSoat.php", { id_asesor: permisos.id_usuario, id_general: getIdCotiSoat}, function (data) {
        const comentarios = JSON.parse(data);
        if (comentarios.length === 0) {
          $("#historialComentarios").html("<p>No hay comentarios aún.</p>");
        } else {
          renderizarComentarios(comentarios);
        }
  });
      },
      error: function (xhr, status, error) {
        console.log(error);
        console.log("Error");
      },
    });
  }

  $("#btnEstadoAprobar").prop("disabled", true);
  $("#btnEstadoDevolver").prop("disabled", true);
  $("#txtComentarios").prop("disabled", true);
  // $("#contenedor-subir-archivos").remove();
  $("#container-subida-soat").show();
  $("#contenedor-subir-soat").show();

  $("#contenedor-subir-archivos").removeClass().addClass("col-md-2");

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
  datos = {
      Accion: "Actualizar-datos-soat",
      IdCotizacionSoat: getIdCotiSoat,
      Estado: "Soat Expedido",
      Correo: $("#correoTomadorSoat").val(),
      Celular: $("#celularTomadorSoat").val(),
    };

  guardarEstado(datos);
  msg = "Soat aprobado y emitido";
  enviarEmail(msg);
});

$("#btnEstadoDevolver").click(function () {
  $("#btnEstadoAprobar").prop("disabled", true);
  $("#btnEstadoDevolver").prop("disabled", true);
  $("#txtComentarios").prop("disabled", true);
  let inpComentarios = $("#txtComentarios").val();
  if (inpComentarios == null || inpComentarios == "") {
    swal
      .fire({
        icon: "error",
        title: "Debes escribir un comentario para poder devolver la solicitud",
        showConfirmButton: true,
        confirmButtonText: "Ok",
        allowOutsideClick: false,
        allowEscapeKey: false,
      })
      .then((result) => {

      });
    return;
  }
  // Guardar comentario
  $.ajax({
    type: "POST",
    url: "src/addComment.php",
    dataType: "text",
    data: {
      id_general: getIdCotiSoat,
      modulo: "Soat",
      comentario: inpComentarios,
      idUsuario: permisos.id_usuario,
      nombre_usuario_comentario: permisos.nombre,

    },
    cache: false,
    success: function (data) {
      console.log("comentario agregado: " + data);
    },
    error: function (xhr, status, error) {
      console.log(error);
      console.log("Error");
    },
  });

  datos = {
    Accion: "Actualizar-datos-soat",
    IdCotizacionSoat: getIdCotiSoat,
    Estado: "Solicitud rechazada",
    Correo: $("#correoTomadorSoat").val(),
    Celular: $("#celularTomadorSoat").val(),
  };
  msg = "Solicitud rechazada";
  guardarEstado(datos);
  enviarEmail(msg);
});

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

function generarPreview(url, nombre) {
    const ext = nombre.split('.').pop().toLowerCase();
    const fullUrl = `http://${url}`;

    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
        return `
            <img src="${fullUrl}" 
                 style="max-width:120px; max-height:120px; cursor:pointer; border-radius:8px;"
                 onclick="window.open('${fullUrl}', '_blank')"
            >
        `;
    }

    if (ext === 'pdf') {
        return `
            <iframe src="${fullUrl}" 
                    style="width:120px; height:120px; border-radius:8px; cursor:pointer;"
                    onclick="window.open('${fullUrl}', '_blank')">
            </iframe>
        `;
    }

    return `
        <div onclick="window.open('${fullUrl}', '_blank')" 
             style="width:120px; height:120px; display:flex; align-items:center; justify-content:center; cursor:pointer; background:#f5f5f5; border-radius:8px;">
            📄
        </div>
    `;
}