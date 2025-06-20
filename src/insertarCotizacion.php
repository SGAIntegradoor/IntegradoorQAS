<?php
session_start();

/* Conectar a la base de datos*/
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$placa = $_POST['placa'];
$esCeroKm = $_POST['esCeroKm'];
$idCliente = $_POST['idCliente'];
$idRepCliente = $_POST['idCliente'];
$tipoDocumento = $_POST['tipoDocumento'];
$numIdentificacion = $_POST['numIdentificacion'];
$Nombre = $_POST["Nombre"];
$Apellido = $_POST["Apellido"];
$FechaNacimiento = !empty($_POST["FechaNacimiento"]) ? $_POST["FechaNacimiento"] : null;
$Genero = $_POST["Genero"];
$EstCivil = $_POST["EstadoCivil"];
$Celular = $_POST["Celular"];
$Correo = $_POST["Correo"];

//! Se usan solo para cuando llega un NIT en el tipo de documento (Valor: "2") START

if ($tipoDocumento == 2) {

	$razonSocial = $_POST["razonSocial"];
	$digitoVerif = $_POST["digitoVerif"];
	$tipoDocRep = $_POST["tipoDocRep"];
	$numDocRep = $_POST["numDocRep"];
	$nombresRep = $_POST["nombresRep"];
	$apellidosRep = $_POST["apellidosRep"];
	$fechaNacimientoRep = $_POST["fechaNacimientoRep"];
	$generoRep = $_POST["generoRep"];
	$estCivRep = $_POST["estCivRep"];
	$correoRep = $_POST["correoRep"];
	$celRep = $_POST["celRep"];
}

//! Se usan solo para cuando llega un NIT en el tipo de documento (Valor: "2") END

$Direccion = $_POST["direccionAseg"];
$CodigoClase = $_POST["CodigoClase"];
$Clase = $_POST["Clase"];
$Marca = $_POST['Marca'];
$Modelo = $_POST['Modelo'];
$Linea = $_POST['Linea'];
$Fasecolda = $_POST['Fasecolda'];
$ValorAsegurado = $_POST["ValorAsegurado"];
$tipoUsoVehiculo = $_POST["tipoUsoVehiculo"] ?? "Taxi";
$numeroPasajeros = $_POST["numeroPasajeros"] ?? 0;
$tipoServicio = $_POST["tipoServicio"] ?? 19;
$numToneladas = $_POST["numToneladas"] ?? 0;
$Departamento = $_POST["Departamento"];
$Ciudad = $_POST["Ciudad"];
$benefOneroso = $_POST["benefOneroso"];
$idCotizacion = $_POST["idCotizacion"];
$idUsuario = isset($_SESSION["idUsuario"]) ? $_SESSION["idUsuario"] : 1190;

// if($Departamento == 16){
// 	$Departamento = 14;
// }

if (!isset($_POST["mundial"]) || isset($_POST['mundial']) == "") {
	$mundial = NULL;
} else {
	$mundial = $_POST['mundial'];
}
$credenciales = $_POST["credenciales"];

// VALIDAMOS SI VIENE EL CODIGO DEL CLIENTE Y DE LO CONTRARIO SE CREA EN LA BD
if ($idCliente == "" && $tipoDocumento != 2) {

	// CONSULTAMOS EL ULTIMO CODIGO INSERTADO Y LE SUMAMOS 1 PARA CREAR EL CODIGO DEL NUEVO CLIENTE
	$respCodCliente = mysqli_query($con, "SELECT `cli_codigo` FROM clientes ORDER BY `id_cliente` DESC LIMIT 1");
	$rowsCodCliente = mysqli_num_rows($respCodCliente);

	if ($rowsCodCliente <= 1) {
		$row = $respCodCliente->fetch_assoc();
		$cod = substr($row["cli_codigo"], 4);
		$cli_codigo = "CLI-" . ($cod + 1);
	} else {
		$cli_codigo = "CLI-1";
	}

	$intermediario = $_SESSION["intermediario"];

	// INSERCIÓN DATOS DEL CLIENTE
	$sqlCliente = "INSERT INTO `clientes` (`id_cliente`, `cli_codigo`, `cli_num_documento`, `cli_nombre`, `cli_apellidos`, `cli_fch_nacimiento`, 
											`cli_genero`, `cli_telefono`, `cli_email`, `cli_estado`, `id_tipo_documento`, `id_estado_civil` , `id_Intermediario`) 
					VALUES (NULL, '$cli_codigo', '$numIdentificacion', '$Nombre', '$Apellido', " . ($FechaNacimiento ? "'$FechaNacimiento'" : "0000-00-00") . ", '$Genero', '$Celular', 
									'$Correo', 1, '$tipoDocumento', '$EstCivil', '$intermediario');";

	$resCliente = mysqli_query($con, $sqlCliente);
	$num_rows = mysqli_affected_rows($con);

	if ($num_rows > 0) {
		$data['Message 1'] = 'Cliente creado exitosamente';

		$respIdCliente = mysqli_query($con, "SELECT `id_cliente` FROM clientes ORDER BY `id_cliente` DESC LIMIT 1");
		$arrIdCliente = $respIdCliente->fetch_assoc();
		$idCliente = $arrIdCliente["id_cliente"];
	} else {
		$data['Message 1'] = 'Error cliente: ' . mysqli_error($con);
	}
} else if ($tipoDocumento != 2) {

	// Verificar conexión antes de ejecutar la consulta
	if ($con) {
		// Preparar y ejecutar la consulta para actualizar el cliente
		$sqlClient = "UPDATE clientes 
                  SET id_tipo_documento = '$tipoDocumento', 
                      cli_num_documento = '$numIdentificacion', 
                      cli_nombre = '$Nombre', 
                      cli_apellidos = '$Apellido', 
                      cli_genero = '$Genero', 
					  cli_fch_nacimiento = '$FechaNacimiento',
                      id_estado_civil = '$EstCivil', 
                      cli_email = '$Correo', 
                      cli_telefono = '$Celular' 
                  WHERE id_cliente = $idCliente";

		$resClient = mysqli_query($con, $sqlClient);

		// Verificar si la consulta fue ejecutada con éxito
		if ($resClient) {
			$num_rows = mysqli_affected_rows($con);

			if ($num_rows > 0) {
				$data['Message 1'] = 'Cliente actualizado exitosamente';

				// Verificar si el cliente actualizado existe en la base de datos
				$respIdCliente = mysqli_query($con, "SELECT `id_cliente` FROM clientes WHERE id_cliente = $idCliente");

				if ($respIdCliente) {
					$arrIdCliente = $respIdCliente->fetch_assoc();
					if ($arrIdCliente) {
						$idCliente = $arrIdCliente["id_cliente"];
					} else {
						$data['Message 2'] = 'Error: Cliente actualizado pero no encontrado en la base de datos.';
					}
				} else {
					$data['Message 2'] = 'Error al consultar el cliente actualizado: ' . mysqli_error($con);
				}
			} else {
				$data['Message 1'] = 'No se encontraron cambios en los datos del cliente.';
			}
		} else {
			$data['Message 1'] = 'Error al actualizar el cliente: ' . mysqli_error($con);
		}
	} else {
		$data['Message'] = 'Error: No se pudo establecer conexión con la base de datos.';
	}
} else if ($idCliente == "" && $tipoDocumento == 2) {
	// CONSULTAMOS EL ULTIMO CODIGO INSERTADO Y LE SUMAMOS 1 PARA CREAR EL CODIGO DEL NUEVO CLIENTE
	$respCodCliente = mysqli_query($con, "SELECT `cli_codigo` FROM clientes ORDER BY `id_cliente` DESC LIMIT 1");
	$rowsCodCliente = mysqli_num_rows($respCodCliente);

	if ($rowsCodCliente <= 1) {
		$row = $respCodCliente->fetch_assoc();
		$cod = substr($row["cli_codigo"], 4);
		$cli_codigo = "CLI-" . ($cod + 1);
	} else {
		$cli_codigo = "CLI-1";
	}

	$intermediario = isset($_SESSION["intermediario"]) ? $_SESSION["intermediario"] : 3;

	// INSERCIÓN DATOS DEL CLIENTE

	$nameParts = explode(' ', $razonSocial, 2);

	$Nombre = $nameParts[0];
	$Apellido = $nameParts[1];

	$numID = $numIdentificacion;

	// var_dump($numID);
	// var_dump($digitoVerif);
	// die();

	$sqlCliente = "INSERT INTO `clientes` (`id_cliente`, `cli_codigo`, `cli_num_documento`, `digitoVerificacion` ,`cli_nombre`, `cli_apellidos`, `cli_fch_nacimiento`, 
											`cli_genero`, `cli_telefono`, `cli_email`, `cli_estado`, `id_tipo_documento`, `id_estado_civil` , `id_Intermediario`) 
					VALUES (NULL, '$cli_codigo', '$numIdentificacion', '$digitoVerif', '$Nombre', '$Apellido', " . ($FechaNacimiento ? "'$FechaNacimiento'" : "0000-00-00") . ", '3', '$celRep', 
									'$correoRep', 1,  '$tipoDocumento', '1', '$intermediario');";

	$resCliente = mysqli_query($con, $sqlCliente);

	if (!$resCliente) {
		echo "Error en la consulta: " . mysqli_error($con);
		die(); // Detiene la ejecución para que puedas analizar el problema
	}
	$num_rows = mysqli_affected_rows($con);
	$ultimoId = mysqli_insert_id($con);

	if ($num_rows > 0) {
		$data['Message 1'] = 'Cliente NIT creado exitosamente';

		$sqlRepresentante = "INSERT INTO `clientes_nit_repleg` (`id_repleg`, `rep_nombre`, `rep_apellidos`, `rep_tipo_documento`, `rep_num_documento`, `rep_fch_nacimiento`, 
											`rep_genero`, `rep_est_civil`, `id_cliente_asociado`, `rep_email`, `rep_telefono`) 
							 VALUES (NULL, '$nombresRep', '$apellidosRep', '$tipoDocRep', '$numDocRep','$fechaNacimientoRep', '$generoRep', '$estCivRep', '$ultimoId', 
									'$correoRep', '$celRep');";

		$ultimoIdRep = mysqli_insert_id($con);
		$respIdCliente2 = mysqli_query($con, "SELECT `id_cliente` FROM clientes ORDER BY `id_cliente` DESC LIMIT 1");
		$arrIdCliente2 = $respIdCliente2->fetch_assoc();
		$idCliente = $arrIdCliente2["id_cliente"];

		$resRepresentante = mysqli_query($con, $sqlRepresentante);
		$num_rows_rep = mysqli_affected_rows($con);

		if ($num_rows_rep > 0) {
			$data['Message 3'] = "Representante Legal Creado Correctamente para el cliente " . $ultimoId;
		} else {
			$data['Message 3'] = "Error al crear el Representante Legal al cliente: " . $ultimoId;
		}
	} else {
		$data['Message 1'] = 'Error cliente NIT: ' . mysqli_error($con);
	}
}  else if ($tipoDocumento == 2 && $idCliente != "") {
	// Separar nombre y apellido de la razón social
	$nameParts = explode(' ', $razonSocial, 2);
	$Nombre = $nameParts[0];
	$Apellido = isset($nameParts[1]) ? $nameParts[1] : '';

	// Actualizar cliente
	$sqlCliente = "UPDATE clientes SET 
		id_tipo_documento = '$tipoDocumento', 
		cli_num_documento = '$numIdentificacion', 
		digitoVerificacion = '$digitoVerif', 
		cli_nombre = '$Nombre', 
		cli_apellidos = '$Apellido', 
		cli_genero = '3', 
		cli_fch_nacimiento = '$FechaNacimiento', 
		id_estado_civil = '1', 
		cli_email = '$correoRep', 
		cli_telefono = '$celRep' 
		WHERE id_cliente = $idCliente";
	
	$resCliente = mysqli_query($con, $sqlCliente);
	$num_rows = mysqli_affected_rows($con);

	if ($num_rows >= 0) {
		$data['Message 1'] = $num_rows > 0 ? 'Cliente actualizado exitosamente' : 'Cliente sin modificaciones';

		// Validar si el representante ya existe
		$sqlBuscarRep = "SELECT * FROM clientes_nit_repleg WHERE id_cliente_asociado = $idCliente";
		$representanteCliente = mysqli_query($con, $sqlBuscarRep);
		$rowsRepresentante = mysqli_num_rows($representanteCliente);

		if ($rowsRepresentante > 0) {
			// Actualizar representante
			$sqlRep = "UPDATE clientes_nit_repleg SET 
				rep_nombre = '$nombresRep', 
				rep_apellidos = '$apellidosRep', 
				rep_tipo_documento = '$tipoDocRep', 
				rep_num_documento = '$numDocRep',
				rep_fch_nacimiento = '$fechaNacimientoRep', 
				rep_genero = '$generoRep', 
				rep_est_civil = '$estCivRep', 
				rep_email = '$correoRep', 
				rep_telefono = '$celRep' 
				WHERE id_cliente_asociado = $idCliente";
			
			$resRepresentante = mysqli_query($con, $sqlRep);
			$num_rows_rep = mysqli_affected_rows($con);
			$data['Message 3'] = $num_rows_rep > 0 
				? "Representante Legal actualizado correctamente para el cliente $idCliente"
				: "Sin modificaciones en el representante legal cliente: $idCliente";
		} else {
			// Insertar nuevo representante
			$sqlRep = "INSERT INTO clientes_nit_repleg 
				(rep_nombre, rep_apellidos, rep_tipo_documento, rep_num_documento, rep_fch_nacimiento, rep_genero, rep_est_civil, id_cliente_asociado, rep_email, rep_telefono) 
				VALUES 
				('$nombresRep', '$apellidosRep', '$tipoDocRep', '$numDocRep', '$fechaNacimientoRep', '$generoRep', '$estCivRep', '$idCliente', '$correoRep', '$celRep')";
			
			$resRepresentante = mysqli_query($con, $sqlRep);
			$num_rows_rep = mysqli_affected_rows($con);
			$data['Message 3'] = $num_rows_rep > 0 
				? "Representante Legal creado correctamente para el cliente $idCliente"
				: "Error al crear el Representante Legal para el cliente: $idCliente";
		}
	} else {
		$data['Message 1'] = 'Error al actualizar cliente: ' . mysqli_error($con);
	}
}


// VALIDA SI VIENE EL ID DE LA COTIZACION PARA CREAR O ACTUALIZAR EL REGISTRO
if ($idCotizacion == "" && $idCliente != "") {
	// CONSULTAMOS EL ULTIMO CODIGO INSERTADO Y LE SUMAMOS 1 PARA CREAR EL CODIGO DE LA NUEVA COTIZACIÓN
	$respCodCotizacion = mysqli_query($con, "SELECT `cot_codigo` FROM cotizaciones ORDER BY `id_cotizacion` DESC LIMIT 1");
	$rowsCodCotizacion = mysqli_num_rows($respCodCotizacion);

	if ($rowsCodCotizacion <= 1) {
		$row = $respCodCotizacion->fetch_assoc();
		$cod = substr($row["cot_codigo"], 4);
		$cot_codigo = "COT-" . ($cod + 1);
	} else {
		$cot_codigo = "COT-1";
	}


	// INSERCIÓN DATOS DE LA COTIZACION REALIZADA
	$sql = "INSERT INTO `cotizaciones` (`id_cotizacion`, `cot_codigo`, `cot_fch_cotizacion`, `cot_placa`, `cot_cerokm`, `cot_cod_clase`, `cot_clase`, 
										`cot_marca`, `cot_modelo`, `cot_linea`, `cot_fasecolda`, `cot_valor_asegurado`, `cot_tip_uso`, `cot_tip_servicio`, `cot_num_pasajeros`, `cot_num_toneladas`, `cot_departamento`, 
										`cot_ciudad`, `cot_bnf_oneroso`, `id_cliente`, `id_usuario`, `cot_mundial`, `permisosCotizacion`) 
								VALUES (NULL, '$cot_codigo', current_timestamp(), '$placa', '$esCeroKm', '$CodigoClase', '$Clase', '$Marca', '$Modelo', '$Linea', '$Fasecolda', 
										'$ValorAsegurado', '$tipoUsoVehiculo', '$tipoServicio', '$numeroPasajeros', '$numToneladas', '$Departamento', '$Ciudad', '$benefOneroso', '$idCliente', '$idUsuario', '$mundial', '$credenciales');";

	$res = mysqli_query($con, $sql);
	$num_rows = mysqli_affected_rows($con);


	if ($num_rows > 0) {
		$data['Message 2'] = 'Cotización creada exitosamente';

		$sql2 = "SELECT id_cotizacion FROM `cotizaciones` WHERE `cot_placa` LIKE '$placa' AND `id_cliente` LIKE '$idCliente' ORDER BY `id_cotizacion` DESC LIMIT 1";
		$res2 = mysqli_query($con, $sql2);
		$arrIdCotizacion = $res2->fetch_assoc();

		$data['id_cotizacion'] = $arrIdCotizacion["id_cotizacion"];
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	} else {
		$data['Message 2'] = 'Error cotización: ' . mysqli_error($con);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
} else {
}
