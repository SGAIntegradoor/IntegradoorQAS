// Declaramos las constantes que vamos a utilizar
const numMaxAseg = 10;

/**
 * Cargar fecha.
 * @function
 */
function initializeSelect2(selectors) {
    $(selectors).each(function () {
        if (!$(this).data('select2')) {
            $(this).select2({
                theme: "bootstrap fecnacimiento",
                language: "es",
                width: "100%",
            }).on("select2:open", function () {
                var $select2 = $(this).data("select2");
                $select2.dropdown.$dropdownContainer.addClass("select2-container--above");
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
        { value: "05", text: "DNI" }
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
      { value: "01", text: "Masculino" },
      { value: "02", text: "Femenino" }
    ];
  
    // Cambia 'tipoDocumento' por la clase correspondiente
    var selects = document.querySelectorAll(".genero");
    selects.forEach(function (select) {
      select.innerHTML = "";
      opciones.forEach(function (opcion) {
        var option = document.createElement("option");
        option.value = opcion.value;
        option.textContent = opcion.text;
        select.appendChild(option);
      });
    });
}

/**
 * Intercalar visibilidad de selector de numero de asegurados.
 * @function
 */
function toggleNumAsegSelector() {
    $('.cantAsegurados').hide();
    $('#aseguradosContainer').empty(); 

    $('input[name="tipoCotizacion"]').change(function() {

        if ($('#grupoFamiliar').is(':checked')) {
            $('.cantAsegurados').show();
            generateAseguradosFields();
            $('#lblTomador').text('¿El tomador también será asegurado?');
                        
        } else {
            $('.cantAsegurados').hide();
            $('#aseguradosContainer').empty(); 
            $('#lblTomador').text('¿El tomador es el mismo asegurado?'); 
        }
    });
}

/**
 * Crear campos para tantos asegurados se necesite. Se hizo asi porque se intento clonar el codigo que ya estaba en el DOM, pero la libreria select2 daba muchos errores al clonar.
 * @functions
 */
function generateAseguradosFields() {
    var numAsegurados = parseInt($('#numAsegurados').val());
    
    // Limpiar los campos existentes
    $('#aseguradosContainer').empty();

    for (var i = 2; i <= numAsegurados; i++) {
        // Crear el HTML para los nuevos campos
        var newFields = `
            <div class="row ">
                <div class="col-xs-12 col-sm-6 col-md-6 rowAseg">
                    <label>Datos Asegurado ${i}.</label>
                </div>
            </div>
            <div class="row asegurado" data-asegurado-id="${i}">
                <div class="col-xs-12 col-sm-6 col-md-1">
                    <div class="form-group">
                        <label for="tipoDocumento_${i}">Tipo de Doc</label>
                        <select id="tipoDocumento_${i}" class="form-control tipoDocumento"></select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <div class="form-group">
                        <label for="numeroDocumento_${i}">No. Documento</label>
                        <input id="numeroDocumento_${i}" class="form-control" type="number" />
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="nombreCompleto_${i}">Nombre Completo</label>
                        <div class="nombreCompleto">
                            <input id="nombre_${i}" class="form-control nombre" placeholder="Nombre" />
                            <input id="apellido_${i}" class="form-control apellido" placeholder="Apellido" />
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
        $('#aseguradosContainer').append(newFields);
    }

    // Inicializa Select2 solo en los nuevos elementos clonados
    initializeSelect2('.fecha-nacimiento');
    CargarSelectTipoDocumento();
    CargarSelectGenero();
 
}
function generateOptions(start, end, isMonth = false) {
    var options = '';
    for (var i = start; i <= end; i++) {
        var value = isMonth ? ('0' + i).slice(-2) : i;
        var display = isMonth ? getMonthName(i) : value;
        options += `<option value="${value}">${display}</option>`;
    }
    return options;
}
function getMonthName(month) {
    const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    return months[month - 1];
}

/**
 * Copiar campos de tomardor a asegurado cuando esta check.
 * @function
 */
function handleMismoAsegurado() {
    var isSameInsured = $('#si').is(':checked'); // Verificar si el radio button 'Sí' está seleccionado

    if (isSameInsured) {
        // Copiar información de los campos principales a los campos clonados
        var tipoDocumento = $('#tipoDocumento').val();
        var numeroDocumento = $('#numeroDocumento').val();
        var nombre = $('#nombre').val();
        var apellido = $('#apellido').val();

        $('.asegurado').each(function() {
            $(this).find('#tipoDocumento').val(tipoDocumento).trigger('change');
            $(this).find('#numeroDocumento').val(numeroDocumento);
            $(this).find('#nombre').val(nombre);
            $(this).find('#apellido').val(apellido);
        });
    } else {
        // Vaciar los campos clonados
        $('.asegurado').each(function() {
            $(this).find('#tipoDocumento').val('').trigger('change');
            $(this).find('#numeroDocumento').val('');
            $(this).find('#nombre').val('');
            $(this).find('#apellido').val('');
        });
    }
}

/**
 * Copiar campos de tomardor a asegurado cuando esta check y hay un onchange en los campos.
 * @function
 */
function syncFieldsOnChange() {
    var isSameInsured = $('#si').is(':checked');
    if (isSameInsured) {
        // Agregar eventos onchange a los campos principales
        $('#tipoDocumento').on('change', function() {
            var tipoDocumento = $(this).val();
            $('.asegurado').find('#tipoDocumento').val(tipoDocumento).trigger('change');
        });

        $('#numeroDocumento').on('input', function() {
            var numeroDocumento = $(this).val();
            $('.asegurado').find('#numeroDocumento').val(numeroDocumento);
        });

        $('#nombre').on('input', function() {
            var nombre = $(this).val();
            $('.asegurado').find('#nombre').val(nombre);
        });

        $('#apellido').on('input', function() {
            var apellido = $(this).val();
            $('.asegurado').find('#apellido').val(apellido);
        });
    } else {
        // Remover los eventos onchange si "No" está seleccionado
        $('#tipoDocumento').off('change');
        $('#numeroDocumento').off('input');
        $('#nombre').off('input');
        $('#apellido').off('input');
    }
}

/**
 * Validamos campos antes de enviar request.
 * @function
 */
function validateFormFields() {
    var allFieldsFilled = true;

    // Recorrer todos los inputs y selects dentro de containerDatosSalud
    $('#containerDatosSalud input, #containerDatosSalud select').each(function() {
        var $field = $(this);

        // Si el campo está vacío
        if ($field.val() === '' || $field.val() === null) {
            // Si es un campo Select2, aplicar borde rojo al contenedor de Select2
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').css('border', '2px solid red');
            } else {
                // Marcar con borde rojo los campos normales
                $field.css('border', '2px solid red');
            }
            allFieldsFilled = false;
        } else {
            // Quitar el borde si el campo está lleno
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').css('border', '');
            } else {
                $field.css('border', '');
            }
        }
    });

    return allFieldsFilled;
}

/**
 * Cambioamos nombre del primer asegurado.
 * @function
 */
function validateNames() {
    var lblName = $('#lblDatosAse');
    var suffix = $('#grupoFamiliar').is(':checked') ? '1' : '';

    if ($('#si').is(':checked')) {
        lblName.text('Tomador Asegurado ' + suffix);
    } else {
        lblName.text('Datos Asegurado ' + suffix);
    }
}


function cotizar() {
    var esCotizacionIndividual = $('#individual').is(':checked');
    var tomador = {
        tipoDocumento: $('#tipoDocumento').val(),
        numeroDocumento: $('#numeroDocumento').val(),
        nombre: $('#nombre').val(),
        apellido: $('#apellido').val()
    };

    // Obtener y convertir las variables para la fecha de nacimiento a números enteros
    var diaNacimiento = parseInt($('#dianacimiento').val(), 10);
    var mesNacimiento = parseInt($('#mesnacimiento').val(), 10);
    var anioNacimiento = parseInt($('#anionacimiento').val(), 10);

    // Añadir el asegurado base
    var aseguradoBase = {
        id: 1, // Aquí debes poner un ID apropiado si es necesario
        tipoDocumento: $('#tipoDocumento').val(),
        numeroDocumento: $('#numeroDocumento').val(),
        nombre: $('#nombre').val(),
        apellido: $('#apellido').val(),
        genero: $('#genero').val(),
        edad: calcularEdadAsegurado(diaNacimiento, mesNacimiento, anioNacimiento),
        fechaNacimiento: {
            dia: diaNacimiento,
            mes: mesNacimiento,
            anio: anioNacimiento
        }
    };

    var asegurados = [aseguradoBase];

    // Añadir los asegurados adicionales si es una cotización grupal
    if (!esCotizacionIndividual) {
        $('.row.asegurado').each(function() {
            var aseguradoId = $(this).data('asegurado-id');
            if (aseguradoId > 1) { // Comienza desde el ID 2
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
                        anio: anio
                    }
                };
                asegurados.push(asegurado);
            }
        });
    }

    // Finalmente, construimos el objeto final que se enviará
    var datosCotizacion = {
        esCotizacionIndividual: esCotizacionIndividual,
        tomador: tomador,
        asegurados: asegurados
    };

    // Puedes ver el JSON en la consola para verificar
    console.log(JSON.stringify(datosCotizacion, null, 2));

    // Aquí puedes realizar la petición AJAX o lo que necesites hacer con los datos
    // $.post('/tu-endpoint', datosCotizacion, function(response) {
    //     // Manejar la respuesta aquí
    // });
}

function calcularEdadAsegurado(dia, mes, anio) {
    var hoy = new Date();
    var fechaNacimiento = new Date(anio, mes - 1, dia);
    
    // Calcular la edad base
    var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    var mesNacimiento = hoy.getMonth() - fechaNacimiento.getMonth();

    // Ajustar la edad si el cumpleaños aún no ha pasado en el año actual
    if (mesNacimiento < 0 || (mesNacimiento === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
        edad--;
    }

    return edad;
}

/**
 * Inicializar todo.
 * @function
 */
$(document).ready(function () {
    initializeSelect2('.fecha-nacimiento');
  
    CargarSelectTipoDocumento();
    CargarSelectCantidadAsegurados();
    CargarSelectGenero();
    toggleNumAsegSelector();
    // Actualiza los campos de asegurado al cambiar la cantidad seleccionada
    $('#numAsegurados').change(function() {
        if ($('#grupoFamiliar').is(':checked')) {
            generateAseguradosFields();
        }
    });
    
    $("#menosCotizacion, #masCotizacion ").click(function () {
        toggleContainerData();
    });

    $('input[name="mismoAsegurado"]').change(function() {
        handleMismoAsegurado();
        syncFieldsOnChange();
        validateNames();
    });

    $('input[name="tipoCotizacion"]').change(function() {
        validateNames();
    });

    $('#btnCotizarAsiss').click(function(event) {
        if (!validateFormFields()) {
            event.preventDefault(); 
            Swal.fire({
                icon: "error",
                title: "Faltan datos en los campos marcados en rojo. Por favor, complételos.",
              });
        } else {
            cotizar();
        }
    });


});
// ========================================================================================================================