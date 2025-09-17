<?php
require_once "../../../../config/QAStoPRD.php";
$enlace->set_charset("utf8mb4");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Asesor
{
    public $asesor_id;
    public $asesor;
    public $fecha_ingreso;
    public $estado_usuario;
    public $estado_freelance;
    public $categoria_freelance;
    public $analista;
    public $meses = [
        'mes1' => ['cotizaciones' => 0, 'negocios' => 0, 'prima_emitida' => 0],
        'mes2' => ['cotizaciones' => 0, 'negocios' => 0, 'prima_emitida' => 0],
        'mes3' => ['cotizaciones' => 0, 'negocios' => 0, 'prima_emitida' => 0],
        'mes4' => ['cotizaciones' => 0, 'negocios' => 0, 'prima_emitida' => 0]
    ];
}

// Obtener parámetros
$anio     = $_POST["anio"] ?? null;
$mes      = $_POST["mes"] ?? null;
$asesor   = $_POST["asesor"] ?? null;
$analista = $_POST["analista"] ?? null;
$ramo     = $_POST["ramo"] ?? null;
$estado   = $_POST["estado"] ?? null;
$categoria   = $_POST["categoria"] ?? null;
error_log("Estado recibido: " . var_export($estado, true));

// =======================================
// 1. Determinar rango de fechas
// =======================================
function getRangoFechas($anio = null, $mes = null)
{
    $fechas = [];
    $hoy = new DateTime();

    // Normalizar año y mes si vienen vacíos
    $anio = !empty($anio) ? $anio : null;
    $mes  = !empty($mes)  ? $mes  : null;

    // Lógica de selección de fecha base
    if (!is_null($anio) && !is_null($mes)) {
        $fechaBase = DateTime::createFromFormat('Y-m', "$anio-$mes");
    } elseif (!is_null($mes)) {
        $fechaBase = DateTime::createFromFormat('Y-m', $hoy->format('Y') . "-$mes");
    } elseif (!is_null($anio)) {
        $fechaBase = DateTime::createFromFormat('Y-m', "$anio-" . $hoy->format('m'));
    } else {
        $fechaBase = $hoy;
    }

    if (!$fechaBase) {
        throw new Exception("Error al crear la fecha base con año: $anio y mes: $mes");
    }

    // Armar rangos de 3 meses
    for ($i = 0; $i < 4; $i++) {
        $start = (clone $fechaBase)->modify("-$i month")->modify('first day of this month')->setTime(0, 0, 0);
        $end   = (clone $fechaBase)->modify("-$i month")->modify('last day of this month')->setTime(23, 59, 59);
        $fechas["mes" . ($i + 1)] = [
            'inicio' => $start->format('Y-m-d H:i:s'),
            'fin'    => $end->format('Y-m-d H:i:s')
        ];
    }

    return $fechas;
}

// =======================================
// 2. Obtener asesores
// =======================================
function getAsesores($asesor = null, $analista = null, $estado = null, $categoria = null)
{
    global $enlace;

    $sql = "
       SELECT 
            u.id_usuario,
            CONCAT(u.usu_nombre, ' ', u.usu_apellido) AS asesor,
            DATE_FORMAT(u.usu_fch_creacion, '%d/%m/%Y') AS fecha_ingreso,
            CASE WHEN u.usu_estado = 1 THEN 'Activo' ELSE 'Bloqueado' END AS estado_usuario,
            af.nombre_analista AS analista,
            u.estado_freelance,
            u.categoria_freelance
        FROM usuarios u
        LEFT JOIN analistas_freelances af ON af.id_usuario = u.usu_documento
        WHERE u.id_rol IN (19)";

    $params = [];
    $types  = "";

    if ($analista) {
        $sql .= " AND af.id_analista = ?";
        $params[] = $analista;
        $types .= "s";
    }

    if ($asesor) {
        $sql .= " AND u.id_usuario = ?";
        $params[] = $asesor;
        $types .= "i";
    }

    if ($estado !== null && in_array((string)$estado, ['0', '1'], true)) {
        $sql .= " AND u.usu_estado = ?";
        $params[] = (int)$estado;
        $types   .= "i";
    }

    if ($categoria) {
        $sql .= " AND u.categoria_freelance = ?";
        $params[] = $categoria;
        $types .= "s";
    }

    $stmt = prepareQuery($sql, $types, $params);
    if (!$stmt) {
        die("Error preparando la consulta, revisa logs");
    }
    $stmt->execute();

    // Definir las variables para el bind_result
    $id_usuario = null;
    $asesor = null;
    $fecha_ingreso = null;
    $estado_usuario = null;
    $analista = null;
    $estado_freelance = null;
    $categoria_freelance = null;

    // Vincular los resultados de la consulta con las variables definidas
    $stmt->bind_result($id_usuario, $asesor, $fecha_ingreso, $estado_usuario, $analista, $estado_freelance, $categoria_freelance);
    $asesores = [];
    while ($stmt->fetch()) {
        $asesorObj = new Asesor();
        $asesorObj->asesor_id = $id_usuario;
        $asesorObj->asesor = $asesor;
        $asesorObj->fecha_ingreso = $fecha_ingreso;
        $asesorObj->estado_usuario = $estado_usuario;
        $asesorObj->analista = $analista ?? '';
        $asesorObj->estado_freelance = $estado_freelance ?? '';
        $asesorObj->categoria_freelance = $categoria_freelance ?? '';
        $asesores[$id_usuario] = $asesorObj;
    }

    return $asesores;
}

// Función para preparar la consulta
// function prepareQuery($sql, $types = '', $params = []) {
//     global $enlace;
//     $stmt = $enlace->prepare($sql);
//     if (!empty($types)) {
//         $stmt->bind_param($types, ...$params);
//     }
//     return $stmt;
// }
function prepareQuery($sql, $types = '', $params = [])
{
    global $enlace;
    $stmt = $enlace->prepare($sql);

    if ($stmt === false) {
        error_log("Error al preparar SQL: " . $enlace->error);
        error_log("Consulta: " . $sql);
        return false;
    }

    if (!empty($types)) {
        if (!$stmt->bind_param($types, ...$params)) {
            error_log("Error al hacer bind_param: " . $stmt->error);
            return false;
        }
    }

    return $stmt;
}

// =======================================
// 3. Contar cotizaciones agrupadas
// =======================================
function contarCotizacionesAgrupadas($rangoFechas, $ramo = null)
{
    global $enlace;

    $data = [];

    foreach ($rangoFechas as $keyMes => $rango) {
        $fechaInicio = $rango['inicio'];
        $fechaFin    = $rango['fin'];

        // Caso para ramo '2' (cotizaciones_salud)
        if ($ramo === '2') {
            $sql = "SELECT id_usuario, COUNT(*) as total FROM cotizaciones_salud 
                    WHERE fecha_cotizacion BETWEEN ? AND ? 
                    GROUP BY id_usuario";
            $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
            $stmt->execute();

            // Definir las variables para bind_result
            $id_usuario = null;
            $total = null;

            // Vincular los resultados con bind_result
            $stmt->bind_result($id_usuario, $total);
            while ($stmt->fetch()) {
                $data[$id_usuario][$keyMes] = $total;
            }

            // Caso para ramo '3' (cotizaciones_assistcard)
        } elseif ($ramo === '3') {
            $sql = "SELECT id_usuario, COUNT(*) as total FROM cotizaciones_assistcard 
                    WHERE fecha_cot BETWEEN ? AND ? 
                    GROUP BY id_usuario";
            $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
            $stmt->execute();

            // Definir las variables para bind_result
            $id_usuario = null;
            $total = null;

            // Vincular los resultados con bind_result
            $stmt->bind_result($id_usuario, $total);
            while ($stmt->fetch()) {
                $data[$id_usuario][$keyMes] = $total;
            }

            // Caso por defecto (varias tablas)
        } else {
            $tablas = [
                ['tabla' => 'cotizaciones', 'campo_fecha' => 'cot_fch_cotizacion'],
                ['tabla' => 'cotizaciones_salud', 'campo_fecha' => 'fecha_cotizacion'],
                ['tabla' => 'cotizaciones_assistcard', 'campo_fecha' => 'fecha_cot'],
            ];

            foreach ($tablas as $t) {
                $sql = "SELECT id_usuario, COUNT(*) as total FROM {$t['tabla']} 
                        WHERE {$t['campo_fecha']} BETWEEN ? AND ? 
                        GROUP BY id_usuario";
                $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
                $stmt->execute();

                // Definir las variables para bind_result
                $id_usuario = null;
                $total = null;

                // Vincular los resultados con bind_result
                $stmt->bind_result($id_usuario, $total);
                while ($stmt->fetch()) {
                    if (!isset($data[$id_usuario][$keyMes])) {
                        $data[$id_usuario][$keyMes] = 0;
                    }
                    $data[$id_usuario][$keyMes] += $total;
                }
            }
        }
    }

    return $data;
}


// =======================================
// 4. Contar negocios agrupados
// =======================================
function contarNegociosAgrupados($rangoFechas, $ramo = null)
{
    global $enlace;
    $data = [];


    foreach ($rangoFechas as $keyMes => $rango) {
        $fechaInicio = $rango['inicio'];
        $fechaFin    = $rango['fin'];



        $sql = "SELECT 
            id_user_freelance, 
            COUNT(*) as total, 
            SUM(COALESCE(prima_sin_iva, 0)) AS total_prima
        FROM oportunidades 
        WHERE fecha_expedicion BETWEEN ? AND ? AND estado = 'Emitida'";

        // Añadir condiciones adicionales dependiendo del valor de $ramo, solo si no es null
        if ($ramo === '1') {
            $sql .= " AND ramo IN ('Automoviles', 'Motos', 'Pesados')";
        } elseif ($ramo === '2') {
            $sql .= " AND ramo IN ('Salud')";
        } elseif ($ramo === '3') {
            $sql .= " AND ramo IN ('Asistencia en viajes')";
        }

        // Agrupar por id_user_freelance
        $sql .= " GROUP BY id_user_freelance";


        // Preparar y ejecutar la consulta
        $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
        $stmt->execute();

        // Definir las variables para el bind_result
        $id_user_freelance = null;
        $total_negocios = null;
        $total_prima = null;
        // Vincular los resultados de la consulta con las variables definidas
        $stmt->bind_result($id_user_freelance, $total_negocios, $total_prima);
        while ($stmt->fetch()) {
            $data[$id_user_freelance][$keyMes] = [
                'negocios' => (int)$total_negocios,
                'prima_emitida' => (int)$total_prima
            ];
        }
    }

    return $data;
}

// =======================================
// 5. Ejecutar y armar respuesta
// =======================================
$fechasMeses = getRangoFechas($anio, $mes);
$asesores    = getAsesores($asesor, $analista, $estado, $categoria);

$cotizacionesData = contarCotizacionesAgrupadas($fechasMeses, $ramo);
$negociosYPrimasData = contarNegociosAgrupados($fechasMeses, $ramo);

foreach ($asesores as $asesorObj) {
    $id = $asesorObj->asesor_id;

    foreach ($fechasMeses as $keyMes => $rango) {
        $asesorObj->meses[$keyMes]['cotizaciones']   = $cotizacionesData[$id][$keyMes] ?? 0;
        $asesorObj->meses[$keyMes]['negocios']       = $negociosYPrimasData[$id][$keyMes]['negocios'] ?? 0;
        $asesorObj->meses[$keyMes]['prima_emitida']  = $negociosYPrimasData[$id][$keyMes]['prima_emitida'] ?? 0;
        $asesorObj->meses[$keyMes]['fechas']         = $rango;
    }
}

// Salida final
echo json_encode([
    'fechasBusqueda' => $fechasMeses,
    'asesores' => array_values($asesores)
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
