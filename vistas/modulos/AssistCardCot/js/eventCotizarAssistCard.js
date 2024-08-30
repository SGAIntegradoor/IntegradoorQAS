//Constante con los nombre de productos, esto sepuede migrar despues a base de datos
// ToDo: Pasar esto por base de datos
const equivalencias = {
  "5D": "AC 60",
  "5C": "AC 150",
  "5B": "AC 250",
  "5E": "AC 35",
  GD: "AC 60",
  GC: "AC 150",
  HK: "AC 250",
  WS: "AC 110",
};

//Constantes con los planes permitidos, si se quiere agregar otro solo es colocar su codigo aqui y revisar las coberturas
// ToDo: Pasar esto por base de datos
const vacacionalPermitidos = ["5D", "5C", "5B", "5E"];
const estudiantilesPermitidos = ["WS"];
const empresarialPermitidos = ["GB", "GD", "GC", "HK"];

//Constantes con las coberturas de los planes
// ToDo: Pasar esto por base de datos o en su defecto pintar lo que llegue del servicio y no hardcode
const coberturasVacacional = {
  "5D": "60.000",
  "5C": "150.000",
  "5B": "250.000",
  "5E": "35.000",
};

const coberturasEmpresarial = {
  GB: "250.000",
  GD: "60.000",
  GC: "150.000",
  HK: "250.000",
};

const coberturasEstudiantil = {
  WS: "110.000",
  WR: "110.000",
};

const planesPorDestinoEstudiantiles = {
  "01": "10477", // Norte America // Norte America y Canada
  "02": "10475", // Europa // Internacional
  "03": "10473", // LATAM // Latinoamerica
  "04": "10473", // LATAM // Latinoamerica
  "05": "10475", // Africa // Internacional
  "06": "10475", // Asia // Internacional
  "07": "10475", // Oceania // Internacional
  "08": "10474", // Latam 365 Dias +
  "09": "10476", // Internacional 365 Dias +
  10: "10478", // Norte America y Canada 365 +
};

// Cargar el select de origen
function CargarSelectOrigen() {
  $("#lugarOrigen").html('<option value="">Cargando...</option>');
  $.ajax({
    url: "vistas/modulos/AssistCardCot/services/consultarOrigen.php", // Cambia la URL a la que corresponda para cargar las opciones del select
    success: function (resp) {
      $("#lugarOrigen").html(resp); // Inserta las opciones en el select
    },
  });
}
// ========================================================================================================================

// Cargar el select de destino
function CargarSelectDestino() {
  var opciones = [
    { value: "", text: "Selecciona..." },
    { value: "01", text: "Norte America" },
    { value: "02", text: "Europa" },
    { value: "03", text: "America Central & Caribe" },
    { value: "04", text: "Sur America" },
    { value: "05", text: "Africa" },
    { value: "06", text: "Asia" },
    { value: "07", text: "Oceania" },
  ];

  var select = document.getElementById("lugarDestino");
  select.innerHTML = "";
  opciones.forEach(function (opcion) {
    var option = document.createElement("option");
    option.value = opcion.value;
    option.textContent = opcion.text;
    select.appendChild(option);
  });
}
// ========================================================================================================================

// Cargar el select de destino
function CargarSelectMotivoViaje() {
  var opciones = [
    { value: "", text: "Selecciona..." },
    { value: "Vacacional", text: "Vacacional" },
    { value: "Empresarial", text: "Empresarial" },
    { value: "Estudiantil", text: "Estudiantil" },
    // { value: 'Estudiantil', text: 'Estudiantil' }
  ];

  var select = document.getElementById("motivoViaje");
  select.innerHTML = "";
  opciones.forEach(function (opcion) {
    var option = document.createElement("option");
    option.value = opcion.value;
    option.textContent = opcion.text;
    select.appendChild(option);
  });
}
// ========================================================================================================================

// Carga la fecha de Nacimiento
$("#dianacimiento, #mesnacimiento, #anionacimiento").each(function () {
  $(this).select2({
    theme: "bootstrap fecnacimiento",
    language: "es",
    width: "100%",
    // Otras configuraciones específicas si las necesitas
  });
  $(this).on("select2:open", function (e) {
    var $select2 = $(this).data("select2");
    $select2.dropdown.$dropdownContainer.addClass("select2-container--above");
  });
});
// ========================================================================================================================

// Abrir y cerrar el data container
function toggleContainerData() {
  $("#menosCotizacion").toggle();
  $("#masCotizacion").toggle();
  $("#containerDatos").toggle();
}
// ========================================================================================================================

// Abrir y cerrar el cards container
function toggleContainerCards() {
  $("#menosParrilla").toggle();
  $("#masParrilla").toggle();
  $("#Cards").toggle();
}
// ========================================================================================================================

// Funcion que muestra el container de cards
function showContainerCards() {
  $("#containerCards").show();
}
// ========================================================================================================================

// Funcion que oculta el container del cuadro de cotizaciones
function hideContainerQuotations() {
  $("#containerDataTableTittle").hide();
  $("#containerDataTable").hide();
  $("#containerTable").hide();
}
// ========================================================================================================================

// Funcion que oculta el container de las cars informativas
function hideMainContainers() {
  $("#mainCardContainer").hide();
}
// ========================================================================================================================

// Validar si se puede mostrar ese producto
function validarCodigoVacacional(codigo) {
  return vacacionalPermitidos.includes(codigo);
}
// ========================================================================================================================

// Validar si se puede mostrar ese producto
function validarCodigoEmpresarial(codigo) {
  return empresarialPermitidos.includes(codigo);
}
// ========================================================================================================================

// Validar si se puede mostrar ese producto
function validarCodigoEstudiantil(codigo) {
  return estudiantilesPermitidos.includes(codigo);
}
// ========================================================================================================================

// Permitir cambiar los nombres de los produtos, por ahora se hace para vacacional
function changeNameProduct(codeProduct, nameOriginal) {
  var equivalente = equivalencias[codeProduct];
  return equivalente !== undefined ? equivalente : nameOriginal;
}
// ========================================================================================================================

// Permitir cambiar la cobertura de cada plan vacacional
function changeRateVacationalProduct(codeProduct) {
  var equivalente = coberturasVacacional[codeProduct];
  return equivalente !== undefined ? equivalente : "No disponible";
}
// ========================================================================================================================

// Permitir cambiar la cobertura de cada plan Empresarial
function changeRateBusinessProduct(codeProduct) {
  var equivalente = coberturasEmpresarial[codeProduct];
  return equivalente !== undefined ? equivalente : "No disponible";
}
// ========================================================================================================================

function changeRateStudyingProduct(codeProduct) {
  var equivalente = coberturasEstudiantil[codeProduct];
  return equivalente !== undefined ? equivalente : "No disponible";
}
// ========================================================================================================================

// Funcion para validar el tipo de modalidad para la oferta
function validarModalidad(modalidad) {
  var result = "";
  // console.log(modalidad);
  switch (modalidad) {
    case "0":
      result = "No Aplica";
      break;
    case "1":
      result = "Diaria";
      break;
    case "2":
      result =
        "Múltiples viajes en 1 año, tope hasta 30 días consecutivos por cada viaje.";
      break;
    case "3":
      result =
        "Múltiples viajes en 1 año, tope hasta 60 días consecutivos por cada viaje.";
      break;
    case "4":
      result = "Larga Estadía Diaria";
      break;
    case "5":
      result = "Larga Estadia Anual";
      break;
    case "6":
      result =
        "Múltiples viajes en 1 año, tope hasta 90 días consecutivos por cada viaje.`";
      break;
    case "7":
      result =
        "Múltiples viajes en 1 año, tope hasta 120 días consecutivos por cada viaje.";
      break;
    case "8":
      result = "Capitas";
      break;
    case "9":
      result =
        "Múltiples viajes en 1 año, tope hasta 15 días consecutivos por cada viaje.";
      break;
    case "10":
      result =
        "Múltiples viajes en 1 año, tope hasta 45 días consecutivos por cada viaje.";
      break;
  }
  return result;
}
// ========================================================================================================================

// Funcion para obtener el destino y convertirlo

// "01": "10477", // Norte America // Norte America y Canada
// "02": "10475", // Europa // Internacional
// "03": "10473", // LATAM // Latinoamerica
// "04": "10473", // LATAM // Latinoamerica
// "05": "10475", // Africa // Internacional
// "06": "10475", // Asia // Internacional
// "07": "10475", // Oceania // Internacional

function regionConvert(codigoDestino) {
  let result = "";
  switch (codigoDestino) {
    case "10477":
      result = "USA Y CANADA";
      break;
    case "10475":
      result = "INTERNACIONAL";
      break;
    case "10473":
      result = "LATINOAMERICA";
      break;
    case "10474":
      result = "LATINOAMERICA";
      break;
    case "10476":
      result = "INTERNACIONAL";
      break;
    case "10478":
      result = "USA Y CANADA";
      break;
    default:
      alert("Codigo de Region No Disponible");
      break;
  }

  return result;
}

// ========================================================================================================================

// Funcion para formatear los numeros
function format(n, sep, decimals) {
  sep = sep || ","; //Default to period as decimal separator
  decimals = decimals || 0; //Default to 2 decimals

  return n.toLocaleString().split(sep)[0];
}

//funcion para invalidar las fechas anteriores a la actual
function invaldateBeforeDate() {
  var today = new Date();
  var day = ("0" + today.getDate()).slice(-2);
  var month = ("0" + (today.getMonth() + 1)).slice(-2);
  var year = today.getFullYear();
  var todayFormatted = year + "-" + month + "-" + day;
  $("#fechaSalida, #fechaRegreso").attr("min", todayFormatted);
}
// ========================================================================================================================

//funcion para invalidar las fechas anteriores a fecha de salida
function InvalidReturnDates() {
  var $fechaSalidaInput = $("#fechaSalida");
  var $fechaRegresoInput = $("#fechaRegreso");
  $fechaRegresoInput.attr("min", $fechaSalidaInput.val());
  if ($fechaRegresoInput.val() < $fechaSalidaInput.val()) {
    $fechaRegresoInput.val($fechaSalidaInput.val());
  }
}

//funcion para validar que la edad sea mayor a 18 cuando el motivo es empresarial
function validateDateToBusinessProduct() {
  var motivoViaje = document.getElementById("motivoViaje").value;
  var diaNacimiento = document.getElementById("dianacimiento").value;
  var mesNacimiento = document.getElementById("mesnacimiento").value;
  var anioNacimiento = document.getElementById("anionacimiento").value;
  var fechaSalida = document.getElementById("fechaSalida").value;

  if (motivoViaje !== "Empresarial") {
    return true;
  }

  if (!diaNacimiento || !mesNacimiento || !anioNacimiento || !fechaSalida) {
    return false;
  }

  var fechaNacimiento = new Date(
    anioNacimiento,
    mesNacimiento - 1,
    diaNacimiento
  );
  var fechaSalidaDate = new Date(fechaSalida);

  var edad = fechaSalidaDate.getFullYear() - fechaNacimiento.getFullYear();
  var mes = fechaSalidaDate.getMonth() - fechaNacimiento.getMonth();
  if (
    mes < 0 ||
    (mes === 0 && fechaSalidaDate.getDate() < fechaNacimiento.getDate())
  ) {
    edad--;
  }

  if (edad < 18 || edad > 79) {
    Swal.fire({
      icon: "error",
      title: "Edad fuera de politicas (Corporativo entre 18 a 79 años)",
    });
  }

  return edad >= 18 && edad <= 79;
}

function validateDateToStudyingProduct() {
  var motivoViaje = document.getElementById("motivoViaje").value;
  var diaNacimiento = document.getElementById("dianacimiento").value;
  var mesNacimiento = document.getElementById("mesnacimiento").value;
  var anioNacimiento = document.getElementById("anionacimiento").value;
  var fechaSalida = document.getElementById("fechaSalida").value;
  var fechaRegreso = document.getElementById("fechaRegreso").value;

  if (motivoViaje !== "Estudiantil") {
    return true;
  }

  if (!diaNacimiento || !mesNacimiento || !anioNacimiento || !fechaSalida) {
    return false;
  }

  var fechaNacimiento = new Date(
    anioNacimiento,
    mesNacimiento - 1,
    diaNacimiento
  );
  var fechaSalidaDate = new Date(fechaSalida);

  var edad = fechaSalidaDate.getFullYear() - fechaNacimiento.getFullYear();
  var mes = fechaSalidaDate.getMonth() - fechaNacimiento.getMonth();
  if (
    mes < 0 ||
    (mes === 0 && fechaSalidaDate.getDate() < fechaNacimiento.getDate())
  ) {
    edad--;
  }

  if (edad < 12 || edad > 45) {
    Swal.fire({
      icon: "error",
      title: " Edad fuera de políticas (Estudiantil entre 12 a 45 años)",
    });
  }

  return edad >= 18 && edad <= 45;
}
// ========================================================================================================================

// Funcion para cargar estilos una vez se generan las cards
function cargarEstilos(url) {
  $("<link>").appendTo("head").attr({
    type: "text/css",
    rel: "stylesheet",
    href: url,
  });
}
// ========================================================================================================================

//Cambiar titulo data container una vez se cotiza
function toogleDataContainer() {
  var newTittle = "Datos del Viaje";
  $("#lblDataTrip").text(newTittle);
  $("#colradioPeople, #colBtnCotizar").hide();
}

//Cambiar titulo data container una vez se cotiza
function validarCampos() {
  var campos = [
    "#fechaSalida",
    "#fechaRegreso",
    "#lugarDestino",
    "#motivoViaje",
    "#dianacimiento",
    "#mesnacimiento",
    "#anionacimiento",
    "#nombreProspecto",
  ];
  var camposValidos = true;
  campos.forEach(function (campo) {
    var $elemento = $(campo);
    var valor = $elemento.val();
    // Restaurar borde  si el campo tiene valor
    if (valor) {
      // Verificar si es un select y está usando Select2
      if (
        $elemento.is("select") &&
        $elemento.hasClass("select2-hidden-accessible")
      ) {
        var $select2Container = $elemento.next(".select2-container");
        $select2Container.find(".select2-selection").css("border", "");
      } else if ($elemento.is("input") && valor.length > 2) {
        $elemento.css("border", "");
      } else {
        $elemento.css("border", "");
      }
      return;
    } else {
      // Aplicar borde rojo si el campo está vacío

      if (
        $elemento.is("select") &&
        $elemento.hasClass("select2-hidden-accessible")
      ) {
        var $select2Container = $elemento.next(".select2-container");
        $select2Container
          .find(".select2-selection")
          .css("border", "1px solid red");
      } else if ($elemento.is("input") && valor.length < 1) {
        $elemento.css("border", "1px solid red");
      } else {
        $elemento.css("border", "1px solid red");
      }
      camposValidos = false;
    }
  });

  return camposValidos;
}
// ========================================================================================================================

let id_usuario = permisos.id_usuario;

const guardarOfertas = (oferta) => {
  try {
    fetch(
      "https://grupoasistencia.com/assist_engine/WSAssistCard/pushOfferts.php",
      { method: "POST", body: JSON.stringify({ oferta: oferta }) }
    ).then((response) => {
      if (response.status == 200) {
        // console.log("se guardo correctamente las ofertasd de la cotizacion");
      }
    });
  } catch (error) {}
};

//Funcion que permite cotizar la asistencia en viajes con AssitCard

function cotizar() {
  // Capturamos los valores de los campos del formulario
  var PlanFamilair = "false";
  var txtOrigen = $("#lugarOrigen").val();
  var txtDestino = $("#lugarDestino").val();
  var txtFecSalidaOr = $("#fechaSalida").val();
  var txtFecRegresoOr = $("#fechaRegreso").val();
  var SelmotivoViaje2 = $("#motivoViaje").val();
  var diaNac = $("#dianacimiento").val();
  var mesNac = $("#mesnacimiento").val();
  var anioNac = $("#anionacimiento").val();
  let nombreProspecto = $("#nombreProspecto").val();
  var contPasajeros = 1;
  var arrayPajaseros = {};
  var fechaNacimientoStr = diaNac + "/" + mesNac + "/" + anioNac;

  // Parsear la fecha de nacimiento a un objeto Date
  var partesFecha = fechaNacimientoStr.split("/");
  var fechaNacimiento = new Date(
    partesFecha[2],
    partesFecha[1] - 1,
    partesFecha[0]
  );

  // Obtener la fecha actual
  var fechaActual = new Date();

  // Calcular la diferencia en milisegundos entre las dos fechas
  var diferencia = fechaActual.getTime() - fechaNacimiento.getTime();

  // Calcular la edad en años
  var edadPrincipalParaVerDetalles = Math.floor(
    diferencia / (1000 * 60 * 60 * 24 * 365.25)
  );

  txtFecSalida = txtFecSalidaOr.split("-");
  txtFecRegreso = txtFecRegresoOr.split("-");
  txtFecSalida =
    txtFecSalida[0] + "/" + txtFecSalida[1] + "/" + txtFecSalida[2];
  txtFecRegreso =
    txtFecRegreso[0] + "/" + txtFecRegreso[1] + "/" + txtFecRegreso[2];

  var diasViaje =
    new Date(txtFecRegresoOr).getTime() - new Date(txtFecSalidaOr).getTime();
  var txtDiasViaje = Math.round(diasViaje / (1000 * 60 * 60 * 24)) + 1;

  var info_inputs = {
    txtOrigen,
    txtFecSalida,
    PlanFamilair,
    txtDestino,
    txtFecRegreso,
    contPasajeros,
    txtDiasViaje,
    arrayPajaseros,
    SelmotivoViaje2,
    fechaNacimientoStr,
    edadPrincipalParaVerDetalles,
    nombreProspecto,
    id_usuario,
  }; //Asignacion de valores a un array para mandarlo por POST

  // Ajax para mandar la informacion a cotizar con AssitCard
  let validar = validarCampos();
  if (validar) {
    var SelmotivoViaje2 = $("#motivoViaje").val();
    document.getElementById("spinener-cot").style.display = "flex";
    $.ajax({
      url: "https://grupoasistencia.com/assist_engine/WSAssistCard/assistCard.php",
      type: "POST",
      data: { info_inputs_string: info_inputs },
      success: function (data) {
        // Volvemos la respueta en formato JSON
        const objResponse = JSON.parse(data);
        // console.log(objResponse);
        if (objResponse == false) {
          var html_error = `
          <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 contenedor_error">
                    <span>
                      ❌<br>
                      !Ocurrió un error¡
                    </span> <br><br>          
                  </div>
              </div>
            </div>
          </div>
        `;
          document.getElementById("spinener-cot").style.display = "none";
          document.getElementById("row_contenedor_general").innerHTML =
            html_error;
        } else {
          if (objResponse.codigo) {
            document.getElementById("spinener-cot").style.display = "none";
            //console.log(SelmotivoViaje2, " ", txtDiasViaje)
            if (
              (SelmotivoViaje2 === "Estudiantil" && txtDiasViaje < 60) ||
              (SelmotivoViaje2 === "Estudiantil" && txtDiasViaje > 365)
            ) {
              Swal.fire({
                icon: "error",
                title:
                  "Plan Estudiantil partir de 60 días y hasta 365 días corridos.",
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Oops... Por favor revisa toda la información ingresada",
              });
            }
          } else {
            var dolarHoy = objResponse.cotizacionDolar;
            var cotizaciones = objResponse.cotizaciones;
            var cotizacion = cotizaciones.cotizacion;

            var html_data = "";
            hideMainContainers();
            showContainerCards();
            hideContainerQuotations();
            if (
              typeof cotizacion == "object" &&
              cotizacion.length == undefined
            ) {
              if (SelmotivoViaje2 == "Empresarial") {
                if (validarCodigoEmpresarial(cotizacion.codigo)) {
                  cotizacion.last_id = objResponse.last_id;
                  cotizacion.modalidad = SelmotivoViaje2;
                  console.log(cotizacion)
                  guardarOfertas(cotizacion);
                  toogleDataContainer();
                  html_data += ` 
                              <div class='card-ofertas'>
                                <div class='row card-body'>
                                    <div class="col-xs-12 col-sm-6 col-md-2 align-horizontal ">
                                        <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                        <span class="tittleCard">
                                            Assist Card - ${changeNameProduct(
                                              cotizacion.codigo,
                                              cotizacion.nombreTarifa
                                            )}
                                        </span><br> 
                                        <span class="tittleCard">
                                            CORPORATIVO
                                        </span><br> 
                                        <span class="tittlePrice">
                                            Desde ${
                                              contPasajeros > 1
                                                ? `${
                                                    cotizacion.moneda == "1"
                                                      ? "USD"
                                                      : "COP"
                                                  } $` +
                                                  parseFloat(
                                                    cotizacion.clientesCotizados
                                                      .clienteCotizacion[0]
                                                      .valorAsistencia
                                                  ).toFixed(2)
                                                : `${
                                                    cotizacion.moneda == "1"
                                                      ? "USD"
                                                      : "COP"
                                                  } $` +
                                                  parseFloat(
                                                    cotizacion.clientesCotizados
                                                      .clienteCotizacion
                                                      .valorAsistencia
                                                  ).toFixed(2)
                                            }
                                        </span><br> 
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-3 textCards">                
                                        <ul>
                                            <li>Cobertura USD ${changeRateBusinessProduct(
                                              cotizacion.codigo
                                            )}</li>
                                            <li>Cobertura de accidentes</li>
                                            <li>Cobertura por enfermedades no preexistente</li>
                                            <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                            <li>Traslado ejecutivo por reemplazo de funcionario asistido</li>
                                        </ul>
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-3 textCards">
                                        <ul>
                                            <li>Odontología de urgencia</li>
                                            <li>Repatriación Sanitaria derivada de una atención médica</li>
                                            <li>Repatriación funeraria</li>
                                            <li>Seguro de equipaje ante demora y pérdida</li>
                                            <li>Cobertura salvoconducto ante perdida de pasaporte</li>
                                        </ul>
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-2 colPdf">
                                          <span> Muchas más <br>
                                          </span>
                                          <span> coberturas  
                                          <span class="bigEmoji">👇🏼</span>  
                                          </span>
                                            <button class="btn btn-info btn-block btn-pdf" id="">
                                                <span class="span_titulo_item">
                                                    <a target="_blank" class="btnText" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${
                                                      cotizacion.pais
                                                    }&producto=${
                    cotizacion.codigo
                  }&tarifa=${
                    cotizacion.codigoTarifa
                  }&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${
                    cotizacion.cantidadDias == 365 ? `True` : `False`
                  }'>Ver detalles</a>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                }
              }
              if (SelmotivoViaje2 == "Vacacional") {
                if (validarCodigoVacacional(cotizacion.codigo)) {
                  cotizacion.modalidad = SelmotivoViaje2;
                  cotizacion.last_id = objResponse.last_id;
                  guardarOfertas(cotizacion);
                  toogleDataContainer();
                  html_data += ` 
                              <div class='card-ofertas'>
                                <div class='row card-body'>
                                    <div class="col-xs-12 col-sm-6 col-md-2 align-horizontal ">
                                        <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                        <span class="tittleCard">
                                            Assist Card - ${changeNameProduct(
                                              cotizacion.codigo,
                                              cotizacion.nombreTarifa
                                            )}
                                        </span><br> 
                                        <span class="tittleCard">
                                            CORPORATIVO
                                        </span><br> 
                                        <span class="tittlePrice">
                                            Desde ${
                                              contPasajeros > 1
                                                ? `${
                                                    cotizacion.moneda == "1"
                                                      ? "USD"
                                                      : "COP"
                                                  } $` +
                                                  parseFloat(
                                                    cotizacion.clientesCotizados
                                                      .clienteCotizacion[0]
                                                      .valorAsistencia
                                                  ).toFixed(2)
                                                : `${
                                                    cotizacion.moneda == "1"
                                                      ? "USD"
                                                      : "COP"
                                                  } $` +
                                                  parseFloat(
                                                    cotizacion.clientesCotizados
                                                      .clienteCotizacion
                                                      .valorAsistencia
                                                  ).toFixed(2)
                                            }
                                        </span><br> 
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-3 textCards">                
                                        <ul>
                                            <li>Cobertura USD ${changeRateBusinessProduct(
                                              cotizacion.codigo
                                            )}</li>
                                            <li>Cobertura de accidentes</li>
                                            <li>Cobertura por enfermedades no preexistente</li>
                                            <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                            <li>Traslado ejecutivo por reemplazo de funcionario asistido</li>
                                        </ul>
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-3 textCards">
                                        <ul>
                                            <li>Odontología de urgencia</li>
                                            <li>Repatriación Sanitaria derivada de una atención médica</li>
                                            <li>Repatriación funeraria</li>
                                            <li>Seguro de equipaje ante demora y pérdida</li>
                                            <li>Cobertura salvoconducto ante perdida de pasaporte</li>
                                        </ul>
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-2 colPdf">
                                          <span> Muchas más <br>
                                          </span>
                                          <span> coberturas  
                                          <span class="bigEmoji">👇🏼</span>  
                                          </span>
                                            <button class="btn btn-info btn-block btn-pdf" id="">
                                                <span class="span_titulo_item">
                                                    <a target="_blank" class="btnText" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${
                                                      cotizacion.pais
                                                    }&producto=${
                    cotizacion.codigo
                  }&tarifa=${
                    cotizacion.codigoTarifa
                  }&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${
                    cotizacion.cantidadDias == 365 ? `True` : `False`
                  }'>Ver detalles</a>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                }
              }
            } else {
              $.each(cotizacion, function (key, cotizacionArray) {
                if (SelmotivoViaje2 == "Empresarial") {
                  if (validarCodigoEmpresarial(cotizacionArray.codigo)) {
                    cotizacionArray.modalidad = SelmotivoViaje2;
                    cotizacionArray.last_id = objResponse.last_id;
                    guardarOfertas(cotizacionArray);
                    toogleDataContainer();
                    html_data += ` 
                              <div class='card-ofertas'>
                                <div class='row card-body'>
                                    <div class="col-xs-12 col-sm-6 col-md-2 align-horizontal ">
                                        <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                        <span class="tittleCard">
                                            Assist Card - ${changeNameProduct(
                                              cotizacionArray.codigo,
                                              cotizacionArray.nombreTarifa
                                            )}
                                        </span><br> 
                                        <span class="tittleCard">
                                            CORPORATIVO
                                        </span><br> 
                                        <span class="tittlePrice">
                                            Desde ${
                                              contPasajeros > 1
                                                ? `${
                                                    cotizacionArray.moneda ==
                                                    "1"
                                                      ? "USD"
                                                      : "COP"
                                                  } $` +
                                                  parseFloat(
                                                    cotizacionArray
                                                      .clientesCotizados
                                                      .clienteCotizacion[0]
                                                      .valorAsistencia
                                                  ).toFixed(2)
                                                : `${
                                                    cotizacionArray.moneda ==
                                                    "1"
                                                      ? "USD"
                                                      : "COP"
                                                  } $` +
                                                  parseFloat(
                                                    cotizacionArray
                                                      .clientesCotizados
                                                      .clienteCotizacion
                                                      .valorAsistencia
                                                  ).toFixed(2)
                                            }
                                        </span><br> 
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-3 textCards">                
                                        <ul>
                                            <li>Cobertura USD ${changeRateBusinessProduct(
                                              cotizacionArray.codigo
                                            )}</li>
                                            <li>Cobertura de accidentes</li>
                                            <li>Cobertura por enfermedades no preexistente</li>
                                            <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                            <li>Traslado ejecutivo por reemplazo de funcionario asistido</li>
                                        </ul>
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-3 textCards">
                                        <ul>
                                            <li>Odontología de urgencia</li>
                                            <li>Repatriación Sanitaria derivada de una atención médica</li>
                                            <li>Repatriación funeraria</li>
                                            <li>Seguro de equipaje ante demora y pérdida</li>
                                            <li>Cobertura salvoconducto ante perdida de pasaporte</li>
                                        </ul>
                                    </div>
  
                                    <div class="col-xs-12 col-sm-6 col-md-2 colPdf">
                                          <span> Muchas más <br>
                                          </span>
                                          <span> coberturas  
                                          <span class="bigEmoji">👇🏼</span>  
                                          </span>
                                            <button class="btn btn-info btn-block btn-pdf" id="">
                                                <span class="span_titulo_item">
                                                    <a target="_blank" class="btnText" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${
                                                      cotizacionArray.pais
                                                    }&producto=${
                      cotizacionArray.codigo
                    }&tarifa=${
                      cotizacionArray.codigoTarifa
                    }&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${
                      cotizacionArray.cantidadDias == 365 ? `True` : `False`
                    }'>Ver detalles</a>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                  }
                } else if (SelmotivoViaje2 == "Vacacional") {
                  if (validarCodigoVacacional(cotizacionArray.codigo)) {
                    cotizacionArray.modalidad = SelmotivoViaje2;
                    cotizacionArray.last_id = objResponse.last_id;
                    guardarOfertas(cotizacionArray);
                    toogleDataContainer();
                    html_data += ` 
                                  <div class='card-ofertas'>
                                    <div class='row card-body'>
                                        <div class="col-xs-12 col-sm-6 col-md-2 ">
                                            <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                        </div>
  
                                        <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                            <span class="tittleCard">
                                                Assist Card - ${changeNameProduct(
                                                  cotizacionArray.codigo,
                                                  cotizacionArray.nombreTarifa
                                                )}
                                            </span><br> 
                                            <span class="tittleCard">
                                                VACACIONAL
                                            </span><br> 
                                            <span class="tittlePrice">
                                                Desde  ${
                                                  contPasajeros > 1
                                                    ? `${
                                                        cotizacionArray.moneda ==
                                                        "1"
                                                          ? "USD"
                                                          : "COP"
                                                      } $` +
                                                      parseFloat(
                                                        cotizacionArray
                                                          .clientesCotizados
                                                          .clienteCotizacion[0]
                                                          .valorAsistencia
                                                      ).toFixed(2)
                                                    : `${
                                                        cotizacionArray.moneda ==
                                                        "1"
                                                          ? "USD"
                                                          : "COP"
                                                      } $` +
                                                      parseFloat(
                                                        cotizacionArray
                                                          .clientesCotizados
                                                          .clienteCotizacion
                                                          .valorAsistencia
                                                      ).toFixed(2)
                                                }
                                            </span><br> 
                                        </div>
  
                                        <div class="col-xs-12 col-sm-6 col-md-3 textCards">                
                                            <ul>
                                                <li>Cobertura USD ${changeRateVacationalProduct(
                                                  cotizacionArray.codigo
                                                )}</li>
                                                <li>Cobertura de accidentes</li>
                                                <li>Cobertura por enfermedades no preexistente</li>
                                                <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                            </ul>
                                        </div>
  
                                        <div class="col-xs-12 col-sm-6 col-md-3 textCards">
                                            <ul>
                                                <li>Odontología de urgencia</li>
                                                <li>Repatriación Sanitaria derivada de una atención médica</li>
                                                <li>Repatriación funeraria</li>
                                                <li>Seguro de equipaje ante demora y pérdida</li>
                                            </ul>
                                        </div>
  
                                        <div class="col-xs-12 col-sm-6 col-md-2 colPdf">
                                          <span> Muchas más <br>
                                          </span>
                                          <span> coberturas  
                                          <span class="bigEmoji">👇🏼</span>  
                                          </span>
                                            <button class="btn btn-info btn-block btn-pdf" id="">
                                                <span class="span_titulo_item">
                                                    <a target="_blank" class="btnText" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${
                                                      cotizacionArray.pais
                                                    }&producto=${
                      cotizacionArray.codigo
                    }&tarifa=${
                      cotizacionArray.codigoTarifa
                    }&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${
                      cotizacionArray.cantidadDias == 365 ? `True` : `False`
                    }'>Ver detalles  </a>
                                                </span>
                                                <span class="fa fa-file-text" aria-hidden="true"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                  }
                } else if (SelmotivoViaje2 == "Estudiantil") {
                  var txtDestino = $("#lugarDestino").val();
                  let codigoOferta = "";
                  var codOfertaEstatico =
                    planesPorDestinoEstudiantiles[txtDestino];
                  if (
                    cotizacionArray.codigoTarifa == "10474" &&
                    codOfertaEstatico == "10473"
                  ) {
                    codigoOferta = cotizacionArray.codigoTarifa;
                  } else if (
                    cotizacionArray.codigoTarifa == "10476" &&
                    codOfertaEstatico == "10475"
                  ) {
                    codigoOferta = cotizacionArray.codigoTarifa;
                  } else if (
                    cotizacionArray.codigoTarifa == "10478" &&
                    codOfertaEstatico == "10477"
                  ) {
                    codigoOferta = cotizacionArray.codigoTarifa;
                  } else {
                    codigoOferta = planesPorDestinoEstudiantiles[txtDestino];
                  }
                  if (codigoOferta == cotizacionArray.codigoTarifa) {
                    if (validarCodigoEstudiantil(cotizacionArray.codigo)) {
                      cotizacionArray.modalidad = SelmotivoViaje2;
                      cotizacionArray.last_id = objResponse.last_id;
                      guardarOfertas(cotizacionArray);
                      toogleDataContainer();
                      html_data += ` 
                                    <div class='card-ofertas'>
                                      <div class='row card-body'>
                                          <div class="col-xs-12 col-sm-6 col-md-2 ">
                                              <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                          </div>
    
                                          <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                              <span class="tittleCard">
                                                  Assist Card - ${changeNameProduct(
                                                    cotizacionArray.codigo,
                                                    cotizacionArray.nombreTarifa
                                                  )} - ${regionConvert(
                        cotizacionArray.codigoTarifa
                      )}
                                              </span><br> 
                                              <span class="tittleCard">
                                                ESTUDIANTIL
                                            </span><br>
                                              <span class="tittlePrice">
                                                  Desde  ${
                                                    contPasajeros > 1
                                                      ? `${
                                                          cotizacionArray.moneda ==
                                                          "1"
                                                            ? "USD"
                                                            : "COP"
                                                        } $` +
                                                        parseFloat(
                                                          cotizacionArray
                                                            .clientesCotizados
                                                            .clienteCotizacion[0]
                                                            .valorAsistencia
                                                        ).toFixed(2)
                                                      : `${
                                                          cotizacionArray.moneda ==
                                                          "1"
                                                            ? "USD"
                                                            : "COP"
                                                        } $` +
                                                        parseFloat(
                                                          cotizacionArray
                                                            .clientesCotizados
                                                            .clienteCotizacion
                                                            .valorAsistencia
                                                        ).toFixed(2)
                                                  }
                                              </span><br> 
                                          </div>
    
                                          <div class="col-xs-12 col-sm-6 col-md-3 textCards">                
                                              <ul>
                                                  <li>Cobertura USD ${changeRateStudyingProduct(
                                                    cotizacionArray.codigo
                                                  )}</li>
                                                  <li>Cobertura de accidentes</li>
                                                  <li>Cobertura por enfermedades no preexistente</li>
                                                  <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                                  <li>Acompañamiento psicológico</li>
                                                  <li>Odontología de urgencia</li>
                                              </ul>
                                          </div>
    
                                          <div class="col-xs-12 col-sm-6 col-md-3 textCards">
                                              <ul>
                                                  <li>Traslado de un familiar es caso de hospitalización prevista de 5 días.</li>
                                                  <li>Repatriación Sanitaria derivada de una atención médica</li>
                                                  <li>Repatriación funeraria</li>
                                                  <li>Cobertura salvoconducto ante Pérdida de Pasaporte</li>
                                                  <li>Seguro de equipaje ante demora y pérdida</li>
                                              </ul>
                                          </div>
    
                                          <div class="col-xs-12 col-sm-6 col-md-2 colPdf">
                                            <span> Muchas más <br>
                                            </span>
                                            <span> coberturas  
                                            <span class="bigEmoji">👇🏼</span>  
                                            </span>
                                              <button class="btn btn-info btn-block btn-pdf" id="">
                                                  <span class="span_titulo_item">
                                                      <a target="_blank" class="btnText" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${
                                                        cotizacionArray.pais
                                                      }&producto=${
                        cotizacionArray.codigo
                      }&tarifa=${
                        cotizacionArray.codigoTarifa
                      }&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${
                        cotizacionArray.cantidadDias == 365 ? `True` : `False`
                      }'>Ver detalles  </a>
                                                  </span>
                                                  <span class="fa fa-file-text" aria-hidden="true"></span>
                                              </button>
                                          </div>
                                      </div>
                                  </div>
                              `;
                    }
                  }
                }
              });
            }
            document.getElementById("spinener-cot").style.display = "none";
            document.getElementById("row_contenedor_general").innerHTML =
              html_data;
            //   });
            cargarEstilos("vistas/modulos/AssistCardCot/css/cards.css");
            Swal.fire({
              title: "¡Cotización Exitosa!",
              icon: "success",
            });
          }
        }
      },
      error: function (data) {
        alert("Error");
      },
    });
  } else {
    Swal.fire({
      icon: "error",
      title: "Por favor revisa toda la información ingresada",
    });
  }
}

// Inicializacion de funciones
$(document).ready(function () {

  // var urlPage = new URL(window.location.href); // Instancia la URL Actual

  // var options = urlPage.searchParams.getAll("idCotizacionAssistCard"); //Buscar todos los parametros

  // if(options.length < 0){
  //   CargarSelectOrigen();
  //   CargarSelectDestino();
  //   CargarSelectMotivoViaje();
  // }

  //Inicializamos el tooltip
  $('[data-toggle="tooltip"]').tooltip();

  $("#btnCotizarAsiss").click(function () {
    var dataOk = validarCampos();
    var SelmotivoViaje2 = $("#motivoViaje").val();
    if (SelmotivoViaje2 == "Estudiantil") {
      var edadOK = validateDateToStudyingProduct();
      if (dataOk & edadOK) {
        cotizar();
      }
    } else if (SelmotivoViaje2 == "Empresarial") {
      var edadOK = validateDateToBusinessProduct();
      if (dataOk & edadOK) {
        cotizar();
      }
    } else {
      cotizar();
    }
  });

  $("#menosCotizacion, #masCotizacion ").click(function () {
    toggleContainerData();
  });

  $("#menosParrilla, #masParrilla").click(function () {
    toggleContainerCards();
  });

  $("#fechaSalida").on("change", function () {
    InvalidReturnDates();
  });

  invaldateBeforeDate();
});
// ========================================================================================================================
