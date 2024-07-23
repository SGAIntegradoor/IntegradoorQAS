$(document).ready(function () {
  var permisos = JSON.parse(permisosPlantilla);
  const parrillaCotizaciones = document.getElementById("parrillaCotizaciones");
  parrillaCotizaciones.style.display = "none";

  // Mostrar alertas

  // Valida que el dato ingresado sea numerico
  $("#numDocumentoID").numeric();
  $("#txtFasecolda").numeric();
  $("#txtValorFasecolda").numeric();
  $("#numCotizacion").numeric();
  $("#valorTotal").numeric();

  tokenPrevisora();

  //FUNCION PARA LEVANTAR EL TOKEN DE PREVISORA APENAS INICIE LA PAGINA
  function tokenPrevisora() {
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    var raw = JSON.stringify({});

    var requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow",
    };

    fetch(
      "https://grupoasistencia.com/motor_webservice/codigoTokenPrevisora",
      requestOptions
    )
      .then(function (response) {
        return response.json();
      })
      .then(function (myJson) {
        $("#previsoraToken").val(myJson.TokenPrevisora);
      });
  }

  // Elimina los espacios de la placa
  $("#placaVeh").keyup(function () {
    var numeroInput = document.getElementById("placaVeh").value;
    var placaSinEspacios = numeroInput.replace(/\s/g, "");
    document.getElementById("placaVeh").value = placaSinEspacios;
  });

  // Convierte la Placa ingresada en Mayusculas
  $("#placaVeh").keyup(function () {
    var numPlaca = document.getElementById("placaVeh").value;
    mayuscPlaca = numPlaca.toUpperCase();
    $("#placaVeh").val(mayuscPlaca);
  });

  // Evita Espacios en blanco en el numero de Placa
  $("#placaVeh").on("keypress", function (e) {
    if (e.which == 32) return false;
  });

  // Obtener el campo de entrada por su ID
  var placaInput = document.getElementById("placaVeh");

  // Agregar un evento de escucha para el evento "input"
  placaInput.addEventListener("input", function () {
    // Obtener el valor actual del campo de entrada
    var valor = placaInput.value;

    // Filtrar caracteres especiales y dejar solo letras y números
    var valorFiltrado = valor.replace(/[^a-zA-Z0-9]/g, "");

    // Actualizar el valor del campo de entrada con el valor filtrado
    placaInput.value = valorFiltrado;
  });

  // Obtener los campos de entrada por su ID
  var nombreInput = document.getElementById("txtNombres");
  var apellidoInput = document.getElementById("txtApellidos");
  var ceroKilometros = document.getElementById("txtEsCeroKmSi");

  // Función para filtrar caracteres especiales
  function filtrarCaracteresEspeciales(input) {
    var valor = input.value;
    var valorFiltrado = valor.replace(/[^a-zA-ZñÑ ]/g, ""); // Permitir letras, espacios y la letra "ñ" en mayúsculas o minúsculas
    input.value = valorFiltrado;
  }

  // MANEJO DE NOMBRES Y APELLIDOS

  // Agregar eventos de escucha para el evento "input" en ambos campos
  nombreInput.addEventListener("input", function () {
    filtrarCaracteresEspeciales(nombreInput);
  });

  apellidoInput.addEventListener("input", function () {
    filtrarCaracteresEspeciales(apellidoInput);
  });

  // Agregar un evento 'blur' para eliminar espacios en blanco al final y al principio
  nombreInput.addEventListener("blur", function () {
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

  apellidoInput.addEventListener("blur", function () {
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

  // Conviete la letras iniciales del Nombre y el Apellido deL Cliente en Mayusculas
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

  // Si conoce la Placa muestra el campo Placa y oculta el campo CeroKM.
  $("#txtConocesLaPlacaSi").click(function () {
    document.getElementById("contenPlaca").style.display = "block";
    document.getElementById("contenCeroKM").style.display = "none";
    document.getElementById("placaVeh").value = "";
    $("#txtEsCeroKmSi").prop("checked", false);
    $("#txtEsCeroKmNo").prop("checked", true);
  });

  // Si no conoce la Placa oculta el campo Placa y muestra el campo CeroKM.
  $("#txtConocesLaPlacaNo").click(function () {
    document.getElementById("contenPlaca").style.display = "none";
    document.getElementById("contenCeroKM").style.display = "block";
    document.getElementById("placaVeh").value = "WWW404";
    $("#txtEsCeroKmNo").prop("checked", false);
  });

  // Validamos que si el vehiculo No es Cero KM, debe tener Placa
  $("#txtEsCeroKmNo").click(function () {
    var conoceslaPlaca = document.getElementById("txtConocesLaPlacaNo").checked;
    var esCeroKmNo = document.getElementById("txtEsCeroKmNo").checked;

    if (conoceslaPlaca == true && esCeroKmNo == true) {
      Swal.fire({
        icon: "error",
        title: "!Si el vehiculo no es 0 km, debe tener placa!",
        text: "Si el vehiculo tiene placa, no es 0 km",
        showConfirmButton: true,
      });
      $("#txtEsCeroKmNo").prop("checked", false);
    }
  });

  // DOCUMENTO

  //Elimina espacios y caracteres especiales en el campo DOCUMENTO al copiar y pegar informacion
  $("#numDocumentoID").change(function () {
    convertirNumero();
  });

  $(document).ready(function () {
    // Detectar el evento de entrada (input) en el campo de número de documento
    $("#numDocumentoID").on("input", function () {
      convertirNumero();
    });
  });

  function convertirNumero() {
    var numeroInput = document.getElementById("numDocumentoID").value;
    var numeroSinCaracteresEspeciales = numeroInput.replace(/[^0-9]/g, "");
    document.getElementById("numDocumentoID").value =
      numeroSinCaracteresEspeciales;
  }

  // Convierte la Placa ingresada en Mayusculas
  $("#numDocumentoID").change(function () {
    consultarAsegurado();
  });

  // Carga la fecha de Nacimiento
  $("#dianacimiento, #mesnacimiento, #anionacimiento").select2({
    theme: "bootstrap fecnacimiento",
    language: "es",
    width: "100%",
  });

  // Carga la edad
  $("#edad").select2({
    theme: "bootstrap edad",
    language: "es",
    width: "100%",
  });

  // Conviete la letras iniciales del Nombre y el Apellido deL Cliente en Mayusculas
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

  // Carga los Departamentos disponibles
  $("#DptoCirculacion").select2({
    theme: "bootstrap dpto",
    language: "es",
    width: "100%",
  });
  $("#DptoCirculacion").change(function () {
    consultarCiudad();
  });

  // Carga las Ciudades disponibles
  $("#ciudadCirculacion").select2({
    theme: "bootstrap ciudad",
    language: "es",
    width: "100%",
  });

  // Si es Oneroso muestra el campo N° Beneficiario.
  $("#esOnerosoSi").click(function () {
    document.getElementById("contenBenefOneroso").style.display = "block";
  });

  // Si no es Oneroso oculta el campo N° Beneficiario y lo limpia.
  $("#esOnerosoNo").click(function () {
    document.getElementById("contenBenefOneroso").style.display = "none";
    document.getElementById("benefOneroso").value = "";
  });

  // Obtiene los datos de cada campo del formulario y Valida que no esten Vacios
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

  // Ejectura la funcion Consultar Placa Vehiculo
  $("#btnConsultarPlaca").click(function () {
    consulPlaca();
  });

  // Ejecuta la funcion que trae el Codigo Fasecolda de la Guia
  $("#btnConsultarVeh").click(function () {
    consulCodFasecolda();
  });

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

  async function checkCotTotales() {
    let cotHechas = await mostrarCotRestantes();
    console.log(cotHechas);

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

  let intermediario = document.getElementById("idIntermediario").value;

  $("#btnCotizar").click(function (e) {
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
        if (response.result == 1 || response.result == 2) {
          cotizarOfertas();
        } else {
          e.preventDefault();
          mostrarAlertaCotizacionesExcedidas();
        }
      });
    } else {
        checkCotTotales().then((response) => {
          if (response.result == 1 || response.result == 2) {
            mostrarPoliticaValorAsegurado();
            cotizarOfertas();
          } else {
            e.preventDefault();
            mostrarAlertaCotizacionesExcedidas();
          }
        });
    }
  });
  
  function mostrarAlertaCotizacionesExcedidas() {
    swal
            .fire({
              icon: "error",
              title: "Cotizaciones Totales Excedidas",
              html: `<div style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;">Lo sentimos. No tienes cotizaciones disponibles, por favor comunicate con tu analista asignado.`,
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
  
  function mostrarPoliticaValorAsegurado() {
    return swal
      .fire({
        icon: "warning",
        title: "POLÍTICA DE VALOR ASEGURADO<br>LIVIANOS",
        html: `
          <div style="overflow-x: auto;">
            <table style="border: 2px solid gray; border-collapse: collapse;" id="tableModal">
              <thead style="padding: 5px;">
                <tr style="border: 2px solid gray; text-align: center">
                  <th style="border: 2px solid gray; padding: 10px; height: 50px; text-align: center" id="tdAsegurado">Valor Asegurado</th>
                  <th style="border: 2px solid gray; padding: 10px; height: 50px; text-align: center" id="tdCondiciones">Condiciones</th>
                </tr>
              </thead>
              <tbody>
                <tr style="border: 2px solid gray;">
                  <td style="border: 2px solid gray; padding: 10px;">Menos de 200 millones</td>
                  <td style="border: 2px solid gray; padding: 10px;">De acuerdo a políticas de cada aseguradora</td>
                </tr>
                <tr style="border: 2px solid gray;">
                  <td style="border: 2px solid gray; padding: 10px;">200 a 250 millones</td>
                  <td style="border: 2px solid gray; padding: 10px;">Requieren autorización del Director Comercial de Grupo Asistencia</td>
                </tr>
                <tr style="border: 2px solid gray;">
                  <td style="border: 2px solid gray; padding: 10px;">250 a 300 millones</td>
                  <td style="border: 2px solid gray; padding: 10px;">Requieren autorización de Gerencia de Grupo Asistencia de acuerdo al nivel de productividad del Asesor</td>
                </tr>
              </tbody>
            </table>
          </div>
          <p style="text-align: justify; font-family: Helvetica, Arial, sans-serif;" id="pTableModal">
            <strong>Nota:</strong> Tener en cuenta que aunque el cotizador genere ofertas, no todos los vehículos son asegurables. Se podrán hacer excepciones de valor asegurado superior cuando el asesor sea productivo, tenga más de 6 meses de antigüedad con Grupo Asistencia, no tenga altos indices de siniestralidad en su cartera, y si el cliente tiene vinculación con otros productos de la aseguradora. El valor de las primas de las cotizaciones puede variar al momento de emitir en los casos autorizados de manera excepcional.
          </p>
        `,
        width: "30%",
        showConfirmButton: true,
        confirmButtonText: "Continuar",
        customClass: {
          popup: "custom-swal-alertaMontoLivianos",
          title: "custom-swal-titleLivianos",
          confirmButton: "custom-swal-confirm-button20",
          actions: "custom-swal-actions-livianos",
          icon: "swal2-icon_monto",
        },
        timer: 20000,
        timerProgressBar: true,
      });
  }
});

// Maximiza el formulario Datos Asegurado
function masAseg() {
  document.getElementById("DatosAsegurado").style.display = "block";
  document.getElementById("menosAsegurado").style.display = "block";
  document.getElementById("masAsegurado").style.display = "none";
}
// Minimiza el formulario Datos Asegurado
function menosAseg() {
  document.getElementById("DatosAsegurado").style.display = "none";
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

// Permite consultar los datos del Asegurado si existe en el sistema
function consultarAsegurado() {
  var tipoDocumentoID = document.getElementById("tipoDocumentoID").value;
  var numDocumentoID = document.getElementById("numDocumentoID").value;

  $.ajax({
    type: "POST",
    url: "src/consultarAsegurado.php",
    dataType: "json",
    data: { tipoDocumento: tipoDocumentoID, numDocumento: numDocumentoID },
    success: function (data) {
      var estado = data.estado;
      var fechaNac = data.cli_fch_nacimiento;

      if (estado) {
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

// FUNCION PARA OBTENER EL NOMBRE DEL MES
function obtenerNombreMes(numero) {
  var fecha = new Date();
  if (0 < numero && numero <= 12) {
    fecha.setMonth(numero - 1);
    return new Intl.DateTimeFormat("es-ES", { month: "long" }).format(fecha);
  }
}

var contErrMetEstado = 0;
var contErrProtocolo = 0;

// Permite consultar la informacion del vehiculo por medio de la Placa (Seguros del Estado)
function consulPlaca() {
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
    if (tipoDocumentoID == "2") {
      var restriccion = "";
      if (rolAsesor == 19) {
        restriccion =
          "Lo sentimos, no puedes realizar cotizaciones para personas jurídicas por este cotizador. Para hacerlo debes comunicarte con el Equipo de Asesores Freelance de Grupo Asistencia, quienes podrán ayudarte a cotizar de manera manual con diferentes aseguradoras.";
      } else {
        restriccion =
          "Lo sentimos, no puedes realizar cotizaciones para personas jurídicas por este cotizador.";
      }
      Swal.fire({
        icon: "error",
        text: restriccion,
        confirmButtonText: "Cerrar",
      }).then(() => {
        // Recargar la página después de cerrar el SweetAlert
        location.reload();
      });
    }
    if (
      numplaca != "" &&
      tipoDocumentoID != "" &&
      numDocumentoID != "" &&
      dianacimiento != "" &&
      mesnacimiento != "" &&
      anionacimiento != "" &&
      nombresAseg != "" &&
      apellidosAseg != "" &&
      generoAseg != "" &&
      estadoCivil != ""
    ) {
      // Oculta los campos de consultar Vehiculo paso a paso desde la Guia Fasecolda
      document.getElementById("formularioVehiculo").style.display = "none";
      $("#loaderPlaca").html(
        '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Consultando Placa...</strong>'
      );

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

      // Llama la informacion del Vehiculo por medio de la Placa
      fetch(
        "https://grupoasistencia.com/motor_webservice/Vehiculo",
        requestOptions
      )
        .then(function (response) {
          if (!response.ok) {
            throw Error(response.statusText);
          }
          return response.json();
        })
        .then(function (myJson) {
          //console.log(myJson)
          var estadoConsulta = myJson.Success;
          var mensajeConsulta = myJson.Message;

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
              } else {
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
                  var restriccion = "";
                  if (rolAsesor == 19) {
                    restriccion =
                      "Lo sentimos, no puedes cotizar motos por este módulo. Para hacerlo debes ingresar al modulo Cotizar Motocicletas.";
                  } else {
                    restriccion =
                      "Lo sentimos, no puedes cotizar motos por este módulo.";
                  }
                  Swal.fire({
                    icon: "error",
                    text: restriccion,
                    confirmButtonText: "Cerrar",
                  }).then(() => {
                    // Recargar la página después de cerrar el SweetAlert
                    location.reload();
                  });
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
                    location.reload();
                  });
                } else if (codigoClase == 19) {
                  claseVehiculo = "VAN";
                  limiteRCESTADO = 18;
                } else if (codigoClase == 16) {
                  claseVehiculo = "MOTOCICLETA";
                  limiteRCESTADO = 6;
                  var restriccion = "";
                  if (rolAsesor == 19) {
                    restriccion =
                      "No puedes cotizar motos por este módulo. Para hacerlo, debes comunicarte con el Equipo de Asesores Freelance de Grupo Asistencia, quienes podrán ayudarte a cotizar de manera manual con diferentes aseguradoras.";
                  } else {
                    restriccion =
                      "Lo sentimos, no puedes cotizar motos por este módulo.";
                  }
                  Swal.fire({
                    icon: "error",
                    text: restriccion,
                    confirmButtonText: "Cerrar",
                  }).then(() => {
                    // Recargar la página después de cerrar el SweetAlert
                    location.reload();
                  });
                }

                $("#CodigoClase").val(codigoClase);
                $("#txtClaseVeh").val(claseVehiculo);
                $("#LimiteRC").val(limiteRCESTADO);
                $("#CodigoMarca").val(codigoMarca);
                $("#txtModeloVeh").val(modeloVehiculo);
                $("#CodigoLinea").val(codigoLinea);
                $("#txtFasecolda").val(codigoFasecolda);
                $("#txtValorFasecolda").val(valorAsegurado);

                consulDatosFasecolda(codigoFasecolda, modeloVehiculo).then(
                  function (resp) {
                    $("#txtMarcaVeh").val(resp.marcaVeh);
                    $("#txtReferenciaVeh").val(resp.lineaVeh);
                  }
                );
              }
            }
          } else {
            if (
              mensajeConsulta == "Parámetros Inválidos. Placa es requerido." ||
              mensajeConsulta == "Favor diligenciar correctamente la placa"
            ) {
              swal.fire({
                text: "! Favor diligenciar correctamente la placa. ¡",
              });
            } else {
              consulPlacaMapfre(valnumplaca);
            }
            consulPlacaMapfre(valnumplaca);
            // $("#loaderPlaca").html("");
          }
        })
        .catch(function (error) {
          //console.log("Parece que hubo un problema: \n", error);
          consulPlacaMapfre(valnumplaca);

          contErrProtocolo++;
          if (contErrProtocolo > 1) {
            consulPlacaMapfre(valnumplaca);
            // $("#loaderPlaca").html("");
            contErrProtocolo = 0;
          } else {
            // setTimeout(consulPlacaMapfre, 4000);
          }
        });
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
          // desactive
          // console.log(resp)
          $("#txtMarcaVeh").val(resp.marcaVeh);
          $("#txtReferenciaVeh").val(resp.lineaVeh);
          $("#txtValorFasecolda").val(resp.valorVeh);
        });
        // const valor = resp[llave];
        // $("#txtValorFasecolda").val(valorAsegurado);
      } else {
        document.getElementById("formularioVehiculo").style.display = "block";
        document.getElementById("headerAsegurado").style.display = "block";
        document.getElementById("masA").style.display = "block";
        document.getElementById("DatosAsegurado").style.display = "none";
      }
    })
    .catch(function (error) {
      // desactive
      // console.log("Parece que hubo un problema: \n", error);
      document.getElementById("formularioVehiculo").style.display = "block";
      document.getElementById("headerAsegurado").style.display = "block";
      document.getElementById("masA").style.display = "block";
      document.getElementById("DatosAsegurado").style.display = "none";
    });
}

$("#btnConsultarVehmanual").on("click", function (){
   consulCodFasecolda();
 })

// CONSULTA LA GUIA PARA OBTENER EL CODIGO FASECOLDA MANUALMENTE
function consulCodFasecolda() {
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
        // desactive
        // console.log(data);
        var codFasecolda = data.result.codigo;
        consulValorfasecolda(codFasecolda, edadVeh);
      },
    });
  }
}

var contErrMetEstadoFasec = 0;
var contErrProtConsulFasec = 0;

// Permite consultar la informacion del vehiculo segun la Guia Fasecolda
function consulValorfasecolda(codFasecolda, edadVeh) {
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
      // desactive
      // console.log(myJson);
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
      // desactive
      //console.log("Parece que hubo un problema: \n", error);

      contErrProtConsulFasec++;
      if (contErrProtConsulFasec > 1) {
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
        contErrProtConsulFasec = 0;
      } else {
        setTimeout(consulCodFasecolda, 4000);
      }
    });
}

//FUNCION PARA CONSULTAR VALORES EN FASECOLDA
function consulDatosFasecolda(codFasecolda, edadVeh) {
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
        if (data.mensaje == "No hay Registros") {
          document.getElementById("formularioVehiculo").style.display = "block";
        } else {
          // console.log(data);
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
        // desactive
        // console.log(data);
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

  //if (codigoDpto == 1 || codigoDpto == 3 || codigoDpto == 10 || codigoDpto == 11 || codigoDpto == 12 || codigoDpto == 14 || codigoDpto == 17
  //|| codigoDpto == 19 || codigoDpto == 25 || codigoDpto == 28 || codigoDpto == 33 || codigoDpto == 34) {

  //	swal({ text: '! El Departamento de circulación no posee cobertura. ¡' });

  //} else {

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
  console.log(responses);
  let dataToDB = [];
  if (Array.isArray(responses) && responses.length >= 1) {
    dataToDB = responses.map((element) => {
      return element;
    });
  }
  return dataToDB;
}

let cotizoFinesa = false;

function cotizarFinesa(ofertasCotizaciones) {
  
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
      cuotas: 11,
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
              // "http://localhost/motorTest/saveDataQuotationsFinesa",
              "https://www.grupoasistencia.com/motor_webservice/saveDataQuotationsFinesa",
              {
                method: "POST",
                headers: headers,
                body: JSON.stringify(finesaData),
              }
            )
              .then((dbResponse) => dbResponse.json())
              .then((dbData) => {
                const elementDiv = document.getElementById(element.objFinesa);
                if (element.aseguradora == "Seguros Bolivar" || element.aseguradora == "Liberty")
                  {
                    cotizacionesFinesa[index].cotizada = true;
                     elementDiv.innerHTML = `Financiación Aseguradora:<br /> Consulte analista`;
                  } else if (dbData?.data?.mensaje.includes("Por políticas de Finesa")) {
                    cotizacionesFinesa[index].cotizada = true;
                    elementDiv.innerHTML = `Financiación:<br /> No aplica financiación`;
                  } else if (dbData?.data?.mensaje.includes("Asegurado no viable para financiacion")) {
                    cotizacionesFinesa[index].cotizada = true;
                    elementDiv.innerHTML = `Financiación Finesa:<br /> Asegurado no viable para financiación`;
                  } else {
                    cotizacionesFinesa[index].cotizada = true;
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
      $("#loaderRecotOferta").html("");
      $("#loaderRecotOfertaBox").css("display", "none");
      //console.log(cotizacionesFinesa);
      return;
    }
  });

  Promise.all(promisesFinesa)
    .then((results) => {
      cotEnFinesaResponse = saveQuotations(results);
      $("#loaderOferta").html("");
      $("#loaderOfertaBox").css("display", "none");
      $("#loaderRecotOferta").html("");
      $("#loaderRecotOfertaBox").css("display", "none");
      swal
        .fire({
          title: "¡Cotización a Finesa Finalizada!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
        })
        .then(() => {
          $("#loaderOferta").html("");
          $("#loaderOfertaBox").css("display", "none");
          if(!cotizoFinesa){
            document.getElementById("btnReCotizarFallidas").disabled = false;
            cotizoFinesa = true;
          }
        });
    })
    .catch((error) => {
      console.error("Error en las promesas: ", error);
    })
    .finally(() => {
      console.log(cotEnFinesaResponse);
    });
}

let actIdentity = "";

// REGISTRA CADA UNA DE LAS OFERTAS COTIZADAS EN LA BD
function registrarOferta(
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
  manual,
  pdf
) {
  return new Promise((resolve, reject) => {
    var idCotizOferta = idCotizacion;
    var numDocumentoID = document.getElementById("numDocumentoID").value;
    var placa = document.getElementById("placaVeh").value;

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
        // Agregue esta variable en Ofertas para reconocer el nombre en Script PHP e insertarlo en la BD en el momento que se crea.
        identityElement: actIdentity != "" ? actIdentity : NULL,
      },
      success: function (data) {
        console.log(data);
        // var datos = data.Data;
        // var message = data.Message;
        // var success = data.Success;
        resolve();
      },
      error: function (error) {
        // desactive
        console.log(error);
        reject(error);
      },
    });
  });
}

let contCotizacion = 0;
let cotizacionesFinesa = [];

const mostrarOferta = (
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
    } else if ($data == "Liberty Seguros") {
      $resultado = "Liberty";
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
  var aseguradoraCredenciales = nombreAseguradora + "_C";
  var permisosCredenciales = permisos[aseguradoraCredenciales];

  let cotOferta = {
    aseguradora: aseguradora,
    objFinesa: aseguradora + "_" + contCotizacion,
    producto: producto,
    prima: Number(prima.replace(/\./g, "")),
    cuotas: 11,
    cotizada: null,
  };

  actIdentity = aseguradora + "_" + contCotizacion;

  if (
    cotizacionesFinesa.filter((e) => e.objFinesa === cotOferta.objFinesa)
      .length === 0
  ) {
    cotizacionesFinesa.push(cotOferta);
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
                          aseguradora == "Liberty" ||
                          aseguradora == "Equidad" ||
                          aseguradora == "Mapfre" ||
                          aseguradora == "Seguros Bolivar") &&
                        id_intermediario == "79"
                          ? `<center>
                          <!-- Código para el caso específico de Axa Colpatria, Liberty, Equidad o Mapfre y id_intermediario no es 78 -->
                          <!-- Agrega aquí el contenido específico para estas aseguradoras y el id_intermediario no es 78 -->
                        </center>`
                          : permisos.Vernumerodecotizacionencadaaseguradora ==
                              "x" && permisosCredenciales == "1"
                          ? `<center>
                          <label class='entidad'>N° Cot: <span style='color:black'>${numCotizOferta}</span></label>
                        </center>`
                          : ""
                      }
                    </div>
                       </div>
                       <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit">
                       <h5 class='entidad' style='font-size: 15px'><b>${aseguradora} - ${
                        producto == "Pesados con RCE en exceso"
                          ? "Pesados RCE + Exceso"
                          : producto == "PREVILIVIANOS INDIVIDUAL - "
                          ? "PREVILIVIANOS INDIVIDUAL"
                          : producto == "AU DEDUCIBLE UNICO LIVIANOS - "
                          ? "AU DEDUCIBLE UNICO LIVIANOS"
                          : producto == "LIVIANOS MIA - "
                          ? "LIVIANOS MIA"
                          : producto
                      }</b></h5>
                       <h5 class='precio' style='margin-top: 0px !important;'>Desde $ ${prima}</h5>
                       <p class='title-precio' style='margin: 0 0 3px !important'>Precio (IVA incluido)</p>
                       <div id='${actIdentity}' style='display: none; color: #88d600;'>
                      </div>
                    </div>
                                      <div class="col-xs-12 col-sm-6 col-md-4">
                                          <ul class="list-group">
                                              <li class="list-group-item">
                                                  <span class="badge">* $${valorRC}</span>
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

// VALIDA QUE LAS OFERTAS COTIZADAS HAYAN SIDO GUARDADAS EN SU TOTALIDAD
function validarOfertas(ofertas, aseguradora, exito) {
  let contadorPorEntidad = {};

  if (aseguradora == "Bolivar" || aseguradora == "Seguros Bolivar") {
  }

  ofertas.forEach((oferta, i) => {
    var numCotizacion = oferta.numero_cotizacion;
    var precioOferta = oferta.precio;
    if (oferta == null) return;
    if (numCotizacion == null && precioOferta == "0") return;
    if (precioOferta.length <= 3) return;
    // contadorOfertas++;   // Variable para contar el número de ofertas
    contadorPorEntidad[oferta.entidad] =
      (contadorPorEntidad[oferta.entidad] || 0) + 1;
    // console.log(`Entidad: ${oferta.entidad}, Contador: ${contadorPorEntidad[oferta.entidad]}`);
    contCotizacion++;
    mostrarOferta(
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

    registrarOferta(
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
      0
    );

    // });
  });

  // Llamada a la función registrarNumeroOfertas para cada entidad
  Object.entries(contadorPorEntidad).forEach(([entidad, contador]) => {
    // const numCotizacion = ofertas.find(oferta => oferta.entidad === entidad)?.numero_cotizacion;
    var idCotizOferta = idCotizacion;
    registrarNumeroOfertas(entidad, contador, idCotizOferta, exito);
  });

  return contadorPorEntidad;
}

//VERSION DEFINITIVA "validarProblema()""
function validarProblema(aseguradora, ofertas) {
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
            // var datos = data.Data;
            // console.log(datos);
            // var message = data.Message
            // var success = data.Success
            // resolve();
          },
          error: function (error) {
            console.log(error);
            // reject(error)
          },
        });
      }
    });
  } else if (
    ofertas &&
    ofertas.jsonZurich &&
    typeof ofertas.jsonZurich === "object"
  ) {
    // let cadena = ""
    // Caso específico para la estructura de Zurich
    let mensajesZurich = ofertas.Mensajes || [];
    if (Array.isArray(mensajesZurich) && mensajesZurich.length > 0) {
      // Concatenar mensajes en un solo párrafo
      let mensajeConcatenadoZurich = "";
      // var mensajeConcatenadoZurich = mensajesZurich
      //   .map((m) => m.messageText)
      //   .join(", ");
      if (mensajesZurich.length == 1) {
        console.log(mensajesZurich);
        mensajeConcatenadoZurich = mensajesZurich[0];
        console.log(mensajeConcatenadoZurich);
      } else {
        mensajesZurich.map((element, index) => {
          if (element.includes("Referred")) {
            if (index == 2) {
              mensajeConcatenadoZurich += " - " + element;
            } else {
              mensajeConcatenadoZurich += element;
            }
          }
          if (element.includes("Lo sentimos")) {
            mensajeConcatenadoZurich += " - " + element;
          }
        });
      }

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
          //var datos = data.Data;
          //console.log(datos);
          // var message = data.Message
          // var success = data.Success
          // resolve();
        },
        error: function (error) {
          console.log(error);
          // reject(error)
        },
      });
    }
  }
}

function registrarNumeroOfertas(entidad, contador, numCotizacion, exito) {
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
      // console.log(data);
      // var datos = data.Data;
      // var message = data.Message;
      // var success = data.Success;
      // resolve()
    },
    error: function (error) {
      console.log(error);
      // reject(error)
    },
  });
}

var idCotizacion = "";
var contErrProtocoloCotizar = 0;

var aseguradorasFallidas = [];
var aseguradorasIntentadas = [];
var primerIntentoRealizado = false;

const agregarAseguradoraFallida = (_aseguradora) => {
  const result = aseguradorasFallidas.find(
    (aseguradoras) => aseguradoras == _aseguradora
  );
  if (result !== undefined) return;
  aseguradorasFallidas.push(_aseguradora);
};

const comprobarFallida = (_aseguradora) => {
  const result = aseguradorasFallidas.find(
    (aseguradoras) => aseguradoras == _aseguradora
  );
  if (result !== undefined) return true;

  return false;
};

document
  .querySelector("#btnReCotizarFallidas")
  .addEventListener("click", () => {
    cotizarOfertas();
  });

// desactive
//console.log(permisosPlantilla)
// Captura los datos suministrados por el cliente y los envia al API para recibir la cotizacion.
function cotizarOfertas() {
  var codigoFasecolda1 = document.getElementById("txtFasecolda");
  var contenido = codigoFasecolda1.value;

  // Obtener el cuarto y quinto dígito de la variable contenido
  var cuartoDigito = contenido.charAt(3);
  var quintoDigito = contenido.charAt(4);

  // Verificar si el cuarto dígito es igual a 0 y eliminarlo si es así
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
        location.reload();
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
        location.reload();
      }, 2000); // 2000 milisegundos = 2 segundos
    });
    // Salir del código aquí para evitar la ejecución del resto del código
    return;
  }

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
  var dia = document.getElementById("dianacimiento").value;
  var mes = document.getElementById("mesnacimiento").value;
  var anio = document.getElementById("anionacimiento").value;
  var FechaNacimiento = anio + "-" + mes + "-" + dia;
  var Genero = document.getElementById("genero").value;
  var estadoCivil = document.getElementById("estadoCivil").value;
  var celularAseg = document.getElementById("txtCelular").value;
  var emailAseg = document.getElementById("txtCorreo").value;
  var direccionAseg = document.getElementById("direccionAseg").value;

  var CodigoClase = document.getElementById("CodigoClase").value;
  var CodigoMarca = document.getElementById("CodigoMarca").value;
  var CodigoLinea = document.getElementById("CodigoLinea").value;
  var claseVeh = document.getElementById("txtClaseVeh").value;
  var marcaVeh = document.getElementById("txtMarcaVeh").value;
  var modeloVeh = document.getElementById("txtModeloVeh").value;
  var lineaVeh = document.getElementById("txtReferenciaVeh").value;

  var LimiteRC = document.getElementById("LimiteRC").value;
  var CoberturaEstado = document.getElementById("CoberturaEstado").value;
  var ValorAccesorios = document.getElementById("ValorAccesorios").value;
  var CodigoVerificacion = document.getElementById("CodigoVerificacion").value;
  var AniosSiniestro = document.getElementById("AniosSiniestro").value;
  var AniosAsegurados = document.getElementById("AniosAsegurados").value;
  var NivelEducativo = document.getElementById("NivelEducativo").value;
  var Estrato = document.getElementById("Estrato").value;

  var fasecoldaVeh = document.getElementById("txtFasecolda").value;
  var valorFasecolda = document.getElementById("txtValorFasecolda").value;
  var DptoCirculacion = document.getElementById("DptoCirculacion").value;
  var ciudadCirculacion = document.getElementById("ciudadCirculacion").value;
  var isBenefOneroso = $("input:radio[name=oneroso]:checked").val(); // Valida que alguno de los 2 este selecionado
  var benefOneroso = document.getElementById("benefOneroso").value;
  var TokenPrevisora = document.getElementById("previsoraToken").value;
  var intermediario = document.getElementById("intermediario").value;

  /**
   * Variables para SBS
   */
  var cre_sbs_usuario = document.getElementById("cre_sbs_usuario").value;
  var cre_sbs_contrasena = document.getElementById("cre_sbs_contrasena").value;

  /**
   * Variables para ESTADO
   */
  var cre_est_usuario = document.getElementById("cre_est_usuario").value;
  var cre_equ_contrasena = document.getElementById("cre_equ_contrasena").value;
  var Cre_Est_Entity_Id = document.getElementById("Cre_Est_Entity_Id").value;
  var cre_est_zona = document.getElementById("cre_est_zona").value;

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
  var livianos_productos = document.getElementById(
    "cre_axa_livianos_productos"
  ).value;

  /**
   * Variables para Bolivar
   */
  var cre_bol_api_key = document.getElementById("cre_bol_api_key").value;
  var cre_bol_claveAsesor = document.getElementById(
    "cre_bol_claveAsesor"
  ).value;

  /**
   * Variables de Solidaria
   */
  var cre_sol_cod_sucursal = document.getElementById(
    "cre_sol_cod_sucursal"
  ).value;
  var cre_sol_cod_per = document.getElementById("cre_sol_cod_per").value;
  var cre_sol_cod_tipo_agente = document.getElementById(
    "cre_sol_cod_tipo_agente"
  ).value;
  var cre_sol_cod_agente = document.getElementById("cre_sol_cod_agente").value;
  var cre_sol_cod_pto_vta = document.getElementById(
    "cre_sol_cod_pto_vta"
  ).value;
  var cre_sol_grant_type = document.getElementById("cre_sol_grant_type").value;
  var cre_sol_Cookie_token = document.getElementById(
    "cre_sol_Cookie_token"
  ).value;
  var cre_sol_token = document.getElementById("cre_sol_token").value;
  var cre_sol_fecha_token = document.getElementById(
    "cre_sol_fecha_token"
  ).value;

  /**
   * Variables de Previsora
   */
  var cre_pre_username = document.getElementById(
    "cre_pre_Username"
  ).value;
  var cre_pre_password= document.getElementById("cre_pre_Password").value;
  var cre_pre_agentcode = document.getElementById(
    "cre_pre_AgentCode"
  ).value;
  var cre_pre_sourcecode = document.getElementById("cre_pre_SourceCode").value;
  var cre_pre_bussinedId = document.getElementById(
    "cre_pre_BusinessId"
  ).value;

  var aseguradoras_autorizar = JSON.parse(
    document.getElementById("aseguradoras").value
  );
  // desactive
  //console.log(aseguradoras_autorizar)

  if (ciudadCirculacion.length == 4) {
    ciudadCirculacion = "0" + ciudadCirculacion;
  } else if (ciudadCirculacion.length == 3) {
    ciudadCirculacion = "00" + ciudadCirculacion;
  }

  if (
    fasecoldaVeh != "" &&
    valorFasecolda != "" &&
    tipoUsoVehiculo != "" &&
    tipoServicio != "" &&
    DptoCirculacion != "" &&
    ciudadCirculacion != "" &&
    isBenefOneroso != undefined
  ) {
    if (
      placa != "" &&
      tipoDocumentoID != "" &&
      numDocumentoID != "" &&
      Nombre != "" &&
      Apellido1 != "" &&
      dia != "" &&
      mes != "" &&
      anio != "" &&
      Genero != "" &&
      estadoCivil != ""
    ) {
      $("#loaderOferta").html(
        '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Consultando Ofertas...</strong>'
      );
      var myHeaders = new Headers();
      myHeaders.append("Content-Type", "application/json");

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
        CodigoClase: CodigoClase,
        CodigoFasecolda: fasecoldaVeh,
        Modelo: modeloVeh,
        ValorAsegurado: valorFasecolda,
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
        TokenPrevisora: TokenPrevisora,
        intermediario: intermediario,
        SBS: {
          cre_sbs_usuario: cre_sbs_usuario,
          cre_sbs_contrasena: cre_sbs_contrasena,
        },
        PREVISORA: {
          cre_pre_username: cre_pre_username,
          cre_pre_password: cre_pre_password,
          cre_pre_agentcode: cre_pre_agentcode,
          cre_pre_sourcecode: cre_pre_sourcecode,
          cre_pre_bussinedId: cre_pre_bussinedId
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
        ESTADO: {
          cre_est_usuario: cre_est_usuario,
          cre_equ_contrasena: cre_equ_contrasena,
          Cre_Est_Entity_Id: Cre_Est_Entity_Id,
          cre_est_zona: cre_est_zona,
        },
        Bolivar: {
          cre_bol_api_key: cre_bol_api_key,
          cre_bol_claveAsesor: cre_bol_claveAsesor,
        },
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
          livianos_productos: livianos_productos,
        },
        SOLIDARIA: {
          cre_sol_cod_sucursal: cre_sol_cod_sucursal,
          cre_sol_cod_per: cre_sol_cod_per,
          cre_sol_cod_tipo_agente: cre_sol_cod_tipo_agente,
          cre_sol_cod_agente: cre_sol_cod_agente,
          cre_sol_cod_pto_vta: cre_sol_cod_pto_vta,
          cre_sol_grant_type: cre_sol_grant_type,
          cre_sol_Cookie_token: cre_sol_Cookie_token,
          cre_sol_token: cre_sol_token,
          cre_sol_fecha_token: cre_sol_fecha_token,
        },
      };
      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: JSON.stringify(raw),
        redirect: "follow",
      };

      if (!primerIntentoRealizado) {
        //menosVeh();

        const aseguradorasCoti = Object.keys(aseguradoras_autorizar).filter(
          (aseguradora) => aseguradoras_autorizar[aseguradora]["A"] === "1"
        );
        if (aseguradoras_autorizar.Previsora.A !== "1") {
          var mensajePrevisora = document.getElementById("mensajePrevisora");
          mensajePrevisora.style.display = "none"; // o cualquier otro valor como 'inline', 'flex', etc.
        }
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
        // desactive
        //console.log(aseguradorasCredenciales)
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
            Celular: celularAseg,
            Correo: emailAseg,
            direccionAseg: direccionAseg,
            CodigoClase: "1",
            Clase: claseVeh,
            Marca: marcaVeh,
            Modelo: modeloVeh,
            Linea: lineaVeh,
            Fasecolda: fasecoldaVeh,
            ValorAsegurado: valorFasecolda,
            tipoUsoVehiculo: tipoUsoVehiculo,
            tipoServicio: tipoServicio,
            Departamento: DptoCirculacion,
            Ciudad: ciudadCirculacion,
            benefOneroso: benefOneroso,
            idCotizacion: idCotizacion,
            mundial: null,
            credenciales: aseguradorasCredenciales,
          },
          cache: false,
          success: function (data) {
            document.querySelector("#btnCotizar").disabled = true;
            const contenParrilla = document.querySelector("#contenParrilla");
            parrillaCotizaciones.style.display = "block";
            contenParrilla.style.display = "block";
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
                nuevaFila.innerHTML = `
                    <td>${aseguradora}</td>
                    <td style="text-align: center;"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i></td>
                    <td style="text-align: center;">${contadorOfertas}</td>
                    <td>Nuevo Valor para Response</td>
                    <td>Nuevo Valor para Products</td>
                    <td>Nuevo Valor para Observation</td>
                  `;
                tablaResumenCotBody.appendChild(nuevaFila);
              }
            };

            const mostrarAlertarCotizacionFallida = (aseguradora, mensaje) => {
              if (aseguradora == "Estado2") {
                aseguradora = "Estado";
              }
              // console.log(aseguradora);
              // console.log(mensaje);
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
                //   console.log(`${aseguradora} tiene alertas iguales: "${observacionesActuales}" === "${mensaje}"`);
                // }
              } else {
                console.log(mensaje);
                // Si no existe, crea una nueva fila
                const nuevaFila = document.createElement("tr");
                nuevaFila.setAttribute("data-aseguradora", aseguradora);
                nuevaFila.innerHTML = `
                        <td>${aseguradora}</td>
                        <td style="text-align: center;"><i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i></td>
                        <td style="text-align: center;">0</td> <!-- Valor predeterminado para 'Productos cotizados' -->
                        <td>${mensaje}</td> <!-- Valor predeterminado para 'Observaciones' -->
                    `;

                // Agregar la fila a la tabla
                tablaResumenCotBody.appendChild(nuevaFila);
              }
            };

            // console.log(aseguradorasCoti); // Esto imprimirá el array con los nombres de aseguradoras autorizadas

            aseguradorasCoti.forEach((aseguradora) => {
              let url;

              // if (aseguradora === "Mapfre") {
              //   url = `https://grupoasistencia.com/motor_webservice_tst2/mapfrecotizacion4?callback=myCallback`;
              // } else
              // if (aseguradora === "AXA") {
              //   url = `https://grupoasistencia.com/motor_webservice_tst2/AXA?callback=myCallback`;
              // } else

              if (aseguradora === "HDI") {
                url = `https://grupoasistencia.com/motor_webservice/HdiPlus?callback=myCallback`;
              } else if (aseguradora === "Zurich") {
                const planes = ["FULL"];
                planes.forEach((plan) => {
                  let body = JSON.parse(requestOptions.body);
                  body.plan = plan;
                  body.Email = "@gmail.com";
                  body.Email2 = Math.round(Math.random() * 999999) + body.Email;
                  requestOptions.body = JSON.stringify(body);
                  url = `https://grupoasistencia.com/motor_webservice/${aseguradora}_autos?callback=myCallback`;

                  cont.push(
                    fetch(url, requestOptions)
                      .then((res) => {
                        if (!res.ok) throw Error(res.statusText);
                        return res.json();
                      })
                      .then((ofertas) => {
                        if (typeof ofertas.Resultado !== "undefined") {
                          validarProblema("Zurich", ofertas);
                          agregarAseguradoraFallida(plan);
                          let mensaje = "";
                          if (ofertas.Mensajes.length == 1) {
                            mensaje = ofertas.Mensajes[0];
                          } else {
                            ofertas.Mensajes.map((element, index) => {
                              if (element.includes("Lo sentimos")) {
                                mensaje += " - " + element;
                              }
                              if (element.includes("Referred")) {
                                if (index == 2) {
                                  mensaje += " - " + element;
                                } else {
                                  mensaje += element;
                                }
                              }
                            });
                          }
                          mostrarAlertarCotizacionFallida(`Zurich`, mensaje);
                        } else {
                          const contadorPorEntidad = validarOfertas(
                            ofertas,
                            "Zurich",
                            1
                          );
                          mostrarAlertaCotizacionExitosa(
                            `Zurich`,
                            contadorPorEntidad
                          );
                        }
                      })
                      .catch((err) => {
                        agregarAseguradoraFallida(plan);
                        mostrarAlertarCotizacionFallida(
                          "Zurich",
                          "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                        );
                        console.error(err);
                      })
                  );
                });
                return; // Salir del bucle después de procesar Zurich
              } else if (aseguradora === "Estado") {
                const aseguradorasEstado = ["Estado", "Estado2"]; // Agrega más aseguradoras según sea necesario
                aseguradorasEstado.forEach((aseguradora) => {
                  let successAseguradora = true;
                  cont.push(
                    fetch(
                      `https://grupoasistencia.com/motor_webservice/${aseguradora}?callback=myCallback`,
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
                          agregarAseguradoraFallida("Estado");
                          validarProblema(aseguradora, result);
                          result[0].Mensajes.forEach((mensaje) => {
                            mostrarAlertarCotizacionFallida(
                              aseguradora,
                              mensaje
                            );
                          });
                        } else {
                          const contadorPorEntidad = validarOfertas(
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
                        agregarAseguradoraFallida("Estado");
                        mostrarAlertarCotizacionFallida(
                          aseguradora,
                          "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                        );
                        console.error(err);
                      })
                  );
                });
                return; // Salir del bucle después de procesar Estado
                // Construir la URL de la solicitud para cada aseguradora
              } else {
                url = `https://grupoasistencia.com/motor_webservice/${aseguradora}_autos?callback=myCallback`;
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
                      agregarAseguradoraFallida(aseguradora);
                      validarProblema(aseguradora, ofertas);
                      ofertas[0].Mensajes.forEach((mensaje) => {
                        mostrarAlertarCotizacionFallida(aseguradora, mensaje);
                      });
                    } else {
                      const contadorPorEntidad = validarOfertas(
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
                    agregarAseguradoraFallida(aseguradora);
                    mostrarAlertarCotizacionFallida(
                      aseguradora,
                      "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                    );
                    console.error(err);
                  })
              );
            });

            Promise.all(cont).then(() => {
              // $("#btnCotizar").hide();
              $("#loaderOferta").html("");
              //$("#loaderOfertaBox").css("display", "none");
              swal
                .fire({
                  title: "¡Proceso de Cotización Finalizada!",
                  text: "¿Deseas incluir la financiación con Finesa a 11 cuotas?",
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
                    document.getElementById("btnReCotizarFallidas").disabled = true;
                    $("#loaderOferta").html(
                      '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Cotizando en Finesa...</strong>'
                    );
                    cotizarFinesa(cotizacionesFinesa);
                  } else if (result.isDismissed) {
                    if (result.dismiss === "cancel") {
                      // console.log("El usuario seleccionó 'No'");
                      $("#loaderOferta").html("");
                      $("#loaderOfertaBox").css("display", "none");
                    } else if (result.dismiss === "backdrop") {
                      $("#loaderOferta").html("");
                      $("#loaderOfertaBox").css("display", "none");
                    }
                  }
                });
              document.querySelector(".button-recotizar").style.display =
                "block";
              /* Se monta el botón para generar el pdf con 
                    el valor de la variable idCotizacion */
              const contentCotizacionPDF = document.querySelector(
                "#contenCotizacionPDFLivianos"
              );
              contentCotizacionPDF.innerHTML = `  
                                                      <div class="col-xs-12" style="width: 100%;">
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
                                                      </div>
                                                          `;
              $("#btnParrillaPDF").click(function () {
                const todosOn = $(".classSelecOferta:checked").length;
                const idCotizacionPDF = idCotizacion;
                const checkboxAsesor = $("#checkboxAsesor");

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
                    let url = `extensiones/tcpdf/pdf/comparador.php?cotizacion=${idCotizacionPDF}`;
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
      } else {
        //ZONA RECOTIZACIÓN//
        $("#loaderRecotOferta").html(
          '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Recotizando Ofertas...</strong>'
        );
        const btnRecotizar = document.getElementById("btnReCotizarFallidas");
        btnRecotizar.disabled = true;
        const contenParrilla = document.querySelector("#contenParrilla");
        raw.cotizacion = idCotizacion;

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
            nuevaFila.innerHTML = `
                <td>${aseguradora}</td>
                <td style="text-align: center;"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i></td>
                <td style="text-align: center;">${contadorOfertas}</td>
                <td>Nuevo Valor para Response</td>
                <td>Nuevo Valor para Products</td>
                <td>Nuevo Valor para Observation</td>
              `;
            tablaResumenCotBody.appendChild(nuevaFila);
          }
        };

        const mostrarAlertarCotizacionFallida = (aseguradora, mensaje) => {
          if (aseguradora == "Estado2") {
            aseguradora = "Estado";
          }
          // console.log(aseguradora);
          // console.log(mensaje);
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
            //   console.log(`${aseguradora} tiene alertas iguales: "${observacionesActuales}" === "${mensaje}"`);
            // }
          } else {
            // Si no existe, crea una nueva fila
            const nuevaFila = document.createElement("tr");
            nuevaFila.setAttribute("data-aseguradora", aseguradora);
            nuevaFila.innerHTML = `
                    <td>${aseguradora}</td>
                    <td style="text-align: center;"><i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i></td>
                    <td style="text-align: center;">0</td> <!-- Valor predeterminado para 'Productos cotizados' -->
                    <td>${mensaje}</td> <!-- Valor predeterminado para 'Observaciones' -->
                `;

            // Agregar la fila a la tabla
            tablaResumenCotBody.appendChild(nuevaFila);
          }
        };

        //console.log(aseguradorasFallidas)
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

        /* Solidaria */
        const solidariaPromise = comprobarFallida("Solidaria")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Solidaria_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("Solidaria");
                  validarProblema("Solidaria", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("Solidaria", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('Solidaria');
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Solidaria",
                    1
                  );
                  mostrarAlertaCotizacionExitosa(
                    "Solidaria",
                    contadorPorEntidad
                  );
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Solidaria");
                mostrarAlertarCotizacionFallida(
                  "Solidaria",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(solidariaPromise);

        /* Previsora */
        const previsoraPromise = comprobarFallida("Previsora")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Previsora_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("Previsora");
                  validarProblema("Previsora", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("Previsora", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('Previsora');
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Previsora",
                    1
                  );
                  mostrarAlertaCotizacionExitosa(
                    "Previsora",
                    contadorPorEntidad
                  );
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Previsora");
                mostrarAlertarCotizacionFallida(
                  "Previsora",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(previsoraPromise);

        /* Equidad */
        const equidadPromise = comprobarFallida("Equidad")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Equidad_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("Equidad");
                  validarProblema("Equidad", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("Equidad", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('Equidad');
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Equidad",
                    1
                  );
                  mostrarAlertaCotizacionExitosa("Equidad", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Equidad");
                mostrarAlertarCotizacionFallida(
                  "Equidad",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(equidadPromise);

        /* Mapfre */
        const mapfrePromise = comprobarFallida("Mapfre")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Mapfre_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("Mapfre");
                  validarProblema("Mapfre", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("Mapfre", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('Mapfre');
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Mapfre",
                    1
                  );
                  mostrarAlertaCotizacionExitosa("Mapfre", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Mapfre");
                mostrarAlertarCotizacionFallida(
                  "Mapfre",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(mapfrePromise);

        /* Bolivar */
        const bolivarPromise = comprobarFallida("Bolivar")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Bolivar_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("Bolivar");
                  validarProblema("Bolivar", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("Bolivar", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('Bolivar');
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Bolivar",
                    1
                  );
                  mostrarAlertaCotizacionExitosa("Bolivar", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Bolivar");
                mostrarAlertarCotizacionFallida(
                  "Bolivar",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(bolivarPromise);

        /* HDI */
        const HDIPromise = comprobarFallida("HDI")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/HdiPlus?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("HDI");
                  validarProblema("HDI", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("HDI", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('HDI');
                  const contadorPorEntidad = validarOfertas(ofertas, "HDI", 1);
                  mostrarAlertaCotizacionExitosa("HDI", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("HDI");
                mostrarAlertarCotizacionFallida(
                  "HDI",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(HDIPromise);

        // Para 'FULL'
        const ZFullPromise = comprobarFallida("FULL")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Zurich_autos?callback=myCallback",
              {
                ...requestOptions,
                method: "POST",
                headers: {
                  ...requestOptions.headers,
                  "Content-Type": "application/json",
                },
                body: JSON.stringify({
                  ...JSON.parse(requestOptions.body),
                  plan: "FULL",
                  Email2: Math.round(Math.random() * 999999) + "@gmail.com",
                }),
              }
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas.Resultado !== "undefined") {
                  let plan = "FULL";
                  validarProblema("Zurich", ofertas);
                  agregarAseguradoraFallida(plan);
                  let mensaje = "";
                  ofertas.Mensajes.map((element, index) => {
                    if (element.includes("Referred")) {
                      if (index == 2) {
                        mensaje += " - " + element;
                      } else {
                        mensaje += element;
                      }
                    }
                  });
                  mostrarAlertarCotizacionFallida(`Zurich`, mensaje);
                } else {
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Zurich",
                    1
                  );
                  mostrarAlertaCotizacionExitosa("Zurich", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Zurich");
                mostrarAlertarCotizacionFallida(
                  "Zurich",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(ZFullPromise);

        /* Estado */
        const aseguradorasEstado = ["Estado", "Estado2"]; // Agrega más aseguradoras según sea necesario
        aseguradorasEstado.forEach((aseguradora) => {
          let successAseguradora = true;
          const aseguradoraPromise = comprobarFallida(aseguradora)
            ? fetch(
                `https://grupoasistencia.com/motor_webservice/${aseguradora}?callback=myCallback`,
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
                    agregarAseguradoraFallida("Estado");
                    validarProblema(aseguradora, result);
                    result[0].Mensajes.forEach((mensaje) => {
                      mostrarAlertarCotizacionFallida(aseguradora, mensaje);
                    });
                  } else {
                    const contadorPorEntidad = validarOfertas(
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
                  agregarAseguradoraFallida("Estado");
                  mostrarAlertarCotizacionFallida(
                    aseguradora,
                    "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                  );
                  console.error(err);
                })
            : Promise.resolve();

          cont.push(aseguradoraPromise);
        });

        /* Liberty */
        const libertyPromise = comprobarFallida("Liberty")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Liberty_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("Liberty");
                  validarProblema("Liberty", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("Liberty", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('Liberty');
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Liberty",
                    1
                  );
                  mostrarAlertaCotizacionExitosa("Liberty", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Liberty");
                mostrarAlertarCotizacionFallida(
                  "Liberty",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(libertyPromise);

        /* Allianz */
        const allianzPromise = comprobarFallida("Allianz")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/Allianz_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("Allianz");
                  validarProblema("Allianz", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("Allianz", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('Allianz');
                  const contadorPorEntidad = validarOfertas(
                    ofertas,
                    "Allianz",
                    1
                  );
                  mostrarAlertaCotizacionExitosa("Allianz", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("Allianz");
                mostrarAlertarCotizacionFallida(
                  "Allianz",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(allianzPromise);

        const axaPromise = comprobarFallida("AXA")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/AXA_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("AXA");
                  validarProblema("AXA", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("AXA", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('AXA');
                  const contadorPorEntidad = validarOfertas(ofertas, "AXA", 1);
                  mostrarAlertaCotizacionExitosa("AXA", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("AXA");
                mostrarAlertarCotizacionFallida(
                  "AXA",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(axaPromise);

        const sbsPromise = comprobarFallida("SBS")
          ? fetch(
              "https://grupoasistencia.com/motor_webservice/SBS_autos?callback=myCallback",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas[0].Resultado !== "undefined") {
                  agregarAseguradoraFallida("SBS");
                  validarProblema("SBS", ofertas);
                  ofertas[0].Mensajes.forEach((mensaje) => {
                    mostrarAlertarCotizacionFallida("SBS", mensaje);
                  });
                } else {
                  // eliminarAseguradoraFallida('SBS');
                  const contadorPorEntidad = validarOfertas(ofertas, "SBS", 1);
                  mostrarAlertaCotizacionExitosa("SBS", contadorPorEntidad);
                }
              })
              .catch((err) => {
                agregarAseguradoraFallida("SBS");
                mostrarAlertarCotizacionFallida(
                  "SBS",
                  "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial"
                );
                console.error(err);
              })
          : Promise.resolve();

        cont.push(sbsPromise);

        Promise.all(cont).then(() => {
          $("#loaderOferta").html("");
          $("#loaderRecotOferta").html("");
          let nuevas = cotizacionesFinesa.filter((cotizaciones) => 
            cotizaciones.cotizada === null
          );
          if(nuevas.length > 0){
            swal
            .fire({
              title: "¡Proceso de Re-Cotización Finalizada!",
              text: "¿Deseas incluir la financiación con Finesa a 11 cuotas?",
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
                $("#loaderRecotOfertaBox").css("display", "block");
                $("#loaderRecotOferta").html(
                  '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong>Re-Cotizando en Finesa...</strong>'
                );
                let btnRecot = document.getElementById("btnReCotizarFallidas");
                btnRecot.disabled = true;
                cotizarFinesa(cotizacionesFinesa);
              } else if (result.isDismissed && cotizacionesFinesaMotos) {
                if (result.dismiss === "cancel") {
                  // console.log("El usuario seleccionó 'No'");
                  $("#loaderRecotOfertaBox").css("display", "none");
                  $("#loaderRecotOferta").html("");
                } else if (result.dismiss === "backdrop") {
                  $("#loaderRecotOfertaBox").css("display", "none");
                  $("#loaderRecotOferta").html("");
                }
              }
            });
          } else {
            swal
            .fire({
              title: "¡Proceso de Re-Cotización Finalizada!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            })
          }
          
        });
      }
      let zurichErrors = true;
      let zurichSuccess = true;
      let successEstado = true;
    }
  }
}

// function createModal(
//   title,
//   selectorModal,
//   btnTriggerOpen,
//   width,
//   height,
//   btnTriggerClose = null
// ) {
//   $(selectorModal).dialog({
//     autoOpen: true,
//     modal: true,
//     width: width, // overcomes width:'auto' and maxWidth bug
//     maxWidth: width,
//     height: "auto",
//     fluid: true, //new option
//     resizable: false,
//     title: title,
//     dialogClass: "no-close",
//     show: { effect: "slide", duration: 500, direction: "down" }, // Efecto de slide hacia abajo
//     hide: { effect: "slide", duration: 500, direction: "down" }, // Efecto de slide hacia abajo
//     open: function (event, ui) {
//       // Cambiar el color del título del diálogo
//       $(this).prev().find(".ui-dialog-title").css({
//         color: "white",
//         "font-weight": "lighter",
//       });
//     },
//   });
//   $(btnTriggerOpen)
//     .button()
//     .click(function () {
//       $(selectorModal).dialog("option", "width", width);
//       $(selectorModal).dialog("option", "height", height);
//       $(selectorModal).dialog("option", "resizable", false);
//       $(selectorModal).dialog("open");
//     });
//   if (btnTriggerClose) {
//     $(btnTriggerClose)
//       .button()
//       .click(function () {
//         $(selectorModal).dialog("close");
//       });
//   }
// }

// createModal(
//   "Modal Test",
//   ".modal-body2",
//   "#btnCotizar",
//   400,
//   500,
//   "#btn-cerrar-fasecolda"
// );

// Consultar datos del vehiculo
document
  .querySelector("#btn-consultar-fasecolda")
  .addEventListener("click", (e) => {
    const fasecolda = document.querySelector("#txtFasecolda_modal").value;
    const modelo = document.querySelector("#txtModeloVeh_modal").value;
    if (fasecolda === "" || modelo === "") {
      return;
    }
    consulDatosFasecolda(fasecolda, modelo)
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

function validarNumCotizaciones() {
  fecha1 = new Date();
  fecha2 = fecha1.toLocaleDateString();
  fecha3 = fecha2.split("/");
  fecha = fecha3[2] + "-" + fecha3[1] + "-" + fecha3[0];
  cotRestan = $("#cotRestanv").val();

  $.ajax({
    url: "ajax/compararFecha.php",
    method: "POST",
    data: { fecha },
    success: function (respuesta) {
      respuesta = parseInt(respuesta);

      cotRestan = parseInt(cotRestan);

      if (respuesta < cotRestan) {
      } else {
        Swal.fire({
          icon: "error",
          title:
            "¡Has llegado al límite de cotizaciones diarias... Inténtalo de nuevo mañana!.",
          confirmButtonText: "Cerrar",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location = "inicio";
          } else if (result.isDenied) {
          }
        });

        setTimeout(function () {
          window.location = "inicio";
        }, 5000);
      }
    },
  });
}

$("#btnConsultarVehmanualbuscador").click(function () {
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

          document.getElementById("formularioVehiculo").style.display = "none";
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
      },
    });
  }
});

// $("#btnConsultarVehmanual").click(function () {
//   var fasecolda = document.getElementById("fasecoldabuscadormanual").value;
//   var modelo = document.getElementById("modelobuscadormanual").value;

//   if (fasecolda != "" && modelo != "") {
//     $.ajax({
//       type: "POST",
//       url: "src/fasecolda/consulDatosFasecolda.php",
//       dataType: "json",
//       data: {
//         fasecolda: fasecolda,
//         modelo: modelo,
//       },
//       success: function (data) {
//         if (data.estado == undefined) {
//           alert("Vehículo no encontrado");
//         } else {
//           // console.log(data);
//           var claseVeh = data.clase;
//           var marcaVeh = data.marca;
//           var ref1Veh = data.referencia1;
//           var ref2Veh = data.referencia2;
//           var ref3Veh = data.referencia3;
//           var lineaVeh = ref1Veh + " " + ref2Veh + " " + ref3Veh;

//           var valorFasecVeh = data[modelo];
//           var valorVeh = Number(valorFasecVeh) * 1000;
//           var clase = data.clase;

//           $("#clasepesados").val(clase);

//           var placaVeh = $("#placaVeh").val();
//           if (placaVeh == "WWW404") {
//             $("#txtPlacaVeh").val("SIN PLACA - VEHÍCULO 0 KM").val();
//           } else {
//             $("#txtPlacaVeh").val(placaVeh).val();
//           }

//           document.getElementById("resumenVehiculo").style.display = "block";
//           document.getElementById("contenBtnCotizar").style.display = "block";
//           document.getElementById("headerAsegurado").style.display = "block";
//           document.getElementById("masA").style.display = "block";

//           document.getElementById("formularioVehiculo").style.display = "none";
//           document.getElementById("DatosAsegurado").style.display = "none";

//           document.getElementById("txtFasecolda").value = fasecolda;
//           document.getElementById("txtModeloVeh").value = modelo;
//           document.getElementById("txtMarcaVeh").value = data.marca;
//           document.getElementById("txtValorFasecolda").value = valorVeh;
//           document.getElementById("txtReferenciaVeh").value = lineaVeh;
//           document.getElementById("txtClaseVeh").value = claseVeh;
//         }
//       },
//     });
//   }
// });
