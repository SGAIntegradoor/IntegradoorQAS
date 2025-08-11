// Declaramos las constantes que vamos a utilizar
var errores = 0;
const numMaxAseg = 10;
const iva = 5;
const COBERTURAS_FESALUD_AMPARADO = ["XXXXXX"];

const COBERTURAS_SALUD_IDEAL_EMERMEDICA = ["XXXXXX"];

const COBERTURAS_PLAN_AMBULATORIO = ["XXXXXX"];

const COBERTURAS_ORIGINAL_AMPARADO = ["XXXXXX"];

const COBERTURAS_ALTERNO_AMPARADO = [
  "Coberturas Salud ideal",
  "+ Consulta externa",
  "+ Continuidad pago de prima por desempleo",
];

const COBERTURAS_SALUD_IDEAL = [
  "Urgencias",
  "Hospitalización y cirugía",
  "Urgencias odontológicas",
];

/**
 * Abrir modal con info.
 * @function
 */
function adjustModalWidth() {
  return new Promise((resolve) => {
    const modal = document.querySelector(".swal2-popup");
    if (window.innerWidth < 768) {
      modal.style.width = "90%"; // Ancho para móvil
    } else {
      modal.style.width = "70%"; // Ancho para desktop
    }
    resolve(); // Resolvemos la promesa
  });
}

function openModal() {
  Swal.fire({
    title: `
      <div style="display: flex; align-items: center; border-top: 1px solid #d3d3d3; margin: 0px 20px;">
        <div style="flex: 1; margin-left: 10px;">
          <strong>EXCLUSIONES PÓLIZAS DE SALUD</strong>
        </div>
        <div>
          <img src="vistas/modulos/SaludCot/img/logo-convenio-axa-colpatria.png" alt="Logo" style="max-height: 40px;">
        </div>
      </div>
      <div style="border-bottom: 1px solid #d3d3d3; margin: 0px 20px;"></div>`,
    html: `
      <style>
        .responsive-ul {
          display: flex;
          flex-wrap: wrap;
          justify-content: space-between;
          padding: 20px;
        }
        .responsive-ul > div {
          width: 100%;
          max-width: 45%;
          margin-bottom: 20px;
        }
        @media (max-width: 768px) {
          .responsive-ul > div {
            width: 100%;
            max-width: 100%;
          }
        }
      </style>
      <div class="responsive-ul">
        <div>
          <ul style="text-align: left;">
            <li>Diabetes</li>
            <li>Enfermedad coronaria</li>
            <li>Hipertensión arterial severa</li>
            <li>Cáncer</li>
            <li>Antecedentes de accidente cerebro vascular</li>
            <li>Obesos con IMC (Índice de Masa Corporal) > 36</li>
            <li>Enfermedades del colágeno: Artritis reumatoide, Lupus Eritematoso sistémico, Dermatomiositis, Síndrome antifosfolípidos</li>
            <li>Enfermedades autoinmunes</li>
            <li>Neurofibromatosis</li>
            <li>Valvulopatía cardíaca y otras enfermedades del corazón</li>
            <li>Trastorno psiquiátrico mayor</li>
            <li>Anorexia nerviosa y bulimia</li>
            <li>Autismo</li>
            <li>Enfermedades huérfanas</li>
          </ul>
        </div>
        <div>
          <ul style="text-align: left;">
            <li>Hemofilia o trastornos de coagulación. Pacientes anticoagulados.</li>
            <li>VIH-SIDA</li>
            <li>Paciente oxígeno dependiente</li>
            <li>Síndrome de Down</li>
            <li>Malformaciones congénitas</li>
            <li>Drogadicción, consumo de sustancias psicoactivas</li>
            <li>Epilepsia</li>
            <li>Embarazadas (Opción de compra de anexo de maternidad de acuerdo con el producto a ingresar)</li>
            <li>Bebés en "Plan canguro" (Opción de posponer e ingreso)</li>
            <li>EPOC (Enfermedad Pulmonar Obstructiva Crónica)</li>
            <li>Cirugías pendientes, post operatorio recientes, tratamientos médicos en curso</li>
            <li>Insuficiencia renal</li>
            <li>Antecedente de hospitalización por Covid-19, se valida con copia de historia clínica</li>
          </ul>
        </div>
      </div>`,
    showCloseButton: true,
    confirmButtonText: "Cerrar",
    width: "70%", // Ancho predeterminado
    customClass: {
      closeButton: "swal2-close",
    },
  }).then(() => {
    // Ajustar el ancho al cerrar el modal
    adjustModalWidth();
  });

  // Ajustar el ancho inmediatamente después de abrir el modal
  adjustModalWidth();

  // También ajustar el ancho al cambiar el tamaño de la ventana
  window.addEventListener("resize", adjustModalWidth);
}

/**
 * Cargar fecha.
 * @function
 */
function initializeSelect2(selectors) {
  $(selectors).each(function () {
    if (!$(this).data("select2")) {
      $(this)
        .select2({
          theme: "bootstrap fecnacimiento",
          language: "es",
          width: "100%",
        })
        .on("select2:open", function () {
          var $select2 = $(this).data("select2");
          $select2.dropdown.$dropdownContainer.addClass(
            "select2-container--above"
          );
        });
    }
  });
}

/**
 * Cargar dpto y ciudad.
 * @function
 */
function initializeSelect2Dpto(selectors) {
  $(selectors).each(function () {
    if (!$(this).data("select2")) {
      $(this)
        .select2({
          theme: "bootstrap dpto",
          language: "es",
          width: "100%",
        })
        .on("select2:open", function () {
          var $select2 = $(this).data("select2");
          $select2.dropdown.$dropdownContainer.addClass(
            "select2-container--above"
          );
        });
    }
  });
}

/**
 * Abrir y cerrar dataContainer
 * @function
 */
function toggleContainerData() {
  $("#menosCotizacion").toggle();
  $("#masCotizacion").toggle();
  $("#containerDatosSalud").toggle();
}

/**
 * Cargar selects.
 * @functions
 */
function CargarSelectCantidadAsegurados() {
  const select = document.getElementById("numAsegurados");
  select.innerHTML = "";

  // Crea opciones para los números del 1 al numMaxAseg
  for (let i = 2; i <= numMaxAseg; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.textContent = i;

    // Selecciona por defecto la opción 1
    if (i === 2) {
      option.selected = true;
    }
    select.appendChild(option);
  }
}
function CargarSelectTipoDocumento() {
  var opciones = [
    { value: "", text: "Selecciona..." },
    { value: "01", text: "CC" },
    { value: "02", text: "TI" },
    { value: "03", text: "RC" },
    { value: "04", text: "CE" },
    { value: "05", text: "DNI" },
  ];

  // Cambia 'tipoDocumento' por la clase correspondiente
  var selects = document.querySelectorAll(".tipoDocumento");
  selects.forEach(function (select) {
    // Solo agregar opciones si el select está vacío
    if (select.options.length === 0) {
      // Limpia el contenido actual
      select.innerHTML = "";

      // Agrega las opciones predeterminadas
      opciones.forEach(function (opcion) {
        var option = document.createElement("option");
        option.value = opcion.value;
        option.textContent = opcion.text;
        select.appendChild(option);
      });
    }
  });
}
function CargarSelectGenero() {
  var opciones = [
    { value: "", text: "Selecciona..." },
    { value: "1", text: "Masculino" },
    { value: "2", text: "Femenino" },
  ];

  // Cambia 'tipoDocumento' por la clase correspondiente
  var selects = document.querySelectorAll(".genero");
  selects.forEach(function (select) {
    if (select.options.length === 0) {
      select.innerHTML = "";
      opciones.forEach(function (opcion) {
        var option = document.createElement("option");
        option.value = opcion.value;
        option.textContent = opcion.text;
        select.appendChild(option);
      });
    }
  });
}

/**
 * Intercalar visibilidad de selector de numero de asegurados.
 * @function
 */
function toggleNumAsegSelector() {
  $(".cantAsegurados").hide();
  $("#aseguradosContainer").empty();

  $('input[name="tipoCotizacion"]').change(function () {
    if ($("#grupoFamiliar").is(":checked")) {
      $("#asociadoSi_1").prop("checked", false);
      $("#asociadoNo_1").prop("checked", true);
      $(".cantAsegurados").show();
      generateAseguradosFields();
      $("#lblTomador").text("¿El tomador también será asegurado?");
    } else {
      $("#asociadoSi_1").prop("checked", true);
      $("#asociadoNo_1").prop("checked", false);
      $(".cantAsegurados").hide();
      $("#aseguradosContainer").empty();
      $("#lblTomador").text("¿El tomador es el mismo asegurado?");
    }
  });
}

/**
 * Crear campos para tantos asegurados se necesite. Se hizo asi porque se intento clonar el codigo que ya estaba en el DOM, pero la libreria select2 daba muchos errores al clonar.
 * @functions
 */
function generateAseguradosFields() {
  var numAsegurados = parseInt($("#numAsegurados").val());

  // Limpiar los campos existentes
  $("#aseguradosContainer").empty();
  const grupoFamiliar = $("#grupoFamiliar").is(":checked");
  const siTomador = $("#si").is(":checked");

  for (var i = 2; i <= numAsegurados; i++) {
    // Crear el HTML para los nuevos campos
    var newFields = `
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 rowAseg">
                    <label>Datos Asegurado ${i}.</label>
                </div>
            </div>
            <div class="row asegurado" data-asegurado-id="${i}">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="form-group">
                        <label for="nombreCompleto_${i}">Nombre Completo</label>
                        <div class="nombreCompleto">
                            <input id="nombre_${i}" class="form-control nombre format-text" placeholder="Nombre" />
                            <input id="apellido_${i}" class="form-control apellido format-text" placeholder="Apellido" />
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="form-group">
                        <label for="fechaNaci_${i}">Fecha de nacimiento</label>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4 conten-dia">
                                <select class="form-control fecha-nacimiento" name="dianacimiento_${i}" id="dianacimiento_${i}" required>
                                    <option value="">Dia</option>
                                    ${generateOptions(1, 31)}
                                </select>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 conten-mes">
                                <select class="form-control fecha-nacimiento" name="mesnacimiento_${i}" id="mesnacimiento_${i}" required>
                                    <option value="" selected>Mes</option>
                                    ${generateOptions(1, 12, true)}
                                </select>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 conten-anio">
                                <select class="form-control fecha-nacimiento" name="anionacimiento_${i}" id="anionacimiento_${i}" required>
                                    <option value="">Año</option>
                                    ${generateOptions(1920, 2024, false, true)}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <div class="form-group">
                        <label for="genero_${i}">Genero</label>
                        <select id="genero_${i}" class="form-control genero"></select>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-2 departamento">
                        <label for="departamento_${i}">Departamento</label>
                        <select id="departamento_${i}" class="form-control departamento departamentoSelect" >
                          <option value=""></option>
                          <option value="91">Amazonas</option>
                          <option value="05">Antioquia</option>
                          <option value="81">Arauca</option>
                          <option value="08">Atlántico</option>

                          <option value="13">Bolívar</option>
                          <option value="15">Boyacá</option>
                          <option value="17">Caldas</option>
                          <option value="18">Caquetá</option>

                          <option value="85">Casanare</option>
                          <option value="19">Cauca</option>
                          <option value="20">Cesar</option>
                          <option value="27">Chocó</option>
                          <option value="23">Córdoba</option>

                          <option value="25">Cundinamarca</option>
                          <option value="94">Guainía</option>
                          <option value="44">La Guajira</option>
                          <option value="95">Guaviare</option>
                          <option value="41">Huila</option>

                          <option value="47">Magdalena</option>
                          <option value="50">Meta</option>
                          <option value="52">Nariño</option>
                          <option value="54">Norte de Santander</option>
                          <option value="86">Putumayo</option>

                          <option value="63">Quindío</option>
                          <option value="66">Risaralda</option>
                          <option value="88">San Andrés, Providencia y Santa Catalina</option>
                          <option value="68">Santander</option>
                          <option value="70">Sucre</option>

                          <option value="73">Tolima</option>
                          <option value="76">Valle del Cauca</option>
                          <option value="97">Vaupés</option>
                          <option value="99">Vichada</option>
                        </select>
              </div>
              <div class="form-group col-sm-6 col-md-2 ciudad">
                        <label for="ciudad_${i}">Ciudad</label>
                        <select id="ciudad_${i}" class="form-control ciudad ciudadSelect"></select>
              </div>

              <!-- Campo pregunta algun asegurado es asociado a coomeva -->
              <div class="col-xs-12 col-sm-6 col-md-4 asociadoC">
                  <div class="form-group">
                      <label id="">Asociado Cooperativa Coomeva</label><br>
                      <div class="form-check form-check-inline">
                          <span class=" center-elements">
                              <input type="radio" id="asociadoSi_${i}" name="aseguradoAsociadoCoomeva_${i}" class="form-check-input">
                              <label for="" class="form-check-label colorGray">Si</label>
                          </span>
                          <span class="radio-container center-elements">
                              <input type="radio" id="asociadoNo_${i}" name="aseguradoAsociadoCoomeva_${i}" class="form-check-input" checked>
                              <label for="" class="form-check-label colorGray">No</label>
                          </span>
                      </div>
                  </div>
              </div>
            </div>
        `;

    // Agregar los nuevos campos al contenedor
    $("#aseguradosContainer").append(newFields);
    activateFormate();
  }

  // Inicializa Select2 solo en los nuevos elementos clonados
  initializeSelect2(".fecha-nacimiento");
  initializeSelect2Dpto(".ciudadSelect");
  initializeSelect2Dpto(".departamentoSelect");
  CargarSelectTipoDocumento();
  CargarSelectGenero();
  hideShowCamposCiudad();
  hideShowAsociadoCoomeva();
}
function generateOptions(start, end, isMonth = false, isYear = false) {
  var options = "";
  for (var i = start; i <= end; i++) {
    var value = isMonth
      ? ("0" + i).slice(-2)
      : isYear
      ? i
      : ("0" + i).slice(-2);
    var display = isMonth ? getMonthName(i) : value;
    options += `<option value="${value}">${display}</option>`;
  }
  return options;
}
function getMonthName(month) {
  const months = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
  ];
  return months[month - 1];
}

/**
 * Copiar campos de tomardor a asegurado cuando esta check.
 * @function
 */
function handleMismoAsegurado() {
  var isSameInsured = $("#si").is(":checked"); // Verificar si el radio button 'Sí' está seleccionado

  if (isSameInsured) {
    console.log("entre aca");
    // Copiar información de los campos principales a los campos clonados
    var tipoDocumento = $(".tipoDocumento").val();
    var numeroDocumento = $(".numeroDocumento").val();
    var nombre = $(".nombre").val();
    var apellido = $(".apellido").val();
    $("#aseguradoTemplate")
      .find(".tipoDocumento")
      .val(tipoDocumento)
      .trigger("change");
    $("#aseguradoTemplate").find(".numeroDocumento").val(numeroDocumento);
    $("#aseguradoTemplate").find(".nombre").val(nombre);
    $("#aseguradoTemplate").find(".apellido").val(apellido);
  } else {
    // Vaciar los campos clonados
    $("#aseguradoTemplate").find(".tipoDocumento").val("").trigger("change");
    $("#aseguradoTemplate").find(".numeroDocumento").val("");
    $("#aseguradoTemplate").find(".nombre").val("");
    $("#aseguradoTemplate").find(".apellido").val("");
    $("#aseguradoTemplate").each(function () {});
  }
}

/**
 * Copiar campos de tomardor a asegurado cuando esta check y hay un onchange en los campos.
 * @function
 */
function syncFieldsOnChange() {
  var isSameInsured = $("#si").is(":checked");
  if (isSameInsured) {
    // Agregar eventos onchange a los campos principales
    $("#tomadorContainerData .tipoDocumento").on("change", function () {
      var tipoDocumento = $(this).val();
      console.log(tipoDocumento);
      $("#aseguradoTemplate .tipoDocumento")
        .val(tipoDocumento)
        .trigger("change");
    });

    $("#tomadorContainerData .numeroDocumento").on("input", function () {
      var numeroDocumento = $(this).val();
      $("#aseguradoTemplate").find(".numeroDocumento").val(numeroDocumento);
    });

    $("#tomadorContainerData .nombre").on("input", function () {
      var nombre = $(this).val();
      $("#aseguradoTemplate").find(".nombre").val(nombre);
    });

    $("#tomadorContainerData .apellido").on("input", function () {
      var apellido = $(this).val();
      $("#aseguradoTemplate").find(".apellido").val(apellido);
    });
  } else {
    // Remover los eventos onchange si "No" está seleccionado
    $("#tomadorContainerData .tipoDocumento").off("change");
    $("#tomadorContainerData .numeroDocumento").off("input");
    $("#tomadorContainerData .nombre").off("input");
    $("#tomadorContainerData .apellido").off("input");
  }
}

/**
 * Validamos campos antes de enviar request.
 * @function
 */
function validateFormFields() {
  var allFieldsFilled = true;

  // Recorrer todos los inputs y selects dentro de containerDatosSalud
  $("#containerDatosSalud input, #containerDatosSalud select").each(
    function () {
      var $field = $(this);

      if ($field.is(":visible")) {
        // Si el campo está vacío
        if ($field.val() === "" || $field.val() === null) {
          // Si es un campo Select2, aplicar borde rojo al contenedor de Select2
          if ($field.hasClass("select2-hidden-accessible")) {
            $field
              .next(".select2-container")
              .find(".select2-selection")
              .css("border", "2px solid red");
          } else {
            // Marcar con borde rojo los campos normales
            $field.css("border", "2px solid red");
          }
          allFieldsFilled = false;
        } else {
          // Quitar el borde si el campo está lleno
          if ($field.hasClass("select2-hidden-accessible")) {
            $field
              .next(".select2-container")
              .find(".select2-selection")
              .css("border", "");
          } else {
            $field.css("border", "");
          }
        }
      }
    }
  );

  return allFieldsFilled;
}

/**
 * Cambioamos nombre del primer asegurado.
 * @function
 */
function validateNames() {
  var lblName = $("#lblDatosAse");
  var suffix = $("#grupoFamiliar").is(":checked") ? "1" : "";
  var text = $("#si").is(":checked") ? "Tomador Asegurado" : "Datos Asegurado";

  lblName.text(text + (suffix ? " " + suffix : "") + ".");
}

/**
 * Ocultar 6 cards principales.
 * @function
 */
function hideMainContainerCards() {
  $("#mainCardContainerSalud").hide();
}

/**
 * Mostrar container salud.
 * @function
 */
function showContainerCardsSalud() {
  $("#containerCardsSalud").show();
}

/**
 * Alternar nombre y visibilidad del container donde estan los datos a cotizar.
 * @function
 */
function toogleDataContainer() {
  var newTittle = "DATOS DE LA COTIZACIÓN";
  $("#lblAseData").text(newTittle);
  toggleContainerData();
  $("#btnCotizarAsiss").toggle();
}

/**
 * Calculmaos la edad del asegurado con la fecha actual
 * @function
 */
function calcularEdadAsegurado(dia, mes, anio) {
  var hoy = new Date();
  var fechaNacimiento = new Date(anio, mes - 1, dia);

  // Calcular la edad base
  var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
  var mesNacimiento = hoy.getMonth() - fechaNacimiento.getMonth();

  // Ajustar la edad si el cumpleaños aún no ha pasado en el año actual
  if (
    mesNacimiento < 0 ||
    (mesNacimiento === 0 && hoy.getDate() < fechaNacimiento.getDate())
  ) {
    edad--;
  }

  return edad;
}

/**
 * Cargamos estilos de las cards de result, se cargan despues de que se genera el hmtl, porque si lo hacemos antes al moento de generar el html no lo toma.
 * @function
 */
function cargarEstilos(url) {
  $("<link>").appendTo("head").attr({
    type: "text/css",
    rel: "stylesheet",
    href: url,
  });
}

/**
 * Convertimos una cadena a miscula menos la primera letra
 * @function
 */
function capitalizeFirstLetter(str) {
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

/**
 * Generamos un card individual por cada plan
 * @function
 */
function makeIndividualCard(
  plan_id,
  nombrePlan,
  precioMensual,
  precioTrimestral,
  precioSemestral,
  precioAnual,
  coberturas,
  titulo,
  subtitulo,
  pdf,
  logo,
  tipoCotizacion,
  cantAseg,
  tableHTML
) {
  coberturasHTML = "";
  coberturas.forEach((plan) => {
    coberturasHTML += `<li>${plan}</li>`;
  });

  const uniqueId = `table_${plan_id}`; // Crear un ID único basado en plan_id
  const buttonId = `toggleBtn_${plan_id}`;

  return `
    <div class='card-ofertas'>
      <div class='row card-body'>
          <div class="col-xs-12 col-sm-6 col-md-2 align-horizontal ">
              <img src="${logo}" class="logoCardAsist" alt="Logo">  
              <span class="tittleCard tittleCard_top">
                  ${capitalizeFirstLetter(nombrePlan)}
              </span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-3 oferta-logo infoPlanes">
                <div class='row textCenter tittlePrice2'>
                    <span class="tittlePrice">
                        ${
                          tipoCotizacion === 1
                            ? "Precio total según periodicidad de pago (IVA incluido)"
                            : `Precio total ${cantAseg} personas según periodicidad de pago (IVA incluido)`
                        }
                    </span>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3 aling-start">
                        <span class="tittleCard centar-span-txt">
                            Mensual
                        </span>
                        <span class="tittlePrice centar-span-txt">
                            $${precioMensual}
                        </span>
                    </div>
                    <!-- *** inicio comentado para trimestral y semestral ***
                    <div class="col-xs-12 col-sm-6 col-md-3 aling-start">
                         <span class="tittleCard centar-span-txt">
                            Trimestral
                        </span>
                        <span class="tittlePrice centar-span-txt">
                            $${precioTrimestral}
                        </span>                      
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 aling-start">
                        <span class="tittleCard centar-span-txt">
                            Semestral
                        </span>
                        <span class="tittlePrice centar-span-txt">
                            $${precioSemestral}
                        </span>                     
                    </div>
                    *** fin comentado para trimestral y semestral *** -->
                    <div class="col-xs-12 col-sm-6 col-md-3 aling-start">
                        <span class="tittleCard centar-span-txt">
                            Anual
                        </span>
                        <span class="tittlePrice centar-span-txt">
                            $${precioAnual}
                        </span>                       
                    </div>
                </div>
                <div class="row center-row" style="padding-top: 11px !important; padding-bottom: 10px;">
                    <span class="tittlePrice" style="padding-left: 15px;">
                    </span> 
                    <strong>Nota:</strong> Esta propuesta tiene una vigencia limitada
                </div>
                </div>
                
                <div class="col-xs-12 col-sm-6 col-md-7 textCards">     
                    <div style="width: 100%; text-align: justify; padding-right: 25px">
                      <p>${titulo}</p>
                      <br>
                        <div class="coberturas_botones_padre">
                            <div>
                              <b>${subtitulo}</b>
                              <br>
                              <ul>
                                ${coberturasHTML}
                              </ul>
                            </div>
                            <div class="botones_hijo">
                               <!--<b style="font-size: 14px; margin-bottom: 15px;">Muchas más coberturas 👇🏼</b>-->
                              <a style="width: 100%;" class="" href="${pdf}" target="_blank"><!--<img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/>--> <button style="width: 100%;" class="btn-table float-left">Más coberturas</button></a>    
                            
                              <!--<button id="" class="btn-table float-left" data-target="#${uniqueId}">Más coberturas</button>-->
                              <button style="width: 100%;" id="${buttonId}" class="btn-table float-left" data-target="#${uniqueId}">Detalle de precios</button>
                              
                            </div>
                        </div
                    </div>
                </div>
                </div>
              </div>
              <div class='row card-body'>
              ${tipoCotizacion === 2 ? tableHTML : tableHTML}
              </div>
        </div>`;
}

/**
 * Eliminamos numeros y colocamos la primera en mayuscula
 * @function
 */
function formatInput(value) {
  value = value.replace(/[0-9]/g, "");
  return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
}

/**
 * Cuando la cotizacion es grupal generamos tabla resumen.
 * @function
 */
function makeTable(asegurados, plan_id, pdf) {
  const uniqueId = `table_${plan_id}`; // Crear un ID único basado en plan_id
  const buttonId = `toggleBtn_${plan_id}`;

  let tableHTML = `

  <div class="container flex-colum table-responsive">
      <div class="row custom-table-colum">
            <div class="col-12 botonesSoloMovil">
            <a style="width: 100%;" href="${pdf}" target="_blank"><!--<img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/>--> <button style="width: 100%;" class="btn-table float-left">Más coberturas</button></a>    
            <button style="width: 100%;" id="${buttonId}" class="btn-table float-left" data-target="#${uniqueId}">Detalle de precios</button>
          </div>
      </div>
      <div class="row">
          <div class="col-12">
              <div class="table-responsive"> 
                  <table id="${uniqueId}" class="table table-striped custom-table" style="display: none;">
                      <thead>
                          <tr class="">
                              <th colspan="3" class="periodicity-header-empty"></th> 
                              <th colspan="4" class="periodicity-header">Periodicidad de Pago</th>
                          </tr>
                          <tr class="header-row">
                              <th>Asegurado</th>
                              <th>Género</th>
                              <th>Edad</th>
                              <th>Mensual</th>
                              <!-- *** inicio comentado para trimestral y semestral Javier***
                              <th>Trimestral</th>
                              <th>Semestral</th>
                              *** fin comentario *** -->
                              <th>Anual</th>
                          </tr>
                      </thead>
                      <tbody>`;

  let subtotalMensual = 0,
    subtotalTrimestral = 0,
    subtotalSemestral = 0,
    subtotalAnual = 0;

  asegurados.forEach((asegurado) => {
    descuentoAsegurado = asegurado.asociado;
    let plan = asegurado.planes.find((p) => p.plan_id === plan_id);
    if (plan) {
      let mensual = parseFloat(
        plan.mensual.replace(/\./g, "").replace(",", ".")
      );
      let trimestral = parseFloat(
        plan.trimestral.replace(/\./g, "").replace(",", ".")
      );
      let semestral = parseFloat(
        plan.semestral.replace(/\./g, "").replace(",", ".")
      );
      let anual = parseFloat(plan.anual.replace(/\./g, "").replace(",", "."));

      subtotalMensual += mensual;
      subtotalTrimestral += trimestral;
      subtotalSemestral += semestral;
      if (plan_id >= 9 && plan_id <= 15) {
        if (descuentoAsegurado == 1) {
          subtotalAnual += anual - (anual * 9.5) / 100;
        } else {
          subtotalAnual += anual - (anual * 9) / 100;
        }
      } else {
        subtotalAnual += anual;
      }
      let generoTexto = asegurado.genero === "1" ? "Masculino" : "Femenino";

      tableHTML += `
          <tr>
              <td>${asegurado.nombre} ${asegurado.apellido}</td>
              <td>${generoTexto}</td>
              <td>${asegurado.edad}</td>
              <td>$${processValue(mensual, 0)}</td>
              <!-- *** inicio comentado para trimestral y semestral Javier ***
              <td>$${processValue(trimestral, 0)}</td>
              <td>$${processValue(semestral, 0)}</td>
              *** fin comentario *** -->
              <td>$${processValue(anual, 0)}</td>
          </tr>`;
    }
  });

  let ivaMensual = subtotalMensual * (iva / 100);
  let ivaTrimestral = subtotalTrimestral * (iva / 100);
  let ivaSemestral = subtotalSemestral * (iva / 100);
  let ivaAnual = subtotalAnual * (iva / 100);

  let totalMensual = subtotalMensual + ivaMensual;
  let totalTrimestral = subtotalTrimestral + ivaTrimestral;
  let totalSemestral = subtotalSemestral + ivaSemestral;
  let totalAnual = subtotalAnual + ivaAnual;

  tableHTML += `
                      </tbody>
                      <tfoot>
                          <tr class="bold-row">
                              <th class="th-out-border"></th>
                              <td colspan="2">Subtotal</td>
                              <td>$${processValue(subtotalMensual, 0)}</td>
                              <!-- *** inicio comentado para trimestral y semestral Javier ***
                              <td>$${processValue(subtotalTrimestral, 0)}</td>
                              <td>$${processValue(subtotalSemestral, 0)}</td>
                              *** fin comentario *** -->
                              <td>$${processValue(subtotalAnual, 0)}</td>
                          </tr>
                          <tr class="bold-row">
                              <th class="th-out-border"></th>
                              <td colspan="2">IVA (5%)</td>
                              <td>$${processValue(ivaMensual, 0)}</td>
                              <!-- *** inicio comentado para trimestral y semestral Javier ***
                              <td>$${processValue(ivaTrimestral, 0)}</td>
                              <td>$${processValue(ivaSemestral, 0)}</td>
                              *** fin comentario *** -->
                              <td>$${processValue(ivaAnual, 0)}</td>
                          </tr>
                          <tr class="bold-row">
                              <th class="th-out-border"></th>
                              <td colspan="2">Total</td>
                              <td>$${processValue(totalMensual, 0)}</td>
                              <!-- *** inicio comentado para trimestral y semestral Javier ***
                              <td>$${processValue(totalTrimestral, 0)}</td>
                              <td>$${processValue(totalSemestral, 0)}</td>
                              *** fin comentario *** -->
                              <td>$${processValue(totalAnual, 0)}</td>
                          </tr>
                      </tfoot>
                  </table>
              </div> <!-- Fin del contenedor table-responsive -->
          </div>
      </div>
  </div>`;

  return tableHTML;
}

let showPopup = true;

/**
 * Manager para generar las cards en general.
 * @function
 */

function makeCards(data, tipoCotizacion) {
  console.log(data, tipoCotizacion);

  let html_data = "";
  let aseguradoDes = 0;
  if (tipoCotizacion === 1) {
    // Generar tarjetas individuales para cada plan
    // Acumular los valores por plan_id
    let planesSumados = {};
    let coberturasAgrupadas = {};

    data.asegurados.forEach((asegurado) => {
      aseguradoDes = asegurado.asociado;
      asegurado.planes.forEach((plan) => {
        if (!planesSumados[plan.plan_id]) {
          planesSumados[plan.plan_id] = {
            id_plan: plan.plan_id,
            id_plan_ordenado: plan.id_plan_ordenado,
            nombre: plan.nombre,
            titulo: plan.titulo,
            subtitulo: plan.descripcion,
            logo: plan.logo,
            pdf: plan.pdf,
            mensual: 0,
            trimestral: 0,
            semestral: 0,
            anual: 0,
            coberturas: [],
          };
        }

        planesSumados[plan.plan_id].mensual += parseFloat(
          plan.mensual.replace(/\./g, "").replace(",", ".")
        );
        planesSumados[plan.plan_id].trimestral += parseFloat(
          plan.trimestral.replace(/\./g, "").replace(",", ".")
        );
        planesSumados[plan.plan_id].semestral += parseFloat(
          plan.semestral.replace(/\./g, "").replace(",", ".")
        );
        if (plan.plan_id >= 9 && plan.plan_id <= 15) {
          // Limpiar el valor de plan.anual (ej: "1.234,56" => 1234.56)
          const anualParsed = parseFloat(
            plan.anual.replace(/\./g, "").replace(",", ".")
          );

          if (aseguradoDes == 1) {
            planesSumados[plan.plan_id].anual +=
              anualParsed - (anualParsed * 9.5) / 100;
          } else {
            planesSumados[plan.plan_id].anual +=
              anualParsed - (anualParsed * 9) / 100;
          }
        } else {
          planesSumados[plan.plan_id].anual += parseFloat(
            plan.anual.replace(/\./g, "").replace(",", ".")
          );
        }
        // Si el plan tiene coberturas, agregarlas
        if (planesSumados[plan.plan_id].coberturas.length === 0) {
          planesSumados[plan.plan_id].coberturas = plan.coberturas || [];
        }
      });
    });

    const params = new URLSearchParams(window.location.search);

    const idCoti = params.get("idCotizacionSalud");

    if (!idCoti) {
      // Convertir el objeto a un array de sus valores, Ordenar por el valor mensual desc y Actualizar planesSumados con el objeto ordenado
      let planesArray = Object.values(planesSumados);
      planesArray.sort((a, b) => b.mensual - a.mensual);
      planesSumados = planesArray;
    } else if (idCoti) {
      let planesArray = Object.values(planesSumados);
      planesArray.sort((a, b) => b.id_plan_sumado - a.id_plan_sumado);
      planesSumados = planesArray;
    }

    // Generar tarjetas grupales con los valores sumados
    for (let plan_id in planesSumados) {
      let plan = planesSumados[plan_id];
      planAnual = plan.anual;
      let tableHTML = makeTable(data.asegurados, plan.id_plan, plan.pdf);
      html_data += makeIndividualCard(
        plan.id_plan,
        plan.nombre,
        processValue(plan.mensual, iva),
        processValue(plan.trimestral, iva),
        processValue(plan.semestral, iva),
        processValue(planAnual, iva),
        plan.coberturas,
        plan.titulo,
        plan.subtitulo,
        plan.pdf,
        plan.logo,
        tipoCotizacion,
        data.asegurados.length,
        tableHTML
      );
    }
  } else if (tipoCotizacion === 2) {
    let planesSumados = {};

    data.asegurados.forEach((asegurado) => {
      aseguradoDes = asegurado.asociado;
      asegurado.planes.forEach((plan) => {
        if (!planesSumados[plan.plan_id]) {
          planesSumados[plan.plan_id] = {
            id_plan: plan.plan_id,
            id_plan_ordenado: plan.id_plan_ordenado,
            nombre: plan.nombre,
            titulo: plan.titulo,
            subtitulo: plan.descripcion,
            logo: plan.logo,
            pdf: plan.pdf,
            mensual: 0,
            trimestral: 0,
            semestral: 0,
            anual: 0,
            coberturas: [],
          };
        }

        planesSumados[plan.plan_id].mensual += parseFloat(
          plan.mensual.replace(/\./g, "").replace(",", ".")
        );
        planesSumados[plan.plan_id].trimestral += parseFloat(
          plan.trimestral.replace(/\./g, "").replace(",", ".")
        );
        planesSumados[plan.plan_id].semestral += parseFloat(
          plan.semestral.replace(/\./g, "").replace(",", ".")
        );
        if (plan.plan_id >= 9 && plan.plan_id <= 15) {
          // Limpiar el valor de plan.anual (ej: "1.234,56" => 1234.56)
          const anualParsed = parseFloat(
            plan.anual.replace(/\./g, "").replace(",", ".")
          );

          if (aseguradoDes == 1) {
            planesSumados[plan.plan_id].anual +=
              anualParsed - (anualParsed * 9.5) / 100;
          } else {
            planesSumados[plan.plan_id].anual +=
              anualParsed - (anualParsed * 9) / 100;
          }
        } else {
          planesSumados[plan.plan_id].anual += parseFloat(
            plan.anual.replace(/\./g, "").replace(",", ".")
          );
        }

        if (planesSumados[plan.plan_id].coberturas.length === 0) {
          planesSumados[plan.plan_id].coberturas = plan.coberturas || [];
        }
      });
    });

    const params = new URLSearchParams(window.location.search);

    const idCoti = params.get("idCotizacionSalud");

    if (!idCoti) {
      // Convertir el objeto a un array de sus valores, Ordenar por el valor mensual desc y Actualizar planesSumados con el objeto ordenado
      let planesArray = Object.values(planesSumados);
      planesArray.sort((a, b) => b.mensual - a.mensual);
      planesSumados = planesArray;
    } else if (idCoti) {
      let planesArray = Object.values(planesSumados);
      planesArray.sort((a, b) => a.id_plan_ordenado - b.id_plan_ordenado);
      planesSumados = planesArray;
    }

    // Generar tarjetas grupales con los valores sumados
    for (let plan_id in planesSumados) {
      let plan = planesSumados[plan_id];
      planAnual = plan.anual;
      let tableHTML = makeTable(data.asegurados, plan.id_plan, plan.pdf);
      html_data += makeIndividualCard(
        plan.id_plan,
        plan.nombre,
        processValue(plan.mensual, iva),
        processValue(plan.trimestral, iva),
        processValue(plan.semestral, iva),
        processValue(planAnual, iva),
        plan.coberturas,
        plan.titulo,
        plan.subtitulo,
        plan.pdf,
        plan.logo,
        tipoCotizacion,
        data.asegurados.length,
        tableHTML
      );
    }
  }

  if (getParams("idCotizacionSalud").length > 0) {
    document.getElementById("row_contenedor_general_salud2").innerHTML =
      html_data;
    showPopup = !showPopup;
  } else {
    document.getElementById("row_contenedor_general_salud").innerHTML +=
      html_data;
  }
  $("#resumenCotizaciones").show();
  cargarEstilos("vistas/modulos/SaludCot/css/cardsResult.css");
}

/**
 * Aplicamos iva a los valores y formateamos.
 * @function
 */
function processValue(value, percentage) {
  const updatedValue = value * (1 + percentage / 100);
  const roundedValue = Math.round(updatedValue);
  const formattedValue = (roundedValue / 100).toLocaleString("es-ES", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  });

  return formattedValue;
}

/**
 * formeamos texto
 * @function
 */
function activateFormate() {
  $(".format-text").on("input blur", function () {
    $(this).val(formatInput($(this).val()));
  });
}

/**
 * Cotizamos.
 * @function
 */
function cotizar() {
  let param = true;

  let documentUser = permisos.usu_documento;
  const dataValidation = {
    cedula: documentUser,
  };

  if (param) {
    document.getElementById("spinener-cot-salud").style.display = "flex";
    var tipoCotizacion = $("#individual").is(":checked") ? 1 : 2;
    var esCotizacionIndividual = $("#individual").is(":checked");
    var tomador = {
      tipoDocumento: $("#tomadorContainerData").find(".tipoDocumento").val(),
      numeroDocumento: $("#tomadorContainerData")
        .find(".numeroDocumento")
        .val(),
      nombre: $("#tomadorContainerData").find(".nombre").val(),
      apellido: $("#tomadorContainerData").find(".apellido").val(),
    };

    // Obtener y convertir las variables para la fecha de nacimiento a números enteros
    var diaNacimiento = parseInt($("#dianacimiento").val(), 10);
    var mesNacimiento = parseInt($("#mesnacimiento").val(), 10);
    var anioNacimiento = parseInt($("#anionacimiento").val(), 10);

    var asociado;
    // Si el check general de NO está activo, todos los asociados adicionales son NO
    if ($("#noAsociadoC").is(":checked")) {
      asociado = 0;
    } else {
      // Si no, toma el valor seleccionado por el usuario
      asociado = $("#asociadoSi_1").prop("checked") ? 1 : 0;
    }

    // Añadir el asegurado base
    var aseguradoBase = {
      id: 1, // Aquí debes poner un ID apropiado si es necesario
      tipoDocumento: $("#TipoDocumento").val(),
      numeroDocumento: $("#NroDocumento").val(),
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      genero: $("#genero").val(),
      asociado: asociado,
      ciudad: $("#ciudad_1").val(),
      departamento: $("#departamento_1").val(),
      edad: calcularEdadAsegurado(diaNacimiento, mesNacimiento, anioNacimiento),
      fechaNacimiento: {
        dia: diaNacimiento,
        mes: mesNacimiento,
        anio: anioNacimiento,
      },
    };

    var asegurados = [aseguradoBase];

    // Añadir los asegurados adicionales si es una cotización grupal
    if (!esCotizacionIndividual) {
      $(".row.asegurado").each(function () {
        var aseguradoId = $(this).data("asegurado-id");
        // Comienza desde el ID 2
        if (aseguradoId > 1) {
          var dia = parseInt($(this).find('[id^="dianacimiento_"]').val(), 10);
          var mes = parseInt($(this).find('[id^="mesnacimiento_"]').val(), 10);
          var anio = parseInt(
            $(this).find('[id^="anionacimiento_"]').val(),
            10
          );

          var asociado;
          // Si el check general de NO está activo, todos los asociados adicionales son NO
          if ($("#noAsociadoC").is(":checked")) {
            asociado = 0;
          } else {
            // Si no, toma el valor seleccionado por el usuario
            asociado = $(this).find('[id^="asociadoSi_"]').prop("checked")
              ? 1
              : 0;
          }

          var asegurado = {
            id: aseguradoId,
            // tipoDocumento: $(this).find('[id^="tipoDocumento_"]').val(),
            // numeroDocumento: $(this).find('[id^="numeroDocumento_"]').val(),
            tipoDocumento: null,
            // numeroDocumento: null,
            nombre: $(this).find('[id^="nombre_"]').val(),
            apellido: $(this).find('[id^="apellido_"]').val(),
            genero: $(this).find('[id^="genero_"]').val(),
            // asociado: $(this).find('[id^="asociadoSi_"]').prop("checked")
            //   ? 1
            //   : 0,
            asociado: asociado,
            ciudad: $(this).find('[id^="ciudad_"]').val(),
            departamento: $(this).find('[id^="departamento_"]').val(),
            edad: calcularEdadAsegurado(dia, mes, anio),
            fechaNacimiento: {
              dia: dia,
              mes: mes,
              anio: anio,
            },
          };
          asegurados.push(asegurado);
        }
      });
    }

    const path = window.location.pathname;
    let env = "PROD"; // Valor por defecto

    if (path.includes("/dev/") || path.includes("/DEV/")) {
      env = "DEV";
    } else if (path.includes("/QAS/") || path.includes("/qas/")) {
      env = "QAS";
    }

    // Finalmente, construimos el objeto final que se enviará
    var datosCotizacion = {
      tipoCotizacion: tipoCotizacion,
      tomador: tomador,
      asegurados: asegurados,
      id_usuario: permisos.id_usuario,
      env: env,
    };
    toogleDataContainer();
    console.log("Datos de la cotización:", datosCotizacion);

    //Principal peticion ajax para crear la cotizacion
    $.ajax({
      url: "https://grupoasistencia.com/WS-laravel/api/salud/nueva-cotizacion",
      type: "POST",
      data: JSON.stringify(datosCotizacion),
      contentType: "application/json",
      dataType: "json",
      success: function (newCoti) {
        $.ajax({
          // url: "https://grupoasistencia.com/health_engine/WSAxa/axa.php",
          url:
            "https://grupoasistencia.com/WS-laravel/api/salud/axa/cotizar?idNewCoti=" +
            newCoti,
          type: "POST",
          data: JSON.stringify(datosCotizacion),
          contentType: "application/json",
          dataType: "json",
          success: function (data) {
            hideMainContainerCards();
            showContainerCardsSalud();
            // toogleDataContainer();
            document.getElementById("spinener-cot-salud").style.display =
              "none";
            // console.log(data);debugger;
            makeCards(data, tipoCotizacion);
          },
          error: function (data) {
            errores = errores + 1;
            Swal.fire({
              icon: "error",
              title: "Error al cotizar",
              text: "Por favor, verifica los datos ingresados.",
            });
          },
        });

        $.ajax({
          url:
            "https://grupoasistencia.com/WS-laravel/api/salud/bolivar/cotizar?idNewCoti=" +
            newCoti,
          type: "POST",
          data: JSON.stringify(datosCotizacion),
          contentType: "application/json",
          dataType: "json",
          success: function (data) {
            hideMainContainerCards();
            showContainerCardsSalud();
            // toogleDataContainer();
            document.getElementById("spinener-cot-salud").style.display =
              "none";
            makeCards(data, tipoCotizacion);
          },
          error: function (xhr, status, error) {
            errores = errores + 1;
            console.log("Error status:", status);
            console.log("Error:", error);
            console.log("Response:", xhr.responseText);

            Swal.fire({
              icon: "error",
              title: "Error al cotizar",
              text: "Por favor, verifica los datos ingresados.",
            });
          },
        });
        $.ajax({
          url:
            "https://grupoasistencia.com/WS-laravel/api/salud/coomeva/cotizar?idNewCoti=" +
            newCoti,
          type: "POST",
          data: JSON.stringify(datosCotizacion),
          contentType: "application/json",
          dataType: "json",
          success: function (data) {
            hideMainContainerCards();
            showContainerCardsSalud();
            // toogleDataContainer();
            document.getElementById("spinener-cot-salud").style.display =
              "none";
            makeCards(data, tipoCotizacion);
          },
          error: function (xhr, status, error) {
            errores = errores + 1;
            console.log("Error status:", status);
            console.log("Error:", error);
            console.log("Response:", xhr.responseText);

            Swal.fire({
              icon: "error",
              title: "Error al cotizar",
              text: "Por favor, verifica los datos ingresados.",
            });
          },
        });
      },
      error: function (xhr, status, error) {
        errores = errores + 1;
        console.log("Error status:", status);
        console.log("Error:", error);
        console.log("Response:", xhr.responseText);

        Swal.fire({
          icon: "error",
          title: "Error al cotizar",
          text: "Por favor, verifica los datos ingresados.",
        });
      },
    });

    if (errores == 0) {
      //esperar dos segundos antes de mostrar el mensaje Javier-Dev
      $("body").append(
        '<div id="overlay-cotizacion" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);z-index:9999;"></div>'
      );
      setTimeout(() => {
        $("#overlay-cotizacion").remove();
        Swal.fire({
          title: "¡Cotización Exitosa!",
          icon: "success",
        });
      }, 2000);
    }
    window.scrollTo(0, 0);
    $("#contenParrilla").show();

    $(".nombreCompleto").find("input").prop("disabled", true);
    $("#numAsegurados").prop("disabled", true);
    $("#TipoDocumento").prop("disabled", true);
    $("#NroDocumento").prop("disabled", true);
    $("#nombre").prop("disabled", true);
    $("#apellido").prop("disabled", true);
    $("#genero").prop("disabled", true);
    $("#grupoFamiliar").prop("disabled", true);
    $("#dianacimiento").prop("disabled", true);
    $("#mesnacimiento").prop("disabled", true);
    $("#anionacimiento").prop("disabled", true);
    $("#individual").prop("disabled", true);
    $("#si").prop("disabled", true);
    $("#no").prop("disabled", true);
    $("#asociadoSi_1").prop("disabled", true);
    $("#asociadoNo_1").prop("disabled", true);
    $("#siAsociadoC").prop("disabled", true);
    $("#noAsociadoC").prop("disabled", true);
    $("#siCiudadB").prop("disabled", true);
    $("#noCiudadB").prop("disabled", true);
    $("#departamento_1").prop("disabled", true);
    $("#ciudad_1").prop("disabled", true);

    if ($("#individual").is(":checked")) {
      if ($("#numAsegurados option[value='1']").length === 0) {
        $("#numAsegurados").prepend('<option value="1">1</option>');
      }
      $("#numAsegurados").val("1");
    }

    if ($("#numAsegurados").val() > 1) {
      for (let i = 1; i < $("#numAsegurados").val(); i++) {
        // Deshabilita los inputs de los asegurados
        $("#nombre_" + (i + 1)).prop("disabled", true);
        $("#apellido_" + (i + 1)).prop("disabled", true);
        $("#departamento_" + (i + 1)).prop("disabled", true);
        $("#ciudad_" + (i + 1)).prop("disabled", true);
        $("#dianacimiento_" + (i + 1)).prop("disabled", true);
        $("#mesnacimiento_" + (i + 1)).prop("disabled", true);
        $("#anionacimiento_" + (i + 1)).prop("disabled", true);
        $("#genero_" + (i + 1)).prop("disabled", true);
        $("#asociadoSi_" + (i + 1)).prop("disabled", true);
        $("#asociadoNo_" + (i + 1)).prop("disabled", true);

        if (asegurados[i].asociado == 1) {
          $("#asociadoSi_" + (i + 1)).prop("checked", true);
        } else {
          $("#asociadoNo_" + (i + 1)).prop("checked", true);
        }

        // Asigna los valores de los asegurados a los inputs correspondientes
        $("#nombre_" + (i + 1)).val(asegurados[i].nombre);
        $("#apellido_" + (i + 1)).val(asegurados[i].apellido);
        $("#genero_" + (i + 1)).val(asegurados[i].genero);
        $("#select2-dianacimiento_" + (i + 1) + "-container").text(
          asegurados[i].fechaNacimiento.dia
        );
        $("#select2-mesnacimiento_" + (i + 1) + "-container").text(
          asegurados[i].fechaNacimiento.mes
        );
        $("#select2-anionacimiento_" + (i + 1) + "-container").text(
          asegurados[i].fechaNacimiento.anio
        );

        $("#departamento_" + (i + 1))
          .val(asegurados[i].departamento)
          .trigger("change");

        $("#ciudad_" + (i + 1)).val(asegurados[i].ciudad);
      }
    }
  }
}

function hideShowCamposCiudad() {
  var mostrarCampoCiudad = $("#siCiudadB").prop("checked");
  const selectsDepartamento = document.querySelectorAll(".departamentoSelect");
  const selectsCiudad = document.querySelectorAll(".ciudadSelect");
  initializeSelect2Dpto(".ciudadSelect");
  initializeSelect2Dpto(".departamentoSelect");

  if (mostrarCampoCiudad) {
    $('[class*="departamento"]').show();
    $('[class*="ciudad"]').show();
    selectsCiudad.forEach((select) => {
      select.required = true;
    });
    selectsDepartamento.forEach((select) => {
      select.required = true;
    });
  } else {
    $('[class*="departamento"]').hide();
    $('[class*="ciudad"]').hide();
    selectsDepartamento.forEach((select) => {
      select.required = false;
    });
    selectsCiudad.forEach((select) => {
      select.required = false;
    });
  }
}

function hideShowAsociadoCoomeva() {
  var mostrarCampoasociado = $("#siAsociadoC").prop("checked");

  if (mostrarCampoasociado) {
    $('[class*="asociadoC"]').show();
  } else {
    $('[class*="asociadoC"]').hide();
  }
}

/**
 * Inicializar todo.
 * @function
 */
$(document).ready(function () {
  let controlBtn = false;
  initializeSelect2(".fecha-nacimiento");
  activateFormate();
  CargarSelectTipoDocumento();
  CargarSelectCantidadAsegurados();
  CargarSelectGenero();
  toggleNumAsegSelector();
  // Actualiza los campos de asegurado al cambiar la cantidad seleccionada
  $("#numAsegurados").change(function () {
    if ($("#grupoFamiliar").is(":checked")) {
      generateAseguradosFields();
    }
  });

  $("#menosCotizacion, #masCotizacion ").click(function () {
    toggleContainerData();
  });

  $('input[name="mismoAsegurado"]').change(function () {
    handleMismoAsegurado();
    syncFieldsOnChange();
    validateNames();
  });

  /*** Inicio bloque de codigo Javier-Dev, agrega logica para manejar eventos en las preguntas de cotización  ***/

  // Pregunta sobre ciudad barranquilla
  $('input[name="ciudadBarranquilla"]').change(function () {
    hideShowCamposCiudad();
  });

  // Pregunta sobre asociación a coomeva
  $('input[name="asociadoCoomeva"]').change(function () {
    hideShowAsociadoCoomeva();
  });

  /*** Fin bloque Javier-Dev ***/

  $('input[name="tipoCotizacion"]').change(function () {
    validateNames();
  });

  $("#modalCards").click(function (event) {
    openModal();
  });

  // Evento para remover o colocar campos al primer asegurado dependiendo de la cantidad seleccionada
  $('input[name="mismoAsegurado"]').on("change", function () {
    const valor = $(this).is(":checked") ? "si" : "no";
    const grupoFamiliar = $("#grupoFamiliar").is(":checked");

    if (valor === "si" && grupoFamiliar) {
      console.log("Se seleccionó SÍ");
      // acciones si se elige SÍ
    } else if (valor === "no" && grupoFamiliar) {
      console.log("Se seleccionó NO");
    }
  });

  $(document).on("click", "[id^=toggleBtn_]", function () {
    // Obtener la tabla objetivo basada en el data-target del botón
    var targetTable = $($(this).data("target"));

    // Alterna entre mostrar y ocultar la tabla con efecto de deslizamiento
    targetTable.slideToggle("fast");

    // Cambia el texto del botón dependiendo de su texto actual
    var button = $(this);
    if (button.text() === "Detalle de precios") {
      button.text("Ocultar detalles");
    } else {
      button.text("Detalle de precios");
    }
  });

  $("#btnCotizarAsiss").click(function (event) {
    if (!controlBtn) {
      if (!validateFormFields()) {
        event.preventDefault();
        Swal.fire({
          icon: "error",
          title:
            "Faltan datos en los campos marcados en rojo. Por favor, complételos.",
        });
      } else {
        cotizar();
        controlBtn = true;
        $("#containerDataTable").hide();
        $("#containerTable").hide();
      }
    }
  });

  $("#NroDocumento").numeric();
  if ($("#noAsociadoC").is(":checked")) {
    $;
  }
});

// ========================================================================================================================
