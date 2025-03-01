$(document).ready(function () {
  $("#btnCotizarMotos").click(function () {
    cotizarOfertasMotos();
  });

  $("#btnConsultarPlacaMotos").click(function () {
    consulPlacaMotos();
  });

  document
    .querySelector("#btnReCotizarFallidas")
    .addEventListener("click", () => {
      cotizarOfertasMotos();
    });
});

// Permite consultar la informacion del vehiculo por medio de la Placa (Seguros del Estado)
function consulPlacaMotos() {
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
        title: "Lo sentimos",
        text: restriccion,
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
                    location.reload();
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
                    title: "Lo sentimos",
                    text: restriccion,
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
          console.log("Parece que hubo un problema: \n", error);
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
      // console.log("Mapfre consulta");
      // console.log("Marca Cod:", marcaCod);
      // console.log("Clase:", clase);
      // console.log("Línea:", linea);
      // console.log("Modelo:", modelo);
      // console.log("Cilindraje:", cilindraje);
      // console.log("Código Fasecolda:", codFasecolda);
      // console.log("Aseguradora:", aseguradora);

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
          console.log(resp);
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
      console.log("Parece que hubo un problema: \n", error);
      document.getElementById("formularioVehiculo").style.display = "block";
      document.getElementById("headerAsegurado").style.display = "block";
      document.getElementById("masA").style.display = "block";
      document.getElementById("DatosAsegurado").style.display = "none";
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
      console.log("Parece que hubo un problema: \n", error);

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
function consulDatosFasecoldaMotos(codFasecolda, edadVeh) {
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
        }
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

// REGISTRA CADA UNA DE LAS OFERTAS COTIZADAS EN LA BD
function registrarOfertaMotos(
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
  UrlPdf,
  responsabilidad_civil_familiar,
  manual,
  pdf
) {
  
  return new Promise((resolve, reject) => {
    var idCotizOferta = idCotizacion;
    var numDocumentoID = document.getElementById("numDocumentoID").value;
    var placa = document.getElementById("placaVeh").value;
    if (manual == null) {
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
      },
      success: function (data) {
        console.log(PT, PP, CE, GR, logo, UrlPdf, manual, pdf, responsabilidad_civil_familiar, valorRC ,prima, placa, idCotizOferta, numIdentificacion, aseguradora, producto, numCotizOferta )
        // var datos = data.Data;
        var message = data.Message;
        var success = data.Success;
        resolve();
      },
      error: function (error) {
        console.log(error);
        reject(error);
      },
    });
  });
}

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
  var aseguradoraCredenciales = nombreAseguradora + "_C_motos";
  var permisosCredenciales = permisos[aseguradoraCredenciales];
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
                                      <div class="col-xs-12 col-sm-6 col-md-2 oferta-header">
                                          <h5 class='entidad'>${aseguradora} - ${producto}</h5>
                                          <h5 class='precio'>Desde $ ${prima}</h5>
                                          <p class='title-precio'>(IVA incluido)</p>
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

// VALIDA QUE LAS OFERTAS COTIZADAS HAYAN SIDO GUARDADAS EN SU TOTALIDAD
function validarOfertasMotos(ofertas, aseguradora, exito) {
  let contadorPorEntidad = {};

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
      0
    );

    // });
  });

  // Llamada a la función registrarNumeroOfertas para cada entidad
  Object.entries(contadorPorEntidad).forEach(([entidad, contador]) => {
    // const numCotizacion = ofertas.find(oferta => oferta.entidad === entidad)?.numero_cotizacion;
    var idCotizOferta = idCotizacion;
    registrarNumeroOfertasMotos(entidad, contador, idCotizOferta, exito);
  });

  return contadorPorEntidad;
}

//VERSION DEFINITIVA "validarProblema()""
function validarProblemaMotos(aseguradora, ofertas) {
  var idCotizOferta = idCotizacion;
  console.log(ofertas);

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
            console.log(datos);
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
          console.log(datos);
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
      // var datos = data.Data;
      var message = data.Message;
      var success = data.Success;
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

const agregarAseguradoraFallidaMotos = (_aseguradora) => {
  const result = aseguradorasFallidas.find(
    (aseguradoras) => aseguradoras == _aseguradora
  );
  if (result !== undefined) return;
  aseguradorasFallidas.push(_aseguradora);
  console.log(aseguradorasFallidas);
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

document
  .querySelector("#btnReCotizarFallidas")
  .addEventListener("click", () => {
    cotizarOfertasMotos();
  });

function cotizarOfertasMotos() {
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

  var DptoCirculacion = document.getElementById("DptoCirculacion").value;
  var ciudadCirculacion = document.getElementById("ciudadCirculacion").value;
  var isBenefOneroso = $("input:radio[name=oneroso]:checked").val(); // Valida que alguno de los 2 este selecionado
  var benefOneroso = document.getElementById("benefOneroso").value;
  
  var tipoUsoVehiculo = document.getElementById("txtTipoUsoVehiculo").value;
  var tipoServicio = document.getElementById("txtTipoServicio").value;

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
  console.log(aseguradoras_motos_autorizar);

  if (ciudadCirculacion.length == 4) {
    ciudadCirculacion = "0" + ciudadCirculacion;
  } else if (ciudadCirculacion.length == 3) {
    ciudadCirculacion = "00" + ciudadCirculacion;
  }

  //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  if (
    fasecoldaVeh != "" &&
    valorfasecoldaVeh != "" &&
    modelovehiculo != "" &&
    marca != "" &&
    linea != ""
  ) {
    $("#loaderOferta").html(
      '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Consultando Ofertas...</strong>'
    );
    $("#loaderRecotOferta").html(
      '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Recotizando Ofertas...</strong>'
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

    var requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: JSON.stringify(raw),
      redirect: "follow",
    };

    if (!primerIntentoRealizado) {
      const aseguradorasCoti = Object.keys(aseguradoras_motos_autorizar).filter(
        (aseguradora) => aseguradoras_motos_autorizar[aseguradora]["A"] === "1"
      );

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
        },
        cache: false,
        success: function (data) {
          const contenParrilla = document.querySelector("#contenParrilla");
          contenParrilla.style.display = "block";
          idCotizacion = data.id_cotizacion;
          raw.cotizacion = idCotizacion;
          var requestOptions = {
            method: "POST",
            headers: myHeaders,
            body: JSON.stringify(raw),
            redirect: "follow",
          };

          let cont = [];
          const aseguradorasExitosas = new Set();
          const mostrarAlertaCotizacionExitosa = (aseguradora) => {
            if (!aseguradorasExitosas.has(aseguradora)) {
              aseguradorasExitosas.add(aseguradora);
              document.querySelector(
                ".exitosas"
              ).innerHTML += `<span style="margin-right: 15px;"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>${aseguradora}</span>`;
            }
          };

          const mostrarAlertarCotizacionFallida = (aseguradora, mensaje) => {
            document.querySelector(
              ".fallidas"
            ).innerHTML += `<p><i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i><b>${aseguradora}:</b> ${mensaje}</p>`;
          };

          // console.log(aseguradorasCredenciales)

          /*SBS*/
          // cont.push(
          //   fetch("https://grupoasistencia.com/motor_webservice_tst/SBS", requestOptions)
          //   .then((res) => {
          //       if (!res.ok) throw Error(res.statusText);
          //       return res.json();
          //   })
          //   .then((ofertas) => {
          //       let result = ofertas
          //       if (typeof result[0].Resultado !== 'undefined') {
          //       agregarAseguradoraFallidaMotos('SBS')
          //       result[0].Mensajes.forEach(mensaje => {
          //           mostrarAlertarCotizacionFallida('SBS', mensaje)
          //       })
          //       } else {
          //       validarOfertasMotos(result);
          //       mostrarAlertaCotizacionExitosa('SBS')
          //       }
          //   })
          //   .catch((err) => {
          //       agregarAseguradoraFallidaMotos('SBS');
          //       mostrarAlertarCotizacionFallida('SBS', "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial");
          //       console.error(err);
          //   })
          // )

          /* AXA */
          // cont.push(
          //   fetch("https://grupoasistencia.com/motor_webservice_tst/AXA_tst", requestOptions)
          //   .then((res) => {
          //       if (!res.ok) throw Error(res.statusText);
          //       return res.json();
          //   })
          //   .then((ofertas) => {

          //       if (typeof ofertas[0].Resultado !== 'undefined') {
          //       agregarAseguradoraFallidaMotos('AXA')
          //       ofertas[0].Mensajes.forEach(mensaje => {
          //           mostrarAlertarCotizacionFallida('AXA', mensaje)
          //       })
          //       } else {
          //       validarOfertasMotos(ofertas)
          //       mostrarAlertaCotizacionExitosa('AXA')
          //       }
          //   })
          //   .catch((err) => {
          //       agregarAseguradoraFallidaMotos('AXA');
          //       mostrarAlertarCotizacionFallida('AXA', "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial");
          //       console.error(err);
          //   })
          // )

          /* LIBERTY */
          // cont.push(
          //   fetch("https://grupoasistencia.com/motor_webservice_tst/Liberty", requestOptions)
          //   .then((res) => {
          //       if (!res.ok) throw Error(res.statusText);
          //       return res.json();
          //   })
          //   .then((ofertas) => {
          //       if (typeof ofertas[0].Resultado !== 'undefined') {
          //       agregarAseguradoraFallidaMotos('Liberty')
          //       ofertas[0].Mensajes.forEach(mensaje => {
          //           mostrarAlertarCotizacionFallida('Liberty', mensaje)
          //       })
          //       } else {
          //       validarOfertasMotos(ofertas);
          //       mostrarAlertaCotizacionExitosa('Liberty')
          //       }
          //   })
          //   .catch((err) => {
          //       agregarAseguradoraFallidaMotos('Liberty');
          //       mostrarAlertarCotizacionFallida('Liberty', "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial");
          //       console.error(err);
          //   })
          // )

          /* Allianz */
          // cont.push(
          //   fetch("https://grupoasistencia.com/motor_webservice_tst2/Allianz_motos?callback=myCallback", requestOptions)
          //     .then((res) => {
          //       if (res.status === 500) {
          //           throw Error("Error interno del servidor (HTTP 500)");
          //       }
          //       if (!res.ok) {
          //           throw Error(res.statusText);
          //       }
          //       return res.json();
          //     })
          //     .then((ofertas) => {
          //       if (typeof ofertas[0].Resultado !== 'undefined') {
          //         agregarAseguradoraFallidaMotos('Allianz');
          //         // validarProblema('Allianz', ofertas);
          //         ofertas[0].Mensajes.forEach(mensaje => {
          //           mostrarAlertarCotizacionFallida('Allianz', mensaje)
          //         })
          //       } else {
          //         validarOfertasMotos(ofertas);
          //         mostrarAlertaCotizacionExitosa('Allianz')
          //       }
          //     })
          //     .catch((err) => {
          //       agregarAseguradoraFallidaMotos('Allianz')
          //       mostrarAlertarCotizacionFallida('Allianz', "Error de conexión. Intente de nuevo o comuníquese con el equipo comercial");
          //       console.error(err);
          //     })
          // );
          aseguradorasCoti.forEach((aseguradora) => {
            let url;

            if (aseguradora === "SBS") {
              url = `https://grupoasistencia.com/motor_webservice_tst/SBS`;
            } else if (aseguradora === "Liberty") {
              url = `https://grupoasistencia.com/motor_webservice_tst/Liberty`;
            } else if (aseguradora === "AXA") {
              url = `https://grupoasistencia.com/motor_webservice_tst/AXA_tst`;
            } else if (aseguradora === "Zurich") {
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
                        validarProblema("Zurich", ofertas);
                        agregarAseguradoraFallidaMotos(plan);
                        ofertas.Mensajes.forEach((mensaje) => {
                          mostrarAlertarCotizacionFallida(`Zurich`, mensaje);
                        });
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
            } else if (aseguradora === "Estado") {
              const aseguradorasEstado = ["Estado", "Estado2"]; // Agrega más aseguradoras según sea necesario
              aseguradorasEstado.forEach((aseguradora) => {
                let successAseguradora = true;
                cont.push(
                  fetch(
                    `https://grupoasistencia.com/motor_webservice_tst2/${aseguradora}?callback=myCallback`,
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
                );
              });
              return; // Salir del bucle después de procesar Estado
            } else {
              // Construir la URL de la solicitud para cada aseguradora
              url = `https://grupoasistencia.com/motor_webservice_tst2/${aseguradora}?callback=myCallback`;
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

          // Llamar a esta función cuando todas las promesas se resuelvan
          function ejecutarDespuesDePromesas() {
            setTimeout(function () {
              $("#btnCotizar").hide();
              $("#loaderOferta").html("");
              $("#loaderRecotOferta").html("");
              swal.fire({
                type: "success",
                title: "! Cotización Exitosa ¡",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
              });
              //  window.location = "index.php?ruta=editar-cotizacion&idCotizacion=" + idCotizacion;
              console.log("Se completó todo");
              document.querySelector(".button-recotizar").style.display =
                "block";

              /* Se monta el botón para generar el PDF con 
            el valor de la variable idCotizacion */
              const contentCotizacionPDF = document.querySelector(
                "#contenCotizacionPDF"
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
                      "¡Esta versión no tiene esta funcionalidad disponible!",
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
                      title: "¡Debes seleccionar al menos una oferta!",
                    });
                  } else {
                    let url = `extensiones/tcpdf/pdf/comparadorMotos.php?cotizacion=${idCotizacionPDF}`;
                    if (checkboxAsesor.is(":checked")) {
                      url += "&generar_pdf=1";
                    }
                    window.open(url, "_blank");
                  }
                }
              });
            }, 200); // Agrega el tiempo de retraso en milisegundos aquí
          }

          Promise.all(cont)
            .then(() => {
              ejecutarDespuesDePromesas(); // Llama a la función después de que todas las promesas se resuelvan
            })
            .catch((error) => {
              console.error(error);
            });
        },
      });
    } else {
      const contenParrilla = document.querySelector("#contenParrilla");
      raw.cotizacion = idCotizacion;

      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: JSON.stringify(raw),
        redirect: "follow",
      };
      let cont = [];

      const mostrarAlertaCotizacionExitosa = (aseguradora) => {
        document.querySelector(
          ".exitosas"
        ).innerHTML += `<span style="margin-right: 15px;"><i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;
                    "></i>${aseguradora}</span>
                    `;
      };

      const mostrarAlertarCotizacionFallida = (aseguradora, mensaje) => {
        document.querySelector(
          ".fallidas"
        ).innerHTML += `<p><i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i><b>${aseguradora}:</b> ${mensaje}</p>`;
      };

      /* Solidaria */
      if (comprobarFallida("Solidaria")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Solidaria",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              console.log(ofertas);
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("Solidaria");
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("Solidaria");
                eliminarAseguradoraFallida("Solidaria");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* Previsora */
      if (comprobarFallida("Previsora")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Previsora",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("Previsora");
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("Previsora");
                eliminarAseguradoraFallida("Previsora");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* Equidad */
      if (comprobarFallida("Equidad")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Equidad",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("Equidad");
                ofertas[0].Mensajes.forEach((mensaje) => {
                  mostrarAlertarCotizacionFallida("Equidad", mensaje);
                });
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("Equidad");
                eliminarAseguradoraFallida("Equidad");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      if (comprobarFallida("Mapfre")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/mapfrecotizacion4",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("Mapfre");
                ofertas[0].Mensajes.forEach((mensaje) => {
                  mostrarAlertarCotizacionFallida("Mapfre", mensaje);
                });
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("Mapfre");
                eliminarAseguradoraFallida("Mapfre");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* Bolivar */
      if (comprobarFallida("Bolivar")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Bolivar",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              console.log(ofertas);
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("Bolivar");
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("Bolivar");
                eliminarAseguradoraFallida("Bolivar");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* HDI */
      if (comprobarFallida("HDI")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/HDI",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("HDI");
              } else {
                console.log("Here2");
                let result = [];
                result.push(ofertas[0]);
                validarOfertas(result);
                mostrarAlertaCotizacionExitosa("HDI");
                eliminarAseguradoraFallida("HDI");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      let zurichErrors = true;
      let zurichSuccess = true;

      /* Zurich */
      if (comprobarFallida("Zurich")) {
        const planes = ["BASIC", "MEDIUM", "FULL"];
        let body = JSON.parse(requestOptions.body);
        planes.forEach((plan) => {
          body.plan = plan;
          body.Email = Math.round(Math.random() * 999999) + body.Email;
          requestOptions.body = JSON.stringify(body);
          cont.push(
            fetch(
              "https://grupoasistencia.com/motor_webservice_tst/Zurich",
              requestOptions
            )
              .then((res) => {
                if (!res.ok) throw Error(res.statusText);
                return res.json();
              })
              .then((ofertas) => {
                if (typeof ofertas.Resultado !== "undefined") {
                  agregarAseguradoraFallida("Zurich");
                  zurichErrors = false;
                } else {
                  validarOfertas(ofertas);
                  if (zurichSuccess) {
                    mostrarAlertaCotizacionExitosa("Zurich");
                    eliminarAseguradoraFallida("Zurich");
                    zurichSuccess = false;
                  }
                }
              })
              .catch((err) => console.error(err))
          );
        });
      }

      let successEstado = true;

      /* Estado */
      if (comprobarFallida("Estado")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Estado",
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
              } else {
                validarOfertas(result);
                if (successEstado) {
                  mostrarAlertaCotizacionExitosa("Estado");
                  eliminarAseguradoraFallida("Estado");
                  successEstado = false;
                }
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* Estado2 */
      if (comprobarFallida("Estado2")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Estado2",
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
                agregarAseguradoraFallida("Zurich2");
              } else {
                validarOfertas(result);
                if (successEstado) {
                  mostrarAlertaCotizacionExitosa("Estado");
                  eliminarAseguradoraFallida("Estado");
                  successEstado = false;
                }
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* Liberty */
      if (comprobarFallida("Liberty")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Liberty",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("Liberty");
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("Liberty");
                eliminarAseguradoraFallida("Liberty");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* Allianz */
      if (comprobarFallida("Allianz")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/Allianz",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              console.log(res);
              return res.json();
            })
            .then((ofertas) => {
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("Allianz");
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("Allianz");
                eliminarAseguradoraFallida("Allianz");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* AXA */
      if (comprobarFallida("AXA")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/AXA_tst",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              if (typeof ofertas[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("AXA");
                ofertas[0].Mensajes.forEach((mensaje) => {
                  mostrarAlertarCotizacionFallida("AXA", mensaje);
                });
              } else {
                validarOfertas(ofertas);
                mostrarAlertaCotizacionExitosa("AXA");
                eliminarAseguradoraFallida("AXA");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      /* SBS */
      if (comprobarFallida("SBS")) {
        cont.push(
          fetch(
            "https://grupoasistencia.com/motor_webservice_tst/SBS",
            requestOptions
          )
            .then((res) => {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
            })
            .then((ofertas) => {
              let result = ofertas;
              if (typeof result[0].Resultado !== "undefined") {
                agregarAseguradoraFallida("SBS");
              } else {
                validarOfertas(result);
                mostrarAlertaCotizacionExitosa("SBS");
                eliminarAseguradoraFallida("SBS");
              }
            })
            .catch((err) => {
              console.error(err);
            })
        );
      }

      Promise.all(cont).then(() => {
        $("#loaderOferta").html("");
        $("#loaderRecotOferta").html("");
        swal({
          type: "success",
          title: "! Re cotización completada ¡",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
        });
        console.log("Se completo la re-cotización");
      });
    }
  }
}
