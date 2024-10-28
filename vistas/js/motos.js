$(document).ready(function () {
  // function decresCotTotales() {
  //   return new Promise(function (resolve, reject) {
  //     $.ajax({
  //       type: "POST",
  //       url: "src/updateCotizacionesTotales.php",
  //       dataType: "json",
  //       success: function (data) {
  //         resolve(data);
  //       },
  //       error: function (xhr, status, error) {
  //         reject(error);
  //       },
  //     });
  //   });
  // }

  $("#numDocumentoID").numeric();
  $("#txtFasecolda").numeric();
  $("#txtValorFasecolda").numeric();
  $("#numCotizacion").numeric();
  $("#valorTotal").numeric();
  $("#txtDigitoVerif").numeric();
  

  let inputsArr = ["txtNombres","txtNombresRepresentante","txtApellidos","txtApellidosRepresentante"]

  // Función para filtrar caracteres especiales
  function filtrarCaracteresEspeciales(input) {
    var valor = input.value;
    var valorFiltrado = valor.replace(/[^a-zA-ZñÑ ]/g, ""); // Permitir letras, espacios y la letra "ñ" en mayúsculas o minúsculas
    input.value = valorFiltrado;
  }
  
  // MANEJO DE NOMBRES Y APELLIDOS
  inputsArr.forEach(element => {
    let temp = document.getElementById(element);

    // Agregar eventos de escucha para el evento "input" en ambos campos
    temp.addEventListener("input", function () {
      filtrarCaracteresEspeciales(temp);
    });
    
    // Agregar un evento 'blur' para eliminar espacios en blanco al final y al principio
    temp.addEventListener("blur", function () {
      this.value = this.value.trim(); // Elimina espacios en blanco al principio y al final
  
      // Divide la cadena en palabras
      var words = this.value.split(" ");
  
      // Capitaliza la primera letra de cada palabra y convierte el resto en minúsculas
      for (var i = 0; i < words.length; i++) {
        words[i] =
          words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
      }
  
      // Vuelve a unir las palabras en una sola cadena
      var formattedValue = words.join(" ");
  
      // Asigna el valor formateado al campo de entrada
      this.value = formattedValue;
    });

  });

  $("#txtNombres").keyup(function () {
    var cliNombres = document.getElementById("txtNombres").value.toLowerCase();
    $("#txtNombres").val(
      cliNombres.replace(/^(.)|\s(.)/g, function ($1) {
        return $1.toUpperCase();
      })
    );
  });

  $("#txtApellidos").keyup(function () {
    var cliApellido = document
      .getElementById("txtApellidos")
      .value.toLowerCase();
    $("#txtApellidos").val(
      cliApellido.replace(/^(.)|\s(.)/g, function ($1) {
        return $1.toUpperCase();
      })
    );
  });

  // Valida que el dato ingresado sea numerico
  $("#numDocumentoID").numeric();
  $("#txtFasecolda").numeric();
  $("#txtValorFasecolda").numeric();
  $("#numCotizacion").numeric();
  $("#valorTotal").numeric();

  // $("#txtValorFasecolda").on("input", function () {
  //   this.value = this.value.replace(/\./g, "");
  // });

  // // Previene el ingreso de puntos desde el teclado
  // $("#txtValorFasecolda").on("keydown", function (event) {
  //   if (event.which === 190 || event.which === 110) {
  //     event.preventDefault();
  //   }
  // });

  const parseNumbersToString = (selector) => {
    $(selector).on("input", function () {
      this.value = this.value.replace(/\./g, "");
    });

    // Previene el ingreso de puntos desde el teclado
    $(selector).on("keydown", function (event) {
      if (event.which === 190 || event.which === 110) {
        event.preventDefault();
      }
    });
  };

  parseNumbersToString("#txtValorFasecolda");

  $("#formResumAseg, #formVehManual, #formResumVeh, #agregarOferta").on(
    "submit",
    function (e) {
      e.preventDefault(); // Evita que la pagina se recargue
    }
  );

  document.addEventListener("DOMContentLoaded", function () {
    var formulario = document.getElementById("formResumAseg"); // Reemplaza 'formulario' con el ID de tu formulario
    var tipoDocumento = document.getElementById("tipoDocumentoID");

    formulario.addEventListener("submit", function (event) {
      if (tipoDocumento.value === "") {
        event.preventDefault(); // Evita que el formulario se envíe
        document.getElementById("alertaTipoDocumento").style.display = "block"; // Muestra la alerta
      }
    });

    tipoDocumento.addEventListener("change", function () {
      if (tipoDocumento.value !== "") {
        document.getElementById("alertaTipoDocumento").style.display = "none"; // Oculta la alerta si se selecciona un documento
      }
    });
  });

  async function checkCotTotales() {
    let cotHechas = await mostrarCotRestantes();
    return new Promise(function (resolve, reject) {
      $.ajax({
        type: "POST",
        url: "src/updateCotizacionesTotales.php",
        dataType: "json",
        data: { cotHechas: cotHechas },
        success: function (data) {
          resolve(data);
        },
        error: function (xhr, status, error) {
          reject(error);
        },
      });
    });
  }

  let intermediario = document.getElementById("intermediario").value;
  // Ejectura la funcion Cotizar Ofertas
  $("#btnCotizarMotos").click(function (e) {
    let deptoCirc = $("#DptoCirculacion").val();
    let ciudadCirc = $("#ciudadCirculacion").val();

    if (!deptoCirc) {
      return;
    }
    if (!ciudadCirc) {
      return;
    }
    masRE();
    if (intermediario != 3) {
      checkCotTotales().then((response) => {
        if (response.result !== undefined) {
          switch (response.result) {
            case 1:
            case 2:
              cotizarOfertasMotos();
              break;
            case -1:
              if (intermediario == 89) {
                mostrarAlertaCotizacionesExcedidasMotosDemo();
              } else {
                e.preventDefault();
                mostrarAlertaCotizacionesExcedidasMotosFreelance();
              }
              break;
            default:
              mostrarAlertaErrorDeConexionMotos();
              break;
          }
        } else {
          mostrarAlertaErrorDeConexionMotos();
        }
      });
    } else {
      checkCotTotales().then((response) => {
        if (response.result) {
          switch (response.result) {
            case 1:
            case 2:
              mostrarPoliticaValorAseguradoMotos();
              cotizarOfertasMotos();
              break;
            case -1:
              e.preventDefault();
              mostrarAlertaCotizacionesExcedidasMotosFreelance();
              break;
            default:
              mostrarAlertaErrorDeConexionMotos();
              break;
          }
        } else {
          mostrarAlertaErrorDeConexionMotos();
        }
      });
    }
  });

  function mostrarAlertaCotizacionesExcedidasMotosFreelance() {
    swal
      .fire({
        icon: "error",
        title:
          "Llegaste al tope máximo de Multicotizaciones de Seguros de Autos",
        html: `<div style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;"><p>Ponte en contacto con tu Analista Comercial si deseas recargar tus multicotizaciones del mes.</p>
        <p>Nota: Ten en cuenta que el cupo mensual depende de tu productividad.</p>
    </div>`,
        width: "50%",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        customClass: {
          popup: "custom-swal-popupCotExcep",
        },
      })
      .then(function (result) {
        if (result.isConfirmed) {
          window.location = "inicio";
        } else if (result.isDismissed) {
          if (result.dismiss === "cancel") {
            window.location = "inicio";
          } else if (result.dismiss === "backdrop") {
            window.location = "inicio";
          }
        }
      });
  }

  function mostrarAlertaErrorDeConexionMotos() {
    swal
      .fire({
        icon: "error",
        title: "Error de conexion",
        html: `<div style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;"><p>Ocurrio un error de conexion porfavor vuelve a intentarlo.</p>
      </div>`,
        width: "50%",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        customClass: {
          popup: "custom-swal-popupCotExcep",
        },
      })
      .then(function (result) {
        // if (result.isConfirmed) {
        //   window.location = "inicio";
        // } else if (result.isDismissed) {
        //   if (result.dismiss === "cancel") {
        //     window.location = "inicio";
        //   } else if (result.dismiss === "backdrop") {
        //     window.location = "inicio";
        //   }
        // }
      });
  }

  function mostrarAlertaCotizacionesExcedidasMotosDemo() {
    swal
      .fire({
        icon: "error",
        title:
          "Llegaste al tope máximo de Multicotizaciones de Seguros de Autos",
        html: `<div style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;">
              <p>Si te interesa tener tu propia versión personalizada del software para generar cotizaciones y cuadros comparativos, comunícate con nosotros, Strategico Technologies, desarrolladores de esta plataforma, para conocer acerca de los planes de pago.</p>
            </div>`,
        width: "50%",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        customClass: {
          popup: "custom-swal-popupCotExcep",
        },
      })
      .then(function (result) {
        if (result.isConfirmed) {
          window.location = "inicio";
        } else if (result.isDismissed) {
          if (result.dismiss === "cancel") {
            window.location = "inicio";
          } else if (result.dismiss === "backdrop") {
            window.location = "inicio";
          }
        }
      });
  }

  function mostrarPoliticaValorAseguradoMotos() {
    swal.fire({
      icon: "warning",
      title: "POL\u00cdTICA DE VALOR ASEGURADO MOTOS",
      //html: "<p style='font-family: Helvetica, Arial, sans-serif;'>Para motocicletas el valor asegurado m\u00e1ximo es $50 millones. Motos por encima de ese valor, deben ser autorizadas por la Gerencia General.</p>",
      html: "<p style='font-family: Helvetica, Arial, sans-serif;'>Nota: Para motocicletas el valor asegurado m\u00e1ximo es $50 millones. Motos por encima de ese valor deben ser autorizadas por el Gerente General, quien podrá hacer excepciones de valor asegurado superior cuando el asesor sea productivo, tenga más de 6 meses de antigüedad con Grupo Asistencia, no tenga altos indices de siniestralidad en su cartera, y si el cliente tiene vinculación con otros productos de la aseguradora.</p>",
      width: "30%",
      showConfirmButton: true,
      confirmButtonText: "Continuar",
      customClass: {
        popup: "custom-swal-alertaMontoMotos",
        title: "custom-swal-title",
        confirmButton: "custom-swal-confirm-button23",
        actions: "custom-swal-actions-motos",
        icon: "swal2-icon_monto",
      },
      timer: 20000,
      timerProgressBar: true,
    });
  }

  $("#btnConsultarPlacaMotos2").click(function () {
    consulPlacaMotos(2);
  });

  $("#btnConsultarPlacaMotos").click(function () {
    consulPlacaMotos();
  });

  document
    .querySelector("#btnReCotizarFallidasMotos")
    .addEventListener("click", () => {
      cotizarOfertasMotos();
    });
});

var idCotizacion = "";

const vehiculoPermitido = ["MOTOCICLETA", "MOTO", "MOTOCICLETAS", "MOTOCARRO"];

$("#btnConsultarVehmanualbuscadorMotos").click(function () {
  var fasecolda = document.getElementById("fasecoldabuscadormanual").value;
  var modelo = document.getElementById("modelobuscadormanual").value;

  if (fasecolda == "") {
    alert("Error en el código fasecolda");
  }

  if (modelo == "") {
    alert("Error en el modelo del vehículo");
  }

  if (fasecolda != "" && modelo != "") {
    $.ajax({
      type: "POST",
      url: "src/fasecolda/consulDatosFasecolda.php",
      dataType: "json",
      data: {
        fasecolda: fasecolda,
        modelo: modelo,
      },
      success: function (data) {
        if (data.estado == undefined) {
          alert("Vehículo no encontrado");
        } else {
          var claseVeh = data.clase;
          let control = false;
          if (!data.estado) {
            control = true;
            return Swal.fire({
              icon: "warning",
              title:
                "Vehículo no encontrado, revise el código fasecolda e inténtelo nuevamente.",
              confirmButtonText: "Cerrar",
              allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location = "cotizar";
              } else if (result.isDenied) {
                window.location = "cotizar";
              }
            });
          }
          let found = vehiculoPermitido.find((element) => element == claseVeh);

          if (!found && control) {
            Swal.fire({
              icon: "error",
              title:
                "Lo sentimos, no puedes cotizar vehÍculos diferentes a motos por este módulo.",
              confirmButtonText: "Cerrar",
              allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location = "motos";
              } else if (result.isDenied) {
                window.location = "motos";
              }
            });
          } else {
            var marcaVeh = data.marca;
            var ref1Veh = data.referencia1;
            var ref2Veh = data.referencia2;
            var ref3Veh = data.referencia3;
            var lineaVeh = ref1Veh + " " + ref2Veh + " " + ref3Veh;

            var valorFasecVeh = data[modelo];
            var valorVeh = Number(valorFasecVeh) * 1000;
            var clase = data.clase;

            $("#clasepesados").val(clase);

            var placaVeh = $("#placaVeh").val();
            if (placaVeh == "WWW404") {
              $("#txtPlacaVeh").val("SIN PLACA - VEHÍCULO 0 KM").val();
            } else {
              $("#txtPlacaVeh").val(placaVeh).val();
            }

            document.getElementById("resumenVehiculo").style.display = "block";
            document.getElementById("contenBtnCotizar").style.display = "block";
            document.getElementById("headerAsegurado").style.display = "block";
            document.getElementById("masA").style.display = "block";

            document.getElementById("formularioVehiculo").style.display =
              "none";
            document.getElementById("DatosAsegurado").style.display = "none";

            document.getElementById("txtFasecolda").value = fasecolda;
            document.getElementById("txtModeloVeh").value = modelo;
            document.getElementById("txtMarcaVeh").value = data.marca;
            document.getElementById("txtValorFasecolda").value = valorVeh;
            document.getElementById("txtReferenciaVeh").value = lineaVeh;
            document.getElementById("txtClaseVeh").value = claseVeh;
          }

          //01601146

          // menosAseg();
        }
      },
    });
  }
});

$("#btnConsultarVehmanualMotos").click(function () {
  consulCodFasecoldaMotos();
  // var fasecolda = document.getElementById("fasecoldabuscadormanual").value;
  // var modelo = document.getElementById("modelobuscadormanual").value;

  // if (fasecolda != "" && modelo != "") {
  //   $.ajax({
  //     type: "POST",
  //     url: "src/fasecolda/consulDatosFasecolda.php",
  //     dataType: "json",
  //     data: {
  //       fasecolda: fasecolda,
  //       modelo: modelo,
  //     },
  //     success: function (data) {
  //       if (data.estado == undefined) {
  //         alert("Vehículo no encontrado");
  //       } else {
  //         // console.log(data);
  //         var claseVeh = data.clase;
  //         var marcaVeh = data.marca;
  //         var ref1Veh = data.referencia1;
  //         var ref2Veh = data.referencia2;
  //         var ref3Veh = data.referencia3;
  //         var lineaVeh = ref1Veh + " " + ref2Veh + " " + ref3Veh;

  //         var valorFasecVeh = data[modelo];
  //         var valorVeh = Number(valorFasecVeh) * 1000;
  //         var clase = data.clase;

  //         $("#clasepesados").val(clase);

  //         var placaVeh = $("#placaVeh").val();
  //         if (placaVeh == "WWW404") {
  //           $("#txtPlacaVeh").val("SIN PLACA - VEHÍCULO 0 KM").val();
  //         } else {
  //           $("#txtPlacaVeh").val(placaVeh).val();
  //         }

  //         document.getElementById("resumenVehiculo").style.display = "block";
  //         document.getElementById("contenBtnCotizar").style.display = "block";
  //         document.getElementById("headerAsegurado").style.display = "block";
  //         document.getElementById("masA").style.display = "block";

  //         document.getElementById("formularioVehiculo").style.display = "none";
  //         document.getElementById("DatosAsegurado").style.display = "none";

  //         document.getElementById("txtFasecolda").value = fasecolda;
  //         document.getElementById("txtModeloVeh").value = modelo;
  //         document.getElementById("txtMarcaVeh").value = data.marca;
  //         document.getElementById("txtValorFasecolda").value = valorVeh;
  //         document.getElementById("txtReferenciaVeh").value = lineaVeh;
  //         document.getElementById("txtClaseVeh").value = claseVeh;
  //       }
  //     },
  //   });
  // }
});

const requiredFieldsNotNit = (val) => {
  if (val) {
    const arrIDs = ["txtNombres", "txtApellidos", "genero", "estadoCivil"];

    arrIDs.map((id) => {
      document.getElementById(id).removeAttribute("required");
      // document.getElementById(id).classList.remove("form-control");
    });
  } else {
    const arrIDs = ["txtNombres", "txtApellidos", "genero", "estadoCivil"];

    arrIDs.map((id) => {
      document.getElementById(id).setAttribute("required", true);
      //document.getElementById(id).classList.add("form-control");
    });
  }
};

const requiredFields = (val) => {
  if (val) {
    const arrIDs = [
      "txtNombresRepresentante",
      "txtApellidosRepresentante",
      "dianacimientoRepresentante",
      "mesnacimientoRepresentante",
      "anionacimientoRepresentante",
    ];

    arrIDs.map((id) => {
      document.getElementById(id).setAttribute("required", true);
    });
  } else {
    const arrIDs = [
      "txtNombresRepresentante",
      "txtApellidosRepresentante",
      "dianacimientoRepresentante",
      "mesnacimientoRepresentante",
      "anionacimientoRepresentante",
    ];

    arrIDs.map((id) => {
      document.getElementById(id).removeAttribute("required");
    });
  }
};

const controlFields = (val) => {
  if (val) {
    // Fila Placa, nombres, id, doc
    $('label[for="txtNombres"]').text("Digito de Verificacion");
    $("#divNombre").css("display", "none");
    $("#digitoVerificacion").css("display", "block");

    // Fila Fecha, Razon Social (Para Nit), Genero, Estado Civil, Celular (Todas menos NIT)
    $('label[name="lblFechaNacimiento"]').html(
      'Fecha Constitucion Empresa <span style="font-weight: normal;">(Opcional. Se requiere para Zurich y Allianz)</span>'
    );
    $('label[name="lblFechaNacimiento"]').css("max-width", "447px");
    $('label[name="lblFechaNacimiento"]').css("width", "447px");

    $("#divRazonSocial").css("display", "block");

    $('label[for="genero"]').css("display", "none");
    $("#genero").css("display", "none");

    $('label[for="estadoCivil"]').css("display", "none");
    $("#estadoCivil").css("display", "none");

    $('label[for="txtCorreo"]').css("display", "none");
    $("#txtCorreo").css("display", "none");

    $('label[for="celular"]').css("display", "none");
    $("#txtCelular").css("display", "none");

    $("#rowBoton").css("display", "none");

    // CAMPOS REPRESENTANTE LEGAL
    $("#datosAseguradoNIT").css("display", "block");

    requiredFields(val);
    requiredFieldsNotNit(val);
  } else {
    $('label[for="txtNombres"]').text("Nombre Completo");
    $("#divNombre").css("display", "block");
    $("#digitoVerificacion").css("display", "none");

    // Fila Fecha, Razon Social (Para Nit), Genero, Estado Civil, Celular (Todas menos NIT)
    $('label[name="lblFechaNacimiento"]').html("Fecha de Nacimiento");
    $('label[name="lblFechaNacimiento"]').css("max-width", "");
    $('label[name="lblFechaNacimiento"]').css("width", "");

    $("#divRazonSocial").css("display", "none");

    $('label[for="genero"]').css("display", "block");
    $("#genero").css("display", "block");

    $('label[for="estadoCivil"]').css("display", "block");
    $("#estadoCivil").css("display", "block");

    $('label[for="txtCorreo"]').css("display", "block");
    $("#txtCorreo").css("display", "block");

    $('label[for="celular"]').css("display", "block");
    $("#txtCelular").css("display", "block");

    // CAMPOS REPRESENTANTE LEGAL
    $("#datosAseguradoNIT").css("display", "none");

    $("#rowBoton").css("display", "block");

    requiredFields(val);
    requiredFieldsNotNit(val);
  }
};

$("#tipoDocumentoID").on("change", function () {
  let doctype = $("#tipoDocumentoID").val();
  // console.log(doctype)
  if (doctype == 2) {
    controlFields(true);
  } else {
    controlFields(false);
  }
});

// Maximiza el formulario Datos Asegurado
function masAseg() {
  document.getElementById("DatosAsegurado").style.display = "block";
  document.getElementById("datosAseguradoNIT").style.display = "block";
  document.getElementById("menosAsegurado").style.display = "block";
  document.getElementById("masAsegurado").style.display = "none";
}
// Minimiza el formulario Datos Asegurado
function menosAseg() {
  document.getElementById("DatosAsegurado").style.display = "none";
  document.getElementById("datosAseguradoNIT").style.display = "none";
  document.getElementById("menosAsegurado").style.display = "none";
  document.getElementById("masAsegurado").style.display = "block";
}

// Maximizar el formulario Datos Vehiculo
function masVeh() {
  document.getElementById("DatosVehiculo").style.display = "block";
  document.getElementById("menosVehiculo").style.display = "block";
  document.getElementById("masVehiculo").style.display = "none";
}
// Minimiza el formulario Datos Vehiculo
function menosVeh() {
  document.getElementById("DatosVehiculo").style.display = "none";
  document.getElementById("menosVehiculo").style.display = "none";
  document.getElementById("masVehiculo").style.display = "block";
}

// Maximiza el Formulario Agregar Oferta
function masAgr() {
  document.getElementById("DatosAgregarOferta").style.display = "block";
  document.getElementById("menosAgrOferta").style.display = "block";
  document.getElementById("masAgrOferta").style.display = "none";
}
// Minimiza el Formulario Agregar Oferta
function menosAgr() {
  document.getElementById("DatosAgregarOferta").style.display = "none";
  document.getElementById("menosAgrOferta").style.display = "none";
  document.getElementById("masAgrOferta").style.display = "block";
}

$("#numDocumentoID").change(function () {
  consultarAsegurado();
});

function consultarAsegurado() {
  var tipoDocumentoID = document.getElementById("tipoDocumentoID").value;
  var numDocumentoID = document.getElementById("numDocumentoID");
  $.ajax({
    type: "POST",
    url: "src/consultarAsegurado.php",
    dataType: "json",
    data: {
      tipoDocumento: tipoDocumentoID,
      numDocumento: numDocumentoID.value,
    },
    success: function (data) {
      var estado = data.estado;
      var fechaNac = data.cli_fch_nacimiento;
      let documentCli = data.cli_num_documento.slice(0, -1);

      if (estado && data.id_tipo_documento == 2) {
        let fechaNacRep = data.rep_legal.rep_fch_nacimiento;
        $("#idCliente").val(data.id_cliente);
        $("#tipoDocumentoID").val(data.id_tipo_documento);
        $("#txtRazonSocial").val(data.cli_nombre + " " + data.cli_apellidos);
        $("#txtDigitoVerif").val(data.cli_num_documento.slice(-1)); // Último dígito
        numDocumentoID.value = documentCli;

        var fecha = fechaNac.split("-");
        var nombreMes = obtenerNombreMes(fecha[1]);
        $("#dianacimiento").append(
          "<option value='" + fecha[2] + "' selected>" + fecha[2] + "</option>"
        );
        $("#mesnacimiento").append(
          "<option value='" +
            fecha[1] +
            "' selected>" +
            nombreMes[0].toUpperCase() +
            nombreMes.slice(1) +
            "</option>"
        );
        $("#anionacimiento").append(
          "<option value='" + fecha[0] + "' selected>" + fecha[0] + "</option>"
        );

        // Asignar datos del representante legal
        $("#tipoDocumentoIDRepresentante").val(
          data.rep_legal.rep_tipo_documento
        );
        $("#numDocumentoIDRepresentante").val(data.rep_legal.rep_num_documento);
        $("#txtNombresRepresentante").val(data.rep_legal.rep_nombre);
        $("#txtApellidosRepresentante").val(data.rep_legal.rep_apellidos);
        $("#generoRepresentante").val(data.rep_legal.rep_genero);
        $("#estadoCivilRepresentante").val(data.rep_legal.rep_est_civil);
        $("#txtCorreoRepresentante").val(data.rep_legal.rep_email);
        $("#txtCelularRepresentante").val(data.rep_legal.rep_telefono);
        controlFields(true);

        var fecha = fechaNacRep.split("-");
        var nombreMes = obtenerNombreMes(fecha[1]);
        $("#dianacimientoRepresentante").append(
          "<option value='" + fecha[2] + "' selected>" + fecha[2] + "</option>"
        );
        $("#mesnacimientoRepresentante").append(
          "<option value='" +
            fecha[1] +
            "' selected>" +
            nombreMes[0].toUpperCase() +
            nombreMes.slice(1) +
            "</option>"
        );
        $("#anionacimientoRepresentante").append(
          "<option value='" + fecha[0] + "' selected>" + fecha[0] + "</option>"
        );
      } else if (estado) {
        $("#idCliente").val(data.id_cliente);
        $("#tipoDocumentoID").val(data.id_tipo_documento);
        $("#txtNombres").val(data.cli_nombre);
        $("#txtApellidos").val(data.cli_apellidos);
        $("#genero").val(data.cli_genero);
        $("#estadoCivil").val(data.id_estado_civil);
        $("#txtCorreo").val(data.cli_email);
        $("#txtCelular").val(data.cli_telefono);
        // Adjuntar correo y número

        var fecha = fechaNac.split("-");
        var nombreMes = obtenerNombreMes(fecha[1]);
        $("#dianacimiento").append(
          "<option value='" + fecha[2] + "' selected>" + fecha[2] + "</option>"
        );
        $("#mesnacimiento").append(
          "<option value='" +
            fecha[1] +
            "' selected>" +
            nombreMes[0].toUpperCase() +
            nombreMes.slice(1) +
            "</option>"
        );
        $("#anionacimiento").append(
          "<option value='" + fecha[0] + "' selected>" + fecha[0] + "</option>"
        );
      } else {
        $("#idCliente").val("");
        //$("#tipoDocumentoID").val("");
        $("#txtNombres").val("");
        $("#txtApellidos").val("");
        $("#genero").val("");
        $("#estadoCivil").val("");
        $("#txtCorreo").val("");
        $("#txtCelular").val("");

        $("#dianacimiento").append("<option value='' selected></option>");
        $("#mesnacimiento").append("<option value=''selected ></option>");
        $("#anionacimiento").append("<option value='' selected></option>");
        //console.log(data.mensaje);
      }
    },
  });
}

var contErrMetEstado = 0;
var contErrProtocolo = 0;

// Permite consultar la informacion del vehiculo por medio de la Placa (Seguros del Estado)
function consulPlacaMotos(query = "1") {
  debugger;
  var numplaca = document.getElementById("placaVeh").value;
  if (numplaca == "WWW404") {
    document.getElementById("formularioVehiculo").style.display = "block";
    $("#loaderPlaca").html("");
  } else {
    var rolAsesor = document.getElementById("rolAsesor").value;
    var valnumplaca = numplaca.toUpperCase(); // Convierte la Placa en Mayusculas
    var tipoDocumentoID = document.getElementById("tipoDocumentoID").value;
    var numDocumentoID = document.getElementById("numDocumentoID").value;
    var dianacimiento = document.getElementById("dianacimiento").value;
    var mesnacimiento = document.getElementById("mesnacimiento").value;
    var anionacimiento = document.getElementById("anionacimiento").value;
    var nombresAseg = document.getElementById("txtNombres").value;
    var apellidosAseg = document.getElementById("txtApellidos").value;
    var generoAseg = document.getElementById("genero").value;
    var estadoCivil = document.getElementById("estadoCivil").value;
    var intermediario = document.getElementById("intermediario").value;
    if (intermediario !== "3") {
      var mensajeSga = document.getElementById("mensajeSga");
      mensajeSga.style.display = "none"; // o cualquier otro valor como 'inline', 'flex', etc.
    }

    //! Agregar esto a MOTOS y Pesados START
    let digitoVerif = $("#txtDigitoVerif").val();
    let razonSocial = $("#txtRazonSocial").val();
    let numDocRep = $("#numDocumentoIDRepresentante").val();
    let nomRep = $("#txtNombresRepresentante").val();
    let apellidoRep = $("#txtApellidosRepresentante").val();
    let generoRep = $("#generoRepresentante").val();
    let estadoCivilRep = $("#estadoCivilRepresentante").val();
    let correoRep = $("#txtCorreoRepresentante").val();
    let anioRep = $("#anionacimientoRepresentante").val();
    let diaRep = $("#dianacimientoRepresentante").val();
    let mesRep = $("#mesnacimientoRepresentante").val();
    let celularRep = $("#txtCelularRepresentante").val();
    //! Agregar esto a MOTOS y Pesados END

    // console.log(
    //   rolAsesor,
    //   valnumplaca,
    //   tipoDocumentoID,
    //   numDocumentoID,
    //   dianacimiento,
    //   mesnacimiento,
    //   anionacimiento,
    //   nombresAseg,
    //   apellidosAseg,
    //   generoAseg,
    //   estadoCivil,
    //   intermediario
    // );
    // if (tipoDocumentoID == "2") {
    //   var restriccion = "";
    //   if (rolAsesor == 19) {
    //     restriccion =
    //       "Lo sentimos, no puedes realizar cotizaciones para personas jurídicas por este cotizador. Para hacerlo debes comunicarte con el Equipo de Asesores Freelance de Grupo Asistencia, quienes podrán ayudarte a cotizar de manera manual con diferentes aseguradoras.";
    //   } else {
    //     restriccion =
    //       "Lo sentimos, no puedes realizar cotizaciones para personas jurídicas por este cotizador.";
    //   }
    //   Swal.fire({
    //     icon: "error",
    //     text: restriccion,
    //     confirmButtonText: "Cerrar",
    //   }).then(() => {
    //     // Recargar la página después de cerrar el SweetAlert
    //     location.reload();
    //   });
    // }

    //! Agregar esto a MOTOS y Pesados START
    let typeQuery =
      query != "2"
        ? numplaca != "" &&
          tipoDocumentoID != "" &&
          numDocumentoID != "" &&
          dianacimiento != "" &&
          mesnacimiento != "" &&
          anionacimiento != "" &&
          nombresAseg != "" &&
          apellidosAseg != "" &&
          generoAseg != "" &&
          estadoCivil != ""
        : numplaca != "" &&
          digitoVerif != "" &&
          razonSocial != "" &&
          anioRep != "" &&
          diaRep != "" &&
          mesRep != "" &&
          dianacimiento != "" &&
          mesnacimiento != "" &&
          anionacimiento != "" &&
          numDocRep != "" &&
          nomRep != "" &&
          apellidoRep != "" &&
          generoRep != "" &&
          estadoCivilRep != "";
    //correoRep != "" &&
    //celularRep != "";

    //! Agregar esto a MOTOS y Pesados END

    if (typeQuery) {
      // Oculta los campos de consultar Vehiculo paso a paso desde la Guia Fasecolda
      document.getElementById("formularioVehiculo").style.display = "none";
      $("#loaderPlaca").html(
        '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Consultando Placa...</strong>'
      );

      //! Agregar esto a MOTOS y Pesados START

      $("#loaderPlaca2").html(
        '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Consultando Placa...</strong>'
      );

      //! Agregar esto a MOTOS y Pesados END

      //INICIO DE CABECERA PARA INGRESAR INFORMACION DEL METODO
      var myHeaders = new Headers();
      myHeaders.append("Content-Type", "application/json");

      var raw = JSON.stringify({
        Placa: valnumplaca,
        intermediario: intermediario,
      });

      var requestOptions = {
        mode: "cors",
        method: "POST",
        headers: myHeaders,
        body: raw,
        redirect: "follow",
      };
      try {
        // Llama la informacion del Vehiculo por medio de la Placa
        fetch(
          "https://grupoasistencia.com/motor_webservice/Vehiculo",
          requestOptions
        )
          .then(function (response) {
            if (!response.ok) {
              throw Error(response.statusText);
            }
            try {
              return response.json();
            } catch (error) {
              console.error("Error al procesar JSON:", error);
              throw error; // Vuelve a lanzar el error para que puedas verlo en el bloque catch siguiente
            }
          })
          // .then(function (myJson) {
          //   // Procesar myJson si todo está bien
          //   console.log("Respuesta JSON:", myJson);
          // })
          // .catch(function (error) {
          //   console.error("Error en la petición fetch:", error);
          // })
          // return;
          .then(function (myJson) {
            debugger;
            var estadoConsulta = myJson.Success;
            var mensajeConsulta = myJson.Message;
            //console.log(myJson);
            //VALIDA SI LA CONSULTA FUE EXITOSA
            if (estadoConsulta == true) {
              var codigoClase = myJson.Data.ClassId;
              var codigoMarca = myJson.Data.Brand;
              var modeloVehiculo = myJson.Data.Modelo;
              var codigoLinea = myJson.Data.BrandLine;
              var codigoFasecolda = myJson.Data.CodigoFasecolda;
              var valorAsegurado = myJson.Data.ValorAsegurado;

              if (codigoFasecolda != null) {
                if (valorAsegurado == "null" || valorAsegurado == null) {
                  consulPlacaMapfre(valnumplaca);
                  // document.getElementById("formularioVehiculo").style.display =
                  //   "block";
                  // $("#loaderPlaca").html("");
                  //! Agregar esto a MOTOS y Pesados START
                  $("#loaderPlaca").html("");
                  $("#loaderPlaca2").html("");
                  //! Agregar esto a MOTOS y Pesados END
                } else {
                  var claseVehiculo = "";
                  var limiteRCESTADO = "";

                  if (codigoClase == 1) {
                    claseVehiculo = "AUTOMOVILES";
                    limiteRCESTADO = 6;
                    var restriccion = "";
                    if (rolAsesor == 19) {
                      restriccion =
                        "Lo sentimos, no puedes cotizar vehÍculos livianos por este módulo. Para hacerlo debes ingresar al modulo Cotizar Livianos.";
                    } else {
                      restriccion =
                        "Lo sentimos, no puedes cotizar vehÍculos livianos por este módulo.";
                    }
                    Swal.fire({
                      icon: "error",
                      text: restriccion,
                      confirmButtonText: "Cerrar",
                    }).then(() => {
                      // Recargar la página después de cerrar el SweetAlert
                      // location.reload();
                    });
                  } else if (codigoClase == 2) {
                    claseVehiculo = "CAMPEROS";
                    limiteRCESTADO = 18;
                  } else if (codigoClase == 3) {
                    claseVehiculo = "PICK UPS";
                    limiteRCESTADO = 18;
                  } else if (codigoClase == 4) {
                    claseVehiculo = "UTILITARIOS DEPORTIVOS";
                    limiteRCESTADO = 6;
                  } else if (codigoClase == 12) {
                    claseVehiculo = "MOTOCICLETA";
                    limiteRCESTADO = 6;
                  } else if (codigoClase == 14 || codigoClase == 21) {
                    claseVehiculo = "PESADO";
                    limiteRCESTADO = 18;
                    var restriccion = "";
                    if (rolAsesor == 19) {
                      restriccion =
                        "Lo sentimos, no puedes cotizar vehículos pesados por este módulo. Para hacerlo debes ingresar al modulo Cotizar Pesados.";
                    } else {
                      restriccion =
                        "Lo sentimos, no puedes cotizar pesados por este módulo.";
                    }
                    Swal.fire({
                      icon: "error",
                      text: restriccion,
                      confirmButtonText: "Cerrar",
                    }).then(() => {
                      // Recargar la página después de cerrar el SweetAlert
                      // location.reload();
                    });
                  } else if (codigoClase == 19) {
                    claseVehiculo = "VAN";
                    limiteRCESTADO = 18;
                  } else if (codigoClase == 16) {
                    claseVehiculo = "MOTOCICLETA";
                    limiteRCESTADO = 6;
                  }

                  $("#CodigoClase").val(codigoClase);
                  $("#txtClaseVeh").val(claseVehiculo);
                  $("#LimiteRC").val(limiteRCESTADO);
                  $("#CodigoMarca").val(codigoMarca);
                  $("#txtModeloVeh").val(modeloVehiculo);
                  $("#CodigoLinea").val(codigoLinea);
                  $("#txtFasecolda").val(codigoFasecolda);
                  $("#txtValorFasecolda").val(valorAsegurado);

                  consulDatosFasecoldaMotos(
                    codigoFasecolda,
                    modeloVehiculo
                  ).then(function (resp) {
                    $("#txtMarcaVeh").val(resp.marcaVeh);
                    $("#txtReferenciaVeh").val(resp.lineaVeh);
                  });
                }
              }
            } else {
              if (
                mensajeConsulta ==
                  "Parámetros Inválidos. Placa es requerido." ||
                mensajeConsulta == "Favor diligenciar correctamente la placa"
              ) {
                swal.fire({
                  text: "! Favor diligenciar correctamente la placa. ¡",
                });
              } else {
                consulPlacaMapfre(valnumplaca);
              }
              consulPlacaMapfre(valnumplaca);
            }
          })
          .catch(function (error) {
            consulPlacaMapfre(valnumplaca);

            contErrProtocolo++;
            if (contErrProtocolo > 1) {
              consulPlacaMapfre(valnumplaca);

              contErrProtocolo = 0;
            } else {
              // setTimeout(consulPlacaMapfre, 4000);
            }
          });
      } catch (error) {
        console.log(error);
      }
      return;
    } else {
      console.log("me fui por aqui");
    }
  }
}

function consulPlacaMapfre(valnumplaca) {
  let bodyContent = JSON.stringify({
    Placa: valnumplaca,
  });

  let headersList = {
    Accept: "*/*",
    "User-Agent": "Thunder Client (https://www.thunderclient.com)",
    "Content-Type": "application/json",
  };

  fetch("https://grupoasistencia.com/webserviceAutos/ultimaPolizaMapfre", {
    method: "POST",
    body: bodyContent,
    headers: headersList,
  })
    .then(function (response) {
      return response.json();
    })
    .then(async function (data) {
      var resultadoConsulta = data.respuesta.errorEjecucion;
      var codigoClase = data.polizaReciente.COD_MODELO;
      var marcaCod = data.polizaReciente.COD_MARCA;
      var clase = data.polizaReciente.NOM_CLASE;
      var linea = data.polizaReciente.NOM_LINEA;
      var modelo = data.polizaReciente.ANIO_VEHICULO;
      var cilindraje = data.polizaReciente.VAL_CILINDRAJE;
      var codFasecolda = data.polizaReciente.COD_FASECOLDA;
      var aseguradora = data.polizaReciente.nomCompania;

      propietario = data.polizaReciente.asegNombre;
      cedulaP = data.polizaReciente.asegCodDocum;

      if (
        marcaCod == "" &&
        clase == "" &&
        linea == "" &&
        modelo == "" &&
        cilindraje == "" &&
        codFasecolda == "" &&
        aseguradora == "" &&
        aseguradora == "" &&
        fechFinTR == "" &&
        propietario == "" &&
        cedulaP == ""
      ) {
        alert("No se encuentra poliza en esta placa");
      }

      if (resultadoConsulta == false || resultadoConsulta == "false") {
        var claseVehiculo = "";
        var limiteRCESTADO = "";

        if (codigoClase == 1) {
          claseVehiculo = "AUTOMOVILES";
          limiteRCESTADO = 6;
        } else if (codigoClase == 2) {
          claseVehiculo = "CAMPEROS";
          limiteRCESTADO = 18;
        } else if (codigoClase == 3) {
          claseVehiculo = "PICK UPS";
          limiteRCESTADO = 18;
        } else if (codigoClase == 4) {
          claseVehiculo = "UTILITARIOS DEPORTIVOS";
          limiteRCESTADO = 6;
        } else if (codigoClase == 12) {
          claseVehiculo = "MOTOCICLETA";
          limiteRCESTADO = 6;
        } else if (codigoClase == 14 || codigoClase == 21) {
          claseVehiculo = "PESADO";
          limiteRCESTADO = 18;
        } else if (codigoClase == 19) {
          claseVehiculo = "VAN";
          limiteRCESTADO = 18;
        } else if (codigoClase == 16) {
          claseVehiculo = "MOTOCICLETA";
          limiteRCESTADO = 6;
        }

        $("#CodigoClase").val(codigoClase);
        $("#txtClaseVeh").val(claseVehiculo);
        $("#LimiteRC").val(limiteRCESTADO);
        $("#CodigoMarca").val(marcaCod);
        $("#txtModeloVeh").val(modelo);
        $("#CodigoLinea").val(linea);
        $("#txtFasecolda").val(codFasecolda);

        consulDatosFasecolda(codFasecolda, modelo).then(function (resp) {
          $("#txtMarcaVeh").val(resp.marcaVeh);
          $("#txtReferenciaVeh").val(resp.lineaVeh);
          $("#txtValorFasecolda").val(resp.valorVeh);
        });
      } else {
        document.getElementById("formularioVehiculo").style.display = "block";
        document.getElementById("headerAsegurado").style.display = "block";
        document.getElementById("masA").style.display = "block";
        document.getElementById("DatosAsegurado").style.display = "none";
        document.getElementById("loaderPlaca").style.display = "none";
        //! Agregar esto a MOTOS y Pesados START
        document.getElementById("loaderPlaca2").style.display = "none";
        //! Agregar esto a MOTOS y Pesados END
      }
    })
    .catch(function (error) {
      document.getElementById("formularioVehiculo").style.display = "block";
      document.getElementById("headerAsegurado").style.display = "block";
      document.getElementById("masA").style.display = "block";
      document.getElementById("DatosAsegurado").style.display = "none";
      document.getElementById("loaderPlaca").style.display = "none";
      //! Agregar esto a MOTOS y Pesados START
      document.getElementById("loaderPlaca2").style.display = "none";
      //! Agregar esto a MOTOS y Pesados END
    });
}

// CONSULTA LA GUIA PARA OBTENER EL CODIGO FASECOLDA MANUALMENTE
function consulCodFasecoldaMotos() {
  var claseVeh = document.getElementById("clase").value;
  var marcaVeh = document.getElementById("Marca").value;
  var edadVeh = document.getElementById("edad").value;
  var refe = document.getElementById("linea").value;
  var refe2 = $(".refe1").val();
  var refe3 = $(".refe22").val();

  if (
    claseVeh != "" &&
    marcaVeh != "" &&
    edadVeh != "" &&
    refe != "" &&
    refe2 != "" &&
    refe3 != ""
  ) {
    $.ajax({
      type: "POST",
      url: "src/fasecolda/consulCodFasecolda.php",
      dataType: "json",
      data: {
        clasveh: claseVeh,
        MarcaVeh: marcaVeh,
        edadVeh: edadVeh,
        lineaVeh: refe,
        refe: refe2,
        refe2: refe3,
      },
      success: function (data) {
        var codFasecolda = data.result.codigo;
        consulValorfasecoldaMotos(codFasecolda, edadVeh);
      },
    });
  }
}

var contErrMetEstadoFasec = 0;
var contErrProtConsulFasec = 0;

// Permite consultar la informacion del vehiculo segun la Guia Fasecolda
function consulValorfasecoldaMotos(codFasecolda, edadVeh) {
  $("#loaderVehiculo").html(
    '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Consultando Vehículo...</strong>'
  );

  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  var raw = JSON.stringify({
    CodigoFasecolda: codFasecolda,
    brand: "",
    brandline: "",
    ClassId: "",
    Modelo: edadVeh,
  });

  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  fetch(
    "https://grupoasistencia.com/motor_webservice/VehiculoFasecolda",
    requestOptions
  )
    .then(function (response) {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      return response.json();
    })
    .then(function (myJson) {
      if (myJson.Data != null) {
        var codigoClase = myJson.Data.ClassId;
        var codigoMarca = myJson.Data.Brand;
        var modeloVehiculo = myJson.Data.Modelo;
        var codigoLinea = myJson.Data.BrandLine;
        var codigoFasecolda = myJson.Data.CodigoFasecolda;
        var valorAsegurado = myJson.Data.ValorAsegurado;

        var claseVehiculo = "";
        var limiteRCESTADO = "";

        if (codigoClase == 1) {
          claseVehiculo = "AUTOMOVILES";
          limiteRCESTADO = 6;
        } else if (codigoClase == 2) {
          claseVehiculo = "CAMPEROS";
          limiteRCESTADO = 18;
        } else if (codigoClase == 3) {
          claseVehiculo = "PICK UPS";
          limiteRCESTADO = 18;
        } else if (codigoClase == 4) {
          claseVehiculo = "UTILITARIOS DEPORTIVOS";
          limiteRCESTADO = 6;
        } else if (codigoClase == 12) {
          claseVehiculo = "MOTOCICLETA";
          limiteRCESTADO = 6;
        } else if (codigoClase == 14) {
          claseVehiculo = "PESADO";
          limiteRCESTADO = 18;
        } else if (codigoClase == 19) {
          claseVehiculo = "VAN";
          limiteRCESTADO = 18;
        } else if (codigoClase == 16) {
          claseVehiculo = "MOTOCICLETA";
          limiteRCESTADO = 6;
        }

        $("#CodigoClase").val(codigoClase);
        $("#txtClaseVeh").val(claseVehiculo);
        $("#LimiteRC").val(limiteRCESTADO);
        $("#CodigoMarca").val(codigoMarca);
        $("#txtModeloVeh").val(modeloVehiculo);
        $("#CodigoLinea").val(codigoLinea);
        $("#txtFasecolda").val(codigoFasecolda);
        $("#txtValorFasecolda").val(valorAsegurado);

        consulDatosFasecolda(codigoFasecolda, modeloVehiculo).then(function (
          resp
        ) {
          $("#txtMarcaVeh").val(resp.marcaVeh);
          $("#txtReferenciaVeh").val(resp.lineaVeh);
        });
      } else {
        contErrMetEstadoFasec++;
        if (contErrMetEstadoFasec > 2) {
          $("#txtModeloVeh").val(edadVeh);
          $("#txtFasecolda").val(codFasecolda);

          consulDatosFasecolda(codFasecolda, edadVeh).then(function (resp) {
            var codigoClaseEstado = "";
            if (resp.claseVeh == "MOTOS") {
              codigoClaseEstado = 12;
            }
            $("#CodigoClase").val(codigoClaseEstado);
            $("#txtClaseVeh").val(resp.claseVeh);
            $("#txtMarcaVeh").val(resp.marcaVeh);
            $("#txtReferenciaVeh").val(resp.lineaVeh);
            $("#txtValorFasecolda").val(resp.valorVeh);
          });
          contErrMetEstadoFasec = 0;
        } else {
          setTimeout(consulCodFasecolda, 2000);
        }
      }
    })
    .catch(function (error) {
      contErrProtConsulFasec++;
      if (contErrProtConsulFasec > 1) {
        $("#txtModeloVeh").val(edadVeh);
        $("#txtFasecolda").val(codFasecolda);

        consulDatosFasecoldaMotos(codFasecolda, edadVeh).then(function (resp) {
          var codigoClaseEstado = "";
          if (resp.claseVeh == "MOTOS") {
            codigoClaseEstado = 12;
          }
          $("#CodigoClase").val(codigoClaseEstado);
          $("#txtClaseVeh").val(resp.claseVeh);
          $("#txtMarcaVeh").val(resp.marcaVeh);
          $("#txtReferenciaVeh").val(resp.lineaVeh);
          $("#txtValorFasecolda").val(resp.valorVeh);
        });
        contErrProtConsulFasec = 0;
      } else {
        setTimeout(consulCodFasecolda, 4000);
      }
    });
}

//FUNCION PARA CONSULTAR VALORES EN FASECOLDA
function consulDatosFasecoldaMotos(codFasecolda, edadVeh) {
  debugger;
  console.log("entre aqui");

  return new Promise(function (resolve, reject) {
    $.ajax({
      type: "POST",
      url: "src/fasecolda/consulDatosFasecolda.php",
      dataType: "json",
      data: {
        fasecolda: codFasecolda,
        modelo: edadVeh,
      },
      success: function (data) {
        if (data.mensaje == "No hay Registros.") {
          document.getElementById("formularioVehiculo").style.display = "block";
          Swal.fire({
            icon: "error",
            title: "Error al traer la información",
            text: "No se obtuvieron registros, verifique la información del vehículo e intente nuevamente",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
          });
          $("#loaderPlaca").html("");
          //! Agregar esto a MOTOS y Pesados START
          $("#loaderPlaca2").html("");
          //! Agregar esto a MOTOS y Pesados END
        } else {
          var claseVeh = data.clase;
          var marcaVeh = data.marca;
          var ref1Veh = data.referencia1;
          var ref2Veh = data.referencia2;
          var ref3Veh = data.referencia3;
          var lineaVeh = ref1Veh + " " + ref2Veh + " " + ref3Veh;
          var valorFasecVeh = data[edadVeh];
          var valorVeh = Number(valorFasecVeh) * 1000;

          var placaVeh = $("#placaVeh").val();
          if (placaVeh == "WWW404") {
            $("#txtPlacaVeh").val("SIN PLACA - VEHÍCULO 0 KM").val();
          } else {
            $("#txtPlacaVeh").val(placaVeh).val();
          }
          document.getElementById("formularioVehiculo").style.display = "none";
          document.getElementById("headerAsegurado").style.display = "block";
          document.getElementById("contenSuperiorPlaca").style.display = "none";
          document.getElementById(
            "contenBtnConsultarPlacaMotos"
          ).style.display = "none";
          document.getElementById("resumenVehiculo").style.display = "block";
          document.getElementById("contenBtnCotizar").style.display = "block";
          $("#loaderPlaca").html("");
          //! Agregar esto a MOTOS y Pesados START
          $("#loaderPlaca2").html("");
          //! Agregar esto a MOTOS y Pesados END
          menosAseg();

          resolve({
            claseVeh: claseVeh,
            marcaVeh: marcaVeh,
            lineaVeh: lineaVeh,
            valorVeh: valorVeh,
          });
          reject(new Error("Fallo la Consulta"));
        }
      },
    });
  });
}

//FUNCION PARA CONSULTAR VALORES EN FASECOLDA
function consulDatosFasecoldaPesados(codFasecolda, edadVeh) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      type: "POST",
      url: "src/fasecolda/consulDatosFasecolda.php",
      dataType: "json",
      data: {
        fasecolda: codFasecolda,
        modelo: edadVeh,
      },
      success: function (data) {
        var claseVeh = data.clase;
        var marcaVeh = data.marca;
        var ref1Veh = data.referencia1;
        var ref2Veh = data.referencia2;
        var ref3Veh = data.referencia3;
        var lineaVeh = ref1Veh + " " + ref2Veh + " " + ref3Veh;
        var valorFasecVeh = data[edadVeh];
        var valorVeh = Number(valorFasecVeh) * 1000;
        var clase = data.clase;

        $("#clasepesados").val(clase);

        var placaVeh = $("#placaVeh").val();
        if (placaVeh == "WWW404") {
          $("#txtPlacaVeh").val("SIN PLACA - VEHÍCULO 0 KM").val();
        } else {
          $("#txtPlacaVeh").val(placaVeh).val();
        }
        document.getElementById("formularioVehiculo").style.display = "none";
        document.getElementById("headerAsegurado").style.display = "block";
        document.getElementById("contenSuperiorPlaca").style.display = "none";
        document.getElementById("contenBtnConsultarPlaca").style.display =
          "none";
        document.getElementById("resumenVehiculo").style.display = "block";
        document.getElementById("contenBtnCotizar").style.display = "block";
        $("#loaderPlaca").html("");
        menosAseg();

        resolve({
          claseVeh: claseVeh,
          marcaVeh: marcaVeh,
          lineaVeh: lineaVeh,
          valorVeh: valorVeh,
        });
        reject(new Error("Fallo la Consulta"));
      },
    });
  });
}

// FUNCION PARA CARGAR LA CIUDAD DE CIRCULACIÓN
function consultarCiudad() {
  var codigoDpto = document.getElementById("DptoCirculacion").value;

  $.ajax({
    type: "POST",
    url: "src/consultarCiudad.php",
    dataType: "json",
    data: { data: codigoDpto },
    cache: false,
    success: function (data) {
      // console.log(data);
      var ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;

      data.forEach(function (valor, i) {
        var valorNombre = valor.Nombre.split("-");
        var nombreMinusc = valorNombre[0].toLowerCase();
        var ciudad = nombreMinusc.replace(/^(.)|\s(.)/g, function ($1) {
          return $1.toUpperCase();
        });

        ciudadesVeh += `<option value="${valor.Codigo}">${ciudad}</option>`;
      });
      document.getElementById("ciudadCirculacion").innerHTML = ciudadesVeh;
    },
  });

  //}
}

//trae el ID del cliente sin caracteres especiales y solamente el numero para generar la cotización.
function idWithOutSpecialChars() {
  const numeroInput = document.getElementById("numDocumentoID").value;
  const idWOSpecialChars = numeroInput.replace(/[^0-9]/g, "");
  return idWOSpecialChars;
}

// Obtiene la fecha para la cotizacion de finesa, puede obtener la fecha actual y la fecha un año despues
function obtenerFechaActual(incrementarAnio = false) {
  const fecha = new Date();

  if (incrementarAnio) {
    fecha.setFullYear(fecha.getFullYear() + 1);
  }

  const dia = String(fecha.getDate()).padStart(2, "0");
  const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Los meses van de 0 a 11, por eso se suma 1
  const año = fecha.getFullYear();

  return `${dia}-${mes}-${año}`;
}

function saveQuotations(responses) {
  let dataToDB = [];
  if (Array.isArray(responses) && responses.length >= 1) {
    dataToDB = responses.map((element) => {
      return element;
    });
  }
  return dataToDB;
}

let cotizoFinesaMotos = false;

function cotizarFinesaMotos(ofertasCotizaciones) {
  let cotEnFinesaResponse = [];
  let promisesFinesa = [];

  const headers = new Headers();
  headers.append("Content-Type", "application/json");

  const tipoId = document.getElementById("tipoDocumentoID").value;

  ofertasCotizaciones.forEach((element, index) => {
    let data = {
      fecha_cotizacion: obtenerFechaActual(),
      valor_poliza: element.prima,
      beneficiario_oneroso: false,
      cuotas: element.cuotas,
      fecha_inicio_poliza: obtenerFechaActual(),
      primera_cuota: "min",
      valor_primera_cuota: 0,
      id_ramo: 1,
      valor_mayor: 0,
      fecha_fin_poliza: obtenerFechaActual(true),
      id_insured: idWithOutSpecialChars(),
      typeId: tipoId,
    };

    if (element.cotizada == null || element.cotizada == false) {
      //console.log(element);
      promisesFinesa.push(
        fetch(
          "https://www.grupoasistencia.com/motor_webservice/paymentInstallmentsFinesa",
          // "http://localhost/motorTest/paymentInstallmentsFinesa",
          {
            method: "POST",
            headers: headers,
            redirect: "follow",
            referrerPolicy: "no-referrer",
            body: JSON.stringify(data),
          }
        )
          .then((response) => response.json())
          .then((finesaData) => {
            // Sub Promesa para guardar la data en la BD con relacion a la cotizacion actual.

            finesaData.producto = element.producto;
            finesaData.aseguradora = element.aseguradora;
            finesaData.id_cotizacion = idCotizacion;
            finesaData.identity = element.objFinesa;
            finesaData.cuotas = element.cuotas;
            return fetch(
              "https://www.grupoasistencia.com/motor_webservice/saveDataQuotationsFinesa",
              //"http://localhost/motorTest/saveDataQuotationsFinesa",
              {
                method: "POST",
                headers: headers,
                body: JSON.stringify(finesaData),
              }
            )
              .then((dbResponse) => dbResponse.json())
              .then((dbData) => {
                const elementDiv = document.getElementById(element.objFinesa);
                console.log(dbData);
                console.log(element.aseguradora);
                if (
                  (element.prima < 1000000 &&
                    !(element.aseguradora == "HDI (Antes Liberty)")) ||
                  element.aseguradora == "Bolivar" ||
                  element.aseguradora == "Seguros Bolivar" ||
                  element.aseguradora == "HDI (Antes Liberty)"
                ) {
                  cotizacionesFinesaMotos[index].cotizada = true;
                  elementDiv.innerHTML = `Financiación:<br /> No aplica financiación`;
                } else if (
                  element.aseguradora == "Seguros Bolivar" ||
                  element.aseguradora == "HDI (Antes Liberty)"
                ) {
                  cotizacionesFinesaMotos[index].cotizada = true;
                  elementDiv.innerHTML = `Financiación Aseguradora:<br /> Consulte analista`;
                } else if (
                  dbData?.data?.mensaje.includes("Por políticas de Finesa")
                ) {
                  cotizacionesFinesaMotos[index].cotizada = true;
                  elementDiv.innerHTML = `Financiación:<br /> No aplica financiación`;
                } else if (
                  dbData?.data?.mensaje.includes(
                    "Asegurado no viable para financiacion"
                  )
                ) {
                  cotizacionesFinesaMotos[index].cotizada = true;
                  elementDiv.innerHTML = `Financiación Finesa:<br /> Asegurado no viable para financiación`;
                } else {
                  cotizacionesFinesaMotos[index].cotizada = true;
                  elementDiv.innerHTML = `Financiación Finesa:<br />$${dbData?.data?.data?.val_cuo.toLocaleString(
                    "es-ES"
                  )} (${dbData?.data?.cuotas} Cuotas)`;
                }
                elementDiv.style.display = "block";
                // Agrega el resultado final al array
                cotEnFinesaResponse.push({
                  finesaData: finesaData,
                  dbData: dbData,
                });
                return {
                  finesaData: finesaData,
                  dbData: dbData,
                };
              });
          })
      );
    } else {
      return;
    }
  });

  Promise.all(promisesFinesa)
    .then((results) => {
      cotEnFinesaResponse = saveQuotations(results);
      swal
        .fire({
          title: "¡Cotización a Finesa Finalizada!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
        })
        .then(() => {
          $("#loaderOferta").html("");
          $("#loaderOfertaBox").css("display", "none");
          $("#loaderRecotOferta").html("");
          $("#loaderRecotOfertaBox").css("display", "none");
          if (!cotizoFinesaMotos) {
            document.getElementById(
              "btnReCotizarFallidasMotos"
            ).disabled = false;
            cotizoFinesaMotos = true;
          }
        });
    })
    .catch((error) => {
      console.error("Error en las promesas: ", error);
    })
    .finally(() => {
      //console.log(cotEnFinesaResponse);
    });
}

let actIdentityMotos = "";

// REGISTRA CADA UNA DE LAS OFERTAS COTIZADAS EN LA BD
function registrarOfertaMotos(
  aseguradora,
  prima,
  producto,
  numCotizOferta,
  valorRC,
  PT,
  PP,
  CE,
  GR,
  logo,
  UrlPdf,
  responsabilidad_civil_familiar,
  manual,
  pdf,
  pph
) {
  return new Promise((resolve, reject) => {
    var idCotizOferta = idCotizacion;
    var numDocumentoID = document.getElementById("numDocumentoID").value;
    var placa = document.getElementById("placaVeh").value;
    if (manual == undefined || manual == null) {
      manual = 0;
    }
    $.ajax({
      type: "POST",
      url: "src/insertarOferta.php",
      dataType: "json",
      data: {
        placa: placa,
        idCotizOferta: idCotizOferta,
        numIdentificacion: numDocumentoID,
        aseguradora: aseguradora,
        numCotizOferta: numCotizOferta,
        producto: producto,
        valorPrima: prima,
        valorRC: valorRC,
        PT: PT,
        PP: PP,
        CE: CE,
        GR: GR,
        logo: logo,
        UrlPdf: UrlPdf,
        manual: manual,
        pdf: pdf,
        responsabilidad_civil_familiar: responsabilidad_civil_familiar,
        pph: pph,
        identityElement: actIdentityMotos != "" ? actIdentityMotos : NULL,
      },
      success: function (data) {
        //console.log(data);
        resolve();
      },
      error: function (error) {
        //console.log(error);
        //reject(error);
      },
    });
  });
}

let contCotizacionMotos = 0;
let cotizacionesFinesaMotos = [];

const mostrarOfertaMotos = (
  aseguradora,
  prima,
  producto,
  numCotizOferta,
  RC,
  PT,
  PP,
  CE,
  GR,
  logo,
  UrlPdf
) => {
  var id_intermediario = document.getElementById("idIntermediario").value;
  let datosPermisos = permisosPlantilla;
  var permisos = JSON.parse(datosPermisos);

  function nombreAseguradora($data) {
    $resultado = "";
    if ($data == "Seguros del Estado") {
      $resultado = "Estado";
    } else if ($data == "Seguros Bolivar") {
      $resultado = "Bolivar";
    } else if ($data == "Axa Colpatria") {
      $resultado = "AXA";
    } else if ($data == "HDI Seguros") {
      $resultado = "HDI";
    } else if ($data == "SBS Seguros") {
      $resultado = "SBS";
    } else if ($data == "Allianz Seguros") {
      $resultado = "Allianz";
    } else if ($data == "Equidad Seguros") {
      $resultado = "Equidad";
    } else if ($data == "Equidad") {
      $resultado = "Equidad";
    } else if ($data == "Seguros Mapfre") {
      $resultado = "Mapfre";
    } else if ($data == "Mapfre") {
      $resultado = "Mapfre";
    } else if ($data == "HDI (Antes Liberty)") {
      $resultado = "HDI (Antes Liberty)";
    } else if ($data == "Aseguradora Solidaria") {
      $resultado = "Solidaria";
    } else if ($data == "Seguros Sura") {
      $resultado = "SURA";
    } else if ($data == "Zurich Seguros") {
      $resultado = "Zurich";
    } else if ($data == "Zurich") {
      $resultado = "Zurich";
    } else if ($data == "Previsora Seguros") {
      $resultado = "Previsora";
    } else if ($data == "Solidaria") {
      $resultado = "Solidaria";
    } else {
      $resultado = $data;
    }
    return $resultado;
  }

  var nombreAseguradora = nombreAseguradora(aseguradora);
  var aseguradoraCredenciales = nombreAseguradora + "_C_motos";
  var permisosCredenciales = permisos[aseguradoraCredenciales];

  let calcCuotas =
    prima.replace(/\./g, "") > 800000 && prima.replace(/\./g, "") <= 1400000
      ? 7
      : prima.replace(/\./g, "") > 1400000 && prima.replace(/\./g, "") < 2000000
      ? 9
      : prima.replace(/\./g, "") > 2000000
      ? 11
      : 11;

  let cotOferta = {
    aseguradora: aseguradora,
    objFinesa: aseguradora + "_" + contCotizacionMotos,
    producto: producto,
    prima: Number(prima.replace(/\./g, "")),
    cuotas: calcCuotas,
    cotizada: null,
  };

  actIdentityMotos = aseguradora + "_" + contCotizacionMotos;

  if (
    cotizacionesFinesaMotos.filter((e) => e.objFinesa === cotOferta.objFinesa)
      .length === 0
  ) {
    cotizacionesFinesaMotos.push(cotOferta);
  }

  let cardCotizacion = `
  <div class='col-lg-12'>
  <div class='card-ofertas'>
      <div class='row card-body'>
          <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
          <center>

              <img src='vistas/img/logos/${logo}'>

</center>  

<div class='col-12' style='margin-top:2%;'>
${
  (aseguradora == "Axa Colpatria" ||
    aseguradora == "HDI (Antes Liberty)" ||
    aseguradora == "Equidad" ||
    aseguradora == "Mapfre" ||
    aseguradora == "Seguros Bolivar") &&
  id_intermediario == "78"
    ? `<center>
          <!-- Código para el caso específico de Axa Colpatria, HDI (Antes Liberty), Equidad o Mapfre y id_intermediario no es 78 -->
          <!-- Agrega aquí el contenido específico para estas aseguradoras y el id_intermediario no es 78 -->
          </center>`
    : permisos.Vernumerodecotizacionencadaaseguradora == "x" &&
      permisosCredenciales == "1"
    ? `<center>
          <label class='entidad'>N° Cot: <span style='color:black'>${numCotizOferta}</span></label>
          </center>`
    : ""
}
            </div>
              </div>
                  <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit">
                  <h5 class='entidad' style='font-size: 15px'><b>${aseguradora} - ${producto}</b></h5>
                  <h5 class='precio' style='margin-top: 0px !important;'>Desde $ ${prima}</h5>
                  <p class='title-precio' style='margin: 0 0 3px !important'>Precio (IVA incluido)</p>
                  <div id='${actIdentityMotos}' style='display: none; color: #88d600;'>
              </div>
            </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
              <ul class="list-group">
                  <li class="list-group-item">
                      <span class="badge">* $${RC}</span>
                      Responsabilidad Civil (RCE)
                  </li>
                  <li class="list-group-item">
                      <span class="badge">* ${PT}</span>
                      Pérdida Total Daños y Hurto
                  </li>
                  <li class="list-group-item">
                      <span class="badge">* ${PP}</span>
                      Pérdida Parcial Daños y Hurto
                  </li>
                  <li class="list-group-item">
                      <span class="badge">* ${CE}</span>
                      Conductor elegido
                  </li>
                  <li class="list-group-item">
                      <span class="badge">* ${GR}</span>
                      Servicio de Grúa
                  </li>
              </ul>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-2">
            <div class="selec-oferta">
              <label for="seleccionar">SELECCIONAR</label>&nbsp;&nbsp;
              <input type="checkbox" class="classSelecOferta" name="selecOferta" id="selec${numCotizOferta}${numId}${producto}\" onclick='seleccionarOferta(\"${aseguradora}\", \"${prima}\", \"${producto}\", \"${numCotizOferta}\", this);' />
            </div>
            <div class="recom-oferta">
              <label for="recomendar">RECOMENDAR</label>&nbsp;&nbsp;
              <input type="checkbox" class="classRecomOferta" name="recomOferta" id="recom${numCotizOferta}${numId}${producto}\" onclick='recomendarOferta(\"${aseguradora}\", \"${prima}\", \"${producto}\", \"${numCotizOferta}\", this);' />
            </div>
          </div>`;
  if (
    (aseguradora == "Seguros Bolivar" || aseguradora == "Axa Colpatria") &&
    permisosCredenciales == "1"
  ) {
    cardCotizacion += `
              <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
                  <button type="button" class="btn btn-info" id="btnAsegPDF${numCotizOferta}${numId}\" onclick='verPdfOferta(\"${aseguradora}\", \"${numCotizOferta}\", \"${numId}\", \"${id_intermediario}\");'>
<div id="verPdf${numCotizOferta}${numId}\">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
</button>
              </div>`;
  } else if (
    aseguradora == "Seguros del Estado" &&
    UrlPdf !== null &&
    permisosCredenciales == "1"
  ) {
    cardCotizacion += `
<div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
<button type="button" class="btn btn-info" id="btnAsegPDF${numCotizOferta}${numId}\" onclick='verPdfEstado(\"${aseguradora}\", \"${numCotizOferta}\", \"${numId}\", \"${UrlPdf}\");'>
  <div id="verPdf${numCotizOferta}${numId}\">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
</button>
</div>`;
  } else if (aseguradora == "Solidaria" && permisosCredenciales == "1") {
    cardCotizacion += `
<div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  <button id="solidaria-pdf" type="button" class="btn btn-info" onclick='verPdfSolidaria(${numCotizOferta})'>
      <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  </button>
</div>`;
  } else if (aseguradora == "Zurich" && permisosCredenciales == "1") {
    cardCotizacion += `
<div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  <button id="solidaria-pdf${numCotizOferta}" type="button" class="btn btn-info" onclick='verPdfZurich(${numCotizOferta})'>
      <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  </button>
</div>`;
  } else if (
    aseguradora == "Previsora Seguros" &&
    permisosCredenciales == "1"
  ) {
    cardCotizacion += `
<div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  <button id="previsora-pdf${numCotizOferta}" type="button" class="btn btn-info" onclick='verPdfPrevisora(${numCotizOferta})'>
      <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  </button>
</div>`;
  } else if (aseguradora == "HDI Seguros" && permisosCredenciales == "1") {
    cardCotizacion += `
<div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  <button id="Hdi-pdf${numCotizOferta}" type="button" class="btn btn-info" onclick='verPdfHdi("${numCotizOferta}")'>
      <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  </button>
</div>`;
  }
  cardCotizacion += `
              </div>
          </div>
      </div>
  </div>
`;
  $("#cardCotizacion").append(cardCotizacion);
};

function validarOfertasMotos(ofertas, aseguradora, exito) {
  // console.log(ofertas);
  // console.log(exito);
  //console.log(aseguradora);
  let contadorPorEntidad = {};
  $responsabilidadCivilFamiliar = ofertas[0].responsabilidad_civil_familiar;
  ofertas.forEach((oferta, i) => {
    // console.log(oferta);
    var numCotizacion = oferta.numero_cotizacion;
    // console.log(numCotizacion);
    var precioOferta = oferta.precio;
    // console.log(precioOferta);
    if (oferta == null) return;
    if (numCotizacion == null && precioOferta == "0") return;
    if (precioOferta.length <= 3) return;
    // contadorOfertas++;   // Variable para contar el número de ofertas
    contadorPorEntidad[oferta.entidad] =
      (contadorPorEntidad[oferta.entidad] || 0) + 1;
    //console.log(contadorPorEntidad);
    // console.log(
    //   `Entidad: ${oferta.entidad}, Contador: ${
    //     contadorPorEntidad[oferta.entidad]
    //   }`
    // );
    contCotizacionMotos++;
    mostrarOfertaMotos(
      oferta.entidad,
      oferta.precio,
      oferta.producto,
      oferta.numero_cotizacion,
      oferta.responsabilidad_civil,
      oferta.cubrimiento,
      oferta.deducible,
      oferta.conductores_elegidos,
      oferta.servicio_grua,
      oferta.imagen,
      oferta.pdf
    );

    registrarOfertaMotos(
      oferta.entidad,
      oferta.precio,
      oferta.producto,
      oferta.numero_cotizacion,
      oferta.responsabilidad_civil,
      oferta.cubrimiento,
      oferta.deducible,
      oferta.conductores_elegidos,
      oferta.servicio_grua,
      oferta.imagen,
      oferta.pdf,
      $responsabilidadCivilFamiliar,
      0,
      null,
      oferta.pph
    );
  });
  // Llamada a la función registrarNumeroOfertas para cada entidad
  Object.entries(contadorPorEntidad).forEach(([entidad, contador]) => {
    var idCotizOferta = idCotizacion;
    registrarNumeroOfertasMotos(entidad, contador, idCotizOferta, exito);
  });
  return contadorPorEntidad;
}

//VERSION DEFINITIVA "validarProblema()"" COTIZAR.JS
function validarProblemaMotos(aseguradora, ofertas) {
  var idCotizOferta = idCotizacion;
  //console.log(ofertas);

  // Verificar si ofertas es un array
  if (Array.isArray(ofertas)) {
    ofertas.forEach((oferta) => {
      // Obtener mensajes de la oferta
      var mensajes = oferta.Mensajes || [];

      // Verificar si mensajes es un array y tiene al menos un mensaje
      if (Array.isArray(mensajes) && mensajes.length > 0) {
        // Concatenar mensajes en un solo párrafo
        var mensajeConcatenado = mensajes.join(", ");

        // Realizar la petición AJAX con los datos
        $.ajax({
          type: "POST",
          url: "src/insertarAlerta.php",
          dataType: "json",
          data: {
            aseguradora: aseguradora,
            cantidadOfertas: 0,
            cotizacion: idCotizOferta,
            exito: 0,
            mensaje: mensajeConcatenado,
          },
          success: function (data) {
            var datos = data.Data;
            //console.log(datos);
          },
          error: function (error) {
            console.log("Error en validarProblemaMotos moto", error);
          },
        });
      }
    });
  } else if (
    ofertas &&
    ofertas.jsonZurich &&
    typeof ofertas.jsonZurich === "object"
  ) {
    // Caso específico para la estructura de Zurich
    var mensajesZurich = ofertas.jsonZurich.result.messages || [];
    if (Array.isArray(mensajesZurich) && mensajesZurich.length > 0) {
      // Concatenar mensajes en un solo párrafo
      var mensajeConcatenadoZurich = mensajesZurich
        .map((m) => m.messageText)
        .join(", ");
      // Realizar la petición AJAX con los datos
      $.ajax({
        type: "POST",
        url: "src/insertarAlerta.php",
        dataType: "json",
        data: {
          aseguradora: aseguradora,
          cantidadOfertas: 0,
          cotizacion: idCotizOferta,
          exito: 0,
          mensaje: mensajeConcatenadoZurich,
        },
        success: function (data) {
          var datos = data.Data;
          //console.log(datos);
        },
        error: function (error) {
          console.log(
            "error en validar en else de validarProblemaMotos moto",
            error
          );
        },
      });
    }
  }
}

function registrarNumeroOfertasMotos(entidad, contador, numCotizacion, exito) {
  $.ajax({
    type: "POST",
    url: "src/insertarAlerta.php",
    dataType: "json",
    data: {
      aseguradora: entidad,
      cantidadOfertas: contador,
      cotizacion: numCotizacion,
      exito: exito,
      mensaje: "",
    },
    success: function (data) {
      var datos = data.Data;
      var message = data.Message;
      var success = data.Success;
      //console.log("Alerta Insertada Validar Numeros", datos);
      //resolve();
    },
    error: function (error) {
      console.log(
        "Error",
        "Alerta Insertada Validar Problemas Motos, Registrar Numeros Ofertas",
        error
      );
      reject(error);
    },
  });
}

var idCotizacion = "";
var contErrProtocoloCotizar = 0;

var aseguradorasFallidas = [];
var aseguradorasIntentadas = [];
var primerIntentoRealizado = false;

const agregarAseguradoraFallidaMotos = (_aseguradora) => {
  const result = aseguradorasFallidas.find(
    (aseguradoras) => aseguradoras == _aseguradora
  );
  if (result !== undefined) return;
  aseguradorasFallidas.push(_aseguradora);
};

const eliminarAseguradoraFallidaMotos = (_aseguradora) => {
  aseguradorasFallidas = aseguradorasFallidas.filter(
    (aseguradora) => aseguradora !== _aseguradora
  );
};

const comprobarFallida = (_aseguradora) => {
  const result = aseguradorasFallidas.find(
    (aseguradoras) => aseguradoras == _aseguradora
  );
  if (result !== undefined) return true;

  return false;
};

// document
//   .querySelector("#btnReCotizarFallidasMotos")
//   .addEventListener("click", () => {
//     cotizarOfertasMotos();
//   });

$(
  "#dianacimiento, #mesnacimiento, #anionacimiento, #dianacimientoRepresentante, #mesnacimientoRepresentante, #anionacimientoRepresentante"
).select2({
  theme: "bootstrap fecnacimiento",
  language: "es",
  width: "100%",
});

// console.log(permisosPlantilla);

// Variable para controlar la recotización
var recotizacionIntentoRealizado = false;

function cotizarOfertasMotos() {
  var codigoFasecolda1 = document.getElementById("txtFasecolda");
  var contenido = codigoFasecolda1.value;

  // Obtener el cuarto y quinto dígito de la variable contenido
  var cuartoDigito = contenido.charAt(3);
  var quintoDigito = contenido.charAt(4);

  // Verificar si el cuarto dígito es igual a 0 y eliminarlo si es así
  var condicional;
  if (cuartoDigito === "0") {
    condicional = quintoDigito;
  } else {
    // Concatenar los dígitos en un solo número
    condicional = cuartoDigito + quintoDigito;
  }

  var tipoUsoVehiculo = document.getElementById("txtTipoUsoVehiculo").value;
  if (tipoUsoVehiculo == "Trabajo") {
    var restriccion = "";
    if (rolAsesor == 19) {
      restriccion =
        "Lo sentimos, no puedes realizar cotizaciones para vehículo de trabajo por este cotizador. Para hacerlo debes comunicarte con el Equipo de Asesores Freelance de Grupo Asistencia, quienes podrán ayudarte a cotizar de manera manual con diferentes aseguradoras.";
    } else {
      restriccion =
        "Lo sentimos, no puedes realizar cotizaciones para vehículo de trabajo por este cotizador.";
    }
    Swal.fire({
      icon: "error",
      confirmButtonText: "Cerrar",
      text: restriccion,
    }).then(() => {
      // Agregar un retraso antes de recargar la página (por ejemplo, 2 segundos)
      setTimeout(() => {
        // Recargar la página después del retraso
        //location.reload();
      }, 2000); // 2000 milisegundos = 2 segundos
    });
    // Salir del código aquí para evitar la ejecución del resto del código
    return;
  }
  var tipoServicio = document.getElementById("txtTipoServicio").value;
  if (tipoServicio == "11" || tipoServicio == "12") {
    var restriccion = "";
    if (rolAsesor == 19) {
      restriccion =
        "Lo sentimos, no puedes realizar cotizaciones para el tipo de servicio público o intermunicipal por este cotizador. Para hacerlo debes comunicarte con el Equipo de Asesores Freelance de Grupo Asistencia, quienes podrán ayudarte a cotizar de manera manual con diferentes aseguradoras.";
    } else {
      restriccion =
        "Lo sentimos, no puedes realizar cotizaciones para el tipo de servicio público o intermunicipal por este cotizador.";
    }
    Swal.fire({
      icon: "error",
      confirmButtonText: "Cerrar",
      text: restriccion,
    }).then(() => {
      // Agregar un retraso antes de recargar la página (por ejemplo, 2 segundos)
      setTimeout(() => {
        // Recargar la página después del retraso
        // location.reload();
      }, 2000); // 2000 milisegundos = 2 segundos
    });
    // Salir del código aquí para evitar la ejecución del resto del código
    return;
  }

  var fasecoldaVeh = document.getElementById("txtFasecolda").value;
  var valorfasecoldaVeh = document.getElementById("txtValorFasecolda").value;
  var modelovehiculo = document.getElementById("txtModeloVeh").value;
  var marca = document.getElementById("txtMarcaVeh").value;
  var linea = document.getElementById("txtReferenciaVeh").value;

  // var hdi = document.getElementById("hdiseguros").value;
  // var estado = document.getElementById("estadoseguros").value;

  // var ofinanciera = document.getElementById("obligacionfinanciera").value;

  //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  var placa = document.getElementById("placaVeh").value;
  var esCeroKmSi = document.getElementById("txtEsCeroKmSi").checked;
  var esCeroKm = esCeroKmSi.toString();
  var esCeroKmInt = esCeroKmSi == true ? 1 : 0;

  var idCliente = document.getElementById("idCliente").value;
  var tipoDocumentoID = document.getElementById("tipoDocumentoID").value;
  var numDocumentoID = document.getElementById("numDocumentoID").value;
  var Nombre = document.getElementById("txtNombres").value;
  var Apellido1 = document.getElementById("txtApellidos").value;
  var Apellido2 = "";

  //! Agregar a Motos y Pesados START
  let razonSocial = document.getElementById("txtRazonSocial").value;
  let digitoVerif = document.getElementById("txtDigitoVerif").value;
  // Representante Legal START
  let tipoDocRep = document.getElementById(
    "tipoDocumentoIDRepresentante"
  ).value;
  let numDocRep = document.getElementById("numDocumentoIDRepresentante").value;
  let nombresRep = document.getElementById("txtNombresRepresentante").value;
  let apellidosRep = document.getElementById("txtApellidosRepresentante").value;
  let diaRep = document.getElementById("dianacimientoRepresentante").value;
  let mesRep = document.getElementById("mesnacimientoRepresentante").value;
  let anioRep = document.getElementById("anionacimientoRepresentante").value;
  let fechaNacimientoRep = anioRep + "-" + mesRep + "-" + diaRep;
  let generoRep = document.getElementById("generoRepresentante").value;
  let estCivRep = document.getElementById("estadoCivilRepresentante").value;
  let correoRep = document.getElementById("txtCorreoRepresentante").value;
  let celRep = document.getElementById("txtCelularRepresentante").value;
  // Representante Legal END
  //! Agregar a Motos y Pesados END

  var dia = document.getElementById("dianacimiento").value;
  var mes = document.getElementById("mesnacimiento").value;
  var anio = document.getElementById("anionacimiento").value;
  var FechaNacimiento = anio + "-" + mes + "-" + dia;

  var Genero = document.getElementById("genero").value;

  var estadoCivil = document.getElementById("estadoCivil").value;
  var celularAseg = document.getElementById("celularAseg").value;
  var emailAseg = document.getElementById("emailAseg").value;
  var direccionAseg = document.getElementById("direccionAseg").value;

  var CodigoClase = document.getElementById("CodigoClase").value;
  var CodigoMarca = document.getElementById("CodigoMarca").value;
  var CodigoLinea = document.getElementById("CodigoLinea").value;
  var claseVeh = document.getElementById("txtClaseVeh").value;

  var LimiteRC = document.getElementById("LimiteRC").value;
  var CoberturaEstado = document.getElementById("CoberturaEstado").value;
  var ValorAccesorios = document.getElementById("ValorAccesorios").value;
  var CodigoVerificacion = document.getElementById("CodigoVerificacion").value;
  var AniosSiniestro = document.getElementById("AniosSiniestro").value;
  var AniosAsegurados = document.getElementById("AniosAsegurados").value;
  var NivelEducativo = document.getElementById("NivelEducativo").value;
  var Estrato = document.getElementById("Estrato").value;

  var tipoUsoVehiculo = document.getElementById("txtTipoUsoVehiculo").value;
  var tipoServicio = document.getElementById("txtTipoServicio").value;
  var DptoCirculacion = document.getElementById("DptoCirculacion").value;
  var ciudadCirculacion = document.getElementById("ciudadCirculacion").value;
  var isBenefOneroso = $("input:radio[name=oneroso]:checked").val(); // Valida que alguno de los 2 este selecionado
  var benefOneroso = document.getElementById("benefOneroso").value;

  /**
   * Variables de AXA
   */
  var cre_axa_sslcertfile = document.getElementById(
    "cre_axa_sslcertfile"
  ).value;
  var cre_axa_sslkeyfile = document.getElementById("cre_axa_sslkeyfile").value;

  var cre_axa_passphrase = document.getElementById("cre_axa_passphrase").value;
  var cre_axa_codigoDistribuidor = document.getElementById(
    "cre_axa_codigoDistribuidor"
  ).value;

  var cre_axa_idTipoDistribuidor = document.getElementById(
    "cre_axa_idTipoDistribuidor"
  ).value;
  var cre_axa_codigoDivipola = document.getElementById(
    "cre_axa_codigoDivipola"
  ).value;

  var cre_axa_canal = document.getElementById("cre_axa_canal").value;
  var cre_axa_validacionEventos = document.getElementById(
    "cre_axa_validacionEventos"
  ).value;
  var url_axa = document.getElementById("url_axa").value;
  var motos_productos = document.getElementById("motos_productos").value;
  /**
   * Variables para Allianz
   */

  var cre_alli_sslcertfile = document.getElementById(
    "cre_alli_sslcertfile"
  ).value;
  var cre_alli_sslkeyfile = document.getElementById(
    "cre_alli_sslkeyfile"
  ).value;
  var cre_alli_passphrase = document.getElementById(
    "cre_alli_passphrase"
  ).value;
  var cre_alli_partnerid = document.getElementById("cre_alli_partnerid").value;
  var cre_alli_agentid = document.getElementById("cre_alli_agentid").value;
  var cre_alli_partnercode = document.getElementById(
    "cre_alli_partnercode"
  ).value;
  var cre_alli_agentcode = document.getElementById("cre_alli_agentcode").value;

  var aseguradoras_motos_autorizar = JSON.parse(
    document.getElementById("aseguradoras_motos").value
  );
  //console.log(aseguradoras_motos_autorizar);

  if (ciudadCirculacion.length == 4) {
    ciudadCirculacion = "0" + ciudadCirculacion;
  } else if (ciudadCirculacion.length == 3) {
    ciudadCirculacion = "00" + ciudadCirculacion;
  }

  //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  //! Agregar a Motos y Pesados START

  let typeQuery =
    tipoDocumentoID != "2"
      ? placa != "" &&
        tipoDocumentoID != "" &&
        numDocumentoID != "" &&
        dia != "" &&
        mes != "" &&
        anio != "" &&
        Nombre != "" &&
        Apellido1 != "" &&
        Genero != "" &&
        estadoCivil != ""
      : placa != "" &&
        digitoVerif != "" &&
        razonSocial != "" &&
        numDocRep != "" &&
        nombresRep != "" &&
        apellidosRep != "" &&
        generoRep != "" &&
        estCivRep != "";
  //correoRep != "" &&
  //celRep != "";

  //! Agregar a Motos y Pesados END

  if (
    fasecoldaVeh != "" &&
    valorfasecoldaVeh != "" &&
    modelovehiculo != "" &&
    marca != "" &&
    linea != ""
  ) {
    if (typeQuery) {
      $("#loaderOferta").html(
        '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Consultando Ofertas...</strong>'
      );
      var myHeaders = new Headers();
      myHeaders.append("Content-Type", "application/json");

      // console.log("Condicional en Cotizar Ofertas Motos: ", condicional);
      var raw = {
        Placa: placa,
        ceroKm: esCeroKm,
        TipoIdentificacion: tipoDocumentoID,
        NumeroIdentificacion: numDocumentoID,
        Nombre: Nombre,
        Apellido: Apellido1,
        Genero: Genero,
        FechaNacimiento: FechaNacimiento,
        EstadoCivil: estadoCivil,
        NumeroTelefono: celularAseg,
        Direccion: direccionAseg,
        Email: emailAseg,
        ZonaCirculacion: DptoCirculacion,
        CodigoMarca: CodigoMarca,
        CodigoLinea: CodigoLinea,
        CodigoClase: condicional,
        CodigoFasecolda: fasecoldaVeh,
        Modelo: modelovehiculo,
        ValorAsegurado: valorfasecoldaVeh,
        LimiteRC: LimiteRC,
        Cobertura: CoberturaEstado,
        ValorAccesorios: ValorAccesorios,
        CiudadBolivar: ciudadCirculacion,
        tipoServicio: tipoServicio,
        CodigoVerificacion: CodigoVerificacion,
        Apellido2: Apellido2,
        AniosSiniestro: AniosSiniestro,
        AniosAsegurados: AniosAsegurados,
        NivelEducativo: NivelEducativo,
        Estrato: Estrato,
        AXA: {
          cre_axa_sslcertfile: cre_axa_sslcertfile,
          cre_axa_sslkeyfile: cre_axa_sslkeyfile,
          cre_axa_passphrase: cre_axa_passphrase,
          cre_axa_codigoDistribuidor: cre_axa_codigoDistribuidor,
          cre_axa_idTipoDistribuidor: cre_axa_idTipoDistribuidor,
          cre_axa_codigoDivipola: cre_axa_codigoDivipola,
          cre_axa_canal: cre_axa_canal,
          cre_axa_validacionEventos: cre_axa_validacionEventos,
          url_axa: url_axa,
          motos_productos: motos_productos,
        },
        ALLIANZ: {
          cre_alli_sslcertfile: cre_alli_sslcertfile,
          cre_alli_sslkeyfile: cre_alli_sslkeyfile,
          cre_alli_passphrase: cre_alli_passphrase,
          cre_alli_partnerid: cre_alli_partnerid,
          cre_alli_agentid: cre_alli_agentid,
          cre_alli_partnercode: cre_alli_partnercode,
          cre_alli_agentcode: cre_alli_agentcode,
        },
      };

      //! Agregar a Motos y Pesados START

      if (tipoDocumentoID == 2) {
        raw.razonSocial = razonSocial;
        raw.digitoVerif = digitoVerif;
        raw.tipoDocRep = tipoDocRep;
        raw.numDocRep = numDocRep;
        raw.nombresRep = nombresRep;
        raw.apellidosRep = apellidosRep;
        raw.fechaNacimientoRep = fechaNacimientoRep;
        raw.generoRep = generoRep;
        raw.estCivRep = estCivRep;
        raw.correoRep = correoRep == "" ? null : correoRep;
        raw.celRep = celRep == "" ? null : celRep;
      }

      //! Agregar a Motos y Pesados END

      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: JSON.stringify(raw),
        redirect: "follow",
      };

      if (!primerIntentoRealizado && !recotizacionIntentoRealizado) {
        // Primer intento de cotización
        const aseguradorasCoti = Object.keys(
          aseguradoras_motos_autorizar
        ).filter(
          (aseguradora) =>
            aseguradoras_motos_autorizar[aseguradora]["A"] === "1"
        );

        // const aseguradoras = ['Allianz', 'AXA', 'Bolivar', 'Equidad', 'Estado', 'HDI', 'Liberty', 'Mapfre', 'Previsora', 'SBS', 'Solidaria', 'Zurich'];
        const tbody = document.querySelector("#tablaResumenCot tbody");

        aseguradorasCoti.forEach((aseguradora) => {
          // Crear una fila
          const fila = document.createElement("tr");
          fila.id = aseguradora; // Establecer el id del tr igual al nombre de la aseguradora

          // Crear la celda de nombre de aseguradora
          const celdaNombre = document.createElement("td");
          celdaNombre.textContent = aseguradora;
          celdaNombre.id = aseguradora; // Establecer el id igual al nombre de la aseguradora
          fila.appendChild(celdaNombre);

          // Crear la celda de respuesta
          const celdaRespuesta = document.createElement("td");
          celdaRespuesta.className = "text-center";
          celdaRespuesta.id = `${aseguradora}Response`;
          fila.appendChild(celdaRespuesta);

          // Crear la celda de productos cotizados
          const celdaProductos = document.createElement("td");
          celdaProductos.className = "text-center";
          celdaProductos.id = `${aseguradora}Products`;
          fila.appendChild(celdaProductos);

          // Crear la celda de observaciones
          const celdaObservaciones = document.createElement("td");
          celdaObservaciones.id = `${aseguradora}Observation`;
          fila.appendChild(celdaObservaciones);

          // Agregar la fila al cuerpo de la tabla
          tbody.appendChild(fila);

          const celdaResponse = document.getElementById(
            `${aseguradora}Response`
          );

          // Agregar un elemento de carga (por ejemplo, un gif) en la celda de respuesta
          const loadingElement = document.createElement("img");
          loadingElement.src = "vistas/img/plantilla/loader-update.gif"; // Reemplaza con la ruta correcta del gif
          loadingElement.alt = "Cargando...";

          // Establecer el tamaño deseado del gif (por ejemplo, 50px x 50px)
          loadingElement.style.width = "22px";
          loadingElement.style.height = "22px";

          // Limpiar cualquier contenido existente en la celda de respuesta
          celdaResponse.innerHTML = "";

          // Agregar el elemento de carga a la celda de respuesta
          celdaResponse.appendChild(loadingElement);
        });
        //masRE();
        primerIntentoRealizado = true;
        $.ajax({
          type: "POST",
          url: "src/insertarCotizacion.php",
          dataType: "json",
          data: {
            placa: placa,
            esCeroKm: esCeroKmInt,
            idCliente: idCliente,
            tipoDocumento: tipoDocumentoID,
            numIdentificacion: numDocumentoID,
            Nombre: Nombre,
            Apellido: Apellido1,
            FechaNacimiento: FechaNacimiento,
            Genero: Genero,
            EstadoCivil: estadoCivil,
            Celular: "",
            Correo: "",
            direccionAseg: direccionAseg,
            CodigoClase: condicional,
            Clase: claseVeh,
            Marca: marca,
            Modelo: modelovehiculo,
            Linea: linea,
            Fasecolda: fasecoldaVeh,
            ValorAsegurado: valorfasecoldaVeh,
            tipoUsoVehiculo: tipoUsoVehiculo,
            tipoServicio: tipoServicio,
            Departamento: DptoCirculacion,
            Ciudad: ciudadCirculacion,
            benefOneroso: benefOneroso,
            idCotizacion: idCotizacion,
            credenciales: aseguradorasCredencialesMotos,
            razonSocial: razonSocial,
            digitoVerif: digitoVerif,
            tipoDocRep: tipoDocRep,
            numDocRep: numDocRep,
            nombresRep: nombresRep,
            apellidosRep: apellidosRep,
            fechaNacimientoRep: fechaNacimientoRep,
            generoRep: generoRep,
            estCivRep: estCivRep,
            correoRep: correoRep,
            celRep: celRep,
          },
          cache: false,
          success: function (data) {
            const contenParrilla = document.querySelector("#contenParrilla");
            parrillaCotizaciones.style.display = "block";
            contenParrilla.style.display = "block";
            const btnCotizar = document.getElementById("btnCotizarMotos");
            btnCotizar.disabled = true;
            idCotizacion = data.id_cotizacion;
            raw.cotizacion = idCotizacion;
            // console.log(idCotizacion)

            var requestOptions = {
              method: "POST",
              headers: myHeaders,
              body: JSON.stringify(raw),
              redirect: "follow",
            };

            let cont = [];

            const mostrarAlertaCotizacionExitosa = (aseguradora, contador) => {
              if (aseguradora == "Estado2") {
                aseguradora = "Estado";
              }

              // Obtener la primera clave del objeto
              const primeraClave = Object.keys(contador)[0];

              // Obtener el valor asociado a la primera clave
              const contadorOfertas = contador[primeraClave];

              // Obtener la referencia de la tabla
              const tablaResumenCotBody = document.querySelector(
                "#tablaResumenCot tbody"
              );

              // Verificar si ya existe la fila
              const filaExistente = document.getElementById(aseguradora);
              if (filaExistente) {
                // Acceder directamente a las celdas de la fila existente
                const celdaContador = filaExistente.cells[2]; // Tercera celda de la fila
                const celdaCotizo = filaExistente.cells[1]; // Segunda celda de la fila
                const celdaResponse = filaExistente.cells[3]; // Cuarta celda de la fila
                // Actualizar los valores según sea necesario
                const contadorActualTexto = celdaContador.textContent.trim();
                // Verificar si el texto está vacío o no es un número
                const contadorActual =
                  contadorActualTexto === ""
                    ? 0
                    : parseInt(contadorActualTexto, 10);
                const nuevoContador = contadorActual + contadorOfertas;

                if (contadorActualTexto !== "") {
                  celdaContador.textContent = nuevoContador;
                  celdaCotizo.innerHTML =
                    '<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>';
                  celdaResponse.textContent = "Cotización exitosa";
                } else {
                  celdaContador.textContent = nuevoContador;
                  celdaCotizo.innerHTML =
                    '<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>';
                  celdaResponse.textContent = "Cotización exitosa";
                }
              } else {
                // Si la fila no existe, puedes agregarla
                const nuevaFila = document.createElement("tr");
                nuevaFila.id = aseguradora;
                nuevaFila.innerHTML = `<td>${aseguradora}</td>
                      <td style="text-align: center;"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i></td>
                      <td style="text-align: center;">${contadorOfertas}</td>
                      <td>Nuevo Valor para Response</td>
                      <td>Nuevo Valor para Products</td>
                      <td>Nuevo Valor para Observation</td>`;
                tablaResumenCotBody.appendChild(nuevaFila);
              }
            };

            const mostrarAlertarCotizacionFallida = (aseguradora, mensaje) => {
              if (aseguradora == "Estado2") {
                aseguradora = "Estado";
              }
              //console.log(aseguradora);
              //console.log(mensaje);
              // Referecnia de la tabla
              const tablaResumenCotBody = document.querySelector(
                "#tablaResumenCot tbody"
              );

              // Verificar si ya existe una fila para la aseguradora
              const filaExistente = document.getElementById(aseguradora);
              // desactive
              // console.log(filaExistente)
              if (filaExistente) {
                // Si la fila existe, actualiza el mensaje de observaciones

                // Acceder directamente a las celdas de la fila existente
                const celdaContador = filaExistente.cells[2]; // Tercera celda de la fila
                const celdaCotizo = filaExistente.cells[1]; // Segunda celda de la fila
                const celdaResponse = filaExistente.cells[3]; // Cuarta celda de la fila

                if (celdaResponse.textContent.trim() !== "Cotización exitosa") {
                  celdaContador.textContent = 0;
                  celdaCotizo.innerHTML =
                    '<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i>';
                  celdaResponse.textContent = mensaje;
                }
                // Verifica si el mensaje es diferente antes de actualizar
                // if (observacionesActuales !== mensaje) {
                //   celdaObservaciones.textContent = mensaje;
                // } else {
                //   console.log(${aseguradora} tiene alertas iguales: "${observacionesActuales}" === "${mensaje}");
                // }
              } else {
                // Si no existe, crea una nueva fila
                const nuevaFila = document.createElement("tr");
                nuevaFila.setAttribute("data-aseguradora", aseguradora);
                nuevaFila.innerHTML = `<td>${aseguradora}</td>
                          <td style="text-align: center;"><i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i></td>
                          <td style="text-align: center;">0</td> <!-- Valor predeterminado para 'Productos cotizados' -->
                          <td>${mensaje}</td> <!-- Valor predeterminado para 'Observaciones' -->`;

                // Agregar la fila a la tabla
                tablaResumenCotBody.appendChild(nuevaFila);
              }
            };

            // console.log(aseguradorasCoti); // Esto imprimirá el array con los nombres de aseguradoras autorizadas

            aseguradorasCoti.forEach((aseguradora) => {
              let url;
              // if (aseguradora === "SBS") {
              //   url = https://grupoasistencia.com/motor_webservice_tst/SBS;
              // } else

              // if (aseguradora === "Liberty") {
              //   url = https://grupoasistencia.com/motor_webservice_tst/Liberty;
              // } else

              // if (aseguradora === "AXA") {
              //   url = https://grupoasistencia.com/motor_webservice_tst/AXA_tst;
              // } else

              // if (aseguradora === "Allianz") {
              //   url = https://grupoasistencia.com/motor_webservice_tst2/Allianz_motos;
              // } else

              if (aseguradora === "Zurich") {
                const planes = ["BASIC", "MEDIUM", "FULL"];
                planes.forEach((plan) => {
                  let body = JSON.parse(requestOptions.body);
                  body.plan = plan;
                  body.Email = "@gmail.com";
                  body.Email2 = Math.round(Math.random() * 999999) + body.Email;
                  requestOptions.body = JSON.stringify(body);
                  url = `https://grupoasistencia.com/motor_webservice_tst2/Zurich?callback=myCallback`;

                  cont.push(
                    fetch(url, requestOptions)
                      .then((res) => {
                        if (!res.ok) throw Error(res.statusText);
                        return res.json();
                      })
                      .then((ofertas) => {
                        if (typeof ofertas.Resultado !== "undefined") {
                          validarProblemaMotos("Zurich", ofertas);
                          agregarAseguradoraFallidaMotos(plan);
                          ofertas.Mensajes.forEach((mensaje) => {
                            mostrarAlertarCotizacionFallida(Zurich, mensaje);
                          });
                        } else {
                          const contadorPorEntidad = validarOfertasMotos(
                            ofertas,
                            "Zurich",
                            1
                          );
                          mostrarAlertaCotizacionExitosa(
                            Zurich,
                            contadorPorEntidad
                          );
                        }
                      })
                      .catch((err) => {
                        agregarAseguradoraFallidaMotos(plan);
                        mostrarAlertarCotizacionFallida(
                          "Zurich",
                          "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                        );
                        console.error(err);
                      })
                  );
                });
                return; // Salir del bucle después de procesar Zurich
              } else if (aseguradora === "HDI (Antes Liberty)") {
                const planes = ["INTEGRAL", "BASICO + PT", "FULL"];
                planes.forEach((plan) => {
                  let body = JSON.parse(requestOptions.body);
                  body.plan = plan;
                  requestOptions.body = JSON.stringify(body);
                  url = `https://grupoasistencia.com/motor_webservice/Liberty_motos`;
                  //url = `https://grupoasistencia.com/motor_webservice/${aseguradora}_motos`;
                  cont.push(
                    fetch(url, requestOptions)
                      .then((res) => {
                        if (!res.ok) throw Error(res.statusText);
                        return res.json();
                      })
                      .then((ofertas) => {
                        if (typeof ofertas[0].Resultado !== "undefined") {
                          validarProblemaMotos(aseguradora, ofertas);
                          agregarAseguradoraFallidaMotos(aseguradora);
                          ofertas[0].Mensajes.forEach((mensaje) => {
                            mostrarAlertarCotizacionFallida(
                              aseguradora,
                              mensaje
                            );
                          });
                        } else {
                          const contadorPorEntidad = validarOfertasMotos(
                            ofertas,
                            "HDI (Antes Liberty)",
                            1
                          );
                          mostrarAlertaCotizacionExitosa(
                            "HDI (Antes Liberty)",
                            contadorPorEntidad
                          );
                        }
                      })
                      .catch((err) => {
                        agregarAseguradoraFallidaMotos("HDI (Antes Liberty)");
                        mostrarAlertarCotizacionFallida(
                          aseguradora,
                          "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                        );
                        console.error(err);
                      })
                  );
                });
                return;
              } else if (aseguradora === "Estado") {
                const aseguradorasEstado = ["Estado", "Estado2"]; // Agrega más aseguradoras según sea necesario
                aseguradorasEstado.forEach((aseguradora) => {
                  let successAseguradora = true;
                  cont.push(
                    fetch(
                      `https://grupoasistencia.com/motor_webservice_tst2/${aseguradora}`,
                      requestOptions
                    )
                      .then((res) => {
                        if (!res.ok) throw Error(res.statusText);
                        return res.json();
                      })
                      .then((ofertas) => {
                        let result = [];
                        result.push(ofertas);
                        if (typeof result[0].Resultado !== "undefined") {
                          validarProblemaMotos(aseguradora, result);
                          agregarAseguradoraFallidaMotos("Estado");
                          result[0].Mensajes.forEach((mensaje) => {
                            mostrarAlertarCotizacionFallida(
                              aseguradora,
                              mensaje
                            );
                          });
                        } else {
                          const contadorPorEntidad = validarOfertasMotos(
                            result,
                            aseguradora,
                            1
                          );
                          if (successAseguradora) {
                            mostrarAlertaCotizacionExitosa(
                              aseguradora,
                              contadorPorEntidad
                            );
                            successAseguradora = false;
                          }
                        }
                      })
                      .catch((err) => {
                        agregarAseguradoraFallidaMotos("Estado");
                        mostrarAlertarCotizacionFallida(
                          aseguradora,
                          "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                        );
                        console.error(err);
                      })
                  );
                });
                return; // Salir del bucle después de procesar Estado
              } else {
                // Construir la URL de la solicitud para cada aseguradora
                url = `https://grupoasistencia.com/motor_webservice/${aseguradora}_motos`;
              }

              // Realizar la solicitud fetch y agregar la promesa al array
              cont.push(
                fetch(url, requestOptions)
                  .then((res) => {
                    if (!res.ok) throw Error(res.statusText);
                    return res.json();
                  })
                  .then((ofertas) => {
                    if (typeof ofertas[0].Resultado !== "undefined") {
                      agregarAseguradoraFallidaMotos(aseguradora);
                      validarProblemaMotos(aseguradora, ofertas);
                      ofertas[0].Mensajes.forEach((mensaje) => {
                        mostrarAlertarCotizacionFallida(aseguradora, mensaje);
                      });
                    } else {
                      const contadorPorEntidad = validarOfertasMotos(
                        ofertas,
                        aseguradora,
                        1
                      );
                      mostrarAlertaCotizacionExitosa(
                        aseguradora,
                        contadorPorEntidad
                      );
                    }
                  })
                  .catch((err) => {
                    agregarAseguradoraFallidaMotos(aseguradora);
                    mostrarAlertarCotizacionFallida(
                      aseguradora,
                      "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                    );
                    console.error(err);
                  })
              );
            });

            Promise.all(cont).then(() => {
              $("#loaderOferta").html("");
              if (contCotizacionMotos > 0) {
                let intermediario =
                  document.getElementById("intermediario").value;
                if (intermediario != 3 && intermediario != 149) {
                  swal.fire({
                    title: "¡Proceso de Cotización Finalizada!",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar",
                  });
                  $("#loaderOferta").html("");
                  $("#loaderOfertaBox").css("display", "none");
                } else {
                  swal
                    .fire({
                      title: "¡Proceso de Cotización Finalizada!",
                      text: "¿Deseas incluir la financiación con Finesa?",
                      showConfirmButton: true,
                      confirmButtonText: "Si",
                      showCancelButton: true,
                      cancelButtonText: "No",
                      customClass: {
                        title: "custom-title-messageFinesa",
                        htmlContainer: "custom-text-messageFinesa",
                        popup: "custom-popup-messageFinesa",
                        actions: "custom-actions-messageFinesa",
                        confirmButton: "custom-confirmnButton-messageFinesa",
                        cancelButton: "custom-cancelButton-messageFinesa",
                      },
                    })
                    .then(function (result) {
                      if (result.isConfirmed) {
                        document.getElementById(
                          "btnReCotizarFallidasMotos"
                        ).disabled = true;
                        $("#loaderOferta").html(
                          '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Cotizando en Finesa...</strong>'
                        );
                        cotizarFinesaMotos(cotizacionesFinesaMotos);
                      } else if (result.isDismissed) {
                        if (result.dismiss === "cancel") {
                          $("#loaderOferta").html("");
                          $("#loaderOfertaBox").css("display", "none");
                        } else if (result.dismiss === "backdrop") {
                          $("#loaderOferta").html("");
                          $("#loaderOfertaBox").css("display", "none");
                        }
                      }
                    });
                }
              } else {
                return swal.fire({
                  title: "Proceso de Cotización Finalizado",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                });
              }

              const btnCotizar = document.getElementById("btnCotizarMotos");
              btnCotizar.disabled = true;
              document.querySelector(".button-recotizar").style.display =
                "block";
              /* Se monta el botón para generar el pdf con 
                      el valor de la variable idCotizacion */
              const contentCotizacionPDF = document.querySelector(
                "#contenCotizacionPDF"
              );
              contentCotizacionPDF.innerHTML = `<div class="col-xs-12" style="width: 100%;">
                                                          <div class="row align-items-center">
                                                              <div class="col-xs-4">
                                                                  <label for="checkboxAsesor">¿Deseas agregar tus datos como asesor en la cotización?</label>
                                                                  <input class="form-check-input" type="checkbox" id="checkboxAsesor" style="margin-left: 10px;" checked>
                                                              </div>
                                                              <div class="col-xs-4">
                                                                  <button type="button" class="btn btn-danger" id="btnParrillaPDF">
                                                                      <span class="fa fa-file-text"></span> Generar PDF de Cotización
                                                                  </button>
                                                              </div>
                                                          </div>
                                                        </div>`;
              $("#btnParrillaPDF").click(function () {
                const todosOn = $(".classSelecOferta:checked").length;
                const idCotizacionPDF = idCotizacion;
                const checkboxAsesor = $("#checkboxAsesor");
                // $("#loaderRecotOferta").html("");

                if (permisos.Generarpdfdecotizacion != "x") {
                  Swal.fire({
                    icon: "error",
                    title:
                      "¡Esta versión no tiene ésta funcionalidad disponible!",
                    showCancelButton: true,
                    confirmButtonText: "Cerrar",
                    cancelButtonText: "Conoce más",
                  }).then((result) => {
                    if (result.isConfirmed) {
                    } else if (result.isDismissed) {
                      window.open("https://www.integradoor.com", "_blank");
                    }
                  });
                } else {
                  if (!todosOn) {
                    swal.fire({
                      title: "¡Debes seleccionar mínimo una oferta!",
                    });
                  } else {
                    let url = `extensiones/tcpdf/pdf/comparadorMotos.php?cotizacion=${idCotizacionPDF}`;
                    if (checkboxAsesor.is(":checked")) {
                      url += "&generar_pdf=1";
                    }
                    window.open(url, "_blank");

                    //   window.open("extensiones/tcpdf/pdf/comparador.php?cotizacion=" + idCotizacionPDF,"_blank");
                  }
                }
              });
            });
          },
        });
      } else if (primerIntentoRealizado && !recotizacionIntentoRealizado) {
        //ZONA RECOTIZACIÓN//
        console.log("Entrando en el bloque else");
        const btnRecotizar = document.getElementById(
          "btnReCotizarFallidasMotos"
        );
        btnRecotizar.disabled = true;
        const contenParrilla = document.querySelector("#contenParrilla");
        raw.cotizacion = idCotizacion;

        $("#loaderRecotOfertaBox").css("display", "block");
        $("#loaderRecotOferta").html(
          '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Recotizando Ofertas Fallidas...</strong>'
        );

        var requestOptions = {
          method: "POST",
          headers: myHeaders,
          body: JSON.stringify(raw),
          redirect: "follow",
        };

        const mostrarAlertaCotizacionExitosa = (aseguradora, contador) => {
          if (aseguradora == "Estado2") {
            aseguradora = "Estado";
          }

          // Obtener la primera clave del objeto
          const primeraClave = Object.keys(contador)[0];

          // Obtener el valor asociado a la primera clave
          const contadorOfertas = contador[primeraClave];

          // Obtener la referencia de la tabla
          const tablaResumenCotBody = document.querySelector(
            "#tablaResumenCot tbody"
          );

          // Verificar si ya existe la fila
          const filaExistente = document.getElementById(aseguradora);
          if (filaExistente) {
            // Acceder directamente a las celdas de la fila existente
            const celdaContador = filaExistente.cells[2]; // Tercera celda de la fila
            const celdaCotizo = filaExistente.cells[1]; // Segunda celda de la fila
            const celdaResponse = filaExistente.cells[3]; // Cuarta celda de la fila
            // Actualizar los valores según sea necesario
            const contadorActualTexto = celdaContador.textContent.trim();
            // Verificar si el texto está vacío o no es un número
            const contadorActual =
              contadorActualTexto === ""
                ? 0
                : parseInt(contadorActualTexto, 10);
            const nuevoContador = contadorActual + contadorOfertas;

            if (
              celdaContador.textContent.trim() !==
              '<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i>'
            ) {
              celdaContador.textContent = nuevoContador;
              celdaCotizo.innerHTML =
                '<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>';
              celdaResponse.textContent = "Cotización exitosa";
            } else {
              celdaContador.textContent = nuevoContador;
              celdaCotizo.innerHTML =
                '<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>';
              celdaResponse.textContent = "Cotización exitosa";
            }
          } else {
            // Si la fila no existe, puedes agregarla
            const nuevaFila = document.createElement("tr");
            nuevaFila.id = aseguradora;
            nuevaFila.innerHTML = `<td>${aseguradora}</td>
                  <td style="text-align: center;"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i></td>
                  <td style="text-align: center;">${contadorOfertas}</td>
                  <td>Nuevo Valor para Response</td>
                  <td>Nuevo Valor para Products</td>
                  <td>Nuevo Valor para Observation</td>`;
            tablaResumenCotBody.appendChild(nuevaFila);
          }
        };

        const mostrarAlertarCotizacionFallida = (aseguradora, mensaje) => {
          if (aseguradora == "Estado2") {
            aseguradora = "Estado";
          }
          //console.log(aseguradora);
          //console.log(mensaje);
          // Referecnia de la tabla
          const tablaResumenCotBody = document.querySelector(
            "#tablaResumenCot tbody"
          );

          // Verificar si ya existe una fila para la aseguradora
          const filaExistente = document.getElementById(aseguradora);

          if (filaExistente) {
            // Si la fila existe, actualiza el mensaje de observaciones

            // Acceder directamente a las celdas de la fila existente
            const celdaContador = filaExistente.cells[2]; // Tercera celda de la fila
            const celdaCotizo = filaExistente.cells[1]; // Segunda celda de la fila
            const celdaResponse = filaExistente.cells[3]; // Cuarta celda de la fila

            celdaContador.textContent = 0;
            celdaCotizo.innerHTML =
              '<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i>';
            celdaResponse.textContent = mensaje;

            // Verifica si el mensaje es diferente antes de actualizar
            // if (observacionesActuales !== mensaje) {
            //   celdaObservaciones.textContent = mensaje;
            // } else {
            //   console.log(${aseguradora} tiene alertas iguales: "${observacionesActuales}" === "${mensaje}");
            // }
          } else {
            // Si no existe, crea una nueva fila
            const nuevaFila = document.createElement("tr");
            nuevaFila.setAttribute("data-aseguradora", aseguradora);
            nuevaFila.innerHTML = `<td>${aseguradora}</td>
                      <td style="text-align: center;"><i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i></td>
                      <td style="text-align: center;">0</td> <!-- Valor predeterminado para 'Productos cotizados' -->
                      <td>${mensaje}</td> <!-- Valor predeterminado para 'Observaciones' -->`;

            // Agregar la fila a la tabla
            tablaResumenCotBody.appendChild(nuevaFila);
          }
        };

        console.log(aseguradorasFallidas);
        aseguradorasFallidas.forEach((aseguradora) => {
          if (
            aseguradora == "BASIC" ||
            aseguradora == "MEDIUM" ||
            aseguradora == "FULL"
          ) {
            aseguradora = "Zurich";
          }
          const celdaResponse = document.getElementById(
            `${aseguradora}Response`
          );

          // Agregar un elemento de carga (por ejemplo, un gif) en la celda de respuesta
          const loadingElement = document.createElement("img");
          loadingElement.src = "vistas/img/plantilla/loader-update.gif"; // Reemplaza con la ruta correcta del gif
          loadingElement.alt = "Cargando...";

          // Establecer el tamaño deseado del gif (por ejemplo, 50px x 50px)
          loadingElement.style.width = "22px";
          loadingElement.style.height = "22px";

          // Limpiar cualquier contenido existente en la celda de respuesta
          celdaResponse.innerHTML = "";

          // Agregar el elemento de carga a la celda de respuesta
          celdaResponse.appendChild(loadingElement);
        });

        let cont = [];

        /* Liberty */
        comprobarFallida("Liberty")
          ? cont.push(
              fetch(
                "https://grupoasistencia.com/motor_webservice_tst2/Liberty_motos",
                requestOptions
              )
                .then((res) => {
                  if (!res.ok) throw Error(res.statusText);
                  return res.json();
                })
                .then((ofertas) => {
                  if (typeof ofertas[0].Resultado !== "undefined") {
                    agregarAseguradoraFallidaMotos("Liberty");
                    validarProblemaMotos("Liberty", ofertas);
                    ofertas[0].Mensajes.forEach((mensaje) => {
                      mostrarAlertarCotizacionFallida("Liberty", mensaje);
                    });
                  } else {
                    // eliminarAseguradoraFallida('Liberty');
                    const contadorPorEntidad = validarOfertasMotos(
                      ofertas,
                      "Liberty",
                      1
                    );
                    mostrarAlertaCotizacionExitosa(
                      "Liberty",
                      contadorPorEntidad
                    );
                  }
                })
                .catch((err) => {
                  agregarAseguradoraFallidaMotos("Liberty");
                  mostrarAlertarCotizacionFallida(
                    "Liberty",
                    "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                  );
                  console.error(err);
                })
            )
          : null;

        // cont.push(libertyPromise);

        /* Allianz */
        comprobarFallida("Allianz")
          ? cont.push(
              fetch(
                "https://grupoasistencia.com/motor_webservice/Allianz_motos",
                requestOptions
              )
                .then((res) => {
                  if (!res.ok) throw Error(res.statusText);
                  return res.json();
                })
                .then((ofertas) => {
                  if (typeof ofertas[0].Resultado !== "undefined") {
                    agregarAseguradoraFallidaMotos("Allianz");
                    validarProblemaMotos("Allianz", ofertas);
                    ofertas[0].Mensajes.forEach((mensaje) => {
                      mostrarAlertarCotizacionFallida("Allianz", mensaje);
                    });
                  } else {
                    // eliminarAseguradoraFallida('Allianz');
                    const contadorPorEntidad = validarOfertasMotos(
                      ofertas,
                      "Allianz",
                      1
                    );
                    mostrarAlertaCotizacionExitosa(
                      "Allianz",
                      contadorPorEntidad
                    );
                  }
                })
                .catch((err) => {
                  agregarAseguradoraFallidaMotos("Allianz");
                  mostrarAlertarCotizacionFallida(
                    "Allianz",
                    "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                  );
                  console.error(err);
                })
            )
          : //  : Promise.resolve();
            null;

        // cont.push(allianzPromise);

        comprobarFallida("AXA")
          ? cont.push(
              fetch(
                "https://grupoasistencia.com/motor_webservice/AXA_motos",
                requestOptions
              )
                .then((res) => {
                  if (!res.ok) throw Error(res.statusText);
                  return res.json();
                })
                .then((ofertas) => {
                  if (typeof ofertas[0].Resultado !== "undefined") {
                    agregarAseguradoraFallidaMotos("AXA");
                    validarProblemaMotos("AXA", ofertas);
                    ofertas[0].Mensajes.forEach((mensaje) => {
                      mostrarAlertarCotizacionFallida("AXA", mensaje);
                    });
                  } else {
                    // eliminarAseguradoraFallida('AXA');
                    const contadorPorEntidad = validarOfertasMotos(
                      ofertas,
                      "AXA",
                      1
                    );
                    mostrarAlertaCotizacionExitosa("AXA", contadorPorEntidad);
                  }
                })
                .catch((err) => {
                  agregarAseguradoraFallidaMotos("AXA");
                  mostrarAlertarCotizacionFallida(
                    "AXA",
                    "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                  );
                  console.error(err);
                })
            )
          : // : Promise.resolve();
            null;
        //cont.push(axaPromise);

        comprobarFallida("SBS")
          ? cont.push(
              fetch(
                "https://grupoasistencia.com/motor_webservice/SBS_motos",
                requestOptions
              )
                .then((res) => {
                  if (!res.ok) throw Error(res.statusText);
                  return res.json();
                })
                .then((ofertas) => {
                  if (typeof ofertas[0].Resultado !== "undefined") {
                    agregarAseguradoraFallidaMotos("SBS");
                    validarProblemaMotos("SBS", ofertas);
                    ofertas[0].Mensajes.forEach((mensaje) => {
                      mostrarAlertarCotizacionFallida("SBS", mensaje);
                    });
                  } else {
                    // eliminarAseguradoraFallida('SBS');
                    const contadorPorEntidad = validarOfertasMotos(
                      ofertas,
                      "SBS",
                      1
                    );
                    mostrarAlertaCotizacionExitosa("SBS", contadorPorEntidad);
                  }
                })
                .catch((err) => {
                  agregarAseguradoraFallidaMotos("SBS");
                  mostrarAlertarCotizacionFallida(
                    "SBS",
                    "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                  );
                  console.error(err);
                })
            )
          : // : Promise.resolve();
            null;
        // cont.push(sbsPromise);
        Promise.all(cont).then(() => {
          // $("#loaderOferta").html("");
          $("#loaderRecotOferta").html("");
          let nuevas = cotizacionesFinesaMotos.filter(
            (cotizaciones) => cotizaciones.cotizada === null
          );
          if (nuevas.length > 0) {
            let intermediario = document.getElementById("intermediario").value;
            if (intermediario != 3 && intermediario != 149) {
              swal.fire({
                title: "¡Proceso de Cotización Finalizada!",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
              });
              $("#loaderOferta").html("");
              $("#loaderOfertaBox").css("display", "none");
            } else {
              swal
                .fire({
                  title: "¡Proceso de Re-Cotización Finalizada!",
                  text: "¿Deseas incluir la financiación con Finesa?",
                  showConfirmButton: true,
                  confirmButtonText: "Si",
                  showCancelButton: true,
                  cancelButtonText: "No",
                  customClass: {
                    title: "custom-title-messageFinesa",
                    htmlContainer: "custom-text-messageFinesa",
                    popup: "custom-popup-messageFinesa",
                    actions: "custom-actions-messageFinesa",
                    confirmButton: "custom-confirmnButton-messageFinesa",
                    cancelButton: "custom-cancelButton-messageFinesa",
                  },
                })
                .then(function (result) {
                  if (result.isConfirmed) {
                    document.getElementById(
                      "btnReCotizarFallidasMotos"
                    ).disabled = true;
                    $("#loaderOferta").html(
                      '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Cotizando en Finesa...</strong>'
                    );
                    cotizarFinesaMotos(cotizacionesFinesaMotos);
                  } else if (result.isDismissed) {
                    if (result.dismiss === "cancel") {
                      $("#loaderOferta").html("");
                      $("#loaderOfertaBox").css("display", "none");
                    } else if (result.dismiss === "backdrop") {
                      $("#loaderOferta").html("");
                      $("#loaderOfertaBox").css("display", "none");
                    }
                  }
                });
            }
          } else {
            let anuncio = true;
            if (anuncio) {
              anuncio = false;
              swal.fire({
                title: "¡Proceso de Re-Cotización Finalizada!",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
              });
            }
          }
        });
        recotizacionIntentoRealizado = true; // Marcar que se realizó la recotización
        return;
      }
    }
  }
}

document
  .querySelector("#btn-consultar-fasecolda")
  .addEventListener("click", (e) => {
    const fasecolda = document.querySelector("#txtFasecolda_modal").value;
    const modelo = document.querySelector("#txtModeloVeh_modal").value;
    if (fasecolda === "" || modelo === "") {
      return;
    }
    consulDatosFasecoldaMotos(fasecolda, modelo)
      .then((data) => {
        if (typeof data.marcaVeh === "undefined") {
          alert("Vehículo no Encontrado");
        } else {
          alert("Vehículo Encontrado");
          $("#txtClaseVeh").val(data.claseVeh);
          $("#txtMarcaVeh").val(data.marcaVeh);
          $("#txtReferenciaVeh").val(data.lineaVeh);
          $("#txtValorFasecolda").val(data.valorVeh);
          document.querySelector("#txtFasecolda").value = fasecolda;
          document.querySelector("#txtModeloVeh").value = modelo;
          $(".modal-body").dialog("close");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  });

// Cuando se cierra el modal
$("#btn-cerrar-fasecolda").on(() => {
  document.querySelector("#txtFasecolda_modal").value = "";
  document.querySelector("#txtModeloVeh_modal").value = "";
  $(".modal-body").dialog("close");
});

$(function () {
  $(".modal-body").dialog({
    autoOpen: false,
    modal: true,
    width: 300, // overcomes width:'auto' and maxWidth bug
    maxWidth: 300,
    height: "auto",
    fluid: true, //new option
    resizable: false,
    title: "Busqueda Manual Fasecolda",
    dialogClass: "no-close",
    show: { effect: "slide", duration: 500, direction: "down" }, // Efecto de slide hacia abajo
    hide: { effect: "slide", duration: 500, direction: "down" }, // Efecto de slide hacia abajo
    open: function (event, ui) {
      // Cambiar el color del título del diálogo
      $(this).prev().find(".ui-dialog-title").css({
        color: "white",
        "font-weight": "lighter",
      });
    },
  });
  $(".buscarFasecolda")
    .button()
    .click(function () {
      txtFasecolda_modal.value = txtFasecolda.value;
      txtModeloVeh_modal.value = txtModeloVeh.value;
      $(".modal-body").dialog("option", "width", 300);
      $(".modal-body").dialog("option", "height", 270);
      $(".modal-body").dialog("option", "resizable", false);
      $(".modal-body").dialog("open");
    });
  $("#btn-cerrar-fasecolda")
    .button()
    .click(function () {
      document.querySelector("#txtFasecolda_modal").value = "";
      document.querySelector("#txtModeloVeh_modal").value = "";
      $(".modal-body").dialog("close");
    });
});

function fluidDialog() {
  var $visible = $(".ui-dialog:visible");
  // each open dialog
  $visible.each(function () {
    var $this = $(this);
    var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
    // if fluid option == true
    if (dialog.options.fluid) {
      var wWidth = $(window).width();
      // check window width against dialog width
      if (wWidth < parseInt(dialog.options.maxWidth) + 50) {
        // keep dialog from filling entire screen
        $this.css("max-width", "90%");
      } else {
        // fix maxWidth bug
        $this.css("max-width", dialog.options.maxWidth + "px");
      }
      //reposition dialog
      dialog.option("position", dialog.options.position);
    }
  });
}

$(window).resize(function () {
  fluidDialog();
});

// Ejecuta function Fluid Dialog cuando detecta que se abre algun dialogo con el nombre dialogopen o ui-dialog como clase
$(document).on("dialogopen", ".ui-dialog", function (event, ui) {
  fluidDialog();
});
