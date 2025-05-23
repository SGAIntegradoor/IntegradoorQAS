<?php
require_once "../../../../config/QAStoPRD.php";

$enlace->set_charset("utf8mb4");

class Asesor {
    public $asesor_id;
    public $asesor;
    public $fecha_ingreso;
    public $estado_usuario;
    public $analista;
    public $meses = [
        'mes1' => ['cotizaciones' => 0, 'negocios' => 0],
        'mes2' => ['cotizaciones' => 0, 'negocios' => 0],
        'mes3' => ['cotizaciones' => 0, 'negocios' => 0]
    ];
}

// Obtener parámetros
$anio     = $_POST["anio"] ?? null;
$mes      = $_POST["mes"] ?? null;
$asesor   = $_POST["asesor"] ?? null;
$analista = $_POST["analista"] ?? null;
$ramo     = $_POST["ramo"] ?? null;

// =======================================
// 1. Determinar rango de fechas
// =======================================
function getRangoFechas($anio = null, $mes = null) {
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
    for ($i = 0; $i < 3; $i++) {
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
function getAsesores($asesor = null, $analista = null) {
    global $enlace;

    $sql = "SELECT u.id_usuario, CONCAT(u.usu_nombre, ' ', u.usu_apellido) AS asesor, 
                   DATE_FORMAT(u.usu_fch_creacion, '%d/%m/%Y') AS fecha_ingreso, 
                   CASE WHEN u.usu_estado = 1 THEN 'Activo' ELSE 'Inactivo' END AS estado_usuario, 
                   af.nombre_analista AS analista 
            FROM usuarios u 
            LEFT JOIN analistas_freelances af ON af.id_usuario = u.usu_documento 
            WHERE u.id_rol IN (19, 12, 11, 10, 1)";
    
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

    $stmt = prepareQuery($sql, $types, $params);
    $stmt->execute();

    // Definir las variables para el bind_result
    $id_usuario = null;
    $asesor = null;
    $fecha_ingreso = null;
    $estado_usuario = null;
    $analista = null;

    // Vincular los resultados de la consulta con las variables definidas
    $stmt->bind_result($id_usuario, $asesor, $fecha_ingreso, $estado_usuario, $analista);
    $asesores = [];
    while ($stmt->fetch()) {
        $asesorObj = new Asesor();
        $asesorObj->asesor_id = $id_usuario;
        $asesorObj->asesor = $asesor;
        $asesorObj->fecha_ingreso = $fecha_ingreso;
        $asesorObj->estado_usuario = $estado_usuario;
        $asesorObj->analista = $analista ?? '';
        $asesores[$id_usuario] = $asesorObj;
    }

    $stmt->close();
    return $asesores;
}

// Función para preparar la consulta
function prepareQuery($sql, $types = '', $params = []) {
    global $enlace;

    // Se intanta preparar la consulta 
    $stmt = $enlace->prepare($sql);
    if ($stmt === false) {
        // Si falla se envia una exepcion para que pare la ejecucion pero nos diga donde fallo
        throw new Exception("MySQL prepare failed: " . $enlace->error);
    }

    // Si hay tipos y parámetros, los enlazamos
    if (!empty($types) && !empty($params)) {
        // bind_param devuelve false si algo sale mal entonces debemos validar cada iteracion del bind_param
        // y si falla se lanza una exepcion para que pare la ejecucion pero nos diga donde fallo
        if (! $stmt->bind_param($types, ...$params)) {
            throw new Exception("MySQL bind_param failed: " . $stmt->error);
        }
    }

    return $stmt;
}

// =======================================
// 3. Contar cotizaciones agrupadas
// =======================================
// function contarCotizacionesAgrupadas($rangoFechas, $ramo = null) {
//     global $enlace;

//     $data = [];

//     foreach ($rangoFechas as $keyMes => $rango) {
//         $condiciones = "";
//         $fechaInicio = $rango['inicio'];
//         $fechaFin    = $rango['fin'];

//         if ($ramo === '2') {
//             $sql = "SELECT id_usuario, COUNT(*) as total FROM cotizaciones_salud 
//                     WHERE fecha_cotizacion BETWEEN ? AND ?
//                     GROUP BY id_usuario";
//             $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
//             $stmt->execute();
//             $res = $stmt->get_result();
//             while ($row = $res->fetch_assoc()) {
//                 $data[$row['id_usuario']][$keyMes] = $row['total'];
//             }

//         } elseif ($ramo === '3') {
//             $sql = "SELECT id_usuario, COUNT(*) as total FROM cotizaciones_assistcard 
//                     WHERE fecha_cot BETWEEN ? AND ?
//                     GROUP BY id_usuario";
//             $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
//             $stmt->execute();
//             $res = $stmt->get_result();
//             while ($row = $res->fetch_assoc()) {
//                 $data[$row['id_usuario']][$keyMes] = $row['total'];
//             }

//         } else {
//             $tablas = [
//                 ['tabla' => 'cotizaciones', 'campo_fecha' => 'cot_fch_cotizacion'],
//                 ['tabla' => 'cotizaciones_salud', 'campo_fecha' => 'fecha_cotizacion'],
//                 ['tabla' => 'cotizaciones_assistcard', 'campo_fecha' => 'fecha_cot'],
//             ];

//             foreach ($tablas as $t) {
//                 $sql = "SELECT id_usuario, COUNT(*) as total FROM {$t['tabla']} 
//                         WHERE {$t['campo_fecha']} BETWEEN ? AND ?
//                         GROUP BY id_usuario";
//                 $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
//                 $stmt->execute();
//                 $res = $stmt->get_result();
//                 while ($row = $res->fetch_assoc()) {
//                     if (!isset($data[$row['id_usuario']][$keyMes])) {
//                         $data[$row['id_usuario']][$keyMes] = 0;
//                     }
//                     $data[$row['id_usuario']][$keyMes] += $row['total'];
//                 }
//             }
//         }
//     }

//     return $data;
// }

// =======================================
// 4. Contar negocios agrupados
// =======================================
// function contarNegociosAgrupados($rangoFechas, $ramo = null) {
//     global $enlace;
//     $data = [];

//     foreach ($rangoFechas as $keyMes => $rango) {
//         $fechaInicio = $rango['inicio'];
//         $fechaFin    = $rango['fin'];

//         $sql = "SELECT id_user_freelance, COUNT(*) as total FROM oportunidades 
//                 WHERE fecha_expedicion BETWEEN ? AND ? AND estado = 'Emitida'";

//         if ($ramo == '1') {
//             $sql .= " AND ramo IN ('Automoviles', 'Motos', 'Pesados')";
//         } elseif ($ramo == '2') {
//             $sql .= " AND ramo IN ('Salud', 'vida deudor')";
//         } elseif ($ramo == '3') {
//             $sql .= " AND ramo IN ('Asistencia en viajes')";
//         }

//         $sql .= " GROUP BY id_user_freelance";

//         $stmt = prepareQuery($sql, 'ss', [$fechaInicio, $fechaFin]);
//         $stmt->execute();
//         $res = $stmt->get_result();
//         while ($row = $res->fetch_assoc()) {
//             $data[$row['id_user_freelance']][$keyMes] = $row['total'];
//         }
//     }

//     return $data;
// }

function contarCotizacionesAgrupadas($rangoFechas, $ramo = null) {
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
            $stmt->close();

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
            $stmt->close();

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
                $stmt->close();
            }
        }
    }

    return $data;
}


// =======================================
// 4. Contar negocios agrupados
// =======================================
function contarNegociosAgrupados($rangoFechas, $ramo = null) {
    global $enlace;
    $data = [];


    foreach ($rangoFechas as $keyMes => $rango) {
        $fechaInicio = $rango['inicio'];
        $fechaFin    = $rango['fin'];



        // Consulta SQL básica
        $sql = "SELECT id_user_freelance, COUNT(*) as total FROM oportunidades 
                WHERE fecha_expedicion BETWEEN ? AND ? AND estado = 'Emitida'";

        // Añadir condiciones adicionales dependiendo del valor de $ramo, solo si no es null
        if ($ramo === '1') {
            $sql .= " AND ramo IN ('Automoviles', 'Motos', 'Pesados')";
        } elseif ($ramo === '2') {
            $sql .= " AND ramo IN ('Salud', 'vida deudor')";
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
        $total = null;

        // Vincular los resultados de la consulta con las variables definidas
        $stmt->bind_result($id_user_freelance, $total);
        while ($stmt->fetch()) {
            $data[$id_user_freelance][$keyMes] = $total;
        }

        $stmt->close();
    }

    return $data;
}



// =======================================
// 5. Ejecutar y armar respuesta
// =======================================
$fechasMeses = getRangoFechas($anio, $mes);
$asesores    = getAsesores($asesor, $analista);

$cotizacionesData = contarCotizacionesAgrupadas($fechasMeses, $ramo);
$negociosData     = contarNegociosAgrupados($fechasMeses, $ramo);
// Llenar datos en cada asesor
foreach ($asesores as $asesorObj) {
    $id = $asesorObj->asesor_id;
    foreach ($fechasMeses as $keyMes => $rango) {
        $asesorObj->meses[$keyMes]['cotizaciones'] = $cotizacionesData[$id][$keyMes] ?? 0;
        $asesorObj->meses[$keyMes]['negocios'] = $negociosData[$id][$keyMes] ?? 0;
        $asesorObj->meses[$keyMes]['fechas'] = $rango;
    }
}

// Salida final
echo json_encode([ 
    'fechasBusqueda' => $fechasMeses,
    'asesores' => array_values($asesores)
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
