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

	// static public function mdlMostrarCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6, $item, $valor) {

	// 	var_dump($item);
	// 	die();

	// 	if ($item != null) {
	// 		if ($item == 'id_cotizacion') {
	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5, $tabla6 
	// 			WHERE $tabla.id_cliente = $tabla2.id_cliente 
	// 			AND $tabla.id_usuario = $tabla5.id_usuario 
	// 			AND $tabla.cot_ciudad = $tabla6.Codigo 
	// 			AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 			AND $tabla2.id_estado_civil = $tabla4.id_estado_civil 
	// 			AND $tabla.id_cotizacion = :$item 
	// 			AND $tabla5.id_Intermediario = :idIntermediario
	// 			AND $tabla.cot_fch_cotizacion >= '2023-01-01'");

	// 			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
	// 			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

	// 			$stmt->execute();

	// 			return $stmt->fetch(PDO::FETCH_ASSOC);
	// 		}
	// 	}

	// 	$stmt->close();
	// 	$stmt = null;
	// }



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
	ELIMINAR COTIZACIONES
	=============================================*/

	static public function mdlEliminarCotizaciones($tabla, $tabla2, $datos)
	{

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla2 WHERE $tabla2.id_cotizacion LIKE :id");
		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);
		$stmt->execute();

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $tabla.id_cotizacion LIKE :id");
		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);

		// if($stmt -> execute()){
		// 	$stmt -> close(); // Aquí cerramos el statement antes de retornar
		// 	$stmt = null;    // También establecemos el statement a null
		// 	return "ok";
		// }else{
		// 	$stmt -> close(); // Igualmente cerramos el statement antes de retornar
		// 	$stmt = null;    // Y establecemos el statement a null
		// 	return "error";    
		// }


		if ($stmt->execute()) {
			return "ok";
		} else {
			return "error";
		}

		$stmt->close();
		$stmt = null;
	}

	/*=============================================
	RANGO FECHAS COTIZACIONES
	=============================================*/

	// 	static public function mdlRangoFechasCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5,$tabla6, $fechaInicialCotizaciones, $fechaFinalCotizaciones){

	// 		$condicion = "";
	// 		if($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x"){ $condicion = "AND $tabla.id_usuario = :idUsuario"; }

	// 		if($fechaInicialCotizaciones == null){


	// 			$anoActual = date("Y"); // Obtener el año actual
	// 			$mesActual = date("m"); // Obtener el mes actual

	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente 
	// 			AND $tabla.id_usuario = $tabla5.id_usuario 
	// 			AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 			AND $tabla2.id_estado_civil = $tabla4.id_estado_civil 
	// 			AND YEAR($tabla.cot_fch_cotizacion) = :anoActual 
	// 			AND MONTH($tabla.cot_fch_cotizacion) >= :mesInicio 
	// 			AND MONTH($tabla.cot_fch_cotizacion) <= :mesFin
	// 			AND usuarios.id_Intermediario = :idIntermediario $condicion");

	// 			// Calcular el mes de inicio hace tres meses
	// 			$mesInicio = ($mesActual - 2) <= 0 ? 12 + ($mesActual - 2) : $mesActual - 2;

	// 			// Calcular el mes de fin (mes actual)
	// 			$mesFin = $mesActual;

	// 			$stmt->bindParam(":anoActual", $anoActual, PDO::PARAM_INT);
	// 			$stmt->bindParam(":mesInicio", $mesInicio, PDO::PARAM_INT);
	// 			$stmt->bindParam(":mesFin", $mesFin, PDO::PARAM_INT);
	// 			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

	// 			if ($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x") {
	// 				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);
	// 			}

	// 			$stmt->execute();

	// 			return $stmt->fetchAll(PDO::FETCH_ASSOC);
	// 			print_r($stmt);
	// 			die();

	// 		}else if($fechaInicialCotizaciones == $fechaFinalCotizaciones){

	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente
	// 													AND $tabla.id_usuario = $tabla5.id_usuario AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 													AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND cot_fch_cotizacion LIKE '%$fechaFinalCotizaciones%' AND usuarios.id_Intermediario = :idIntermediario $condicion");

	// 			$stmt -> bindParam(":cot_fch_cotizacion", $fechaFinalCotizaciones, PDO::PARAM_STR);
	// 			$stmt->bindParam(":idIntermediario",$_SESSION["intermediario"], PDO::PARAM_INT);

	// 			$stmt -> execute();

	// 			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	// 		}else{

	// 			$fechaActual = new DateTime();
	// 			$fechaActual ->add(new DateInterval("P1D"));
	// 			$fechaActualMasUno = $fechaActual->format("Y-m-d");

	// 			$fechaFinalCotizaciones2 = new DateTime($fechaFinalCotizaciones);
	// 			$fechaFinalCotizaciones2 ->add(new DateInterval("P1D"));
	// 			$fechaFinalCotizacionesMasUno = $fechaFinalCotizaciones2->format("Y-m-d");

	// 			if($fechaFinalCotizacionesMasUno == $fechaActualMasUno){

	// 				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente
	// 														AND $tabla.id_usuario = $tabla5.id_usuario AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 														AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND cot_fch_cotizacion 
	// 														BETWEEN '$fechaInicialCotizaciones' AND '$fechaFinalCotizacionesMasUno'AND usuarios.id_Intermediario = :idIntermediario $condicion");

	// 			}else{


	// 				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente
	// 														AND $tabla.id_usuario = $tabla5.id_usuario AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 														AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND cot_fch_cotizacion 
	// 														BETWEEN '$fechaInicialCotizaciones' AND '$fechaFinalCotizaciones' AND usuarios.id_Intermediario = :idIntermediario $condicion");

	// 			}
	// 			$stmt->bindParam(":idIntermediario",$_SESSION["intermediario"], PDO::PARAM_INT);
	// 			$stmt -> execute();

	// 			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	// 		}

	// 	}

	//     static public function mdlRangoFechasCotizaciones($tabla, $tabla2, $tabla3, $tabla4, $tabla5,$tabla6, $fechaInicialCotizaciones, $fechaFinalCotizaciones){

	// 		$condicion = "";
	// 		if($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x"){ $condicion = "AND $tabla.id_usuario = :idUsuario"; }

	// 		if($fechaInicialCotizaciones == null){

	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente AND $tabla.id_usuario = $tabla5.id_usuario AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND usuarios.id_Intermediario = :idIntermediario $condicion ");

	// 			$stmt->bindParam(":idIntermediario", $_SESSION["intermediario"], PDO::PARAM_INT);

	// 			if($_SESSION["permisos"]["Verlistadodecotizacionesdelaagencia"] != "x"){ 
	// 				$stmt->bindParam(":idUsuario", $_SESSION["idUsuario"], PDO::PARAM_INT);


	// 			}


	// 			$stmt -> execute();

	// 			return $stmt -> fetchAll(PDO::FETCH_ASSOC);
	// 			print_r($stmt);
	// 			die();

	// 		}else if($fechaInicialCotizaciones == $fechaFinalCotizaciones){

	// 			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente
	// 													AND $tabla.id_usuario = $tabla5.id_usuario AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 													AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND cot_fch_cotizacion LIKE '%$fechaFinalCotizaciones%' AND usuarios.id_Intermediario = :idIntermediario $condicion");

	// 			$stmt -> bindParam(":cot_fch_cotizacion", $fechaFinalCotizaciones, PDO::PARAM_STR);
	// 			$stmt->bindParam(":idIntermediario",$_SESSION["intermediario"], PDO::PARAM_INT);

	// 			$stmt -> execute();

	// 			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	// 		}else{

	// 			$fechaActual = new DateTime();
	// 			$fechaActual ->add(new DateInterval("P1D"));
	// 			$fechaActualMasUno = $fechaActual->format("Y-m-d");

	// 			$fechaFinalCotizaciones2 = new DateTime($fechaFinalCotizaciones);
	// 			$fechaFinalCotizaciones2 ->add(new DateInterval("P1D"));
	// 			$fechaFinalCotizacionesMasUno = $fechaFinalCotizaciones2->format("Y-m-d");

	// 			if($fechaFinalCotizacionesMasUno == $fechaActualMasUno){

	// 				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente
	// 														AND $tabla.id_usuario = $tabla5.id_usuario AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 														AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND cot_fch_cotizacion 
	// 														BETWEEN '$fechaInicialCotizaciones' AND '$fechaFinalCotizacionesMasUno'AND usuarios.id_Intermediario = :idIntermediario $condicion");

	// 			}else{


	// 				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla, $tabla2, $tabla3, $tabla4, $tabla5 WHERE $tabla.id_cliente = $tabla2.id_cliente
	// 														AND $tabla.id_usuario = $tabla5.id_usuario AND $tabla2.id_tipo_documento = $tabla3.id_tipo_documento 
	// 														AND $tabla2.id_estado_civil = $tabla4.id_estado_civil AND cot_fch_cotizacion 
	// 														BETWEEN '$fechaInicialCotizaciones' AND '$fechaFinalCotizaciones' AND usuarios.id_Intermediario = :idIntermediario $condicion");

	// 			}
	// 			$stmt->bindParam(":idIntermediario",$_SESSION["intermediario"], PDO::PARAM_INT);
	// 			$stmt -> execute();

	// 			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	// 		}

	// 	}

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

			if($_SESSION['rol'] == 10){
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
