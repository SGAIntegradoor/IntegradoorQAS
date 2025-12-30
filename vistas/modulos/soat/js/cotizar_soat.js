$(document).ready(function () {
  var valorSoatGlobal = 0;
  const urlCompleta = window.location.href;

  const partes = urlCompleta.split("/");

  if (partes.includes("dev") || partes.includes("DEV")) {
    env = "dev";
  } else if (
    partes.includes("QAS") ||
    partes.includes("qas") ||
    partes.includes("Pruebas")
  ) {
    env = "qas";
  } else if (partes.includes("app") || partes.includes("App")) {
    env = "";
  }

  var permisos = JSON.parse(permisosPlantilla);

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

  var ceroKilometros = document.getElementById("txtEsCeroKmSi");

  // Función para filtrar caracteres especiales
  function filtrarCaracteresEspeciales(input) {
    var valor = input.value;
    var valorFiltrado = valor.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ ]/g, ""); // Permitir letras, espacios, "ñ" y vocales con tilde
    input.value = valorFiltrado;
  }

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
    Swal.fire({
      icon: "info",
      title: "",
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      html: `
        Para la emisión de vehículos 0 Km, comunícate con el área SOAT al número 
        <a href="https://api.whatsapp.com/send?phone=573013232210" target="_blank" style="color:#3085d6; text-decoration:underline;">
          301 323 2210
        </a>.
        La expedición tiene un tiempo de respuesta de 1 día hábil.
      `,
      showConfirmButton: true,
      confirmButtonText: "Nueva cotización",
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload(); // recarga la ubicación actual
      }
    });
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

  // Convierte la Placa ingresada en Mayusculas
  $("#numDocumentoID").change(function () {
    consultarAsegurado();
  });

  document.addEventListener("DOMContentLoaded", function () {
    var formulario = document.getElementById("formResumAseg"); // Reemplaza 'formulario' con el ID de tu formulario

    formulario.addEventListener("submit", function (event) {
      if (1 === 2) {
        event.preventDefault(); // Evita que el formulario se envíe
      }
    });
  });

  $("#btnConsultarPlaca").click(function () {
    $("#containerDataTable").hide();
    $(".card-container").hide();
    $("html, body").animate({ scrollTop: 0 }, 600);
    consulPlaca();
  });
  // let intermediario = document.getElementById("idIntermediario").value;
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
// Permite consultar la informacion del vehiculo por medio de la Placa (Seguros del Estado)
function consulPlaca(query = "1") {
  var numplaca = document.getElementById("placaVeh").value;

  let lastChar = numplaca.slice(-1);
  if (!isNaN(lastChar)) {
  } else {
    // Swal.fire({
    //   icon: "error",
    //   title: "Error",
    //   text: "La placa no coincide con el formato de vehiculos livianos",
    //   showConfirmButton: true,
    // }).then(() => {
    //   window.location.reload();
    // });
    // return false;
  }

  if (numplaca == "WWW404") {
    document.getElementById("formularioVehiculo").style.display = "block";
    $("#loaderPlaca").html("");
  } else {
    var valnumplaca = numplaca.toUpperCase(); // Convierte la Placa en Mayusculas
    let typeQuery = query != "2" ? numplaca != "" : numplaca != "";

    if (typeQuery) {
      $("btnConsultarPlaca2").remove();

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
        placa: valnumplaca,
        // intermediario: intermediario,
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
        "https://grupoasistencia.com/motor_webservice/consulta_veh_soat",
        requestOptions
      )
        .then(function (response) {
          if (!response.ok) {
            throw Error(response.statusText);
          }
          return response.json();
        })
        .then(function (myJson) {
          var codigoLinea = myJson.ConsultarInfoVehiculoRuntDocResult.linea;
          var modeloVehiculo =
            myJson.ConsultarInfoVehiculoRuntDocResult.aaaa_modelo;
          var codigoClase =
            myJson.ConsultarInfoVehiculoRuntDocResult.claseVehiculo;
          var idClase =
            myJson.ConsultarInfoVehiculoRuntDocResult.idClaseVehiculo;
          var codigoMarca = myJson.ConsultarInfoVehiculoRuntDocResult.marca;

          var servicio = myJson.ConsultarInfoVehiculoRuntDocResult.tipoServicio;
          var cilindraje = myJson.ConsultarInfoVehiculoRuntDocResult.cnt_cc;
          var pasajeros =
            myJson.ConsultarInfoVehiculoRuntDocResult.cnt_ocupantes;
          var motor = myJson.ConsultarInfoVehiculoRuntDocResult.noMotor;
          var chasis = myJson.ConsultarInfoVehiculoRuntDocResult.noChasis;
          var capacidad =
            myJson.ConsultarInfoVehiculoRuntDocResult.cnt_toneladas;

          var nroDocPropietario =
            myJson.ConsultarInfoVehiculoRuntDocResult.Propietarios.Propietario
              .noDocumento;

          const fechaInicioVigencia = new Date();
          const fechaFinVigencia = new Date(fechaInicioVigencia);
          fechaFinVigencia.setFullYear(fechaFinVigencia.getFullYear() + 1);
          //   $("#LimiteRC").val(limiteRCESTADO);

          $("#txtClaseVeh").val(codigoClase);
          $("#txtMarcaVeh").val(codigoMarca);
          $("#txtModeloVeh").val(modeloVehiculo);
          $("#txtLinea").val(codigoLinea);
          $("#txtServicio").val(servicio);
          $("#txtCilindraje").val(cilindraje);
          $("#txtPasajeros").val(pasajeros);
          $("#txtMotor").val(motor);
          $("#txtChasis").val(chasis);

          $("#lblDataTrip2Top").css("display", "none");
          $(".box").css("border-top", "0px");

          // document.getElementById("formularioVehiculo").style.display = "none";
          document.getElementById("headerAsegurado").style.display = "block";
          document.getElementById("contenSuperiorPlaca").style.display = "none";
          document.getElementById("resumenVehiculo").style.display = "block";
          document.getElementById("contenBtnCotizar").style.display = "block";
          $("#loaderPlaca").hide();
          //! Agregar esto a MOTOS y Pesados START
          $("#loaderPlaca2").html("");
          //! Agregar esto a MOTOS y Pesados END
          menosAseg();
          document.getElementById("contenBtnConsultarPlaca").style.display =
            "none";
          $("#contenSuperiorPlaca").css("display", "block");
          $("#txtConocesLaPlacaSi").prop("disabled", true);
          $("#txtConocesLaPlacaNo").prop("disabled", true);
          $("#placaVeh").prop("disabled", true);
          $("#loaderPlacaTwo").html(
            '<img src="vistas/img/plantilla/loader-loading.gif" width="34" height="34"><strong> Cotizando SOAT...</strong>'
          );

          $.ajax({
            type: "POST",
            url: "https://grupoasistencia.com/motor_webservice/calcular_pol_soat",
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            processData: false,
            data: JSON.stringify({
              Placa: valnumplaca,
              Clase: idClase,
              Cilindraje: cilindraje,
              Capacidad: capacidad,
              Modelo: modeloVehiculo,
              Pasajeros: pasajeros,
              FechaInicioVigencia: "?",
              FechaFinVigencia: "?",
              NumeroDocumento: nroDocPropietario,
            }),
            success: function (data) {
              console.log(typeof data.CalcularPolizaResult.ValorTotalPagar);
              let valorAPagarSoat = Number(
                data.CalcularPolizaResult.ValorTotalPagar
              );
              let totalPagarSoat = valorAPagarSoat + 45000;
              valorSoatGlobal = valorAPagarSoat;
              $("#valorSoat").text(
                "$ " + valorAPagarSoat.toLocaleString("es-CO")
              );
              $("#totalPagarSoat").text(
                "$ " + totalPagarSoat.toLocaleString("es-CO")
              );
              $("#loaderPlacaTwo").html("");
              $(".containerResumenCoti").show();
            },
            error: function (error) {
              console.log("Error al cotizar SOAT: ", error);
              $("#loaderPlacaTwo").html("");
            },
          });
          return;
        })
        .catch(function (error) {
          console.log("Error al consultar la placa: ", error);
        });
    } else {
      Swal.fire({
        icon: "error",
        title: "Completa toda la información del formulario",
        text: "Para avanzar debes completa la informacion del formulario",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
      });
    }
  }
}

$("#radioSinComision").click(function () {
  sinComision = valorSoatGlobal + 20000;
  $("#totalPagarSoat").text("$ " + sinComision.toLocaleString("es-CO"));
});

$("#radioConComision").click(function () {
  conComision = valorSoatGlobal + 45000;
  $("#totalPagarSoat").text("$ " + conComision.toLocaleString("es-CO"));
});

$("#btnNuevaCoti").click(function () {
  window.location.reload();
});
