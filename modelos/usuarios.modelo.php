<?php

require_once "conexion.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ModeloUsuarios
{

	/*=============================================
								  MOSTRAR USUARIOS
								  =============================================*/

	// static public function mdlMostrarUsuarios($tabla, $tabla2, $tabla3, $item, $valor)
	// {
	// 	global $stmt;
	// 	if ($item != null) {

	// 		if ($item == 'id_usuario' || $item == 'usu_usuario' || $item == 'usu_documento') {

	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3 WHERE $tabla.id_rol = $tabla2.id_rol AND $item = :$item AND $tabla.id_Intermediario = $tabla3.id_Intermediario");

	// 			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

	// 			$stmt->execute();
	// 			$user = $stmt->fetch(PDO::FETCH_ASSOC);
	// 			if ($user) {
	// 				if($user['id_rol'] == "19" || $user['id_rol'] == 19){
	// 					$value = $user['usu_documento'];
	// 					$stmt2 = Conexion::conectar()->prepare("SELECT * FROM analistas_freelances WHERE id_usuario = :id_user");
	// 					$stmt2->bindParam(":id_user", $value, PDO::PARAM_STR);
	// 					$stmt2->execute();
	// 					$analista_comercial = $stmt2->fetch(PDO::FETCH_ASSOC);
	// 					if($analista_comercial){
	// 						$user["analista_comercial"] = $analista_comercial["nombre_analista"];
	// 						$user["id_analista_comercial"] = $analista_comercial["id_analista"];
	// 					}
	// 					return $user;
	// 				}
	// 			} else {
	// 				return $user;
	// 			}
	// 		}
	// 	} else {

	// 		if ($_SESSION["rol"] == 18 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1) {
	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2 WHERE $tabla.id_rol = $tabla2.id_rol ORDER BY $tabla.id_usuario ASC");

	// 			$stmt->execute();
	// 			return $stmt->fetchAll(PDO::FETCH_ASSOC);
	// 		} else {
	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2 WHERE $tabla.id_rol = $tabla2.id_rol AND id_intermediario =" . $_SESSION["intermediario"]);
	// 			$stmt->execute();
	// 			return $stmt->fetchAll(PDO::FETCH_ASSOC);
	// 		}
	// 	}

	// 	$stmt->close();

	// 	$stmt = null;
	// }

	static public function mdlMostrarUsuarios($tabla, $tabla2, $tabla3, $item, $valor)
	{
		if ($item != null) {
			if ($item == 'id_usuario' || $item == 'usu_usuario' || $item == 'usu_documento') {
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3 WHERE $tabla.id_rol = $tabla2.id_rol AND $item = :$item AND $tabla.id_Intermediario = $tabla3.id_Intermediario");

				$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($user) {
					if ($user['id_rol'] == "19" || $user['id_rol'] == 19) {
						$value = $user['usu_documento'];
						$stmt2 = Conexion::conectar()->prepare("SELECT * FROM analistas_freelances WHERE id_usuario = :id_user");
						$stmt2->bindParam(":id_user", $value, PDO::PARAM_STR);
						$stmt2->execute();
						$analista_comercial = $stmt2->fetch(PDO::FETCH_ASSOC);

						if ($analista_comercial) {
							$user["analista_comercial"] = $analista_comercial["nombre_analista"];
							$user["id_analista_comercial"] = $analista_comercial["id_analista"];
						}

						// Liberar recursos
						$stmt2 = null;
					}

					$stmt = null; // Liberar recursos del statement principal
					return $user;
				} else {
					$stmt = null;
					return false; // No se encontró usuario
				}
			}
		} else {
			if ($_SESSION["rol"] == 18 || $_SESSION["rol"] == 10 || $_SESSION["rol"] == 1) {
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2 WHERE $tabla.id_rol = $tabla2.id_rol ORDER BY $tabla.id_usuario ASC");
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$stmt = null; // Liberar recursos
				return $result;
			} else {
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2 WHERE $tabla.id_rol = $tabla2.id_rol AND id_intermediario =" . $_SESSION["intermediario"]);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$stmt = null; // Liberar recursos
				return $result;
			}
		}

		// Asegurarse de que el statement se libera en cualquier caso
		$stmt = null;
	}


	static public function mdlCheckPassword($actualPass, $idUser)
	{
		// Obtén el enlace de la base de datos desde la clase Conexion
		$conexion = Conexion::conectar();
		$response = false;

		// Prepara la consulta SQL utilizando una consulta preparada
		$stmt = $conexion->prepare("SELECT usu_password FROM usuarios WHERE id_usuario = :idUser");

		// Vincula el parámetro :idUser con el valor $idUser
		$stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);

		// Ejecuta la consulta preparada
		$stmt->execute();

		// Obtiene el resultado de la consulta
		$fila = $stmt->fetch(PDO::FETCH_ASSOC);

		// Verifica si se encontraron resultados
		if ($fila) {
			// Compara la contraseña obtenida de la base de datos con la contraseña actual
			if ($fila["usu_password"] == $actualPass) {
				$response = true;
			}
		}

		// Retorna el resultado de la comparación
		return $response;
	}
	/*=============================================
								  PERMISOS USUARIOS
								  =============================================*/

	static public function mdlUsuariosLogin($tabla, $tabla2, $tabla3, $tabla4, $item, $valor)
	{
		$tabla = "usuarios";
		$tabla2 = "roles";
		$tabla3 = "intermediario";
		$tabla4 = "permisosintegradoor";
		$tabla5 = "credenciales";
		$tabla6 = "credenciales_motos";
		$tabla7 = "credenciales_pesados";
		$tabla8 = "permisos_modulos";

		$stmt = Conexion::conectar()->prepare("
			SELECT *
			FROM $tabla
			JOIN $tabla3 ON $tabla.id_Intermediario = $tabla3.id_Intermediario
			JOIN $tabla2 ON $tabla.id_rol = $tabla2.id_rol
			JOIN $tabla4 ON $tabla.id_rol = $tabla4.idRol
			JOIN $tabla5 ON $tabla3.id_Intermediario = $tabla5.id_Intermediario
			JOIN $tabla6 ON $tabla3.id_Intermediario = $tabla6.id_Intermediario
			JOIN $tabla7 ON $tabla3.id_Intermediario = $tabla7.id_Intermediario
			LEFT JOIN $tabla8 ON $tabla8.cedula = $tabla.usu_documento
			WHERE $item = :$item
		");
		$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
		$stmt->execute();

		$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($resultado === false) {
			// Imprimir mensaje de error
			$errorInfo = $stmt->errorInfo();
			echo "Error: " . $errorInfo[1]; // El índice 2 contiene el mensaje de error
		} else {
			// Procesar el resultado
			// print_r($resultado);
		}

		// $stmt->close();
		$stmt = null;
		return $resultado;
	}

	/*=============================================
								  REGISTRO DE USUARIO
								  =============================================*/

	static public function mdlIngresarUsuario($tabla, $datos)
	{
		$stmt = Conexion::conectar()->prepare("SELECT usu_usuario FROM usuarios WHERE usu_usuario LIKE 'Invitado%' ORDER BY CAST(SUBSTRING(usu_usuario, 9) AS UNSIGNED) DESC LIMIT 1");
		// Ejecutar la consulta
		$stmt->execute();
		// Obtener el resultado
		$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($resultado) {
			// Extraer el número de usuario del nombre de usuario
			$lastUserNumber = (int)substr($resultado['usu_usuario'], 8);
			$nextUserNumber = $lastUserNumber + 1;
		} else {
			// No se encontraron usuarios en la serie, comenzar desde 0
			$nextUserNumber = 1;
		}

		$newUsername = 'Invitado' . $nextUserNumber;

		if ($datos['apellido'] == "SGA") {
			$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(usu_documento, usu_nombre, usu_apellido, usu_usuario, usu_password, usu_genero, usu_fch_nac, direccion, ciudades_id, tipos_documentos_id, usu_telefono, usu_email,
																	usu_cargo, usu_foto, usu_estado, id_rol, id_Intermediario, numCotizaciones, cotizacionesTotales, fechaFin)
																	VALUES (:documento, :nombre, :apellido, :usuario, :password, :genero, :fechaNacimiento, :direccion, :ciudad, :tipoDocumento, :telefono, :email, :cargo, :foto, 1, :rol, :intermediario, :maxCot, 20, :fechaLimite )");
		} else {
			$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(usu_documento, usu_nombre, usu_apellido, usu_usuario, usu_password, usu_genero, usu_fch_nac, direccion, ciudades_id, tipos_documentos_id, usu_telefono, usu_email,
																	usu_cargo, usu_foto, usu_estado, id_rol, id_Intermediario, numCotizaciones, cotizacionesTotales, fechaFin)
																	VALUES (:documento, :nombre, :apellido, :usuario, :password, :genero, :fechaNacimiento, :direccion, :ciudad, :tipoDocumento, :telefono, :email, :cargo, :foto, 1, :rol, :intermediario, :maxCot, :cotTotales, :fechaLimite )");
		}


		$valoresPermitidos = array('fechaNacimiento', 'fechaLimite');

		foreach ($valoresPermitidos as $field) {
			if (!isset($datos[$field]) || empty($datos[$field])) {
				$datos[$field] = null;
			} else {
				// Asegurar que la fecha esté en el formato correcto
				$datos[$field] = date("Y-m-d H:i:s", strtotime($datos[$field]));
			}
		}

		$user = $datos['apellido'] == "SGA" ? $newUsername : $datos['usuario'];

		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
		$stmt->bindParam(":usuario", $user, PDO::PARAM_STR);
		$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
		$stmt->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_INT);
		$stmt->bindParam(":tipoDocumento", $datos["tipoDocumento"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":cargo", $datos["cargo"], PDO::PARAM_STR);
		$stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
		$stmt->bindParam(":rol", $datos["rol"], PDO::PARAM_INT);
		$stmt->bindParam(":intermediario", $datos["intermediario"], PDO::PARAM_INT);
		$stmt->bindParam(":maxCot", $datos["maxCotizaciones"], PDO::PARAM_INT);
		$stmt->bindParam(":cotTotales", $datos["cotizacionesTotales"], PDO::PARAM_INT);
		$stmt->bindParam(":fechaLimite", $datos["fechaLimite"], PDO::PARAM_STR);

		// if (!$stmt->execute()) {
		// 	$errorInfo = $stmt->errorInfo();
		// 	echo "Error al ejecutar la consulta: " . $errorInfo[2];
		// 	// Opcionalmente, puedes mostrar información adicional sobre el error

		// 	echo "Código de error: " . $errorInfo[0];
		// }

		// echo '<script>

		// swal({

		// 	type: "success",
		// 	title: "' . $datos["intermediario"] . '",
		// 	showConfirmButton: true,
		// 	confirmButtonText: "Cerrar"

		// }).then(function(result){

		// 	if(result.value){

		// 		window.location = "usuarios";

		// 	}

		// });


		// </script>';

		if ($stmt->execute()) {
			if ($datos['rol'] == 19 && $datos['analista'] != "") {
				// echo json_encode($datos);
				// die();
				$stmtAnalistAsUser = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE usu_documento = :documento");
				$stmtAnalistAsUser->bindParam(":documento", $datos["analista"], PDO::PARAM_STR);
				$stmtAnalistAsUser->execute();
				$datosAnalista = $stmtAnalistAsUser->fetch(PDO::FETCH_ASSOC);

				$nombreCompleto = $datos["nombre"] . ' ' . $datos["apellido"];
				$nombreCompletoAnalista = $datosAnalista["usu_nombre"] . ' ' . $datosAnalista["usu_apellido"];

				$stmtAnalist = Conexion::conectar()->prepare("INSERT INTO analistas_freelances (id, id_usuario, nombre_completo_freelance, correo, nombre_analista, id_analista ) VALUES (null, :documento, :nombreCompleto, :correo, :nombreAnalista, :idAnalista)");
				$stmtAnalist->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
				$stmtAnalist->bindParam(":nombreCompleto", $nombreCompleto, PDO::PARAM_STR);
				$stmtAnalist->bindParam(":correo", $datos["email"], PDO::PARAM_STR);
				$stmtAnalist->bindParam(":nombreAnalista", $nombreCompletoAnalista, PDO::PARAM_STR);
				$stmtAnalist->bindParam(":idAnalista", $datos["analista"], PDO::PARAM_INT);
				$stmtAnalist->execute();
			}
			return array("result" => "ok", "detailedResponse" => "Se creo el usuario");
		} else {
			$errorInfo = $stmt->errorInfo();
			return array("result" => "error", "detailedResponse" => $errorInfo);
		}

		$stmt->close();

		$stmt = null;
	}

	/*=============================================
								  EDITAR USUARIO
								  =============================================*/

	static public function mdlEditarUsuario($tabla, $datos)
	{
		$permisoEditar = $_SESSION["permisos"]["Editarusuario"];

		if ($permisoEditar != "x") {
			return "authError";
		} else {
			$cont = 0;
			$analistPost = $datos["analista"];
			$resultadosUser = null;
			try {
				$idUsuario = $datos["id"];
				$idDocUser = $datos["documento"];
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla u LEFT JOIN `analistas_freelances` a ON a.id_usuario = :idUsuarioDoc WHERE u.id_usuario = :idUsuario");
				$stmt->bindParam(":idUsuarioDoc", $idDocUser, PDO::PARAM_INT);
				$stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
				$stmt->execute();
				$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$idAnalista = $resultados[0]['id_analista'];
				if (!empty($resultados)) {

					$stmtUser = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE usu_documento = :idUsuarioUser");
					$stmtUser->bindParam(":idUsuarioUser", $analistPost, PDO::PARAM_INT);

					$controlVar = $stmtUser->execute();

					if (!$controlVar) {
						return array("result" => "error", "detailedResponse" => "fallo la busqueda, o no hay datos del usuario");
					} else if ($controlVar != false) {
						$cont++;
					} else {
						$errorInfo = $stmtUser->errorInfo();
						echo "SQLSTATE error code: " . $errorInfo[0] . "\n";
						echo "Driver-specific error code: " . $errorInfo[1] . "\n";
						echo "Driver-specific error message: " . $errorInfo[2] . "\n";
					}
					$resultadosUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
					$nombreFreelance = $resultados[0]["usu_nombre"] . ' ' . $resultados[0]["usu_apellido"];
					$email = $resultados[0]["usu_email"];
					$document = $resultados[0]['usu_documento'];
					$user = $resultados[0]['usu_usuario'];
					$cotTot = $datos['cotizacionesTotales'];
					$apellidoAnalista = explode(" ", $resultadosUser["usu_apellido"], 2);
					$analistName = $resultadosUser["usu_nombre"] . ' ' . $apellidoAnalista[0];

					if ($analistPost != 1 && ($idAnalista == null || $idAnalista == "")) {
						$stmtAnalist = Conexion::conectar()->prepare("INSERT INTO analistas_freelances (id, id_usuario, nombre_completo_freelance, correo, nombre_analista, id_analista ) VALUES (null, '$document', '$nombreFreelance','$email', '$analistName', $analistPost)");

						if ($stmtAnalist->execute()) {
							$cont++;
						} else {
							$errorInfo = $stmtAnalist->errorInfo();
							echo "SQLSTATE error code: " . $errorInfo[0] . "\n";
							echo "Driver-specific error code: " . $errorInfo[1] . "\n";
							echo "Driver-specific error message: " . $errorInfo[2] . "\n";
						}
					} else if ($analistPost != 1 && ($idAnalista != null && $idAnalista != "") && $analistPost != $idAnalista) {
						$updateQueryAnalist = "UPDATE analistas_freelances SET 
												  nombre_analista = :nombre_analista,
												  id_analista = :id_analista WHERE id_usuario = $document";
						$stmtUpdate = Conexion::conectar()->prepare($updateQueryAnalist);
						$stmtUpdate->bindParam(":nombre_analista", $analistName, PDO::PARAM_STR);
						$stmtUpdate->bindParam(":id_analista", $analistPost, PDO::PARAM_INT);

						if ($stmtUpdate->execute()) {
							$cont++;
						} else {
							$errorInfo = $stmtUpdate->errorInfo();
							echo "SQLSTATE error code: " . $errorInfo[0] . "\n";
							echo "Driver-specific error code: " . $errorInfo[1] . "\n";
							echo "Driver-specific error message: " . $errorInfo[2] . "\n";
						}
					}


					$updateQuery = "UPDATE $tabla SET 
												  usu_documento = :documento,
												  tipos_documentos_id = :tipoDocumento,
												  usu_nombre = :nombre,
												  usu_apellido = :apellido,
												  usu_genero = :genero,
												  direccion = :direccion,
												  ciudades_id = :ciudad,
												  usu_telefono = :telefono,
												  usu_email = :email,
												  usu_cargo = :cargo,
												  usu_foto = :foto,
												  id_rol = :rol,
												  id_Intermediario = :intermediario,
												  fechaFin = :fechaLimEdi";

					if (isset($datos['password'])) {
						$updateQuery .= ", usu_password = :usu_password";
					}

					if (!empty($datos['fechNacimiento'])) {
						$updateQuery .= ", usu_fch_nac = :fechNacimiento";
					}

					if ($document == $user) {
						$updateQuery .= ", usu_usuario = :documento";
					}

					if (!empty($cotTot)) {
						$updateQuery .= ", cotizacionesTotales = :cotTotales";
					}


					$updateQuery .= " WHERE usu_usuario = :usuario";

					$stmt = Conexion::conectar()->prepare($updateQuery);

					$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
					$stmt->bindParam(":tipoDocumento", $datos["tipoDocumento"], PDO::PARAM_STR);
					$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
					$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
					$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
					if (isset($datos['password'])) {
						$stmt->bindParam(":usu_password", $datos["password"], PDO::PARAM_STR);
					}
					$stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
					if (!empty($datos['fechNacimiento'])) {
						$stmt->bindParam(":fechNacimiento", $datos["fechNacimiento"], PDO::PARAM_STR);
					}
					$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
					$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
					$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
					$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
					$stmt->bindParam(":cargo", $datos["cargo"], PDO::PARAM_STR);
					$stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
					$stmt->bindParam(":intermediario", $datos["intermediario"], PDO::PARAM_STR);
					if (!empty($cotTot)) {
						$stmt->bindParam(":cotTotales", $datos["cotizacionesTotales"], PDO::PARAM_STR);
					}
					$stmt->bindParam(":fechaLimEdi", $datos["fechaLimEdi"], PDO::PARAM_STR);
					$stmt->bindParam(":rol", $datos["rol"], PDO::PARAM_STR);


					// echo json_encode($datos);
					// die();

					if ($stmt->execute()) {
						// var_dump("Entre aqui");
						return "ok";
					} else {
						$errorInfo = $stmt->errorInfo();
						echo "SQLSTATE error code: " . $errorInfo[0] . "\n";
						echo "Driver-specific error code: " . $errorInfo[1] . "\n";
						echo "Driver-specific error message: " . $errorInfo[2] . "\n";
						return "error";
					}
				} else {
					echo "No se encontraron resultados para el usuario con ID $idUsuario.";
				}

				//$stmt->close();
				$stmt = null;
			} catch (PDOException $e) {
				echo "Error: " . $e->getMessage();
			}
		}
	}

	/*=============================================
								  ACTUALIZAR USUARIO
								  =============================================*/

	static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2, $usuarioBanner = null)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt->bindParam(":" . $item1, $valor1, PDO::PARAM_STR);
		$stmt->bindParam(":" . $item2, $valor2, PDO::PARAM_STR);

		if ($stmt->execute()) {
			$accion = "";

			if ($usuarioBanner != null) {
				if ($valor1 == "0" || $valor1 == 0) {
					$accion = "Bloqueo";
				} else {
					$accion = "Desbloqueo";
				}
				$stmt2 = Conexion::conectar()->prepare("INSERT INTO novedades_usuarios (id, id_usuario_bloqueado, id_usuario_bloqueo, accion, fecha_bloqueo) values (NULL, :valor2 ,:usuarioBanner, '$accion', current_timestamp())");
				$stmt2->bindParam(':valor2', $valor2, PDO::PARAM_INT);
				$stmt2->bindParam(':usuarioBanner', $usuarioBanner, PDO::PARAM_INT);
				if ($stmt2->execute()) {
					return "ok";
				}
			}
			return "ok";
		} else {

			return "error";
		}

		$stmt->close();

		$stmt = null;
	}

	/*=============================================
								  BORRAR USUARIO
								  =============================================*/

	static public function mdlBorrarUsuario($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_usuario = :id");

		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}

		$stmt->close();

		$stmt = null;
	}
	/*=============================================
								  CHECK DE ESTADO DE USUARIO EN SESION
								  =============================================*/

	static public function mdlUserCheckState($tabla, $item, $valor)
	{
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = $valor");
		//$stmt->bindParam(":valor", $valor, PDO::PARAM_STR); // Cambio aquí
		//$stmt->execute();
		//$resultados = $stmt->fetch(PDO::FETCH_ASSOC);
		//var_dump(json_encode($resultados));
		if ($stmt->execute()) {
			$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
			return $resultado;
		} else {
			return "error";
		}
	}
}
