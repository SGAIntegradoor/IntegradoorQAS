//Constante con los nombre de productos, esto sepuede migrar despues a base de datos
const equivalencias = {
    "5D": "AC 60",
    "5C": "AC 150",
    "5B": "AC 250",
    "5E": "AC 35",
    "GD": "AC 60",
    "GC": "AC 150",
    "HK": "AC 250"
};

// Cargar el select de origen
function CargarSelectOrigen() {
  $("#lugarOrigen").html('<option value="">Cargando...</option>');
  $.ajax({
    url: "vistas/modulos/AssistCardCot/services/consultarOrigen.php",  // Cambia la URL a la que corresponda para cargar las opciones del select
    success: function(resp) {
      $("#lugarOrigen").html(resp);  // Inserta las opciones en el select
    }
  });
}
// ========================================================================================================================

// Cargar el select de destino
function CargarSelectDestino() {
  var opciones = [
    { value: '', text: 'Selecciona...' },
    { value: '1', text: 'Norte America' },
    { value: '2', text: 'Europa' },
    { value: '3', text: 'America Central & Caribe' },
    { value: '4', text: 'Sur America' },
    { value: '5', text: 'Africa' },
    { value: '6', text: 'Asia' },
    { value: '7', text: 'Oceania' }
];

  var select = document.getElementById('lugarDestino');
  select.innerHTML = '';
  opciones.forEach(function(opcion) {
      var option = document.createElement('option');
      option.value = opcion.value;
      option.textContent = opcion.text;
      select.appendChild(option);
  });
}
// ========================================================================================================================

// Cargar el select de destino
function CargarSelectMotivoViaje() {
  var opciones = [
    { value: '', text: 'Selecciona...' },
    { value: 'Vacacional', text: 'Vacacional' },
    { value: 'Empresarial', text: 'Empresarial' }
    // { value: 'Estudiantil', text: 'Estudiantil' }
];

  var select = document.getElementById('motivoViaje');
  select.innerHTML = '';
  opciones.forEach(function(opcion) {
      var option = document.createElement('option');
      option.value = opcion.value;
      option.textContent = opcion.text;
      select.appendChild(option);
  });
}
// ========================================================================================================================

// Carga la fecha de Nacimiento
$('#dianacimiento, #mesnacimiento, #anionacimiento').each(function() {
  $(this).select2({
      theme: "bootstrap fecnacimiento",
      language: "es",
      width: "100%"
      // Otras configuraciones específicas si las necesitas
  });
});
// ========================================================================================================================

 // Abrir y cerrar el data container
function toggleContainerData(){
  $("#menosCotizacion").toggle();
  $("#masCotizacion").toggle();
  $("#containerDatos").toggle();
}
// ========================================================================================================================

// Abrir y cerrar el cards container
function toggleContainerCards(){
  $("#menosParrilla").toggle();
  $("#masParrilla").toggle();
  $("#Cards").toggle();
}
// ========================================================================================================================

// Permitir cambiar los nombres de los produtos, por ahora se hace para vacacional
function changeNameProduct(codeProduct, nameOriginal) {
  var equivalente = equivalencias[codeProduct];
  return equivalente !== undefined ? equivalente : nameOriginal;
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
      result = "Múltiples viajes en 1 año, tope hasta 30 días consecutivos por cada viaje.";
      break;
    case "3":
      result = "Múltiples viajes en 1 año, tope hasta 60 días consecutivos por cada viaje.";
      break;
    case "4":
      result = "Larga Estadía Diaria";
      break;
    case "5":
      result = "Larga Estadia Anual";
      break;
    case "6":
      result = "Múltiples viajes en 1 año, tope hasta 90 días consecutivos por cada viaje.`"
      break;
    case "7":
      result = "Múltiples viajes en 1 año, tope hasta 120 días consecutivos por cada viaje.";
      break;
    case "8":
      result = "Capitas";
      break;
    case "9":
      result = "Múltiples viajes en 1 año, tope hasta 15 días consecutivos por cada viaje.";
      break;
    case "10":
      result = "Múltiples viajes en 1 año, tope hasta 45 días consecutivos por cada viaje.";
      break;
  }
  return result;
}
// ========================================================================================================================

// Funcion para formatear los numeros
function format(n, sep, decimals) {
  sep = sep || ",";//Default to period as decimal separator
  decimals = decimals || 0;//Default to 2 decimals

  return n.toLocaleString().split(sep)[0];
}

//funcion para invalidar las fechas anteriores a la actual
function invaldateBeforeDate(){
  var today = new Date();
  var day = ('0' + today.getDate()).slice(-2);
  var month = ('0' + (today.getMonth() + 1)).slice(-2);
  var year = today.getFullYear();
  var todayFormatted = year + '-' + month + '-' + day;
  $('#fechaSalida, #fechaRegreso').attr('min', todayFormatted);
}
// ========================================================================================================================

// Funcion para cargar estilos una vez se generan las cards
function cargarEstilos(url) {
  $('<link>')
      .appendTo('head')
      .attr({
          type: 'text/css',
          rel: 'stylesheet',
          href: url
      });
}
// ========================================================================================================================

//Cambiar titulo data container una vez se cotiza
function toogleDataContainer(){
  var newTittle = "DATOS DEL VIAJE"
  $("#lblDataTrip").text(newTittle);
  $("#colradioPeople, #colBtnCotizar").toggle();
}

//Cambiar titulo data container una vez se cotiza
function validarCampos() {
  var campos = ['#fechaSalida', '#fechaRegreso', '#lugarDestino', '#motivoViaje', '#dianacimiento', '#mesnacimiento', '#anionacimiento'];
  var camposValidos = true;

  campos.forEach(function(campo) {
      var $elemento = $(campo);
      var valor = $elemento.val();
      
      // Restaurar borde  si el campo tiene valor
      if (valor) {
          // Verificar si es un select y está usando Select2
          if ($elemento.is('select') && $elemento.hasClass('select2-hidden-accessible')) {
              var $select2Container = $elemento.next('.select2-container');
              $select2Container.find('.select2-selection').css('border', '');
          } else {
              $elemento.css('border', '');
          }
          return; 
      }

      // Aplicar borde rojo si el campo está vacío
      if ($elemento.is('select') && $elemento.hasClass('select2-hidden-accessible')) {
          var $select2Container = $elemento.next('.select2-container');
          $select2Container.find('.select2-selection').css('border', '1px solid red');
      } else {
          $elemento.css('border', '1px solid red');
      }
      camposValidos = false;
  });

  return camposValidos;
}
// ========================================================================================================================

//Funcion que permite cotizar la asistencia en viajes con AssitCard
function cotizar() {
  document.getElementById("spinener-cot").style.display = "flex";
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
  var contPasajeros = 1;
  var arrayPajaseros = {};
  var fechaNacimientoStr  = diaNac +"/"+ mesNac +"/" +anioNac;

  // Parsear la fecha de nacimiento a un objeto Date
var partesFecha = fechaNacimientoStr .split("/");
var fechaNacimiento = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);

// Obtener la fecha actual
var fechaActual = new Date();

// Calcular la diferencia en milisegundos entre las dos fechas
var diferencia = fechaActual.getTime() - fechaNacimiento.getTime();

// Calcular la edad en años
var edadPrincipalParaVerDetalles = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 365.25));


  txtFecSalida = txtFecSalidaOr.split("-");
  txtFecRegreso = txtFecRegresoOr.split("-");
  txtFecSalida = txtFecSalida[2] + "/" + txtFecSalida[1] + "/" + txtFecSalida[0];    
  txtFecRegreso = txtFecRegreso[2] + "/" + txtFecRegreso[1] + "/" + txtFecRegreso[0];  

  var DiasViaje = (new Date(txtFecRegresoOr).getTime() - new Date(txtFecSalidaOr).getTime()); 
  var txtDiasViaje = (Math.round(DiasViaje / (1000 * 60 * 60 * 24))) 

  var info_inputs = { txtOrigen, txtFecSalida, PlanFamilair, txtDestino, txtFecRegreso, contPasajeros, txtDiasViaje, arrayPajaseros, SelmotivoViaje2,fechaNacimientoStr ,edadPrincipalParaVerDetalles }; //Asignacion de valores a un array para mandarlo por POST


  // Ajax para mandar la informacion a cotizar con AssitCard
  $.ajax({
    url:  "https://grupoasistencia.com/assist_engine/WSAssistCard/assistCard.php",
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
        document.getElementById("row_contenedor_general").innerHTML = html_error;
      } else {
        if (objResponse.codigo) { 
            document.getElementById("spinener-cot").style.display = "none";
            Swal.fire({
              icon: "error",
              title: "Oops... Por favor revisa toda la información ingresada 🤔",
            });
        } else { 
          var dolarHoy = objResponse.cotizacionDolar;
          var cotizaciones = objResponse.cotizaciones; 
          var cotizacion = cotizaciones.cotizacion;
          var SelmotivoViaje2 = $("#motivoViaje").val();
          console.log(cotizacion);
          var html_data = "";
          $.each(cotizacion, function (key, cotizacionArray) {
              
              if(SelmotivoViaje2 =="Empresarial"){
                  if (cotizacionArray.codigo == "GD" || cotizacionArray.codigo == "GC" || cotizacionArray.codigo == "HK") {
                    toogleDataContainer();
                      html_data += ` 
                            <div class='card-ofertas'>
                              <div class='row card-body'>
                                  <div class="col-xs-12 col-sm-6 col-md-2 align-horizontal ">
                                      <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                  </div>

                                  <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                      <span class="tittleCard">
                                          Assist Card - ${changeNameProduct(cotizacionArray.codigo, cotizacionArray.nombreTarifa)}
                                      </span><br> 
                                      <span class="tittleCard">
                                          CORPORATIVO
                                      </span><br> 
                                      <span class="tittlePrice">
                                          Desde ${contPasajeros > 1 ?
                                          `US $` + parseFloat(cotizacionArray.clientesCotizados.clienteCotizacion[0].valorAsistencia).toFixed(2) :
                                          `US $` + parseFloat(cotizacionArray.clientesCotizados.clienteCotizacion.valorAsistencia).toFixed(2)}
                                      </span><br> 
                                  </div>

                                  <div class="col-xs-12 col-sm-6 col-md-3 oferta-logo">                
                                      <ul>
                                          <li>Cobertura USD 60.000</li>
                                          <li>Cobertura de accidentes</li>
                                          <li>Cobertura por enfermedades no preexistente</li>
                                          <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                          <li>Traslado ejecutivo por reemplazo de funcionario asistido</li>
                                      </ul>
                                  </div>

                                  <div class="col-xs-12 col-sm-6 col-md-3 oferta-logo">
                                      <ul>
                                          <li>Odontología de urgencia</li>
                                          <li>Repatriación Sanitaria derivada de una atención médica</li>
                                          <li>Repatriación funeraria</li>
                                          <li>Seguro de equipaje ante demora y pérdida</li>
                                          <li>Cobertura salvoconducto ante perdida de pasaporte</li>
                                      </ul>
                                  </div>

                                  <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                    <span> Muchas más coberturas  
                                    <i class="fa fa-hand-o-down" aria-hidden="true"></i>  
                                    </span>
                                      <button class="btn btn-primary btn-block btn-cot" id="">
                                          <span class="span_titulo_item">
                                              <a target="_blank" style="text-decoration:none;" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${cotizacionArray.pais}&producto=${cotizacionArray.codigo}&tarifa=${cotizacionArray.codigoTarifa}&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${cotizacionArray.cantidadDias == 365 ? `True` : `False`}'>Ver detalles</a>
                                          </span>
                                      </button>
                                  </div>
                              </div>
                          </div>
                      `;
                  }
              }                   
              if(SelmotivoViaje2 =="Vacacional"){
                  if(cotizacionArray.codigo !== "4C"){
                    toogleDataContainer();
                          html_data += ` 
                                <div class='card-ofertas'>
                                  <div class='row card-body'>
                                      <div class="col-xs-12 col-sm-6 col-md-2 ">
                                          <img src="vistas/modulos/AssistCardCot/img/LOGO-ROJO-01.png" class="logoCardAsist" alt="Logo">
                                      </div>

                                      <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                          <span class="tittleCard">
                                              Assist Card - ${changeNameProduct(cotizacionArray.codigo, cotizacionArray.nombreTarifa)}
                                          </span><br> 
                                          <span class="tittleCard">
                                              VACACIONAL
                                          </span><br> 
                                          <span class="tittlePrice">
                                              Desde  ${contPasajeros > 1 ?
                                              `US $` + parseFloat(cotizacionArray.clientesCotizados.clienteCotizacion[0].valorAsistencia).toFixed(2) :
                                              `US $` + parseFloat(cotizacionArray.clientesCotizados.clienteCotizacion.valorAsistencia).toFixed(2)}
                                          </span><br> 
                                      </div>

                                      <div class="col-xs-12 col-sm-6 col-md-3 oferta-logo">                
                                          <ul>
                                              <li>Cobertura USD 60.000</li>
                                              <li>Cobertura de accidentes</li>
                                              <li>Cobertura por enfermedades no preexistente</li>
                                              <li>Cobertura de estabilización de cuadro agudo de preexistencias</li>
                                          </ul>
                                      </div>

                                      <div class="col-xs-12 col-sm-6 col-md-3 oferta-logo">
                                          <ul>
                                              <li>Odontología de urgencia</li>
                                              <li>Repatriación Sanitaria derivada de una atención médica</li>
                                              <li>Repatriación funeraria</li>
                                              <li>Seguro de equipaje ante demora y pérdida</li>
                                          </ul>
                                      </div>

                                      <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
                                        <span> Muchas más coberturas  
                                        <i class="fa fa-hand-o-down" aria-hidden="true"></i>  
                                        </span>
                                          <button class="btn btn-primary btn-block btn-cot" id="">
                                              <span class="span_titulo_item">
                                                  <a target="_blank" style="text-decoration:none;" href='https://serviciocondiciones.assist-card.com/DetalleCcpp.ashx?codigoPais=${cotizacionArray.pais}&producto=${cotizacionArray.codigo}&tarifa=${cotizacionArray.codigoTarifa}&edad=${edadPrincipalParaVerDetalles}&idLanguage=1&anual=${cotizacionArray.cantidadDias == 365 ? `True` : `False`}'>Ver detalles</a>
                                              </span>
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          `;
                      }
                  }           
          });
          document.getElementById("spinener-cot").style.display = "none";
          document.getElementById("row_contenedor_general").innerHTML = html_data;
          //   });
          cargarEstilos("vistas/modulos/AssistCardCot/css/cards.css")
          Swal.fire({
            title: "¡Cotización Exitosa 😎!",
            icon: "success"
          });
        }
      }
    },
    error: function (data) {
      alert("Error");
    }
  });
}

// Inicializacion de funciones
$(document).ready(function() {
  CargarSelectOrigen();
  CargarSelectDestino();
  CargarSelectMotivoViaje();

  //Inicializamos el tooltip
  $('[data-toggle="tooltip"]').tooltip();

  $("#btnCotizar").click(function() { 
    var dataOk = validarCampos()
    if (dataOk){
      cotizar();
    }
  });

  $("#menosCotizacion, #masCotizacion ").click(function(){
    toggleContainerData();
  })

  $("#menosParrilla, #masParrilla").click(function(){
    toggleContainerCards();
  })
 
  invaldateBeforeDate();

});
// ========================================================================================================================
