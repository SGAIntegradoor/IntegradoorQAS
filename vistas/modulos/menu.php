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
		if (permisosCotizacionesTotales !== null && permisosCotizacionesTotales !== undefined) {}
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
		height: auto;
	}

	#negociosOp {
		margin-left: 2px;
		font-size: 18px;
	}

	.mi-clase-success {
		width: 600px;
		height: auto;
	}

	.bg-li-active:active {
		background-color: #88d600 !important;
		color: white !important;
	}

	.hoverLi:hover {
		background-color: #88d600 !important;
		color: white !important;
		border-radius: 5px;
		/* border-bottom: 1px solid gray; */
	}

	.skin-blue .sidebar-menu .treeview-menu > li > a:hover {
		background-color: #88d600 !important;
		color: white !important;
		border-radius: 5px;
	}

	.skin-blue .sidebar-menu .sidebar .sidebar-menu .treeview-menu > li > a:active{
		color: white !important;
	}

	.skin-blue .main-sidebar .sidebar .sidebar-menu .treeview-menu > li > a:hover {
		background-color: #88d600 !important;
		color: white !important;
		border-radius: 5px;
	}

	.skin-blue .sidebar-menu .treeview-menu > li > a:active {
		color: white !important;
	}

	.active-li{
		background-color: #88d600 !important;
		color: white !important;
		border-radius: 5px
	}

	/* Abierto/normal → ocupa todo el ancho del sidebar */
body:not(.sidebar-collapse) .sidebar-menu .consultas-sub{
  width: 100% !important;
  position: static;      /* que no sea flotante */
  box-shadow: none;
}

/* Sidebar mini/colapsado → flyout de 178px a la derecha del icono */
.sidebar-mini.sidebar-collapse .sidebar-menu > li > .consultas-sub{
  position: absolute;
  left: 50px;            /* ancho de la barra colapsada */
  top: 0;
  width: 178px;          /* tu ancho cuando está cerrado */
  margin-left: 0 !important;
  display: none;         /* oculto por defecto en mini */
  box-shadow: 0 6px 18px rgba(0,0,0,.15);
  border-radius: 6px;
  z-index: 1050;
}

/* Mostrar el flyout al pasar el mouse o si el item está abierto */
.sidebar-mini.sidebar-collapse .sidebar-menu > li:hover > .consultas-sub,
.sidebar-mini.sidebar-collapse .sidebar-menu > li.menu-open > .consultas-sub{
  display: block;
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
			if (in_array($_SESSION['rol'], [1, 10, 11, 12, 22])) {
				echo '<li id="btnCRM">
			<a id="btnCRM">
			<i class="fas fa-users"></i>
			<span>CRM</span>
			</a>
			</li>';
			}


			if ($_SESSION["permisos"]["veradministracionintegradoor"] == "x" || in_array($_SESSION['rol'], [1, 10, 11, 12, 22, 19])) {
				echo '<li class="treeview">
					<a href="#">
						<i class="fa-solid fa-magnifying-glass"></i>
						<span class="text-center" style="padding-left: 5px;">Consultas</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu" style="background-color: white; margin-left: 2px; padding-top: 0px; padding-bottom: 0px; padding-left: 0px !important;">
						<li style="border-bottom: 0.5px solid gray !important" class="' . ($currentPage == "user-negocios" ? "active-li bg-li-active" : "hoverLi" ) . '">
							<a id="user-negocios" href="user-negocios" style="text-align: center; padding-left: 0px">Tus negocios</a>
						</li>
						<li style="border-bottom: 0.5px solid gray !important" class="' . ($currentPage == "user-clientes" ? "active-li bg-li-active" : "hoverLi") . '">
							<a id="user-clientes" href="#" style="text-align: center; padding-left: 0px">Clientes</a>
						</li>
						<li style="border-bottom: 0.5px solid gray !important" class="' . ($currentPage == "user-comisiones" ? "active-li bg-li-active" : "hoverLi") . '">
							<a id="user-comisiones" href="#" style="text-align: center; padding-left: 0px">Comisiones</a>
						</li>
						<li style="border-bottom: 0.5px solid gray !important" class="' . ($currentPage == "user-cartera" ? "active-li bg-li-active" : "hoverLi") . '">
							<a id="user-cartera" href="#" style="text-align: center; padding-left: 0px">Cartera</a>
						</li>
					</ul>
				</li>';
			}
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
				echo '<li class="' . ($currentPage == 'prospectos' ? 'active' : '') . '">
				<a href="prospectos">
					<i class="fa fa-user-circle-o"></i>
					<span>Prospectos</span>
				</a>
			</li>';
			}
			/*=============================================
		ASSITCARD
		=============================================*/

			if (($_SESSION["permisos"]["AsistenciaEnViajes"] == "x")) {
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
			echo  '<li class="' . ($currentPage == 'cotizar' || $currentPage == 'livianos' ? 'active' : '') . '">
				<a id="menuCotLiv" href="cotizar">
					<i class="fa fa-car"></i>
					<span>Cotizar Liviano Familiar</span>
				</a>
			</li>';

			/*=============================================
		COTIZAR UTILITARIO
		=============================================*/
			echo  '<li class="' . ($currentPage == 'utilitarios' || $currentPage == 'livianos' ? 'active' : '') . '">
				<a id="menuCotLiv" href="utilitarios">
					<i class="fa fa-truck-pickup"></i>
					<span>Cotizar Liviano Utilitario</span>
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
				echo '<li id="menuCotPas" class="' . ($currentPage == 'transporte-pasajeros' ? 'active' : '') . '">
				<a href="transporte-pasajeros">
				<i class="fa-solid fa-bus" style="font-size: 16px"></i>
				<span>Cotizar Autos Pasajeros</span>
				</a>
			</li>';
			}



			// <li class="' . ($currentPage == "productividad" ? "active bg-li-active" : "hoverLi") . '" style="border: 1px solid gray; border-radius: 5px; padding: 5px;">
			// 	<a href="productividad">Productividad</a>
			// </li>

			/*=============================================
		RC Hidrocarburos
		=============================================*/
			if ($_SESSION["permisos"]["SeguroExequial"] == "x") {
				echo '<li id="menuCotHidro" class="' . ($currentPage == 'hidrocarburos' ? 'active' : '') . '">
				<a href="hidrocarburos">
				<i class="fa-solid fa-truck-droplet" style="font-size: 16px"></i>
				<span>Cotizar RC Hidrocarburos</span>
				</a>
			</li>';
			}

			/*=============================================
		NEGOCIOS
		=============================================*/

			if (in_array($_SESSION['rol'], [1, 10, 11, 12, 22])) {
				$isActive = in_array($currentPage, ['negocios', 'productividad']) ? 'active' : '';
				echo '<li class="treeview">
					<a href="#">
						<i class="fa fa-briefcase"></i>
						<span>Admin. Negocios</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu subitems-normal">
						<li class="' . ($currentPage == "negocios" ? "active bg-li-active" : "hoverLi") . '" style="border: 1px solid gray; border-radius: 5px; padding: 5px;">
							<a href="negocios">Admin. Oportunidades</a>
						</li>
						<li class="' . ($currentPage == "productividad" ? "active bg-li-active" : "hoverLi") . '" style="border: 1px solid gray; border-radius: 5px; padding: 5px;">
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
			<a href="hogar2">
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