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
	$(document).ready(function() {
		// Obtener el valor de la variable de sesión PHP en JavaScript
		let permisosCotizacionesTotales = <?php echo isset($cotizTotales) ? json_encode($cotizTotales) : 'null'; ?>;

		var arrayCot = ["menuCotLiv", "menuCotMot", "menuCotPes", "menuCotMas"];

		// Verificar si el valor obtenido es válido y está definido
		if (permisosCotizacionesTotales !== null && permisosCotizacionesTotales !== undefined) {
			/* Iteramos sobre el array de vistas el cual sera unico y generamos un 
			JQuery con cada uno de los items dentro del array los cuales son ide que luego se les 
			asocia el evento click al elemento del menú */
			arrayCot.forEach(view => {
				return $(`#${view}`).on("click", function(e) {
					// Verificar los permisos
					if (permisosCotizacionesTotales <= "0") {
						e.preventDefault();
						swal
							.fire({
								icon: "error",
								title: "Cotizaciones Totales Excedidas",
								text: "Lo sentimos. No tienes cotizaciones disponibles, por favor comunicate con el administrador.",
								showConfirmButton: true,
								confirmButtonText: "Cerrar",
								customClass: {
									confirmButton: "btnConfirm",
								},
							})
							.then(function(result) {
								if (result.value) {
									window.location = "inicio";
								}
							});
					}
				});
			})
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

div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
    background-color: #88d600 !important;
}

div:where(.swal2-container) button:where(.swal2-styled) {
    box-shadow: 0 0 0 0px rgba(0, 0, 0, 0) !important;
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
			if ($_SESSION["permisos"]["veradministracionintegradoor"] == "x") {
				echo '<li class="' . ($currentPage == 'politicas' || $currentPage == 'planes' || $currentPage == 'contratos' || $currentPage == 'pagos' ? 'active' : '') . '">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<i class="fa fa-wrench" aria-hidden="true"></i>
				<span >Administrador Integradoor</span>
			</a>			
				<ul class="dropdown-menu right">
					<li class="user-body">			
						<a href="politicas" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Políticas</a>
					</li>
					<li class="user-body">	
						<a href="planes" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Planes</a>
					</li>
					<li class="user-body">	
						<a href="inicio" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Contratos</a>
					</li>
					<li class="user-body">	
						<a href="inicio" class="btn btn-default btn-flat"><i class="" style="color: red;"></i>Pagos</a>
					</li>

				</ul>
		</li>';
			}

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
			/*=============================================
		AUTOGESTION
		=============================================*/
			if ($_SESSION["permisos"]["administracionCotizaciones"] == "x") {
				echo '<!--<li>
				<a href="autogestion">
					<i class="fa fa-user"></i>
					<span>Autogestión</span>
				</a>
			</li>-->';
			}

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
			if ($_SESSION["permisos"]["Cotizacionesmasivas"] == "x") {
				echo  '<li class="' . ($currentPage == 'livianoMasivas' ? 'active' : '') . '">
				<a id="menuCotMas" href="livianoMasivas">
					<i class="fa fa-file-archive-o"></i>
					<span>Cotizaciones masivas liviano</span>
				</a>
			</li>';
			}
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
		ASSITCARD
		=============================================*/

			if ($_SESSION["permisos"]["AsistenciaEnViajes"] == "x") {
				echo '<li id="menuCoAssist" class="' . ($currentPage == 'assistcard' ? 'active' : '') . '">
				<a href="assistcard">
					<i class="fa fa-plane" aria-hidden="true" style="font-size: 1.2em;"></i>
					<span>Asistencia en viajes</span>
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
			?>
			<li id="menuCotAyud" class="<?php echo $currentPage == 'ayuda-ventas' ? 'active' : ''; ?>">
				<a id="ayuda-ventas">
					<i class="fa fa-book"></i>
					<span>Ayuda Ventas</span>
				</a>
			</li>
			<?php
			/*=============================================
		INTERMEDIARIO
		=============================================*/
			if ($_SESSION["permisos"]["Agregarintermediario"] == "x") {
				echo '<li class="' . ($currentPage == 'intermediario' ? 'active' : '') . '">
				<a href="intermediario">
					<i class="fa fa-briefcase"></i>
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