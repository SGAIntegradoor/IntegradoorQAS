<?php

require_once "conexion.php";

class ModeloCotizaciones
{

	/*=============================================
	MOSTRAR COTIZACIONES
	=============================================*/

	static public function mdlMostrarCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $item, $valor)
	{

		if ($item != null) {

			if ($item == 'id_cotizacion') {


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6 
														WHERE $tabla.id_cliente = $tabla2.id_cliente AND $tabla.id_usuario = $tabla5.id_usuario 
														AND $tabla.cot_ciudad = $tabla6.Codigo AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
														AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND $tabla.id_cotizacion = :$item AND $tabla5.id_Intermediario = :idIntermediario");

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

	static public function ctrMostrarCotizaOfertas($tabla, $item, $valor)
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


			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $field = :field");
			// var_dump($stmt->queryString);

			// die();
			$stmt->bindParam(":field", $id, PDO::PARAM_STR);

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

	static public function mdlRangoFechasCotizacionesAssistCard($tabla, $tabla5, $fechaInicialCotizaciones, $fechaFinalCotizaciones)
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
		}
		// else if ($fechaInicialCotizaciones == $fechaFinalCotizaciones) {
		// 	$stmt = Conexion::conectar()->prepare("
		// 	SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 
		// 	WHERE $tabla.id_cliente = $tabla2.id_cliente
		// 		AND $tabla.id_usuario = $tabla5.id_usuario 
		// 		AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
		// 		AND $tabla2.id_estado_civil = $tabla4.id_estado_civil 
		// 		AND cot_fch_cotizacion LIKE CONCAT('%', :fecha, '%') 
		// 		AND usuarios.id_Intermediario = :idIntermediario
		// 		$condicion
		// 	");
		// 	$stmt->bindParam(":fecha", $fechaFinalCotizaciones, PDO::PARAM_STR);
		// 	$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

		// 	if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
		// 		$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
		// 	}

		// 	$stmt->execute();

		// 	return $stmt->fetchAll(PDO::FETCH_ASSOC);
		// } 
		else {
			$inicioMes = new DateTime($fechaInicialCotizaciones);
			$inicioMes = $inicioMes->format('Y-m-d');
			$finMes = new DateTime($fechaFinalCotizaciones);
			$finMes = $finMes->format('Y-m-d');

			if ($_SESSION['rol'] == 10) {
				$stmt = Conexion::conectar()->prepare("
				SELECT c.id_cotizacion, c.fecha_cot, c.fch_nacimiento, c.lugar_origen, c.lugar_destino, c.nom_prospecto, c.fch_salida, c.fch_regreso, c.modalidad_cot, us.usu_nombre, us.usu_apellido, c.numero_pasajeros FROM $tabla c
				INNER JOIN $tabla5 us ON c.id_usuario = us.id_usuario
				WHERE c.fecha_cot >= :fechaInicial AND c.fecha_cot <= :fechaFinal
				ORDER BY c.fecha_cot DESC
			");
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



	static public function mdlGetDataLastRegisters($fechaInicialCotizaciones, $fechaFinalCotizaciones, $condicion = null)
	{
		$condicion = "";
		// if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
		// 	$condicion = "AND cotizaciones.id_usuario = :idUsuario";
		// }
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
