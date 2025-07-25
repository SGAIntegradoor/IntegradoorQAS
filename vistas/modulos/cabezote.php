<?php
$ruta = $_SESSION["foto"];
$rutaDefault = "vistas/img/usuarios/default/anonymous.png";

// Verificamos si la ruta es válida y si el archivo existe físicamente en el servidor
if (!empty($ruta) && file_exists($ruta)) {
	$imgSrc = $ruta;
} else {
	$imgSrc = $rutaDefault;
}
?>

<header class="main-header">
	<style>
		@media (max-width: 450px) {
			.li_cotDi {
				font-size: 15px !important;
				margin-top: 0px !important;
				margin-right: 0px !important;
			}

			.li_reloj {
				float: left;
				font-size: 20px !important;
				margin-right: 5px !important;
				color: #88d600 !important;
			}

			.calendar_li {
				font-size: 20px !important;
			}

			.cuentatras_li {
				margin-top: 0px !important;
			}

			.divLi {
				margin-left: 10px
			}
		}

		.li_reloj {
			float: left;
			margin-right: 5px;
			color: #88d600;
			font-size: 30px;
		}

		.li_cotDi {
			font-size: 15px;
			margin-top: 0px;
			margin-right: 20px;
		}

		.cuentatras_li {
			margin-top: 0px;
		}

		.cuentatras_li>p {
			margin: 0px;
		}

		.li_cotDi>p {
			margin: 0px;
		}

		.navbar-nav {
			display: flex !important;
			align-items: center;
		}
	</style>

	<!--=====================================
	LOGOTIPO
	======================================-->
	<a href="inicio" class="logo">

		<!-- logo mini -->
		<span class="logo-mini">

			<img src="vistas/img/plantilla/icon_Integradoor_Cotizador.png" class="img-responsive" style="padding:10px">

		</span>

		<!-- logo normal -->

		<span class="logo-lg">

			<img src="vistas/img/plantilla/Logo_Integradoor_Cotizador_2.png" class="img-responsive" style="padding: 0px 5px">

		</span>

	</a>

	<!--=====================================
	BARRA DE NAVEGACIÓN
	======================================-->
	<nav class="navbar navbar-static-top" role="navigation">

		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">

			<span class="sr-only">Toggle navigation</span>

		</a>

		<div class="row" style="margin-right: 0px !important;">

			<div class="col-md-4">
				<?php
				if ($_SESSION['rol'] == 2) { ?>
					<p style="font-size:20px; margin-top:2%;">Demo</p>
				<?php

				} else { ?>
					<!--<p style="font-size:25px; margin-top:5%; margin-right:20px;"> <strong>Software Integradoor </strong></p>-->
				<?php } ?>
			</div>

			<div class="navbar-custom-menu divLi">

				<ul class="nav navbar-nav">
					<li style="display: flex !important; flex-direction: row">
						<?php
						if ($_SESSION['rol'] == 19 || $_SESSION['rol'] == 20) { ?>
							<ul class="nav navbar-nav">
								<li class="calendar_li" style="margin-right: 5px; color: #88d600;font-size: 23px;">
									<i class="fa-solid fa-file-lines"></i>
								</li>

								<li class="li_cotDi">
									<p>Cotizaciones totales: <b><?php echo $_SESSION['permisos']['cotizacionesTotales']; ?></b>, llevas: <b id="cotRestantes1"></b> </p>
								</li>
							</ul>
						<?php } else if ($_SESSION['rol'] == 20) { ?>
							<ul class="nav navbar-nav">
								<li class="li_reloj">
									<i class="fa fa-clock-o" aria-hidden="true"></i>
								</li>

								<li class="cuentatras_li">
									<p id="cuentatras"></p>

								</li>
							</ul>
						<?php

						} else if ($_SESSION['rol'] == 3) { ?>

							<ul class="nav navbar-nav">
								<li class="li_cotDi">
									<!-- <b id="cot_lleva_inter_"> | </strong> Cot. disp. recargas : <b id="cot_recar_inter_"></b> -->
									<p>Cot. disp. plan : <b><?php echo $_SESSION['permisos']['cotizacionesTotales']; ?> <strong>|</strong></b> Cot. hechas: <b id="cotRestantes1"></b> </p>

								</li>
							</ul>

						<?php } else if ($_SESSION['rol'] == 2) { ?>
							<ul class="nav navbar-nav">
								<li class="calendar_li" style="margin-right: 5px; color: #88d600;font-size: 23px;">
									<i class="fa-solid fa-file-lines"></i>
								</li>

								<li class="li_cotDi">
									<p>Cotizaciones totales: <b><?php echo $_SESSION['permisos']['cotizacionesTotales']; ?></b>, llevas: <b id="cotRestantes1"></b> </p>
								</li>
							</ul>

							<ul class="nav navbar-nav">
								<li class="li_reloj">
									<i class="fa fa-clock-o" aria-hidden="true"></i>
								</li>

								<li class="cuentatras_li">
									<p id="cuentatras"></p>

								</li>
							</ul>
						<?php } else if ($_SESSION['rol'] == 5) { ?>
							<ul class="nav navbar-nav">
								<li class="li_cotDi">
									<!-- <b id="cot_lleva_inter_"> | </strong> Cot. disp. recargas : <b id="cot_recar_inter_"></b> -->
									<p>Cot. disp. plan : <b><?php echo $_SESSION['permisos']['Num_cot_plan']; ?> <strong>|</strong></b> Cot. hechas: <b id="cotRestantes1"></b> </p>

								</li>
							</ul>


						<?php

						}

						?>

					</li>

					<li class="dropdown user user-menu">

						<a href="#" class="dropdown-toggle" data-toggle="dropdown">

							<?php
							if ($_SESSION["foto"] != "") {
								echo '<img src="' . $imgSrc . '" class="user-image"';
							} else {
								echo '<img src="vistas/img/usuarios/default/anonymous.png" class="user-image">';
							}
							?>

							<span class="hidden-xs"><?php echo $_SESSION["nombre"] . ' ' . $_SESSION["apellido"]; ?></span>

						</a>

						<!-- Dropdown-toggle -->

						<ul class="dropdown-menu">

							<?php

							if ($_SESSION["rol"] == 1 || $_SESSION["rol"] == 10) {
							?>

								<li class="user-body">



									<a href="perfilintermediario" class="btn btn-default btn-flat "><i class="fa fa-user" style="color: #88D600;"></i>Ver perfil agencia</a>
								</li>

							<?php
							}
							?>
							<li class="user-body">

								<a href="miperfil" class="btn btn-default btn-flat "><i class="fa fa-user" style="color: #88D600;"></i>Mi perfil</a>
							</li>

							<li class="user-body">

								<a href="salir" class="btn btn-default btn-flat"><i class="fa fa-times" style="color: red;"></i>Cerrar Sesión</a>

							</li>


						</ul>

					</li>

				</ul>

			</div>

		</div>

		<!-- Botón de navegación -->





		<!-- perfil de usuario -->



	</nav>

</header>