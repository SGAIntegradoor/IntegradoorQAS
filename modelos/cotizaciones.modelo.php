<?php

require_once "conexion.php";

class ModeloCotizaciones
{

	/*=============================================
	MOSTRAR COTIZACIONES
	=============================================*/

	static public function mdlMostrarCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $tabla7, $item, $valor)
	{

		global $stmt;

		if ($item != null) {

			if ($item == 'id_cotizacion') {

				$stmt = Conexion::conectar()->prepare(
					"SELECT * FROM $tabla, $tabla2
				WHERE $tabla.id_cliente = $tabla2.id_cliente AND $tabla.id_cotizacion = $valor"
				);
				$stmt->execute();

				$response = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($response['id_tipo_documento'] == "2") {
					$stmt = Conexion::conectar()->prepare(
						"SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $tabla7 
															WHERE $tabla.id_cliente = $tabla2.id_cliente AND $tabla.id_usuario = $tabla5.id_usuario 
															AND $tabla.cot_ciudad = $tabla6.Codigo AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
															AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND $tabla.id_cotizacion = :$item AND $tabla5.id_Intermediario = :idIntermediario AND $tabla7.id_cliente_asociado = $tabla2.id_cliente"
					);
				} else {
					$stmt = Conexion::conectar()->prepare(
						"SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6
															WHERE $tabla.id_cliente = $tabla2.id_cliente AND $tabla.id_usuario = $tabla5.id_usuario 
															AND $tabla.cot_ciudad = $tabla6.Codigo AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
															AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND $tabla.id_cotizacion = :$item AND $tabla5.id_Intermediario = :idIntermediario"
					);
				}

				$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
				$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

				$stmt->execute();

				return $stmt->fetch(PDO::FETCH_ASSOC);
			}
		}

		$stmt->closeCursor();

		$stmt = null;
	}


	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS"
	=============================================*/

	static public function mdlMostrarCotizaOfertas($tabla, $item, $valor)
	{

		if ($item != null) {

			if ($item == 'id_cotizacion') {

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $tabla.id_cotizacion = :$item ORDER BY Aseguradora");

				$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
				$stmt->execute();

				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		$stmt->close();

		$stmt = null;
	}

	/*=============================================
	MOSTRAR "OFERTAS" CATEGORIA
	=============================================*/

	static public function mdlCategoriaOfertas($tabla, $item, $valor, $item2, $valor2)
	{
		// Validar los parámetros de entrada
		if ($item != null && $item2 != null) {

			if ($item == 'id_cotizacion' && $item2 == 'Categoria' && $valor2 == 'Todas') {
				// Consulta SQL corregida
				$stmt = Conexion::conectar()->prepare(
					"SELECT * FROM $tabla 
					 WHERE $tabla.$item = :id_cotizacion 
					 ORDER BY Aseguradora"
				);

				// Asignar parámetros
				$stmt->bindParam(":id_cotizacion", $valor, PDO::PARAM_STR);

				// Ejecutar y devolver resultados
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else if ($item == 'id_cotizacion' && $item2 == 'Categoria') {

				// Consulta SQL corregida
				// Consulta SQL corregida
				$stmt = Conexion::conectar()->prepare(
					"SELECT * FROM $tabla 
					 WHERE $tabla.$item = :id_cotizacion 
					 AND (
						 JSON_VALUE(Categoria, '$[0]') = :categoria 
						 OR JSON_VALUE(Categoria, '$[1]') = :categoria
					 )"
				);

				// Asignar parámetros
				$stmt->bindParam(":id_cotizacion", $valor, PDO::PARAM_STR);
				$stmt->bindParam(":categoria", $valor2, PDO::PARAM_STR);

				// Ejecutar y devolver resultados
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		// Retornar vacío si no se cumplen las condiciones
		return null;
	}

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS ASSISTCARD"
	=============================================*/

	static public function ctrMostrarCotizaOfertasAssistCard($tabla, $item, $valor)
	{

		if ($item != null) {

			if ($item == 'id_cotizacion') {

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = $valor");
				$stmt->execute();

				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		$stmt->close();

		$stmt = null;
	}

	/*=============================================
	MOSTRAR COTIZACIONES "OFERTAS HOGAR"
	=============================================*/

	static public function ctrMostrarCotizaOfertasHogar($tabla, $item, $valor)
	{

		if ($item != null) {

			if ($item == 'id_cotizacion') {
				var_dump($valor);
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = '$valor'");
				var_dump("SELECT * FROM $tabla WHERE $item = $valor");
				$stmt->execute();

				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		$stmt->close();

		$stmt = null;
	}




	static public function mdlShowQuotesAssistCard($tabla, $valor, $item)
	{


		if ($valor != null) {

			if ($item == 'assistcard_cots') {


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

				$stmt->execute();

				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		$stmt->closeCursor();

		$stmt = null;
	}

	static public function mdlShowQuoteAssistCard($tabla, $field, $id)
	{
		// Inicializa la variable $stmt
		$stmt = null;
		if ($id != null) {
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $field = :id");
			$stmt->bindParam(":id", $id, PDO::PARAM_STR);

			if ($stmt->execute()) {
				$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
				$stmt->closeCursor(); // Correctamente cerrando el cursor
				return $resultado;
			} else {
				return null; // Si la consulta falla, devuelve null
			}
		}

		return null; // En caso de que no se cumplan las condiciones, devuelve null
	}

	static public function mdlShowQuoteHogar($tabla, $tabla2, $field, $id)
	{
		// Inicializa la variable $stmt
		$stmt = null;
		if ($id != null) {

			$stmt = Conexion::conectar()->prepare("SELECT c.*, cl.id_tipo_documento, cl.cli_nombre, cl.cli_apellidos, cl.cli_num_documento, cl.cli_email, cl.cli_telefono FROM $tabla c JOIN $tabla2 cl ON cl.id_cliente = c.id_cliente WHERE $field = :id");
			$stmt->bindParam(":id", $id, PDO::PARAM_STR);

			if ($stmt->execute()) {
				$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
				$stmt->closeCursor(); // Correctamente cerrando el cursor
				return $resultado;
			} else {
				return null; // Si la consulta falla, devuelve null
			}
		}

		return null; // En caso de que no se cumplan las condiciones, devuelve null
	}

	static public function responseFormatted($responses)
	{
		// Inicializar arreglos para almacenar información procesada
		$asegurados = []; // Asegurados únicos
		$asegsRequestData = []; // Datos formateados de la cotización
		$idsAsegurados = []; // IDs de asegurados únicos para evitar duplicados
		$tomador = []; // Datos del tomador

		// Contador para asignar IDs únicos a los asegurados
		$cont = 1;

		// Procesar cada respuesta
		foreach ($responses as $row) {
			$idAsegurado = $row["id_asegurado"];

			// Verificar si el asegurado ya fue procesado
			if (!in_array($idAsegurado, $idsAsegurados)) {
				// Obtener y formatear todos los planes relacionados con este asegurado
				$plans = [];
				foreach ($responses as $plan) {
					if ($plan["id_asegurado"] === $idAsegurado) {
						$plans[] = [
							"plan_id" => $plan["id_plan"],
							"anual" => $plan["anual_plan"],
							"mensual" => $plan["mensual_plan"],
							"semestral" => $plan["semestral_plan"],
							"trimestral" => $plan["trimestral_plan"],
							"nombre" => $plan["nombre_plan"],
							"tipo_cotizacion_id" => (int)$plan["tipo_cotizacion"],
						];
					}
				}

				// Dividir nombre y fecha de nacimiento
				$arrayFecha = explode("-", $row["fch_nac_asegurado"]);
				$arrayNombre = explode(" ", $row["nom_asegurado"], 2);

				// Crear objeto asegurado
				$asegurado = [
					"id" => $cont,
					"id_asegurado" => $idAsegurado,
					"nombre" => $arrayNombre[0] ?? "",
					"apellido" => $arrayNombre[1] ?? "",
					"edad" => $row["edad_asegurado"],
					"genero" => $row["genero_asegurado"],
					"numeroDocumento" => $row["cedula_asegurado"],
					"fechaNacimiento" => [
						"dia" => (int)$arrayFecha[2] ?? "",
						"mes" => (int)$arrayFecha[1] ?? "",
						"anio" => (int)$arrayFecha[0] ?? "",
					],
					"planes" => array_values($plans), // Reinicia los índices del array de planes
					"tipoDocumento" => $row["tipo_documento_asegurado"],
				];

				// Agregar asegurado al resultado
				$asegurados[] = $asegurado;
				$idsAsegurados[] = $idAsegurado; // Marcar ID como procesado
				$cont++;
			}
		}

		// Formatear datos del tomador
		$nombreCompletoTomador = explode(" ", $responses[0]["nombre_tomador"], 2);
		$tomador = [
			"nombre" => $nombreCompletoTomador[0] ?? "",
			"apellido" => $nombreCompletoTomador[1] ?? "",
			"cedula" => $responses[0]["id_tomador"],
			"tipoDocumento" => $responses[0]["tipo_documento"],
		];

		// Preparar datos de la cotización

		$aseguradorRequest = $asegurados;

		foreach ($aseguradorRequest as &$aseguradoReq) {
			unset($aseguradoReq["planes"]);
		}

		unset($aseguradoReq); // Romper la referencia después del foreach, es una buena práctica

		$asegsRequestData = [
			"asegurados" => $aseguradorRequest,
			"id_usuario" => $responses[0]["id_usuario"],
			"tipo_cotizacion" => $responses[0]["num_asegurados"] == 1 ? 1 : 2,
			"tomador" => $tomador,
		];

		// Retornar datos formateados
		return [
			"asegurados" => $asegurados,
			"success" => true,
			"requestData" => $asegsRequestData,
		];
	}

	static public function mdlShowQuoteSalud($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $field, $id)
	{
		// Inicializa la variable $stmt
		$stmt = null;
		if ($id != null) {
			$stmt = Conexion::conectar()->prepare("SELECT 
				*
			FROM 
				$tabla c
			INNER JOIN 
				$tabla2 t ON t.id_cotizacion = c.id_cotizacion
			INNER JOIN 
				$tabla3 a ON a.id_cotizacion = c.id_cotizacion
			INNER JOIN 
				$tabla4 p ON p.id_asegurado = a.id_asegurado
			INNER JOIN 
				$tabla5 us ON c.id_usuario = us.id_usuario
			WHERE 
				c.$field = :id");

			$stmt->bindParam(":id", $id, PDO::PARAM_STR);

			if ($stmt->execute()) {
				$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor(); // Correctamente cerrando el cursor
				return self::responseFormatted($resultado);
			} else {
				return null; // Si la consulta falla, devuelve null
			}
		}

		return null; // En caso de que no se cumplan las condiciones, devuelve null
	}

	/*=============================================
	ELIMINAR COTIZACIONES
	=============================================*/

	static public function mdlEliminarCotizaciones($tabla, $tabla2, $datos)
	{

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla2 WHERE $tabla2.id_cotizacion LIKE :id");
		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);
		$stmt->execute();

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $tabla.id_cotizacion LIKE :id");
		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);

		if ($stmt->execute()) {
			return "ok";
		} else {
			return "error";
		}

		$stmt->close();
		$stmt = null;
	}

	static public function mdlRangoFechasCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $fechaInicialCotizaciones, $fechaFinalCotizaciones)
	{
		$condicion = "";
		if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
			$condicion = "AND $tabla.id_usuario = :idUsuario";
		}
		if ($fechaInicialCotizaciones == null) {
			$fechaActual = new DateTime();
			// Obtener la fecha de inicio de mes
			$inicioMes = clone $fechaActual;
			$inicioMes->modify('first day of this month');
			$inicioMes = $inicioMes->format('Y-m-d');

			// Obtener la fecha de fin de mes
			$finMes = clone $fechaActual;
			$finMes->modify('first day of next month')->modify(-1);
			$finMes = $finMes->format('Y-m-d');

			$stmt = Conexion::conectar()->prepare("
				SELECT * FROM cotizaciones, clientes, tipos_documentos, estados_civiles, usuarios 
				WHERE cotizaciones.id_cliente = clientes.id_cliente 
					AND cotizaciones.id_usuario = usuarios.id_usuario 
					AND clientes.id_tipo_documento = tipos_documentos.id_tipo_documento 
					AND clientes.id_estado_civil = estados_civiles.id_estado_civil 
					AND cot_fch_cotizacion >= :fechaInicio AND cot_fch_cotizacion <= :fechaFin
					AND usuarios.id_Intermediario = :idIntermediario
					$condicion
			");

			$stmt->bindParam(":fechaInicio", $inicioMes, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFin", $finMes, PDO::PARAM_STR);
			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

			if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
			}

			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else if ($fechaInicialCotizaciones == $fechaFinalCotizaciones) {
			$stmt = Conexion::conectar()->prepare("
			SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 
			WHERE $tabla.id_cliente = $tabla2.id_cliente
				AND $tabla.id_usuario = $tabla5.id_usuario 
				AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
				AND $tabla2.id_estado_civil = $tabla4.id_estado_civil 
				AND cot_fch_cotizacion LIKE CONCAT('%', :fecha, '%') 
				AND usuarios.id_Intermediario = :idIntermediario
				$condicion
			");
			$stmt->bindParam(":fecha", $fechaFinalCotizaciones, PDO::PARAM_STR);
			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

			if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
			}

			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {

			$inicioMes = new DateTime($fechaInicialCotizaciones);
			$inicioMes = $inicioMes->format('Y-m-d');
			$finMes = new DateTime($fechaFinalCotizaciones);
			$finMes = $finMes->format('Y-m-d');

			if ($_SESSION['rol'] == 10) {
				$stmt = Conexion::conectar()->prepare("
				SELECT * FROM $tabla
				INNER JOIN $tabla2 ON $tabla.id_cliente = $tabla2.id_cliente
				INNER JOIN $tabla3 ON $tabla2.id_tipo_documento = $tabla3.id_tipo_documento
				INNER JOIN $tabla4 ON $tabla2.id_estado_civil = $tabla4.id_estado_civil
				INNER JOIN $tabla5 ON $tabla.id_usuario = $tabla5.id_usuario
				WHERE cot_fch_cotizacion >= :fechaInicial AND cot_fch_cotizacion <= :fechaFinal
				$condicion
				ORDER BY cot_fch_cotizacion DESC
			");
			} else {
				$stmt = Conexion::conectar()->prepare("
					SELECT * FROM $tabla
					INNER JOIN $tabla2 ON $tabla.id_cliente = $tabla2.id_cliente
					INNER JOIN $tabla3 ON $tabla2.id_tipo_documento = $tabla3.id_tipo_documento
					INNER JOIN $tabla4 ON $tabla2.id_estado_civil = $tabla4.id_estado_civil
					INNER JOIN $tabla5 ON $tabla.id_usuario = $tabla5.id_usuario
					WHERE cot_fch_cotizacion >= :fechaInicial AND cot_fch_cotizacion <= :fechaFinal
					AND $tabla5.id_Intermediario = :idIntermediario
					$condicion
					ORDER BY cot_fch_cotizacion DESC
				");
				$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);
			}

			$stmt->bindParam(":fechaInicial", $inicioMes, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFinal", $finMes, PDO::PARAM_STR);

			if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
			}

			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	static public function mdlRangoFechasCotizacionesAssistCard($tabla, $tabla5,  $fechaInicialCotizaciones, $fechaFinalCotizaciones)
	{
		$condicion = "";
		if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
			$condicion = "AND $tabla.id_usuario = :idUsuario";
		}
		if ($fechaInicialCotizaciones == null) {

			$fechaActual = new DateTime();
			// Obtener la fecha de inicio de mes
			$inicioMes = clone $fechaActual;
			$inicioMes->modify('first day of this month');
			$inicioMes = $inicioMes->format('Y-m-d');

			// Obtener la fecha de fin de mes
			$finMes = clone $fechaActual;
			$finMes->modify('first day of next month')->modify(-1);
			$finMes = $finMes->format('Y-m-d');

			$stmt = Conexion::conectar()->prepare("
				SELECT * FROM cotizaciones, clientes, tipos_documentos, estados_civiles, usuarios 
				WHERE cotizaciones.id_cliente = clientes.id_cliente 
					AND cotizaciones.id_usuario = usuarios.id_usuario 
					AND clientes.id_tipo_documento = tipos_documentos.id_tipo_documento 
					AND clientes.id_estado_civil = estados_civiles.id_estado_civil 
					AND cot_fch_cotizacion >= :fechaInicio AND cot_fch_cotizacion <= :fechaFin
					AND usuarios.id_Intermediario = :idIntermediario
					$condicion
			");

			$stmt->bindParam(":fechaInicio", $inicioMes, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFin", $finMes, PDO::PARAM_STR);
			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

			if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
			}

			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {

			$inicioMes = new DateTime($fechaInicialCotizaciones);
			$inicioMes = $inicioMes->format('Y-m-d');
			$finMes = new DateTime($fechaFinalCotizaciones);
			if ($finMes->format('t') == $finMes->format('d')) {
				// Si es el último día del mes, ajustar al primer día del siguiente mes
				$finMes->modify('first day of next month');
			} else {
				// Si no, simplemente agregar un día
				$finMes->modify('+1 day');
			}

			$finMes = $finMes->format('Y-m-d');

			if ($_SESSION['rol'] == 10) {
				$stmt = Conexion::conectar()->prepare("
				SELECT c.id_cotizacion, c.fecha_cot, c.fch_nacimiento, c.lugar_origen, c.lugar_destino, c.nom_prospecto, c.fch_salida, c.fch_regreso, c.modalidad_cot, us.usu_nombre, us.usu_apellido, c.numero_pasajeros FROM $tabla c
				INNER JOIN $tabla5 us ON c.id_usuario = us.id_usuario
				WHERE c.fecha_cot >= :fechaInicial AND c.fecha_cot <= :fechaFinal
				ORDER BY c.fecha_cot DESC

				");
				// var_dump("
				// SELECT c.id_cotizacion, c.fecha_cot, c.fch_nacimiento, c.lugar_origen, c.lugar_destino, c.nom_prospecto, c.fch_salida, c.fch_regreso, c.modalidad_cot, us.usu_nombre, us.usu_apellido, c.numero_pasajeros FROM $tabla c
				// INNER JOIN $tabla5 us ON c.id_usuario = us.id_usuario
				// WHERE c.fecha_cot >= :fechaInicial AND c.fecha_cot <= :fechaFinal
				// ORDER BY c.fecha_cot DESC");
				// die();
			} else {
				$stmt = Conexion::conectar()->prepare("
					SELECT * FROM $tabla
					INNER JOIN $tabla5 ON $tabla.id_usuario = $tabla5.id_usuario
					WHERE fecha_cot >= :fechaInicial AND fecha_cot <= :fechaFinal
					AND $tabla5.id_Intermediario = :idIntermediario
					$condicion
					ORDER BY fecha_cot DESC
				");
				$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);
			}

			$stmt->bindParam(":fechaInicial", $inicioMes, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFinal", $finMes, PDO::PARAM_STR);

			if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
			}

			$stmt->execute();
			//echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	static public function mdlRangoFechasCotizacionesSalud($tabla, $tabla2, $tabla3, $tabla4, $tabla5,  $fechaInicialCotizaciones, $fechaFinalCotizaciones)
	{
		$condicion = "";
		if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
			$condicion = "AND $tabla.id_usuario = :idUsuario";
		}
		if ($fechaInicialCotizaciones == null) {
			$fechaActual = new DateTime();
			// Obtener la fecha de inicio de mes
			$inicioMes = clone $fechaActual;
			$inicioMes->modify('first day of this month');
			$inicioMes = $inicioMes->format('Y-m-d');

			// Obtener la fecha de fin de mes
			$finMes = clone $fechaActual;
			$finMes->modify('first day of next month')->modify(-1);
			$finMes = $finMes->format('Y-m-d');

			$stmt = Conexion::conectar()->prepare("
				SELECT 
				*
				FROM 
					$tabla c
				INNER JOIN 
					$tabla3 a ON a.id_cotizacion = c.id_cotizacion
				INNER JOIN 
					$tabla2 t ON t.id_cotizacion = c.id_cotizacion
				INNER JOIN 
					$tabla5 us ON us.id_usuario = c.id_usuario
				WHERE 
					c.fecha_cotizacion BETWEEN :fechaInicio AND :fechaFin 
				$condicion
				GROUP BY c.id_cotizacion;
			");

			$stmt->bindParam(":fechaInicio", $inicioMes, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFin", $finMes, PDO::PARAM_STR);
			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

			if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
			}

			$stmt->execute();

			var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
			die();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$inicioMes = new DateTime($fechaInicialCotizaciones);
			$inicioMes = $inicioMes->format('Y-m-d');
			$finMes = new DateTime($fechaFinalCotizaciones);

			if ($finMes->format('t') == $finMes->format('d')) {
				// Si es el último día del mes, ajustar al primer día del siguiente mes
				$finMes->modify('first day of next month');
			} else {
				// Si no, simplemente agregar un día
				$finMes->modify('+1 day');
			}

			$finMes = $finMes->format('Y-m-d');

			if ($_SESSION['rol'] == 10 || $_SESSION['rol'] == 1 || $_SESSION['rol'] == 12) {
				$stmt = Conexion::conectar()->prepare("
					SELECT 
						*
					FROM 
						$tabla c
					INNER JOIN 
						$tabla3 a ON a.id_cotizacion = c.id_cotizacion
					INNER JOIN 
						$tabla2 t ON t.id_cotizacion = c.id_cotizacion
					INNER JOIN 
						$tabla5 us ON us.id_usuario = c.id_usuario
					WHERE 
						c.fecha_cotizacion BETWEEN :fechaInicial AND :fechaFinal 
					GROUP BY c.id_cotizacion;
				");
			} else {
				$stmt = Conexion::conectar()->prepare("
					SELECT 
						*
					FROM 
						cotizaciones_salud c
					INNER JOIN 
						asegurados_cotizaciones_salud a ON a.id_cotizacion = c.id_cotizacion
					INNER JOIN 
						tomadores_cotizaciones_salud t ON t.id_cotizacion = c.id_cotizacion
					INNER JOIN
						usuarios us ON us.id_usuario = c.id_usuario
					WHERE 
						c.fecha_cotizacion BETWEEN :fechaInicial AND :fechaFinal
						AND us.id_Intermediario = :idIntermediario
						AND c.id_usuario = :idUsuario;
				");
			}

			// Enlazar parámetros comunes
			$stmt->bindParam(":fechaInicial", $inicioMes, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFinal", $finMes, PDO::PARAM_STR);
			// Enlazar solo si aplica
			if ($_SESSION['rol'] != 10 && $_SESSION['rol'] != 1 && $_SESSION['rol'] != 12) {
				$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);
				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
			}

			// if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
			// }


			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}


	static public function mdlGetDataLastRegisters($fechaInicialCotizaciones, $fechaFinalCotizaciones, $condicion = null)
	{
		$condicion = "";
		if (isset($_GET["fechaInicialCotizaciones"]) && isset($_GET["fechaFinalCotizaciones"])) {
			$fechaFinalCotizaciones = date('Y-m-d', strtotime($fechaFinalCotizaciones . ' +1 day'));
			$stmt = Conexion::conectar()->prepare("
				SELECT * FROM cotizaciones, clientes, tipos_documentos, estados_civiles, usuarios 
				WHERE cotizaciones.id_cliente = clientes.id_cliente 
					AND cotizaciones.id_usuario = usuarios.id_usuario 
					AND clientes.id_tipo_documento = tipos_documentos.id_tipo_documento 
					AND clientes.id_estado_civil = estados_civiles.id_estado_civil 
					AND usuarios.id_Intermediario = :idIntermediario
					AND cot_fch_cotizacion BETWEEN :fechaInicio AND :fechaFin
					ORDER BY id_cotizacion ASC");
			$stmt->bindParam(":fechaInicio", $fechaInicialCotizaciones, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFin", $fechaFinalCotizaciones, PDO::PARAM_STR);
			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);
		} else if (!isset($_GET["fechaInicialCotizaciones"]) && !isset($_GET["fechaFinalCotizaciones"]) && $condicion == "") {
			$stmt = Conexion::conectar()->prepare("
				SELECT * FROM cotizaciones, clientes, tipos_documentos, estados_civiles, usuarios 
				WHERE cotizaciones.id_cliente = clientes.id_cliente 
					AND cotizaciones.id_usuario = usuarios.id_usuario 
					AND clientes.id_tipo_documento = tipos_documentos.id_tipo_documento 
					AND clientes.id_estado_civil = estados_civiles.id_estado_civil 
					AND usuarios.id_Intermediario = :idIntermediario");
			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);
		}

		if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
			$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
		}

		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
