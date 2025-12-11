<?php
// $identificador = $_GET['cotizacion'];
// $idCotizacion = $_GET['cotizacion'] ?? 0;

$user = "grupoasi_cotizautos";
$password = "M1graci0n123";

$URI = explode("/", $_SERVER['REQUEST_URI']);

if (in_array("dev", $URI)) {
    $server = "52.15.158.65";
    $bd = "grupoasi_cotizautos_dev";
} else if (in_array("QAS", $URI)) {
    $server = "52.15.158.65";
    $bd = "grupoasi_cotizautos_qas";
} else if (in_array("Pruebas", $URI)) {
    $server = "52.15.158.65";
    $bd = "grupoasi_cotizautos_qas";
} else {
    $server = "52.15.158.65";
    $bd = "grupoasi_cotizautos_qas";
}

$conexion = mysqli_connect($server, $user, $password, $bd);
if (!$conexion) {
    die('Error de Conexión: ' . mysqli_connect_errno());
}
$conexion->set_charset("utf8");

// Información del solicitante
$infoSolicitante = "SELECT
                ts.nombre_tomador, ts.id_tomador AS documento,
                case
                    when cs.tipo_cotizacion = 1 then 'Individual'
                    when cs.tipo_cotizacion = 2 then 'Grupo familiar'
                END AS tipo_cotizacion,
                cs.fecha_cotizacion, cs.id_usuario
                FROM tomadores_cotizaciones_salud ts
                JOIN cotizaciones_salud cs ON cs.id_cotizacion = ts.id_cotizacion
                WHERE ts.id_cotizacion = $idCotizacion;";
$resInfoSolicitante = $conexion->query($infoSolicitante);
$rowInfoSolicitante = mysqli_fetch_array($resInfoSolicitante, MYSQLI_ASSOC);

// Información del asesor
$infoAsesor = "SELECT * FROM usuarios u WHERE u.id_usuario =" . $rowInfoSolicitante['id_usuario'];
$resInfoAsesor = $conexion->query($infoAsesor);
$rowInfoAsesor = mysqli_fetch_array($resInfoAsesor, MYSQLI_ASSOC);

// Información de beneficiarios
$infoBeneficiarios = "SELECT
                        a.nom_asegurado AS nombre_asegurado,
                        a.edad_asegurado, if(a.genero_asegurado = 1, 'Masculino', 'Femenino') AS genero,
                    if(a.asociado_coomeva = 1, 'Si', 'No') AS asociado_coomeva,
                    if(a.ciudad IS NULL, 'NA' , co.ciudad) AS ciudad
                    FROM asegurados_cotizaciones_salud a
                    LEFT JOIN ciudadeshogar co ON co.codigo = a.ciudad
                    WHERE a.id_cotizacion = $idCotizacion;";
$resInfoBeneficiarios = $conexion->query($infoBeneficiarios);
$rowInfoBeneficiarios = [];
while ($fila = mysqli_fetch_assoc($resInfoBeneficiarios)) {
    $rowInfoBeneficiarios[] = $fila;
}

// Información de productos seleccionados
$infoPlanes = "SELECT
CASE 
	WHEN ps.nombre_plan = 'Bienestar y Salud para Disfrutar' THEN 'Bienestar'
	WHEN ps.nombre_plan = 'Salud ideal + Emermedica' THEN 'Salud ideal'
	WHEN ps.nombre_plan = 'Ambulatorio esencial' THEN 'Amb. Esencial'
	WHEN ps.nombre_plan = 'Ambulatorio especial' THEN 'Amb, Especial'
	ELSE ps.nombre_plan
END AS nombre_abreviado,
ps.*, p.*,sa.* FROM planes_cotizaciones_salud ps
LEFT JOIN planes_salud p ON p.id_plan = ps.id_plan
LEFT JOIN aseguradoras_salud sa ON sa.id_aseguradora = p.id_aseguradora
WHERE ps.id_cotizacion = $idCotizacion AND ps.seleccionar = 1
GROUP BY ps.id_plan;";
$resInfoPlanes = $conexion->query($infoPlanes);
// Info Planes
$rowInfoPlanes = [];
while ($fila = mysqli_fetch_assoc($resInfoPlanes)) {
    $rowInfoPlanes[] = $fila;
}
$countPlanes = count($rowInfoPlanes);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Integradoor</title>
</head>

<body>
    <style>
        @font-face {
            font-family: "Letritas Molde";
            src: url("fonts/Letritas  - Molde Condensed-Medium.otf") format("opentype");
            font-weight: normal;
            font-style: normal;
        }

        html {
            font-family: "Letritas Molde", sans-serif;
        }

        body {
            font-family: "Letritas Molde", sans-serif;
            margin: 0;
            background-color: #ffffff;
            font-size: 10px;
        }

        .header {
            display: flex;
            justify-content: space-evenly;
            align-items: flex-start;
            gap: 1.5rem;
            margin: 40px auto 15px;
            width: 95%;
            /* min-height: 180px;
            max-height: 110px; */
            flex-wrap: wrap;
        }

        .info-logo {
            max-width: 25%;
            min-width: 17%;
            text-align: center;
            align-self: center;
        }

        .info-logo img {
            width: auto;
            height: auto;
        }

        .info-principal {
            background-color: #f7f7f7;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            flex: 1;
            min-width: 250px;
            max-width: 28%;
        }

        .info-header {
            background-color: #67b5fb;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            font-size: 0.9rem;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        .info-content {
            padding: 15px 20px;
            color: #333;
            /* font-size: 0.95rem; */
        }

        .info-content p {
            margin: 6px 0;
            line-height: 1.4;
        }

        .benef-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .benef-table th,
        .benef-table td {
            text-align: left;
            padding: 6px;
            border-bottom: 1px solid #ddd;
            /* font-size: 0.9rem; */
        }

        .benef-table th {
            color: #666;
        }

        .info-asesor,
        .info-principal {
            flex: 1;
            align-self: stretch;
        }

        .info-asesor,
        .info-cliente {
            max-width: 25%;
        }

        .info-beneficiarios {
            max-width: 35%;
        }

        @media (max-width: 900px) {
            .info-principal {
                max-width: 100%;
            }
        }

        .separador {
            width: 100%;
            height: 5vh;
            background-color: #67b5fb;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            align-content: center;
        }

        .hemos {
            width: 100%;
            height: 5vh;
            color: grey;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            align-content: center;
        }

        .container {
            width: 96.5%;
            display: flex;
            gap: 1px;
            align-items: flex-start;
            /* margin-left: 20px; */
            justify-content: space-evenly;
        }

        .header img {
            height: 80px;
            display: block;
            margin: 0 auto 10px;
        }

        .title-box {
            background: #606060;
            color: #fff;
            padding: 5px 6px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            min-width: 100px;
        }

        /* Columna izquierda */
        h3 {
            font-weight: 600;
            margin: 10px 0px;
            text-align: center;
        }

        .left-col {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin: 0 10px;
            min-width: 160px;
        }

        .name-box {
            background: #29b6f6;
            padding: 5px 2px;
            color: #606060e0;
            border-radius: 8px;
            margin-bottom: 10px;
            width: 72px;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
        }

        .name-clients {
            padding: 5px 2px !important;
            color: white;
            width: 100px;
        }

        /* Columna derecha */
        .right-col h3 {
            font-weight: 600;
            text-align: left;
            margin-bottom: 10px;
        }

        .value-row {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
        }

        .value-box {
            background: #f7f7f7;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: bold;
            width: 150px;
            text-align: center;
            color: #555;
        }

        .oculto {
            visibility: hidden;
            /* Oculta el contenido */
        }

        .main-container {
            display: flex;
            align-items: end;
            /* margin-left: 20px; */
        }

        .asistencias {
            /* padding: 20px 2px !important;
            width: 100%;
            border-radius: 5px;
            box-sizing: border-box; */
            align-content: center;
            width: 100%;
            border-radius: 5px;
            box-sizing: border-box;
            min-width: 164px;
            min-height: 49px;
            max-width: 164px;
            max-height: 49px;
        }
    </style>
    <header>
        <div class="header">
            <div class="info-logo">
                <img src="https://www.grupoasistencia.com/autogestionpro/Assets//images/LogoGA.png" alt="LOGO" />
            </div>

            <div class="info-asesor info-principal">
                <div class="info-header">INFORMACIÓN DEL ASESOR</div>
                <div class="info-content">
                    <p><strong>Nombre:</strong> <?php echo $rowInfoAsesor['usu_nombre'] . " " . $rowInfoAsesor['usu_apellido'] ?></p>
                    <p>
                        <strong>Correo electrónico:</strong> <?php echo $rowInfoAsesor['usu_email'] ?>
                    </p>
                    <p><strong>Número de contacto:</strong> <?php echo $rowInfoAsesor['usu_telefono'] ?></p>
                </div>
            </div>

            <div class="info-cliente info-principal">
                <div class="info-header">DATOS DEL SOLICITANTE</div>
                <div class="info-content">
                    <p><strong>Nombres y apellidos:</strong> <?php echo $rowInfoSolicitante['nombre_tomador'] ?></p>
                    <p><strong>Número de documento:</strong> <?php echo $rowInfoSolicitante['documento'] ?></p>
                    <p><strong>Tipo de cotización:</strong> <?php echo $rowInfoSolicitante['tipo_cotizacion'] ?></p>
                    <p><strong>Fecha de cotización:</strong> <?php echo $rowInfoSolicitante['fecha_cotizacion'] ?></p>
                    <p>
                        <small><em>Nota: Esta propuesta tiene una vigencia limitada</em></small>
                    </p>
                </div>
            </div>

            <div class="info-beneficiarios info-principal">
                <div class="info-header">DATOS DE BENEFICIARIOS</div>
                <div class="info-content">
                    <table class="benef-table">
                        <tr>
                            <th>Nombres y apellidos</th>
                            <th>Edad</th>
                            <th>Género</th>
                            <th>Asociado a Coomeva</th>
                            <th>Ciudad</th>
                        </tr>

                        <?php foreach ($rowInfoBeneficiarios as $beneficiarios): ?>
                            <tr>
                                <td><?= $beneficiarios['nombre_asegurado'] ?></td>
                                <td style="text-align: center;"><?= $beneficiarios['edad_asegurado'] ?></td>
                                <td style="text-align: center;"><?= $beneficiarios['genero'] ?></td>
                                <td style="text-align: center;"><?= $beneficiarios['asociado_coomeva'] ?></td>
                                <td style="text-align: center;"><?= $beneficiarios['ciudad'] ?></td>
                            </tr>
                        <?php endforeach; ?>

                    </table>
                </div>
            </div>

        </div>
    </header>
    <div class="separador">SIMULACIÓN DE PRECIOS SEGURO DE SALUD</div>
    <div class="hemos">
        Hemos cotizado <?php echo $countPlanes ?> aseguradora(s), a continuacion te presentamos un
        comparativo de precios anuales y mensuales (IVA incluido)
    </div>
    <div class="main-container">

        <div class="left-col">
            <img class="oculto" width="75 px"
                src="https://www.elempleo.com/co/sitio-empresarial/CompanySites/axa-colpatria/resources/images/logo-social.png"
                alt="Logo" /><br>
            <div class="title-box oculto">Nombre</div>
            <h3>Nombre</h3>
            <?php foreach ($rowInfoBeneficiarios as $beneficiarios): ?>
                <div class="name-box name-clients"><?php echo $beneficiarios['nombre_asegurado'] ?></div>
            <?php endforeach; ?>
            <div class="name-box name-clients" style="background: #00a8e8">Total</div>
        </div>

        <div class="container">
            <!-- Columna izquierda -->
            <?php foreach ($rowInfoPlanes as $plan):

                $totalMensual = 0;
                $totalAnual = 0;

                $planActual = $plan['id_plan'];
                // Información de productos seleccionados
                $infoprecios = "SELECT * FROM planes_cotizaciones_salud ps
                            LEFT JOIN planes_salud p ON p.id_plan = ps.id_plan
                            LEFT JOIN aseguradoras_salud sa ON sa.id_aseguradora = p.id_aseguradora
                            WHERE ps.id_cotizacion = $idCotizacion AND ps.seleccionar = 1 AND ps.id_plan = $planActual";
                $resInfoprecios = $conexion->query($infoprecios);
                // Info Precios
                        $rowInfoprecios = [];
                        while ($fila = mysqli_fetch_assoc($resInfoprecios)) {
                            $rowInfoprecios[] = $fila;
                        } ?>

                <div class="left-col">
                    <img width="75px" height="30px"
                        src="https://integradoor.com/app/<?php echo $plan['logo']; ?>"
                        alt="Logo" style="width: 75px !important; height: 30px !important;" /><br>
                    <div class="title-box"><?php echo $plan['nombre_abreviado'] ?></div>
                    <div style="display: flex; gap: 0.3rem;">
                        <div class="izquierda">
                            <h3>Mensual</h3>
                            <?php foreach ($rowInfoprecios as $precio): ?>
                                <?php $totalMensual = $totalMensual + $precio['mensual_plan'] ?>
                                <div class="name-box" style="background: #f7f7f7">$<?= number_format($precio['mensual_plan'], 0, ',', '.') ?></div>
                            <?php endforeach; ?>
                            <div class="name-box" style="background: #f7f7f7">$<?php echo number_format($totalMensual, 0, ',', '.') ?></div>

                        </div>
                        <div class="derecha">
                            <h3>Anual</h3>
                            <?php foreach ($rowInfoprecios as $precio): ?>
                                <?php $totalAnual = $totalAnual + $precio['anual_plan'] ?>
                                <div class="name-box" style="background: #f7f7f7">$<?php echo number_format($precio['anual_plan'], 0, ',', '.') ?></div>
                            <?php endforeach; ?>
                            <div class="name-box" style="background: #f7f7f7">$<?php echo number_format($totalAnual, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div style="display: flex; gap: 6rem; justify-content: center; align-items: center" class="hemos">
        <p>Si quieres comparar las coberturas y asistencias de todos los planes de salud, revisa el siguiente cuadro (Recuerda que este icono significa Si Cubre)</p>
        <div style="width: 115px; text-align: center; font-size: 10px;">
            <p>A nivel nacional desde el primer día del contrato</p>
        </div>
    </div>

    <div class="main-container">

        <div class="left-col">
            <div class="title-box oculto">Nombre</div>
            <h3>Nombre</h3>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Urgencias</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Hospitalizacion y cirujia</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Unidad de cuidado intensivo</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Consulta externa</div>
            <!-- <div class="oculto break" style="width: 100%;">salto de pagina</div> -->

            <img class="oculto" width="75 px"
                src="https://www.elempleo.com/co/sitio-empresarial/CompanySites/axa-colpatria/resources/images/logo-social.png"
                alt="Logo" /><br>
            <div class="title-box oculto">Nombre</div>
            <h3 class="oculto">Nombre</h3>

            <div class="name-box name-clients asistencias" style="background: #00a8e8">Consulta externa (medicina alternativa)</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Teleconsulta</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Exámenes de diagnóstico simple</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Exámenes de diagnóstico especializados y complejos</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Urgencias y accidentes odontológicos</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Atención odontológica general y/o especializada</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Cobertura Integral de Maternidad</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Atención integral al recién nacido</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Terapias básicas complementarias</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Terapias alternativas</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Terapias post hospitalización (físicas, respiratorias, del lenguaje o de rehabilitación cardíaca)</div>

            <div class="name-box name-clients asistencias" style="background: #00a8e8">Asistencia en viajes internacionales</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Asistencia médica domiciliaria</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Asistencia domiciliaria para urgencias odontológicas</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Prótesis y material de osteosíntesis</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Enfermedades graves</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Segunda opinión médica</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Transplantes</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Tratamiento cálculos biliares y renales</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Oncología</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">VIH-Sida</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Transporte terrestre urbano (Ambulancia terrestre)</div>

            <div class="name-box name-clients asistencias" style="background: #00a8e8">Transporte aéreo de emergencia (Ambulancia aérea)</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Medicamentos derivados de la atención de urgencias</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Medicamentos pre y post hospitalarios</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Recolección y almacenamiento de células madre para gestantes del programa</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Auxilio económico para vacunas</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Continuidad en el pago prima 6 meses por desempleo</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Exoneración del pago de la Prima por fallecimiento</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Renta diaria por hospitalización</div>
            <div class="name-box name-clients asistencias" style="background: #00a8e8">Programas especiales</div>
        </div>

        <div class="container">
            <!-- Columna izquierda -->
            <?php foreach ($rowInfoPlanes as $plan):

                $totalMensual = 0;
                $totalAnual = 0;

                $planActual = $plan['id_plan'];
                // Información de productos seleccionados
                $infoprecios = "SELECT * FROM planes_cotizaciones_salud ps
                            LEFT JOIN planes_salud p ON p.id_plan = ps.id_plan
                            LEFT JOIN aseguradoras_salud sa ON sa.id_aseguradora = p.id_aseguradora
                            WHERE ps.id_cotizacion = $idCotizacion AND ps.seleccionar = 1 AND ps.id_plan = $planActual";
                $resInfoprecios = $conexion->query($infoprecios);
                // Info Precios
                    $rowInfoprecios = [];
                    while ($fila = mysqli_fetch_assoc($resInfoprecios)) {
                        $rowInfoprecios[] = $fila;
                    } ?>

                <div class="left-col">
                    <br>
                    <div class="title-box"><?php echo $plan['nombre_abreviado'] ?></div>
                    <div style="display: block; gap: 0.3rem; width: 100%;">
                        <h3 class="oculto">Anual</h3>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <!-- <div class="oculto break" style="width: 100%;">salto de pagina</div> -->
                        <img width="75px" height="30px"
                            src="https://integradoor.com/app/<?php echo $plan['logo']; ?>"
                            alt="Logo" style="width: 75px !important; height: 30px !important; margin-top: 19px;" /><br>
                        <div class="title-box"><?php echo $plan['nombre_abreviado'] ?></div>
                        <h3 class="oculto">Nombre</h3>

                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                        <div class="name-box name asistencias" style="background: #f7f7f7">Cobertura pendiente</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        document.body.style.zoom = "120%";
    </script>
</body>

</html>