<head>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta2/css/all.css" integrity="sha384-OA4SkQ1hW5kfQF3/OBdzK99bg7sQKT6+yXxq5Iu7QvGrrkrBsX3p5SRy9CrJ0+Gx" crossorigin="anonymous">

</head>
<style>
  input[type="checkbox"] {
    content: "";
    width: 26px;
    height: 26px;
    border: 2px solid #ccc;
    background: #ddd;
  }

  .gray-header {
    color: #808080;
  }

  .divBoton {
    display: flex;
    justify-content: end;
  }

  .separador {
    margin-left: 15px;
  }

  .smaller-input {
    max-width: 200px;
    margin: 0 auto;
  }

  .input-addon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 325px;
    z-index: 1;
  }

  .placeholder {
    position: absolute;
    top: 0;
    left: 0;
    padding: 6px;
    color: #aaa;
    pointer-events: none;
    transition: all 0.2s;
  }

  .input-container {
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }

  .input-container .form-control {
    margin-left: 10px;
  }

  .form {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .form input {
    width: 90%;
    height: 80px;
    margin: 0.5rem;
  }

  .form button {
    padding: 0.5em 1em;
    border: none;
    background: rgb(100, 200, 255);
    cursor: pointer;
  }


  .container {
    display: flex;
    justify-content: center;
  }

  .login-logo {
    display: flex;
    justify-content: center;
    align-items: center;
    /* margin-right: 20px; */
  }

  .rounded-container {
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    padding: 10px;
    /* max-width: 400px; Ajusta el valor según tus necesidades */
    /* width: 390px; */
    /* height: 300px; */
    margin: 0 auto;
    /* margin: 10% 0% 10% 0%; */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
  }

  .rounded-container-logo {
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 0 0px rgba(0, 0, 0, 0.3);
    padding: 10px;
    max-width: 400px;
    /* Ajusta el valor según tus necesidades */
    margin: 0 auto;
    text-align: center;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .circle-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    /* Ajusta el ancho según tus necesidades */
    height: 100%;
    /* Ajusta la altura según tus necesidades */
    overflow: hidden;
    /* Oculta el contenido que se desborde del contenedor */
  }


  .circle {
    width: 90%;
    height: 90%;
    border-radius: 100%;
    overflow: hidden;
    /* margin-right: 5px; */
  }


  .circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  #contenBtnConsultarExequial {
    padding-top: 25px;
  }

  .card-exequias {
    flex: 0 1 calc(15% - 0px);
    /* 48% es solo un ejemplo, ajusta según tus necesidades */
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 0 7px rgba(0, 0, 0, 0.3);
    /* padding: 3.5% 7%; */
    padding: 10px 32px;
    max-width: 100%;
    margin: 0 auto;
    margin-top: 12px;
    /* min-height: 370px; */
    /* max-height: 370px;  */
    text-align: center;
    margin-bottom: 12px;
    display: flex;
    flex-direction: column;
    /* justify-content: center; */
    align-items: center;
  }

  .card-exequias-logo {
    flex: 0 1 calc(15% - 0px);
    /* 48% es solo un ejemplo, ajusta según tus necesidades */
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 0 0px rgba(0, 0, 0, 0.3);
    /* padding: 3.5% 7%; */
    /* padding: 10px 30px; */
    max-width: 100%;
    margin: 0 auto;
    margin-top: 12px;
    /* min-height: 370px; */
    /* max-height: 370px;  */
    text-align: center;
    margin-bottom: 12px;
    display: flex;
    flex-direction: column;
    /* justify-content: center; */
    align-items: center;
  }


  .row-card {

    padding-top: 3%;
    padding-left: 3%;
    padding-right: 3%;
    display: flex;

  }

  .row-card-end {
    padding-bottom: 3%;
    padding-left: 7%;
    padding-right: 7%;
  }

  .error-message {
    display: none;
    color: red;
    font-size: 12px;
    margin-top: 5px;
  }

  input:invalid+.error-message,
  select:invalid+.error-message {
    display: block;
  }

  .row1 {
    display: flex;

  }

  .card-text {

    text-align: justify;

  }

  .card-container {
    display: flex;
    flex-wrap: wrap;
    /* justify-content: space-between; */
  }

  .card-exequias .card-title {

    font-size: 19px;
    margin-bottom: 5.5%;
  }

  .card-exequias .card-text {
    font-size: 13px;
    margin-bottom: 14px;
  }

  /* Estilo para la card especial sin sombra en el borde */
  .special-card {
    box-shadow: none;
    /* Esto elimina la sombra */
  }

  /* .card-exequias .card-text {
    margin: 0 auto;  
  min-height: 100px;
  border-radius: 20px;
  text-align: center; 
  margin-bottom: 10px; 
  display: flex; 
  flex-direction: column; 
  justify-content: center;
} */

  /* .card-exequias .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    height: 100%;
} */

  .miIframe {
    width: 100%;
    max-width: none;
    height: 900px;
    transition: width 0.5s;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">

    <h1 style="margin-bottom: 0%;">

      Solicitud de Cotización de Hogar

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Hogar</li>

    </ol>

  </section>
  <section class="content">
    <div class="box">
      <div class="row card-container">
        <!-- TITULO PLANES -->
        <div class="content">

          <!-- //LOGO Y DESCRIPCIÓN// -->
          <!-- Primera tarjeta con el logo -->
          <div class="col-md-4 col-sm-12 mb-3">
            <div class="card-exequias special-card">
              <div class="card-body" style="display: flex; flex-direction:column; align-items: center; justify-content: center;">
                <h4 class="card-title" style="font-weight: bold; padding-top: 20px">¿Por qué ofrecer el Seguro de<br> Hogar a tus clientes?</h4>
                <img src="vistas/img/plantilla/imagenHogar.png" class="img-fluid mx-auto" style="max-width: 40%; margin-bottom: 10px;">
                <p class="card-text" style="width: 300px">El <b>Seguro de Hogar</b> protege tanto la estructura de la casa o apartamento, es decir, la parte destructible de la vivienda, como también, los contenidos (muebles y enseres, equipos eléctricos y/o electrónicos, entre otros).
                  <br>
                  Además protege a las familias ante daños que puedan ser ocasionados a terceras personas (puede incluirse daños causados por empleados domésticos y mascotas).
                </p>
              </div>
            </div>
          </div>

          <!-- Segunda tarjeta con título y párrafo -->
          <div class="col-md-4 col-sm-12 mb-3">
            <div class="card-exequias">
              <div class="card-body">
                <h4 class="card-title" style="font-weight: bold; padding-top: 20px">¿Con qué aseguradoras y cuáles son los tiempos de respuesta?</h4>
                <p class="card-text">Ten presente que para cotizar un <b>Seguro de Hogar</b> debes registrar correcta y completamente el formulario que se encuentra en la parte inferior.
                  <br>
                  <br>
                  <b>Cotizamos con 2 aseguradoras:</b>
                </p>
                <div style="display: flex; flex-direction: row; gap: 30px; justify-content: center; padding-top: 30px;">
                  <img src="vistas/img/logos/LOGO BOLIVAR.png" class="img-fluid mx-auto" style="max-width: 35%;">
                  <img src="vistas/img/logos/LOGO ALLIANZ.png" class="img-fluid mx-auto" style="max-width: 35%; margin-top: 3px;">
                </div>
                <br>
                <br>
                <p class="card-text">Nuestros tiempos de respuesta para cotizaciones es de <b>5 horas hábiles</b>, siempre y cuando el formulario esté debidamente diligenciado.</p>
              </div>
            </div>
          </div>

          <!-- Tercera tarjeta con título y párrafo -->
          <div class="col-md-4 col-sm-12 mb-3">
            <div class="card-exequias">
              <div class="card-body">
                <h4 class="card-title" style="font-weight: bold; padding-top: 20px">¿Cuáles son las principales coberturas del Seguro de Hogar?</h4>
                <p class="card-text">Este seguro puede ofrecerse en 3 modalidades: sólo contenidos, sólo estructuras (también llamado edificio) y contenidos + estructuras.
                  <br>
                  <br>
                  <b>Principales coberturas y asistencias:</b></p>
                
                <ul class="card-text" style="padding-left: 16px; text-align: justify;">
                  <li>
                  Incendio, rayo, terremoto, daños por agua, anegación, huracán, granizo, vientos fuertes y otros eventos de la naturaleza
                  </li>
                  <li>
                    Hurto con violencia
                  </li>
                  <li>
                    Rotura accidental de vidrios planos y permanentes 
                  </li>
                  <li>
                    Asonada, motín, conmoción civil o popular, huelgas, conflictos colectivos de trabajo, actos mal intencionados de terceros y terrorismo
                  </li>
                  <li>
                    Responsabilidad civil extracontractual
                  </li>
                  <li>
                    Asistencia domiciliaria: cerrajería, electricidad, plomería, entre otros.
                  </li>
                </ul>  
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12 mb-3">
            <div class="card-exequias">
              <div class="card-body">
              <h4 class="card-title" style="font-weight: bold; padding-top: 20px">¿Qué datos se requieren para cotizar?</h4>
              <p class="card-text" style="text-align: justify;">Ten a la mano los siguientes datos para solicitar una cotización de <b>Seguro de Hogar:</b></p>
                <ul class="card-text" style="padding-left: 18px; text-align: justify;">
                  <li><b>Datos del asegurado</b> (Ej: No. de Identificación, nombre completo, etc.)</li>
                  <li><b>Datos generales del inmueble</b> (Ej: Dirección completa, ciudad, departamento, estrato, características de la vivienda, área, tipo de construcción, etc.)</li>
                  <li><b>Valores asegurados requeridos</b> (Ej: Valores a nuevo aproximados de contenidos como muebles, enseres, electrodomésticos, artículos que salen del hogar, etc.)</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- quinta tarjeta con título y párrafo -->
          <div class="col-md-4 col-sm-12 mb-3">
            <div class="card-exequias">
              <div class="card-body">
                <h4 class="card-title" style="font-weight: bold; padding-top: 20px">Políticas de Suscripción</h4>
                <p class="card-text">Estas son algunas de las políticas de suscripción, las cuales pueden cambiar en cualquier momento, sin previo aviso:</p>
                <ul class="card-text" style="padding-left: 18px; text-align: justify;">
                  <li>Se aseguran riesgos en ciudades capitales y municipios principales.</li>
                  <li>Inmuebles de estrato 3 en adelante.</li>
                  <li>Inmuebles de máximo 35 años de construcción.</li>
                  <li>El tipo de uso de la vivienda debe ser familiar</li>
                  <li>Riesgos en zonas rurales requieren autorización.</li>
                  <li>Los inmuebles se aseguran a valor comercial.</li>
                  <li>Se pueden requerir fotos o coordenadas del predio al momento de emisión.</li>
                  <li>Los contenidos se aseguran a valor reposición a nuevo.</li>
                  <li>Se requiere una relación detallada de los artículos que desean asegurarse para la cobertura Todo Riesgo.</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- sexta tarjeta con título y párrafo -->
          <div class="col-md-4 col-sm-12 mb-3">
            <div class="card-exequias">
              <div class="card-body text-center">
                <h4 class="card-title" style="font-weight: bold; padding-top: 20px">Comisión</h4>
                <p class="card-text">La comisión base que nos ofrece Seguros Bolívar y Allianz Seguros para nuestra alianza de asesores es del 15% y 20% respectivamente. De este porcentaje, tu participación será de acuerdo al nivel de ventas de todos los negocios (sin IVA), sumando todos los ramos, que realices en el mes.</p>
                <ul class="card-text" style="padding-left: 0px; list-style-position: inside;">
                  <li>- Primas mensuales inferiores a 8 SMMLV: 70%</li>
                  <li>- Primas mensuales superiores a 8 SMMLV: 75%</li> 
                </ul>
                <h4 class="card-title" style="font-weight: bold;">Clausulados</h4>
                <p class="card-text" style="text-align:justify">
                  Para conocer más del <b>Seguro de Hogar</b> de <b>Allianz</b> ingresa <b><a href="https://integradoor.com/app/vistas/pdfs/Clausulado%20Hogar%20Allianz.pdf" target="_blank">AQUÍ</a></b>
                  <br>
                  Para conocer más del <b>Seguro de Hogar</b> de <b>Bolívar</b> ingresa <b><a href="https://integradoor.com/app/vistas/pdfs/Clausulado%20Hogar%20Bolivar.pdf" target="_blank">AQUÍ</a></b>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- //FORMULARIO VIAJES -->
      <div class="content" style="margin-top: -5px; margin-bottom: 5px" data-evaluar="si">
        <!-- TITULO FORMULARIO VIAJES -->
        <h4 style="font-family: 'Arial Arabic', Arial; font-weight: bold; margin-bottom: 5px; margin-top: 3px;">Solicita una cotización en el siguiente formulario:</h4>
        <div style="width: 100%; max-width: 640px; margin: 0 auto; margin-top: 30px; margin-bottom: -2px">
          <img src="vistas/img/bannerHogar.png" alt="Banner de hogar" style="width: 100%; height: auto; display: block; margin: 0 auto;">
          <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfpwY-AgJ7VWmmUh7dVcjKoLisjqpN1QFfFVe22Xuv3jmpHxw/viewform?embedded=true" width="640" height="2875" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    function ajustarAlturaTarjetas() {
      var filas = document.querySelectorAll('.row.card-container'); // Modificado el selector

      filas.forEach(function(fila) {
        var tarjetas = fila.querySelectorAll('.card-exequias');

        var alturaMaxima = 0;

        tarjetas.forEach(function(tarjeta) {
          tarjeta.style.height = 'auto'; // Restablecer la altura a 'auto' antes de medir
          var altura = tarjeta.offsetHeight;

          if (altura > alturaMaxima) {
            alturaMaxima = altura;
          }
        });

        tarjetas.forEach(function(tarjeta) {
          tarjeta.style.height = alturaMaxima + 'px';

          if (tarjeta.classList.contains('special-card')) {
            tarjeta.style.display = 'flex';
            tarjeta.style.flexDirection = 'column';
            tarjeta.style.alignItems = 'center';
            tarjeta.style.justifyContent = 'center';
          }

        });
      });

    }

    // Llamada inicial y en redimensionamiento de la ventana
    ajustarAlturaTarjetas();
    window.addEventListener('resize', ajustarAlturaTarjetas);
  });
</script>