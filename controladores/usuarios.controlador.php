<?php

require_once __DIR__ . '../../modelos/usuarios.modelo.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

class ControladorUsuarios
{

	/*=============================================
				 INGRESO DE USUARIO
	=============================================*/

	static public function ctrIngresoUsuario()
	{

		if (isset($_POST["ingUsuario"])) {

			if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"])) {

				$encriptar = crypt($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

				$tabla = "usuarios";
				$tabla2 = "roles";
				$tabla3 = "intermediario";
				$tabla4 = "permisosintegradoor";

				date_default_timezone_set('America/Bogota');

				$fechaAct = date('Y-m-d H:i:s');
				$item = "usu_usuario";

				$valor = $_POST["ingUsuario"];

				$respuesta = ModeloUsuarios::mdlUsuariosLogin($tabla, $tabla2, $tabla3, $tabla4, $item, $valor);
				if ($respuesta["usu_usuario"] == $_POST["ingUsuario"] && $respuesta["usu_password"] === $encriptar) {
					if ($respuesta["usu_estado"] == 1) {
						if ($respuesta["fechaFin"] != null && ($fechaAct >= $respuesta["fechaFin"]) && (isset($respuesta['id_rol']) && $respuesta['id_rol'] == 20)) {
							echo '<script>
								Swal.fire({
									html:  `
									<div style="text-align: justify; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;">
										Hola, lamentamos comunicarte que este usuario ha sido inhabilitado.
										<br>
										<br>
										<strong>Si deseas volver a ingresar a la plataforma debes vincularte al Programa donde te daremos un usuario personalizado y permanente.</strong>
										<br><br>Si quieres hacer este proceso comunícate con el área encargada de vinculaciones de Grupo Asistencia al: 📱+573185127910 o vía 📧 mercadeo@grupoasistencia.com.
									</div>
									`,
									confirmButtonColor: "#88d600",
									width: "40%", 
									customClass: {
										container: "swal-container",
										title: "swal-title",
										confirmButton: "swal-confirm-button", 
									},
									confirmButtonText: "Cerrar",
								}).then(function () {
									window.location.href = "/login"; 
								});

								const swalContainer = document.querySelector(".swal-container");
								swalContainer.style.marginTop = "20px"; // Ajusta este valor según tu necesidad

								// Agrega estilos adicionales para pantallas móviles aquí
								if (window.innerWidth <= 768) {
									// Estilos para pantallas con un ancho máximo de 768px (ajusta según sea necesario)
									swalContainer.style.padding = "5px";
								}
							</script>
							
							<style>
								.swal-confirm-button {
									font-size: 15px !important; /* Aumenta el tamaño del botón */
									padding: 6px 15px; /* Ajusta el padding para hacer que el botón sea más grande */
								}
							</style>';
							die();
						} else if ($respuesta["fechaFin"] != null && ($fechaAct >= $respuesta["fechaFin"]) && (isset($respuesta['id_rol']) && $respuesta['id_rol'] == 19)) {
							echo '<script>
								Swal.fire({
									html:  `
									<div style="text-align: justify; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;">
										Hola, lamentamos comunicarte que este usuario ha sido inhabilitado.
										<br>
										<br>
										<strong>Si deseas volver a ingresar a la plataforma comunícate con el área de vinculaciones de Grupo Asistencia al: 📱+573185127910 o vía 📧 mercadeo@grupoasistencia.com.
									</div>
									`,
									confirmButtonColor: "#88d600",
									width: "40%", 
									customClass: {
										container: "swal-container",
										title: "swal-title",
										confirmButton: "swal-confirm-button", 
									},
									confirmButtonText: "Cerrar",
								}).then(function () {
									window.location.href = "/login"; 
								});

								const swalContainer = document.querySelector(".swal-container");
								swalContainer.style.marginTop = "20px"; // Ajusta este valor según tu necesidad

								// Agrega estilos adicionales para pantallas móviles aquí
								if (window.innerWidth <= 768) {
									// Estilos para pantallas con un ancho máximo de 768px (ajusta según sea necesario)
									swalContainer.style.padding = "5px";
								}
							</script>
							
							<style>
								.swal-confirm-button {
									font-size: 15px !important; /* Aumenta el tamaño del botón */
									padding: 6px 15px; /* Ajusta el padding para hacer que el botón sea más grande */
								}
							</style>';
							die();
						} else if ($respuesta["fechaFin"] != null && ($fechaAct >= $respuesta["fechaFin"])  && (isset($respuesta['id_rol']) && $respuesta['id_rol'] == 2)) {
							echo '<script>
								Swal.fire({
									html:  `
									<div style="text-align: justify; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;">
									Hola, lamentamos comunicarte que este usuario ha sido inhabilitado.
										<br>
										<br>
										<strong>Si deseas volver a ingresar a la plataforma comunícate con Strategico Tech al: 📱+573187664954 o vía 📧 proyectostrategico@gmail.com</strong>
									</div>
									`,
									confirmButtonColor: "#88d600",
									width: "40%", 
									customClass: {
										container: "swal-container",
										title: "swal-title",
										confirmButton: "swal-confirm-button", 
									},
									confirmButtonText: "Cerrar",
								}).then(function () {
									window.location.href = "login"; 
								});

								const swalContainer = document.querySelector(".swal-container");
								swalContainer.style.marginTop = "20px"; // Ajusta este valor según tu necesidad

								// Agrega estilos adicionales para pantallas móviles aquí
								if (window.innerWidth <= 768) {
									// Estilos para pantallas con un ancho máximo de 768px (ajusta según sea necesario)
									swalContainer.style.padding = "5px";
								}
							</script>
							
							<style>
								.swal-confirm-button {
									font-size: 15px !important; /* Aumenta el tamaño del botón */
									padding: 6px 15px; /* Ajusta el padding para hacer que el botón sea más grande */
								}
							</style>';
							die();
						}

						$_SESSION["iniciarSesion"] = "ok";
						$_SESSION['loggedIn'] = true;
						$_SESSION['showPopup'] = true;
						$_SESSION["idUsuario"] = $respuesta["id_usuario"];
						$_SESSION["nombre"] = $respuesta["usu_nombre"];
						$_SESSION["apellido"] = $respuesta["usu_apellido"];
						$_SESSION["usuario"] = $respuesta["usu_usuario"];
						$_SESSION["foto"] = $respuesta["usu_foto"];
						$_SESSION["imgPDF"] = $respuesta["usu_logo_pdf"];
						$_SESSION["rol"] = $respuesta["id_rol"];
						$_SESSION["intermediario"] = $respuesta["id_Intermediario"];
						$_SESSION["cotRestantes"] = $respuesta["numCotizaciones"];
						$_SESSION["fechaLimi"] = $respuesta["fechaFin"];
						$_SESSION["permisos"] = $respuesta;
                        session_write_close();
						/*=============================================
						REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN
						=============================================*/

						date_default_timezone_set('America/Bogota');

						$fecha = date('Y-m-d');
						$hora = date('H:i:s');

						$fechaActual = $fecha . ' ' . $hora;

						$item1 = "usu_ultimo_login";
						$valor1 = $fechaActual;

						$item2 = "id_usuario";
						$valor2 = $respuesta["id_usuario"];

						$ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2, null);
						
						$tablaPU = "usuarios";

						// $popUpLogIn = ModeloUsuarios::mdlPopUpLogIn($tablaPU, $respuesta["usu_documento"], 1);
		
						// if ($popUpLogIn["result"] == "1") {
						// 	$_SESSION["popUpLogIn"] = "logIn";
						// }

						if ($ultimoLogin == "ok") {
						/* ✅ Forzamos el guardado de la sesión antes de redirigir */
                            echo '<script>
                                window.location.href = "inicio"; // ✅ Redirigimos solo después de guardar la sesión
                            </script>';
						}
						exit();
						
					} elseif ($respuesta["id_rol"] == 19) {
						function esMovil()
						{
							// Obtener el agente de usuario del navegador
							$userAgent = $_SERVER['HTTP_USER_AGENT'];

							// Lista de cadenas de texto que indican dispositivos móviles
							$dispositivosMoviles = array(
								'iPhone',
								'iPad',
								'Android',
								'Windows Phone',
								'BlackBerry'
							);

							// Comprobar si el agente de usuario contiene alguna de las cadenas de texto de dispositivos móviles
							foreach ($dispositivosMoviles as $dispositivo) {
								if (stripos($userAgent, $dispositivo) !== false) {
									return true; // El usuario está en un dispositivo móvil
								}
							}
							return false; // El usuario no está en un dispositivo móvil
						}
						if (esMovil()) {
							echo '<script>
								Swal.fire({
									html:  `
									<div style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;">
										<strong>Hola</strong> 😔, lamentamos comunicarte, <strong>que por improductividad</strong>, tu usuario como aliado de Grupo Asistencia ha sido inhabilitado.
										<br><br> 
										<strong>Si deseas reactivarlo, debes realizar compromiso de producción</strong> y comunicarte con el área de vinculaciones de Grupo Asistencia al
										📱 <a href="https://wa.link/qkywo4">+573185127910</a> o vía 📧 <u>analistadeseguros@grupoasistencia.com</u>.
										<br><br>
										Si por el contrario, no estás interesado en vender seguros por medio de Grupo Asistencia como aliado, 👉🏽 <strong>pero si te interesa tener tu propia versión personalizada del software para generar cotizaciones y cuadros comparativos (incluyendo tu propio logo)</strong>, comunícate con nosotros, <strong>Strategico Technologies</strong>, desarrolladores de esta plataforma, para conocer acerca de los planes de pago, que inician desde los $1.950 pesos por placa cotizada.										<br><br><br>
										<strong>Strategico Technologies</strong>
										<br>
										<a href="https://wa.link/0d7fk9">+573187664954</a>
										<br>
										<u>proyectos@strategico.tech</u>
									</div>
									`,
									width: "90%", // Personaliza el ancho aquí (puedes usar porcentaje o píxeles)
									customClass: {
										container: "swal-container",
										title: "swal-title",
										confirmButton: "swal-confirm-button", // Clase personalizada para el botón de confirmación
									},
									confirmButtonText: "Cerrar",
								}).then(function () {
									window.location.href = ""; // Redirigir después de cerrar SweetAlert
								});

								const swalContainer = document.querySelector(".swal-container");
								swalContainer.style.marginTop = "20px"; // Ajusta este valor según tu necesidad

								// Agrega estilos adicionales para pantallas móviles aquí
								if (window.innerWidth <= 768) {
									// Estilos para pantallas con un ancho máximo de 768px (ajusta según sea necesario)
									swalContainer.style.padding = "5px";
								}
							</script>
							
							<style>
								.swal-confirm-button {
									font-size: 15px !important; /* Aumenta el tamaño del botón */
									padding: 6px 15px; /* Ajusta el padding para hacer que el botón sea más grande */
								}
							</style>';
						} else {
							echo '<script>
								Swal.fire({
									html:  `
										<div style="text-align: left;font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 4px; margin-bottom: 3px; margin-top: 4px">
											<strong>Hola</strong> 😔, lamentamos comunicarte, <strong>que por improductividad</strong>, tu usuario como aliado de Grupo Asistencia ha sido inhabilitado.
											<br><br> 
											<strong>Si deseas reactivarlo, debes realizar compromiso de producción</strong> y comunicarte con el área de vinculaciones de Grupo Asistencia al
											📱 <a href="https://wa.link/qkywo4">+573185127910</a> o vía 📧 <u>analistadeseguros@grupoasistencia.com</u>.
											<br><br>
											Si por el contrario, no estas interesado en vender seguros por medio de Grupo Asistencia como aliado,👉🏽<strong>pero si te interesa tener tu propia versión personalizada del software para generar cotizaciones y cuadros comparativos (incluyendo tu propio logo)</strong>, comunícate con nosotros, <strong>Strategico Technologies</strong>, desarrolladores de esta plataforma, para conocer acerca de los planes de pago, que inician desde los $1.950 pesos por placa cotizada.										<br><br><br>
											<strong>Strategico Technologies</strong>
											<br>
											<a href="https://wa.link/0d7fk9">+573187664954</a>
											<br>
											<u>proyectos@strategico.tech</u>
										</div>
								`,
									width: "44%", // Personaliza el ancho aquí (puedes usar porcentaje o píxeles)
									customClass: {
										container: "swal-container",
										title: "swal-title",
										confirmButton: "swal-confirm-button", // Clase personalizada para el botón de confirmación
									},
									confirmButtonText: "Cerrar",
									position: "-40px",
								}).then(function () {
									window.location.href = ""; // Redirigir después de cerrar SweetAlert
								});

								const swalContainer = document.querySelector(".swal-container");
								swalContainer.style.paddingTop = "100px"; // Ajusta este valor para moverlo hacia abajo

							</script>
							
							<style>
								.swal-confirm-button {
									margin-top: 2px; /* Ajusta el margen superior para reducir el espacio entre el botón y el texto */
									font-size: 14px !important; /* Aumenta el tamaño del botón */
									padding: 11px 30px; /* Ajusta el padding para hacer que el botón sea más grande */
								}
							</style>';
						}
					} else {
						echo '<br>
							<div class="alert alert-danger">Esta cuenta esta bloqueada. Indica otra cuenta o comunicate con tu administrador</div>';
					}
				} else {
					echo '<br>
									<div class="alert alert-danger">Usuario y/o Contraseña incorrecta. Vuelve a intentarlo o selecciona ¿Se te olvido la contraseña? para cambiarla</div>';
				}
			}
		}
	}

	/*=============================================
				REGISTRO DE USUARIO
	=============================================*/

	public static function ctrCrearUsuario()
	{

		if (isset($_POST['newUserTemp'])) {
			$tabla = "usuarios";
			$ruta = "";
			$encriptar = crypt($_POST["newPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

			$datos = array(
				"nombre" => $_POST["newName"],
				"apellido" => $_POST["newLastName"],
				"documento" => $_POST["newDocIdUser"],
				"usuario" => $_POST["newUserTemp"],
				"password" => $encriptar,
				"genero" => $_POST["newGender"],
				"rol" => $_POST["newRol"],
				"telefono" => $_POST["newPhone"],
				"email" => $_POST["newEmail"],
				"cargo" => $_POST["newCharge"],
				"maxCotizaciones" => $_POST["maxCotizaciones"],
				"CotizacionesTotales" => $_POST["cotizacionesTotales"],
				"intermediario" => $_POST["intermediario"],
				"fechaLimite" => $_POST["lifeTime"],
				"fechaNacimiento" => $_POST["bornDate"],
				"direccion" => $_POST["address"],
				"ciudad" => $_POST["city"],
				"tipoDocumento" => $_POST["typeDoc"],
				"foto" => $_POST['picture']
			);

			$respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);


			var_dump("usuario temporal", json_encode($_POST), json_encode($respuesta));
			die();
			if ($respuesta['result'] == "ok") {
				return array("result" => "Success");
			} else {

				return array("result" => "Error");
			}
		}


		if (isset($_POST["nuevoUsuario"])) {

			if (
				preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ &]+$/', $_POST["nuevoNombre"]) &&
				preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ &]+$/', $_POST["nuevoApellido"]) &&
				preg_match('/^[0-9]+$/', $_POST["nuevoDocIdUser"]) &&
				preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoUsuario"]) &&
				preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoPassword"]) &&
				preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) &&
				preg_match('/^[a-zA-Z0-9_\-\.~]{2,}@[a-zA-Z0-9_\-\.~]{2,}\.[a-zA-Z]{2,4}$/', $_POST["nuevoEmail"])
				//    preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCargo"])
			) {

				// Convierto el usuario a Minisculas
				$nuevoUsuario = strtolower($_POST["nuevoUsuario"]);

				/*=============================================
																						VALIDAR IMAGEN
																						=============================================*/

				$ruta = "";

				$tabla = "usuarios";

				$encriptar = crypt($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

				$datos = array(
					"nombre" => $_POST["nuevoNombre"],
					"apellido" => $_POST["nuevoApellido"],
					"documento" => $_POST["nuevoDocIdUser"],
					"usuario" => $nuevoUsuario,
					"password" => $encriptar,
					"genero" => $_POST["nuevoGenero"],
					"rol" => $_POST["nuevoRol"],
					"analista" => $_POST["nuevoAnalista"],
					"telefono" => $_POST["nuevoTelefono"],
					"email" => $_POST["nuevoEmail"],
					"cargo" => $_POST["nuevoCargo"],
					"maxCotizaciones" => 0,
					"CotizacionesTotales" => $_POST["cotizacionesTotalesEditar"],
					"intermediario" => $_POST["idIntermediario"],
					"fechaLimite" => $_POST["fecLim"],
					"fechaNacimiento" => $_POST["AgregfechNacimiento"],
					"direccion" => $_POST["AgregDireccion"],
					"ciudad" => $_POST["ingciudadCirculacion"],
					"tipoDocumento" => $_POST["agregarTipoDocumento"],
					"foto" => $ruta
				);
				$respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);
				if ($respuesta['result'] == "ok") {

					echo '<script>

					swal.fire({

						icon: "success",
						text: "¡El usuario ha sido guardado correctamente!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "usuarios";

						}

					});
				

					</script>';
				} else {

					echo '<script>

					swal.fire({

						icon: "error",
						title: "¡Algo ha salido mal!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "usuarios";

						}

					});
				

					</script>';
				}
			} else {

				echo '<script>

					validarFormulario(event){
					event.preventDefault();
					swal.fire({

						type: "error",
						text: "¡El usuario no puede ir vacío o llevar caracteres especiales!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "usuarios";

						}

					});
				}

				</script>';
			}
		}
	}

	/*=============================================
				FILTROS USUARIO
	=============================================*/

	static public function ctrMostrarUsuariosFilters($params)
	{

		$respuesta = ModeloUsuarios::mdlMostrarUsuariosFilters($params);

		return $respuesta;
	}

	/*=============================================
					  MOSTRAR USUARIO
					  =============================================*/

	static public function ctrMostrarUsuarios($item, $valor)
	{

		$tabla = "usuarios";
		$tabla2 = "roles";
		$tabla3 = "intermediario";

		$respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $tabla2, $tabla3, $item, $valor);

		return $respuesta;
	}

	/*=============================================
					  CHECKEAR ESTADO USUARIO
					  =============================================*/

	static public function ctrUserCheckState($valor)
	{

		$tabla = "usuarios";
		$item = "usu_documento";

		$respuesta = ModeloUsuarios::mdlUserCheckState($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
					  EDITAR USUARIO
					  =============================================*/

	static public function ctrEditarUsuario()
	{


		if (isset($_POST["editarUsuario"])) {

			if ($_SESSION["permisos"]["EditarUsuarioInvitado"] == "x") {
				if (
					preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
					preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarApellido"] &&
						preg_match('/^[0-9]+$/', $_POST["editarDocIdUser"])) &&
					preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"]) &&
					preg_match('/^[a-zA-Z0-9_\-\.~]{2,}@[a-zA-Z0-9_\-\.~]{2,}\.[a-zA-Z]{2,4}$/', $_POST["editarEmail"])
					//    preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCargo"])
				) {

					// Convierto el usuario a Minisculas
					$editarUsuario = strtolower($_POST["editarUsuario"]);

					/*=============================================
																							VALIDAR IMAGEN
																							=============================================*/

					$ruta = $_POST["fotoActual"];

					if (isset($_FILES["editarFoto"]["tmp_name"]) && !empty($_FILES["editarFoto"]["tmp_name"])) {

						list($ancho, $alto) = getimagesize($_FILES["editarFoto"]["tmp_name"]);

						$nuevoAncho = 500;
						$nuevoAlto = 500;



						/*=============================================
																												  CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
																												  =============================================*/

						$directorio = "vistas/img/usuarios/" . $editarUsuario . "/imgUser";

						/*=============================================
																												  PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
																												  =============================================*/

						if (!empty($_POST["fotoActual"]) && file_exists($_POST["fotoActual"])) {
							unlink($_POST["fotoActual"]);
						} else {
							if (!is_dir($directorio)) {
								mkdir($directorio, 0755, true); // El tercer parámetro true permite la creación de directorios anidados
							}
						}

						/*=============================================
																												  DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
																												  =============================================*/

						if ($_FILES["editarFoto"]["type"] == "image/jpeg") {

							/*=============================================
																																		GUARDAMOS LA IMAGEN EN EL DIRECTORIO
																																		=============================================*/

							$aleatorio = mt_rand(100, 999);

							$ruta = "vistas/img/usuarios/" . $editarUsuario . "/imgUser" . "/" . basename($_FILES['editarFoto']['name']);

							$origen = imagecreatefromjpeg($_FILES["editarFoto"]["tmp_name"]);

							$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

							imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

							imagejpeg($destino, $ruta);
						}

						if ($_FILES["editarFoto"]["type"] == "image/png") {

							/*=============================================
																																		GUARDAMOS LA IMAGEN EN EL DIRECTORIO
																																		=============================================*/

							$aleatorio = mt_rand(100, 999);

							$ruta = "vistas/img/usuarios/" . $editarUsuario . "/imgUser" . "/" . basename($_FILES['editarFoto']['name']);

							$origen = imagecreatefrompng($_FILES["editarFoto"]["tmp_name"]);

							$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

							imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

							imagepng($destino, $ruta);
						}
					}

					$tabla = "usuarios";

					$actualPassword = crypt($_POST["passwordActual"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
					$actualPassw = $_POST["passwordActual"];
					$actualIdUser = $_POST['idUsuEdit'];

					$checkPass = ModeloUsuarios::mdlCheckPassword($actualPassw, $actualIdUser);
					if (!$checkPass) {
						if (isset($_POST["ciudad2"]) && $_POST["ciudad2"] == NULL) {
							$datos = array(
								"id" => $_POST["idUsuEdit"],
								"nombre" => $_POST["editarNombre"],
								"apellido" => $_POST["editarApellido"],
								"documento" => $_POST["editarDocIdUser"],
								"tipoDocumento" => $_POST["editarTipoDocumento"],
								"usuario" => $_POST["editarUsuario"],
								"password" => $actualPassword,
								"genero" => $_POST["editarGenero"],
								"fechNacimiento" => $_POST["fechNacimiento"],
								"direccion" => $_POST["editarDireccion"],
								"rol" => $_POST["editarRol"],
								"telefono" => $_POST["editarTelefono"],
								"email" => $_POST["editarEmail"],
								"cargo" => $_POST["editarCargo"],
								"intermediario" => $_POST["idIntermediario2"],
								//"maxCotEdi" => $_POST["maxiCot"],
								"cotizacionesTotales" => $_POST["cotizacionesTotalesEditar"],
								"fechaLimEdi" => $_POST["fechaLimEdi"],
								"ciudad" => $_POST["codigoCiudadActual"],
								"analista" => $_POST['analista'],
								"foto" => $ruta
							);
						} else {
							$datos = array(
								"id" => $_POST["idUsuEdit"],
								"nombre" => $_POST["editarNombre"],
								"apellido" => $_POST["editarApellido"],
								"documento" => $_POST["editarDocIdUser"],
								"tipoDocumento" => $_POST["editarTipoDocumento"],
								"usuario" => $_POST["editarUsuario"],
								"password" => $actualPassword,
								"genero" => $_POST["editarGenero"],
								"fechNacimiento" => $_POST["fechNacimiento"],
								"direccion" => $_POST["editarDireccion"],
								"rol" => $_POST["editarRol"],
								"telefono" => $_POST["editarTelefono"],
								"email" => $_POST["editarEmail"],
								"cargo" => $_POST["editarCargo"],
								"intermediario" => $_POST["idIntermediario2"],
								//"maxCotEdi" => $_POST["maxiCot"],
								"cotizacionesTotales" => $_POST["cotizacionesTotales"],
								"fechaLimEdi" => $_POST["fechaLimEdi"],
								"ciudad" => $_POST["codigoCiudadActual"],
								"analista" => $_POST['analista'],
								"foto" => $ruta
							);
						}



						$respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);
						if ($respuesta == "ok") {

							echo '<script>
		
							swal.fire({
								  type: "success",
								  title: "El usuario ha sido editado correctamente",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
											if (result.value) {
												//window.location = "usuarios"
											}
										})
		
							</script>';
						} else if ($respuesta == "authError") {

							echo '<script>
		
							swal.fire({
								  icon: "error",
								  title: "No tiene permisos para ejecutar esta función, comunícate con el administrador del sistema",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
									if (result.value) {	
										window.location = "usuarios";	
									} else if (result.isDismissed) {
										window.location = "usuarios"
									}
								})
		
						  </script>';
						} else if ($respuesta == "Sin cambios") {

							echo '<script>
		
							swal.fire({
								  type: "warning",
								  title: "No se hicieron cambios en el usuario",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
									if (result.value) {	
										//window.location = "usuarios";	
									}
								})
						  </script>';
						} else {
							echo '<script>
								swal.fire({
								  type: "error",
								  title: "¡El nombre no puede ir vacío o llevar arcacteres especiales!",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
									if (result.value) {	
										//window.location = "usuarios";	
									}
								})
						  </script>';
						}
					} else {
						if (isset($_POST["ciudad2"])) {
							$datos = array(
								"id" => $_POST["idUsuEdit"],
								"nombre" => $_POST["editarNombre"],
								"apellido" => $_POST["editarApellido"],
								"documento" => $_POST["editarDocIdUser"],
								"tipoDocumento" => $_POST["editarTipoDocumento"],
								"usuario" => $_POST["editarUsuario"],
								//"password" => $actualPassword,
								"genero" => $_POST["editarGenero"],
								"fechNacimiento" => $_POST["fechNacimiento"],
								"direccion" => $_POST["editarDireccion"],
								"rol" => $_POST["editarRol"],
								"telefono" => $_POST["editarTelefono"],
								"email" => $_POST["editarEmail"],
								"cargo" => $_POST["editarCargo"],
								"intermediario" => $_POST["idIntermediario2"],
								//"maxCotEdi" => $_POST["maxiCot"],
								"cotizacionesTotales" => $_POST["cotizacionesTotales"],
								"fechaLimEdi" => $_POST["fechaLimEdi"],
								"ciudad" => $_POST["ciudad2"],
								"analista" => $_POST['analista'],
								"foto" => $ruta
							);
							// $datos["If1"] = "Es if 1";
							// $datos['Ciudadmala'] = $_POST["codigoCiudadActual"];
							// $datos['ciudadbuena'] = $_POST["ciudad2"];
							// echo json_encode($_POST);
							// echo json_encode($datos);
							// die();
						} else {
							$datos = array(
								"id" => $_POST["idUsuEdit"],
								"nombre" => $_POST["editarNombre"],
								"apellido" => $_POST["editarApellido"],
								"documento" => $_POST["editarDocIdUser"],
								"tipoDocumento" => $_POST["editarTipoDocumento"],
								"usuario" => $_POST["editarUsuario"],
								//"password" => $actualPassword,
								"genero" => $_POST["editarGenero"],
								"fechNacimiento" => $_POST["fechNacimiento"],
								"direccion" => $_POST["editarDireccion"],
								"rol" => $_POST["editarRol"],
								"telefono" => $_POST["editarTelefono"],
								"email" => $_POST["editarEmail"],
								"cargo" => $_POST["editarCargo"],
								"intermediario" => $_POST["idIntermediario2"],
								//"maxCotEdi" => $_POST["maxiCot"],
								"cotizacionesTotales" => $_POST["cotizacionesTotales"],
								"fechaLimEdi" => $_POST["fechaLimEdi"],
								"ciudad" => $_POST["codigoCiudadActual"],
								"analista" => $_POST['analista'],
								"foto" => $ruta
							);
						}

						$respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

						if ($respuesta == "ok") {

							echo '<script>
		
							swal.fire({
								icon: "success",
								title: "El usuario ha sido editado correctamente",
								showConfirmButton: true,
								confirmButtonText: "Cerrar"
								}).then(function(result) {
										  if (result.value) {	
										window.location = "usuarios";	
									} else if (result.isDismissed) {
										window.location = "usuarios"
									}
									  })
		
							</script>';
						} else if ($respuesta == "authError") {

							echo '<script>
		
							swal.fire({
								  icon: "error",
								  title: "No tiene permisos para ejecutar esta función, comunícate con el administrador del sistema",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
									if (result.value) {	
										window.location = "usuarios";	
									} else if (result.isDismissed) {
										window.location = "usuarios"
									}
								})
		
						  </script>';
						} else if ($respuesta == "Sin cambios") {

							echo '<script>
		
							swal.fire({
								  type: "warning",
								  title: "No se hicieron cambios en el usuario",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
									if (result.value) {	
										//window.location = "usuarios";	
									}
								})
		
						  </script>';

						}else {

							echo '<script>
		
							swal.fire({
								  icon: "warning",
								  title: "¡El nombre no puede ir vacío o llevar caracteres especiales!",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
									if (result.value) {	
										window.location = "usuarios";	
									} else if (result.isDismissed) {
										window.location = "usuarios"
									}
								})
		
						  </script>';
						}
					}
				}
			} else {
				echo '<script>
		
							swal.fire({
								  type: "error",
								  title: "No tienes permiso para editar usuarios",
								  showConfirmButton: true,
								  confirmButtonText: "Cerrar"
								  }).then(function(result) {
									if (result.value) {	
										//window.location = "usuarios";	
									}
								})
		
						  </script>';
			}
		}
	}
	/*=============================================
					  BORRAR USUARIO
	=============================================*/

	static public function ctrBorrarUsuario()
	{

		if (isset($_GET["idUsuario"])) {

			$tabla = "usuarios";
			$datos = $_GET["idUsuario"];

			if ($_GET["fotoUsuario"] != "") {

				// Convierto el usuario a Minisculas
				$usuario = strtolower($_GET["usuario"]);

				unlink($_GET["fotoUsuario"]);
				rmdir('vistas/img/usuarios/' . $usuario);
			}

			$respuesta = ModeloUsuarios::mdlBorrarUsuario($tabla, $datos);

			if ($respuesta == "ok") {

				echo '<script>

				swal.fire({
					  type: "success",
					  title: "El usuario ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result) {
								if (result.value) {

								window.location = "usuarios";

								}
							})

				</script>';
			}
		}
	}
}
