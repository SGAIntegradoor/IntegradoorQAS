<?php
require_once("config/db.php"); //Contiene las variables de configuracion para conectar a la base de datos
require_once("config/conexion.php"); //Contiene funcion que conecta a la base de datos

$identidad = $_SESSION['permisos']['usu_documento'];

$sqlConfirm = "SELECT * from usuarios where usu_documento = $identidad";
$res = mysqli_query($con, $sqlConfirm);
$num_rows = mysqli_num_rows($res);

$cotizTotales = null;

if ($num_rows >= 1) {
	$data = $res->fetch_assoc();
	$cotizTotales = $data['cotizacionesTotales'];
} else {
	echo "Error al traer cotizaciones Totales";
}
// Obtener la URL de la página actual
$currentPage = basename($_SERVER['REQUEST_URI']);
//die();
// Función para agregar la clase 'active' si la URL coincide
function setActive($page)
{
	global $currentPage;
	if ($currentPage == $page) {
		return 'active';
	}
}

// echo '<script>console.log('.json_encode($cotizTotales).')</script>';
//::::::::::::::::::::::::::::::::Consulta el estado actual del usuario:::::::::::::::::::::::::::::://
//:::Cierra la sesion si el estado es 0 y en su proxima interaccion con el software lo desconecta::://
include_once 'config/checkUser.php';
checkUserStatus();
//::::::::::::::::::::::::::::::::Fin del metodo::::::::::::::::::::::::::::::::::::::::::::::::::::://
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://

?>
<script>
	$(document).ready(async function() {
		// Obtener el valor de la variable de sesión PHP en JavaScript
		let permisosCotizacionesTotales = <?php echo isset($cotizTotales) ? json_encode($cotizTotales) : 'null'; ?>;
		let cotHechas = await mostrarCotRestantes();
		var arrayCot = ["menuCotLiv", "menuCotMot", "menuCotPes", "menuCotMas"];
		// Verificar si el valor obtenido es válido y está definido
		if (permisosCotizacionesTotales !== null && permisosCotizacionesTotales !== undefined) {
			/* Iteramos sobre el array de vistas el cual sera unico y generamos un 
			JQuery con cada uno de los items dentro del array los cuales son ide que luego se les 
			asocia el evento click al elemento del menú */
			// 		arrayCot.forEach(view => {
			// 			return $(`#${view}`).on("click", function(e) {
			// 				// Verificar los permisos
			// 				// if (cotHechas >= permisosCotizacionesTotales ) {
			// 				// 	e.preventDefault();
			// 				// 	swal
			// 				// 		.fire({
			// 				// 			icon: "error",
			// 				// 			title: "Sin Cotizaciones Disponibles",
			// 				// 			html: `<div style="text-align: justify; font-family: Helvetica, Arial, sans-serif; font-size: 15px; border-radius: 4px; padding: 8px;">El usuario no cuenta con cotizaciones disponibles. En este momento solo podrás visualizar las cotizaciones realizadas hasta que se agoten los días habilitados. Si quieres seguir haciendo cotizaciones solicita vincularte al Programa. Comunícate con el área encargada de vinculaciones de Grupo Asistencia al:
			// 				// 			<br><br>
			// 				// 			<div style="text-align: center;">📱<strong>+573185127910</strong> o vía 📧 <strong>mercadeo@grupoasistencia.com</strong> </div></div>`,
			// 				// 			width: "60%",
			// 				// 			showConfirmButton: true,
			// 				// 			confirmButtonText: "Cerrar",
			// 				// 			customClass: {
			// 				// 				popup: "custom-swal-popup",
			// 				// 				title: "custom-swal-title",
			// 				// 				content: "custom-swal-content",
			// 				// 				confirmButton: "custom-swal-confirm-button",
			// 				// 			},
			// 				// 		})
			// 				// 		.then(function(result) {
			// 				// 			if (result.value) {
			// 				// 				window.location = "inicio";
			// 				// 			}
			// 				// 		});
			// 				// }
			// 			});
			// 		})
		}
	});
</script>
<style>
	.btnConfirm {
		background-color: #ff5733 !important;
		color: white !important;
		border: 0px !important;
		box-shadow: 0px !important;
	}

	.mi-clase-warning {
		width: 600px;
		/* Ajusta el ancho según sea necesario */
		height: auto;
		/* Ajusta el alto según sea necesario */
	}

	#negociosOp {
		margin-left: 2px;
		font-size: 18px;
	}

	.mi-clase-success {
		width: 600px;
		/* Ajusta el ancho según sea necesario */
		height: auto;
		/* Ajusta el alto según sea necesario */
	}
</style>
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<!-- =============================================
		INICIO
		============================================= -->
			<li class="<?php echo $currentPage == 'inicio' ? 'active' : ''; ?>">
				<a href="inicio">
					<i class="fa fa-home"></i>
					<span>Inicio</span>
				</a>
			</li>


			<?php
			/*=============================================
		CONFIGURAR POLÍTICIAS
		=============================================*/
			// 	if ($_SESSION["permisos"]["veradministracionintegradoor"] == "x") {
			// 		echo '<li class="' . ($currentPage == 'politicas' || $currentPage == 'planes' || $currentPage == 'contratos' || $currentPage == 'pagos' ? 'active' : '') . '">
			// 	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			// 	<i class="fa fa-wrench" aria-hidden="true"></i>
			// 		<span >Administrador Integradoor</span>
			// 	</a>			
			// 		<ul class="dropdown-menu right">
			// 			<li class="user-body">			
			// 				<a href="politicas" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Políticas</a>
			// 			</li>
			// 			<li class="user-body">	
			// 				<a href="planes" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Planes</a>
			// 			</li>
			// 			<li class="user-body">	
			// 				<a href="inicio" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Contratos</a>
			// 			</li>
			// 			<li class="user-body">	
			// 				<a href="inicio" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Pagos</a>
			// 			</li>

			// 		</ul>
			// </li>';
			//	}

			/*=============================================
		ADMINISTRAR COTIZACIONES
		=============================================*/
			if ($_SESSION["permisos"]["administracionCotizaciones"] == "x") {
				echo '<li class="' . ($currentPage == 'adminCoti' ? 'active' : '') . '">
            <a href="adminCoti">
                <i class="fa fa-list-ul"></i>
                <span>Admin Cotizaciones</span>
            </a>
          </li>';
			}
			// 	/*=============================================
			// AUTOGESTION
			// =============================================*/
			// 	if ($_SESSION["permisos"]["administracionCotizaciones"] == "x") {
			// 		echo '<!--<li>
			// 		<a href="autogestion">
			// 			<i class="fa fa-user"></i>
			// 			<span>Autogestión</span>
			// 		</a>
			// 	</li>-->';
			// 	}

		/*=============================================
		CLIENTES
		=============================================*/
			if ($_SESSION["permisos"]["Clientes"] == "x") {
				echo '<li class="' . ($currentPage == 'clientes' ? 'active' : '') . '">
				<a href="clientes">
					<i class="fa fa-user-circle-o"></i>
					<span>Clientes</span>
				</a>
			</li>';
			}
		/*=============================================
		ASSITCARD
		=============================================*/

			if (($_SESSION["permisos"]["AsistenciaEnViajes"] == "x") || $_SESSION["idUsuario"] == 34) {
				echo '<li id="menuCoAssist" class="' . ($currentPage == 'assistcard' ? 'active' : '') . '">
				<a href="assistcard">
				<i class="fa fa-plane" aria-hidden="true" style="font-size: 1.2em;"></i>
				<span>Asistencia en viajes</span>
				</a>
				</li>';
			}
			/*=============================================
		COTIZAR LIVIANO
		=============================================*/
			echo  '<li class="' . ($currentPage == 'cotizar' ? 'active' : '') . '">
				<a id="menuCotLiv" href="cotizar">
					<i class="fa fa-car"></i>
					<span>Cotizar Livanos</span>
				</a>
			</li>';
			?>
			<?php
			/*=============================================
		COTIZACIONES MASIVAS
		=============================================*/
			// if ($_SESSION["permisos"]["Cotizacionesmasivas"] == "x") {
			// 	echo  '<li class="' . ($currentPage == 'livianoMasivas' ? 'active' : '') . '">
			// 	<a id="menuCotMas" href="livianoMasivas">
			// 		<i class="fa fa-file-archive-o"></i>
			// 		<span>Cotizaciones masivas liviano</span>
			// 	</a>
			// </li>';
			// }
			/*=============================================
		PESADOS
		=============================================*/
			if ($_SESSION["permisos"]["Cotizarpesados"] == "x") {
				echo '<li id="menuCotPes" class="' . ($currentPage == 'pesados' ? 'active' : '') . '">
				<a id="menuCotPes" href="pesados">
					<i class="fa fa-truck"></i>
					<span>Cotizar Pesados</span>
				</a>
			</li>';
			}

			/*=============================================
		MOTOS
		=============================================*/
			if ($_SESSION["permisos"]["Cotizarmotos"] == "x") {
				echo '<li id="menuCotMot" class="' . ($currentPage == 'motos' ? 'active' : '') . '">
				<a href="motos">
				<i class="fa fa-motorcycle"></i>
				<span>Cotizar Motos</span>
				</a>
			</li>';
			}
			/*=============================================
		TRANSPORTE PASAJEROS
		=============================================*/
			if ($_SESSION["permisos"]["cotizarpasajeros"] == "x") {
				echo '<li id="menuCotPas" class="' . ($currentPage == 'motos' ? 'active' : '') . '">
				<a href="transporte-pasajeros">
				<i class="fa-solid fa-bus" style="font-size: 16px"></i>
				<span>Cotizar Transporte Pasajeros</span>
				</a>
			</li>';
			}

			/*=============================================
		NEGOCIOS
		=============================================*/
		if ($_SESSION['rol'] == 11 || $_SESSION['rol'] == 12 || $_SESSION["rol"] == 10 || $_SESSION['rol'] == 1) {
			echo '<li class="treeview">
			<a href="#">
				<i class="fa fa-briefcase"></i>
				<span>Admin. Negocios</span>
				<i class="fa fa-angle-left pull-right"></i>
			</a>
			<ul class="treeview-menu subitems-normal">
				<li class="' . ($currentPage == "negocios" ? "active" : "") . '">
					<a href="negocios">Admin. Oportunidades</a>
				</li>
				<li class="' . ($currentPage == "productividad" ? "active" : "") . '">
					<a href="productividad">Productividad</a>
				</li>
			</ul>
		</li>';
		}
			/*=============================================
		HOGAR
		=============================================*/
			if ($_SESSION["intermediario"] == "3" || $_SESSION["intermediario"] == "149" || $_SESSION["idUsuario"] == 34) {
				echo '<li id="menuCotHog" class="' . ($currentPage == 'hogar' ? 'active' : '') . '">
			<a href="hogar">
			<i class="fa-solid fa-house-circle-check"></i>
			<span>Cotizar Hogar</span>
			</a>
			</li>';
			}
			/*=============================================
		SALUD
		=============================================*/
			if ($_SESSION["intermediario"] == "3" || $_SESSION["intermediario"] == "149" || $_SESSION["idUsuario"] == 34) {
				echo '<li id="menuCoAssist" class="' . ($currentPage == 'salud' ? 'active' : '') . '">
		<a href="salud">
			<i class="fa fa-heartbeat" aria-hidden="true" style="font-size: 1.2em;"></i>
			<span>Cotizador Seguro de Salud</span>
		</a>
	</li>';
			}

			/*=============================================
			MÓDULO SOAT
			=============================================*/

			if ($_SESSION["permisos"]["SeguroExequial"] == "x") {
				echo '<li id="menuCotSoat" role="presentation" style="width: 50px; height: 44px;" class="' . ($currentPage == 'soat' ? 'active' : '') . '">
				<a href="soat">
				<img class="imagen" style="margin-left: -5px;" width="25" height="25" src="vistas/img/plantilla/soat.png" alt="SOAT">
				<span>SOAT</span>
				</a>
				</li>';
			}

			/*=============================================
		EXEQUIAS
		=============================================*/

			if ($_SESSION["permisos"]["SeguroExequial"] == "x") {
				echo '<li id="menuCotExe" class="' . ($currentPage == 'exequias' ? 'active' : '') . '">
			<a href="exequias">
				<i class="fa fa-umbrella" aria-hidden="true"></i>
				<span>Exequias</span>
				</a>
				</li>';
			}

			/*=============================================
		USUARIOS
		=============================================*/
			if ($_SESSION["permisos"]["AdministrarUsuarios"] == "x") {
				echo '<li class="' . ($currentPage == 'usuarios' ? 'active' : '') . '">
				<a href="usuarios">
					<i class="fa fa-user-plus"></i>
					<span>Usuarios</span>
				</a>
			</li>';
			}
			/*=============================================
		PRODUCTOS
		=============================================*/
			if ($_SESSION["permisos"]["Modificaciondeproductos"] == "x") {
				echo '<li class="' . ($currentPage == 'Productos' ? 'active' : '') . '">
				<a href="Productos">
					<i class="fa fa-folder"></i>
					<span>Productos</span>
				</a>
			</li>';
			}
			/*=============================================
		AYUDA VENTAS
		=============================================*/
		if ($_SESSION['rol'] != 2) {
			?>
			<li id="menuCotAyud" class="<?php echo $currentPage == 'ayuda-ventas' ? 'active' : ''; ?>">
				<a id="ayuda-ventas">
					<i class="fa fa-book"></i>
					<span>Ayuda Ventas</span>
				</a>
			</li>
			<?php
		}
			/*=============================================
		INTERMEDIARIO
		=============================================*/
			if ($_SESSION["permisos"]["Agregarintermediario"] == "x") {
				echo '<li class="' . ($currentPage == 'intermediario' ? 'active' : '') . '">
				<a href="intermediario">
					<i id="negociosOp" class="fa-solid fa-building"></i>
					<span>Intermediario</span>
				</a>
			</li>';
			}

			/*=============================================
		INVITACIÓN
		=============================================*/

			if ($_SESSION["permisos"]["veradministracionintegradoor"] == "x") {
				echo '<li class="' . ($currentPage == 'invitar' ? 'active' : '') . '">
				<a href="invitar">
				<i class="fa fa-paper-plane" aria-hidden="true"></i>
					<span>Invitación</span>
				</a>
			</li>';
			}

			/*=============================================
		CONFIGURAR PDF
		=============================================*/

			// <li>
			// 	<a id="configuracion-pdf">
			// 		<i class="fa fa-cog" aria-hidden="true"></i>
			// 		<span>Configuracion</span>
			// 	</a>
			// </li>
			?>

		</ul>
	</section>
</aside>