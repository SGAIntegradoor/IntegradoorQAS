// Declaramos las constantes que vamos a utilizar
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
      $(".cantAsegurados").show();
      generateAseguradosFields();
      $("#lblTomador").text("¿El tomador también será asegurado?");
    } else {
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

  for (var i = 2; i <= numAsegurados; i++) {
    // Crear el HTML para los nuevos campos
    var newFields = `
            <div class="row ">
                <div class="col-xs-12 col-sm-6 col-md-6 rowAseg">
                    <label>Datos Asegurado ${i}.</label>
                </div>
            </div>
            <div class="row asegurado" data-asegurado-id="${i}">
                <div class="col-xs-12 col-sm-6 col-md-2">
                    <div class="form-group">
                        <label for="tipoDocumento_${i}">Tipo de Documento</label>
                        <select id="tipoDocumento_${i}" class="form-control tipoDocumento"></select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <div class="form-group">
                        <label for="numeroDocumento_${i}">No. Documento</label>
                        <input id="numeroDocumento_${i}" class="form-control" type="number" />
                    </div>
                </div>

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
                                    ${generateOptions(1920, 2024)}
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
            </div>
        `;

    // Agregar los nuevos campos al contenedor
    $("#aseguradosContainer").append(newFields);
    activateFormate();
  }

  // Inicializa Select2 solo en los nuevos elementos clonados
  initializeSelect2(".fecha-nacimiento");
  CargarSelectTipoDocumento();
  CargarSelectGenero();
}
function generateOptions(start, end, isMonth = false) {
  var options = "";
  for (var i = start; i <= end; i++) {
    var value = isMonth ? ("0" + i).slice(-2) : i;
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
    // Copiar información de los campos principales a los campos clonados
    var tipoDocumento = $("#tipoDocumento").val();
    var numeroDocumento = $("#numeroDocumento").val();
    var nombre = $("#nombre").val();
    var apellido = $("#apellido").val();

    $(".asegurado").each(function () {
      $(this).find("#tipoDocumento").val(tipoDocumento).trigger("change");
      $(this).find("#numeroDocumento").val(numeroDocumento);
      $(this).find("#nombre").val(nombre);
      $(this).find("#apellido").val(apellido);
    });
  } else {
    // Vaciar los campos clonados
    $(".asegurado").each(function () {
      $(this).find("#tipoDocumento").val("").trigger("change");
      $(this).find("#numeroDocumento").val("");
      $(this).find("#nombre").val("");
      $(this).find("#apellido").val("");
    });
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
    $("#tipoDocumento").on("change", function () {
      var tipoDocumento = $(this).val();
      $(".asegurado")
        .find("#tipoDocumento")
        .val(tipoDocumento)
        .trigger("change");
    });

    $("#numeroDocumento").on("input", function () {
      var numeroDocumento = $(this).val();
      $(".asegurado").find("#numeroDocumento").val(numeroDocumento);
    });

    $("#nombre").on("input", function () {
      var nombre = $(this).val();
      $(".asegurado").find("#nombre").val(nombre);
    });

    $("#apellido").on("input", function () {
      var apellido = $(this).val();
      $(".asegurado").find("#apellido").val(apellido);
    });
  } else {
    // Remover los eventos onchange si "No" está seleccionado
    $("#tipoDocumento").off("change");
    $("#numeroDocumento").off("input");
    $("#nombre").off("input");
    $("#apellido").off("input");
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
  nombrePlan,
  precioMensual,
  precioTrimestral,
  precioSemestral,
  precioAnual,
  coberturas,
  tipoCotizacion,
  cantAseg,
  tableHTML
) {
  return `
    <div class='card-ofertas'>
      <div class='row card-body'>
          <div class="col-xs-12 col-sm-6 col-md-2 align-horizontal ">
              <img src="vistas/modulos/SaludCot/img/logo-convenio-axa-colpatria.png" class="logoCardAsist" alt="Logo">  
              <span class="tittleCard tittleCard_top">
                  ${capitalizeFirstLetter(nombrePlan)}
              </span>
          </div>

          <div class="col-xs-12 col-sm-6 col-md-5 oferta-logo infoPlanes">
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
                ${
                  capitalizeFirstLetter(nombrePlan) === "Fesalud amparado"
                    ? `<div class="col-xs-12 col-sm-6 col-md-5 textCards">     
                  <div style="width: 100%; text-align: justify;">
                    <b>Este Plan es mejor que el Plan Original, incluyendo coberturas como:</b> Urgencias + Hospitalización y cirugía + Consulta externa ilimitada en todas las especialidades + Examenes y laboratorios + Asistencia medica domiciliaria (primeras 5 sin pago de bono) + Continuidad en el pago de prima por desempleo + Exoneración pago de prima por fallecimiento + Urgencias odontologicas
                    <br>
                    <b>Más estas otras coberturas como:</b>
                    <br>
                    <ul>
                      <li>
                      Clínicas TOP
                      </li>
                      <li>
                      Deportes de Alto riesgo
                      </li>
                      <li>
                      Acceso a servicios sin cobro de bono (terapias y exámenes)
                      </li>
                      <li>
                      Asistencia internacional USD$50.000
                      </li>
                    </ul>
                    <a href="vistas/pdfs/PDF FESALUD AMPARADO.pdf" target="_blank"><img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/> Ver más</a> 
                  </div>
                </div>`
                    : capitalizeFirstLetter(nombrePlan) === "Original amparado"
                    ? `<div class="col-xs-12 col-sm-6 col-md-5 textCards">     
                    <div style="width: 100%; text-align: justify;">
                      <b>Este Plan es mejor que el Plan Alterno, incluyendo coberturas como:</b> Urgencias + Hospitalización y cirugía + Consulta externa ilimitada en todas las especialidades + Examenes y laboratorios + Asistencia medica domiciliaria (primeras 5 sin pago de bono)
                      <br>
                      <b>Más estas otras coberturas como:</b>
                      <br>
                      <ul>
                        <li>
                        Continuidad en el pago de prima por desempleo
                        </li>
                        <li>
                        Exoneración pago de prima por fallecimiento
                        </li>
                        <li>
                        Urgencias odontologicas
                        </li>
                        <li>
                        Asistencia internacional USD$30.000
                        </li>
                      </ul>
                      <a href="vistas/pdfs/PDF ORIGINAL AMPARADO.pdf" target="_blank"><img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/> Ver más</a> 
                    </div>
                  </div>`
                    : capitalizeFirstLetter(nombrePlan) === "Alterno amparado"
                    ? `<div class="col-xs-12 col-sm-6 col-md-5 textCards">     
                      <div style="width: 100%; text-align: justify;">
                        <b>El Plan Alterno incluye coberturas como:</b> 
                        <br>
                        <ul>
                          <li>
                          Urgencias
                          </li>
                          <li>
                          Hospitalización y cirugía
                          </li>
                          <li>
                          Consulta externa ilimitada en todas las especialidades
                          </li>
                          <li>
                          Examenes y laboratorios
                          </li>
                          <li>
                          Asistencia internacional USD$25.000
                          </li>
                          <li>
                          Asistencia medica domiciliaria (primeras 5 sin pago de bono)
                          </li>
                        </ul>
                        <a href="vistas/pdfs/PDF ALTERNO AMPARADO.pdf" target="_blank"><img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/> Ver más</a> 
                      </div>
                    </div>`
                    : capitalizeFirstLetter(nombrePlan) === "Salud ideal"
                    ? `<a href="vistas/pdfs/PDF SALUD IDEAL.pdf" target="_blank"> <img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/>Ver más</a>`
                    : capitalizeFirstLetter(nombrePlan) === "Salud ideal + emermedica"
                    ? `<div class="col-xs-12 col-sm-6 col-md-5 textCards">
                    <div style="width: 100%; text-align: justify;">
                      <b>Incluye coberturas del Plan Salud Ideal como: </b>emergencias odontológicas + consultas, exámenes complementarios y terapias relacionados con hospitalización + medicamentos ambulatorios de hospitalización + atención médica de urgencias en el exterior.
                    <br>
                    <b>Más estas otras coberturas como:</b>
                      <br>
                      <ul>
                        <li>
                        Asesoría en Lactancia Materna
                        </li>
                        <li>
                        Consulta Médica domiciliaria ilimitada sin Pago de bono.
                        </li>
                      </ul>
                      <a href="vistas/pdfs/PDF SALUD IDEAL + EMERMEDICA.pdf" target="_blank"><img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/> Ver más</a> 
                    </div>
                  </div>`
                    : capitalizeFirstLetter(nombrePlan) === "Plan ambulatorio" ? `<div class="col-xs-12 col-sm-6 col-md-5 textCards">     
                    <div style="width: 100%; text-align: justify;">
                      <b>Coberturas:</b>
                      <br>
                      <ul>
                        <li>
                        Consulta médica general, prioritaria y especializada con bono
                        </li>
                        <li>
                        Traslado a consulta médica 3 eventos por año de vigencia sin pago de bono
                        </li>
                        <li>
                        Servicio odontologicos de urgencia y prevención
                        </li>
                        <li>
                        Exámenes de laboratorio y diagnóstico simple 
                        </li>
                        <li>
                         Renta diaria por hospitalización
                        </li>
                        <li>
                         Terapias ilimitadas con pago de bono y muchas coberturas más
                        </li>
                      </ul>
                      <a href="vistas/pdfs/PDF PLAN AMBULTARIO.pdf" target="_blank"><img src="vistas/img/iconosResources/icons8-pdf-office-m/icons8-pdf-30.png" width="25px"/> Ver más</a> 
                    </div>
                  </div>` : ""
                }
                
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
function makeTable(asegurados, plan_id) {
  const uniqueId = `table_${plan_id}`; // Crear un ID único basado en plan_id
  const buttonId = `toggleBtn_${plan_id}`;

  let tableHTML = `

  <div class="container flex-colum table-responsive">
      <div class="row custom-table-colum">
            <div class="col-12">
              <button id="${buttonId}" class="btn-table float-left" data-target="#${uniqueId}">Ver detalle de precios por asegurado</button>
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
                              <th>Trimestral</th>
                              <th>Semestral</th>
                              <th>Anual</th>
                          </tr>
                      </thead>
                      <tbody>`;

  let subtotalMensual = 0,
    subtotalTrimestral = 0,
    subtotalSemestral = 0,
    subtotalAnual = 0;

  asegurados.forEach((asegurado) => {
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
      subtotalAnual += anual;
      let generoTexto = asegurado.genero === "1" ? "Masculino" : "Femenino";

      tableHTML += `
          <tr>
              <td>${asegurado.nombre} ${asegurado.apellido}</td>
              <td>${generoTexto}</td>
              <td>${asegurado.edad}</td>
              <td>$${processValue(mensual, 0)}</td>
              <td>$${processValue(trimestral, 0)}</td>
              <td>$${processValue(semestral, 0)}</td>
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
                              <td>$${processValue(subtotalTrimestral, 0)}</td>
                              <td>$${processValue(subtotalSemestral, 0)}</td>
                              <td>$${processValue(subtotalAnual, 0)}</td>
                          </tr>
                          <tr class="bold-row">
                              <th class="th-out-border"></th>
                              <td colspan="2">IVA (5%)</td>
                              <td>$${processValue(ivaMensual, 0)}</td>
                              <td>$${processValue(ivaTrimestral, 0)}</td>
                              <td>$${processValue(ivaSemestral, 0)}</td>
                              <td>$${processValue(ivaAnual, 0)}</td>
                          </tr>
                          <tr class="bold-row">
                              <th class="th-out-border"></th>
                              <td colspan="2">Total</td>
                              <td>$${processValue(totalMensual, 0)}</td>
                              <td>$${processValue(totalTrimestral, 0)}</td>
                              <td>$${processValue(totalSemestral, 0)}</td>
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

/**
 * Manager para generar las cards en general.
 * @function
 */
function makeCards(data, tipoCotizacion) {
  let html_data = "";

  if (tipoCotizacion === 1) {
    // Generar tarjetas individuales para cada plan
    // Acumular los valores por plan_id
    let planesSumados = {};

    data.asegurados.forEach((asegurado) => {
      asegurado.planes.forEach((plan) => {
        if (!planesSumados[plan.plan_id]) {
          planesSumados[plan.plan_id] = {
            nombre: plan.nombre,
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
        planesSumados[plan.plan_id].anual += parseFloat(
          plan.anual.replace(/\./g, "").replace(",", ".")
        );

        // Si el plan tiene coberturas, agregarlas
        if (planesSumados[plan.plan_id].coberturas.length === 0) {
          let nombrePlanUpper = plan.nombre.toUpperCase();
          switch (nombrePlanUpper) {
            case "FESALUD AMPARADO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_FESALUD_AMPARADO;
              break;
            case "ORIGINAL AMPARADO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_ORIGINAL_AMPARADO;
              break;
            case "ALTERNO AMPARADO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_ALTERNO_AMPARADO;
              break;
            case "SALUD IDEAL":
              planesSumados[plan.plan_id].coberturas = COBERTURAS_SALUD_IDEAL;
              break;
            case "SALUD IDEAL + EMERMEDICA":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_SALUD_IDEAL_EMERMEDICA;
              break;
            case "PLAN AMBULATORIO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_PLAN_AMBULATORIO;
              break;
            default:
              planesSumados[plan.plan_id].coberturas = ["Cobertura estándar"];
          }
        }
      });
    });

    // Generar tarjetas grupales con los valores sumados
    for (let plan_id in planesSumados) {
      let plan = planesSumados[plan_id];
      let tableHTML = makeTable(data.asegurados, plan_id);
      html_data += makeIndividualCard(
        plan.nombre,
        processValue(plan.mensual, iva),
        processValue(plan.trimestral, iva),
        processValue(plan.semestral, iva),
        processValue(plan.anual, iva),
        plan.coberturas,
        tipoCotizacion,
        data.asegurados.length,
        tableHTML
      );
    }
  } else if (tipoCotizacion === 2) {
    // Acumular los valores por plan_id
    let planesSumados = {};

    data.asegurados.forEach((asegurado) => {
      asegurado.planes.forEach((plan) => {
        if (!planesSumados[plan.plan_id]) {
          planesSumados[plan.plan_id] = {
            nombre: plan.nombre,
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
        planesSumados[plan.plan_id].anual += parseFloat(
          plan.anual.replace(/\./g, "").replace(",", ".")
        );

        // Si el plan tiene coberturas, agregarlas
        if (planesSumados[plan.plan_id].coberturas.length === 0) {
          let nombrePlanUpper = plan.nombre.toUpperCase();
          switch (nombrePlanUpper) {
            case "FESALUD AMPARADO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_FESALUD_AMPARADO;
              break;
            case "ORIGINAL AMPARADO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_ORIGINAL_AMPARADO;
              break;
            case "ALTERNO AMPARADO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_ALTERNO_AMPARADO;
              break;
            case "SALUD IDEAL":
              planesSumados[plan.plan_id].coberturas = COBERTURAS_SALUD_IDEAL;
              break;
            case "SALUD IDEAL + EMERMEDICA":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_SALUD_IDEAL_EMERMEDICA;
              break;
            case "PLAN AMBULATORIO":
              planesSumados[plan.plan_id].coberturas =
                COBERTURAS_PLAN_AMBULATORIO;
              break;
            default:
              planesSumados[plan.plan_id].coberturas = ["Cobertura estándar"];
          }
        }
      });
    });

    // Generar tarjetas grupales con los valores sumados
    for (let plan_id in planesSumados) {
      let plan = planesSumados[plan_id];
      let tableHTML = makeTable(data.asegurados, plan_id);
      html_data += makeIndividualCard(
        plan.nombre,
        processValue(plan.mensual, iva),
        processValue(plan.trimestral, iva),
        processValue(plan.semestral, iva),
        processValue(plan.anual, iva),
        plan.coberturas,
        tipoCotizacion,
        data.asegurados.length,
        tableHTML
      );
    }
  }

  document.getElementById("row_contenedor_general_salud").innerHTML = html_data;
  cargarEstilos("vistas/modulos/SaludCot/css/cardsResult.css");
  Swal.fire({
    title: "¡Cotización Exitosa!",
    icon: "success",
  });
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
  document.getElementById("spinener-cot-salud").style.display = "flex";
  var tipoCotizacion = $("#individual").is(":checked") ? 1 : 2;
  var esCotizacionIndividual = $("#individual").is(":checked");
  var tomador = {
    tipoDocumento: $("#tipoDocumento").val(),
    numeroDocumento: $("#numeroDocumento").val(),
    nombre: $("#nombre").val(),
    apellido: $("#apellido").val(),
  };

  // Obtener y convertir las variables para la fecha de nacimiento a números enteros
  var diaNacimiento = parseInt($("#dianacimiento").val(), 10);
  var mesNacimiento = parseInt($("#mesnacimiento").val(), 10);
  var anioNacimiento = parseInt($("#anionacimiento").val(), 10);

  // Añadir el asegurado base
  var aseguradoBase = {
    id: 1, // Aquí debes poner un ID apropiado si es necesario
    tipoDocumento: $("#tipoDocumento").val(),
    numeroDocumento: $("#numeroDocumento").val(),
    nombre: $("#nombre").val(),
    apellido: $("#apellido").val(),
    genero: $("#genero").val(),
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
      if (aseguradoId > 1) {
        // Comienza desde el ID 2
        var dia = parseInt($(this).find('[id^="dianacimiento_"]').val(), 10);
        var mes = parseInt($(this).find('[id^="mesnacimiento_"]').val(), 10);
        var anio = parseInt($(this).find('[id^="anionacimiento_"]').val(), 10);

        var asegurado = {
          id: aseguradoId,
          tipoDocumento: $(this).find('[id^="tipoDocumento_"]').val(),
          numeroDocumento: $(this).find('[id^="numeroDocumento_"]').val(),
          nombre: $(this).find('[id^="nombre_"]').val(),
          apellido: $(this).find('[id^="apellido_"]').val(),
          genero: $(this).find('[id^="genero_"]').val(),
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

  // Finalmente, construimos el objeto final que se enviará
  var datosCotizacion = {
    tipoCotizacion: tipoCotizacion,
    tomador: tomador,
    asegurados: asegurados,
  };

  // Puedes ver el JSON en la consola para verificar
  console.log(JSON.stringify(datosCotizacion, null, 2));

  $.ajax({
    url: "https://grupoasistencia.com/health_engine/WSAxa/axa.php",
    type: "POST",
    data: JSON.stringify(datosCotizacion),
    success: function (data) {
      hideMainContainerCards();
      showContainerCardsSalud();
      toogleDataContainer();
      makeCards(data, tipoCotizacion);
      console.log(data);
      document.getElementById("spinener-cot-salud").style.display = "none";
    },
    error: function (data) {
      alert("Error");
    },
  });
  window.scrollTo(0, 0);
}

/**
 * Inicializar todo.
 * @function
 */
$(document).ready(function () {
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

  $('input[name="tipoCotizacion"]').change(function () {
    validateNames();
  });

  $("#modalCards").click(function (event) {
    openModal();
  });

  $(document).on("click", "[id^=toggleBtn_]", function () {
    // Obtener la tabla objetivo basada en el data-target del botón
    var targetTable = $($(this).data("target"));

    // Alterna entre mostrar y ocultar la tabla con efecto de deslizamiento
    targetTable.slideToggle("fast");

    // Cambia el texto del botón dependiendo de su texto actual
    var button = $(this);
    if (button.text() === "Ver detalle de precios por asegurado") {
      button.text("Cerrar detalle de precios por asegurado");
    } else {
      button.text("Ver detalle de precios por asegurado");
    }
  });

  $("#btnCotizarAsiss").click(function (event) {
    if (!validateFormFields()) {
      event.preventDefault();
      Swal.fire({
        icon: "error",
        title:
          "Faltan datos en los campos marcados en rojo. Por favor, complételos.",
      });
    } else {
      cotizar();
    }
  });
});
// ========================================================================================================================
