let manualGeneral = 0;

let env = "";

let ofertas = [];

const paramsGenerals = new URLSearchParams(window.location.search);

$(document).ready(function () {
  // Obtener la URL completa
  const urlCompleta = window.location.href;

  const partes = urlCompleta.split("/");

  if (partes.includes("dev") || partes.includes("DEV")) {
    env = "dev";
  } else if (partes.includes("QAS") || partes.includes("qas") || partes.includes("Pruebas")) {
    env = "qas";
  } else if (partes.includes("app") || partes.includes("App")) {
    env = "";
  }

  function obtenerFechaActual() {
    const hoy = new Date();
    const año = hoy.getFullYear();
    const mes = String(hoy.getMonth() + 1).padStart(2, "0"); // Los meses van de 0 a 11
    const día = String(hoy.getDate()).padStart(2, "0");

    return `${año}-${mes}-${día}`;
  }

  function obtenerMesActual() {
    const meses = [
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

    const mesActual = new Date().getMonth(); // Retorna un valor entre 0 y 11
    return meses[mesActual]; // Devuelve el nombre del mes
  }

  function loadAnalistas() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "ajax/analistas.ajax.php",
        type: "POST",
        success: function (data) {
          let info = JSON.parse(data);
          $("#txtAnalistaOportunidad").append(info.options);
          // console.log(info);

          info?.analistas.map((analista) => {
            let miusuario = "";

            if (analista.usu_documento == permisos.usu_documento) {
              miusuario = analista;
            }
            // console.log(miusuario);

            // console.log(analista)
            if (analista.usu_documento == permisos.usu_documento) {
              $("#txtAnalistaOportunidad").val(analista.usu_documento);
            }
          });

          resolve(); // Resolviendo la promesa una vez que los datos se han añadido
        },
        error: function (error) {
          reject(error); // En caso de error, rechazar la promesa
        },
      });
    });
  }

  loadAnalistas();

  function abrirDialogo(idCotizacion, oferta) {
    // Configurar el diálogo
    let info = "";
    $("#myModal").dialog({
      autoOpen: false,
      modal: true,
      width: 750,
      dialogClass: "custom-dialog",
      buttons: {
        Cerrar: function () {
          $(this).dialog("close");
        },
        Guardar: function () {
          let mes = obtenerMesActual();

          let oneroso = $(
            "#txtAsesorOnerosoOportunidad option:selected"
          ).text();
          let estado = $("#txtEstadoOportunidad option:selected").text();
          let observaciones = $("#txtObservacionesOportunidades").val();
          let idCotAseguradora = oferta.NumCotizOferta;
          let fechaCreacion = obtenerFechaActual();

          let analista_comercial = $(
            "#txtAnalistaOportunidad option:selected"
          ).text();
          let id_analista_comercial = $("#txtAnalistaOportunidad").val();

          if (analista_comercial == "") {
            return Swal.fire({
              icon: "error",
              title: "Error",
              text: "Debe seleccionar un analista comercial",
            });
          }

          // Se valida previamente que los campos este completos
          // En caso de no estarlos debe dar un error marcando que campo debe ser llenado en el formulario o modal
          var data = new FormData();

          //id_oportunidad
          data.append("idCotizacion", idCotizacion);
          data.append("idCotAseguradora", idCotAseguradora);
          data.append("valor_cotizacion", oferta.Prima);
          data.append("idOferta", oferta.id_oferta);
          data.append("mesOportunidad", mes);
          data.append(
            "asesor_freelance",
            info.usu_nombre + " " + info.usu_apellido
          );
          data.append("id_user_freelance", info.id_usuario);
          data.append(
            "ramo",
            oferta.Manual == 9
              ? "Automoviles"
              : oferta.Manual == 8
              ? "Motos"
              : oferta.Manual == 0 || oferta.Manual == 3
              ? "Pesados"
              : "Autos Pasajeros"
          );
          data.append("placa", oferta.Placa);
          data.append("oneroso", oneroso);
          data.append(
            "aseguradora",
            oferta.Aseguradora == "Previsora"
              ? "Previsora Seguros"
              : oferta.Aseguradora == "HDI (Antes Liberty)"
              ? "HDI Seguros"
              : oferta.Aseguradora
          );
          data.append("analista_comercial", analista_comercial);
          data.append("numcotaseg", oferta.NumCotizOferta);
          data.append("id_analista_comercial", id_analista_comercial);
          //numero de poliza
          data.append("estado", estado);
          data.append("asegurado", info.cli_nombre + " " + info.cli_apellidos);
          data.append("id_asegurado", info.id_cliente);
          data.append(
            "observaciones",
            observaciones == null || observaciones == false ? "" : observaciones
          );
          data.append("fechaCreacion", fechaCreacion);
          // Se ejecuta la peticion por AJAX para llamar a un controlador que se encargara de guardar la data en la base de datos en la tabla "Oportunidades".
          $.ajax({
            url: "ajax/oportunidades.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
              if (respuesta.code === 1) {
                Swal.fire({
                  icon: "success",
                  text: `Oportunidad # ${respuesta.inserted_id} registrada con éxito`,
                  showConfirmButton: true,
                  confirmButtonText: "Ok",
                }).then((result) => {
                  if (result.isConfirmed) {
                    $("#myModal").dialog("close"); // Cerrar el modal
                    window.location.reload(); // Recargar la página (opcional)
                  } else if (result.isDismissed) {
                    $("#myModal").dialog("close"); // Cerrar el modal
                    window.location.reload(); // Recargar la página (opcional)
                  }
                });
              } else {
                Swal.fire({
                  icon: "error",
                  showConfirmButton: true,
                  text: `Error al intentar crear la oportunidad, comuníquese con el administrador del sistema`,
                  confirmButtonText: "Cerrar",
                }).then((result) => {
                  if (result.isConfirmed) {
                    return;
                  } else if (result.isDismissed) {
                    return;
                  }
                });
              }
            },
            error: function () {
              console.log("Error al obtener los datos");
            },
          });
        },
      },
      open: function () {
        $("body").css("overflow", "hidden");
        $(".ui-dialog-buttonpane button:contains('Cerrar')").attr(
          "id",
          "btnCerrar"
        );
        $(".ui-dialog-buttonpane button:contains('Guardar')").attr(
          "id",
          "btnGuardar"
        );

        // Realizar la solicitud AJAX para cargar datos basados en el idCotizacion
        var datos = new FormData();
        datos.append("idCotizacion", idCotizacion);

        $.ajax({
          url: "ajax/cotizaciones.ajax.php",
          method: "POST",
          data: datos,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function (respuesta) {
            $("#noCotizacion").val(respuesta.id_cotizacion);
            $("#txtAsesorOportunidad").val(
              respuesta.usu_nombre + " " + respuesta.usu_apellido
            );
            $("#txtPlacaOportunidad").val(respuesta.cot_placa);
            $("#txtAseguradoraOportunidad").val(
              oferta.Aseguradora == "HDI (Antes Liberty)"
                ? "HDI Seguros"
                : oferta.Aseguradora
            );
            info = respuesta;
          },
          error: function () {
            console.log("Error al obtener los datos");
          },
        });
      },
      close: function () {
        $("body").css("overflow", "auto");
      },
    });

    // Abrir el diálogo
    $("#myModal").dialog("open");
  }

  window.abrirDialogo = abrirDialogo;

  // Asignar eventos a elementos específicos
  // $(document).on("click", ".openModal", function() {
  //   abrirDialogo(); // Llamar la función con el ID específico
  // });

  // Abre el modal cuando se hace clic en el botón

  //permisosPlantilla = permisosPlantilla.replace(/\s+/g, '');
  //let permisos = JSON.parse(permisosPlantilla);
  //console.log(permisos);
  const aseguradorasExitosas = [];
  if (typeof idCotizacion !== "undefined" && idCotizacion !== null) {
    const alertas = new Promise((resolve, reject) => {
      const requestOptions = {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          obtenerAlertas: true,
          cotizacion: idCotizacion,
        }),
      };
      //console.log(aseguradorasExitosas);

      var documentosTable = document.getElementById("tablaResumenCot");

      const params = new URLSearchParams(window.location.search);

      if (params.has("idCotizacion")) {
        fetch("ajax/alerta_aseguradora.ajax.php", requestOptions)
          .then((response) => response.json())
          .then((data) => {
            // console.log(data)
            const cotizacionesSeparadas = {};
            data.forEach((cotizacion) => {
              const aseguradora = cotizacion.aseguradora;

              if (!cotizacionesSeparadas[aseguradora]) {
                cotizacionesSeparadas[aseguradora] = [];
              }

              cotizacionesSeparadas[aseguradora].push(cotizacion);
            });
            // console.log(cotizacionesSeparadas)
            //console.log(data);
            // Ordenar aseguradoras alfabéticamente
            const aseguradorasOrdenadas = Object.keys(
              cotizacionesSeparadas
            ).sort();
            const cotizacionesConVariasOfertas = [];
            const cotizacionesConUnaOferta = [];
            // console.log(aseguradorasOrdenadas)
            aseguradorasOrdenadas.forEach((aseguradora) => {
              const cotizacionesAseguradora =
                cotizacionesSeparadas[aseguradora];
              //console.log(cotizacionesAseguradora)
              if (cotizacionesAseguradora.length > 1) {
                cotizacionesConVariasOfertas.push(...cotizacionesAseguradora);
              } else {
                cotizacionesConUnaOferta.push(...cotizacionesAseguradora);
              }
            });

            //   console.log(cotizacionesConUnaOferta)

            const cotizacionesPorAseguradora = {};

            cotizacionesConVariasOfertas.forEach((cotizacion) => {
              const aseguradora = cotizacion.aseguradora;

              if (!cotizacionesPorAseguradora[aseguradora]) {
                cotizacionesPorAseguradora[aseguradora] = {
                  exitosa1: [],
                  exitosa0: [],
                  sumExitosa1: 0,
                  sumExitosa0: 0,
                };
              }

              if (cotizacion.exitosa === "1") {
                cotizacionesPorAseguradora[aseguradora].exitosa1.push(
                  cotizacion
                );
                cotizacionesPorAseguradora[aseguradora].sumExitosa1 +=
                  cotizacion.ofertas_cotizadas;
              } else if (cotizacion.exitosa === "0") {
                cotizacionesPorAseguradora[aseguradora].exitosa0.push(
                  cotizacion
                );
                cotizacionesPorAseguradora[aseguradora].sumExitosa0 +=
                  cotizacion.ofertas_cotizadas;
              }
            });

            //console.log(cotizacionesConVariasOfertas);
            //console.log(cotizacionesPorAseguradora);

            let cotizacionesExitosa1 = [];
            let cotizacionesExitosa0 = [];

            for (const aseguradora in cotizacionesPorAseguradora) {
              const exitosa1Array =
                cotizacionesPorAseguradora[aseguradora].exitosa1;

              if (exitosa1Array.length > 0) {
                const sumaOfertasExitosa1 = exitosa1Array.reduce(
                  (sum, usuario) => sum + usuario.ofertas_cotizadas,
                  0
                );

                cotizacionesExitosa1.push(
                  ...exitosa1Array.map((usuario) => ({
                    aseguradora: usuario.aseguradora,
                    exitosa: usuario.exitosa,
                    ofertas_cotizadas: sumaOfertasExitosa1,
                    mensaje: "",
                  }))
                );
              } else {
                // Cambié la asignación a push para agregar un nuevo elemento al array
                cotizacionesExitosa0.push({
                  aseguradora,
                  exitosa: 0,
                  ofertas_cotizadas: 0,
                  mensaje:
                    cotizacionesPorAseguradora[aseguradora].exitosa0[0].mensaje,
                });
              }
            }

            let aseguradorasData = {};
            for (const aseguradora in cotizacionesPorAseguradora) {
              const exitosa1Array =
                cotizacionesPorAseguradora[aseguradora].exitosa1;
              // desactive
              // console.log('exitosa1Array:', exitosa1Array);

              if (exitosa1Array.length > 0) {
                const sumaOfertasExitosa1 = exitosa1Array.reduce(
                  (sum, usuario) => {
                    // Convertir las cadenas a números usando parseInt
                    const ofertasCotizadas = parseInt(
                      usuario.ofertas_cotizadas,
                      10
                    );

                    return sum + ofertasCotizadas;
                  },
                  0
                );
                // desactive
                //console.log('sumaOfertasExitosa1:', sumaOfertasExitosa1);

                if (aseguradorasData[aseguradora]) {
                  // Si ya existe una entrada para la aseguradora, actualiza la información
                  aseguradorasData[aseguradora].ofertas_cotizadas +=
                    sumaOfertasExitosa1;
                } else {
                  // Si no existe una entrada, crea una nueva
                  aseguradorasData[aseguradora] = {
                    aseguradora,
                    exitosa: "1",
                    ofertas_cotizadas: sumaOfertasExitosa1,
                    mensaje: "",
                  };
                }
              } else {
                // Crear array con características específicas si exitosa1 está vacío
                aseguradorasData[aseguradora] = {
                  aseguradora,
                  exitosa: 0,
                  ofertas_cotizadas: 0,
                  mensaje:
                    cotizacionesPorAseguradora[aseguradora].exitosa0[0].mensaje,
                };
              }
            }

            // Convertir el objeto en un array
            const resultadoFinal = Object.values(aseguradorasData);

            // Combina los dos arrays
            const combinedArray = [
              ...resultadoFinal,
              ...cotizacionesConUnaOferta,
            ];

            // Ordena el array resultante por la propiedad "aseguradora"
            combinedArray.sort((a, b) =>
              a.aseguradora.localeCompare(b.aseguradora)
            );

            // COTIZACIONES EXITOSAS VARIAS PETICIONES //

            var tableBody = documentosTable.getElementsByTagName("tbody")[0];
            tableBody.innerHTML = "";

            // COTIZACIONES EXITOSAS VARIAS PETICIONES FINAL //

            // UNA OFERTA Iterar sobre los datos y agregar filas a la tabla
            combinedArray.forEach((usuario) => {
              var newRow = tableBody.insertRow();

              var aseguradoraCell = newRow.insertCell();
              aseguradoraCell.textContent =
                usuario.aseguradora == "HDI (Antes Liberty)"
                  ? "HDI Seguros"
                  : usuario.aseguradora;

              var cotizoCell = newRow.insertCell();
              // Cambiar el contenido de la celda en función de si cotizó o no
              cotizoCell.innerHTML =
                usuario.exitosa === "1"
                  ? '<i class="fa fa-check" aria-hidden="true" style="color: green; margin-right: 5px;"></i>'
                  : '<i class="fa fa-times" aria-hidden="true" style="color: red; margin-right: 10px;"></i>';
              cotizoCell.classList.add("text-center"); // Agrega la clase text-center a cotizoCell

              var productosCell = newRow.insertCell();
              if (isNaN(usuario.ofertas_cotizadas)) {
                productosCell.textContent = "";
              } else {
                productosCell.textContent = usuario.ofertas_cotizadas;
              }
              productosCell.classList.add("text-center");
              // console.log(productosCell);
              var observacionesCell = newRow.insertCell();
              observacionesCell.innerHTML = usuario.mensaje;
            });
          })
          .catch((error) => {
            console.error(
              "Error al obtener la información de la tabla:",
              error
            );
          });
      }
    });
  } else {
  }

  $("#valorTotal").numeric();
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
  parseNumbersToString("#valorTotal");

  // Limpia los contenedores de las Cards y del Boton PDF y Recotiza

  $("#btnRecotizar").click(function () {
    document.getElementById("formularioCotizacionManual").style.display =
      "none";

    let cardCotizacion = document.querySelector("#cardCotizacion");

    cardCotizacion.innerHTML = "";

    cotizarOfertas();
  });

  // Visualiza el formulario para agregar cotizaciones manualmente

  $("#btnMostrarFormCotManual").click(function () {
    if (permisos.Agregarcotizacionmanual != "x") {
      Swal.fire({
        icon: "error",

        title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
      document.getElementById("formularioCotizacionManual").style.display =
        "block";

      //document.querySelector(".btnAgregar").innerHTML = '<button class="btn btn-primary btn-block" id="btnAgregarCotizacion">Agregar Cotización</button>';

      $("#btnAgregarCotizacion").click(function () {
        agregarCotizacion();
      });

      vaciarCamposOfertaManual();

      menosVeh();

      masAgr();
    }
  });

  $("#clase").change(function () {
    $("#Marca").html("");
    $("#edad").html("");
    $("#linea").html("");
    $("#referenciados").html("");
    $("#referenciatres").html("");
  });

  // Funcion para seleccionar el Producto Manualmente

  $("#aseguradora").change(function () {
    selecProductoManual();
  });

  // Función para seleccionar RC Manualmente

  $("#producto").change(function () {
    selecRCManual();
  });

  // Función para cargar las Coberturas Manualmente

  $("#valorRC").change(function () {
    selecCoberturasManual();
  });

  // Ejectura la funcion Agregar Cotizacion Manualmente

  $("#btnAgregarCotizacion").click(function () {
    agregarCotizacion();
  });

  // Ejectura la funcion Agregar Cotizacion Manualmente

  $("#btnAgregarCotizacionManual").click(function () {
    agregarCotizacionManual2();
  });

  $("#btnParrillaPDF").click(function () {
    var todosOn = $(".classSelecOferta:checked").length;

    var idCotizacionPDF = idCotizacion;

    var checkboxAsesorEditar = $("#checkboxAsesorEditar");

    var valorTxtFasecolda = $("#txtFasecolda").val(); // Obtener el valor del input con el id "txtFasecolda"

    function codigoClase(numero) {
      // Convierte el número a una cadena para acceder a los dígitos individualmente
      var numeroComoCadena = numero.toString();

      // Asegúrate de que la cadena tenga al menos 5 dígitos
      if (numeroComoCadena.length >= 5) {
        var cuartoDigito = numeroComoCadena.charAt(3);
        var quintoDigito = numeroComoCadena.charAt(4);

        // Verifica si el cuarto dígito no es cero
        if (cuartoDigito !== "0") {
          // Concatena el cuarto y quinto dígitos
          return cuartoDigito + quintoDigito;
        } else {
          // Devuelve solo el cuarto dígito
          return quintoDigito;
        }
      } else {
        // No hay suficientes dígitos, devuelve el número original
        return numero;
      }
    }

    var claseFasecolda = codigoClase(valorTxtFasecolda);

    if (permisos.Generarpdfdecotizacion != "x") {
      Swal.fire({
        icon: "error",

        title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
      const filtradas = ofertas.some((element) => element.seleccionar == "Si");

      if (!todosOn && !filtradas) {
        swal.fire({
          icon: "error",

          title: "¡Debes seleccionar minimo una oferta!",
        });
      } else {
        if (
          claseFasecolda == 4 ||
          claseFasecolda == 10 ||
          claseFasecolda == 11 ||
          claseFasecolda == 12 ||
          claseFasecolda == 13 ||
          claseFasecolda == 14 ||
          claseFasecolda == 22 ||
          claseFasecolda == 23 ||
          claseFasecolda == 25 ||
          claseFasecolda == 26
        ) {
          let url = `extensiones/tcpdf/pdf/comparadorPesados.php?cotizacion=${idCotizacionPDF}`;

          if (checkboxAsesorEditar.is(":checked")) {
            url += "&generar_pdf=1";
          }

          window.open(url, "_blank");
        } else if (claseFasecolda == 17 || claseFasecolda == 18) {
          let url = `extensiones/tcpdf/pdf/comparadorMotos.php?cotizacion=${idCotizacionPDF}`;

          if (checkboxAsesorEditar.is(":checked")) {
            url += "&generar_pdf=1";
          }

          window.open(url, "_blank");
        } else {
          let url = `extensiones/tcpdf/pdf/comparador${
            manualGeneral == 4 ? "Pasajeros.php" : ".php"
          }?cotizacion=${idCotizacionPDF}`;

          if (checkboxAsesorEditar.is(":checked")) {
            url += "&generar_pdf=1";
          }

          window.open(url, "_blank");
        }
      }
    }
  });

  // Imprimir Parrilla de Cotizaciones

  $("#btnParrillaPDF2").click(function () {
    var todosOn = $(".classSelecOferta:checked").length;

    var idCotizacionPDF = idCotizacion;

    if (permisos.Generarpdfdecotizacion != "x") {
      Swal.fire({
        icon: "error",

        title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
      const filtradas = ofertas.some((element) => element.seleccionar == "Si");

      if (!todosOn && !filtradas) {
        swal.fire({
          icon: "error",

          title: "¡Debes seleccionar minimo una oferta!",
        });
      } else {
        // window.open("comparador.php?cotizacion="+idCotizacionPDF, "_blank");

        window.open(
          "extensiones/tcpdf/pdf/comparadorPesados.php?cotizacion=" +
            idCotizacionPDF,

          "_blank"
        );
      }
    }
  });

  /*=============================================

  BOTON EDITAR COTIZACIÓN

  =============================================*/

  $(".tablas-cotizaciones").on("click", ".btnEditarCotizacion", function () {
    var idCotizacion = $(this).attr("idCotizacion");

    window.location =
      "index.php?ruta=editar-cotizacion&idCotizacion=" + idCotizacion;

    // $.redirect("editar-cotizacion", { idCotizacion: idCotizacion }, "POST");
  });

  /*=============================================

  ELIMINAR COTIZACIÓN

  =============================================*/

  $(".tablas-cotizaciones").on("click", ".btnEliminarCotizacion", function () {
    var idCotizacion = $(this).attr("idCotizacion");

    Swal.fire({
      title: "¿Está seguro de borrar la cotización?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Si, eliminar",
      customClass: {
        popup: "mi-clase-warning", // Clase personalizada para esta caja
      },
    }).then(function (result) {
      if (result.isConfirmed) {
        Swal.fire({
          title: "Cotización Eliminada Correctamente",
          type: "success",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Cerrar",
          customClass: {
            popup: "mi-clase-success", // Clase personalizada para esta caja
          },
        }).then(function () {
          window.location =
            "index.php?ruta=inicio&idCotizacion=" + idCotizacion;
        });
      }
    });
  });

  /*===================================================

  CONFIGURACION DE LA TABLA DATATABLE PARA COTIZACIONES

  ===================================================*/
  $(".tablas-cotizaciones").DataTable({
    layout: {
      topStart: "buttons",
      topCenter: {
        search: {
          placeholder: "Buscar...",
        },
      },
      topEnd: {
        pageLength: {
          menu: [10, 25, 50, 100],
        },
      },
      bottomEnd: {
        paging: {
          numbers: 3,
        },
      },
    },
    buttons: [
      {
        extend: "excelHtml5",
        className: "btn-excel",
        text: '<img src="vistas/img/excelIco.png" />', // Agrega un texto descriptivo
        titleAttr: "Exportar a Excel", // Agrega un tooltip
      },
    ],
    responsive: true,
    order: [
      [0, "desc"],
      [1, "desc"],
    ],
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sInfoPostFix: "",
      sSearch: "Buscar:",
      sUrl: "",
      sInfoThousands: ",",
      sLoadingRecords: "Cargando...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
      },
      oAria: {
        sSortAscending:
          ": Activar para ordenar la columna de manera ascendente",
        sSortDescending:
          ": Activar para ordenar la columna de manera descendente",
      },
    },
  });

  $("#daterange-btnCotizaciones").daterangepicker(
    {
      ranges: {
        Hoy: [moment(), moment()],
        Ayer: [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Últimos 7 días": [moment().subtract(7, "days"), moment()],
        "Últimos 30 días": [moment().subtract(30, "days"), moment()],
        "Este mes": [moment().startOf("month"), moment()],
        "Último mes": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
        "Últimos 3 meses": [
          moment().subtract(3, "month").startOf("month"),
          moment(),
        ],
      },
    },
    function (startDate, endDate) {
      $("#daterange-btnCotizaciones span").html(
        startDate.format("MMMM D, YYYY") +
          " - " +
          endDate.format("MMMM D, YYYY")
      );
      var fechaInicialCotizaciones = startDate.format("YYYY-MM-DD");
      var fechaFinalCotizaciones = endDate.format("YYYY-MM-DD");
      var capturarRango = $("#daterange-btnCotizaciones span").html();
      localStorage.setItem("capturarRango", capturarRango);
      var selectedOption = $("#daterange-btnCotizaciones").data(
        "daterangepicker"
      ).chosenLabel;
      localStorage.setItem("Selected", selectedOption);
      window.location =
        "index.php?ruta=adminCoti&fechaInicialCotizaciones=" +
        fechaInicialCotizaciones +
        "&fechaFinalCotizaciones=" +
        fechaFinalCotizaciones;
    }
  );

  // Switch para determinar y configurar la seleccion en el menu desplegable de los rangos

  let selected = localStorage.getItem("Selected");
  switch (selected) {
    case "Hoy":
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setStartDate(moment());
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    case "Ayer":
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setStartDate(moment().subtract(1, "days"));
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setEndDate(moment().subtract(1, "days"));
      break;
    case "Últimos 7 días":
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setStartDate(moment().subtract(7, "days"));
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    case "Últimos 30 días":
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setStartDate(moment().subtract(30, "days"));
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    case "Este mes":
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setStartDate(moment().startOf("month"));
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setEndDate(moment().endOf("month"));
      break;
    case "Último mes":
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setStartDate(moment().subtract(1, "month").startOf("month"));
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setEndDate(moment().subtract(1, "month").endOf("month"));
      break;
    case "Últimos 3 meses":
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setStartDate(moment().subtract(3, "month").startOf("month"));
      $("#daterange-btnCotizaciones")
        .data("daterangepicker")
        .setEndDate(moment());
      break;
    default:
      break;
  }

  /*=============================================

  CANCELAR RANGO DE FECHAS

  =============================================*/

  $("#daterange-btnCotizaciones").on(
    "cancel.daterangepicker",

    function (ev, picker) {
      localStorage.removeItem("capturarRango");

      localStorage.clear();

      window.location = "adminCoti";
    }
  );

  /*=============================================

  CAPTURAR HOY

  =============================================*/

  $(".daterangepicker.opensleft").on("click", ".liCotizaciones", function () {
    var textoHoy = $(this).attr("data-range-key");

    if (textoHoy == "Hoy") {
      var d = new Date();

      var dia = d.getDate();

      var mes = d.getMonth() + 1;

      var año = d.getFullYear();

      dia = ("0" + dia).slice(-2);

      mes = ("0" + mes).slice(-2);

      var fechaInicialCotizaciones = año + "-" + mes + "-" + dia;

      var fechaFinalCotizaciones = año + "-" + mes + "-" + dia;

      var fechaInicialCotizaciones1 =
        fechaInicialCotizaciones.format("YYYY-MM-DD");

      var fechaFinalCotizaciones1 = fechaFinalCotizaciones.format("YYYY-MM-DD");

      localStorage.setItem("capturarRango", "Hoy");

      window.location =
        "index.php?ruta=adminCoti&" +
        "fechaInicialCotizaciones=" +
        fechaInicialCotizaciones1 +
        "&fechaFinalCotizaciones=" +
        fechaFinalCotizaciones1;
    }
  });

  /*================================================
 Funcion para resetear el formulario de aseguradora
================================================*/

  function resetFormAseg() {
    $(".form-resumAseg")
      .find("input, select")
      .each(function () {
        if (this.id == "tipoDocumentoID") {
          console.log("entre aca");
        } else if (this.id == "placaVeh" && $(this).val() != "") {
          return;
        } else {
          if (
            this.id == "mesnacimiento" ||
            this.id == "dianacimiento" ||
            this.id == "anionacimiento" ||
            this.id == "dianacimientoRepresentante" ||
            this.id == "mesnacimientoRepresentante" ||
            this.id == "anionacimientoRepresentante"
          ) {
            $(this).val("").trigger("change"); // Limpia los campos de texto y los select
          } else {
            $(this).val(""); // Limpia los campos de texto y los select
          }
        }
      });
  }

  /*================================================
 Eventos en cambio de documento
================================================*/

  let params = urlPage.searchParams.getAll("idCotizacion");
  if (params.length <= 0) {
    $("#tipoDocumentoID").change(function () {
      // resetFormAseg(); se deshabilita mientras se mide la funcionalidad de los campos
      if ($(this).val() == "2") {
        $("#numDocumentoID").attr("maxlength", "9");
      } else {
        $("#numDocumentoID").attr("maxlength", "10");
      }
    });
  }
});

/*================================================

// CAPTURA LA URL DE LA PAGINA EDITAR COTIZACIONES

================================================*/

var urlPage = new URL(window.location.href); // Instancia la URL Actual

var options = urlPage.searchParams.getAll("idCotizacion"); //Buscar todos los parametros

if (options.length > 0) {
  editarCotizacion(options[0]);
}

// metodo comprobador

// function sayHi() {
//   console.log("hi!!")
// }

/*=============================================

EDITAR COTIZACION

=============================================*/

var cards = "";

var numId = 1;

let excepControl = false;

async function renderCards(response) {
  let offerts = await offertsFinesaRender();
  let filter = "Todas"; // Valor por defecto
  if (response.length > 0 && response[0].Categoria) {
    if (JSON.parse(response[0].Categoria).length > 0) {
      $(".container-filters").css("display", "block");
      filter =
        JSON.parse(response[0].Categoria).length == 0
          ? "Todas"
          : JSON.parse(response[0].Categoria);
    }
  }

  if (response.length > 11) {
    filter = "Todas";
  }
  let globalResponse = "";
  $("#loaderFilters").html(
    `<div style="display:flex; align-items: center; justify-content: center; margin-bottom: 90px; margin-top: 90px; gap: 10px"><img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong style="font-size: 19px"> Cargando filtro ${filter}...</strong></div>`
  );
  $("#cardCotizacion").html("");
  // Obtener los permisos de cotización START

  $.ajax({
    url: "ajax/cotizaciones.ajax.php",
    type: "POST",
    data: { idCotizacion: idCotizacion },
    success: function (response) {
      globalResponse = response;
    },
    error: function (error) {
      console.error("Error al obtener los permisos de cotización:", error);
    },
  });

  if (!response[0].Categoria) {
    $(".container-filters").css("display", "none");
  }
  // Obtener los permisos de cotización END
  cardCotizacion = "";
  cards = response;

  setTimeout(() => {
    response.forEach(function (oferta, i) {
      function nombreAseguradora(data) {
        let resultado = "";
        switch (data) {
          case "Seguros del Estado":
            resultado = "Estado";
            break;
          case "Seguros Bolivar":
            resultado = "Bolivar";
            break;
          case "Axa Colpatria":
            resultado = "AXA";
            break;
          case "HDI Seguros":
            resultado = "HDI Seguros";
            break;
          case "SBS Seguros":
            resultado = "SBS";
            break;
          case "Allianz Seguros":
            resultado = "Allianz";
            break;
          case "Equidad Seguros":
          case "Equidad":
            resultado = "Equidad";
            break;
          case "Seguros Mapfre":
          case "Mapfre":
            resultado = "Mapfre";
            break;
          case "HDI (Antes Liberty)":
            resultado = "HDI Seguros";
            break;
          case "Aseguradora Solidaria":
          case "Solidaria":
            resultado = "Solidaria";
            break;
          case "Seguros Sura":
            resultado = "SURA";
            break;
          case "Zurich Seguros":
          case "Zurich":
            resultado = "Zurich";
            break;
          case "Previsora Seguros":
            resultado = "Previsora";
            break;
          default:
            resultado = data;
            break;
        }
        return resultado;
      }

      // Permisos Credenciales aseguradoras

      var permisosCotizacion = globalResponse["permisosCotizacion"];
      // console.log(permisosCotizacion)

      if (permisosCotizacion === null || permisosCotizacion === undefined) {
        var permisosCotizacion =
          '{"Qualitas":{"A":"1","C":"1"},"Allianz":{"A":"1","C":"1"},"AXA":{"A":"1","C":"1"},"Bolivar":{"A":"1","C":"1"},"Equidad":{"A":"1","C":"1"},"Estado":{"A":"1","C":"1"},"HDI (Antes Liberty)":{"A":"1","C":"1"},"HDI Seguros":{"A":"1","C":"1"},"Mapfre":{"A":"1","C":"1"},"Previsora":{"A":"1","C":"1"},"SBS":{"A":"1","C":"1"},"Solidaria":{"A":"1","C":"1"},"Zurich":{"A":"1","C":"1"}}';
      }

      // Permisos Credenciales aseguradoras

      function obtenerValorC(aseguradora) {
        const aseguradorasPermisos = JSON.parse(permisosCotizacion);
        if (aseguradorasPermisos[aseguradora]) {
          return aseguradorasPermisos[aseguradora]["C"];
        } else {
          return "Aseguradora no encontrada";
        }
      }

      let viable = true;

      let count = 0;

      offerts.forEach((element) => {
        if (element.cuota_1 == null) {
          count++;
        }
      });

      viable = count == offerts.length ? false : true;

      let aseguradora = oferta.Aseguradora;
      let aseguradoraName = nombreAseguradora(aseguradora);
      let aseguradoraPermisos = obtenerValorC(aseguradoraName);

      var primaFormat = formatNumber(oferta.Prima);
      var id_intermediario = document.getElementById("idIntermediario").value;

      function isNumeric(value) {
        // Comprueba si es un número válido o una cadena numérica válida
        return !isNaN(parseFloat(value)) && isFinite(value);
      }
      const aseguradorasViajes = [
        "Mundial",
        "HDI Seguros",
        "HDI (Antes Liberty)",
        "Axa Colpatria",
        "Previsora",
        "Solidaria",
        "Equidad",
        "AXA Colpatria",
        "AXA",
      ];

      const planesViajes = [
        "Convenio Pesados",
        "Convenio Remolques",
        "Convenio Linea F Chevrolet",
        "Pesados Full",
        "Pesados Full1",
        "Pesados Integral1",
        "Pesados Integral",
        "Remolques",
        "Remolques1",
        "Tanques",
        "Ded. Unico Remolques",
        "Ded. Unico Tanques",
        "Carga (deducible tradicional)",
        "Ded. Unico Pesados",
        "Ded. Unico Volquetas",
        "Volquetas",
        "Pesados - lucro",
        "Pesados + lucro",
        "Liv Pub+Lucro",
        "Microbuses",
        "Camionetas Repa",
        "Pesados con RCE en exceso",
        "Pesados",
        "Todo riesgo Trailer",
        "Taxis Full",
        "Taxis Amarillos Elite",
        "Taxis Amarillos Premium",
        "Taxis Amarillos Plus",
        "Buses Premium",
        "Buses Plus",
        "Buses Elite",
        "Plan Básico",
        "Plan Normal",
        "Plan Full",
        "Buses",
      ];

      var valorRC = isNumeric(oferta.ValorRC);

      if (valorRC) {
        var valorRCFormat = formatNumber(oferta.ValorRC);
      } else {
        var valorRCFormat = oferta.ValorRC;
      }
      // Desactive
      //FUNCION QUE ACOMODA RCE EN PARRILLA CUANDO LLEGA MUNDIAL
      if (
        oferta.Aseguradora == "Mundial" &&
        oferta.Producto == "Pesados con RCE en exceso"
      ) {
        // Eliminar los puntos y convertir a número
        var RC = oferta.ValorRC;
        RC = parseFloat(RC.replace(/\./g, ""));

        // Sumar 1.500.000.000
        RC += 1500000000;

        // Volver a formatear con puntos
        var valorRCFormat = RC.toLocaleString();
      }
      if (
        (oferta.Aseguradora == "HDI Seguros" &&
          oferta.Producto == "Convenio Pesados") ||
        (oferta.Aseguradora == "HDI Seguros" &&
          oferta.Producto == "Linea F Chevrolet")
      ) {
        // Eliminar los puntos y convertir a número
        var RC = oferta.ValorRC;
        RC = parseFloat(RC.replace(/\./g, ""));

        // Sumar 1.000.000.000
        RC += 1000000000;

        // Volver a formatear con puntos
        var valorRCFormat = RC.toLocaleString();
      }
      if (
        oferta.Aseguradora == "SBS Seguros" &&
        oferta.Producto == "RCE Daños"
      ) {
        oferta.PerdidaTotal = "Cubrimiento al 100% (Daños)";

        oferta.PerdidaParcial = "Deducible 10% - 1 SMMLV (Daños)";
      } else if (
        oferta.Aseguradora == "SBS Seguros" &&
        oferta.Producto == "RCE Hurto"
      ) {
        oferta.PerdidaTotal = "Cubrimiento al 100% (Hurto)";

        oferta.PerdidaParcial = "Deducible 10% - 1 SMMLV (Hurto)";
      }

      if (oferta.seleccionar == "Si") {
        var selecChecked = "checked";
      }

      if (oferta.recomendar == "Si") {
        var recomChecked = "checked";
      }

      cardCotizacion += `
  
                  <div class='col-lg-12'>
  
                    <div class='card-ofertas'>
  
                      <div class='row card-body'>
  
                        <div class="col-xs-12 col-sm-6 col-md-2 oferta-logo">
  
                        <center> 
                          <img src='${oferta.logo}' style="${
        oferta.Aseguradora == "Mundial"
          ? "margin-top: 65px;"
          : oferta.Aseguradora == "Equidad" && oferta.Manual == "4"
          ? "padding-top: 15px;"
          : null
      }">
                        </center>
                        <div class='col-12' style='margin-top:2%;'>
                            ${
                              (oferta.Aseguradora === "Axa Colpatria" ||
                                oferta.Aseguradora === "HDI (Antes Liberty)" ||
                                oferta.Aseguradora === "Equidad" ||
                                oferta.Aseguradora === "Mapfre") &&
                              id_intermediario == "79"
                                ? `<center>
                                <!-- Código para el caso específico de Axa Colpatria, Liberty, Equidad o Mapfre -->
                                <!-- Agrega aquí el contenido específico para estas aseguradoras -->
                              </center>`
                                : oferta.Aseguradora !== "Mundial" &&
                                  permisos.Vernumerodecotizacionencadaaseguradora ==
                                    "x" &&
                                  aseguradoraPermisos == "1"
                                ? `<center>
                                ${
                                  // agregar aqui un console.log para verificar si la oferta tiene un número de cotización
                                  oferta.NumCotizOferta != 0
                                    ? "<label class='entidad'>N° Cot: <span style='color:black'>" +
                                      oferta.NumCotizOferta +
                                      "</span></label>"
                                    : ""
                                }
                              </center>`
                                : ""
                            }
                            
                              <div style="display: flex; justify-content: center; margin-top: 10px">
                              ${
                                permisos.permisos_oportunidades == "x"
                                  ? oferta.id_oportunidad == null
                                    ? `<p class="openModal" onclick='abrirDialogo(${idCotizacion}, ${JSON.stringify(
                                        oferta
                                      ).replace(
                                        /'/g,
                                        "\\'"
                                      )})' style="text-decoration: underline; text-underline-offset: 3px; cursor: pointer">Crear oportunidad</p>`
                                    : `<p style="text-decoration: underline; text-underline-offset: 3px; color: blue;">Oportunidad Creada ID # ${
                                        oferta.id_oportunidad ??
                                        "ID No Encontrado"
                                      }</p>`
                                  : ""
                              }                      
                            </div>                 
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-2 oferta-headerEdit" style='${
                          oferta.Aseguradora == "HDI (Antes Liberty)" &&
                          (oferta.oferta_finesa == "" ||
                            oferta.oferta_finesa == null)
                            ? "padding-top: 10px;"
                            : oferta.Aseguradora == "Mundial" &&
                              oferta.oferta_finesa &&
                              oferta.oferta_finesa != null
                            ? "padding-top: 14px;"
                            : oferta.Aseguradora == "HDI (Antes Liberty)" &&
                              oferta.oferta_finesa != null
                            ? "padding-top: 14px"
                            : "padding-top: 14px"
                        }'>
                        <h5 class='entidad' style='font-size: 15px'><b>${
                          oferta.Aseguradora == "HDI (Antes Liberty)"
                            ? "HDI Seguros"
                            : oferta.Aseguradora
                        } - ${
        oferta.Producto == "Pesados con RCE en exceso"
          ? "Pesados RCE + Exceso"
          : oferta.Producto == "PREVILIVIANOS INDIVIDUAL - "
          ? "PREVILIVIANOS INDIVIDUAL"
          : oferta.Producto == "AU DEDUCIBLE UNICO LIVIANOS - "
          ? "AU DEDUCIBLE UNICO LIVIANOS"
          : oferta.Producto == "LIVIANOS MIA - "
          ? "LIVIANOS MIA"
          : oferta.Producto == "Pesados Full1"
          ? "Pesados Full"
          : oferta.Producto == "Pesados Integral1"
          ? "Pesados Integral"
          : oferta.Producto == "134"
          ? "Integral Motos 0 a 6 MM"
          : oferta.Producto == "135"
          ? "Integral Motos 6 a 10 MM"
          : oferta.Producto == "136"
          ? "Integral Motos 10 a 20 MM"
          : oferta.Producto == "137"
          ? "Integral Motos 20 a 30 MM"
          : oferta.Producto == "138"
          ? "Integral Motos 30 a 90 MM"
          : oferta.Producto == "139"
          ? "Basico + PT Motos 0 a 6 MM"
          : oferta.Producto == "140"
          ? "Basico + PT Motos 10 a 20 MM"
          : oferta.Producto == "141"
          ? "Basico + PT Motos 20 a 30 MM"
          : oferta.Producto == "142"
          ? "Basico + PT Motos 30 a 90 MM"
          : oferta.Producto == "31"
          ? "Premium Motos"
          : oferta.Producto == "30"
          ? "Total CAR"
          : oferta.Producto == "99"
          ? "Pesados Full"
          : oferta.Producto == "100"
          ? "Pesados Integral"
          : oferta.Producto == "103"
          ? "Pesados Full"
          : oferta.Producto == "104"
          ? "Pesados Integral"
          : oferta.Producto == "111"
          ? "Remolques"
          : oferta.Producto == "112"
          ? "Remolques"
          : oferta.Producto == "145"
          ? "Premium Motos 0 a 6 MM"
          : oferta.Producto == "112"
          ? "Premium Motos 6 a 10 MM"
          : oferta.Producto == "112"
          ? "Premium Motos 10 a 20 MM"
          : oferta.Producto == "112"
          ? "Premium Motos 20 a 30 MM"
          : oferta.Producto == "112"
          ? "Premium Motos 30 a 90 MM"
          : oferta.Producto
      }</b></h5>
                        <h5 class='precio' style='${
                          oferta.Aseguradora == "HDI (Antes Liberty)"
                            ? "padding-bottom: 0px; !important"
                            : ""
                        }'>Precio $ ${primaFormat}</h5>
                        <p class='title-precio'>(IVA incluido)</p>
                        
                        ${
                          oferta.oferta_finesa && oferta.oferta_finesa != null
                            ? `
                            <div id=${
                              oferta.oferta_finesa
                            } style="display: block; color: #88d600;">
                              ${offerts
                                .map((element) => {
                                  if (
                                    element.identityElement ==
                                    oferta.oferta_finesa
                                  ) {
                                    if (
                                      ($("#CodigoClase").val() == 17 ||
                                        $("#CodigoClase").val() == 18 ||
                                        $("#CodigoClase").val() == 19) &&
                                      oferta.Prima < 800000 &&
                                      !(
                                        oferta.Aseguradora ==
                                          "HDI (Antes Liberty)" ||
                                        oferta.Aseguradora == "Bolivar" ||
                                        oferta.Aseguradora == "Seguros Bolivar"
                                      )
                                    ) {
                                      return `Financiación Finesa:<br />No aplica para financiación`;
                                    } else if (!viable) {
                                      if (
                                        element.identityElement.includes(
                                          "HDI (Antes Liberty)"
                                        ) ||
                                        element.identityElement.includes(
                                          "Bolivar"
                                        ) ||
                                        element.identityElement.includes(
                                          "Seguros Bolivar"
                                        )
                                      ) {
                                        return `Financiación Aseguradora:<br /> Consulte analista`;
                                      } else {
                                        return `Financiación Finesa:<br />Asegurado no viable para financiación`;
                                      }
                                    } else if (
                                      element.identityElement.includes(
                                        "HDI (Antes Liberty)"
                                      ) ||
                                      element.identityElement.includes(
                                        "Bolivar"
                                      ) ||
                                      element.identityElement.includes(
                                        "Seguros Bolivar"
                                      )
                                    ) {
                                      return `Financiación Aseguradora:<br /> Consulte analista`;
                                    } else if (element.cuota_1 == null) {
                                      return `Financiación Finesa:<br />No aplica para financiación`;
                                    } else {
                                      return `Financiación Finesa:<br />$${Number(
                                        element.cuota_1
                                      ).toLocaleString("de-DE")}
                                    (${element.cuotas} Cuotas pólizas sin oneroso)`;
                                    }
                                  }
                                  return "";
                                })
                                .join("")}
                            </div>
                          `
                            : ""
                        }
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-4">
  
                          <ul class="list-group">
  
                            <li class="list-group-item">
  
                              <span class="badge">* ${
                                valorRCFormat !== "No cubre" &&
                                !valorRCFormat.includes("/")
                                  ? "$"
                                  : ""
                              }${valorRCFormat}</span>
  
                              Responsabilidad Civil (RCE)
  
                            </li>
  
                            <li class="list-group-item">
  
                              <span class="badge">* ${
                                oferta.PerdidaTotal
                              }</span>
  
                              Pérdida Total Daños y Hurto
  
                            </li>
  
                            <li class="list-group-item">
  
                              <span class="badge">* ${
                                oferta.PerdidaParcial
                              }</span>
  
                              Pérdida Parcial Daños y Hurto
  
                            </li>
  
                            <li class="list-group-item">
  
                              <span class="badge">* ${
                                oferta.ConductorElegido
                              }</span>
  
                              Conductor elegido
  
                            </li>
  
                            <li class="list-group-item">
  
                              <span class="badge">* ${oferta.Grua}</span>
                              ${
                                aseguradorasViajes.includes(aseguradora) &&
                                planesViajes.includes(oferta.Producto)
                                  ? "Asistencia en Viajes"
                                  : "Servicio de Grúa"
                              } 
  
                            </li>
  
                          </ul>
  
                        </div>
  
                        <div class="col-xs-12 col-sm-6 col-md-2">
  
                        <div class="selec-oferta">
  
                          <label for="seleccionar">SELECCIONAR</label>&nbsp;&nbsp;
  
                          <input type="checkbox" class="classSelecOferta" name="selecOferta" id="selec${
                            oferta.NumCotizOferta
                          }${numId}\" onclick='seleccionarOferta(\"${
        oferta.Aseguradora
      }\", \"${oferta.Prima}\", \"${oferta.Producto}\", \"${
        oferta.NumCotizOferta
      }\", \"${oferta.oferta_finesa}\", this);' ${selecChecked}/>
  
                        </div>
  
                        
  
                        </div>`;

      if (oferta.Manual == "1") {
        cardCotizacion += `
  
                <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                  <button type="button" class="btn btn-danger eliminar-manual" id="eliminar-${oferta.id_oferta}">
  
                    <div>ELIMINAR &nbsp;&nbsp;<span class="fa fa-trash"></span></div>
  
                  </button>
  
                </div>`;
      }

      if (oferta.Manual == "1" && oferta.UrlPdf != "") {
        cardCotizacion += `
  
                <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                  <a type="button" class="btn btn-info" href="${oferta.UrlPdf}">
  
                    <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                  </a>
  
                </div>`;
      }

      if (
        (oferta.Manual == "0" ||
          oferta.Manual == "8" ||
          oferta.Manual == "9" ||
          oferta.Manual == "4") &&
        (oferta.Aseguradora == "Seguros Bolivar" ||
          oferta.Aseguradora == "Axa Colpatria") &&
        aseguradoraPermisos == "1"
      ) {
        cardCotizacion += `
  
                        <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                        <button type="button" class="btn btn-info" id="btnAsegPDF${oferta.NumCotizOferta}${numId}\" onclick='verPdfOferta(\"${oferta.Aseguradora}\", \"${oferta.NumCotizOferta}\", \"${numId}\", \"${id_intermediario}\");'>
  
                          <div id="verPdf${oferta.NumCotizOferta}${numId}\">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                        </button>
  
                        </div>`;
      } else if (
        (oferta.Manual == "0" ||
          oferta.Manual == "8" ||
          oferta.Manual == "9" ||
          oferta.Manual == "3" ||
          oferta.Manual == "4") &&
        (oferta.Aseguradora == "Previsora Seguros" ||
          oferta.Aseguradora == "Previsora") &&
        oferta.UrlPdf !== null &&
        aseguradoraPermisos == "1"
      ) {
        cardCotizacion += `
  
                        <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                        <button type="button" class="btn btn-info" id="previsora-pdf${oferta.NumCotizOferta}" onclick='verPdfPrevisora(\"${oferta.NumCotizOferta}\");'>
  
                          <div id="verPdf${oferta.NumCotizOferta}${numId}\">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                        </button>
  
                        </div>`;
      } else if (
        (oferta.Manual == "0" ||
          oferta.Manual == "8" ||
          oferta.Manual == "9" ||
          oferta.Manual == "4") &&
        oferta.Aseguradora == "Seguros del Estado" &&
        oferta.UrlPdf !== null &&
        aseguradoraPermisos == "1"
      ) {
        cardCotizacion += `
  
                        <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                        <button type="button" class="btn btn-info" id="btnAsegPDF${oferta.NumCotizOferta}${numId}\" onclick='verPdfEstado(\"${oferta.Aseguradora}\", \"${oferta.NumCotizOferta}\", \"${numId}\", \"${oferta.UrlPdf}\");'>
  
                          <div id="verPdf${oferta.NumCotizOferta}${numId}\">VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                        </button>
  
                        </div>`;
      } else if (
        (oferta.Manual == "0" ||
          oferta.Manual == "8" ||
          oferta.Manual == "9" ||
          oferta.Manual == "4") &&
        oferta.Aseguradora == "Solidaria" &&
        aseguradoraPermisos == "1"
      ) {
        cardCotizacion += `
  
                        <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                            <button id="solidaria-pdf" type="button" class="btn btn-info" onclick='verPdfSolidaria(${oferta.NumCotizOferta})'>
  
                              <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                            </button>
  
                        </div>`;
      } else if (
        (oferta.Manual == "0" ||
          oferta.Manual == "8" ||
          oferta.Manual == "9" ||
          oferta.Manual == "4") &&
        oferta.Aseguradora == "Mapfre" &&
        aseguradoraPermisos == "1"
      ) {
        cardCotizacion += `
  
                        <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                            <button id="mapfre-pdf" type="button" class="btn btn-info" onclick='verPdfMapfre(${oferta.NumCotizOferta})'>
  
                              <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                            </button>
  
                        </div>`;
      } else if (
        (oferta.Manual == "0" ||
          oferta.Manual == "8" ||
          oferta.Manual == "9" ||
          oferta.Manual == "4") &&
        oferta.Aseguradora == "Zurich" &&
        aseguradoraPermisos == "1"
      ) {
        cardCotizacion += `
  
                        <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                            <button id="Zurich-pdf${oferta.NumCotizOferta}" type="button" class="btn btn-info" onclick='verPdfZurich(${oferta.NumCotizOferta})'>
  
                              <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                            </button>
  
                        </div>`;
      } else if (
        (oferta.Manual == "0" ||
          oferta.Manual == "8" ||
          oferta.Manual == "9" ||
          oferta.Manual == "4") &&
        oferta.Aseguradora == "HDI Seguros" &&
        aseguradoraPermisos == "1"
      ) {
        cardCotizacion += `
  
                        <div class="col-xs-12 col-sm-6 col-md-2 verpdf-oferta">
  
                            <button id="Hdi-pdf${oferta.NumCotizOferta}" type="button" class="btn btn-info" onclick='verPdfHdi("${oferta.NumCotizOferta}")'>
  
                              <div>VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span></div>
  
                            </button>
  
                        </div>`;
      }

      cardCotizacion += `
  
                      </div>
  
                    </div>
  
                  </div>
  
                `;

      numId++;
    });
    $("#loaderFilters").html("");
    $("#cardCotizacion").html(cardCotizacion);
  }, 1000);
}

async function offertsFinesaRender() {
  let ofrts = [];
  const MAX_RETRIES = 3; // Número máximo de intentos
  const RETRY_DELAY = 2000; // Tiempo de espera entre intentos (en milisegundos)

  const headers = new Headers();
  headers.append("Content-Type", "application/json");

  const body = {
    idCotizacion: idCotizacion,
    //env: "QAS",
  };

  // Función recursiva para manejar reintentos
  async function fetchWithRetry(retries = MAX_RETRIES) {
    try {
      const dbResponse = await fetch(
        `https://grupoasistencia.com/motor_webservice/getOffertsFinesa${
          env == "qas" ? "_qas" : env == "dev" ? "_qas" : ""
        }`,
        {
          method: "POST",
          headers: headers,
          body: JSON.stringify(body),
        }
      );

      if (!dbResponse.ok) {
        throw new Error(`HTTP error! Status: ${dbResponse.status}`);
      }

      ofrts = await dbResponse.json();
      if (ofrts.length === 0) {
        $("#btnCotizarFinesaRetoma").show();
      }
      return ofrts;
    } catch (error) {
      console.error("Error fetching data:", error);

      if (retries > 0) {
        console.log(
          `Retrying... ${MAX_RETRIES - retries + 1} of ${MAX_RETRIES}`
        );
        await new Promise((resolve) => setTimeout(resolve, RETRY_DELAY)); // Espera antes de reintentar
        return fetchWithRetry(retries - 1); // Reintenta la solicitud
      } else {
        console.error("Max retries reached. Could not fetch data.");
        throw error; // Lanza el error si se agotaron los intentos
      }
    }
  }

  return fetchWithRetry(); // Llamada inicial a la función con reintentos
}

function editarCotizacion(id) {
  idCotizacion = id; // Almacena el Id en la variable global de idCotización
  $(".container-filters").css("display", "none");
  var datos = new FormData();

  datos.append("idCotizacion", idCotizacion);
  /*=============================================			
  
  INFORMACION DEL ASEGURADO Y DEL VEHICULO
  
  =============================================*/

  $.ajax({
    url: "ajax/cotizaciones.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      /* FORMULARIO INFORMACIÓN DEL ASEGURADO */
      $("#placaVeh").val(respuesta["cot_placa"]);
      $("#idCliente").val(respuesta["id_cliente"]);
      $("#tipoDocumentoID").val(respuesta["id_tipo_documento"]);
      $("#numDocumentoID").val(respuesta["cli_num_documento"]);
      $("#mundial").val(respuesta["cot_mundial"]);

      if (respuesta["id_tipo_documento"] == 2) {
        $("#numDocumentoID").val(respuesta["cli_num_documento"]);
        $("#txtDigitoVerif").val(respuesta["digitoVerificacion"]);
        $('label[for="txtNombres"]').text("Dígito de Verificación");
        $("#divNombre").css("display", "none");
        $("#digitoVerificacion").css("display", "block");

        // Fila Fecha, Razon Social (Para Nit), Genero, Estado Civil, Celular (Todas menos NIT)
        $('label[name="lblFechaNacimiento"]').html(
          "Fecha Constitución Empresa"
        );
        // <span style="font-weight: normal;">(Opcional. Se requiere para Allianz)</span>
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

        $("#txtRazonSocial").val(
          respuesta.cli_nombre + " " + respuesta.cli_apellidos
        );

        let fechaNit = respuesta["cli_fch_nacimiento"].split("-");

        $("#dianacimiento").append(
          "<option value='" +
            fechaNit[2] +
            "' selected>" +
            fechaNit[2] +
            "</option>"
        );
        $("#mesnacimiento").append(
          "<option value='" +
            fechaNit[1] +
            "' selected>" +
            fechaNit[1] +
            "</option>"
        );
        $("#anionacimiento").append(
          "<option value='" +
            fechaNit[0] +
            "' selected>" +
            fechaNit[0] +
            "</option>"
        );

        // Carga de Data del Representante Legal BEGIN
        $("#tipoDocumentoIDRepresentante").val(respuesta.rep_tipo_documento);
        $("#numDocumentoIDRepresentante").val(respuesta.rep_num_documento);
        $("#txtNombresRepresentante").val(respuesta.rep_nombre);
        $("#txtApellidosRepresentante").val(respuesta.rep_apellidos);
        $("#generoRepresentante").val(respuesta.rep_genero);
        $("#estadoCivilRepresentante").val(respuesta.rep_est_civil);
        $("#txtCorreoRepresentante").val(respuesta.rep_email);
        $("#txtCelularRepresentante").val(respuesta.rep_telefono);

        let fechaRep = respuesta.rep_fch_nacimiento.split("-");

        $("#dianacimientoRepresentante").append(
          "<option value='" +
            fechaRep[2] +
            "' selected>" +
            fechaRep[2] +
            "</option>"
        );
        $("#mesnacimientoRepresentante").append(
          "<option value='" +
            fechaRep[1] +
            "' selected>" +
            fechaRep[1] +
            "</option>"
        );
        $("#anionacimientoRepresentante").append(
          "<option value='" +
            fechaRep[0] +
            "' selected>" +
            fechaRep[0] +
            "</option>"
        );

        $("#DatosVehiculo").css("display", "hidden");

        // Carga de Data del Representante Legal END
      } else {
        $("#txtNombres").val(respuesta["cli_nombre"]);
        $("#txtApellidos").val(respuesta["cli_apellidos"]);
        $("#genero").val(respuesta["cli_genero"]);
        $("#estadoCivil").val(respuesta["id_estado_civil"]);
        $("#telefonoID").val(respuesta["cli_telefono"]);
        $("#emailID").val(respuesta["cli_email"]);

        // console.log("Valor de #mundial:", respuesta["cot_mundial"]);
        //Desactive
        //console.log("CREDENCIALES:", respuesta);

        if (respuesta && respuesta["cli_fch_nacimiento"]) {
          var fecha = respuesta["cli_fch_nacimiento"].split("-");
          // Resto del código que utiliza 'fecha'
        } else {
          console.error(
            "La propiedad 'cli_fch_nacimiento' no está definida o es null/undefined."
          );
        }

        if (fecha && Array.isArray(fecha) && fecha.length > 1) {
          var nombreMes = obtenerNombreMes(fecha[1]);
          // Resto del código que utiliza 'nombreMes'
        } else {
          console.error(
            "La variable 'fecha' no está definida, no es un array o no tiene al menos dos elementos."
          );
        }

        if (fecha && Array.isArray(fecha) && fecha.length >= 3) {
          $("#dianacimiento").append(
            "<option value='" +
              fecha[2] +
              "' selected>" +
              fecha[2] +
              "</option>"
          );
        } else {
          console.error(
            "La variable 'fecha' no está definida, no es un array o no tiene al menos tres elementos."
          );
        }

        if (fecha && Array.isArray(fecha) && fecha.length >= 1 && nombreMes) {
          $("#mesnacimiento").append(
            "<option value='" +
              fecha[1] +
              "' selected>" +
              nombreMes[0].toUpperCase() +
              nombreMes.slice(1) +
              "</option>"
          );
        } else {
          console.error(
            "La variable 'fecha' no está definida, no es un array o no tiene al menos un elemento, o 'nombreMes' no está definida."
          );
        }

        if (fecha && Array.isArray(fecha) && fecha.length >= 1) {
          $("#anionacimiento").append(
            "<option value='" +
              fecha[0] +
              "' selected>" +
              fecha[0] +
              "</option>"
          );
        } else {
          console.error(
            "La variable 'fecha' no está definida, no es un array o no tiene al menos un elemento."
          );
        }
      }

      /* FORMULARIO INFORMACIÓN DEL VEHICULO */

      if (respuesta["cot_cerokm"] == 1) {
        document.getElementById("contenPlaca").style.display = "none";

        document.getElementById("contenCeroKM").style.display = "block";

        $("#txtConocesLaPlacaNo").prop("checked", true);

        $("#txtEsCeroKmSi").prop("checked", true);
      }

      if (respuesta["cot_placa"] == "KZY000") {
        $("#txtPlacaVeh").val("SIN PLACA - VEHÍCULO 0 KM").val();
      } else {
        $("#txtPlacaVeh").val(respuesta["cot_placa"]).val();
      }

      $("#CodigoClase").val(respuesta["cot_cod_clase"]);

      $("#txtClaseVeh").val(respuesta["cot_clase"]);

      $("#txtMarcaVeh").val(respuesta["cot_marca"]);

      $("#txtModeloVeh").val(respuesta["cot_modelo"]);

      $("#txtReferenciaVeh").val(respuesta["cot_linea"]);

      $("#txtFasecolda").val(respuesta["cot_fasecolda"]);

      $("#txtValorFasecolda").val(respuesta["cot_valor_asegurado"]);

      $("#DptoCirculacion").append(
        "<option value='" +
          respuesta["cot_departamento"] +
          "' selected>" +
          departamentoVeh(respuesta["cot_departamento"]) +
          "</option>"
      );

      var posicion =
        respuesta["cot_ciudad"].slice(0, 2) == "44"
          ? respuesta["ciudad"]
          : respuesta["Nombre"].split("-");

      var ciudad =
        respuesta["cot_ciudad"].slice(0, 2) == "44"
          ? posicion.toLowerCase()
          : posicion[0].toLowerCase();

      var nomCiudad = ciudad.replace(/^(.)|\s(.)/g, function ($1) {
        return $1.toUpperCase();
      });

      $("#ciudadCirculacion").append(
        "<option value='" +
          respuesta["cot_ciudad"] +
          "' selected>" +
          nomCiudad +
          "</option>"
      );

      if (respuesta["cot_bnf_oneroso"] != "") {
        $("#esOnerosoSi").prop("checked", true);

        $("#benefOneroso").val(respuesta["cot_bnf_oneroso"]);

        document.getElementById("contenBenefOneroso").style.display = "block";
      } else {
        $("#esOnerosoNo").prop("checked", true);
      }

      document.getElementById("contentOnerosoCheckBox").style.display = "none";
      //FORMULARIO DE PESADOS//

      if (respuesta["cot_placa"] == "CAT770") {
        $("#txtPlacaVehPesado").val(respuesta["cot_placa"]).val();
      } else {
        $("#txtPlacaVehPesado").val(respuesta["cot_placa"]).val();
      }

      $("#txtModeloVehPesado").val(respuesta["cot_modelo"]);

      $("#clasepesados").val(respuesta["cot_clase"]);

      $("#txtMarcaVehPesado").val(respuesta["cot_marca"]);

      $("#txtReferenciaVehPesado").val(respuesta["cot_linea"]);

      $("#txtFasecoldaPesado").val(respuesta["cot_fasecolda"]);

      $("#txtValorFasecoldaPesado").val(respuesta["cot_valor_asegurado"]);

      $("#txtTipoUsoVehiculoPesado").val(respuesta["cot_tip_uso"]);

      $("#txtTipoServicioPesado").val(respuesta["cot_tip_servicio"]);

      $("#DptoCirculacionPesado").append(
        "<option value='" +
          respuesta["cot_departamento"] +
          "' selected>" +
          departamentoVeh(respuesta["cot_departamento"]) +
          "</option>"
      );

      $("#ciudadCirculacionPesado").append(
        "<option value='" +
          respuesta["cot_ciudad"] +
          "' selected>" +
          nomCiudad +
          "</option>"
      );

      $("#mundialseguros").val(respuesta["cot_mundial"]);

      var valorMundial = document.getElementById("mundial").value;
      // Desactive
      //console.log(valorMundial);

      if (valorMundial === null || valorMundial === "") {
        document.getElementById("DatosVehiculoPesados").style.display = "none";
        document.getElementById("DatosVehiculo").style.display = "block";
      } else {
        document.getElementById("DatosVehiculoPesados").style.display = "block";
        document.getElementById("DatosVehiculo").style.display = "none";
      }

      var permisosCotizacion = respuesta["permisosCotizacion"];

      if (permisosCotizacion === null || permisosCotizacion === undefined) {
        var permisosCotizacion =
          '{"Allianz":{"A":"1","C":"1"},"AXA":{"A":"1","C":"1"},"Bolivar":{"A":"1","C":"1"},"Equidad":{"A":"1","C":"1"},"Estado":{"A":"1","C":"1"},"HDI":{"A":"1","C":"1"},"Liberty":{"A":"1","C":"1"},"Mapfre":{"A":"1","C":"1"},"Previsora":{"A":"1","C":"1"},"SBS":{"A":"1","C":"1"},"Solidaria":{"A":"1","C":"1"},"Zurich":{"A":"1","C":"1"}}';
      }

      window.permisosCotizacion1 = permisosCotizacion;

      //Desactive
      //console.log(permisosCotizacion)
      /*=============================================			
 
       // CONSULTA LAS OFERTAS DE LA COTIZACION
 
       =============================================*/

      var datos2 = new FormData();

      datos2.append("idCotizaOferta", idCotizacion);

      $.ajax({
        url: "ajax/cotizaciones.ajax.php",

        method: "POST",

        data: datos2,

        cache: false,

        contentType: false,

        processData: false,

        dataType: "json",

        success: async function (resp) {
          menosRE();
          if (resp.length > 0) {
            ofertas = resp;
            manualGeneral = resp[0].Manual;
            if (manualGeneral != "4") {
              if (manualGeneral == "3") {
                $("#divNumToneladas").css("display", "block");
                $("#numToneladas").val(respuesta["cot_num_toneladas"]);
              }
              $("#txtTipoUsoVehiculo").val(respuesta["cot_tip_uso"]);
              $("#txtTipoServicio").val(respuesta["cot_tip_servicio"]);
            } else {
              $("#divTipoUso").css("display", "none");
              $("#divTipoServicio").css("display", "none");
              $("#divTipoTransporte").css("display", "block");
              // trigger change
              if (respuesta["cot_tip_uso"] == "2") {
                console.log(respuesta["cot_tip_uso"]);
                $("#divNumeroPasajeros").css("display", "block");
                $("#txtNumeroPasajeros").val(respuesta["cot_num_pasajeros"]);
              }

              $("#txtTipoTransporteVehiculo")
                .val(respuesta["cot_tip_uso"])
                .trigger("change");
            }

            renderCards(resp);
            let updatevideos = document.querySelectorAll(".editar-manual");
            for (updatevideo of updatevideos) {
              updatevideo.addEventListener("click", function (e) {
                let idupdate = updatevideo.id;

                getManualOffer(idupdate);
              });
            }

            let videos = document.querySelectorAll(".eliminar-manual");
            for (video of videos) {
              video.addEventListener("click", function (e) {
                let id = video.id;
                id2 = id.split("-");
                //desactive
                //console.log(id2[1]);

                deleteManualOffer(id2[1]);
              });
            }
          } else {
            $("#loaderOferta").html("");

            swal.fire({
              type: "warning",

              title: "¡ UPS, Lo Sentimos !",

              text: "¡ No hay ofertas disponibles para tu vehículo !",

              showConfirmButton: true,

              confirmButtonText: "Cerrar",
            });
          }

          document.getElementById("headerAsegurado").style.display = "block";

          document.getElementById("contenSuperiorPlaca").style.display = "none";

          document.getElementById("contenBtnConsultarPlaca").style.display =
            "none";

          document.getElementById("resumenVehiculo").style.display = "block";

          // Oculta el Boton Cotizar Ofertas al cargar la Parrilla

          document.getElementById("contenBtnCotizar").style.display = "none";

          // Muestra los Botones Recotizar y Agregar Cotizacion

          document.getElementById("contenRecotizarYAgregar").style.display =
            "block";

          // Muestra el Contenido de la Parrilla de Ofertas, Cotizaciones Manuales y PDF

          document.getElementById("contenParrilla").style.display = "block";

          menosAseg();
          menosRECot();
        },
      });
    },
  });
}

$(
  "#dianacimiento, #mesnacimiento, #anionacimiento, #dianacimientoRepresentante, #mesnacimientoRepresentante, #anionacimientoRepresentante"
).select2({
  theme: "bootstrap fecnacimiento",
  language: "es",
  width: "100%",
});

/*===============================================

FUNCION PARA SELECCIONAR OFERTA DE LA ASEGURADORA

===============================================*/

function seleccionarOferta(
  aseguradora,
  prima,
  producto,
  numCotizOferta,
  id_oferta,
  valCheck
) {
  var idSelecOferta = idCotizacion;

  console.log(
    aseguradora,
    prima,
    producto,
    numCotizOferta,
    id_oferta,
    valCheck
  );

  var placa = document.getElementById("placaVeh").value;

  // Capturamos el Id del Checkbox seleccionado

  var idCheckbox = $(valCheck).attr("id");

  var seleccionar = "";

  if (document.getElementById(idCheckbox).checked) {
    seleccionar = "Si";
  }

  ofertas.forEach((element) => {
    if (element.seleccionar == "Si" && element.oferta_finesa == id_oferta) {
      element.seleccionar = "";
    } else {
      element.seleccionar = seleccionar;
    }
  });

  $.ajax({
    type: "POST",

    url: "src/seleccionarOferta.php",

    dataType: "json",

    data: {
      placa: placa,

      idCotizacion: idSelecOferta,

      aseguradora: aseguradora,

      numCotizOferta: numCotizOferta,

      producto: producto,

      valorPrima: prima,

      seleccionar: seleccionar,
    },

    success: function (data) {},
  });
}

/*===============================================

FUNCION PARA RECOMENDAR OFERTA DE LA ASEGURADORA

===============================================*/

function recomendarOferta(
  aseguradora,

  prima,

  producto,

  numCotizOferta,

  valCheck
) {
  var idRecomOferta = idCotizacion;

  var placa = document.getElementById("placaVeh").value;

  // Capturamos el Id del Checkbox seleccionado

  var idCheckbox = $(valCheck).attr("id");

  var recomendar = "";

  if (document.getElementById(idCheckbox).checked) {
    recomendar = "Si";
  }

  // Valida que no se Recomiende mas de 3 Ofertas.

  if ($(".classRecomOferta:checked").length > 3) {
    $("#" + idCheckbox).prop("checked", false); // Permite deselecionar el Checkbox

    swal({
      text: "! No se permite recomendar mas de 3 Ofertas por Parrilla. ¡",
    });
  } else {
    $.ajax({
      type: "POST",

      url: "src/recomendarOferta.php",

      dataType: "json",

      data: {
        placa: placa,

        idCotizacion: idRecomOferta,

        aseguradora: aseguradora,

        numCotizOferta: numCotizOferta,

        producto: producto,

        valorPrima: prima,

        recomendar: recomendar,
      },

      success: function (data) {
        // desactive
        // console.log(data);
      },
    });
  }
}

/*==================================================

FUNCION PARA CARGAR EL PDF OFICIAL DE LA ASEGURADORA

==================================================*/

function verPdfOferta(aseguradora, numCotizOferta, numId, intermediario) {
  // desactive
  // console.log(aseguradora)
  // console.log(numCotizOferta)
  // console.log(numId)

  if (permisos.Verpdfindividuales != "x") {
    Swal.fire({
      icon: "error",

      title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
    $("#verPdf" + numCotizOferta + numId).html(
      "VER PDF &nbsp;&nbsp;<img src='vistas/img/plantilla/loading.gif' width='18' height='18'>"
    );

    var ventanaPDF = window.open(
      "",

      aseguradora,

      "width=" + 1024 + ", height=" + 768
    );

    // var ventanaPDF = window.open('http://example.com/waiting.html', '_blank'); // Carga otra pagina

    // ventanaPDF.document.write("Cargando vista previa Pdf " + aseguradora + "..."); // Carga un mensaje de espera

    var myHeaders = new Headers(); // Cabecera del Metodo

    myHeaders.append("Content-Type", "application/json");

    var raw = JSON.stringify({
      aseguradora: aseguradora,

      numero_cotizacion: numCotizOferta,
      intermediario: intermediario,
    });

    var requestOptions = {
      mode: "cors",

      method: "POST",

      headers: myHeaders,

      body: raw,

      redirect: "follow",
    };

    // Llama la URL del PDF oficial de la oferta generada por la aseguradora

    fetch(
      "https://www.grupoasistencia.com/motor_webservice/ImpresionPdf",

      requestOptions
    )
      .then(function (response) {
        // desactive
        //console.log(response)

        if (!response.ok) {
          throw Error(response.statusText);
        }

        return response.json();
      })

      .then(function (data) {
        ventanaPDF.location.href = data;

        $("#verPdf" + numCotizOferta + numId).html(
          'VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span>'
        );
      })

      .catch(function (error) {
        // desactive
        //console.log("Parece que hubo un problema: \n", error);
      });
  }
}

/*======================================================

FUNCION PARA CARGAR EL PDF OFICIAL DE SEGUROS DEL ESTADO

======================================================*/

function verPdfEstado(aseguradora, numCotizOferta, numId, UrlPdf) {
  if (permisos.Verpdfindividuales != "x") {
    Swal.fire({
      icon: "error",

      title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
    $("#verPdf" + numCotizOferta + numId).html(
      "VER PDF &nbsp;&nbsp;<img src='vistas/img/plantilla/loading.gif' width='18' height='18'>"
    );

    var ventanaPDF = window.open(
      "",

      aseguradora,

      "width=" + 1024 + ", height=" + 768
    );

    ventanaPDF.document.write(
      "Cargando vista previa Pdf " + aseguradora + "..."
    ); // Carga un mensaje de espera

    ventanaPDF.location.href = UrlPdf;

    setTimeout(function () {
      $("#verPdf" + numCotizOferta + numId).html(
        'VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span>'
      );
    }, 6000);
  }
}

/*======================================================

FUNCION PARA CARGAR EL PDF OFICIAL PREVISORA

======================================================*/

const verPdfPrevisora = async (cotizacion) => {
  if (permisos.Verpdfindividuales != "x") {
    Swal.fire({
      icon: "error",

      title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
    $("#previsora-pdf" + cotizacion).html(
      "VER PDF &nbsp;&nbsp;<img id='loading-gif' src='vistas/img/plantilla/loading.gif' width='18' height='18'>"
    );

    let base64 = await obtenerPdfprevisora(cotizacion);
    // console.log(base64);
    const linkSource = `data:application/pdf;base64,${base64}`;
    // console.log(linkSource);
    const downloadLink = document.createElement("a");

    const fileName = cotizacion + ".pdf";

    downloadLink.href = linkSource;

    downloadLink.download = fileName;

    downloadLink.addEventListener("click", () => {
      // Eliminar la animación del GIF al hacer clic en el enlace de descarga
      $("#loading-gif").remove();
    });

    downloadLink.click();

    $("#previsora-pdf" + cotizacion).html(
      'VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span>'
    );
  }
};

const obtenerPdfprevisora = async (cotizacion) => {
  const formData = new FormData();

  const id_intermediario = document.getElementById("idIntermediario").value;

  formData.append("cotizacion", cotizacion);
  formData.append("intermediario", id_intermediario);

  const pdfText = await fetch(
    "https://www.grupoasistencia.com/motor_webservice/WSPrevisora/get_pdf_previsora.php",

    {
      method: "POST",

      body: formData,
    }
  )
    // .then((response) => response.text()) // Obtén la respuesta como texto
    // .then((responseText) => {
    //   console.log(responseText); // Imprime la respuesta para depuración

    //   // Ahora intenta analizarla como JSON
    //   try {
    //     const jsonResponse = JSON.parse(responseText);
    //     return jsonResponse.SerializedPDF;
    //   } catch (error) {
    //     console.error("Error al analizar JSON:", error);
    //     return null; // Otra acción si el análisis falla
    //   }
    // });

    // .then((response) => response.json())

    // .then((responseText) => {
    //   return responseText.SerializedPDF;

    // });

    .then((response) => {
      // Imprime la respuesta en la consola para depuración
      // Desactive
      //console.log("Respuesta del servidor:", response);

      if (response.ok) {
        return response.json();
      } else {
        throw new Error("Error en la respuesta del servidor");
      }
    })
    .then((responseText) => {
      // Imprime el contenido de la respuesta (JSON) en la consola
      // desactive
      // console.log("Contenido de la respuesta (JSON):", responseText);
      console.log(responseText.SerializedPDF);
      return responseText.SerializedPDF;
    })
    .catch((error) => {
      console.error("Error al obtener PDF:", error);
      return null; // Manejar el error de alguna manera
    });

  return pdfText;
};

/*======================================================

FUNCION PARA CARGAR EL PDF OFICIAL DE SOLIDARIA

======================================================*/

const verPdfSolidaria = async (cotizacion) => {
  if (permisos.Verpdfindividuales != "x") {
    Swal.fire({
      icon: "error",

      title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
    let base64 = await obtenerPdfSolidaria(cotizacion);

    base64 = base64.slice(1, -1);

    const linkSource = `data:application/pdf;base64,${base64}`;

    const downloadLink = document.createElement("a");

    const fileName = cotizacion + ".pdf";

    downloadLink.href = linkSource;

    downloadLink.download = fileName;

    downloadLink.click();
  }
};

const verPdfMapfre = async (cotizacion) => {
  if (permisos.Verpdfindividuales != "x") {
    Swal.fire({
      icon: "error",

      title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
    $("#mapfre-pdf").html(
      "VER PDF &nbsp;&nbsp;<img src='vistas/img/plantilla/loading.gif' width='18' height='18'>"
    );
    let base64 = await obtenerPdfMapfre(cotizacion);
    $("#mapfre-pdf").html(
      "VER PDF &nbsp;&nbsp;<span class='fa fa-file-text'></span>"
    );
    const linkSource = `data:application/pdf;base64,${base64}`;
    const downloadLink = document.createElement("a");
    const fileName = cotizacion + ".pdf";
    downloadLink.href = linkSource;
    downloadLink.download = fileName;
    downloadLink.click();
  }
};

/*======================================================

FUNCION PARA CARGAR EL PDF OFICIAL DE MAPFRE

======================================================*/

const obtenerPdfMapfre = async (cotizacion) => {
  const myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  const raw = JSON.stringify({
    aseguradora: "Mapfre",
    numero_cotizacion: cotizacion,
  });

  const requestOptions = {
    mode: "cors",
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  try {
    const response = await fetch(
      "https://www.grupoasistencia.com/motor_webservice/ImpresionPdf",
      requestOptions
    );

    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.statusText}`);
    }
    const pdfText = await response.json(); // Asume que la respuesta es texto
    return pdfText.imprimirPolizaCotizacionResponse.stream;
  } catch (error) {
    console.error("Error al obtener el PDF:", error);
    throw error; // Relanza el error para que el llamador lo maneje
  }
};

const obtenerPdfSolidaria = async (cotizacion) => {
  const formData = new FormData();
  const id_intermediario = document.getElementById("idIntermediario").value;

  formData.append("cotizacion", cotizacion);
  formData.append("intermediario", id_intermediario);

  for (const entry of formData) {
    // desactive
    // console.log(entry);
  }

  // O bien, convertir el FormData a un objeto y luego imprimirlo
  const formDataObj = Object.fromEntries(formData.entries());
  // desactive
  // console.log(formDataObj);

  const pdfText = await fetch(
    "https://www.grupoasistencia.com/motor_webservice/WSSolidaria/get_pdf.php",

    {
      method: "POST",

      body: formData,
    }
  )
    .then((response) => response.text())

    .then((responseText) => {
      return responseText;
    });

  return pdfText;
};

/*======================================================

FUNCION PARA CARGAR EL PDF OFICIAL DE ZURICH

======================================================*/

const verPdfZurich = async (cotizacion) => {
  if (permisos.Verpdfindividuales != "x") {
    Swal.fire({
      icon: "error",

      title: "¡Esta versión no tiene ésta funcionalidad disponible!",

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
    $("#Zurich-pdf" + cotizacion).html(
      "VER PDF &nbsp;&nbsp;<img src='vistas/img/plantilla/loading.gif' width='18' height='18'>"
    );

    const formData = new FormData();

    formData.append("cotizacion", cotizacion);

    const blobPdfZurich = await fetch(
      "https://www.grupoasistencia.com/motor_webservice/WSZurich/get_pdf.php",

      {
        method: "POST",

        body: formData,
      }
    )
      .then((response) => response.blob())

      .then((resBlob) => {
        const res = new Blob([resBlob], {
          type: "application/pdf",
        });

        return res;
      });

    const downloadUrl = URL.createObjectURL(blobPdfZurich);

    const a = document.createElement("a");

    a.href = downloadUrl;

    a.download = "Zurich_" + cotizacion + ".pdf";

    document.body.appendChild(a);

    a.click();

    $("#Zurich-pdf" + cotizacion).html(
      'VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span>'
    );
  }
};

/*======================================================

FUNCION PARA CARGAR EL PDF OFICIAL DE HDI

======================================================*/

const verPdfHdi = async (cotizacion) => {
  $("#Hdi-pdf" + cotizacion).html(
    "VER PDF &nbsp;&nbsp;<img src='vistas/img/plantilla/loading.gif' width='18' height='18'>"
  );

  const formData = new FormData();
  formData.append("cotizacion", cotizacion);
  formData.append("idCotizacion", idCotizacion);

  try {
    const blobPdfHdi = await fetch(
      "https://www.grupoasistencia.com/motor_webservice/WSHDIPLUS/get_pdf_hdi.php",
      {
        method: "POST",
        body: formData,
      }
    ).then((response) => response.blob());

    const downloadUrl = URL.createObjectURL(blobPdfHdi);
    const a = document.createElement("a");
    a.href = downloadUrl;
    a.download = "HDI" + cotizacion + ".pdf";
    document.body.appendChild(a);
    a.click();

    $("#Hdi-pdf" + cotizacion).html(
      'VER PDF &nbsp;&nbsp;<span class="fa fa-file-text"></span>'
    );
  } catch (error) {
    console.error("Error durante la descarga del PDF:", error);
  }
};

// const obtenerPdfZurich = async (cotizacion) => {

//   const formData = new FormData();

//   formData.append("cotizacion", cotizacion);

//   const pdfText = await fetch(

//     "https://www.grupoasistencia.com/motor_webservice/WSZurich/get_pdf.php",

//     {

//       method: "POST",

//       body: formData,

//     }

//   )

//     .then((response) => response.text())

//     .then((responseText) => {

//       return responseText;

//     });

//   return pdfText;

// };

/*==================================================

FUNCION PARA CARGAR EL PRODUCTO DE LA ASEGURADORA

==================================================*/

function selecProductoManual() {
  vaciarCamposOfertaManual();

  var aseguradora = $("#aseguradora").val();

  $.ajax({
    type: "POST",

    url: "src/seleccionarProducto.php",

    dataType: "json",

    data: {
      aseguradora: aseguradora,
    },

    cache: false,

    success: function (data) {
      // console.log(data);

      var producto = "<option value=''>Seleccione Producto</option>";

      $.each(data, function (key, item) {
        if (item.aseguradora == "HDI (Antes Liberty)") {
          switch (item.id_asistencias) {
            case "134":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 0 a 6 MM" +
                "</option>";
              break;
            case "135":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 6 a 10 MM" +
                "</option>";
              break;
            case "136":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 10 a 20 MM" +
                "</option>";
              break;
            case "137":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 20 a 30 MM" +
                "</option>";
              break;
            case "138":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 30 a 90 MM" +
                "</option>";
              break;
            case "139":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 0 a 6 MM" +
                "</option>";
              break;
            case "140":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 6 a 10 MM" +
                "</option>";
              break;
            case "141":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 10 a 20 MM" +
                "</option>";
              break;
            case "142":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 20 a 30 MM" +
                "</option>";
              break;
            case "143":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 30 a 90 MM" +
                "</option>";
              break;
            case "145":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 0 a 6 MM" +
                "</option>";
              break;
            case "146":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 6 a 10 MM" +
                "</option>";
              break;
            case "147":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 10 a 20 MM" +
                "</option>";
              break;
            case "148":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 20 a 30 MM" +
                "</option>";
              break;
            case "149":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos 30 a 90 MM" +
                "</option>";
              break;
            case "31":
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                " Motos" +
                "</option>";
              break;
            default:
              producto +=
                "<option value='" +
                item.id_asistencias +
                "'>" +
                item.producto +
                "</option>";
          }
        } else {
          producto +=
            "<option value='" +
            item.producto +
            "'>" +
            item.producto +
            "</option>";
        }
      });

      $("#producto").html(producto);
    },
  });
}

/*==================================================

FUNCION PARA CARGAR LAS COBERTURAS

==================================================*/

function selecRCManual() {
  var aseguradora = $("#aseguradora").val();

  var producto = $("#producto").val();

  $.ajax({
    type: "POST",

    url: "src/seleccionarRC.php",

    dataType: "json",

    data: {
      aseguradora: aseguradora,

      producto: producto,
    },

    cache: false,

    success: function (data) {
      // console.log(data);

      if (data.length > 1) {
        var valorRC = "<option value=''>Seleccione RC</option>";

        $.each(data, function (key, item) {
          valorRC +=
            "<option value='" + item.rce + "'>" + item.rce + "</option>";
        });

        $("#valorRC").html(valorRC);
      } else {
        $("#valorRC").html(
          "<option value='" +
            data[0].rce +
            "' selected>" +
            data[0].rce +
            "</option>"
        );

        selecCoberturasManual();
      }
    },
  });
}

/*==================================================

FUNCION PARA CARGAR LAS COBERTURAS

==================================================*/

function selecCoberturasManual() {
  var aseguradora = $("#aseguradora").val();

  var producto = $("#producto").val();

  var valorRC = $("#valorRC").val();

  var modeloVeh = $("#txtModeloVeh").val();

  var valorFasecolda = $("#txtValorFasecolda").val();

  var diaNac = $("#dianacimiento").val();

  var mesNac = $("#mesnacimiento").val();

  var anioNac = $("#anionacimiento").val();

  var fechaNacimiento = diaNac + "/" + mesNac + "/" + anioNac;

  $.ajax({
    type: "POST",

    url: "src/seleccionarCoberturas.php",

    dataType: "json",

    data: {
      aseguradora: aseguradora,

      producto: producto,

      valorRC: valorRC,
    },

    cache: false,

    success: function (data) {
      // console.log(data);

      var edadVeh = new Date().getFullYear() - modeloVeh;

      var edadAseg = calcularEdad(fechaNacimiento);

      var perdTotales = data.pth;

      var perdParcialDanio = data.ppd;

      if (
        (aseguradora == "Seguros Bolivar" && producto == "Estandar") ||
        producto == "Clasico"
      ) {
        if (edadVeh <= 5) {
          perdTotales = "Cubrimiento al 100%";
        } else {
          perdTotales = "Cubrimiento al 90%";
        }
      }

      if (
        (aseguradora == "Axa Colpatria" && producto == "Plus") ||
        producto == "VIP" ||
        producto == "Tradicional"
      ) {
        if (edadVeh <= 11 && edadAseg > 33) {
          perdParcialDanio = "Deducible unico: $700.000";
        } else {
          perdParcialDanio = "Deducible 10% - 1 SMMLV";
        }
      }

      if (aseguradora == "Allianz Seguros" && producto == "Motocicletas") {
        if (valorFasecolda <= 6000000) {
          perdTotales =
            "Cubrimiento al " +
            calcularPerdTotalAllianz(valorFasecolda, 800000) +
            "%";

          perdParcialDanio = "Deducible unico: $800.000";
        } else if (valorFasecolda > 6000000 && valorFasecolda <= 10000000) {
          perdTotales =
            "Cubrimiento al " +
            calcularPerdTotalAllianz(valorFasecolda, 1350000) +
            "%";

          perdParcialDanio = "Deducible unico: $1.350.000";
        } else if (valorFasecolda > 10000000 && valorFasecolda <= 20000000) {
          perdTotales =
            "Cubrimiento al " +
            calcularPerdTotalAllianz(valorFasecolda, 2000000) +
            "%";

          perdParcialDanio = "Deducible unico: $2.000.000";
        } else if (valorFasecolda > 20000000 && valorFasecolda <= 30000000) {
          perdTotales =
            "Cubrimiento al " +
            calcularPerdTotalAllianz(valorFasecolda, 3000000) +
            "%";

          perdParcialDanio = "Deducible unico: $3.000.000";
        } else if (valorFasecolda > 30000000) {
          perdTotales =
            "Cubrimiento al " +
            calcularPerdTotalAllianz(valorFasecolda, 4000000) +
            "%";

          perdParcialDanio = "Deducible unico: $4.000.000";
        }
      }

      $("#valorPerdidaTotal").val(perdTotales);

      $("#valorPerdidaParcial").val(perdParcialDanio);

      $("#conductorElegido").val(data.CE);

      $("#servicioGrua").val(data.Grua);
    },
  });
}

/*==================================================

FUNCION PARA CALCULAR LA EDAD DESDE LA FECHA DE NAC.

==================================================*/

function calcularEdad(fecha) {
  var fechaNac = new Date(fecha);

  var fechaActual = new Date();

  var mes = fechaActual.getMonth();

  var dia = fechaActual.getDate();

  var año = fechaActual.getFullYear();

  fechaActual.setDate(dia);

  fechaActual.setMonth(mes);

  fechaActual.setFullYear(año);

  edad = Math.floor((fechaActual - fechaNac) / (1000 * 60 * 60 * 24) / 365);

  return edad;
}

/*==================================================

FUNCION PARA CALCULAR LAS PERDIDAS TOTALES "ALLIANZ"

==================================================*/

function calcularPerdTotalAllianz(valorFasecolda, deducible) {
  var cubrimiento = valorFasecolda - deducible;

  var porcentCubrimiento = Math.round((cubrimiento / valorFasecolda) * 100);

  return porcentCubrimiento;
}

/*=============================================

FUNCION PARA AGREGAR COTIZACIONES MANUALES

=============================================*/

function agregarCotizacionManual2() {
  var aseguradora = document.getElementById("aseguradora").value;

  var producto = document.getElementById("producto").value;

  var numCotizOferta = document.getElementById("numCotizacion").value;

  var prima = document.getElementById("valorTotal").value;

  var valorRC = document.getElementById("valorRC").value;

  var PT = document.getElementById("valorPerdidaTotal").value;

  var PT2 = document.getElementById("valorPerdidaTotal").value;

  var PP = document.getElementById("valorPerdidaParcial").value;

  var PP2 = document.getElementById("valorPerdidaParcial").value;

  var CE = document.getElementById("conductorElegido").value;

  var GR = document.getElementById("servicioGrua").value;

  var placa = document.getElementById("txtPlacaVeh").value;

  var id_oferta = document.getElementById("idofertaguardarmanual").value;

  var numDocumentoID = document.getElementById("numDocumentoID").value;

  if (aseguradora == "SBS Seguros" && producto == "RCE Daños") {
    PT = "Cubrimiento al 100% (Daños)";

    PP = "Deducible 10% - 1 SMMLV (Daños)";
  } else if (aseguradora == "SBS Seguros" && producto == "RCE Hurto") {
    PT = "Cubrimiento al 100% (Hurto)";

    PP = "Deducible 10% - 1 SMMLV (Hurto)";
  }

  rutaPdf = "";

  if (
    aseguradora != "" &&
    producto != "" &&
    numCotizOferta != "" &&
    prima != "" &&
    valorRC != "" &&
    PT != "" &&
    PP != "" &&
    CE != "" &&
    GR != ""
  ) {
    $.ajax({
      type: "POST",
      url: "src/agregarcotizacionmanual.php",
      dataType: "json",
      data: {
        aseguradora: aseguradora,
        producto: producto,
        numCotizOferta: numCotizOferta,
        prima: prima,
        placa: placa,
        id_oferta: id_oferta,
        valorRC: valorRC,
        PT: PT,
        PP: PP,
        CE: CE,
        GR: GR,
        manual: 1,
        numIdentificacion: numDocumentoID,
      },
      cache: false,
      success: function (data) {
        // desactive
        // console.log(data);
        if (data.Success == true) {
          swal
            .fire({
              icon: "success",
              title: "¡ Cotización Registrada Exitosamente !",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            })
            .then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
        } else {
          swal.fire({
            icon: "error",
            title: "¡ Cotización no registrada !",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
          });
        }
      },
    });
  }
}

const guardarPdfCotizacioManual = (rutaPdf, archivo) => {
  return new Promise((resolve, reject) => {
    const formData = new FormData();

    formData.append("archivo", archivo);

    formData.append("urlPdf", rutaPdf);

    $.ajax({
      type: "POST",

      url: "src/guardarPdfCotizacion.php",

      data: formData,

      contentType: false,

      processData: false,

      success: (data) => {
        resolve(data);
      },

      error: (err) => {
        reject(err);
      },
    });
  });
};

/*==================================================

FUNCION PARA IDENTIFICAR EL NOMBRE DEL LOGO MANUALMETE

==================================================*/

function logoOfertaManual(aseguradora) {
  var logo = "";

  if (aseguradora == "Seguros del Estado") {
    logo = "estado.png";
  } else if (aseguradora == "Seguros Bolivar") {
    logo = "bolivar.png";
  } else if (aseguradora == "Axa Colpatria") {
    logo = "axa.png";
  } else if (aseguradora == "HDI Seguros") {
    logo = "hdi.png";
  } else if (aseguradora == "SBS Seguros") {
    logo = "sbs.png";
  } else if (aseguradora == "Allianz Seguros") {
    logo = "allianz.png";
  } else if (aseguradora == "Equidad Seguros") {
    r;
    logo = "equidad.png";
  } else if (aseguradora == "Seguros Mapfre") {
    logo = "mapfre.png";
  } else if (aseguradora == "HDI (Antes Liberty)") {
    logo = "hdi.png";
  } else if (aseguradora == "Aseguradora Solidaria") {
    logo = "solidaria.png";
  } else if (aseguradora == "Seguros Sura") {
    logo = "sura.png";
  } else if (aseguradora == "Zurich Seguros") {
    logo = "zurich.png";
  } else if (aseguradora == "Previsora Seguros") {
    logo = "previsora.png";
  } else if (aseguradora == "Previsora") {
    logo = "previsora.png";
  }

  return logo;
}

/*==========================================================

FUNCION PARA CONSULTAR EL NOMBRE DEL DEPARTAMENTO POR CODIGO

==========================================================*/

function departamentoVeh(codigoDpto) {
  var nomDpto = "";

  if (codigoDpto == 1) {
    nomDpto = "Amazonas";
  } else if (codigoDpto == 2) {
    nomDpto = "Antioquia";
  } else if (codigoDpto == 3) {
    nomDpto = "Arauca";
  } else if (codigoDpto == 4) {
    nomDpto = "Atlántico";
  } else if (codigoDpto == 5) {
    nomDpto = "Barranquilla";
  } else if (codigoDpto == 6) {
    nomDpto = "Bogotá";
  } else if (codigoDpto == 7) {
    nomDpto = "Bolívar";
  } else if (codigoDpto == 8) {
    nomDpto = "Boyacá";
  } else if (codigoDpto == 9) {
    nomDpto = "Caldas";
  } else if (codigoDpto == 10) {
    nomDpto = "Caquetá";
  } else if (codigoDpto == 11) {
    nomDpto = "Casanare";
  } else if (codigoDpto == 12) {
    nomDpto = "Cauca";
  } else if (codigoDpto == 13) {
    nomDpto = "Cesar";
  } else if (codigoDpto == 14) {
    nomDpto = "Chocó";
  } else if (codigoDpto == 15) {
    nomDpto = "Córdoba";
  } else if (codigoDpto == 16) {
    nomDpto = "Cundinamarca";
  } else if (codigoDpto == 17) {
    nomDpto = "Guainía";
  } else if (codigoDpto == 18) {
    nomDpto = "La Guajira";
  } else if (codigoDpto == 19) {
    nomDpto = "Guaviare";
  } else if (codigoDpto == 20) {
    nomDpto = "Huila";
  } else if (codigoDpto == 21) {
    nomDpto = "Magdalena";
  } else if (codigoDpto == 22) {
    nomDpto = "Meta";
  } else if (codigoDpto == 23) {
    nomDpto = "Nariño";
  } else if (codigoDpto == 24) {
    nomDpto = "Norte de Santander";
  } else if (codigoDpto == 25) {
    nomDpto = "Putumayo";
  } else if (codigoDpto == 26) {
    nomDpto = "Quindío";
  } else if (codigoDpto == 27) {
    nomDpto = "Risaralda";
  } else if (codigoDpto == 28) {
    nomDpto = "San Andrés";
  } else if (codigoDpto == 29) {
    nomDpto = "Santander";
  } else if (codigoDpto == 30) {
    nomDpto = "Sucre";
  } else if (codigoDpto == 31) {
    nomDpto = "Tolima";
  } else if (codigoDpto == 32) {
    nomDpto = "Valle del Cauca";
  } else if (codigoDpto == 33) {
    nomDpto = "Vaupés";
  } else if (codigoDpto == 34) {
    nomDpto = "Vichada";
  } else {
    nomDpto = "No Disponible";
  }

  return nomDpto;
}

/*==================================================

FUNCION PARA LIMPIAR LOS CAMPOS AGREGADOS MANUALMENTE

==================================================*/

function vaciarCamposOfertaManual() {
  $("#producto").html("");

  $("#numCotizacion").val("");

  $("#valorTotal").val("");

  $("#valorRC").html("");

  $("#valorPerdidaTotal").val("");

  $("#valorPerdidaParcial").val("");

  $("#conductorElegido").val("");

  $("#servicioGrua").val("");
}

/* EDITAR COTIZACION */

const getManualOffer = (id) => {
  $.ajax({
    type: "POST",

    url: "src/obtenerOferta.php",

    dataType: "json",

    data: { id: id },

    success: function (data) {
      const D = document;

      const aseguradoras = D.querySelectorAll(".clsAseguradora");

      aseguradoras.forEach((e) => {
        if (e.value == data.Aseguradora) {
          e.selected = true;
        }
      });

      const producto = `<option value='${data.Producto}' selected>${data.Producto}</option>`;

      D.querySelector("#producto").innerHTML = producto;

      const rce = `<option value='${data.ValorRC}' selected>${data.ValorRC}</option>`;

      D.querySelector("#valorRC").innerHTML = rce;

      D.querySelector("#numCotizacion").value = data.NumCotizOferta;

      D.querySelector("#valorTotal").value = data.Prima;

      D.querySelector("#valorPerdidaTotal").value = data.PerdidaTotal;

      D.querySelector("#valorPerdidaParcial").value = data.PerdidaParcial;

      D.querySelector("#conductorElegido").value = data.ConductorElegido;

      D.querySelector("#servicioGrua").value = data.Grua;

      D.querySelector(".btnAgregar").innerHTML =
        '<button class="btn btn-success btn-block" id="btnEditarCotizacion">Editar Cotización</button>';

      $("#btnAgregarCotizacion").click(function () {
        agregarCotizacion();
      });

      D.querySelector("#btnEditarCotizacion").addEventListener("click", (e) => {
        editarCotizacionManual(data.id_oferta);
      });

      document.getElementById("formularioCotizacionManual").style.display =
        "block";

      menosVeh();

      masAgr();

      window.scrollTo(0, 0);
    },
  });
};

const editarCotizacionManual = (id) => {
  var placa = document.getElementById("txtPlacaVeh").value;

  var numIdentificacion = document.getElementById("numDocumentoID").value;

  var aseguradora = document.getElementById("aseguradora").value;

  var producto = document.getElementById("producto").value;

  var numCotizOferta = document.getElementById("numCotizacion").value;

  var prima = document.getElementById("valorTotal").value;

  var valorRC = document.getElementById("valorRC").value;

  var PT = document.getElementById("valorPerdidaTotal").value;

  var PT2 = document.getElementById("valorPerdidaTotal").value;

  var PP = document.getElementById("valorPerdidaParcial").value;

  var PP2 = document.getElementById("valorPerdidaParcial").value;

  var CE = document.getElementById("conductorElegido").value;

  var GR = document.getElementById("servicioGrua").value;

  if (
    aseguradora != "" &&
    producto != "" &&
    numCotizOferta != "" &&
    prima != "" &&
    valorRC != "" &&
    PT != "" &&
    PP != "" &&
    CE != "" &&
    GR != ""
  ) {
    var logo = logoOfertaManual(aseguradora);

    var primaFormat = formatNumber(prima);

    var valorRCFormat = valorRC;

    $.ajax({
      type: "POST",

      url: "src/editarOferta.php",

      dataType: "json",

      data: {
        placa: placa,

        numIdentificacion: numIdentificacion,

        aseguradora: aseguradora,

        valorPrima: primaFormat,

        producto: producto,

        valorRC: valorRCFormat,

        PT: PT,

        PP: PP,

        CE: CE,

        GR: GR,

        logo: logo,

        UrlPdf: "",

        id: id,
      },

      success: function (data) {
        document.location.reload(true);
      },
    });
  }
};

const deleteManualOffer = (id) => {
  swal
    .fire({
      title: "¿Deseas eliminar esta cotización?",
      icon: "warning", // Actualizado a "icon" en lugar de "type" en las versiones más recientes
      showCancelButton: true,
      confirmButtonColor: "#88d600",
      cancelButtonColor: "#000000",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Si, eliminar",
      reverseButtons: true,
      customClass: {
        popup: "custom-swal-popup-warning", // Clase personalizada para esta caja
      },
    })
    .then(function (result) {
      if (result.value) {
        $.ajax({
          type: "POST",
          url: "src/eliminarOferta.php",
          dataType: "json",
          data: { id: id },
          success: function (data) {
            swal
              .fire({
                icon: "success",
                title: "¡Cotización Eliminada Correctamente!",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
                customClass: {
                  popup: "custom-swal-popup-success", // Clase personalizada para esta caja
                },
              })
              .then((result) => {
                if (result.isConfirmed) {
                  console.log("entre aqui");
                  window.location.href =
                    "index.php?ruta=editar-cotizacion&idCotizacion=" +
                    idCotizacion;
                }
              });
          },
          error: function (xhr, status, error) {
            console.log(error);
          },
        });
      }
    });
};

$("#btnCancelar").click((e) => {
  document.getElementById("formularioCotizacionManual").style.display = "none";

  document.querySelector(".btnAgregar").innerHTML =
    '<button class="btn btn-info btn-block" id="btnAgregarCotizacion">Agregar Cotización</button>';

  $("#btnAgregarCotizacion").click(function () {
    agregarCotizacion();
  });

  menosAgr();

  vaciarCamposOfertaManual();

  const aseguradoras = document.querySelectorAll(".clsAseguradora");

  aseguradoras.forEach((e) => {
    if (e.value == "") {
      e.selected = true;
    }
  });
});

// FUNCION PARA OBTENER EL NOMBRE DEL MES

function obtenerNombreMes(numero) {
  var fecha = new Date();

  if (0 < numero && numero <= 12) {
    fecha.setMonth(numero - 1);

    return new Intl.DateTimeFormat("es-ES", { month: "long" }).format(fecha);
  }
}

function formatNumber(n) {
  n = String(n).replace(/\D/g, "");

  return n === "" ? n : Number(n).toLocaleString();
}

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
  document.getElementById("tipoDocumentoID").value == "2"
    ? (document.getElementById("DatosVehiculoPesados").style.display = "block")
    : (document.getElementById("DatosVehiculo").style.display = "block");
  // document.getElementById("DatosVehiculo").style.display = "block";

  document.getElementById("menosVehiculo").style.display = "block";

  document.getElementById("masVehiculo").style.display = "none";
}

// Minimiza el formulario Datos Vehiculo

function menosVeh() {
  // document.getElementById("DatosVehiculo").style.display = "none";
  document.getElementById("tipoDocumentoID").value == "2"
    ? (document.getElementById("DatosVehiculoPesados").style.display = "none")
    : (document.getElementById("DatosVehiculo").style.display = "none");

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
//parrillaCotizaciones
function menosAgr() {
  document.getElementById("DatosAgregarOferta").style.display = "none";

  document.getElementById("menosAgrOferta").style.display = "none";

  document.getElementById("masAgrOferta").style.display = "block";
}

// Maximiza el Formulario Agregar Oferta

function masRE() {
  document.getElementById("resumenCotizaciones").style.display = "block";

  document.getElementById("menosResOferta").style.display = "block";

  document.getElementById("masResOferta").style.display = "none";
}

// Minimiza el Formulario Agregar Oferta

function menosRE() {
  document.getElementById("resumenCotizaciones").style.display = "none";

  document.getElementById("menosResOferta").style.display = "none";

  document.getElementById("masResOferta").style.display = "block";
}

function menosRECot() {
  document.getElementById("resumenCotizaciones").style.display = "block";

  document.getElementById("menosResOferta").style.display = "block";

  document.getElementById("masResOferta").style.display = "none";
}

// Funcion loader screen

// function showLoadingPopup(cotType) {
//   let progress = 0;

//   Swal.fire({
//       title: `${cotType}`,
//       html: `<br>Cargando...<br><b id="progressText">0%</b> completado`,
//       backdrop: true,
//       allowOutsideClick: false,
//       allowEscapeKey: false,
//       allowEnterKey: false,
//       didOpen: () => {
//           document.body.style.overflow = "auto"; // Permitir scroll
//       },
//       willClose: () => {
//           document.body.style.overflow = ""; // Restaurar scroll normal
//       },
//       showConfirmButton: false // Ocultar botón mientras carga
//   });

//   // Simulación de progreso dinámico
//   let interval = setInterval(() => {
//       if (progress >= 100) {
//           clearInterval(interval);
//       } else {
//           progress += Math.ceil(Math.random() * 10);
//           document.getElementById("progressText").innerHTML = `${progress}%`;
//       }
//   }, 500);
// }

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
      var ciudadesVeh = `<option value="">Seleccionar Ciudad</option>`;

      if (data.mensaje) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "El departamento actual no cuenta con ciudades para asegurar",
        });
        document.getElementById(
          "ciudadCirculacion"
        ).innerHTML = `<option value="">No se encontraron registros</option>`;
        return;
      }

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
    error: function (xhr, status, error) {
      console.error("Error al consultar las ciudades:", error);
      Swal.fire({
        icon: "error",
        title: "Error al cargar las ciudades",
        text: "Por favor, inténtalo de nuevo más tarde.",
      });
    },
  });

  //}
}

function showCircularProgress(cotType, time, totalTransition) {
  let progress = 0;
  let totalDuration = totalTransition;
  let steps = totalDuration / time;
  let incrementPerStep = Math.floor(99 / steps);

  Swal.fire({
    title: `${cotType}`,
    html: `
          <div style="position: relative; margin: 0 auto;">
              <div style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                  <svg width="100" height="100" viewBox="0 0 100 100">
                      <circle cx="50" cy="50" r="40" stroke="#eee" stroke-width="10" fill="none"></circle>
                      <circle id="progressCircle" cx="50" cy="50" r="40" 
                          stroke="#88D600" stroke-width="10" fill="none" 
                          stroke-dasharray="251.2" stroke-dashoffset="251.2" 
                          stroke-linecap="round"
                          transform="rotate(-90 50 50)"></circle>
                  </svg>
                  <div id="progressText" style="
                      position: absolute; 
                      top: 50%; left: 50%;
                      transform: translate(-50%, -50%);
                      font-size: 18px; font-weight: bold;
                      width: 100px; text-align: center;
                  ">0%</div>
              </div>
              <div style="text-align: center; margin-top: 10px;">
                  <i class="fa fa-circle-exclamation" style="transform: rotate(180deg); font-size: 17px; color: #88D600;"></i>
                  Puedes ir revisando el avance mientras termina el proceso.
              </div>
          </div>
      `,
    backdrop: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    customClass: {
      popup: "popupLoader",
      container: "backdropLoader",
    },
    didOpen: () => {
      document.body.style.overflow = "auto"; // Permitir scroll
      let circle = document.getElementById("progressCircle");
      let text = document.getElementById("progressText");

      let interval = setInterval(() => {
        if (progress >= 99) {
          clearInterval(interval);
          return;
        }

        progress += incrementPerStep;
        if (progress > 99) progress = 99; // Evitar que pase de 99%

        let dashoffset = 251.2 * (1 - progress / 100);
        circle.style.strokeDashoffset = dashoffset;
        text.innerHTML = `${progress}%`;
      }, time);
    },
    willClose: () => {
      document.body.style.overflow = ""; // Restaurar scroll normal
    },
  });
}

// Simula una actualización del % (ajústalo según tu petición real)

function cotizarFinesaRetoma(ofertasCotizaciones) {
  if (typeof disableFilters === "function") {
    disableFilters();
  }
  showCircularProgress("Cotización Finesa en Proceso...", 2200, 90000);
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
      cuotas: 12, // cambiar a 12 cuotas Javier
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
      promisesFinesa.push(
        fetch(
          `https://grupoasistencia.com/motor_webservice/paymentInstallmentsFinesa${
            env == "qas" ? "_qas" : env == "dev" ? "_qas" : ""
          }`,
          // "https://grupoasistencia.com/motorTest/paymentInstallmentsFinesa",
          {
            method: "POST",
            headers: headers,
            body: JSON.stringify(data),
          }
        )
          .then((response) => response.json())
          .then((finesaData) => {
            // Sub Promesa para guardar la data en la BD con relacion a la cotizacion actual.u
            finesaData.producto = element.producto;
            finesaData.aseguradora = element.aseguradora;
            finesaData.id_cotizacion = idCotizacion;
            finesaData.identity = element.objFinesa;
            finesaData.cuotas = element.cuotas;
            return fetch(
              // "https://grupoasistencia.com/motorTest/saveDataQuotationsFinesa",
              `https://grupoasistencia.com/motor_webservice/saveDataQuotationsFinesa${
                env == "qas" ? "_qas" : env == "dev" ? "_qas" : ""
              }`,
              {
                method: "POST",
                headers: headers,
                body: JSON.stringify(finesaData),
              }
            ).then((dbResponse) => dbResponse.json());
          })
      );
      $("#filtersSection").prop("disabled", false);
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
      renderCards(resultNewRenderCardsFinesa);
      // Swal.close();
      Swal.fire({
        title: "¡Cotizacion Finesa finalizada a 12 cuotas!",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        backdrop: true, // Bloquea la interacción con el fondo
        allowOutsideClick: false, // Evita cerrar la alerta haciendo clic afuera
        allowEscapeKey: false, // Evita cerrar con la tecla "Escape"
        allowEnterKey: false, // Evita cerrar con "Enter"
        didOpen: () => {
          document.body.style.overflow = "auto"; // Habilita el scroll en el fondo
        },
        willClose: () => {
          document.body.style.overflow = ""; // Restaura el comportamiento normal
        },
      }).then(() => {
        $("#loaderOferta").html("");
        $("#loaderOfertaBox").css("display", "none");
      });
    })
    .catch((error) => {
      console.error("Error en las promesas: ", error);
    })
    .finally(() => {
      if (typeof enableFilters === "function") {
        enableFilters();
      }
    });
}

function cotizarFinesaMotosRetoma(ofertasCotizaciones) {
  showCircularProgress("Cotización Finesa en Proceso...", 500, 40000);
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
          `https://grupoasistencia.com/motor_webservice/paymentInstallmentsFinesa${
            env == "qas" ? "_qas" : env == "dev" ? "_qas" : ""
          }`,
          // "https://grupoasistencia.com/motorTest/paymentInstallmentsFinesa",
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
              `https://grupoasistencia.com/motor_webservice/saveDataQuotationsFinesa${
                env == "qas" ? "_qas" : env == "dev" ? "_qas" : ""
              }`,
              //"https://grupoasistencia.com/motorTest/saveDataQuotationsFinesa",
              {
                method: "POST",
                headers: headers,
                body: JSON.stringify(finesaData),
              }
            ).then((dbResponse) => dbResponse.json())
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
          title: "¡Cotizacion Finesa finalizada a 12 cuotas!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
        })
        .then(() => {
          $("#loaderOferta").html("");
          $("#loaderOfertaBox").css("display", "none");
          $("#loaderRecotOferta").html("");
          $("#loaderRecotOfertaBox").css("display", "none");
          renderCards(resultNewRenderCardsFinesa);
          // if (!cotizoFinesaMotos) {
          //   document.getElementById(
          //     "btnReCotizarFallidasMotos"
          //   ).disabled = false;
          //   cotizoFinesaMotos = true;
          // }
        });
    })
    .catch((error) => {
      console.error("Error en las promesas: ", error);
    })
    .finally(() => {
      //console.log(cotEnFinesaResponse);
      Swal.close();
    });
}

$("#btnCotizarFinesaRetoma").click(function () {
  $("#loaderOferta").html(
    '<img src="vistas/img/plantilla/loader-update.gif" width="34" height="34"><strong> Cotizando en Finesa...</strong>'
  );
  if (resultNewRenderCardsFinesa[0].Manual == 8) {
    cotizarFinesaMotosRetoma(cotizacionesFinesa);
  } else {
    cotizarFinesaRetoma(cotizacionesFinesa);
  }
  if (typeof countOfferts === "function") {
    countOfferts();
  }
  $(this).prop("disabled", true);
});

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

function idWithOutSpecialChars() {
  const numeroInput = document.getElementById("numDocumentoID").value;
  const idWOSpecialChars = numeroInput.replace(/[^0-9]/g, "");
  return idWOSpecialChars;
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
